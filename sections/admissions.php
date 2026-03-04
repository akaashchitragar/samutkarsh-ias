<?php
$centers = [];
if (isset($conn)) {
    $result = $conn->query("SELECT id, name, city FROM centers WHERE status = 'active' ORDER BY city ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $centers[] = $row;
        }
    }
}
$fallback_centers = [
    ['name' => 'Hubballi (Main Campus)', 'city' => 'Hubballi'],
    ['name' => 'Gangavati', 'city' => 'Gangavati'],
    ['name' => 'Sirsi', 'city' => 'Sirsi'],
    ['name' => 'Kumta', 'city' => 'Kumta'],
    ['name' => 'Raichuru', 'city' => 'Raichuru'],
    ['name' => 'Bellari', 'city' => 'Bellari'],
    ['name' => 'Hagari Bommanhalli', 'city' => 'Hagari Bommanhalli'],
    ['name' => 'Belagavi', 'city' => 'Belagavi'],
    ['name' => 'Bengaluru', 'city' => 'Bengaluru'],
];
if (empty($centers)) $centers = $fallback_centers;
$csrf = $_SESSION['csrf_token'] ?? '';
?>

<section id="admissions">
  <div class="container" style="max-width:1280px;">

    <div class="text-center mb-5" style="max-width:640px;margin-left:auto;margin-right:auto;">
      <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
        style="background:#FFF7ED;border:1px solid #FFEDD5;color:var(--primary);font-size:0.75rem;font-weight:600;">
        <i class="ri-award-line"></i> Admissions Open — Batch 2026
      </span>
      <h2 class="section-title mb-3">Begin Your <span class="text-primary">Civil Services Journey</span></h2>
      <p style="color:var(--slate-600);font-size:0.9rem;line-height:1.8;">
        Register for a Samutkarsh programme — choose your course, fill the form, and receive your hall ticket via email.
      </p>
    </div>

    <div class="row align-items-start g-5">

      <div class="col-lg-5">
        <div class="steps-line d-flex flex-column gap-4 mb-5">
          <div class="step-item">
            <div class="step-num" style="background:var(--primary);color:#fff;">1</div>
            <div class="pt-1">
              <h5 class="fw-bold mb-1" style="color:var(--slate-900);font-size:0.95rem;">Choose Programme &amp; Fill Form</h5>
              <p style="color:var(--slate-500);font-size:0.85rem;" class="mb-0">
                Select your programme. The form updates to match your choice — no page refresh.
              </p>
            </div>
          </div>
          <div class="step-item">
            <div class="step-num" style="border:2px solid var(--primary);color:var(--primary);background:#fff;">2</div>
            <div class="pt-1">
              <h5 class="fw-bold mb-1" style="color:var(--slate-900);font-size:0.95rem;">Entrance Test / Scholarship Test</h5>
              <p style="color:var(--slate-500);font-size:0.85rem;" class="mb-0">
                Appear for the entrance test at your chosen centre. Merit-based scholarships are available.
              </p>
            </div>
          </div>
          <div class="step-item">
            <div class="step-num" style="background:var(--slate-100);color:var(--slate-400);">3</div>
            <div class="pt-1">
              <h5 class="fw-bold mb-1" style="color:var(--slate-900);font-size:0.95rem;">Receive Hall Ticket &amp; Confirm</h5>
              <p style="color:var(--slate-500);font-size:0.85rem;" class="mb-0">
                Your hall ticket is sent to your email. Pay the course fee and confirm your seat.
              </p>
            </div>
          </div>
        </div>

        <div class="rounded-3 p-4 mb-4" style="background:var(--slate-50);border:1px solid var(--slate-100);">
          <h6 class="fw-bold mb-3" style="color:var(--slate-900);font-size:0.875rem;">
            <i class="ri-information-line text-primary me-1"></i> Important Information
          </h6>
          <ul class="mb-0" style="font-size:0.82rem;color:var(--slate-600);line-height:1.8;padding-left:1.1rem;">
            <li>Shraddha-Medha is open to students in Classes 6–9</li>
            <li>IAS Coaching is for degree &amp; graduate students</li>
            <li>Admission fee for Shraddha-Medha: <strong>&#8377;5,000</strong> (post selection)</li>
            <li>Hall ticket sent to your registered email address</li>
          </ul>
        </div>

        <div class="d-flex align-items-center gap-3">
          <div style="width:44px;height:44px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:1.2rem;flex-shrink:0;">
            <i class="ri-phone-line"></i>
          </div>
          <div>
            <p class="mb-0" style="font-size:0.78rem;color:var(--slate-500);">Have questions? Call us</p>
            <a href="tel:+919663424767" class="fw-bold text-decoration-none" style="color:var(--slate-900);font-size:1rem;">+91 96634 24767</a>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="admission-form-card" id="admission-form-card">
          <h3 class="fw-bold mb-1" style="color:var(--slate-900);font-size:1.3rem;">Enrolment Registration</h3>
          <p style="font-size:0.85rem;color:var(--slate-500);" class="mb-4">Choose your programme and complete the steps. The form adapts to your selection.</p>

          <div id="form-success-message" class="alert alert-success d-flex align-items-center gap-2 mb-4 d-none" role="alert">
            <i class="ri-checkbox-circle-line fs-5"></i>
            <span>Your registration has been submitted! We will reach out within 24 hours.</span>
          </div>

          <div id="form-error-message" class="alert alert-danger d-none mb-4" role="alert"></div>

          <form id="enrollment-form" novalidate>
            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <input type="hidden" name="course" id="course" value="">

            <div id="step-indicator" class="d-flex gap-2 mb-4 flex-wrap">
              <span class="enrollment-step-dot active" data-step="1" style="width:10px;height:10px;border-radius:50%;background:var(--primary);"></span>
              <span class="enrollment-step-dot" data-step="2" style="width:10px;height:10px;border-radius:50%;background:var(--slate-200);"></span>
              <span class="enrollment-step-dot" data-step="3" style="width:10px;height:10px;border-radius:50%;background:var(--slate-200);"></span>
              <span class="enrollment-step-dot" data-step="4" style="width:10px;height:10px;border-radius:50%;background:var(--slate-200);"></span>
              <span class="enrollment-step-dot" data-step="5" style="width:10px;height:10px;border-radius:50%;background:var(--slate-200);"></span>
            </div>

            <div id="step-1" class="enrollment-step">
              <p class="fw-semibold mb-2" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--slate-400);">Step 1 — Choose Programme</p>
              <div class="mb-4">
                <label for="course_select">Interested Course <span class="text-danger">*</span></label>
                <div class="custom-select" id="course_select-wrap" data-name="course_select" data-placeholder="Select course">
                  <input type="hidden" name="course_select" id="course_select" value="">
                  <button type="button" class="custom-select-trigger" id="course_select-trigger" aria-haspopup="listbox" aria-expanded="false">
                    <span class="custom-select-label placeholder">Select course</span>
                    <i class="ri-arrow-down-s-line custom-select-arrow"></i>
                  </button>
                  <div class="custom-select-dropdown" id="course_select-dropdown" role="listbox">
                    <button type="button" class="custom-select-option" role="option" data-value="Shraddha-Medha">Shraddha-Medha (Classes 6–9)</button>
                    <button type="button" class="custom-select-option" role="option" data-value="IAS Coaching">IAS Coaching — Offline, Hubballi</button>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary-custom rounded-3 px-4 py-2" id="btn-next-1">Next <i class="ri-arrow-right-line ms-1"></i></button>
              </div>
            </div>

            <div id="step-2" class="enrollment-step d-none">
              <p class="fw-semibold mb-2" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--slate-400);">Step 2 — Personal Information</p>
              <div class="row g-3 mb-3">
                <div class="col-sm-6">
                  <label for="full_name">Full Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Your full name" minlength="2" maxlength="100">
                </div>
                <div class="col-sm-6">
                  <label for="father_name">Father's Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="father_name" name="father_name" placeholder="Father's full name" minlength="2" maxlength="100">
                </div>
              </div>
              <div id="mother-row" class="row g-3 mb-3 d-none">
                <div class="col-12">
                  <label for="mother_name">Mother's Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="Mother's full name" minlength="2" maxlength="100">
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-sm-6">
                  <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                </div>
                <div class="col-sm-6">
                  <label for="gender">Gender <span class="text-danger">*</span></label>
                  <div class="custom-select" id="gender-wrap" data-name="gender" data-placeholder="Select gender">
                    <input type="hidden" name="gender" id="gender" value="">
                    <button type="button" class="custom-select-trigger" id="gender-trigger" aria-haspopup="listbox" aria-expanded="false">
                      <span class="custom-select-label placeholder">Select gender</span>
                      <i class="ri-arrow-down-s-line custom-select-arrow"></i>
                    </button>
                    <div class="custom-select-dropdown" id="gender-dropdown" role="listbox">
                      <button type="button" class="custom-select-option" role="option" data-value="Male">Male</button>
                      <button type="button" class="custom-select-option" role="option" data-value="Female">Female</button>
                      <button type="button" class="custom-select-option" role="option" data-value="Other">Other</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-12">
                  <label for="caste_category">Caste / Category</label>
                  <input type="text" class="form-control" id="caste_category" name="caste_category" placeholder="e.g. General, OBC, SC, ST" maxlength="50">
                </div>
              </div>
              <div class="mb-3">
                <label for="address">Complete Address <span class="text-danger">*</span></label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address with city, state and pincode" minlength="10"></textarea>
              </div>
              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2" id="btn-prev-2"><i class="ri-arrow-left-line me-1"></i> Back</button>
                <button type="button" class="btn btn-primary-custom rounded-3 px-4 py-2" id="btn-next-2">Next <i class="ri-arrow-right-line ms-1"></i></button>
              </div>
            </div>

            <div id="step-3" class="enrollment-step d-none">
              <p class="fw-semibold mb-2" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--slate-400);">Step 3 — Academic Information</p>
              <div id="academic-shraddha" class="d-none">
                <div class="row g-3 mb-3">
                  <div class="col-sm-6">
                    <label for="school_name">School Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="school_name" name="school_name" placeholder="School name" minlength="2" maxlength="150">
                  </div>
                  <div class="col-sm-6">
                    <label for="class_standard">Class / Standard <span class="text-danger">*</span></label>
                    <div class="custom-select" id="class_standard-wrap" data-name="class_standard" data-placeholder="Select class">
                      <input type="hidden" name="class_standard" id="class_standard" value="">
                      <button type="button" class="custom-select-trigger" id="class_standard-trigger" aria-haspopup="listbox" aria-expanded="false">
                        <span class="custom-select-label placeholder">Select class</span>
                        <i class="ri-arrow-down-s-line custom-select-arrow"></i>
                      </button>
                      <div class="custom-select-dropdown" id="class_standard-dropdown" role="listbox">
                        <button type="button" class="custom-select-option" role="option" data-value="6th">6th</button>
                        <button type="button" class="custom-select-option" role="option" data-value="7th">7th</button>
                        <button type="button" class="custom-select-option" role="option" data-value="8th">8th</button>
                        <button type="button" class="custom-select-option" role="option" data-value="9th">9th</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="academic-college" class="d-none">
                <div class="row g-3 mb-3">
                  <div class="col-sm-6">
                    <label for="college_name">College Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="college_name" name="college_name" placeholder="College name" minlength="2" maxlength="150">
                  </div>
                  <div class="col-sm-6">
                    <label for="stream">Stream <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="stream" name="stream" placeholder="e.g. BA, BSc, BCom" maxlength="100">
                  </div>
                </div>
                <div class="row g-3 mb-3">
                  <div class="col-12">
                    <label for="district">District</label>
                    <input type="text" class="form-control" id="district" name="district" placeholder="Your district" maxlength="80">
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2" id="btn-prev-3"><i class="ri-arrow-left-line me-1"></i> Back</button>
                <button type="button" class="btn btn-primary-custom rounded-3 px-4 py-2" id="btn-next-3">Next <i class="ri-arrow-right-line ms-1"></i></button>
              </div>
            </div>

            <div id="step-4" class="enrollment-step d-none">
              <p class="fw-semibold mb-2" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--slate-400);">Step 4 — Contact &amp; Centre</p>
              <div id="contact-shraddha" class="d-none">
                <div class="row g-3 mb-3">
                  <div class="col-sm-6">
                    <label for="father_phone">Father's Phone <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="father_phone" name="father_phone" placeholder="10-digit number" pattern="[6-9][0-9]{9}" maxlength="10">
                  </div>
                  <div class="col-sm-6">
                    <label for="mother_phone">Mother's Phone</label>
                    <input type="tel" class="form-control" id="mother_phone" name="mother_phone" placeholder="10-digit number" pattern="[6-9][0-9]{9}" maxlength="10">
                  </div>
                </div>
              </div>
              <div id="contact-college" class="d-none">
                <div class="row g-3 mb-3">
                  <div class="col-sm-6">
                    <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="10-digit number" pattern="[6-9][0-9]{9}" maxlength="10">
                  </div>
                  <div class="col-sm-6">
                    <label for="whatsapp_number">WhatsApp Number <span class="text-danger">*</span></label>
                    <input type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number" placeholder="10-digit number" pattern="[6-9][0-9]{9}" maxlength="10">
                  </div>
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-sm-6">
                  <label for="email">Email Address <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" maxlength="150">
                </div>
                <div class="col-sm-6">
                  <label for="center">Preferred Study Centre <span class="text-danger">*</span></label>
                  <div class="custom-select" id="center-wrap" data-name="center" data-placeholder="Choose centre">
                    <input type="hidden" name="center" id="center" value="">
                    <button type="button" class="custom-select-trigger" id="center-trigger" aria-haspopup="listbox" aria-expanded="false">
                      <span class="custom-select-label placeholder">Choose centre</span>
                      <i class="ri-arrow-down-s-line custom-select-arrow"></i>
                    </button>
                    <div class="custom-select-dropdown" id="center-dropdown" role="listbox">
                      <?php foreach ($centers as $c): ?>
                        <button type="button" class="custom-select-option" role="option" data-value="<?php echo htmlspecialchars($c['name']); ?>"><?php echo htmlspecialchars($c['name']); ?><?php if (!empty($c['city']) && $c['city'] !== $c['name']): ?> — <?php echo htmlspecialchars($c['city']); ?><?php endif; ?></button>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2" id="btn-prev-4"><i class="ri-arrow-left-line me-1"></i> Back</button>
                <button type="button" class="btn btn-primary-custom rounded-3 px-4 py-2" id="btn-next-4">Next <i class="ri-arrow-right-line ms-1"></i></button>
              </div>
            </div>

            <div id="step-5" class="enrollment-step d-none">
              <p class="fw-semibold mb-2" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--slate-400);">Step 5 — Review &amp; Submit</p>
              <div id="review-summary" class="rounded-3 p-3 mb-4" style="background:var(--slate-50);border:1px solid var(--slate-100);font-size:0.875rem;"></div>
              <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-3 px-4 py-2" id="btn-prev-5"><i class="ri-arrow-left-line me-1"></i> Back</button>
                <button type="submit" class="btn btn-primary-custom rounded-3 px-4 py-2" id="btn-submit">
                  <span class="btn-text"><i class="ri-send-plane-line me-2"></i>Submit Registration</span>
                  <span class="btn-loading d-none"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...</span>
                </button>
              </div>
            </div>
          </form>

        </div>

        <p class="text-center mt-3 mb-0" style="font-size:0.78rem;color:var(--slate-400);" id="form-footer-text">
          By submitting, you agree to be contacted by our admissions team.
        </p>
      </div>

    </div>
  </div>
</section>

<script>
(function() {
  var currentStep = 1;
  var maxStep = 5;
  var courseValue = '';
  var centersList = <?php echo json_encode(array_map(function($c) { return ['name' => $c['name'], 'city' => $c['city'] ?? '']; }, $centers)); ?>;

  function getEl(id) { return document.getElementById(id); }
  function showStep(step) {
    for (var i = 1; i <= maxStep; i++) {
      var el = getEl('step-' + i);
      var dot = document.querySelector('.enrollment-step-dot[data-step="' + i + '"]');
      if (el) el.classList.toggle('d-none', i !== step);
      if (dot) {
        dot.classList.toggle('active', i === step);
        dot.style.background = i === step ? 'var(--primary)' : (i < step ? 'var(--primary)' : 'var(--slate-200)');
      }
    }
    currentStep = step;
  }

  function isShraddha() { return courseValue === 'Shraddha-Medha'; }

  function updateFormForCourse() {
    courseValue = getEl('course_select').value;
    getEl('course').value = courseValue;
    var shraddha = isShraddha();
    getEl('mother-row').classList.toggle('d-none', shraddha);
    getEl('academic-shraddha').classList.toggle('d-none', !shraddha);
    getEl('academic-college').classList.toggle('d-none', shraddha);
    getEl('contact-shraddha').classList.toggle('d-none', !shraddha);
    getEl('contact-college').classList.toggle('d-none', shraddha);
    if (shraddha) {
      getEl('mother_name').removeAttribute('required');
      getEl('phone_number').removeAttribute('required');
      getEl('whatsapp_number').removeAttribute('required');
      getEl('father_phone').setAttribute('required', 'required');
      getEl('school_name').setAttribute('required', 'required');
      getEl('class_standard').setAttribute('required', 'required');
      getEl('college_name').removeAttribute('required');
      getEl('stream').removeAttribute('required');
    } else {
      getEl('mother_name').setAttribute('required', 'required');
      getEl('father_phone').removeAttribute('required');
      getEl('mother_phone').removeAttribute('required');
      getEl('phone_number').setAttribute('required', 'required');
      getEl('whatsapp_number').setAttribute('required', 'required');
      getEl('school_name').removeAttribute('required');
      getEl('class_standard').removeAttribute('required');
      getEl('college_name').setAttribute('required', 'required');
      getEl('stream').setAttribute('required', 'required');
    }
  }

  function validateStep(step) {
    var stepEl = getEl('step-' + step);
    if (!stepEl) return true;
    var inputs = stepEl.querySelectorAll('input[required], input[name="course_select"], textarea[required]');
    var valid = true;
    inputs.forEach(function(inp) {
      if (inp.offsetParent === null) return;
      var wrapper = inp.closest('.custom-select') || inp;
      if (!inp.value.trim()) {
        valid = false;
        if (wrapper.classList) wrapper.classList.add('is-invalid');
        inp.classList.add('is-invalid');
      } else {
        if (wrapper.classList) wrapper.classList.remove('is-invalid');
        inp.classList.remove('is-invalid');
      }
    });
    stepEl.querySelectorAll('.custom-select').forEach(function(wrap) {
      var hid = wrap.querySelector('input[type="hidden"]');
      if (hid && hid.hasAttribute('required') && !hid.value.trim()) {
        valid = false;
        wrap.classList.add('is-invalid');
      }
    });
    return valid;
  }

  function buildReviewHtml() {
    var d = {};
    ['full_name','father_name','mother_name','date_of_birth','gender','address','caste_category','school_name','class_standard','college_name','stream','district','father_phone','mother_phone','phone_number','whatsapp_number','email','center'].forEach(function(k) {
      var el = document.querySelector('[name="' + k + '"]');
      d[k] = el ? el.value : '';
    });
    var labels = {
      full_name: 'Full Name', father_name: "Father's Name", mother_name: "Mother's Name",
      date_of_birth: 'DOB', gender: 'Gender', address: 'Address', caste_category: 'Category',
      school_name: 'School', class_standard: 'Class', college_name: 'College', stream: 'Stream', district: 'District',
      father_phone: "Father's Phone", mother_phone: "Mother's Phone", phone_number: 'Phone', whatsapp_number: 'WhatsApp',
      email: 'Email', center: 'Centre'
    };
    var html = '<p class="mb-2"><strong>Course:</strong> ' + (getEl('course_select').value || '') + '</p>';
    for (var key in labels) {
      if (d[key]) html += '<p class="mb-1"><strong>' + labels[key] + ':</strong> ' + (d[key].substring ? d[key].substring(0, 80) : d[key]) + '</p>';
    }
    return html;
  }

  function nextClick(step) {
    if (step === 1) {
      courseValue = getEl('course_select').value;
      var wrap = getEl('course_select-wrap');
      if (!courseValue) { if (wrap) wrap.classList.add('is-invalid'); return; }
      if (wrap) wrap.classList.remove('is-invalid');
      getEl('course').value = courseValue;
      updateFormForCourse();
      showStep(2);
    } else if (step === 2) {
      if (!validateStep(2)) return;
      showStep(3);
    } else if (step === 3) {
      if (!validateStep(3)) return;
      showStep(4);
    } else if (step === 4) {
      if (!validateStep(4)) return;
      getEl('review-summary').innerHTML = buildReviewHtml();
      showStep(5);
    }
  }

  function prevClick(step) {
    showStep(step - 1);
  }

  getEl('btn-next-1').addEventListener('click', function() { nextClick(1); });
  getEl('btn-next-2').addEventListener('click', function() { nextClick(2); });
  getEl('btn-next-3').addEventListener('click', function() { nextClick(3); });
  getEl('btn-next-4').addEventListener('click', function() { nextClick(4); });
  getEl('btn-prev-2').addEventListener('click', function() { prevClick(2); });
  getEl('btn-prev-3').addEventListener('click', function() { prevClick(3); });
  getEl('btn-prev-4').addEventListener('click', function() { prevClick(4); });
  getEl('btn-prev-5').addEventListener('click', function() { prevClick(5); });

  function initCustomSelects() {
    document.querySelectorAll('.custom-select').forEach(function(wrap) {
      var trigger = wrap.querySelector('.custom-select-trigger');
      var dropdown = wrap.querySelector('.custom-select-dropdown');
      var hidden = wrap.querySelector('input[type="hidden"]');
      var label = wrap.querySelector('.custom-select-label');
      var options = wrap.querySelectorAll('.custom-select-option');
      if (!trigger || !dropdown || !hidden || !label) return;

      var placeholderText = wrap.getAttribute('data-placeholder') || (label.classList.contains('placeholder') ? label.textContent : 'Select');
      if (!hidden.value.trim()) {
        label.textContent = placeholderText;
        label.classList.add('placeholder');
      }

      function close() {
        trigger.classList.remove('open');
        dropdown.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
      }
      function open() {
        document.querySelectorAll('.custom-select-dropdown.open').forEach(function(d) {
          d.classList.remove('open');
          var t = d.closest('.custom-select').querySelector('.custom-select-trigger');
          if (t) t.classList.remove('open').setAttribute('aria-expanded', 'false');
        });
        trigger.classList.add('open');
        dropdown.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
      }

      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        if (dropdown.classList.contains('open')) close(); else open();
      });
      options.forEach(function(opt) {
        opt.addEventListener('click', function(e) {
          e.preventDefault();
          var val = this.getAttribute('data-value');
          var text = this.textContent.trim();
          hidden.value = val;
          label.textContent = text;
          label.classList.remove('placeholder');
          wrap.classList.remove('is-invalid');
          options.forEach(function(o) { o.classList.remove('selected'); });
          this.classList.add('selected');
          close();
          if (hidden.name === 'course_select') {
            courseValue = val;
            getEl('course').value = val;
            updateFormForCourse();
          }
        });
      });
      document.addEventListener('click', function(e) {
        if (!wrap.contains(e.target)) close();
      });
    });
  }
  initCustomSelects();

  getEl('enrollment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var btn = getEl('btn-submit');
    var btnText = btn.querySelector('.btn-text');
    var btnLoading = btn.querySelector('.btn-loading');
    btn.disabled = true;
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    getEl('form-error-message').classList.add('d-none');

    var formData = new FormData(this);
    formData.delete('course_select');
    fetch('submit_enrollment.php', {
      method: 'POST',
      body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.success) {
        if (typeof gtag === 'function') {
          var course = formData.get('course') || '';
          gtag('event', 'generate_lead', { method: 'enrollment_form', course: course });
        }
        getEl('enrollment-form').classList.add('d-none');
        getEl('form-success-message').classList.remove('d-none');
        getEl('form-footer-text').classList.add('d-none');
        document.getElementById('admission-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        var errEl = getEl('form-error-message');
        errEl.innerHTML = (data.errors && data.errors.length) ? data.errors.join('<br>') : 'Something went wrong. Please try again.';
        errEl.classList.remove('d-none');
      }
    })
    .catch(function() {
      getEl('form-error-message').innerHTML = 'Network error. Please try again.';
      getEl('form-error-message').classList.remove('d-none');
    })
    .finally(function() {
      btn.disabled = false;
      btnText.classList.remove('d-none');
      btnLoading.classList.add('d-none');
    });
  });

  if (window.location.hash === '#admissions' && window.location.search.indexOf('success=1') !== -1) {
    getEl('form-success-message').classList.remove('d-none');
    getEl('enrollment-form').classList.add('d-none');
  }
})();
</script>
