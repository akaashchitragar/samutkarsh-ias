<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$page_title = 'Inquiries';
$user = current_user();

// ── Filters ──────────────────────────────────────────────────────────────────
$status_filter = in_array($_GET['status'] ?? '', ['called', 'uncalled', 'all']) ? $_GET['status'] : 'all';
$q             = clean($_GET['q'] ?? '');
$current_page  = max(1, (int) ($_GET['page'] ?? 1));
$per_page      = 20;

$rows           = [];
$pager          = ['total' => 0, 'per_page' => $per_page, 'current' => 1, 'total_pages' => 1, 'offset' => 0];
$uncalled_count = 0;

if ($conn) {
    // Sidebar badge count
    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $uncalled_count = (int) $r->fetch_row()[0];

    // Build WHERE
    $where  = ['1=1'];
    $types  = '';
    $params = [];

    if ($status_filter === 'called') {
        $where[] = 'is_called = 1';
    } elseif ($status_filter === 'uncalled') {
        $where[] = 'is_called = 0';
    }

    if ($q !== '') {
        $where[]  = '(full_name LIKE ? OR phone LIKE ? OR email LIKE ? OR course LIKE ?)';
        $types   .= 'ssss';
        $like     = '%' . $q . '%';
        $params[]  = $like;
        $params[]  = $like;
        $params[]  = $like;
        $params[]  = $like;
    }

    $where_sql = implode(' AND ', $where);

    // Count
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM inquiries WHERE {$where_sql}");
    if ($types) $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total = (int) $count_stmt->get_result()->fetch_row()[0];
    $count_stmt->close();

    $pager  = paginate($total, $per_page, $current_page);
    $offset = $pager['offset'];

    // Fetch rows
    $all_types  = $types . 'i';
    $all_params = array_merge($params, [$offset]);

    $data_stmt = $conn->prepare(
        "SELECT id, full_name, phone, email, course, center, message, is_called, created_at
         FROM inquiries WHERE {$where_sql} ORDER BY created_at DESC LIMIT {$per_page} OFFSET ?"
    );
    $data_stmt->bind_param($all_types, ...$all_params);
    $data_stmt->execute();
    $rows = $data_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $data_stmt->close();
}

// Build base URL for pagination
$base_url = 'inquiries.php?' . http_build_query(['status' => $status_filter, 'q' => $q]);

include __DIR__ . '/partials/header.php';
?>

<div class="panel-card">

  <!-- Filters -->
  <form method="GET" action="inquiries.php" class="filter-bar">
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Search</label>
      <input type="text" name="q" class="form-control" style="min-width:200px"
             placeholder="Name, phone, email, course..."
             value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
    </div>
    <div>
      <label class="form-label mb-1" style="font-size:0.75rem;font-weight:600">Status</label>
      <select name="status" class="form-select" style="min-width:130px">
        <option value="all"     <?php echo $status_filter === 'all'     ? 'selected' : ''; ?>>All</option>
        <option value="uncalled" <?php echo $status_filter === 'uncalled' ? 'selected' : ''; ?>>Pending / Uncalled</option>
        <option value="called"  <?php echo $status_filter === 'called'  ? 'selected' : ''; ?>>Called</option>
      </select>
    </div>
    <div class="d-flex gap-2 align-items-end">
      <button type="submit" class="btn btn-primary-custom btn-sm px-3">Filter</button>
      <a href="inquiries.php" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
    </div>
  </form>

  <!-- Table -->
  <div class="admin-table-wrap">
    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <i class="ri-question-answer-line"></i>
        <p>No inquiries found.</p>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Course</th>
            <th>Center</th>
            <th>Message</th>
            <th>Status</th>
            <th>Date</th>
            <?php if (can_edit()): ?>
              <th>Action</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr style="cursor:pointer" onclick="showInquiry(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)">
              <td style="color:#94A3B8;font-size:0.8rem"><?php echo (int) $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['full_name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td>
                <a href="tel:<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                   style="color:#EA580C;font-weight:600;text-decoration:none"
                   onclick="event.stopPropagation()">
                  <?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
              </td>
              <td style="font-size:0.82rem"><?php echo htmlspecialchars($row['email'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.82rem"><?php echo htmlspecialchars($row['course'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.82rem"><?php echo htmlspecialchars($row['center'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.8rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                  title="<?php echo htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <?php $msg = $row['message'] ?? ''; echo htmlspecialchars(mb_strlen($msg) > 60 ? mb_substr($msg, 0, 60) . '…' : $msg, ENT_QUOTES, 'UTF-8'); ?>
              </td>
              <td>
                <?php if ((int) $row['is_called']): ?>
                  <span class="status-badge badge-called">Called</span>
                <?php else: ?>
                  <span class="status-badge badge-uncalled">Pending</span>
                <?php endif; ?>
              </td>
              <td style="font-size:0.75rem;color:#64748B;white-space:nowrap">
                <?php echo htmlspecialchars(date('d M Y', strtotime($row['created_at'])), ENT_QUOTES, 'UTF-8'); ?>
              </td>
              <?php if (can_edit()): ?>
                <td onclick="event.stopPropagation()">
                  <form method="POST" action="inquiry-toggle.php" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                    <input type="hidden" name="current" value="<?php echo (int) $row['is_called']; ?>">
                    <input type="hidden" name="redirect_page" value="<?php echo $pager['current']; ?>">
                    <input type="hidden" name="redirect_status" value="<?php echo htmlspecialchars($status_filter, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="redirect_q" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-sm btn-sm-icon <?php echo (int) $row['is_called'] ? 'btn-outline-secondary' : 'btn-outline-success'; ?>">
                      <?php echo (int) $row['is_called'] ? 'Unmark' : 'Mark Called'; ?>
                    </button>
                  </form>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($pager['total_pages'] > 1): ?>
    <div class="pagination-wrap">
      <span class="pagination-info">
        Showing <?php echo number_format(($pager['current'] - 1) * $per_page + 1); ?>–<?php echo number_format(min($pager['current'] * $per_page, $pager['total'])); ?>
        of <?php echo number_format($pager['total']); ?> inquiries
      </span>
      <?php echo render_pagination($pager, 'inquiries.php?' . http_build_query(['status' => $status_filter, 'q' => $q])); ?>
    </div>
  <?php else: ?>
    <div class="pagination-wrap">
      <span class="pagination-info"><?php echo number_format($pager['total']); ?> inquiries</span>
    </div>
  <?php endif; ?>

</div><!-- /.panel-card -->

<!-- Detail Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="inquiryCanvas" style="width:420px">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" style="font-size:0.95rem;font-weight:700">Inquiry Detail</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" id="inquiryBody" style="font-size:0.875rem"></div>
</div>

<?php
$extra_js = <<<'JS'
<script>
function showInquiry(row) {
  var fields = [
    ['Name',       row.full_name],
    ['Phone',      row.phone],
    ['Email',      row.email],
    ['Course',     row.course],
    ['Center',     row.center],
    ['Message',    row.message],
    ['Status',     row.is_called == 1 ? 'Called' : 'Pending'],
    ['Received On', row.created_at],
  ];
  var html = '';
  fields.forEach(function(f) {
    var val = f[1] || '—';
    html += '<div class="detail-field">'
          + '<div class="detail-label">' + escHtml(f[0]) + '</div>'
          + '<div class="detail-value">' + escHtml(val) + '</div>'
          + '</div>';
  });
  document.getElementById('inquiryBody').innerHTML = html;
  new bootstrap.Offcanvas(document.getElementById('inquiryCanvas')).show();
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
