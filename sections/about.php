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

<style>
/* ---- About Section Redesign ---- */
#about {
  padding: 5rem 0;
  background:
    radial-gradient(ellipse 60% 50% at 10% 20%, rgba(234,88,12,0.06) 0%, transparent 70%),
    radial-gradient(ellipse 50% 60% at 90% 80%, rgba(37,99,235,0.05) 0%, transparent 70%),
    radial-gradient(ellipse 40% 40% at 50% 50%, rgba(234,88,12,0.03) 0%, transparent 80%),
    #fafafa;
  position: relative;
}
#about::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 320px; height: 320px;
  background: radial-gradient(circle, rgba(234,88,12,0.07) 0%, transparent 65%);
  border-radius: 50%;
  pointer-events: none;
}
#about::after {
  content: '';
  position: absolute;
  bottom: -40px; left: -40px;
  width: 260px; height: 260px;
  background: radial-gradient(circle, rgba(37,99,235,0.06) 0%, transparent 65%);
  border-radius: 50%;
  pointer-events: none;
}
#about .container { position: relative; z-index: 1; }

/* Split block */
.about-split {
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 480px;
  box-shadow: 0 24px 64px rgba(15,23,42,0.13);
  border: 1px solid var(--slate-100);
}

/* Left dark panel */
.about-split-left {
  background: var(--slate-900);
  padding: 3.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.about-eyebrow {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--primary);
  margin-bottom: 1.25rem;
}
.about-headline {
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 800;
  line-height: 1.15;
  letter-spacing: -0.025em;
  color: #fff;
  margin-bottom: 1.25rem;
}
.about-headline em {
  font-style: normal;
  background: linear-gradient(90deg, var(--primary), #F97316);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
.about-sub {
  font-size: 0.9rem;
  color: #94a3b8;
  line-height: 1.75;
  margin-bottom: 2.5rem;
  max-width: 400px;
}

/* Stats row inside dark panel */
.about-stats-row {
  display: flex;
  align-items: center;
  gap: 0;
  padding-top: 2rem;
  border-top: 1px solid rgba(255,255,255,0.1);
}
.about-stat {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 0 0.5rem;
}
.about-stat-val {
  font-size: 1.75rem;
  font-weight: 800;
  color: #fff;
  line-height: 1;
  letter-spacing: -0.02em;
}
.about-stat-label {
  font-size: 0.7rem;
  color: #64748b;
  font-weight: 500;
  margin-top: 4px;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}
.about-stat-divider {
  width: 1px;
  height: 36px;
  background: rgba(255,255,255,0.1);
  flex-shrink: 0;
}

/* Right bento panel */
.about-split-right {
  background: var(--slate-50);
  padding: 2rem;
  display: flex;
  align-items: center;
}
.about-bento {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.875rem;
  width: 100%;
}
.ab-card {
  background: #fff;
  border-radius: 0.875rem;
  padding: 1.5rem;
  border: 1px solid var(--slate-100);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  position: relative;
  overflow: hidden;
}
.ab-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  border-radius: 0.875rem 0.875rem 0 0;
}
.ab-card--orange::before { background: linear-gradient(90deg, var(--primary), #F97316); }
.ab-card--blue::before   { background: linear-gradient(90deg, #3B82F6, #6366F1); }
.ab-card--green::before  { background: linear-gradient(90deg, #10B981, #059669); }
.ab-card--purple::before { background: linear-gradient(90deg, #8B5CF6, #A855F7); }

.ab-card--wide { grid-column: span 2; }

.ab-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(15,23,42,0.1);
}
.ab-icon {
  width: 40px; height: 40px;
  border-radius: 0.5rem;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
  margin-bottom: 0.875rem;
}
.ab-card--orange .ab-icon { background: #FFF7ED; color: var(--primary); }
.ab-card--blue .ab-icon   { background: #EFF6FF; color: #3B82F6; }
.ab-card--green .ab-icon  { background: #ECFDF5; color: #10B981; }
.ab-card--purple .ab-icon { background: #F5F3FF; color: #8B5CF6; }

.ab-card h4 {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--slate-900);
  margin-bottom: 0.4rem;
}
.ab-card p {
  font-size: 0.8rem;
  color: var(--slate-500);
  line-height: 1.6;
  margin: 0;
}

/* Responsive */
@media (max-width: 991.98px) {
  .about-split { grid-template-columns: 1fr; }
  .about-split-left { padding: 2.5rem; }
  .about-split-right { padding: 1.5rem; }
  .about-stats-row { gap: 0; }
}
@media (max-width: 767.98px) {
  .about-split-left { padding: 2rem 1.5rem; }
  .about-split-right { padding: 1.25rem; }
  .about-bento { gap: 0.625rem; }
  .ab-card { padding: 1.1rem; }
  .about-headline { font-size: 1.75rem; }
  .about-stat-val { font-size: 1.4rem; }
}
@media (max-width: 575.98px) {
  .about-bento { grid-template-columns: 1fr; }
  .ab-card--wide { grid-column: span 1; }
  .about-stats-row { flex-wrap: wrap; row-gap: 1rem; }
  .about-stat-divider { display: none; }
  .about-stat { flex: 0 0 50%; }
}
</style>

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
