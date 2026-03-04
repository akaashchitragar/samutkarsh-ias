<?php
session_start();

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// DB connection (sections degrade gracefully if $conn is null)
require_once __DIR__ . '/includes/db.php';

$base_url = 'https://samutkarshias.in';
$page_title = "Samutkarsh IAS Academy — Karnataka's Premier Civil Services Institute";
$page_description = "Samutkarsh IAS Academy — 10+ years of excellence in civil services coaching. 500+ selections, 16+ centers across Karnataka. Join Shraddha-Medha, Utkarsh, IAS Coaching and more.";
$og_image = $base_url . '/assets/images/logo.webp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta name="keywords" content="IAS coaching Karnataka, UPSC coaching Hubballi, KAS coaching, civil services academy, Samutkarsh IAS, Shraddha-Medha, Utkarsh program, IAS coaching Hubballi">
  <meta name="author" content="Samutkarsh IAS Academy">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo htmlspecialchars($base_url); ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($base_url); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>">
  <meta property="og:image:width" content="512">
  <meta property="og:image:height" content="512">
  <meta property="og:locale" content="en_IN">
  <meta property="og:site_name" content="Samutkarsh IAS Academy">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image); ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/webp" href="assets/images/logo.webp">

  <?php include __DIR__ . '/includes/ga4.php'; ?>

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
  <link rel="stylesheet" href="assets/css/custom.css?v=<?php echo filemtime(__DIR__ . '/assets/css/custom.css'); ?>">

  <!-- Structured Data (JSON-LD) -->
  <script type="application/ld+json">
  <?php echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'EducationalOrganization',
    'name' => 'Samutkarsh IAS Academy',
    'alternateName' => 'Samutkarsh IAS',
    'url' => $base_url,
    'logo' => $og_image,
    'description' => $page_description,
    'foundingDate' => '2016',
    'address' => [
      '@type' => 'PostalAddress',
      'addressLocality' => 'Hubballi',
      'addressRegion' => 'Karnataka',
      'addressCountry' => 'IN',
    ],
    'contactPoint' => [
      '@type' => 'ContactPoint',
      'telephone' => '+91-96634-24767',
      'contactType' => 'customer service',
      'areaServed' => 'IN',
      'availableLanguage' => 'English, Kannada, Hindi',
    ],
  ], JSON_UNESCAPED_SLASHES); ?>
  </script>
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

  <!-- GA4 enhanced events -->
  <script>
  (function() {
    if (typeof gtag !== 'function') return;
    if (document.location.search.indexOf('contact=success') !== -1) {
      gtag('event', 'generate_lead', { method: 'contact_form' });
    }
    if (document.location.search.indexOf('inquiry_sent') !== -1) {
      gtag('event', 'generate_lead', { method: 'inquiry_form' });
    }
    document.addEventListener('click', function(e) {
      var a = e.target.closest('a');
      if (!a || !a.href) return;
      var href = (a.getAttribute('href') || '').trim();
      if (href.indexOf('wa.me') !== -1) {
        gtag('event', 'click', { event_category: 'outbound', event_label: 'WhatsApp', link_url: href });
      } else if (a.target === '_blank' && href.indexOf('http') === 0 && href.indexOf(window.location.hostname) === -1) {
        gtag('event', 'click', { event_category: 'outbound', event_label: href, link_url: href });
      }
    });

    var scrollFired = false;
    function onScrollDepth() {
      if (scrollFired) return;
      var max = document.documentElement.scrollHeight - window.innerHeight;
      if (max <= 0 || window.scrollY >= max * 0.9) {
        scrollFired = true;
        gtag('event', 'scroll', { event_category: 'engagement', percent_scrolled: 90 });
      }
    }
    window.addEventListener('scroll', onScrollDepth, { passive: true });
  })();
  </script>

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
