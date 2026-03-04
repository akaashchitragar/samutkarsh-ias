<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inquiries.php');
    exit;
}

verify_csrf();

if (!can_edit()) {
    set_flash('danger', 'You do not have permission to perform this action.');
    header('Location: inquiries.php');
    exit;
}

$id      = intval($_POST['id'] ?? 0);
$current = intval($_POST['current'] ?? 0);
$new_val = $current === 1 ? 0 : 1;

if ($id < 1) {
    set_flash('danger', 'Invalid inquiry ID.');
    header('Location: inquiries.php');
    exit;
}

if ($conn) {
    $stmt = $conn->prepare('UPDATE inquiries SET is_called = ? WHERE id = ?');
    $stmt->bind_param('ii', $new_val, $id);
    $stmt->execute();
    $stmt->close();
    set_flash('success', $new_val ? 'Marked as called.' : 'Marked as pending.');
} else {
    set_flash('danger', 'Database error. Please try again.');
}

// Redirect back to same page/filters
$page   = max(1, intval($_POST['redirect_page'] ?? 1));
$status = in_array($_POST['redirect_status'] ?? '', ['called', 'uncalled', 'all']) ? $_POST['redirect_status'] : 'all';
$q      = clean($_POST['redirect_q'] ?? '');

header('Location: inquiries.php?' . http_build_query(['status' => $status, 'q' => $q, 'page' => $page]));
exit;
