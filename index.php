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
$routes = [
  "/" => [
    "title" => "home",
    "view"  => __DIR__ . "/apps/app.php",
  ],
  "/about" => [
    "title" => "about",
    "view"  => __DIR__ . "/apps/about/app.php",
  ],
];
$page = $routes[$request_path] ?? [
    "title" => "Not Found",
    "view" => __DIR__ . "/pages/404.php",
    "status" => 404,
];
http_response_code($page["status"] ?? 200);
$meta_title = $page["title"];
$meta_file = $page["view"];

require __DIR__ . "/layouts/main.php";
?>