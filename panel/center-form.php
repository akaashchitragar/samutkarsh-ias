<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

if (!can_edit()) {
    set_flash('danger', 'You do not have permission to perform this action.');
    header('Location: centers.php');
    exit;
}

$is_edit = false;
$row     = [
    'id'       => 0,
    'name'     => '',
    'address'  => '',
    'city'     => '',
    'state'    => 'Karnataka',
    'pincode'  => '',
    'phone'    => '',
    'email'    => '',
    'manager'  => '',
    'capacity' => '',
    'status'   => 'active',
];
$errors  = [];

$edit_id = intval($_GET['id'] ?? 0);
if ($edit_id > 0 && $conn) {
    $stmt = $conn->prepare('SELECT * FROM centers WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($found) {
        $row     = $found;
        $is_edit = true;
    }
}

$page_title = $is_edit ? 'Edit Center' : 'Add Center';
$user       = current_user();

// ── POST handler ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $id       = intval($_POST['id'] ?? 0);
    $name     = clean($_POST['name'] ?? '');
    $address  = clean($_POST['address'] ?? '');
    $city     = clean($_POST['city'] ?? '');
    $state    = clean($_POST['state'] ?? 'Karnataka');
    $pincode  = preg_replace('/[^0-9]/', '', $_POST['pincode'] ?? '');
    $phone    = clean($_POST['phone'] ?? '');
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $manager  = clean($_POST['manager'] ?? '');
    $capacity = intval($_POST['capacity'] ?? 0);
    $status   = in_array($_POST['status'] ?? '', ['active', 'inactive', 'maintenance'])
                ? $_POST['status'] : 'active';

    if ($name === '')    $errors[] = 'Center name is required.';
    if ($address === '') $errors[] = 'Address is required.';
    if ($city === '')    $errors[] = 'City is required.';
    if ($phone === '')   $errors[] = 'Phone number is required.';

    if (empty($errors) && $conn) {
        if ($id > 0) {
            $stmt = $conn->prepare(
                'UPDATE centers SET name=?, address=?, city=?, state=?, pincode=?, phone=?, email=?, manager=?, capacity=?, status=?, updated_at=NOW() WHERE id=?'
            );
            $stmt->bind_param('ssssssssssi',
                $name, $address, $city, $state, $pincode, $phone, $email, $manager, $capacity, $status, $id
            );
            $stmt->execute();
            $stmt->close();
            set_flash('success', 'Center updated successfully.');
        } else {
            $stmt = $conn->prepare(
                'INSERT INTO centers (name, address, city, state, pincode, phone, email, manager, capacity, status) VALUES (?,?,?,?,?,?,?,?,?,?)'
            );
            $stmt->bind_param('sssssssssi',
                $name, $address, $city, $state, $pincode, $phone, $email, $manager, $capacity, $status
            );
            $stmt->execute();
            $stmt->close();
            set_flash('success', 'Center added successfully.');
        }
        header('Location: centers.php');
        exit;
    }

    // Re-populate form on error
    $row = compact('id', 'name', 'address', 'city', 'state', 'pincode', 'phone', 'email', 'manager', 'capacity', 'status');
}

// Sidebar badge
$uncalled_count = 0;
if ($conn) {
    $r = $conn->query('SELECT COUNT(*) FROM inquiries WHERE is_called = 0');
    $uncalled_count = (int) $r->fetch_row()[0];
}

include __DIR__ . '/partials/header.php';
?>

<div style="max-width:680px">
  <a href="centers.php" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="ri-arrow-left-line me-1"></i> Back to Centers
  </a>

  <?php if (!empty($errors)): ?>
    <div class="flash-alert alert alert-danger">
      <ul class="mb-0 ps-3">
        <?php foreach ($errors as $e): ?>
          <li><?php echo htmlspecialchars($e, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="panel-card">
    <div class="panel-card-header">
      <h2 class="panel-card-title"><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h2>
    </div>
    <div style="padding:1.5rem">
      <form method="POST" action="center-form.php" novalidate>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">

        <div class="row g-3">
          <div class="col-12">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Center Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="e.g., Hubballi Main Campus" required>
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Address <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control"
                   value="<?php echo htmlspecialchars($row['address'], ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="Street address" required>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control"
                   value="<?php echo htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="e.g., Hubballi" required>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">State</label>
            <input type="text" name="state" class="form-control"
                   value="<?php echo htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Pincode</label>
            <input type="text" name="pincode" class="form-control" maxlength="6"
                   value="<?php echo htmlspecialchars($row['pincode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="580001">
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Phone <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control"
                   value="<?php echo htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="+91 9663424767" required>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="center@samutkarshias.in">
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Manager Name</label>
            <input type="text" name="manager" class="form-control"
                   value="<?php echo htmlspecialchars($row['manager'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="e.g., Suresh Kumar">
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Capacity</label>
            <input type="number" name="capacity" class="form-control" min="0"
                   value="<?php echo (int) ($row['capacity'] ?? 0) ?: ''; ?>"
                   placeholder="e.g., 120">
          </div>
          <div class="col-md-3">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Status</label>
            <select name="status" class="form-select">
              <option value="active"      <?php echo ($row['status'] ?? '') === 'active'      ? 'selected' : ''; ?>>Active</option>
              <option value="inactive"    <?php echo ($row['status'] ?? '') === 'inactive'    ? 'selected' : ''; ?>>Inactive</option>
              <option value="maintenance" <?php echo ($row['status'] ?? '') === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
            </select>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary-custom px-4">
            <i class="ri-save-line me-1"></i> <?php echo $is_edit ? 'Update Center' : 'Add Center'; ?>
          </button>
          <a href="centers.php" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
