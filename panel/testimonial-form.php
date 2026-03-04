<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/csrf.php';

if (!can_edit()) {
    set_flash('danger', 'You do not have permission to perform this action.');
    header('Location: testimonials.php');
    exit;
}

$is_edit = false;
$row     = [
    'id'            => 0,
    'name'          => '',
    'batch'         => '',
    'photo_url'     => '',
    'quote'         => '',
    'rating'        => 5,
    'is_active'     => 1,
    'display_order' => 0,
];
$errors  = [];

$edit_id = intval($_GET['id'] ?? 0);
if ($edit_id > 0 && $conn) {
    $stmt = $conn->prepare('SELECT * FROM testimonials WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $edit_id);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($found) {
        $row     = $found;
        $is_edit = true;
    }
}

$page_title = $is_edit ? 'Edit Testimonial' : 'Add Testimonial';
$user       = current_user();

// ── POST handler ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $id            = intval($_POST['id'] ?? 0);
    $name          = clean($_POST['name'] ?? '');
    $batch         = clean($_POST['batch'] ?? '');
    $photo_url     = filter_var(trim($_POST['photo_url'] ?? ''), FILTER_SANITIZE_URL);
    $quote         = htmlspecialchars(strip_tags(trim($_POST['quote'] ?? '')), ENT_QUOTES, 'UTF-8');
    $rating        = max(1, min(5, intval($_POST['rating'] ?? 5)));
    $is_active     = isset($_POST['is_active']) ? 1 : 0;
    $display_order = max(0, intval($_POST['display_order'] ?? 0));

    if ($name === '') $errors[] = 'Name is required.';
    if ($quote === '') $errors[] = 'Quote is required.';

    if (empty($errors) && $conn) {
        if ($id > 0) {
            // Update
            $stmt = $conn->prepare(
                'UPDATE testimonials SET name=?, batch=?, photo_url=?, quote=?, rating=?, is_active=?, display_order=? WHERE id=?'
            );
            $stmt->bind_param('ssssiiii', $name, $batch, $photo_url, $quote, $rating, $is_active, $display_order, $id);
            $stmt->execute();
            $stmt->close();
            set_flash('success', 'Testimonial updated successfully.');
        } else {
            // Insert
            $stmt = $conn->prepare(
                'INSERT INTO testimonials (name, batch, photo_url, quote, rating, is_active, display_order) VALUES (?,?,?,?,?,?,?)'
            );
            $stmt->bind_param('ssssiiii', $name, $batch, $photo_url, $quote, $rating, $is_active, $display_order, $id);
            // Re-bind without $id for insert
            $stmt->close();
            $stmt = $conn->prepare(
                'INSERT INTO testimonials (name, batch, photo_url, quote, rating, is_active, display_order) VALUES (?,?,?,?,?,?,?)'
            );
            $stmt->bind_param('ssssiii', $name, $batch, $photo_url, $quote, $rating, $is_active, $display_order);
            $stmt->execute();
            $stmt->close();
            set_flash('success', 'Testimonial added successfully.');
        }
        header('Location: testimonials.php');
        exit;
    }

    // Re-populate form on error
    $row = compact('id', 'name', 'batch', 'photo_url', 'quote', 'rating', 'is_active', 'display_order');
}

include __DIR__ . '/partials/header.php';
?>

<div style="max-width:680px">
  <a href="testimonials.php" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="ri-arrow-left-line me-1"></i> Back to Testimonials
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
      <form method="POST" action="testimonial-form.php" novalidate>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Student Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="e.g., Ananya Rao" required>
          </div>
          <div class="col-md-6">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Batch / Achievement</label>
            <input type="text" name="batch" class="form-control"
                   value="<?php echo htmlspecialchars($row['batch'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="e.g., IAS 2022 Batch, KAS Rank 4">
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Photo URL <span style="font-weight:400;color:#64748B">(optional)</span></label>
            <input type="url" name="photo_url" class="form-control"
                   value="<?php echo htmlspecialchars($row['photo_url'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="https://...">
          </div>
          <div class="col-12">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Quote / Testimonial <span class="text-danger">*</span></label>
            <textarea name="quote" class="form-control" rows="4"
                      placeholder="What did this student say about the academy?"
                      required><?php echo htmlspecialchars($row['quote'], ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Rating</label>
            <select name="rating" class="form-select">
              <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?php echo $i; ?>" <?php echo (int) $row['rating'] === $i ? 'selected' : ''; ?>>
                  <?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Display Order</label>
            <input type="number" name="display_order" class="form-control" min="0"
                   value="<?php echo (int) $row['display_order']; ?>">
            <div style="font-size:0.72rem;color:#64748B;margin-top:0.2rem">Lower = shown first. You can also drag-to-reorder on the list page.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label" style="font-size:0.82rem;font-weight:600">Visibility</label>
            <div class="form-check mt-2">
              <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                     <?php echo (int) $row['is_active'] ? 'checked' : ''; ?>>
              <label for="is_active" class="form-check-label" style="font-size:0.875rem">Show on website</label>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
          <button type="submit" class="btn btn-primary-custom px-4">
            <i class="ri-save-line me-1"></i> <?php echo $is_edit ? 'Update Testimonial' : 'Add Testimonial'; ?>
          </button>
          <a href="testimonials.php" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
