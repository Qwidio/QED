<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
  $isHTTPS = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? null) == 443;
  session_start([
    'use_strict_mode' => 1,
    'cookie_httponly' => true,
    'cookie_secure' => $isHTTPS,
    'cookie_samesite' => 'Lax',
  ]);
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
// the .env loader and scoped database wrapper
final class DB {
  private static ?\mysqli $conn = null;
  public static function connect(): \mysqli {
    if (self::$conn !== null) {
      return self::$conn;
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $env = is_file(_DIR_ . '/.env') ? (parse_ini_file(_DIR_ . '/.env') ?: []) : [];
    try {
      self::$conn = new \mysqli(
        $env['DB_HOST'] ?? 'localhost',
        $env['DB_USER'] ?? 'root',
        $env['DB_PASS'] ?? '',
        $env['DB_NAME'] ?? 'qed',
      );
      self::$conn->set_charset('utf8mb4');
      return self::$conn;
    } catch (\Throwable $e) {
      error_log('Failed db connection: ' . $e->getMessage());
      http_response_code(500);
      exit('Internal server error.');
    }
  }
  // query helper
  public static function query(string $sql, array $params = []): \mysqli_result|bool {
    return self::connect()->execute_query($sql, $params);
  }
}
// escapes CSRF 
function e(?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_token(): string {
  if (empty($_SESSION['_csrf'])) {
     $_SESSION['_csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['_csrf'];
}
function csrf_field(): string {
  return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function csrf_verify(): void {
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
  if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
      http_response_code(403);
      exit('Invalid CSRF token');
    }
  }
}
// components
function components(string $name, array $props = []): void {
  if (!preg_match('/^[a-zA-Z0-9_\/-]+$/', $name)) {
    throw new \InvalidArgumentException('Invalid component name.');
  }
  $baseDir = realpath(__DIR__ . '/components');
  $target  = realpath(__DIR__ . '/components/' . $name . '.php');
  if (!$baseDir || !$target || !str_starts_with($target, $baseDir . DIRECTORY_SEPARATOR)) {
    throw new \RuntimeException("Component not found: {$name}");
  }
  (static function (string $__file, array $__props): void {
    extract($__props, EXTR_SKIP);
    require $__file;
  })($target, $props);
}
?>