<?php // admin/dashboard/partials/edit_form.php
// $user, $faculties, $departments provided
?>


<form id="staffEditForm" action="<?= site_url('admin/staff/' . (int)$user['id'] . '/update') ?>" method="post">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="form-label">Fullname</label>
    <input name="fullname" value="<?= esc($user['fullname']) ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" value="<?= esc($user['email']) ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Staff ID</label>
    <input name="staff_id" value="<?= esc($user['staff_id'] ?? $user['staff_number'] ?? '') ?>" class="form-control" required>
  </div>

  <?php if (! empty($faculties)): ?>
  <div class="mb-3">
    <label class="form-label">Faculty</label>
    <select name="faculty_id" class="form-control">
      <option value="">— select —</option>
      <?php foreach ($faculties as $f): ?>
        <option value="<?= $f['id'] ?>" <?= (isset($user['faculty_id']) && $user['faculty_id'] == $f['id']) ? 'selected' : '' ?>>
          <?= esc($f['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php endif; ?>
  

  <div class="mb-3">
    <label>Category</label>
    <?php $cat = old('category', $user['category'] ?? 'non_academic'); ?>
    <select name="category" class="form-select" required>
      <option value="academic" <?= $cat==='academic' ? 'selected' : '' ?>>Academic</option>
      <option value="senior_non_academic" <?= $cat==='senior_non_academic' ? 'selected' : '' ?>>Senior Non-Academic</option>
      <option value="junior_non_academic" <?= $cat==='junior_non_academic' ? 'selected' : '' ?>>Junior Non-Academic</option>
      <option value="non_academic" <?= $cat==='non_academic' ? 'selected' : '' ?>>Non-Academic</option>
    </select>
  </div>

  <?php if (! empty($departments)): ?>
  <div class="mb-3">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-control">
      <option value="">— select —</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= $d['id'] ?>" <?= (isset($user['department_id']) && $user['department_id'] == $d['id']) ? 'selected' : '' ?>>
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

  <?php if ($method === 'create'): ?>
    <div class="mb-3">
      <label>Password</label>
      <input name="password" type="password" class="form-control" required>
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label>New Password (leave blank to keep current)</label>
      <input name="password" type="password" class="form-control">
    </div>
  <?php endif; ?>


  <div class="d-flex justify-content-end">
    <button id="staffEditSubmit" type="submit" class="btn btn-primary">Save</button>
  </div>

  

</form>
