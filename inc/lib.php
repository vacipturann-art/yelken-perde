<?php
/* Yelken Perde — ortak yardımcılar (site sayfaları ve yönetim paneli kullanır).
   PHP 7.4+ ile uyumludur, veritabanı gerektirmez: içerik data/*.json dosyalarında tutulur.
   data/ içinde dosya yoksa inc/seed/ içindeki başlangıç içeriği okunur. */

date_default_timezone_set('Europe/Istanbul');

define('YP_ROOT', dirname(__DIR__));
define('YP_DATA', YP_ROOT . '/data');
define('YP_SEED', __DIR__ . '/seed');
define('YP_UPLOADS', YP_ROOT . '/uploads');

// Galeri kategorileri (anahtar => görünen ad). Filtre butonları bu sırayla listelenir.
const YP_CATEGORIES = [
  'fon'    => 'Fon Perde',
  'tul'    => 'Tül',
  'stor'   => 'Stor',
  'zebra'  => 'Zebra',
  'jaluzi' => 'Jaluzi',
  'klasik' => 'Klasik',
  'modern' => 'Modern',
  'lux'    => 'Lüks',
];

// Ürün sayfasındaki bölümler (anahtar = urunler.html#anahtar)
const YP_PRODUCTS = [
  'ahsap-jaluzi' => 'Ahşap Jaluzi',
  'zebra-perde'  => 'Zebra Perde',
  'stor-perde'   => 'Stor Perde',
  'tul-perde'    => 'Tül Perde',
  'fon-perde'    => 'Fon Perde',
  'dikey-jaluzi' => 'Dikey Jaluzi',
];

function e($s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* ─────────── Veri dosyaları ─────────── */

function yp_read(string $name): array {
  $file = YP_DATA . "/$name.json";
  if (!is_file($file)) $file = YP_SEED . "/$name.json";
  if (!is_file($file)) return [];

  $fp = fopen($file, 'r');
  if (!$fp) return [];
  flock($fp, LOCK_SH);
  $raw = stream_get_contents($fp);
  flock($fp, LOCK_UN);
  fclose($fp);

  $data = json_decode((string)$raw, true);
  return is_array($data) ? $data : [];
}

function yp_write(string $name, array $data): void {
  if (!is_dir(YP_DATA)) mkdir(YP_DATA, 0755, true);
  $file = YP_DATA . "/$name.json";
  $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  if ($json === false) throw new RuntimeException('Veri kaydedilemedi (JSON).');

  // Önce geçici dosyaya yaz, sonra taşı: yarım yazılmış dosya okunmaz.
  $tmp = $file . '.tmp-' . bin2hex(random_bytes(4));
  if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
    throw new RuntimeException('Veri dosyası yazılamadı. data/ klasörünün yazma iznini kontrol edin.');
  }
  if (!rename($tmp, $file)) {
    @unlink($tmp);
    throw new RuntimeException('Veri dosyası güncellenemedi.');
  }
}

/* ─────────── Galeri ─────────── */

function yp_gallery(): array {
  return yp_read('gallery');
}

function yp_showcase_items(): array {
  $byId = [];
  foreach (yp_gallery() as $item) $byId[$item['id']] = $item;
  $out = [];
  foreach (yp_read('showcase') as $id) {
    if (isset($byId[$id])) $out[] = $byId[$id];
  }
  return $out;
}

function yp_youtube_id(string $input): string {
  $input = trim($input);
  if (preg_match('/^[A-Za-z0-9_-]{11}$/', $input)) return $input;
  if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $input, $m)) {
    return $m[1];
  }
  return '';
}

/* Görsel etiketi. images/ altındaki bir .jpg için -mobile/-tablet/-desktop.webp
   sürümleri varsa ekran boyutuna göre WebP sunan <picture> üretir. */
function yp_img(string $src, string $alt = '', array $attrs = []): string {
  $extra = '';
  foreach ($attrs as $k => $v) $extra .= ' ' . $k . ($v === true ? '' : '="' . e($v) . '"');
  $img = '<img src="' . e($src) . '" alt="' . e($alt) . '"' . $extra . '>';

  if (!preg_match('~^(images/.+)\.jpe?g$~i', $src, $m) || !is_file(YP_ROOT . '/' . $m[1] . '-desktop.webp')) return $img;
  $b = e($m[1]);
  return '<picture>'
    . '<source media="(max-width: 600px)" srcset="' . $b . '-mobile.webp" type="image/webp">'
    . '<source media="(max-width: 1024px)" srcset="' . $b . '-tablet.webp" type="image/webp">'
    . '<source srcset="' . $b . '-desktop.webp" type="image/webp">'
    . $img . '</picture>';
}

// Galeri ve vitrin kartlarındaki önizleme görseli
function yp_item_thumb(array $item): string {
  if (!empty($item['thumb'])) return $item['thumb'];
  if (($item['type'] ?? '') === 'youtube') return 'https://img.youtube.com/vi/' . $item['youtube'] . '/hqdefault.jpg';
  if (($item['type'] ?? '') === 'image') return $item['src'] ?? '';
  return '';
}

// Lightbox'ın açacağı kaynak: görsel/video yolu ya da YouTube kimliği
function yp_item_full(array $item): string {
  return ($item['type'] ?? '') === 'youtube' ? ($item['youtube'] ?? '') : ($item['src'] ?? '');
}

/* Tek bir galeri kartı. $overlay = başlık/etiket katmanı (galeri sayfası) */
function yp_gallery_item_html(array $item, bool $overlay = true): string {
  $type = $item['type'] ?? 'image';
  $title = $item['title'] ?? '';
  $alt = ($item['alt'] ?? '') !== '' ? $item['alt'] : $title;
  $caption = ($item['caption'] ?? '') !== '' ? $item['caption'] : $title;
  $thumb = yp_item_thumb($item);

  $h = '<div class="gallery-item" data-category="' . e(implode(' ', $item['categories'] ?? [])) . '"'
     . ' data-lightbox="' . e(yp_item_full($item)) . '" data-type="' . e($type) . '"'
     . ' data-caption="' . e($caption) . '">';

  if ($thumb !== '') {
    $h .= yp_img($thumb, $alt, ['loading' => 'lazy']);
  } else {
    // Kapak karesi oluşturulamamış video: ilk kareyi tarayıcı göstersin
    $h .= '<video src="' . e($item['src']) . '#t=0.5" muted playsinline preload="metadata" aria-label="' . e($alt) . '"></video>';
  }
  if ($type !== 'image') $h .= '<span class="gallery-play" aria-hidden="true"></span>';

  if ($overlay && ($title !== '' || ($item['label'] ?? '') !== '')) {
    $h .= '<div class="gallery-item-overlay"><div class="gallery-item-info">';
    if (($item['label'] ?? '') !== '') $h .= '<div class="gallery-item-category">' . e($item['label']) . '</div>';
    if ($title !== '') $h .= '<div class="gallery-item-title">' . e($title) . '</div>';
    $h .= '</div></div>';
  }
  return $h . '</div>';
}

/* ─────────── Ürünler ─────────── */

function yp_product(string $key): array {
  static $products = null;
  if ($products === null) $products = yp_read('products');
  return ($products[$key] ?? []) + ['image' => '', 'alt' => YP_PRODUCTS[$key] ?? ''];
}

/* ─────────── Blog ─────────── */

function yp_posts(bool $onlyPublished = true): array {
  $posts = yp_read('blog');
  if ($onlyPublished) {
    $posts = array_values(array_filter($posts, function ($p) { return !empty($p['published']); }));
  }
  usort($posts, function ($a, $b) { return strcmp($b['date'] ?? '', $a['date'] ?? ''); });
  return $posts;
}

function yp_find_post(string $slug): ?array {
  foreach (yp_read('blog') as $p) {
    if (($p['slug'] ?? '') === $slug) return $p;
  }
  return null;
}

function yp_read_time(array $post): int {
  if (!empty($post['read_time'])) return (int)$post['read_time'];
  $words = preg_split('/\s+/u', trim(strip_tags($post['content'] ?? '')), -1, PREG_SPLIT_NO_EMPTY);
  return max(1, (int)ceil(count($words) / 200));
}

function yp_slugify(string $text): string {
  $map = ['ç' => 'c', 'Ç' => 'c', 'ğ' => 'g', 'Ğ' => 'g', 'ı' => 'i', 'I' => 'i', 'İ' => 'i',
          'ö' => 'o', 'Ö' => 'o', 'ş' => 's', 'Ş' => 's', 'ü' => 'u', 'Ü' => 'u', 'â' => 'a', 'î' => 'i', 'û' => 'u'];
  $text = strtolower(strtr($text, $map));
  $text = preg_replace('/[^a-z0-9]+/', '-', $text);
  return trim(substr(trim($text, '-'), 0, 80), '-');
}

const YP_MONTHS = ['', 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];

function yp_date_tr(string $ymd): string {
  if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $ymd, $m)) return '';
  return (int)$m[3] . ' ' . YP_MONTHS[(int)$m[2]] . ' ' . $m[1];
}

/* Blog yazıları için küçük ve güvenli Markdown:
   ## / ### başlık, - liste, 1. numaralı liste, > alıntı, ![açıklama](görsel),
   **kalın**, *italik*, [metin](bağlantı). Ham HTML her zaman kaçışlanır. */
function yp_markdown(string $md): string {
  $md = str_replace(["\r\n", "\r"], "\n", $md);
  $blocks = preg_split('/\n\s*\n/', trim($md));
  $out = '';

  foreach ($blocks as $block) {
    $lines = explode("\n", trim($block));
    if ($lines === [''] ) continue;

    if (preg_match('/^(#{2,3})\s+(.+)$/', $lines[0], $m) && count($lines) === 1) {
      $tag = strlen($m[1]) === 2 ? 'h2' : 'h3';
      $out .= "<$tag>" . yp_md_inline($m[2]) . "</$tag>\n";
    } elseif (count($lines) === 1 && preg_match('/^!\[([^\]]*)\]\(([^)\s]+)\)$/', $lines[0], $m)) {
      $src = yp_safe_url($m[2]);
      if ($src !== '') {
        $out .= '<figure><img src="' . e($src) . '" alt="' . e($m[1]) . '" loading="lazy">'
              . ($m[1] !== '' ? '<figcaption>' . e($m[1]) . '</figcaption>' : '') . "</figure>\n";
      }
    } elseif (yp_md_all($lines, '/^[-*]\s+/')) {
      $out .= "<ul>\n";
      foreach ($lines as $l) $out .= '<li>' . yp_md_inline(preg_replace('/^[-*]\s+/', '', $l)) . "</li>\n";
      $out .= "</ul>\n";
    } elseif (yp_md_all($lines, '/^\d+[.)]\s+/')) {
      $out .= "<ol>\n";
      foreach ($lines as $l) $out .= '<li>' . yp_md_inline(preg_replace('/^\d+[.)]\s+/', '', $l)) . "</li>\n";
      $out .= "</ol>\n";
    } elseif (yp_md_all($lines, '/^>\s?/')) {
      $text = implode('<br>', array_map(function ($l) { return yp_md_inline(preg_replace('/^>\s?/', '', $l)); }, $lines));
      $out .= "<blockquote><p>$text</p></blockquote>\n";
    } else {
      // Başlık + paragraf aynı blokta yazılmışsa başlığı ayır
      if (preg_match('/^(#{2,3})\s+(.+)$/', $lines[0], $m)) {
        $tag = strlen($m[1]) === 2 ? 'h2' : 'h3';
        $out .= "<$tag>" . yp_md_inline($m[2]) . "</$tag>\n";
        array_shift($lines);
      }
      $out .= '<p>' . implode('<br>', array_map('yp_md_inline', $lines)) . "</p>\n";
    }
  }
  return $out;
}

function yp_md_all(array $lines, string $re): bool {
  foreach ($lines as $l) if (!preg_match($re, $l)) return false;
  return true;
}

function yp_md_inline(string $text): string {
  $text = e($text);
  $text = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function ($m) {
    $url = yp_safe_url(html_entity_decode($m[2], ENT_QUOTES, 'UTF-8'));
    if ($url === '') return $m[1];
    $ext = preg_match('~^https?://~i', $url) ? ' target="_blank" rel="noopener"' : '';
    return '<a href="' . e($url) . '"' . $ext . '>' . $m[1] . '</a>';
  }, $text);
  $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
  $text = preg_replace('/(?<![*\w])\*(?!\s)(.+?)(?<!\s)\*(?![*\w])/u', '<em>$1</em>', $text);
  return $text;
}

// Yalnızca http(s), mailto, tel ve site içi göreli bağlantılara izin verilir.
function yp_safe_url(string $url): string {
  $url = trim($url);
  if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $url) && !preg_match('/^(https?:|mailto:|tel:)/i', $url)) return '';
  if (strpos($url, '//') === 0) return '';
  return $url;
}
