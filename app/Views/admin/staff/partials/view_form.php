<?php // app/Views/admin/staff/partials/view_form.php
/** @var array $staff */
?>
<div class="p-2">
  <h5 class="mb-2">Staff Details</h5>
  <dl class="row">
    <dt class="col-sm-4">Full name</dt><dd class="col-sm-8"><?= esc($staff['fullname']) ?></dd>
    <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?= esc($staff['email']) ?></dd>
    <dt class="col-sm-4">Staff Number</dt><dd class="col-sm-8"><?= esc($staff['staff_number'] ?? $staff['staff_id'] ?? '-') ?></dd>
    <dt class="col-sm-4">Faculty</dt><dd class="col-sm-8"><?= esc($staff['faculty_name'] ?? $staff['faculty_id'] ?? '-') ?></dd>
    <dt class="col-sm-4">Department</dt><dd class="col-sm-8"><?= esc($staff['department_name'] ?? $staff['department_id'] ?? '-') ?></dd>
    <dt class="col-sm-4">Role</dt><dd class="col-sm-8"><?= esc($staff['role'] ?? '-') ?></dd>
  </dl>
</div>
