<?php
// Fetch testimonials from DB if available
$testimonials = [];
if (isset($conn)) {
    $result = $conn->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY display_order ASC LIMIT 6");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $testimonials[] = $row;
        }
    }
}

// Fallback static testimonials
if (empty($testimonials)) {
    $testimonials = [
        [
            'name'      => 'Vishwanath Diggavi',
            'batch'     => '2018 Batch',
            'avatar'    => 'VD',
            'gradient'  => 'linear-gradient(135deg,#EA580C,#EF4444)',
            'quote'     => 'Samutkarsh centre is best coaching centre in North Karnataka part. It\'s easy to learn about civil service examination because teachers directly interact with students. I learned about CSE as well as enjoyed cultural activities held in this institute.',
            'rating'    => 5,
        ],
        [
            'name'      => 'Runald Jadhav',
            'batch'     => '2018 Batch',
            'avatar'    => 'RJ',
            'gradient'  => 'linear-gradient(135deg,#3B82F6,#6366F1)',
            'quote'     => 'It was a great experience where we got academic coaching and our cultural values and moral guidance. We give through our results all we need is your care and support. Thank you to all of Samutkarsh team.',
            'rating'    => 5,
        ],
        [
            'name'      => 'Ramya Gayakwad',
            'batch'     => '2018 Batch',
            'avatar'    => 'RG',
            'gradient'  => 'linear-gradient(135deg,#22C55E,#10B981)',
            'quote'     => 'I really liked the way institute helped us through our preparation. English workshop was very nice — we learnt about our friends and how to interact. Thank you sir for giving the opportunity to be part of this institute.',
            'rating'    => 5,
        ],
    ];
}
?>

<section id="testimonials">
  <div class="container" style="max-width:1280px;">

    <!-- Header -->
    <div class="text-center mb-5" style="max-width:640px;margin-left:auto;margin-right:auto;">
      <span class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3"
        style="background:#FFF7ED;border:1px solid #FFEDD5;color:var(--primary);font-size:0.75rem;font-weight:600;">
        <i class="ri-user-smile-line"></i> Student Testimonials
      </span>
      <h2 class="section-title mb-3">What Our Students <span class="text-primary">Say</span></h2>
      <p style="color:var(--slate-600);font-size:0.9rem;line-height:1.8;">
        Hear from our successful students about their transformative journey at Samutkarsh IAS Academy.
      </p>
    </div>

    <!-- Cards -->
    <div class="row g-4">
      <?php foreach ($testimonials as $t):
        $gradient = $t['gradient'] ?? 'linear-gradient(135deg,#EA580C,#EF4444)';
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="testimonial-card d-flex flex-column h-100 position-relative" style="padding-top:1.75rem;">

          <!-- Gradient top bar -->
          <div style="position:absolute;top:0;left:0;right:0;height:3px;background:<?php echo htmlspecialchars($gradient); ?>;border-radius:1rem 1rem 0 0;"></div>

          <!-- Quote icon + Stars -->
          <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center justify-content-center rounded-circle"
              style="width:32px;height:32px;background:<?php echo htmlspecialchars($gradient); ?>;">
              <i class="ri-double-quotes-l" style="color:#fff;font-size:0.9rem;"></i>
            </div>
            <div class="stars">
              <?php for ($i = 0; $i < (int)($t['rating'] ?? 5); $i++): ?>
                <i class="ri-star-fill"></i>
              <?php endfor; ?>
            </div>
          </div>

          <!-- Quote text -->
          <p class="flex-grow-1" style="color:var(--slate-600);font-style:italic;font-size:0.875rem;line-height:1.8;margin-bottom:1.25rem;">
            &ldquo;<?php echo htmlspecialchars($t['quote']); ?>&rdquo;
          </p>

          <!-- Student Info -->
          <div class="pt-3" style="border-top:1px solid var(--slate-100);">
            <h5 class="fw-bold mb-0" style="font-size:0.9rem;color:var(--slate-900);">
              <?php echo htmlspecialchars($t['name']); ?>
            </h5>
            <span class="d-inline-block px-2 py-0 mt-1" style="font-size:0.7rem;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--slate-500);background:var(--slate-100);border-radius:999px;">
              <?php echo htmlspecialchars($t['batch']); ?>
            </span>
          </div>

        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
