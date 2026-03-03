<?php
/**
 * MySQL Database Connection
 * Used by: index.php, submit_inquiry.php, submit_enrollment.php, sections (admissions, locations, testimonials).
 * Schema: database/schema.sql (centers, inquiries, enrollment tables, etc.)
 *
 * Configure (in order of precedence):
 * 1. Environment variables: DB_HOST, DB_USER, DB_PASS, DB_NAME
 * 2. includes/config.php: define('DB_HOST', '...'); etc. (copy from config.example.php)
 * 3. Defaults below (edit for quick local use)
 */
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}

$db_host = getenv('DB_HOST') ?: (defined('DB_HOST') ? DB_HOST : 'localhost');
$db_user = getenv('DB_USER') ?: (defined('DB_USER') ? DB_USER : 'your_db_user');
$db_pass = getenv('DB_PASS') ?: (defined('DB_PASS') ? DB_PASS : 'your_db_password');
$db_name = getenv('DB_NAME') ?: (defined('DB_NAME') ? DB_NAME : 'samutkarsh_db');

if (!defined('DB_HOST')) define('DB_HOST', $db_host);
if (!defined('DB_USER')) define('DB_USER', $db_user);
if (!defined('DB_PASS')) define('DB_PASS', $db_pass);
if (!defined('DB_NAME')) define('DB_NAME', $db_name);

$conn = null;

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    $conn = null; // Sections degrade gracefully to static fallback data
}
