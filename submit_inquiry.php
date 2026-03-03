<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// CSRF check
if (
    empty($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])
) {
    die('Security check failed. Please go back and try again.');
}

// Sanitize inputs
function clean(string $val): string {
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

$full_name     = clean($_POST['full_name'] ?? '');
$father_name   = clean($_POST['father_name'] ?? '');
$dob           = clean($_POST['dob'] ?? '');
$gender        = clean($_POST['gender'] ?? '');
$school_college = clean($_POST['school_college'] ?? '');
$phone         = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
$email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$course        = clean($_POST['course'] ?? '');
$center        = clean($_POST['center'] ?? '');

// Basic validation
$errors = [];
if (strlen($full_name) < 2)                   $errors[] = 'Full name is required.';
if (strlen($father_name) < 2)                 $errors[] = "Father's name is required.";
if (empty($dob))                               $errors[] = 'Date of birth is required.';
if (empty($gender))                            $errors[] = 'Gender is required.';
if (strlen($school_college) < 2)              $errors[] = 'School/College name is required.';
if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) $errors[] = 'Enter a valid 10-digit Indian phone number.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
if (empty($course))                            $errors[] = 'Please select a course.';
if (empty($center))                            $errors[] = 'Please select a centre.';

if (!empty($errors)) {
    echo '<ul>';
    foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>';
    echo '</ul>';
    exit;
}

// Insert into DB
if ($conn) {
    $stmt = $conn->prepare(
        "INSERT INTO inquiries (full_name, phone, email, course, center, message)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $note = "Father: $father_name | DOB: $dob | Gender: $gender | School/College: $school_college";
    $stmt->bind_param('ssssss', $full_name, $phone, $email, $course, $center, $note);
    $stmt->execute();
    $stmt->close();
}

// Regenerate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Redirect back with success flag
header('Location: index.php?success=1#admissions');
exit;
