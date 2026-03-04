<?php
session_start();

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/helpers.php';

// Already logged in
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error  = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        $error = 'Security check failed. Please try again.';
    } else {
        $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $error = 'Please enter your email and password.';
        } else {
            require_once __DIR__ . '/../includes/db.php';

            if (!$conn) {
                $error = 'Database connection failed. Please try again later.';
            } else {
                $stmt = $conn->prepare(
                    'SELECT id, email, full_name, role, is_active, password_hash FROM admin_users WHERE email = ? LIMIT 1'
                );
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user   = $result->fetch_assoc();
                $stmt->close();

                // Always run password_verify to prevent timing attacks
                $hash_to_check = $user['password_hash'] ?? '$2y$12$invalidhashpadding000000000000000000000000000000000000000';
                $valid = password_verify($password, $hash_to_check);

                if (!$user || !$valid || (int) $user['is_active'] !== 1) {
                    usleep(300000); // 300ms brute-force penalty
                    $error = 'Invalid email or password.';
                } else {
                    // Successful login
                    session_regenerate_id(true);
                    $_SESSION['admin_id']       = $user['id'];
                    $_SESSION['admin_email']    = $user['email'];
                    $_SESSION['admin_name']     = $user['full_name'];
                    $_SESSION['admin_role']     = $user['role'];
                    $_SESSION['last_activity']  = time();
                    // Fresh CSRF token after login
                    $_SESSION['csrf_token']     = bin2hex(random_bytes(32));

                    header('Location: dashboard.php');
                    exit;
                }
            }
        }
    }
}

// Reason messages from redirects
$reason = $_GET['reason'] ?? '';
if ($reason === 'session' && !$error) {
    $error = 'Please log in to access the admin panel.';
} elseif ($reason === 'timeout' && !$error) {
    $error = 'Your session expired due to inactivity. Please log in again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Admin Login — Samutkarsh IAS</title>

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/panel.css?v=<?php echo filemtime(__DIR__ . '/assets/css/panel.css'); ?>">
</head>
<body>

<div class="login-page">
  <div class="login-card">

    <div class="login-brand">
      <span class="login-brand-name">Samutkarsh IAS Academy</span>
      <span class="login-brand-sub">Admin Panel</span>
    </div>

    <?php if ($error): ?>
      <div class="flash-alert alert alert-danger" role="alert">
        <i class="ri-error-warning-line me-1"></i>
        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_GET['logged_out'])): ?>
      <div class="flash-alert alert alert-success" role="alert">
        <i class="ri-checkbox-circle-line me-1"></i>
        You have been logged out.
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <?php echo csrf_field(); ?>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
          placeholder="admin@samutkarshias.in"
          required
          autofocus
        >
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-control"
          placeholder="Enter your password"
          required
        >
      </div>

      <button type="submit" class="btn btn-login">
        <i class="ri-login-box-line me-1"></i> Sign In
      </button>
    </form>

  </div>
</div>

</body>
</html>
