<?php
/* Yönetim paneli: oturum, şifre ve CSRF koruması */

require_once __DIR__ . '/lib.php';

const YP_AUTH_FILE = YP_DATA . '/auth.php';          // .php: .htaccess çalışmasa bile içeriği okunamaz
const YP_ATTEMPTS_FILE = YP_DATA . '/login-attempts.json';
const YP_MAX_ATTEMPTS = 5;
const YP_LOCK_SECONDS = 900;
const YP_IDLE_SECONDS = 7200;

function yp_is_https(): bool {
  return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
      || (($_SERVER['SERVER_PORT'] ?? '') == 443);
}

function yp_session_start(): void {
  if (session_status() === PHP_SESSION_ACTIVE) return;
  session_name('YPADMIN');
  session_set_cookie_params([
    'lifetime' => 0,
    'path'     => rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/',
    'secure'   => yp_is_https(),
    'httponly' => true,
    'samesite' => 'Strict',
  ]);
  session_start();

  if (!empty($_SESSION['admin']) && time() - ($_SESSION['seen'] ?? 0) > YP_IDLE_SECONDS) {
    $_SESSION = [];
    session_regenerate_id(true);
  }
  if (!empty($_SESSION['admin'])) $_SESSION['seen'] = time();
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function yp_logged_in(): bool {
  return !empty($_SESSION['admin']);
}

function yp_csrf_ok(): bool {
  $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
  return is_string($sent) && $sent !== '' && hash_equals($_SESSION['csrf'] ?? '', $sent);
}

/* ─── Şifre ─── */

function yp_auth_exists(): bool {
  return is_file(YP_AUTH_FILE);
}

function yp_auth_set(string $password): void {
  if (!is_dir(YP_DATA)) mkdir(YP_DATA, 0755, true);
  $php = "<?php\nreturn " . var_export(['hash' => password_hash($password, PASSWORD_DEFAULT)], true) . ";\n";
  if (file_put_contents(YP_AUTH_FILE, $php, LOCK_EX) === false) {
    throw new RuntimeException('Şifre kaydedilemedi. data/ klasörünün yazma iznini kontrol edin.');
  }
  if (function_exists('opcache_invalidate')) @opcache_invalidate(YP_AUTH_FILE, true);
}

function yp_auth_verify(string $password): bool {
  if (!yp_auth_exists()) return false;
  $auth = include YP_AUTH_FILE;
  return is_array($auth) && password_verify($password, $auth['hash'] ?? '');
}

/* ─── Kaba kuvvet koruması (IP başına) ─── */

function yp_attempts(): array {
  $raw = is_file(YP_ATTEMPTS_FILE) ? json_decode((string)file_get_contents(YP_ATTEMPTS_FILE), true) : [];
  $now = time();
  $out = [];
  foreach ((array)$raw as $ip => $a) {
    if (($a['last'] ?? 0) > $now - YP_LOCK_SECONDS) $out[$ip] = $a;
  }
  return $out;
}

function yp_login_locked_for(): int {
  $a = yp_attempts()[$_SERVER['REMOTE_ADDR'] ?? ''] ?? null;
  if (!$a || $a['count'] < YP_MAX_ATTEMPTS) return 0;
  return max(0, $a['last'] + YP_LOCK_SECONDS - time());
}

function yp_login_failed(): void {
  $all = yp_attempts();
  $ip = $_SERVER['REMOTE_ADDR'] ?? '';
  $all[$ip] = ['count' => ($all[$ip]['count'] ?? 0) + 1, 'last' => time()];
  @file_put_contents(YP_ATTEMPTS_FILE, json_encode($all), LOCK_EX);
}

function yp_login_succeeded(): void {
  $all = yp_attempts();
  unset($all[$_SERVER['REMOTE_ADDR'] ?? '']);
  @file_put_contents(YP_ATTEMPTS_FILE, json_encode($all), LOCK_EX);
  session_regenerate_id(true);
  $_SESSION['admin'] = true;
  $_SESSION['seen'] = time();
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
