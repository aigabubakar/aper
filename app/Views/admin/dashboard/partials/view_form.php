<?php // view_form.php — partial (no layout) ?>
<div class="p-3">
  <h5>Staff details</h5>
  <div class="mb-2"><strong>Full name:</strong> <?= esc($user['fullname']) ?></div>
  <div class="mb-2"><strong>Email:</strong> <?= esc($user['email']) ?></div>
  <div class="mb-2"><strong>Staff ID:</strong> <?= esc($user['staff_id'] ?? $user['staff_number'] ?? '') ?></div>
  <div class="mb-2"><strong>Faculty:</strong> <?= esc($facultyName ?? ($user['faculty'] ?? 'N/A')) ?></div>
  <div class="mb-2"><strong>Department:</strong> <?= esc($departmentName ?? ($user['department'] ?? 'N/A')) ?></div>
  <div class="mb-2"><strong>Category:</strong> <?= esc($user['category'] ?? '-') ?></div>
  <div class="text-end mt-3">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>
</div>
