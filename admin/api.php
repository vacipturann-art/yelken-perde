<?php
/* Yönetim paneli JSON API'si. Tüm yazma işlemleri oturum + CSRF anahtarı ister. */

require_once dirname(__DIR__) . '/inc/auth.php';

const YP_MAX_IMAGE = 25 * 1024 * 1024;
const YP_MAX_VIDEO = 512 * 1024 * 1024;

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function out(array $data, int $code = 200): void {
  http_response_code($code);
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

function fail(string $message, int $code = 400): void {
  out(['error' => $message], $code);
}

set_exception_handler(function (Throwable $e) {
  fail($e instanceof RuntimeException ? $e->getMessage() : 'Beklenmeyen bir sunucu hatası oluştu.', 500);
});

yp_session_start();

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$in = [];
if ($method === 'POST' && stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === 0) {
  $in = json_decode((string)file_get_contents('php://input'), true);
  if (!is_array($in)) fail('Geçersiz istek.');
}

if ($method === 'POST' && !yp_csrf_ok()) {
  fail('Oturum anahtarı geçersiz. Sayfayı yenileyip tekrar deneyin.', 403);
}

/* ─────────── Oturum gerektirmeyen işlemler ─────────── */

if ($action === 'session') {
  out(['logged_in' => yp_logged_in(), 'needs_setup' => !yp_auth_exists(), 'csrf' => $_SESSION['csrf']]);
}

if ($action === 'setup' && $method === 'POST') {
  if (yp_auth_exists()) fail('Panel şifresi zaten oluşturulmuş.', 409);
  $pw = (string)($in['password'] ?? '');
  if (strlen($pw) < 8) fail('Şifre en az 8 karakter olmalı.');
  yp_auth_set($pw);
  yp_login_succeeded();
  out(['ok' => true, 'csrf' => $_SESSION['csrf']]);
}

if ($action === 'login' && $method === 'POST') {
  $wait = yp_login_locked_for();
  if ($wait > 0) fail('Çok fazla hatalı deneme. ' . ceil($wait / 60) . ' dakika sonra tekrar deneyin.', 429);
  if (!yp_auth_verify((string)($in['password'] ?? ''))) {
    yp_login_failed();
    usleep(400000);
    fail('Şifre hatalı.', 401);
  }
  yp_login_succeeded();
  out(['ok' => true, 'csrf' => $_SESSION['csrf']]);
}

if (!yp_logged_in()) fail('Oturum kapandı. Lütfen tekrar giriş yapın.', 401);

/* ─────────── Yardımcılar ─────────── */

function text($value, int $max): string {
  $s = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string)$value) ?? '');
  return preg_match('/^.{0,' . $max . '}/us', $s, $m) ? $m[0] : '';
}

// Sadece site içindeki medya yollarına izin verilir (dış adres / javascript: vb. kabul edilmez)
function media_path($value, bool $videoOk = false): string {
  $p = trim((string)$value);
  if ($p === '') return '';
  $ext = $videoOk ? 'jpe?g|png|webp|mp4|webm|mov' : 'jpe?g|png|webp';
  if (strpos($p, '..') !== false || !preg_match('~^(uploads|images)/[A-Za-z0-9/_.-]+\.(' . $ext . ')$~i', $p)) {
    fail('Geçersiz dosya yolu: ' . $p);
  }
  return $p;
}

// Hiçbir içerikte kullanılmayan yüklenmiş dosyaları siler
function cleanup(array $paths): void {
  $all = json_encode([yp_read('gallery'), yp_read('products'), yp_read('blog')], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  foreach (array_unique(array_filter($paths)) as $p) {
    if (strpos($p, 'uploads/') !== 0 || strpos($p, '..') !== false) continue;
    if (strpos($all, $p) !== false) continue;
    $file = YP_ROOT . '/' . $p;
    if (is_file($file)) @unlink($file);
  }
}

function with_lock(callable $fn) {
  if (!is_dir(YP_DATA)) mkdir(YP_DATA, 0755, true);
  $fp = fopen(YP_DATA . '/.lock', 'c');
  flock($fp, LOCK_EX);
  try {
    return $fn();
  } finally {
    flock($fp, LOCK_UN);
    fclose($fp);
  }
}

function ini_bytes(string $v): int {
  $v = trim($v);
  if ($v === '' || $v === '-1') return PHP_INT_MAX;
  $n = (int)$v;
  switch (strtolower(substr($v, -1))) {
    case 'g': $n *= 1024;
    case 'm': $n *= 1024;
    case 'k': $n *= 1024;
  }
  return $n;
}

function chunk_size(): int {
  $limit = min(ini_bytes((string)ini_get('upload_max_filesize')), ini_bytes((string)ini_get('post_max_size')));
  return max(256 * 1024, min(4 * 1024 * 1024, $limit - 128 * 1024));
}

function find_index(array $list, string $key, $value): int {
  foreach ($list as $i => $row) if (($row[$key] ?? null) === $value) return $i;
  return -1;
}

function gallery_item(array $src, array $old = []): array {
  $type = $src['type'] ?? ($old['type'] ?? 'image');
  if (!in_array($type, ['image', 'video', 'youtube'], true)) fail('Geçersiz içerik türü.');

  $item = [
    'id'      => $old['id'] ?? ('g' . bin2hex(random_bytes(5))),
    'type'    => $type,
    'src'     => '',
    'thumb'   => media_path($src['thumb'] ?? ($old['thumb'] ?? '')),
    'title'   => text($src['title'] ?? ($old['title'] ?? ''), 120),
    'label'   => text($src['label'] ?? ($old['label'] ?? ''), 80),
    'caption' => text($src['caption'] ?? ($old['caption'] ?? ''), 240),
    'alt'     => text($src['alt'] ?? ($old['alt'] ?? ''), 160),
    'categories' => [],
    'created' => $old['created'] ?? date('c'),
  ];

  if ($type === 'youtube') {
    $item['youtube'] = yp_youtube_id((string)($src['youtube'] ?? ($old['youtube'] ?? '')));
    if ($item['youtube'] === '') fail('Geçerli bir YouTube bağlantısı girin.');
  } else {
    $item['src'] = media_path($src['src'] ?? ($old['src'] ?? ''), $type === 'video');
    if ($item['src'] === '') fail('Dosya eksik.');
  }

  // Panelde gösterilmeyen eski kategoriler korunur
  $cats = isset($src['categories']) ? (array)$src['categories'] : ($old['categories'] ?? []);
  $keep = array_diff($old['categories'] ?? [], array_keys(YP_CATEGORIES));
  $item['categories'] = array_values(array_unique(array_merge(
    array_values(array_intersect(array_map('strval', $cats), array_keys(YP_CATEGORIES))),
    array_values($keep)
  )));
  return $item;
}

function set_showcase(string $id, bool $on): void {
  $list = yp_read('showcase');
  $list = array_values(array_filter($list, function ($x) use ($id) { return $x !== $id; }));
  if ($on) $list[] = $id;
  yp_write('showcase', $list);
}

function blog_post(array $src, array $posts): array {
  $id = (string)($src['id'] ?? '');
  $i = $id !== '' ? find_index($posts, 'id', $id) : -1;
  $old = $i >= 0 ? $posts[$i] : [];

  $title = text($src['title'] ?? '', 160);
  if ($title === '') fail('Yazı başlığı boş olamaz.');

  $slug = yp_slugify((string)($src['slug'] ?? '') ?: $title);
  if ($slug === '') fail('Yazı adresi oluşturulamadı; başlığı kontrol edin.');
  $base = $slug;
  for ($n = 2; ; $n++) {
    $clash = find_index($posts, 'slug', $slug);
    if ($clash < 0 || $clash === $i) break;
    $slug = "$base-$n";
  }

  $date = (string)($src['date'] ?? '');
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $date = date('Y-m-d');

  return [
    'id'        => $old['id'] ?? ('b' . bin2hex(random_bytes(5))),
    'slug'      => $slug,
    'title'     => $title,
    'category'  => text($src['category'] ?? '', 60),
    'date'      => $date,
    'cover'     => media_path($src['cover'] ?? ''),
    'excerpt'   => text($src['excerpt'] ?? '', 400),
    'content'   => str_replace("\r\n", "\n", text($src['content'] ?? '', 60000)),
    'published' => !empty($src['published']),
  ];
}

/* ─────────── Dosya yükleme (parçalı) ───────────
   Paylaşımlı hostinglerde yükleme sınırı genelde 2–64 MB'dir. Panel büyük
   videoları küçük parçalar halinde gönderir, sunucu bunları birleştirir. */

function sniff(string $file): string {
  $h = (string)file_get_contents($file, false, null, 0, 16);
  if (strncmp($h, "\xFF\xD8\xFF", 3) === 0) return 'jpg';
  if (strncmp($h, "\x89PNG\r\n\x1A\n", 8) === 0) return 'png';
  if (substr($h, 0, 4) === 'RIFF' && substr($h, 8, 4) === 'WEBP') return 'webp';
  if (strncmp($h, "\x1A\x45\xDF\xA3", 4) === 0) return 'webm';
  $box = substr($h, 4, 4);
  if ($box === 'ftyp') return substr($h, 8, 4) === 'qt  ' ? 'mov' : 'mp4';
  if (in_array($box, ['moov', 'mdat', 'wide', 'free'], true)) return 'mov';
  return '';
}

function handle_upload(): void {
  $id = (string)($_POST['upload_id'] ?? '');
  $index = (int)($_POST['index'] ?? -1);
  $total = (int)($_POST['total'] ?? 0);
  if (!preg_match('/^[a-f0-9]{32}$/', $id) || $total < 1 || $total > 5000 || $index < 0 || $index >= $total) {
    fail('Geçersiz yükleme isteği.');
  }

  $f = $_FILES['chunk'] ?? null;
  if (!$f || ($f['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $err = $f['error'] ?? UPLOAD_ERR_NO_FILE;
    fail($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE
      ? 'Sunucunun yükleme sınırı aşıldı.' : 'Dosya parçası alınamadı (kod ' . $err . ').');
  }

  $tmpDir = YP_DATA . '/tmp';
  if (!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);
  $part = "$tmpDir/$id.part";
  $expected = $_SESSION['uploads'][$id] ?? 0;

  // Son parçanın yanıtı bağlantı kopmasıyla kaybolduysa aynı sonucu tekrar ver
  if (isset($_SESSION['uploaded'][$id])) out($_SESSION['uploaded'][$id]);

  if ($index === 0) {
    // Yarım kalmış eski yüklemeleri temizle
    foreach (glob("$tmpDir/*.part") ?: [] as $old) {
      if (filemtime($old) < time() - 86400) @unlink($old);
    }
    $expected = 0;
  }
  if ($index < $expected) out(['done' => false]);  // Tekrar gönderilen parça (bağlantı kopması) — zaten alındı
  if ($index !== $expected) fail('Dosya parçaları sırasız geldi, yüklemeyi tekrar başlatın.');

  $src = fopen($f['tmp_name'], 'rb');
  $dst = fopen($part, $index === 0 ? 'wb' : 'ab');
  if (!$src || !$dst) fail('Geçici dosya yazılamadı. data/ klasörünün yazma iznini kontrol edin.', 500);
  stream_copy_to_stream($src, $dst);
  fclose($src);
  fclose($dst);
  clearstatcache(true, $part);

  if (filesize($part) > YP_MAX_VIDEO) {
    @unlink($part);
    unset($_SESSION['uploads'][$id]);
    fail('Dosya çok büyük (en fazla 512 MB).');
  }

  $_SESSION['uploads'][$id] = $index + 1;
  if ($index < $total - 1) out(['done' => false]);

  unset($_SESSION['uploads'][$id]);
  $ext = sniff($part);
  $size = filesize($part);
  $isVideo = in_array($ext, ['mp4', 'webm', 'mov'], true);

  if ($ext === '' || (!$isVideo && @getimagesize($part) === false)) {
    @unlink($part);
    fail('Desteklenmeyen dosya türü. JPG, PNG, WEBP fotoğraf ya da MP4, MOV, WEBM video yükleyin.');
  }
  if (!$isVideo && $size > YP_MAX_IMAGE) {
    @unlink($part);
    fail('Fotoğraf çok büyük (en fazla 25 MB).');
  }

  $rel = 'uploads/' . date('Y/m');
  if (!is_dir(YP_ROOT . "/$rel") && !mkdir(YP_ROOT . "/$rel", 0755, true)) {
    @unlink($part);
    fail('uploads/ klasörüne yazılamıyor. Klasör izinlerini kontrol edin.', 500);
  }
  $path = "$rel/" . bin2hex(random_bytes(8)) . ".$ext";
  if (!rename($part, YP_ROOT . "/$path")) fail('Dosya kaydedilemedi.', 500);
  @chmod(YP_ROOT . "/$path", 0644);

  $result = ['done' => true, 'path' => $path, 'kind' => $isVideo ? 'video' : 'image', 'size' => $size];
  $_SESSION['uploaded'] = array_slice(($_SESSION['uploaded'] ?? []) + [$id => $result], -20, null, true);
  out($result);
}

/* ─────────── İşlemler ─────────── */

switch ($action) {

  case 'state':
    out([
      'gallery'    => yp_gallery(),
      'showcase'   => yp_read('showcase'),
      'products'   => array_map(function ($k) { return ['key' => $k, 'name' => YP_PRODUCTS[$k]] + yp_product($k); }, array_keys(YP_PRODUCTS)),
      'blog'       => yp_posts(false),
      'categories' => YP_CATEGORIES,
      'limits'     => ['chunk' => chunk_size(), 'image' => YP_MAX_IMAGE, 'video' => YP_MAX_VIDEO],
    ]);

  case 'logout':
    $_SESSION = [];
    session_regenerate_id(true);
    out(['ok' => true]);

  case 'upload':
    if ($method !== 'POST') fail('POST gerekli.', 405);
    handle_upload();

  case 'gallery_add':
    $added = with_lock(function () use ($in) {
      $gallery = yp_gallery();
      $new = [];
      foreach ((array)($in['items'] ?? []) as $src) $new[] = gallery_item((array)$src);
      // Yeni öğeler galerinin başına eklenir
      yp_write('gallery', array_merge($new, $gallery));
      if (!empty($in['showcase'])) {
        foreach ($new as $item) set_showcase($item['id'], true);
      }
      return $new;
    });
    out(['ok' => true, 'items' => $added]);

  case 'gallery_update':
    $item = with_lock(function () use ($in) {
      $gallery = yp_gallery();
      $i = find_index($gallery, 'id', (string)($in['item']['id'] ?? ''));
      if ($i < 0) fail('Öğe bulunamadı.', 404);
      $old = $gallery[$i];
      $gallery[$i] = gallery_item((array)$in['item'], $old);
      yp_write('gallery', $gallery);
      if (isset($in['item']['showcase'])) set_showcase($old['id'], (bool)$in['item']['showcase']);
      cleanup([$old['thumb'] ?? '', $old['src'] ?? '']);
      return $gallery[$i];
    });
    out(['ok' => true, 'item' => $item]);

  case 'gallery_delete':
    with_lock(function () use ($in) {
      $gallery = yp_gallery();
      $i = find_index($gallery, 'id', (string)($in['id'] ?? ''));
      if ($i < 0) fail('Öğe bulunamadı.', 404);
      $old = $gallery[$i];
      array_splice($gallery, $i, 1);
      yp_write('gallery', $gallery);
      set_showcase($old['id'], false);
      cleanup([$old['src'] ?? '', $old['thumb'] ?? '']);
    });
    out(['ok' => true]);

  case 'gallery_order':
    with_lock(function () use ($in) {
      $byId = [];
      foreach (yp_gallery() as $item) $byId[$item['id']] = $item;
      $ordered = [];
      foreach ((array)($in['ids'] ?? []) as $id) {
        if (isset($byId[$id])) { $ordered[] = $byId[$id]; unset($byId[$id]); }
      }
      yp_write('gallery', array_merge($ordered, array_values($byId)));
    });
    out(['ok' => true]);

  case 'showcase_save':
    with_lock(function () use ($in) {
      $valid = array_column(yp_gallery(), 'id');
      $ids = array_values(array_unique(array_intersect(array_map('strval', (array)($in['ids'] ?? [])), $valid)));
      yp_write('showcase', $ids);
    });
    out(['ok' => true]);

  case 'product_save':
    $key = (string)($in['key'] ?? '');
    if (!isset(YP_PRODUCTS[$key])) fail('Ürün bulunamadı.', 404);
    with_lock(function () use ($in, $key) {
      $products = yp_read('products');
      $old = $products[$key] ?? [];
      $image = media_path($in['image'] ?? '');
      if ($image === '') fail('Ürün görseli boş olamaz.');
      $products[$key] = ['image' => $image, 'alt' => text($in['alt'] ?? '', 160) ?: YP_PRODUCTS[$key] . ' uygulaması'];
      yp_write('products', $products);
      cleanup([$old['image'] ?? '']);
    });
    out(['ok' => true]);

  case 'blog_save':
    $post = with_lock(function () use ($in) {
      $posts = yp_read('blog');
      $post = blog_post((array)($in['post'] ?? []), $posts);
      $i = find_index($posts, 'id', $post['id']);
      $old = $i >= 0 ? $posts[$i] : [];
      if ($i >= 0) $posts[$i] = $post; else $posts[] = $post;
      yp_write('blog', $posts);
      // Kapaktan ya da yazıdan çıkarılan görselleri temizle
      preg_match_all('~uploads/[A-Za-z0-9/_.-]+~', (string)($old['content'] ?? ''), $m);
      cleanup(array_merge([$old['cover'] ?? ''], $m[0]));
      return $post;
    });
    out(['ok' => true, 'post' => $post]);

  case 'blog_delete':
    with_lock(function () use ($in) {
      $posts = yp_read('blog');
      $i = find_index($posts, 'id', (string)($in['id'] ?? ''));
      if ($i < 0) fail('Yazı bulunamadı.', 404);
      $old = $posts[$i];
      array_splice($posts, $i, 1);
      yp_write('blog', $posts);
      preg_match_all('~uploads/[A-Za-z0-9/_.-]+~', (string)($old['content'] ?? ''), $m);
      cleanup(array_merge([$old['cover'] ?? ''], $m[0]));
    });
    out(['ok' => true]);

  case 'preview':
    out(['html' => yp_markdown(text($in['content'] ?? '', 60000))]);

  case 'password':
    if (!yp_auth_verify((string)($in['current'] ?? ''))) fail('Mevcut şifre hatalı.', 401);
    $pw = (string)($in['new'] ?? '');
    if (strlen($pw) < 8) fail('Yeni şifre en az 8 karakter olmalı.');
    yp_auth_set($pw);
    out(['ok' => true]);
}

fail('Bilinmeyen işlem.', 404);
