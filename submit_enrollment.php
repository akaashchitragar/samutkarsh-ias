<?php
session_start();
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Method not allowed.']]);
    exit;
}

$input = $_POST;
if (empty($input) && !empty(file_get_contents('php://input'))) {
    $raw = json_decode(file_get_contents('php://input'), true);
    if (is_array($raw)) {
        $input = $raw;
    }
}

if (
    empty($input['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $input['csrf_token'])
) {
    http_response_code(403);
    echo json_encode(['success' => false, 'errors' => ['Security check failed. Please refresh and try again.']]);
    exit;
}

function clean($val) {
    if (!is_string($val)) return '';
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$course = clean($input['course'] ?? '');
$full_name = clean($input['full_name'] ?? '');
$father_name = clean($input['father_name'] ?? '');
$mother_name = clean($input['mother_name'] ?? '');
$address = clean($input['address'] ?? '');
$date_of_birth = clean($input['date_of_birth'] ?? '');
$gender = clean($input['gender'] ?? '');
$school_name = clean($input['school_name'] ?? '');
$class_standard = clean($input['class_standard'] ?? '');
$college_name = clean($input['college_name'] ?? '');
$stream = clean($input['stream'] ?? '');
$district = clean($input['district'] ?? '');
$father_phone = preg_replace('/[^0-9]/', '', $input['father_phone'] ?? '');
$mother_phone = preg_replace('/[^0-9]/', '', $input['mother_phone'] ?? '');
$phone_number = preg_replace('/[^0-9]/', '', $input['phone_number'] ?? '');
$whatsapp_number = preg_replace('/[^0-9]/', '', $input['whatsapp_number'] ?? '');
$email = filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$center = clean($input['center'] ?? '');
$caste_category = clean($input['caste_category'] ?? '');

$errors = [];
$courseTableMap = [
    'Shraddha-Medha' => 'shraddhamedha_enrollments',
    'Utkarsh' => 'utkarsh_enrollments',
    'IAS Coaching' => 'comprehensive_enrollments',
    'Comprehensive Program' => 'comprehensive_enrollments',
    'Mentorship Program' => 'mentorship_enrollments',
];

if (!isset($courseTableMap[$course])) {
    $errors[] = 'Please select a valid programme.';
}

if (strlen($full_name) < 2) $errors[] = 'Full name is required (at least 2 characters).';
if (strlen($father_name) < 2) $errors[] = "Father's name is required.";
if (empty($date_of_birth)) $errors[] = 'Date of birth is required.';
if (!in_array($gender, ['Male', 'Female', 'Other'], true)) $errors[] = 'Please select gender.';
if (strlen($address) < 10) $errors[] = 'Address must be at least 10 characters.';
if (empty($center)) $errors[] = 'Please select a study centre.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';

$isShraddha = ($course === 'Shraddha-Medha');
if ($isShraddha) {
    if (strlen($school_name) < 2) $errors[] = 'School name is required.';
    if (empty($class_standard)) $errors[] = 'Class/Standard is required.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $father_phone)) $errors[] = 'Father\'s phone must be a valid 10-digit number.';
} else {
    if (strlen($college_name) < 2) $errors[] = 'College name is required.';
    if (strlen($stream) < 1) $errors[] = 'Stream is required.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $phone_number)) $errors[] = 'Phone number must be a valid 10-digit number.';
    if (!preg_match('/^[6-9][0-9]{9}$/', $whatsapp_number)) $errors[] = 'WhatsApp number must be a valid 10-digit number.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database unavailable. Please try again later.']]);
    exit;
}

$status = 'pending';
$genderDb = in_array($gender, ['Male', 'Female', 'Other'], true) ? $gender : 'Other';

if ($isShraddha) {
    $mother_name = $mother_name !== '' ? $mother_name : '';
    $mother_phone = $mother_phone !== '' ? $mother_phone : null;
    $stmt = $conn->prepare(
        "INSERT INTO shraddhamedha_enrollments (
          full_name, father_name, mother_name, address, date_of_birth, gender,
          school_name, class_standard, center, father_phone, mother_phone, email, caste_category, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'ssssssssssssss',
        $full_name, $father_name, $mother_name, $address, $date_of_birth, $genderDb,
        $school_name, $class_standard, $center, $father_phone, $mother_phone, $email, $caste_category, $status
    );
} else {
    $mother_name = $mother_name !== '' ? $mother_name : '';
    $stmt = $conn->prepare(
        "INSERT INTO " . $courseTableMap[$course] . " (
          full_name, father_name, mother_name, address, date_of_birth, gender,
          college_name, stream, center, phone_number, whatsapp_number, email, district, caste_category, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'sssssssssssssss',
        $full_name, $father_name, $mother_name, $address, $date_of_birth, $genderDb,
        $college_name, $stream, $center, $phone_number, $whatsapp_number, $email, $district, $caste_category, $status
    );
}

try {
    $stmt->execute();
    $stmt->close();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo json_encode(['success' => true, 'message' => 'Registration submitted successfully. We will contact you soon.']);
} catch (Exception $e) {
    error_log('Enrollment insert error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Unable to save registration. Please try again.']]);
}
