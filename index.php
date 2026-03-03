<?php
session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// DB connection (sections degrade gracefully if $conn is null)
require_once __DIR__ . '/includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Samutkarsh IAS Academy &mdash; Karnataka's Premier Civil Services Institute</title>
  <meta name="description" content="Samutkarsh IAS Academy — 10+ years of excellence in civil services coaching. 500+ selections, 16+ centers across Karnataka. Join Shraddha-Medha, Utkarsh, IAS Coaching and more.">
  <meta name="keywords" content="IAS coaching Karnataka, UPSC coaching Hubballi, KAS coaching, civil services academy, Samutkarsh IAS">
  <meta name="author" content="Samutkarsh IAS Academy">

  <!-- Open Graph -->
  <meta property="og:title" content="Samutkarsh IAS Academy">
  <meta property="og:description" content="Karnataka's Premier Civil Services Coaching Institute. 500+ selections, 16+ centers.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://samutkarshias.in">
  <meta property="og:image" content="assets/images/logo.webp">

  <!-- Favicon -->
  <link rel="icon" type="image/webp" href="assets/images/logo.webp">

  <!-- Bootstrap 5 CSS -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  <!-- Remix Icons (React Icons compatible set) -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
  >

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet"
  >

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>

  <!-- Navbar -->
  <?php include 'sections/navbar.php'; ?>

  <!-- Hero -->
  <?php include 'sections/hero.php'; ?>

  <!-- About -->
  <?php include 'sections/about.php'; ?>

  <!-- Courses -->
  <?php include 'sections/courses.php'; ?>

  <!-- Admissions (with contact form) -->
  <?php include 'sections/admissions.php'; ?>

  <!-- Testimonials / Success Stories -->
  <?php include 'sections/testimonials.php'; ?>

  <!-- Locations / Centers -->
  <?php include 'sections/locations.php'; ?>

  <!-- Footer -->
  <?php include 'sections/footer.php'; ?>

  <!-- WhatsApp FAB -->
  <a href="https://wa.me/919663424767" target="_blank" rel="noopener noreferrer"
    class="whatsapp-fab" aria-label="Chat on WhatsApp">
    <i class="ri-whatsapp-line"></i>
  </a>

  <!-- Bootstrap 5 JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmDX7dTa+NMnJXCpJBSGcxnbF0oV"
    crossorigin="anonymous"
  ></script>

  <!-- Smooth scroll + active nav highlight -->
  <script>
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
      anchor.addEventListener('click', function(e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          // Close mobile menu if open
          var navCollapse = document.getElementById('navbarMenu');
          if (navCollapse && navCollapse.classList.contains('show')) {
            bootstrap.Collapse.getInstance(navCollapse)?.hide();
          }
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    });

    // Active nav link on scroll
    var sections = document.querySelectorAll('section[id]');
    var navLinks = document.querySelectorAll('#mainNavbar .nav-link');

    window.addEventListener('scroll', function() {
      var scrollY = window.scrollY + 100;
      sections.forEach(function(section) {
        if (scrollY >= section.offsetTop && scrollY < section.offsetTop + section.offsetHeight) {
          navLinks.forEach(function(link) {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + section.id) {
              link.classList.add('active');
            }
          });
        }
      });
    });
  </script>

</body>
</html>
<?php
if (isset($conn) && $conn) {
    $conn->close();
}
?>
