<?php
// app/Views/admin/evaluation/form_academic.php
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
  <input type="hidden" name="category" value="academic">

  <div class="alert alert-primary py-2 px-3 mb-3 small d-flex justify-content-between align-items-center">
    <div>
      <strong>Staff:</strong> <?= esc($staff['fullname'] ?? 'N/A') ?>
    </div>
    <div>
      <strong>Category:</strong> Academic
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Teaching Quality (1-5)</label>
    <select name="teaching_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?>
        <?php $val = $i * 20; ?>
        <option value="<?= $val ?>" <?= (isset($meta['teaching_score']) && $meta['teaching_score'] == $val) ? 'selected' : '' ?>><?= $i ?> — <?= $i === 5 ? 'Excellent' : ($i===4 ? 'Very Good' : ($i===3 ? 'Good' : ($i===2 ? 'Fair' : 'Poor'))) ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Research & Publications (1-5)</label>
    <select name="research_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?>
        <?php $val = $i * 20; ?>
        <option value="<?= $val ?>" <?= (isset($meta['research_score']) && $meta['research_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Supervision / Mentorship (1-5)</label>
    <select name="supervision_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?>
        <?php $val = $i * 20; ?>
        <option value="<?= $val ?>" <?= (isset($meta['supervision_score']) && $meta['supervision_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Service & Administration (1-5)</label>
    <select name="service_score" class="form-select score-input" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?>
        <?php $val = $i * 20; ?>
        <option value="<?= $val ?>" <?= (isset($meta['service_score']) && $meta['service_score'] == $val) ? 'selected' : '' ?>><?= $i ?></option>
      <?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label font-weight-bold">Comments</label>
    <textarea name="comments" class="form-control" rows="4" placeholder="Enter comments, strengths, areas for improvement..." required><?= esc(old('comments') ?? $staff['evaluation_comments'] ?? '') ?></textarea>
  </div>

  <hr class="my-3">

  <div class="mb-3">
    <label class="form-label font-weight-bold">Overall Score (computed)</label>
    <input type="number" name="overall_score" id="overall_score" class="form-control bg-light text-primary fw-bold" min="0" max="100" value="<?= esc(old('overall_score') ?? $staff['evaluation_overall_score'] ?? '') ?>" required readonly>
    <div class="small text-muted mt-1">This value is automatically calculated as the average of the four fields above.</div>
  </div>

  <div class="mb-2">
    <strong>Computed total: <span id="computedTotalDisplay" class="text-primary">—</span></strong>
  </div>
</form>
