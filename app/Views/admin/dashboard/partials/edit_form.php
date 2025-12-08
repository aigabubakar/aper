<?php // admin/dashboard/partials/edit_form.php
// Expects: $user (array), $faculties, $departments, $method ('edit' or 'create')

$method = $method ?? 'edit';
?>

<form id="staffEditForm" action="<?= site_url('admin/staff/' . (int)$user['id'] . '/update') ?>" method="post" novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="_method" value="POST">
  <div class="mb-3">
    <label class="form-label">Fullname</label>
    <input name="fullname" value="<?= esc(old('fullname', $user['fullname'] ?? '')) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" value="<?= esc(old('email', $user['email'] ?? '')) ?>" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Staff ID</label>
    <input name="staff_id" value="<?= esc(old('staff_id', $user['staff_id'] ?? $user['staff_number'] ?? '')) ?>" class="form-control" required>
  </div>

  <?php if (! empty($faculties)): ?>
  <div class="mb-3">
    <label class="form-label">Faculty</label>
    <select name="faculty_id" class="form-control">
      <option value="">— select —</option>
      <?php foreach ($faculties as $f): ?>
        <option value="<?= (int)$f['id'] ?>" <?= (isset($user['faculty_id']) && $user['faculty_id'] == $f['id']) ? 'selected' : '' ?>>
          <?= esc($f['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>

  <?php if (! empty($departments)): ?>
  <div class="mb-3">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-control">
      <option value="">— select —</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= (int)$d['id'] ?>" <?= (isset($user['department_id']) && $user['department_id'] == $d['id']) ? 'selected' : '' ?>>
          <?= esc($d['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>

  <div class="mb-3">
    <label>Phone</label>
    <input name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
  </div>

  <div class="mb-3">
    <label>Period from (Year)</label>
    <input name="period_from" type="number" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_from', $user['period_from'] ?? '')) ?>">
  </div>

  <div class="mb-3">
    <label>Period to (Year)</label>
    <input name="period_to" type="number" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_to', $user['period_to'] ?? '')) ?>">
  </div>

  <!-- Password handling: on edit, optional; on create required -->
  <?php if ($method === 'create'): ?>
    <div class="mb-3">
      <label>Password</label>
      <div class="input-group">
        <input name="password" id="passwordField" type="password" class="form-control" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
      </div>
      <div class="form-text">Password will be hashed automatically.</div>
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label>New Password <small class="text-muted">(leave blank to keep current)</small></label>
      <div class="input-group">
        <input name="password" id="passwordField" type="password" class="form-control" placeholder="••••••">
        <button type="button" class="btn btn-outline-secondary" id="togglePassword">Show</button>
      </div>
      <div class="form-text">Provide a new password only when you want to reset user's password.</div>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-end">
    <button id="staffEditSubmit" type="submit" class="btn btn-primary">
      <span id="staffEditSubmitText">Save</span>
      <span id="staffEditSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
    </button>
  </div>
</form>

<script>
  // small password toggle for the inline form
  (function(){
    const toggle = document.getElementById('togglePassword');
    if (!toggle) return;
    toggle.addEventListener('click', function(){
      const pw = document.getElementById('passwordField');
      if (!pw) return;
      if (pw.type === 'password') {
        pw.type = 'text';
        this.textContent = 'Hide';
      } else {
        pw.type = 'password';
        this.textContent = 'Show';
      }
    });
  })();
</script>
