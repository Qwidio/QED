<?php
require_once __DIR__ . "/config.php";
$base_path = "/qed";
$request_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?: "/";
if ($base_path !== "" && ($request_path === $base_path || strpos($request_path, $base_path . "/") === 0)) {
  $request_path = substr($request_path, strlen($base_path));
}

$request_path = "/" . trim($request_path, "/");
if ($request_path === "/") {
  $request_path = "/";
}
?>