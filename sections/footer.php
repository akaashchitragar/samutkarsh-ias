<?php
if (!defined('EMAILJS_PUBLIC_KEY') && file_exists(__DIR__ . '/../includes/config.php')) {
  require_once __DIR__ . '/../includes/config.php';
}
?>
<footer id="footer" class="bg-dark text-white">
  <div class="container py-4 py-sm-5 py-lg-5" style="max-width:1280px;">
    <div class="row g-4 g-sm-5 g-lg-5">

      <!-- Brand + Contact Info (matches Footer.tsx left block) -->
      <div class="col-12 col-md-6 col-xl-4 order-1">
        <div class="text-center text-md-start mb-3">
          <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2 mb-sm-3">
            <div class="rounded-circle overflow-hidden bg-white shadow-sm flex-shrink-0" style="width:40px;height:40px;border:2px solid rgba(234,88,12,0.4);">
              <img src="assets/images/logo.webp" alt="Samutkarsh IAS Logo" class="w-100 h-100 object-fit-cover" style="object-fit:cover;">
            </div>
            <div class="text-white min-w-0">
              <div class="fw-bold text-base" style="background:linear-gradient(to right,#fb923c,#f87171);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">SAMUTKARSH</div>
              <div class="text-gray-300 font-medium" style="font-size:0.8rem;">IAS Academy &bull; Est. 2016</div>
            </div>
          </div>
          <p class="text-warning fw-semibold mb-3" style="font-size:0.875rem;color:#fb923c !important;">Nation Building Through IAS</p>
        </div>
        <div class="space-y-2 text-center text-md-start">
          <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 rounded p-2 mb-2" style="background:rgba(255,255,255,0.06);">
            <i class="ri-map-pin-line flex-shrink-0" style="color:#f97316;font-size:1rem;"></i>
            <div class="text-gray-300" style="font-size:0.8rem;">
              <p class="fw-medium mb-0 text-white">Hubballi, Karnataka</p>
              <p class="mb-0 opacity-75">India</p>
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 rounded p-2" style="background:rgba(255,255,255,0.06);">
            <i class="ri-mail-line flex-shrink-0" style="color:#60a5fa;font-size:1rem;"></i>
            <a href="mailto:support@samutkarshias.in" class="text-gray-300 text-decoration-none font-medium" style="font-size:0.8rem;">support@samutkarshias.in</a>
          </div>
        </div>
      </div>

      <!-- Quick Links (only these three, same as Footer.tsx) -->
      <div class="col-12 col-md-6 col-xl-4 order-2">
        <h4 class="border-bottom border-secondary pb-2 mb-3 fw-bold" style="font-size:1rem;">Quick Links</h4>
        <ul class="list-unstyled mb-0">
          <li class="mb-1"><a href="terms.php" class="text-gray-300 text-decoration-none py-1 px-2 rounded d-inline-block" style="font-size:0.85rem;">Terms &amp; Conditions</a></li>
          <li class="mb-1"><a href="refund-cancellation.php" class="text-gray-300 text-decoration-none py-1 px-2 rounded d-inline-block" style="font-size:0.85rem;">Refund &amp; Cancellation</a></li>
          <li class="mb-1"><a href="privacy-policy.php" class="text-gray-300 text-decoration-none py-1 px-2 rounded d-inline-block" style="font-size:0.85rem;">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Quick Contact Form (EmailJS, same as Footer.tsx) -->
      <div class="col-12 col-xl-4 order-3">
        <h4 class="border-bottom border-secondary pb-2 mb-3 fw-bold" style="font-size:1rem;">Quick Contact</h4>
        <div id="footer-contact-status" class="p-2 rounded mb-3 d-none" role="alert"></div>
        <?php if (isset($_GET['contact']) && $_GET['contact'] === 'success'): ?>
        <div class="p-2 rounded mb-3 border border-success footer-contact-php-status" style="background:rgba(22,163,74,0.2);">
          <p class="text-success fw-semibold mb-0 small text-center">Message sent successfully!</p>
        </div>
        <?php endif; ?>
        <?php if (isset($_GET['contact']) && $_GET['contact'] === 'error'): ?>
        <div class="p-2 rounded mb-3 border border-danger footer-contact-php-status" style="background:rgba(220,53,69,0.2);">
          <p class="text-danger fw-semibold mb-0 small text-center">Failed to send message. Please try again.</p>
        </div>
        <?php endif; ?>
        <?php
        $use_emailjs = defined('EMAILJS_PUBLIC_KEY') && defined('EMAILJS_SERVICE_ID') && defined('EMAILJS_TEMPLATE_ID');
        if ($use_emailjs) {
          $emailjs_key = EMAILJS_PUBLIC_KEY;
          $emailjs_service = EMAILJS_SERVICE_ID;
          $emailjs_template = EMAILJS_TEMPLATE_ID;
        }
        ?>
        <form id="footer-contact-form" class="mb-0" <?php if ($use_emailjs) { ?>data-emailjs-public-key="<?php echo htmlspecialchars($emailjs_key); ?>" data-emailjs-service-id="<?php echo htmlspecialchars($emailjs_service); ?>" data-emailjs-template-id="<?php echo htmlspecialchars($emailjs_template); ?>"<?php } else { ?>action="submit_contact.php" method="POST"<?php } ?>>
          <div class="mb-2">
            <input type="text" name="first_name" required placeholder="Your Name" class="form-control form-control-sm border-secondary" style="background:rgba(255,255,255,0.08);color:#fff;font-size:0.85rem;">
          </div>
          <div class="mb-2">
            <input type="email" name="email" required placeholder="Your Email" class="form-control form-control-sm border-secondary" style="background:rgba(255,255,255,0.08);color:#fff;font-size:0.85rem;">
          </div>
          <div class="mb-2">
            <textarea name="message" rows="3" required placeholder="Your Message" class="form-control form-control-sm border-secondary resize-none" style="background:rgba(255,255,255,0.08);color:#fff;font-size:0.85rem;resize:none;"></textarea>
          </div>
          <button type="submit" id="footer-contact-submit" class="btn w-100 fw-semibold rounded py-2 small" style="background:linear-gradient(to right,#ea580c,#dc2626);color:#fff;font-size:0.85rem;">Send Message</button>
        </form>
      </div>

    </div>

    <!-- Bottom bar (exact match to Footer.tsx) -->
    <div class="mt-5 pt-4 border-top border-secondary">
      <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3">
        <div class="text-center text-lg-start order-2 order-lg-1">
          <p class="mb-0 text-gray-400" style="font-size:0.7rem;">
            <span class="d-block d-sm-inline">Copyright &copy; <?php echo date('Y'); ?> Samutkarsh Trust</span>
            <span class="d-none d-sm-inline"> | </span>
            <span class="d-block d-sm-inline mt-1 mt-sm-0">
              Designed &amp; Developed by <a href="https://www.webart4u.com/" target="_blank" rel="noopener noreferrer" class="text-warning text-decoration-none" style="color:#fb923c !important;">WebArt4U</a>
            </span>
          </p>
        </div>
        <div class="d-flex align-items-center gap-1 small text-gray-400 order-1 order-lg-2" style="font-size:0.7rem;">
          <span>Made with</span>
          <i class="ri-heart-fill text-danger" style="font-size:0.85rem;"></i>
          <span>for</span>
          <span class="border rounded px-2 py-1" style="border-color:#ea580c !important;color:#fb923c;background:rgba(234,88,12,0.15);">Bharat Matha</span>
        </div>
      </div>
    </div>
  </div>
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
    btn.textContent = 'Sending...';
    statusEl.classList.add('d-none');
    statusEl.classList.remove('border-success', 'border-danger');
    statusEl.removeAttribute('style');
    emailjs.sendForm(
      form.dataset.emailjsServiceId,
      form.dataset.emailjsTemplateId,
      form,
      form.dataset.emailjsPublicKey
    ).then(function() {
      statusEl.textContent = 'Message sent successfully!';
      statusEl.classList.add('border', 'border-success', 'text-success');
      statusEl.style.background = 'rgba(22,163,74,0.2)';
      statusEl.classList.remove('d-none');
      form.reset();
    }).catch(function() {
      statusEl.textContent = 'Failed to send message. Please try again.';
      statusEl.classList.add('border', 'border-danger', 'text-danger');
      statusEl.style.background = 'rgba(220,53,69,0.2)';
      statusEl.classList.remove('d-none');
    }).finally(function() {
      btn.disabled = false;
      btn.textContent = 'Send Message';
    });
  });
})();
</script>
<?php endif; ?>
</footer>
