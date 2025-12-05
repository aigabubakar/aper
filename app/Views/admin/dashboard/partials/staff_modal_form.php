<?php // expects $user, $faculties, $departments ?>
<form id="dashboardStaffForm" action="<?= site_url('admin/dashboard/update-staff/'.$user['id']) ?>" method="post">
  <?= csrf_field() ?>

  <div class="mb-2">
    <label class="form-label">Fullname</label>
    <input name="fullname" class="form-control" value="<?= esc($user['fullname']) ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="<?= esc($user['email']) ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Staff ID</label>
    <input name="staff_id" class="form-control" value="<?= esc($user['staff_id'] ?? '') ?>" required>
  </div>

  <div class="mb-2">
    <label class="form-label">Faculty</label>
    <select name="faculty_id" class="form-control">
      <option value="">— Select —</option>
      <?php foreach ($faculties as $f): ?>
        <option value="<?= esc($f['id']) ?>" <?= (isset($user['faculty_id']) && $user['faculty_id']==$f['id']) ? 'selected' : '' ?>><?= esc($f['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-2">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-control">
      <option value="">— Select —</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= esc($d['id']) ?>" <?= (isset($user['department_id']) && $user['department_id']==$d['id']) ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- optionally add hidden submit - the modal footer's submit button will POST via JS -->
</form>
