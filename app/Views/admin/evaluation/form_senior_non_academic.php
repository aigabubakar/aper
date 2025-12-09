<form id="evaluationForm" action="<?= site_url('admin/evaluation/submit') ?>" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id']) ?>">
  <input type="hidden" name="category" value="senior_non_academic">


  <label>Discipline (0-10)</label>
  <input type="number" name="discipline" min="0" max="100" data-component-score class="form-control">

  <label>Punctuality (0-10)</label>
  <input type="number" name="punctuality" min="0" max="100" data-component-score class="form-control">  

  <label>Administration (0-10)</label>
  <input type="number" name="administration" min="0" max="100" class="form-control score-input" data-weight="0.6">

  <label>Service (0-10)</label>
  <input type="number" name="service" min="0" max="100"class="form-control score-input" data-weight="0.4">

  <div class="mb-3">
    <label class="form-label">Job Knowledge & Skills</label>
    <select name="skills_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Punctuality & Attendance</label>
    <select name="punctuality_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Teamwork & Communication</label>
    <select name="teamwork_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Initiative & Problem Solving</label>
    <select name="initiative_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=10;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Unit head Comments</label>
    <textarea name="comments" rows="4" class="form-control" placeholder="Strengths, development areas, recommendations..."></textarea>
  </div>
  
  <hr>

  <label>Overall</label>
  <input type="number" name="overall_score" id="overall_score" readonly>

  <div>Computed total: <span id="computedTotalDisplay">—</span></div>


  <input type="hidden" name="staff_id" value="<?= esc($staff['id'] ?? '') ?>">
  <input type="hidden" name="category" value="non_academic">

  <div class="mb-2">
    <h6>Evaluator: <small class="text-muted"><?= esc(session()->get('fullname') ?? 'You') ?></small></h6>
    <div><strong>Staff:</strong> <?= esc($staff['fullname'] ?? $staff['email'] ?? 'N/A') ?></div>
    <div class="small text-muted">Category: Non-Academic</div>
  </div>

</form>



