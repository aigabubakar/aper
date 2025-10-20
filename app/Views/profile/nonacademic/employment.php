<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Employment History<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px">
  <div class="card-body">
    <h4>Employment History</h4>
    <?= view('partials/flash') ?>

    <form id="employmentForm" method="post" action="<?= site_url('profile/nonacademic/employment/save') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Department</label>
        <input name="department" class="form-control" value="<?= esc(old('department',$user['department'] ?? '')) ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Designation</label>
        <input name="designation" class="form-control" value="<?= esc(old('designation',$user['designation'] ?? '')) ?>">
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Period From (Year)</label>
          <input type="number" name="period_from" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_from',$user['period_from'] ?? '')) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Period To (Year)</label>
          <input type="number" name="period_to" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_to',$user['period_to'] ?? '')) ?>" required>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/nonacademic/personal') ?>" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-primary" id="saveEmploymentBtn">Save & Continue</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('employmentForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = document.getElementById('saveEmploymentBtn');
  btn.disabled = true;
  const fd = new FormData(this);
  try {
    const res = await fetch(this.action, { method:'POST', body: fd, credentials:'same-origin', headers:{ 'X-Requested-With':'XMLHttpRequest' }});
    const json = await res.json();
    if (!res.ok) {
      if (json.errors) alert(Object.values(json.errors).flat().join('\n'));
      else alert(json.message || 'Server error');
      btn.disabled = false;
      return;
    }
    window.location.href = json.redirect || '<?= site_url('profile/nonacademic/qualifications') ?>';
  } catch (err) {
    console.error(err);
    alert('Network error'); btn.disabled = false;
  }
});
</script>
<?= $this->endSection() ?>
