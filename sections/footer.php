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
          <form id="footer-contact-form" class="footer-form" <?php if ($use_emailjs) { ?>data-emailjs-public-key="<?php echo htmlspecialchars($emailjs_key); ?>" data-emailjs-service-id="<?php echo htmlspecialchars($emailjs_service); ?>" data-emailjs-template-id="<?php echo htmlspecialchars($emailjs_template); ?>"<?php } else { ?>action="submit_contact.php" method="POST"<?php } ?>>
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

<style>
/* ---- Footer Redesign ---- */
#footer {
  background: #0f172a;
  color: #94a3b8;
  font-size: 0.875rem;
}

/* Accent bar */
.footer-accent-bar {
  background: linear-gradient(90deg, #1e293b 0%, #1a2744 50%, #1e293b 100%);
  border-top: 3px solid #ea580c;
  padding: 1.25rem 0;
}
.footer-brand-name {
  font-size: 0.9rem;
  font-weight: 800;
  letter-spacing: 0.06em;
  background: linear-gradient(to right, #fb923c, #fbbf24);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1.2;
}
.footer-brand-sub {
  font-size: 0.72rem;
  color: #64748b;
  margin-top: 2px;
}
.footer-tagline {
  font-size: 0.85rem;
  font-weight: 600;
  color: #fb923c;
  font-style: italic;
  letter-spacing: 0.02em;
}

/* Body */
.footer-body {
  padding: 3.5rem 0 2.5rem;
  border-bottom: 1px solid #1e293b;
}
.footer-about-text {
  color: #64748b;
  font-size: 0.82rem;
  line-height: 1.75;
  margin-bottom: 1.25rem;
}

/* Contact list */
.footer-contact-list {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}
.footer-contact-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  color: #94a3b8;
  text-decoration: none;
  font-size: 0.8rem;
  transition: color 0.2s;
}
a.footer-contact-item:hover { color: #fb923c; }
.footer-contact-icon {
  width: 28px;
  height: 28px;
  border-radius: 6px;
  background: rgba(234,88,12,0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ea580c;
  font-size: 0.9rem;
  flex-shrink: 0;
}

/* Social */
.footer-socials {
  display: flex;
  gap: 0.5rem;
}
.footer-social-btn {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  border: 1px solid #1e293b;
  background: #1e293b;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  text-decoration: none;
  transition: background 0.2s, color 0.2s, border-color 0.2s;
}
.footer-social-btn:hover {
  background: #ea580c;
  border-color: #ea580c;
  color: #fff;
}

/* Columns */
.footer-col-heading {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #cbd5e1;
  margin-bottom: 1rem;
}
.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.footer-links a {
  color: #64748b;
  text-decoration: none;
  font-size: 0.82rem;
  transition: color 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}
.footer-links a::before {
  content: '';
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: #334155;
  display: inline-block;
  transition: background 0.2s;
  flex-shrink: 0;
}
.footer-links a:hover { color: #fb923c; }
.footer-links a:hover::before { background: #ea580c; }

/* Form */
.footer-form {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}
.footer-form-input {
  width: 100%;
  background: #1e293b;
  border: 1px solid #2d3748;
  border-radius: 8px;
  color: #e2e8f0;
  font-size: 0.8rem;
  padding: 0.55rem 0.75rem;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  font-family: inherit;
}
.footer-form-input::placeholder { color: #475569; }
.footer-form-input:focus {
  border-color: #ea580c;
  box-shadow: 0 0 0 3px rgba(234,88,12,0.15);
}
.footer-form-textarea {
  resize: none;
  min-height: 72px;
}
.footer-form-btn {
  background: linear-gradient(135deg, #ea580c, #dc2626);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 0.6rem 1rem;
  font-size: 0.82rem;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s, transform 0.15s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
}
.footer-form-btn:hover { opacity: 0.9; transform: translateY(-1px); }
.footer-form-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.footer-form-status {
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  font-size: 0.78rem;
  font-weight: 500;
  margin-bottom: 0.5rem;
}
.footer-form-success {
  background: rgba(22,163,74,0.15);
  border: 1px solid rgba(22,163,74,0.4);
  color: #4ade80;
}
.footer-form-error {
  background: rgba(220,38,38,0.15);
  border: 1px solid rgba(220,38,38,0.4);
  color: #f87171;
}

/* Bottom bar */
.footer-bottom {
  padding: 1rem 0;
}
.footer-bottom-copy {
  font-size: 0.72rem;
  color: #334155;
}
.footer-bottom a {
  color: #fb923c;
  text-decoration: none;
}
.footer-bottom a:hover { text-decoration: underline; }

/* Responsive */
@media (max-width: 767.98px) {
  .footer-accent-bar { padding: 1rem 0; }
  .footer-body { padding: 2.5rem 0 2rem; }
  .footer-tagline { font-size: 0.8rem; text-align: center; }
  .footer-brand-name { font-size: 0.82rem; }
}
</style>

<?php if (!empty($use_emailjs)): ?>
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
<script>
(function() {
  var form = document.getElementById('footer-contact-form');
  if (!form || !form.dataset.emailjsPublicKey) return;
  var btn = document.getElementById('footer-contact-submit');
  var statusEl = document.getElementById('footer-contact-status');
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line me-1"></i> Sending...';
    statusEl.classList.add('d-none');
    statusEl.className = 'footer-form-status d-none';
    emailjs.sendForm(
      form.dataset.emailjsServiceId,
      form.dataset.emailjsTemplateId,
      form,
      form.dataset.emailjsPublicKey
    ).then(function() {
      statusEl.textContent = 'Message sent successfully!';
      statusEl.className = 'footer-form-status footer-form-success';
      form.reset();
    }).catch(function() {
      statusEl.textContent = 'Failed to send. Please try again.';
      statusEl.className = 'footer-form-status footer-form-error';
    }).finally(function() {
      btn.disabled = false;
      btn.innerHTML = '<i class="ri-send-plane-line me-1"></i> Send Message';
    });
  });
})();
</script>
<?php endif; ?>
