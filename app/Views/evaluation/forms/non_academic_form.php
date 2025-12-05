<?php
// non_academic_form.php
// For junior/non-academic staff evaluation
?>
<form id="evaluationForm" action="<?= site_url('evaluation/submit') ?>" method="post" novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id'] ?? '') ?>">
  <input type="hidden" name="category" value="non_academic">

  <div class="mb-2">
    <h6>Evaluator: <small class="text-muted"><?= esc(session()->get('fullname') ?? 'You') ?></small></h6>
    <div><strong>Staff:</strong> <?= esc($staff['fullname'] ?? $staff['email'] ?? 'N/A') ?></div>
    <div class="small text-muted">Category: Non-Academic</div>
  </div>

  <hr>

  <div class="mb-3">
    <label class="form-label">Job Knowledge & Skills</label>
    <select name="skills_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Punctuality & Attendance</label>
    <select name="punctuality_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Teamwork & Communication</label>
    <select name="teamwork_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Initiative & Problem Solving</label>
    <select name="initiative_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Manager Comments</label>
    <textarea name="comments" rows="4" class="form-control" placeholder="Strengths, development areas, recommendations..."></textarea>
  </div>

  <input type="hidden" name="form_type" value="non_academic">
</form>
