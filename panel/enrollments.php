<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$page_title = 'Enrollments';
$user = current_user();

// ── Course / Table Routing ────────────────────────────────────────────────────
$table_map = [
    'shraddhamedha' => 'shraddhamedha_enrollments',
    'utkarsh'       => 'utkarsh_enrollments',
    'comprehensive' => 'comprehensive_enrollments',
    'mentorship'    => 'mentorship_enrollments',
];
$course_labels = [
    'shraddhamedha' => 'Shraddha-Medha',
    'utkarsh'       => 'Utkarsh',
    'comprehensive' => 'Comprehensive / IAS',
    'mentorship'    => 'Mentorship',
];

$course_key = array_key_exists($_GET['course'] ?? '', $table_map) ? $_GET['course'] : 'shraddhamedha';
$table      = $table_map[$course_key];

// ── Filters ───────────────────────────────────────────────────────────────────
$q             = clean($_GET['q'] ?? '');
$center_filter = clean($_GET['center'] ?? '');
$status_filter = in_array($_GET['status'] ?? '', ['pending', 'confirmed', 'cancelled']) ? $_GET['status'] : '';
$year_filter   = (int) ($_GET['year'] ?? 0);
$current_page  = max(1, (int) ($_GET['page'] ?? 1));
$per_page      = 25;

$rows           = [];
$pager          = ['total' => 0, 'per_page' => $per_page, 'current' => 1, 'total_pages' => 1, 'offset' => 0];
$centers        = [];
$uncalled_count = 0;

// Shraddha-Medha uses father_phone/mother_phone; others use phone_number
$is_school = ($course_key === 'shraddhamedha');

if ($conn) {
    // Sidebar badge
    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $uncalled_count = (int) $r->fetch_row()[0];

    // Fetch centers for filter dropdown
    $rc = $conn->query("SELECT name FROM centers WHERE status = 'active' ORDER BY name ASC");
    while ($c = $rc->fetch_assoc()) $centers[] = $c['name'];

    // Build WHERE
    $where  = ['1=1'];
    $types  = '';
    $params = [];

    if ($center_filter !== '') {
        $where[]  = 'center = ?';
        $types   .= 's';
        $params[] = $center_filter;
    }
    if ($status_filter !== '') {
        $where[]  = 'status = ?';
        $types   .= 's';
        $params[] = $status_filter;
    }
    if ($year_filter > 0) {
        $where[]  = 'YEAR(created_at) = ?';
        $types   .= 'i';
        $params[] = $year_filter;
    }
    if ($q !== '') {
        $like     = '%' . $q . '%';
        if ($is_school) {
            $where[]  = '(full_name LIKE ? OR email LIKE ? OR father_phone LIKE ?)';
            $types   .= 'sss';
        } else {
            $where[]  = '(full_name LIKE ? OR email LIKE ? OR phone_number LIKE ?)';
            $types   .= 'sss';
        }
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $where_sql = implode(' AND ', $where);

    // Count
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM `{$table}` WHERE {$where_sql}");
    if ($types) $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total = (int) $count_stmt->get_result()->fetch_row()[0];
    $count_stmt->close();

    $pager      = paginate($total, $per_page, $current_page);
    $all_params = array_merge($params, [$pager['offset']]);
    $all_types  = $types . 'i';

    // Fetch rows — select all columns
    $data_stmt = $conn->prepare(
        "SELECT * FROM `{$table}` WHERE {$where_sql} ORDER BY created_at DESC LIMIT {$per_page} OFFSET ?"
    );
    $data_stmt->bind_param($all_types, ...$all_params);
    $data_stmt->execute();
    $rows = $data_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $data_stmt->close();
}

// Preserve filters for links
$filter_params = ['course' => $course_key, 'q' => $q, 'center' => $center_filter, 'status' => $status_filter, 'year' => $year_filter ?: ''];
$base_url      = 'enrollments.php?' . http_build_query($filter_params);
$export_url    = 'enrollment-export.php?' . http_build_query($filter_params);

include __DIR__ . '/partials/header.php';
?>

<!-- Course tabs -->
<div class="panel-card">
  <div class="course-tabs">
    <?php foreach ($course_labels as $key => $label): ?>
      <a href="enrollments.php?course=<?php echo urlencode($key); ?>"
         class="course-tab <?php echo $course_key === $key ? 'active' : ''; ?>">
        <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Filter bar -->
  <form method="GET" action="enrollments.php" class="filter-bar">
    <input type="hidden" name="course" value="<?php echo htmlspecialchars($course_key, ENT_QUOTES, 'UTF-8'); ?>">
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Search</label>
      <input type="text" name="q" class="form-control" style="min-width:200px"
             placeholder="Name, email, phone..."
             value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Center</label>
      <select name="center" class="form-select" style="min-width:150px">
        <option value="">All Centers</option>
        <?php foreach ($centers as $c): ?>
          <option value="<?php echo htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>"
                  <?php echo $center_filter === $c ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($c, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Status</label>
      <select name="status" class="form-select" style="min-width:130px">
        <option value="">All Statuses</option>
        <option value="pending"   <?php echo $status_filter === 'pending'   ? 'selected' : ''; ?>>Pending</option>
        <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
      </select>
    </div>
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Year</label>
      <select name="year" class="form-select" style="min-width:100px">
        <option value="">All Years</option>
        <?php for ($y = (int) date('Y'); $y >= 2022; $y--): ?>
          <option value="<?php echo $y; ?>" <?php echo $year_filter === $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
      <button type="submit" class="btn btn-primary-custom btn-sm px-3">Filter</button>
      <a href="enrollments.php?course=<?php echo urlencode($course_key); ?>" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
    </div>
    <div class="ms-auto d-flex align-items-end">
      <a href="<?php echo htmlspecialchars($export_url, ENT_QUOTES, 'UTF-8'); ?>"
         class="btn btn-outline-secondary btn-sm px-3">
        <i class="ri-download-line me-1"></i> Export CSV
      </a>
    </div>
  </form>

  <!-- Table -->
  <div class="admin-table-wrap">
    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <i class="ri-file-list-3-line"></i>
        <p>No enrollments found.</p>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Father</th>
            <?php if ($is_school): ?>
              <th>School</th>
              <th>Class</th>
              <th>Phone</th>
            <?php else: ?>
              <th>College</th>
              <th>Stream</th>
              <th>Phone</th>
            <?php endif; ?>
            <th>Center</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr style="cursor:pointer" onclick="showDetail(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>, <?php echo $is_school ? 'true' : 'false'; ?>)">
              <td style="color:#94A3B8;font-size:0.8rem"><?php echo (int) $row['id']; ?></td>
              <td>
                <div style="font-weight:600"><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div style="font-size:0.75rem;color:#64748B"><?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
              </td>
              <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['father_name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <?php if ($is_school): ?>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['school_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['class_standard'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['father_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
              <?php else: ?>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['college_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['stream'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['phone_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
              <?php endif; ?>
              <td style="font-size:0.82rem"><?php echo htmlspecialchars($row['center'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td onclick="event.stopPropagation()">
                <?php if (can_edit()): ?>
                  <form method="POST" action="enrollment-status.php" style="min-width:110px">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                    <input type="hidden" name="course" value="<?php echo htmlspecialchars($course_key, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="page" value="<?php echo $pager['current']; ?>">
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="center" value="<?php echo htmlspecialchars($center_filter, ENT_QUOTES, 'UTF-8'); ?>">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size:0.78rem">
                      <option value="pending"   <?php echo $row['status'] === 'pending'   ? 'selected' : ''; ?>>Pending</option>
                      <option value="confirmed" <?php echo $row['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                      <option value="cancelled" <?php echo $row['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                  </form>
                <?php else: ?>
                  <?php echo status_badge($row['status']); ?>
                <?php endif; ?>
              </td>
              <td style="font-size:0.75rem;color:#64748B;white-space:nowrap">
                <?php echo htmlspecialchars(date('d M Y', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8'); ?>
              </td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-secondary btn-sm-icon">
                  <i class="ri-eye-line"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <div class="pagination-wrap">
    <span class="pagination-info">
      <?php if ($pager['total'] > 0): ?>
        Showing <?php echo number_format(($pager['current'] - 1) * $per_page + 1); ?>–<?php echo number_format(min($pager['current'] * $per_page, $pager['total'])); ?>
        of <?php echo number_format($pager['total']); ?> records
      <?php else: ?>
        0 records
      <?php endif; ?>
    </span>
    <?php echo render_pagination($pager, $base_url); ?>
  </div>

</div><!-- /.panel-card -->

<!-- Detail Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="detailCanvas" style="width:420px">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" style="font-size:0.95rem;font-weight:700">Enrollment Detail</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="detailBody" style="font-size:0.875rem">
  </div>
</div>

<?php
$extra_js = <<<'JS'
<script>
function showDetail(row, isSchool) {
  var body = document.getElementById('detailBody');
  var fields;

  if (isSchool) {
    fields = [
      ['Full Name',     row.full_name],
      ['Father Name',   row.father_name],
      ['Mother Name',   row.mother_name],
      ['Date of Birth', row.date_of_birth],
      ['Gender',        row.gender],
      ['Caste',         row.caste_category],
      ['School',        row.school_name],
      ['Class',         row.class_standard],
      ['Father Phone',  row.father_phone],
      ['Mother Phone',  row.mother_phone],
      ['Email',         row.email],
      ['Center',        row.center],
      ['Address',       row.address],
      ['Status',        row.status],
      ['Enrolled On',   row.created_at],
    ];
  } else {
    fields = [
      ['Full Name',     row.full_name],
      ['Father Name',   row.father_name],
      ['Mother Name',   row.mother_name],
      ['Date of Birth', row.date_of_birth],
      ['Gender',        row.gender],
      ['Caste',         row.caste_category],
      ['College',       row.college_name],
      ['Stream',        row.stream],
      ['District',      row.district],
      ['Phone',         row.phone_number],
      ['WhatsApp',      row.whatsapp_number],
      ['Email',         row.email],
      ['Center',        row.center],
      ['Address',       row.address],
      ['Status',        row.status],
      ['Enrolled On',   row.created_at],
    ];
  }

  var html = '';
  fields.forEach(function(f) {
    var val = f[1] || '—';
    html += '<div class="detail-field">'
          + '<div class="detail-label">' + escHtml(f[0]) + '</div>'
          + '<div class="detail-value">' + escHtml(val) + '</div>'
          + '</div>';
  });
  body.innerHTML = html;

  var canvas = new bootstrap.Offcanvas(document.getElementById('detailCanvas'));
  canvas.show();
}

function escHtml(str) {
  var d = document.createElement('div');
  d.appendChild(document.createTextNode(String(str)));
  return d.innerHTML;
}
</script>
JS;
?>

<?php include __DIR__ . '/partials/footer.php'; ?>
