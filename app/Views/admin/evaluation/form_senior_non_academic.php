<?php
// app/Views/admin/evaluation/form_senior_non_academic.php
$meta = [];
if (!empty($staff['evaluation_meta'])) {
    $decoded = json_decode($staff['evaluation_meta'], true);
    if (is_array($decoded)) {
        $meta = $decoded;
    }
}
?>
<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post" class="p-2">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="senior_non_academic">

  <div class="alert alert-info py-2 px-3 mb-3 small d-flex justify-content-between align-items-center">
    <div>
      <strong>Staff:</strong> <?= esc($staff['fullname'] ?? 'N/A') ?>
    </div>
    <div>
      <strong>Category:</strong> Senior Non-Academic
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Job Knowledge & Skills (1-10)</label>
    <select name="skills_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?>
        <?php $val = $i * 10; ?>
        <option value="<?= $val ?>" <?= (isset($meta['skills_score']) && $meta['skills_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Punctuality & Attendance (1-10)</label>
    <select name="punctuality_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?>
        <?php $val = $i * 10; ?>
        <option value="<?= $val ?>" <?= (isset($meta['punctuality_score']) && $meta['punctuality_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Teamwork & Communication (1-10)</label>
    <select name="teamwork_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?>
        <?php $val = $i * 10; ?>
        <option value="<?= $val ?>" <?= (isset($meta['teamwork_score']) && $meta['teamwork_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Initiative & Problem Solving (1-10)</label>
    <select name="initiative_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?>
        <?php $val = $i * 10; ?>
        <option value="<?= $val ?>" <?= (isset($meta['initiative_score']) && $meta['initiative_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Unit Head Comments</label>
    <textarea name="comments" rows="4" class="form-control" placeholder="Enter comments, strengths, areas for improvement..." required><?= esc(old('comments') ?? $staff['evaluation_comments'] ?? '') ?></textarea>
  </div>

  <hr class="my-3">

  <div class="mb-3">
    <label class="form-label font-weight-bold">Overall Score (computed)</label>
    <input type="number" name="overall_score" id="overall_score" class="form-control bg-light text-success fw-bold" min="0" max="100" value="<?= esc(old('overall_score') ?? $staff['evaluation_overall_score'] ?? '') ?>" required readonly>
    <div class="small text-muted mt-1">This value is automatically calculated as the average of the four fields above.</div>
  </div>

  <div class="mb-2">
    <strong>Computed total: <span id="computedTotalDisplay" class="text-success">—</span></strong>
  </div>
</form>
