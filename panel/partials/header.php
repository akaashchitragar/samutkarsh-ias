<?php
// Variables expected from including page:
// $page_title (string)  — page heading
// $user       (array)   — current_user() result
// $flash      (array|null) — get_flash() result (optional, caller can pass null)

$flash = $flash ?? get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?php echo htmlspecialchars($page_title ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?> — Samutkarsh Admin</title>

  <!-- Bootstrap 5.3.3 -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  <!-- Remix Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Panel CSS -->
  <link rel="stylesheet" href="<?php echo htmlspecialchars(panel_url('assets/css/panel.css'), ENT_QUOTES, 'UTF-8'); ?>?v=<?php echo filemtime(__DIR__ . '/../assets/css/panel.css'); ?>">
</head>
<body>

<div class="admin-wrapper">

  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <span class="brand-name">Samutkarsh IAS</span>
      <span class="brand-sub">Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
      <span class="sidebar-section-label">Main</span>

      <a href="<?php echo htmlspecialchars(panel_url('dashboard.php'), ENT_QUOTES, 'UTF-8'); ?>"
         class="sidebar-link <?php echo active_if('dashboard'); ?>">
        <i class="ri-dashboard-line"></i> Dashboard
      </a>

      <a href="<?php echo htmlspecialchars(panel_url('enrollments.php'), ENT_QUOTES, 'UTF-8'); ?>"
         class="sidebar-link <?php echo in_array(basename($_SERVER['PHP_SELF'] ?? '', '.php'), ['enrollments', 'enrollment-detail', 'enrollment-status', 'enrollment-export']) ? 'active' : ''; ?>">
        <i class="ri-file-list-3-line"></i> Enrollments
      </a>

      <a href="<?php echo htmlspecialchars(panel_url('inquiries.php'), ENT_QUOTES, 'UTF-8'); ?>"
         class="sidebar-link <?php echo in_array(basename($_SERVER['PHP_SELF'] ?? '', '.php'), ['inquiries', 'inquiry-toggle']) ? 'active' : ''; ?>">
        <i class="ri-question-answer-line"></i> Inquiries
        <?php if (!empty($uncalled_count) && $uncalled_count > 0): ?>
          <span class="sidebar-badge"><?php echo (int) $uncalled_count; ?></span>
        <?php endif; ?>
      </a>

<a href="<?php echo htmlspecialchars(panel_url('centers.php'), ENT_QUOTES, 'UTF-8'); ?>"
         class="sidebar-link <?php echo in_array(basename($_SERVER['PHP_SELF'] ?? '', '.php'), ['centers', 'center-form']) ? 'active' : ''; ?>">
        <i class="ri-building-2-line"></i> Centers
      </a>

      <div class="sidebar-spacer"></div>

      <a href="<?php echo htmlspecialchars(panel_url('logout.php'), ENT_QUOTES, 'UTF-8'); ?>"
         class="sidebar-link sidebar-link--danger">
        <i class="ri-logout-box-r-line"></i> Logout
      </a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="admin-main">

    <!-- Top bar -->
    <div class="admin-topbar">
      <h1 class="topbar-title"><?php echo htmlspecialchars($page_title ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
      <div class="topbar-user">
        <span class="user-name"><?php echo htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
        <span class="badge" style="background:#EA580C"><?php echo htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
    </div>

    <div class="admin-content">

      <?php if ($flash): ?>
        <div class="flash-alert alert alert-<?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?> alert-dismissible fade show" role="alert">
          <?php echo htmlspecialchars($flash['msg'], ENT_QUOTES, 'UTF-8'); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
