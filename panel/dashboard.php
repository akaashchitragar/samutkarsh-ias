<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$page_title = 'Dashboard';
$user = current_user();

// ── Counts ──────────────────────────────────────────────────────────────────
$counts = [
    'shraddhamedha'   => 0,
    'utkarsh'         => 0,
    'comprehensive'   => 0,
    'mentorship'      => 0,
    'inquiries_total' => 0,
    'inquiries_new'   => 0,
    'centers_active'  => 0,
    'testimonials'    => 0,
];

$recent_inquiries   = [];
$recent_enrollments = [];

if ($conn) {
    // Enrollment counts
    foreach ([
        'shraddhamedha' => 'shraddhamedha_enrollments',
        'utkarsh'       => 'utkarsh_enrollments',
        'comprehensive' => 'comprehensive_enrollments',
        'mentorship'    => 'mentorship_enrollments',
    ] as $key => $table) {
        $r = $conn->query("SELECT COUNT(*) FROM `{$table}`");
        $counts[$key] = (int) $r->fetch_row()[0];
    }

    $counts['enrollments_total'] = $counts['shraddhamedha'] + $counts['utkarsh']
                                 + $counts['comprehensive'] + $counts['mentorship'];

    // Inquiry counts
    $r = $conn->query('SELECT COUNT(*) FROM inquiries');
    $counts['inquiries_total'] = (int) $r->fetch_row()[0];

    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $counts['inquiries_new'] = (int) $r->fetch_row()[0];

    // Centers
    $r = $conn->query("SELECT COUNT(*) FROM centers WHERE status = 'active'");
    $counts['centers_active'] = (int) $r->fetch_row()[0];

    // Testimonials
    $r = $conn->query('SELECT COUNT(*) FROM testimonials WHERE is_active = 1');
    $counts['testimonials'] = (int) $r->fetch_row()[0];

    // Recent inquiries
    $r = $conn->query(
        'SELECT id, full_name, phone, course, center, is_called, created_at
         FROM inquiries ORDER BY created_at DESC LIMIT 5'
    );
    $recent_inquiries = $r->fetch_all(MYSQLI_ASSOC);

    // Recent enrollments (UNION across all 4 tables)
    $union_sql = "
        SELECT id, full_name, 'Shraddha-Medha' AS course, center, status, created_at
        FROM shraddhamedha_enrollments
        UNION ALL
        SELECT id, full_name, 'Utkarsh' AS course, center, status, created_at
        FROM utkarsh_enrollments
        UNION ALL
        SELECT id, full_name, 'Comprehensive' AS course, center, status, created_at
        FROM comprehensive_enrollments
        UNION ALL
        SELECT id, full_name, 'Mentorship' AS course, center, status, created_at
        FROM mentorship_enrollments
        ORDER BY created_at DESC LIMIT 8
    ";
    $r = $conn->query($union_sql);
    $recent_enrollments = $r->fetch_all(MYSQLI_ASSOC);
}

// Pass uncalled count to sidebar badge
$uncalled_count = $counts['inquiries_new'];

include __DIR__ . '/partials/header.php';
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--orange">
      <div class="stat-card-num"><?php echo number_format($counts['enrollments_total']); ?></div>
      <div class="stat-card-label">Total Enrollments</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--amber">
      <div class="stat-card-num"><?php echo number_format($counts['inquiries_new']); ?></div>
      <div class="stat-card-label">Uncalled Inquiries</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--green">
      <div class="stat-card-num"><?php echo number_format($counts['centers_active']); ?></div>
      <div class="stat-card-label">Active Centers</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--blue">
      <div class="stat-card-num"><?php echo number_format($counts['testimonials']); ?></div>
      <div class="stat-card-label">Active Testimonials</div>
    </div>
  </div>
</div>

<!-- Course Breakdown -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--orange">
      <div class="stat-card-num"><?php echo number_format($counts['shraddhamedha']); ?></div>
      <div class="stat-card-label">Shraddha-Medha</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--blue">
      <div class="stat-card-num"><?php echo number_format($counts['utkarsh']); ?></div>
      <div class="stat-card-label">Utkarsh</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--green">
      <div class="stat-card-num"><?php echo number_format($counts['comprehensive']); ?></div>
      <div class="stat-card-label">Comprehensive / IAS</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card stat-card--purple">
      <div class="stat-card-num"><?php echo number_format($counts['mentorship']); ?></div>
      <div class="stat-card-label">Mentorship</div>
    </div>
  </div>
</div>

<!-- Tables row -->
<div class="row g-4">

  <!-- Recent Inquiries -->
  <div class="col-lg-5">
    <div class="panel-card">
      <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="ri-question-answer-line me-1"></i> Recent Inquiries</h2>
        <a href="inquiries.php" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">View All</a>
      </div>
      <?php if (empty($recent_inquiries)): ?>
        <div class="empty-state"><i class="ri-inbox-line"></i><p>No inquiries yet.</p></div>
      <?php else: ?>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Course</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_inquiries as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size:0.8rem"><?php echo htmlspecialchars($row['course'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <?php if ((int) $row['is_called']): ?>
                      <span class="status-badge badge-called">Called</span>
                    <?php else: ?>
                      <span class="status-badge badge-uncalled">Pending</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Recent Enrollments -->
  <div class="col-lg-7">
    <div class="panel-card">
      <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="ri-file-list-3-line me-1"></i> Recent Enrollments</h2>
        <a href="enrollments.php" class="btn btn-sm btn-outline-secondary" style="font-size:0.75rem">View All</a>
      </div>
      <?php if (empty($recent_enrollments)): ?>
        <div class="empty-state"><i class="ri-inbox-line"></i><p>No enrollments yet.</p></div>
      <?php else: ?>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Center</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_enrollments as $row): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size:0.8rem"><?php echo htmlspecialchars($row['course'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td style="font-size:0.8rem"><?php echo htmlspecialchars($row['center'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo status_badge($row['status']); ?></td>
                  <td style="font-size:0.75rem;color:#64748B">
                    <?php echo htmlspecialchars(date('d M Y', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8'); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div><!-- /.row -->

<?php include __DIR__ . '/partials/footer.php'; ?>
