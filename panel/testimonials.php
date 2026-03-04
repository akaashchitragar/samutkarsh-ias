<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$page_title = 'Testimonials';
$user = current_user();

// ── Toggle active (self-POST) ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle') {
    verify_csrf();
    if (can_edit()) {
        $id  = intval($_POST['id'] ?? 0);
        $cur = intval($_POST['current'] ?? 0);
        $new = $cur === 1 ? 0 : 1;
        if ($id > 0 && $conn) {
            $stmt = $conn->prepare('UPDATE testimonials SET is_active = ? WHERE id = ?');
            $stmt->bind_param('ii', $new, $id);
            $stmt->execute();
            $stmt->close();
            set_flash('success', $new ? 'Testimonial set to active.' : 'Testimonial set to inactive.');
        }
    }
    header('Location: testimonials.php');
    exit;
}

$rows           = [];
$uncalled_count = 0;

if ($conn) {
    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $uncalled_count = (int) $r->fetch_row()[0];

    $r    = $conn->query('SELECT * FROM testimonials ORDER BY display_order ASC, id ASC');
    $rows = $r->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <?php if (can_edit()): ?>
    <a href="testimonial-form.php" class="btn btn-primary-custom btn-sm px-3">
      <i class="ri-add-line me-1"></i> Add Testimonial
    </a>
  <?php else: ?>
    <span></span>
  <?php endif; ?>
  <?php if (can_edit()): ?>
    <button type="button" id="saveOrderBtn" class="btn btn-outline-secondary btn-sm px-3" style="display:none">
      <i class="ri-save-line me-1"></i> Save Order
    </button>
  <?php endif; ?>
</div>

<div class="panel-card">
  <div class="admin-table-wrap">
    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <i class="ri-star-line"></i>
        <p>No testimonials yet. Add one above.</p>
      </div>
    <?php else: ?>
      <table class="admin-table" id="testimonialTable">
        <thead>
          <tr>
            <?php if (can_edit()): ?>
              <th style="width:36px"></th>
            <?php endif; ?>
            <th>Order</th>
            <th>Name</th>
            <th>Batch</th>
            <th>Quote</th>
            <th>Rating</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="sortableBody">
          <?php foreach ($rows as $row): ?>
            <tr data-id="<?php echo (int) $row['id']; ?>">
              <?php if (can_edit()): ?>
                <td class="drag-handle"><i class="ri-drag-move-2-line"></i></td>
              <?php endif; ?>
              <td style="color:#94A3B8;font-size:0.8rem"><?php echo (int) $row['display_order']; ?></td>
              <td style="font-weight:600"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.82rem;color:#64748B"><?php echo htmlspecialchars($row['batch'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.82rem;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                  title="<?php echo htmlspecialchars($row['quote'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars(mb_substr($row['quote'], 0, 80) . (mb_strlen($row['quote']) > 80 ? '…' : ''), ENT_QUOTES, 'UTF-8'); ?>
              </td>
              <td><?php echo star_rating((int) $row['rating']); ?></td>
              <td>
                <?php if ((int) $row['is_active']): ?>
                  <span class="status-badge badge-active">Active</span>
                <?php else: ?>
                  <span class="status-badge badge-inactive">Inactive</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="d-flex gap-1 flex-wrap">
                  <?php if (can_edit()): ?>
                    <a href="testimonial-form.php?id=<?php echo (int) $row['id']; ?>"
                       class="btn btn-sm btn-outline-secondary btn-sm-icon" title="Edit">
                      <i class="ri-edit-line"></i>
                    </a>
                    <form method="POST" action="testimonials.php" style="display:inline">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="toggle">
                      <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                      <input type="hidden" name="current" value="<?php echo (int) $row['is_active']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-secondary btn-sm-icon"
                              title="<?php echo (int) $row['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                        <i class="<?php echo (int) $row['is_active'] ? 'ri-eye-off-line' : 'ri-eye-line'; ?>"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                  <?php if (can_delete()): ?>
                    <form method="POST" action="testimonial-delete.php" style="display:inline"
                          onsubmit="return confirm('Delete this testimonial? This cannot be undone.');">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger btn-sm-icon" title="Delete">
                        <i class="ri-delete-bin-line"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php
$csrf_token_js = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
$extra_js = <<<JS
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
(function() {
  var tbody = document.getElementById('sortableBody');
  var saveBtn = document.getElementById('saveOrderBtn');
  if (!tbody || !saveBtn) return;

  var orderChanged = false;

  Sortable.create(tbody, {
    handle: '.drag-handle',
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: function() {
      orderChanged = true;
      saveBtn.style.display = 'inline-block';
    }
  });

  saveBtn.addEventListener('click', function() {
    var ids = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function(tr) {
      return parseInt(tr.getAttribute('data-id'), 10);
    });

    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="ri-loader-4-line me-1"></i> Saving...';

    fetch('testimonial-reorder.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': '{$csrf_token_js}'
      },
      body: JSON.stringify({ order: ids })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.ok) {
        saveBtn.innerHTML = '<i class="ri-check-line me-1"></i> Saved';
        saveBtn.className = saveBtn.className.replace('btn-outline-secondary', 'btn-outline-success');
        orderChanged = false;
        setTimeout(function() {
          saveBtn.style.display = 'none';
          saveBtn.innerHTML = '<i class="ri-save-line me-1"></i> Save Order';
          saveBtn.className = saveBtn.className.replace('btn-outline-success', 'btn-outline-secondary');
          saveBtn.disabled = false;
        }, 1500);
      } else {
        alert('Failed to save order. Please try again.');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="ri-save-line me-1"></i> Save Order';
      }
    })
    .catch(function() {
      alert('Network error. Please try again.');
      saveBtn.disabled = false;
      saveBtn.innerHTML = '<i class="ri-save-line me-1"></i> Save Order';
    });
  });
})();
</script>
JS;
?>

<?php include __DIR__ . '/partials/footer.php'; ?>
