<nav class="navbar navbar-expand-lg" id="mainNavbar">
  <div class="container" style="max-width:1280px;">

    <!-- Brand / Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <img src="assets/images/logo.webp" alt="Samutkarsh IAS Academy" id="navLogo" style="height:52px;width:auto;transition:height 0.3s ease;">
      <span class="fw-bold text-dark ms-2" style="font-size:1rem;letter-spacing:-0.02em;">Samutkarsh IAS</span>
    </a>

    <!-- Mobile Toggle (custom toggle for smooth transition; Bootstrap collapse not used on mobile) -->
    <button class="navbar-toggler" type="button" id="navbarToggler" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class="ri-menu-line fs-5 text-primary" id="navTogglerIcon"></i>
    </button>

    <!-- Nav Links (at end of navbar) -->
    <div class="collapse navbar-collapse justify-content-lg-end" id="navbarMenu" role="navigation">
      <ul class="navbar-nav gap-lg-1 text-center text-lg-end mt-3 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="#hero">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#courses">Courses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#admissions">Admissions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#testimonials">Reviews</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#locations">Locations</a>
        </li>
      </ul>
    </div>

  </div>
</nav>

<script>
(function () {
  var navbar = document.getElementById('mainNavbar');
  var logo = document.getElementById('navLogo');
  var menu = document.getElementById('navbarMenu');
  var toggler = document.getElementById('navbarToggler');
  var togglerIcon = document.getElementById('navTogglerIcon');
  var scrolled = false;
  var isMobile = function () { return window.innerWidth < 992; };

  function setMenuOpen(open) {
    if (!menu || !toggler) return;
    if (open) {
      menu.classList.add('show');
      toggler.setAttribute('aria-expanded', 'true');
      if (togglerIcon) {
        togglerIcon.className = 'ri-close-line fs-5 text-primary';
      }
    } else {
      menu.classList.remove('show');
      toggler.setAttribute('aria-expanded', 'false');
      if (togglerIcon) {
        togglerIcon.className = 'ri-menu-line fs-5 text-primary';
      }
    }
  }

  function toggleMenu() {
    if (!isMobile()) return;
    var open = !menu.classList.contains('show');
    setMenuOpen(open);
  }

  if (toggler && menu) {
    toggler.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });
  }

  document.querySelectorAll('#navbarMenu .nav-link').forEach(function (link) {
    link.addEventListener('click', function () {
      if (isMobile()) setMenuOpen(false);
    });
  });

  window.addEventListener('resize', function () {
    if (!isMobile()) setMenuOpen(false);
  });

  function onScroll() {
    if (window.scrollY > 60) {
      if (!scrolled) {
        navbar.classList.add('navbar--scrolled');
        logo.style.height = '38px';
        scrolled = true;
      }
    } else {
      if (scrolled) {
        navbar.classList.remove('navbar--scrolled');
        logo.style.height = '52px';
        scrolled = false;
      }
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
