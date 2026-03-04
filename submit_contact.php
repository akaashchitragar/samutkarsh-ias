<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$is_fetch = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest')
         || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
         || (isset($_SERVER['HTTP_SEC_FETCH_MODE']) && $_SERVER['HTTP_SEC_FETCH_MODE'] === 'cors');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name    = htmlspecialchars(strip_tags(trim($_POST['first_name'] ?? '')), ENT_QUOTES, 'UTF-8');
$email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8');

if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) < 5) {
    if ($is_fetch) { http_response_code(422); echo 'invalid'; exit; }
    header('Location: index.php?contact=error#footer');
    exit;
}

if ($conn) {
    $stmt = $conn->prepare(
        "INSERT INTO inquiries (full_name, phone, email, course, center, message)
         VALUES (?, '', ?, 'General Inquiry', 'Website Footer', ?)"
    );
    $stmt->bind_param('sss', $name, $email, $message);
    $stmt->execute();
    $stmt->close();
}

if ($is_fetch) { http_response_code(200); echo 'ok'; exit; }
header('Location: index.php?contact=success#footer');
exit;
