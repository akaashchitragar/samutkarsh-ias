<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

$page_title = 'Centers';
$user = current_user();

// ── Toggle status (self-POST) ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle') {
    verify_csrf();
    if (can_edit()) {
        $id         = intval($_POST['id'] ?? 0);
        $current_st = clean($_POST['current_status'] ?? '');
        $new_st     = ($current_st === 'active') ? 'inactive' : 'active';
        if ($id > 0 && $conn) {
            $stmt = $conn->prepare('UPDATE centers SET status = ?, updated_at = NOW() WHERE id = ?');
            $stmt->bind_param('si', $new_st, $id);
            $stmt->execute();
            $stmt->close();
            set_flash('success', 'Center status updated to ' . $new_st . '.');
        }
    }
    header('Location: centers.php');
    exit;
}

$rows           = [];
$uncalled_count = 0;

if ($conn) {
    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $uncalled_count = (int) $r->fetch_row()[0];

    $r    = $conn->query('SELECT * FROM centers ORDER BY city ASC, name ASC');
    $rows = $r->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/partials/header.php';
?>

<?php if (can_edit()): ?>
  <div class="mb-3">
    <a href="center-form.php" class="btn btn-primary-custom btn-sm px-3">
      <i class="ri-add-line me-1"></i> Add Center
    </a>
  </div>
<?php endif; ?>

<div class="panel-card">
  <div class="admin-table-wrap">
    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <i class="ri-building-2-line"></i>
        <p>No centers found.</p>
      </div>
    <?php else: ?>
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>City</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Manager</th>
            <th>Capacity</th>
            <th>Status</th>
            <?php if (can_edit()): ?>
              <th>Actions</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td style="color:#94A3B8;font-size:0.8rem"><?php echo (int) $row['id']; ?></td>
              <td style="font-weight:600"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.8rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                  title="<?php echo htmlspecialchars($row['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($row['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
              </td>
              <td style="font-size:0.85rem">
                <?php if (!empty($row['phone'])): ?>
                  <a href="tel:<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                     style="color:#EA580C;text-decoration:none;font-weight:600">
                    <?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>
                  </a>
                <?php else: ?>
                  <span style="color:#94A3B8">—</span>
                <?php endif; ?>
              </td>
              <td style="font-size:0.85rem"><?php echo htmlspecialchars($row['manager'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
              <td style="font-size:0.85rem;color:#64748B"><?php echo $row['capacity'] ? (int) $row['capacity'] : '—'; ?></td>
              <td><?php echo status_badge($row['status']); ?></td>
              <?php if (can_edit()): ?>
                <td>
                  <div class="d-flex gap-1">
                    <a href="center-form.php?id=<?php echo (int) $row['id']; ?>"
                       class="btn btn-sm btn-outline-secondary btn-sm-icon" title="Edit">
                      <i class="ri-edit-line"></i>
                    </a>
                    <form method="POST" action="centers.php?action=toggle" style="display:inline">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" name="action" value="toggle">
                      <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                      <input type="hidden" name="current_status" value="<?php echo htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>">
                      <button type="submit"
                              class="btn btn-sm btn-sm-icon <?php echo $row['status'] === 'active' ? 'btn-outline-warning' : 'btn-outline-success'; ?>"
                              title="<?php echo $row['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                        <i class="<?php echo $row['status'] === 'active' ? 'ri-pause-circle-line' : 'ri-play-circle-line'; ?>"></i>
                      </button>
                    </form>
                  </div>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <div class="pagination-wrap">
    <span class="pagination-info"><?php echo number_format(count($rows)); ?> centers total</span>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
