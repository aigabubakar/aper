<?php
// academic_form.php
// Expects $staff available (array) when rendered
?>
<form id="evaluationForm" action="<?= site_url('evaluation/submit') ?>" method="post" novalidate>
  <?= csrf_field() ?>
  <input type="hidden" name="staff_id" value="<?= esc($staff['id'] ?? '') ?>">
  <input type="hidden" name="category" value="academic">

  <div class="mb-2">
    <h6>Evaluator: <small class="text-muted"><?= esc(session()->get('fullname') ?? 'You') ?></small></h6>
    <div><strong>Staff:</strong> <?= esc($staff['fullname'] ?? $staff['email'] ?? 'N/A') ?></div>
    <div class="small text-muted">Category: Academic</div>
  </div>

  <hr>

  <div class="mb-3">
    <label class="form-label">Teaching Quality</label>
    <select name="teaching_score" class="form-select" required>
      <option value="">-- Select rating (1-5) --</option>
      <?php for ($i=5;$i>=1;$i--): ?>
        <option value="<?= $i ?>"><?= $i ?> — <?= $i === 5 ? 'Excellent' : ($i===4 ? 'Very Good' : ($i===3 ? 'Good' : ($i===2 ? 'Fair' : 'Poor'))) ?></option>
      <?php endfor; ?>
    </select>
    <div class="form-text">Clarity, preparedness, student engagement.</div>
  </div>

  <div class="mb-3">
    <label class="form-label">Research & Publications</label>
    <select name="research_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i++): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
    <div class="form-text">Quality & quantity of research outputs.</div>
  </div>

  <div class="mb-3">
    <label class="form-label">Supervision / Mentorship</label>
    <select name="supervision_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Service & Administration</label>
    <select name="service_score" class="form-select" required>
      <option value="">-- Select rating --</option>
      <?php for ($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?></option><?php endfor; ?>
    </select>
    <div class="form-text">Committee work, outreach, admin contributions.</div>
  </div>

  <div class="mb-3">
    <label class="form-label">Overall Comment</label>
    <textarea name="comments" rows="4" class="form-control" placeholder="Your comments, strengths, areas for improvement..."></textarea>
  </div>

  <!-- Optional: summary score computed by server -->
  <input type="hidden" name="form_type" value="academic">

</form>
