<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$table_map = [
    'shraddhamedha' => 'shraddhamedha_enrollments',
    'utkarsh'       => 'utkarsh_enrollments',
    'comprehensive' => 'comprehensive_enrollments',
    'mentorship'    => 'mentorship_enrollments',
];
$course_labels = [
    'shraddhamedha' => 'Shraddha-Medha',
    'utkarsh'       => 'Utkarsh',
    'comprehensive' => 'Comprehensive-IAS',
    'mentorship'    => 'Mentorship',
];

$course_key = array_key_exists($_GET['course'] ?? '', $table_map) ? $_GET['course'] : 'shraddhamedha';
$table      = $table_map[$course_key];
$is_school  = ($course_key === 'shraddhamedha');

$q             = clean($_GET['q'] ?? '');
$center_filter = clean($_GET['center'] ?? '');
$status_filter = in_array($_GET['status'] ?? '', ['pending', 'confirmed', 'cancelled']) ? $_GET['status'] : '';
$year_filter   = (int) ($_GET['year'] ?? 0);

// Build WHERE (same logic as enrollments.php)
$where  = ['1=1'];
$types  = '';
$params = [];

if ($center_filter !== '') {
    $where[]  = 'center = ?';
    $types   .= 's';
    $params[] = $center_filter;
}
if ($status_filter !== '') {
    $where[]  = 'status = ?';
    $types   .= 's';
    $params[] = $status_filter;
}
if ($year_filter > 0) {
    $where[]  = 'YEAR(created_at) = ?';
    $types   .= 'i';
    $params[] = $year_filter;
}
if ($q !== '') {
    $like = '%' . $q . '%';
    if ($is_school) {
        $where[]  = '(full_name LIKE ? OR email LIKE ? OR father_phone LIKE ?)';
        $types   .= 'sss';
    } else {
        $where[]  = '(full_name LIKE ? OR email LIKE ? OR phone_number LIKE ?)';
        $types   .= 'sss';
    }
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$where_sql = implode(' AND ', $where);

if (!$conn) {
    die('Database connection failed.');
}

$stmt = $conn->prepare("SELECT * FROM `{$table}` WHERE {$where_sql} ORDER BY created_at DESC");
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// CSV headers
if ($is_school) {
    $columns = ['id', 'full_name', 'father_name', 'mother_name', 'date_of_birth', 'gender',
                'school_name', 'class_standard', 'father_phone', 'mother_phone', 'email',
                'center', 'address', 'caste_category', 'status', 'created_at'];
} else {
    $columns = ['id', 'full_name', 'father_name', 'mother_name', 'date_of_birth', 'gender',
                'college_name', 'stream', 'district', 'phone_number', 'whatsapp_number', 'email',
                'center', 'address', 'caste_category', 'status', 'created_at'];
}

$label        = $course_labels[$course_key];
$date_str     = date('Ymd');
$filename     = 'enrollments_' . str_replace(' ', '-', strtolower($label)) . '_' . $date_str . '.csv';

// Send CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');

// BOM for Excel UTF-8 compatibility
fwrite($out, "\xEF\xBB\xBF");

// Header row (human-readable)
$header_labels = array_map(function ($col) {
    return ucwords(str_replace('_', ' ', $col));
}, $columns);
fputcsv($out, $header_labels);

// Data rows
while ($row = $result->fetch_assoc()) {
    $csv_row = [];
    foreach ($columns as $col) {
        $val = (string) ($row[$col] ?? '');
        $csv_row[] = csv_safe($val);
    }
    fputcsv($out, $csv_row);
}

fclose($out);
$stmt->close();
exit;
