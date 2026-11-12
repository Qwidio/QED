<?php
declare(strict_types=1);
require_once __DIR__ . "/config.php";
$base_path = "/qed";
$request_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($base_path !== '' && ($request_path === $base_path || str_starts_with($request_path, $base_path . '/'))) {
  $request_path = substr($request_path, strlen($base_path));
}
$segments = array_values(array_filter(explode('/', trim($request_path, '/')), 'strlen'));

// Resolves URL segments inside /apps supporting static folders and [param] folders.
// Strictly validates segment characters and realpath boundaries to prevent LFI.
function resolve_app_route(string $appsRoot, array $segments): ?array {
  $realRoot = realpath($appsRoot);
  if ($realRoot === false) {
    return null;
  }
  $currentDir = $realRoot;
  $params = [];
  $layouts = [];
  if (is_file($currentDir . '/layout.php')) {
    $layouts[] = $currentDir . '/layout.php';
  }
  foreach ($segments as $segment) {
    $segment = rawurldecode($segment);
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $segment)) {
      return null;
    }
    $staticCandidate = $currentDir . DIRECTORY_SEPARATOR . $segment;
    if (is_dir($staticCandidate) && str_starts_with(realpath($staticCandidate) ?: '', $realRoot)) {
      $currentDir = realpath($staticCandidate);
    } else {
      // checking for a dynamic [param] directory
      $matchedDynamic = null;
      foreach (scandir($currentDir) ?: [] as $entry) {
        if (preg_match('/^\[([a-zA-Z0-9_]+)\]$/', $entry, $m)) {
          $candidate = realpath($currentDir . DIRECTORY_SEPARATOR . $entry);
          if ($candidate && is_dir($candidate) && str_starts_with($candidate, $realRoot)) {
            $matchedDynamic = $candidate;
            $params[$m[1]] = $segment;
            break;
          }
        }
      }
      if ($matchedDynamic === null) {
        return null;
      }
      $currentDir = $matchedDynamic;
    }
    if (is_file($currentDir . '/layout.php')) {
      $layouts[] = $currentDir . '/layout.php';
    }
  }
  if (is_file($currentDir . '/route.php')) {
    return ['type' => 'api', 'file' => $currentDir . '/route.php', 'params' => $params];
  }
  foreach (['page.php', 'app.php'] as $viewFile) {
    $candidate = $currentDir . DIRECTORY_SEPARATOR . $viewFile;
    if (is_file($candidate)) {
      return [
        'type'    => 'page',
        'file'    => $candidate,
        'params'  => $params,
        'layouts' => $layouts,
      ];
    }
  }
  return null;
}
$match = resolve_app_route(__DIR__ . '/apps', $segments);
// api routes
if ($match && $match['type'] === 'api') {
  header('Content-Type: application/json; charset=utf-8');
  $params = $match['params'];
  $handlers = require $match['file'];
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
  if (is_array($handlers) && isset($handlers[$method]) && is_callable($handlers[$method])) {
    $response = $handlers[$method]($params);
    echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    exit;
  }
  http_response_code(405);
  echo json_encode(['error' => 'Method forbidden']);
  exit;
}
// auto verif csrf on state changing reqs
csrf_verify();
// metadata
$meta = [
   'title' => 'QED',
   'status' => 200 
  ];
$params = $match['params'] ?? [];
$viewFile = $match['file'] ?? (__DIR__ . '/apps/404.php');
if ($match === null) {
  $meta['title'] = 'page not found';
  $meta['status'] = 404;
}
// global image path helper
function public_sls(string $path): string {
  global $base_path;
  $clean = ltrim($path, '/');
  if (str_contains($clean, '..') || !preg_match('/\.(png|jpe?g|gif|webp|avif|svg|ico)$/i', $clean)) {
    return '';
  }
  return htmlspecialchars($base_path . '/public/' . $clean, ENT_QUOTES, 'UTF-8');
}
// basically the same except for script
function script_sls(string $path): string {
  global $base_path;
  $clean = ltrim($path, '/');
  if (str_contains($clean, '..') || !preg_match('/\.(m?js)$/i', $clean)) {
    return '';
  }
  return htmlspecialchars($base_path . '/script/' . $clean, ENT_QUOTES, 'UTF-8');
}
// execute page first inside the output buffer so it can set $meta['title'] or redirect
ob_start();
if (is_file($viewFile)) {
  (static function (string $__file, array &$meta, array $params, string $base_path): void {
    require $__file;
  })($viewFile, $meta, $params, $base_path);
} else {
  echo '<center><h1>404 - Page not found</h1></center>';
}
$children = ob_get_clean();
http_response_code($meta['status']);
// wrap the inner nested layouts
foreach (array_reverse($match['layouts'] ?? []) as $layoutFile) {
  ob_start();
  (static function (string $__layout, string $children, array &$meta, string $base_path): void {
    require $__layout;
  })($layoutFile, $children, $meta, $base_path);
  $children = ob_get_clean();
}

require __DIR__ . "/layouts/main.php";
?>