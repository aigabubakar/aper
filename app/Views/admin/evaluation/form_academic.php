<?php // app/Views/admin/evaluation/form_academic.php ?>
<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post" class="p-2">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="academic">

  <div class="mb-3">
    <label class="form-label">Teaching (0-100)</label>
    <input type="number" name="teaching" class="form-control score-input" min="0" max="100" data-score="component" data-weight="0.5" value="<?= old('teaching') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Research (0-100)</label>
    <input type="number" name="research" class="form-control score-input" min="0" max="100" data-score="component" data-weight="0.5" value="<?= old('research') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Overall Score (computed)</label>
    <input type="number" name="overall_score" id="overall_score" class="form-control" min="0" max="100" required readonly>
    <div class="small text-muted mt-1">This value is computed from the component scores. You can still edit if needed (remove readonly).</div>
  </div>

  <div class="mb-3">
    <label class="form-label">Comments</label>
    <textarea name="comments" class="form-control" rows="4"><?= old('comments') ?></textarea>
  </div>

  <div class="mb-3">
    <strong>Computed total: <span id="computedTotalDisplay">—</span></strong>
  </div>
</form>
