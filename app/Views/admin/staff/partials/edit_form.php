<?php // app/Views/admin/staff/partials/edit_form.php
/** @var array $staff */
/** @var array $faculties */
/** @var array $departments */
?>
<form id="staffEditForm" action="<?= site_url('/admin/staff/' . (int)$staff['id'] . '/update-ajax') ?>" method="post">
  <?= csrf_field() ?>

  <input type="hidden" name="id" value="<?= esc($staff['id']) ?>">

  <div class="mb-2">
    <label class="form-label">Full name</label>
    <input name="fullname" class="form-control" value="<?= esc($staff['fullname']) ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="<?= esc($staff['email']) ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Staff Number</label>
    <input name="staff_number" class="form-control" value="<?= esc($staff['staff_number'] ?? $staff['staff_id'] ?? '') ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Faculty</label>
    <select name="faculty_id" class="form-select">
      <option value="">-- none --</option>
      <?php foreach ($faculties as $f): ?>
        <option value="<?= $f['id'] ?>" <?= (isset($staff['faculty_id']) && $staff['faculty_id']==$f['id']) ? 'selected' : '' ?>><?= esc($f['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-2">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-select">
      <option value="">-- none --</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= $d['id'] ?>" <?= (isset($staff['department_id']) && $staff['department_id']==$d['id']) ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-2">
    <label class="form-label">Role</label>
    <input name="role" class="form-control" value="<?= esc($staff['role'] ?? 'staff') ?>">
  </div>

  <div class="d-flex justify-content-end gap-2 mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" id="staffEditSubmit">Save changes</button>
  </div>
</form>
