<section id="about" itemscope itemtype="https://schema.org/AboutPage">
  <div class="container" style="max-width:1280px;">

    <!-- Section label -->
    <div class="text-center mb-5">
      <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill"
        style="background:#FFF7ED;border:1px solid #FFEDD5;color:var(--primary);font-size:0.72rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;">
        <i class="ri-shield-star-line"></i> About Samutkarsh IAS
      </span>
    </div>

    <!-- Split hero block -->
    <div class="about-split rounded-4 overflow-hidden mb-5">

      <!-- Left: dark mission panel -->
      <div class="about-split-left">
        <p class="about-eyebrow">Est. 2016 · Hubballi, Karnataka</p>
        <h2 class="about-headline">Inspiring the Next<br>Generation of<br><em>Civil Servants</em></h2>
        <p class="about-sub">
          Samutkarsh IAS is a socially committed Trust, founded with the mission to produce
          civil servants rooted in Bharatiya ethos — combining rigorous academics with strong
          character and values.
        </p>
        <div class="about-stats-row">
          <div class="about-stat">
            <span class="about-stat-val" id="stat-year">2016</span>
            <span class="about-stat-label">Established</span>
          </div>
          <div class="about-stat-divider"></div>
          <div class="about-stat">
            <span class="about-stat-val stat-num" data-target="172" data-suffix="+">172+</span>
            <span class="about-stat-label">Selections</span>
          </div>
          <div class="about-stat-divider"></div>
          <div class="about-stat">
            <span class="about-stat-val stat-num" data-target="16" data-suffix="+">16+</span>
            <span class="about-stat-label">Centers</span>
          </div>
          <div class="about-stat-divider"></div>
          <div class="about-stat">
            <span class="about-stat-val stat-num" data-target="50000" data-suffix="+">50k+</span>
            <span class="about-stat-label">Students</span>
          </div>
        </div>
      </div>

      <!-- Right: values bento grid -->
      <div class="about-split-right">
        <div class="about-bento">

          <div class="ab-card ab-card--orange">
            <div class="ab-icon"><i class="ri-focus-3-line"></i></div>
            <h4>Vision &amp; Mission</h4>
            <p>Creating civil servants of integrity, shaped by Bharatiya ethos and national purpose.</p>
          </div>

          <div class="ab-card ab-card--blue">
            <div class="ab-icon"><i class="ri-government-line"></i></div>
            <h4>Nation Building</h4>
            <p>Every student we train is a step toward stronger, more equitable governance across India.</p>
          </div>

          <div class="ab-card ab-card--green ab-card--wide">
            <div class="ab-icon"><i class="ri-group-2-line"></i></div>
            <h4>Executive &amp; Academic Council</h4>
            <p>Led by seasoned professionals, retired officers, and experienced educators — united by a common purpose of excellence in civil services education.</p>
          </div>

          <div class="ab-card ab-card--purple ab-card--wide">
            <div class="ab-icon"><i class="ri-map-pin-2-line"></i></div>
            <h4>16+ Centers Across Karnataka</h4>
            <p>From Hubballi to Bengaluru — bringing world-class IAS coaching within reach of every student in the state.</p>
          </div>

        </div>
      </div>

    </div>

  </div>
</section>


<script>
(function () {
  var animated = false;

  function formatNum(n, target) {
    if (target >= 1000) return Math.round(n / 1000) + 'k';
    return n.toString();
  }

  function runCountup() {
    if (animated) return;
    animated = true;
    document.querySelectorAll('.stat-num').forEach(function (el) {
      var target = parseInt(el.getAttribute('data-target'), 10);
      var suffix = el.getAttribute('data-suffix') || '';
      var duration = 1600;
      var start = performance.now();
      var startVal = target > 100 ? Math.round(target * 0.3) : 0;
      function step(now) {
        var p = Math.min((now - start) / duration, 1);
        var eased = 1 - Math.pow(1 - p, 3);
        var cur = Math.round(startVal + (target - startVal) * eased);
        el.textContent = formatNum(cur, target) + suffix;
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) { if (e.isIntersecting) runCountup(); });
  }, { threshold: 0.3 });

  var el = document.getElementById('stat-year');
  if (el) observer.observe(el);
})();
</script>
