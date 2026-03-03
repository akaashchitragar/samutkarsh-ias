<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$name = trim($_POST['first_name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$message = trim($_POST['message'] ?? '');

if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($message) < 5) {
    header('Location: index.php?contact=error#footer');
    exit;
}

// Optional: store in DB if you have a contact_messages table, or send via mail()
// For now, redirect with success. Add mail() or DB insert as needed.
// mail($to, $subject, $body);

header('Location: index.php?contact=success#footer');
exit;
