<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="senior_non_academic">

  <label>Administration (0-100)</label>
  <input type="number" name="administration" min="0" max="100"
         class="form-control score-input" data-weight="0.6">

  <label>Service (0-100)</label>
  <input type="number" name="service" min="0" max="100"
         class="form-control score-input" data-weight="0.4">

  <label>Overall</label>
  <input type="number" name="overall_score" id="overall_score" readonly>

  <div>Computed total: <span id="computedTotalDisplay">—</span></div>

</form>
