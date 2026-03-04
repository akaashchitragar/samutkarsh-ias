<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: testimonials.php');
    exit;
}

verify_csrf();

if (!can_delete()) {
    set_flash('danger', 'Only superadmins can delete testimonials.');
    header('Location: testimonials.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id < 1) {
    set_flash('danger', 'Invalid testimonial ID.');
    header('Location: testimonials.php');
    exit;
}

if ($conn) {
    $stmt = $conn->prepare('DELETE FROM testimonials WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    set_flash('success', 'Testimonial deleted.');
} else {
    set_flash('danger', 'Database error. Please try again.');
}

header('Location: testimonials.php');
exit;
