<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: enrollments.php');
    exit;
}

verify_csrf();

if (!can_edit()) {
    set_flash('danger', 'You do not have permission to perform this action.');
    header('Location: enrollments.php');
    exit;
}

$table_map = [
    'shraddhamedha' => 'shraddhamedha_enrollments',
    'utkarsh'       => 'utkarsh_enrollments',
    'comprehensive' => 'comprehensive_enrollments',
    'mentorship'    => 'mentorship_enrollments',
];
$valid_statuses = ['pending', 'confirmed', 'cancelled'];

$course_key = $_POST['course'] ?? '';
$status     = $_POST['status'] ?? '';
$id         = intval($_POST['id'] ?? 0);

if (!array_key_exists($course_key, $table_map)) {
    set_flash('danger', 'Invalid course.');
    header('Location: enrollments.php');
    exit;
}
if (!in_array($status, $valid_statuses, true)) {
    set_flash('danger', 'Invalid status.');
    header('Location: enrollments.php?course=' . urlencode($course_key));
    exit;
}
if ($id < 1) {
    set_flash('danger', 'Invalid enrollment ID.');
    header('Location: enrollments.php?course=' . urlencode($course_key));
    exit;
}

$table = $table_map[$course_key];

if ($conn) {
    $stmt = $conn->prepare("UPDATE `{$table}` SET status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    set_flash('success', 'Status updated to ' . $status . '.');
} else {
    set_flash('danger', 'Database error. Please try again.');
}

$page   = max(1, intval($_POST['page'] ?? 1));
$q      = clean($_POST['q'] ?? '');
$center = clean($_POST['center'] ?? '');

header('Location: enrollments.php?' . http_build_query([
    'course'  => $course_key,
    'q'       => $q,
    'center'  => $center,
    'status'  => $_POST['status_filter'] ?? '',
    'page'    => $page,
]));
exit;
