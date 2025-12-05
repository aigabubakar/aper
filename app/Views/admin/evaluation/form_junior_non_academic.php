<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="junior_non_academic">

  <label>Discipline (0-100)</label>
  <input type="number" name="discipline" min="0" max="100"
         data-component-score class="form-control">

  <label>Punctuality (0-100)</label>
  <input type="number" name="punctuality" min="0" max="100"
         data-component-score class="form-control">

  <label>Overall</label>
  <input type="number" name="overall_score" id="overall_score" readonly>
  <div>Computed total: <span data-target="computedTotal">—</span></div>
</form>
