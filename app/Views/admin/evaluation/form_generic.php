<?php // app/Views/admin/evaluation/form_generic.php ?>
<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post" class="p-2">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="<?= esc($category) ?>">

  <div class="mb-3">
    <label class="form-label">Overall Score (0-100)</label>
    <input type="number" name="overall_score" class="form-control" min="0" max="100" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Comments</label>
    <textarea name="comments" class="form-control" rows="4"></textarea>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-primary">Save</button>
  </div>
</form>
