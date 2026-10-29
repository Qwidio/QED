<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "qed";
try {
  $connection = new mysqli($host, $db_user, $db_pass, $db_name);
  $connection->set_charset('utf8mb4');
} catch (\mysqli_sql_exception $e) {
  error_log('failed to connect with db: ' . $e-getMessage());
  http_response_code(500);
  exit('Failed to connect with the server.');
}
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
?>