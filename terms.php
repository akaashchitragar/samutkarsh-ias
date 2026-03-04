<?php
require_once __DIR__ . '/includes/send-headers.php';
$legal_title = 'Terms & Conditions';
$legal_description = 'Read the terms and conditions for Samutkarsh IAS educational services including enrollment procedures, student responsibilities, and service policies.';
require_once __DIR__ . '/includes/legal-head.php';
?>
<?php include __DIR__ . '/sections/navbar.php'; ?>

<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" style="max-width:56rem;">
  <div class="text-center mb-8 sm:mb-12">
    <div class="d-inline-flex align-items-center px-4 py-2 rounded-pill border mb-4" style="background:linear-gradient(to right,#fff7ed,#ffedd5);border-color:#ffedd5 !important;color:#9a3412;">
      <i class="ri-file-text-line me-2"></i>Legal Document
    </div>
    <h1 class="text-3xl sm:text-4xl fw-bold text-dark mb-3">Terms &amp; Conditions</h1>
    <p class="text-secondary fs-5">Please read these terms carefully before using our services</p>
    <p class="small text-muted mt-2">Last updated: January 2025</p>
  </div>

  <div class="d-flex flex-column gap-4">
    <div class="card legal-card shadow-sm">
      <div class="card-header py-3">
        <h2 class="h5 mb-0 d-flex align-items-center"><i class="ri-shield-check-line text-primary me-2"></i>1. Acceptance of Terms</h2>
      </div>
      <div class="card-body text-secondary">
        <p class="mb-2">By accessing and using the services provided by Samutkarsh Trust (R), you accept and agree to be bound by the terms and provision of this agreement.</p>
        <p class="mb-0">These terms apply to all users of our educational services, including students, volunteers, and visitors to our centers.</p>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3">
        <h2 class="h5 mb-0 d-flex align-items-center"><i class="ri-team-line text-primary me-2"></i>2. Services Provided</h2>
      </div>
      <div class="card-body text-secondary">
        <p class="mb-2">Samutkarsh Trust provides:</p>
        <ul class="ps-4 mb-0" style="list-style-type:disc;">
          <li>Civil services preparation coaching (Shraddha-Medha and Utkarsh programs)</li>
          <li>Educational guidance and mentorship</li>
          <li>Personality development programs</li>
          <li>Cultural values integration training</li>
          <li>Mock tests and assessments</li>
          <li>Career counseling services</li>
        </ul>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">3. Enrollment and Fees</h2></div>
      <div class="card-body text-secondary">
        <p><strong>Enrollment Process:</strong> Students must complete the enrollment form and provide accurate information. Admission is subject to availability and eligibility criteria.</p>
        <p><strong>Fee Structure:</strong> All fees must be paid as per the schedule provided during enrollment. Late payment may result in suspension of services.</p>
        <p class="mb-0"><strong>Fee Refund:</strong> Refunds are governed by our separate <a href="refund-cancellation.php">Refund &amp; Cancellation Policy</a>.</p>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">4. Student Responsibilities</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">Students are expected to:</p>
        <ul class="ps-4 mb-0" style="list-style-type:disc;">
          <li>Maintain regular attendance and punctuality</li>
          <li>Respect faculty, staff, and fellow students</li>
          <li>Complete assignments and assessments on time</li>
          <li>Maintain discipline and follow center rules</li>
          <li>Provide accurate personal and contact information</li>
          <li>Inform about any changes in contact details promptly</li>
        </ul>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">5. Intellectual Property</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">All study materials, content, and resources provided by Samutkarsh Trust are proprietary and protected by copyright laws.</p>
        <p class="mb-2">Students are prohibited from:</p>
        <ul class="ps-4 mb-0" style="list-style-type:disc;">
          <li>Reproducing or distributing study materials without permission</li>
          <li>Recording classes without explicit consent</li>
          <li>Sharing login credentials for online platforms</li>
          <li>Using materials for commercial purposes</li>
        </ul>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">6. Code of Conduct</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">All participants must adhere to our code of conduct based on Bharatiya values and ethics:</p>
        <ul class="ps-4 mb-0" style="list-style-type:disc;">
          <li>Respect for all individuals regardless of background</li>
          <li>Honesty and integrity in all interactions</li>
          <li>Commitment to nation-building values</li>
          <li>No discrimination, harassment, or inappropriate behavior</li>
          <li>Maintaining confidentiality of sensitive information</li>
        </ul>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">7. Limitation of Liability</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">Samutkarsh Trust provides educational services to the best of our ability but cannot guarantee specific outcomes or results in competitive examinations.</p>
        <p class="mb-2">We are not liable for:</p>
        <ul class="ps-4 mb-0" style="list-style-type:disc;">
          <li>Individual performance in examinations</li>
          <li>Changes in exam patterns or government policies</li>
          <li>Personal property loss or damage on premises</li>
          <li>Interruptions due to technical issues or force majeure</li>
        </ul>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">8. Privacy and Data Protection</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">We collect and process personal information in accordance with our <a href="privacy-policy.php">Privacy Policy</a>. By using our services, you consent to such processing.</p>
        <p class="mb-0">We are committed to protecting your privacy and will not share personal information with third parties without consent, except as required by law.</p>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">9. Modifications to Terms</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-2">Samutkarsh Trust reserves the right to modify these terms at any time. Changes will be communicated through our website or direct notification.</p>
        <p class="mb-0">Continued use of our services after changes constitutes acceptance of the modified terms.</p>
      </div>
    </div>

    <div class="card legal-card shadow-sm">
      <div class="card-header py-3"><h2 class="h5 mb-0">10. Contact Information</h2></div>
      <div class="card-body text-secondary">
        <p class="mb-3">For questions about these terms, please contact us:</p>
        <div class="p-4 rounded bg-light">
          <p class="mb-1 fw-bold">Samutkarsh Trust (R)</p>
          <p class="mb-1">KLE Tech, BVB Campus, Vidyanagar, Hubballi 580031</p>
          <p class="mb-1">Phone: <a href="tel:+919663424767">+91 96634 24767</a></p>
          <p class="mb-0">Email: <a href="mailto:support@samutkarshias.in">support@samutkarshias.in</a></p>
        </div>
      </div>
    </div>
  </div>

  <p class="text-center text-muted small mt-5">These terms are governed by the laws of India and any disputes shall be subject to the jurisdiction of courts in Karnataka.</p>
</main>

<?php include __DIR__ . '/sections/footer.php'; ?>
<a href="https://wa.me/919663424767" target="_blank" rel="noopener noreferrer" class="whatsapp-fab" aria-label="Chat on WhatsApp"><i class="ri-whatsapp-line"></i></a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmDX7dTa+NMnJXCpJBSGcxnbF0oV" crossorigin="anonymous"></script>
</body>
</html>
