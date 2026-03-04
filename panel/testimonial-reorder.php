<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed.']);
    exit;
}

// Verify CSRF from header (sent by JS fetch)
verify_csrf_header();

if (!can_edit()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Permission denied.']);
    exit;
}

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['order']) || !is_array($data['order']) || empty($data['order'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid data.']);
    exit;
}

// Cast all IDs to integers (eliminates any injection risk)
$ids = array_map('intval', $data['order']);
$ids = array_filter($ids, fn($id) => $id > 0);

if (empty($ids)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No valid IDs.']);
    exit;
}

if (!$conn) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Database error.']);
    exit;
}

// Build a CASE WHEN UPDATE for atomic reorder
// All values are intval-cast, so safe to interpolate
$cases   = '';
$id_list = implode(',', $ids);
foreach ($ids as $pos => $id) {
    $cases .= " WHEN id = {$id} THEN " . ($pos + 1);
}

$sql = "UPDATE testimonials SET display_order = CASE {$cases} END WHERE id IN ({$id_list})";
$conn->query($sql);

echo json_encode(['ok' => true]);
exit;
