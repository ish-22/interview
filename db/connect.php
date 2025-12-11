<?php
// db/connect.php
// Edit these to match your MySQL server
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'website_project');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'DB connection failed: ' . $mysqli->connect_error]));
}
$mysqli->set_charset('utf8mb4');
