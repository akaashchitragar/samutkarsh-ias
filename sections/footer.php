<?php
// Config is already loaded by index via includes/db.php. Do not require again (avoids path/permission issues on deployment).
$use_emailjs = defined('EMAILJS_PUBLIC_KEY') && defined('EMAILJS_SERVICE_ID') && defined('EMAILJS_TEMPLATE_ID');
?>
<footer id="footer">

  <!-- Top accent bar -->
  <div class="footer-accent-bar">
    <div class="container" style="max-width:1280px;">
      <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
          <img src="assets/images/logo.webp" alt="Samutkarsh IAS Academy" style="height:40px;width:auto;">
          <div>
            <div class="footer-brand-name">SAMUTKARSH IAS ACADEMY</div>
            <div class="footer-brand-sub">Est. 2016 &bull; Hubballi, Karnataka</div>
          </div>
        </div>
        <p class="footer-tagline mb-0">Nation Building Through IAS</p>
      </div>
    </div>
  </div>

  <!-- Main footer body -->
  <div class="footer-body">
    <div class="container" style="max-width:1280px;">
      <div class="row g-4 g-lg-5">

        <!-- Col 1: About + Contact -->
        <div class="col-12 col-md-5 col-lg-4">
          <p class="footer-about-text">
            Empowering aspirants across Karnataka with world-class IAS coaching since 2016. From Hubballi to Bengaluru — making civil services accessible to every student.
          </p>

          <div class="footer-contact-list">
            <a href="mailto:support@samutkarshias.in" class="footer-contact-item">
              <span class="footer-contact-icon"><i class="ri-mail-line"></i></span>
              <span>support@samutkarshias.in</span>
            </a>
            <div class="footer-contact-item">
              <span class="footer-contact-icon"><i class="ri-map-pin-2-line"></i></span>
              <span>Hubballi &bull; Belagavi &bull; Bengaluru</span>
            </div>
          </div>

          <!-- Social links -->
          <div class="footer-socials mt-4">
            <a href="https://www.youtube.com/@samutkarshias" target="_blank" rel="noopener" class="footer-social-btn" aria-label="YouTube">
              <i class="ri-youtube-line"></i>
            </a>
            <a href="https://www.instagram.com/samutkarshias/" target="_blank" rel="noopener" class="footer-social-btn" aria-label="Instagram">
              <i class="ri-instagram-line"></i>
            </a>
            <a href="https://www.facebook.com/samutkarshias" target="_blank" rel="noopener" class="footer-social-btn" aria-label="Facebook">
              <i class="ri-facebook-line"></i>
            </a>
            <a href="https://wa.me/919591777779" target="_blank" rel="noopener" class="footer-social-btn" aria-label="WhatsApp">
              <i class="ri-whatsapp-line"></i>
            </a>
          </div>
        </div>

        <!-- Col 2: Quick Links -->
        <div class="col-6 col-md-3 col-lg-2 offset-lg-1">
          <h5 class="footer-col-heading">Navigate</h5>
          <ul class="footer-links">
            <li><a href="#hero">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#courses">Programs</a></li>
            <li><a href="#admissions">Admissions</a></li>
            <li><a href="#locations">Locations</a></li>
          </ul>
        </div>

        <!-- Col 3: Legal Links -->
        <div class="col-6 col-md-4 col-lg-2">
          <h5 class="footer-col-heading">Legal</h5>
          <ul class="footer-links">
            <li><a href="terms.php">Terms &amp; Conditions</a></li>
            <li><a href="refund-cancellation.php">Refund &amp; Cancellation</a></li>
            <li><a href="privacy-policy.php">Privacy Policy</a></li>
          </ul>
        </div>

        <!-- Col 4: Quick Contact Form -->
        <div class="col-12 col-lg-3">
          <h5 class="footer-col-heading">Send a Message</h5>
          <div id="footer-contact-status" class="footer-form-status d-none" role="alert"></div>
          <?php if (isset($_GET['contact']) && $_GET['contact'] === 'success'): ?>
          <div class="footer-form-status footer-form-success">Message sent successfully!</div>
          <?php endif; ?>
          <?php if (isset($_GET['contact']) && $_GET['contact'] === 'error'): ?>
          <div class="footer-form-status footer-form-error">Failed to send. Please try again.</div>
          <?php endif; ?>
          <?php
          if ($use_emailjs) {
            $emailjs_key = EMAILJS_PUBLIC_KEY;
            $emailjs_service = EMAILJS_SERVICE_ID;
            $emailjs_template = EMAILJS_TEMPLATE_ID;
          }
          ?>
          <form id="footer-contact-form" class="footer-form">
            <input type="text" name="first_name" required placeholder="Your Name" class="footer-form-input">
            <input type="email" name="email" required placeholder="Your Email" class="footer-form-input">
            <textarea name="message" rows="3" required placeholder="Your Message" class="footer-form-input footer-form-textarea"></textarea>
            <button type="submit" id="footer-contact-submit" class="footer-form-btn">
              <i class="ri-send-plane-line me-1"></i> Send Message
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="footer-bottom">
    <div class="container" style="max-width:1280px;">
      <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
        <p class="mb-0 footer-bottom-copy">
          &copy; <?php echo date('Y'); ?> Samutkarsh Trust. All rights reserved.
        </p>
        <p class="mb-0 footer-bottom-copy">
          Made with <i class="ri-heart-fill" style="color:#f87171;font-size:0.8rem;vertical-align:-1px;"></i> for Bharat &mdash;
          Designed by <a href="https://www.webart4u.com/" target="_blank" rel="noopener noreferrer">WebArt4U</a>
        </p>
      </div>
    </div>
  </div>

</footer>


<script>
(function() {
  var form = document.getElementById('footer-contact-form');
  if (!form) return;
  var btn = document.getElementById('footer-contact-submit');
  var statusEl = document.getElementById('footer-contact-status');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i> Sending...';
    statusEl.className = 'footer-form-status d-none';
    fetch('submit_contact.php', { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function(res) {
        if (!res.ok) throw new Error('server');
        if (typeof gtag === 'function') gtag('event', 'generate_lead', { method: 'contact_form' });
        statusEl.textContent = 'Message sent successfully!';
        statusEl.className = 'footer-form-status footer-form-success';
        form.reset();
      })
      .catch(function() {
        statusEl.textContent = 'Failed to send. Please try again.';
        statusEl.className = 'footer-form-status footer-form-error';
      })
      .finally(function() {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-send-plane-line me-1"></i> Send Message';
      });
  });
})();
</script>
