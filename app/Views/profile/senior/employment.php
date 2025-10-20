<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Senior Staff — Employment<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px;">
  <div class="card-body">
    <h4 class="card-title">Senior Non-Academic — Employment History</h4>
    <p class="text-muted mb-3">Add employment history and duties for the reporting period.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="seniorEmploymentForm" action="<?= site_url('profile/senior/employment/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Main Job / Role</label>
          <input name="main_job" class="form-control" value="<?= esc(old('main_job',$user['main_job'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Position in Institution</label>
          <input name="position_institution" class="form-control" value="<?= esc(old('position_institution',$user['position_institution'] ?? '')) ?>">
        </div>

        <div class="col-12">
          <label class="form-label">Activities Within University</label>
          <textarea name="activities_within_university" class="form-control" rows="4"><?= esc(old('activities_within_university',$user['activities_within_university'] ?? '')) ?></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Activities Outside University</label>
          <textarea name="activities_outside_university" class="form-control" rows="3"><?= esc(old('activities_outside_university',$user['activities_outside_university'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Trainings (comma-separated)</label>
          <input name="trainings" class="form-control" value="<?= esc(old('trainings',$user['trainings'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Certifications</label>
          <input name="certifications" class="form-control" value="<?= esc(old('certifications',$user['certifications'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Reporting Period From (Year)</label>
          <input type="number" min="1900" max="<?= date('Y')+1 ?>" name="period_from" class="form-control" required value="<?= esc(old('period_from',$user['period_from'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Reporting Period To (Year)</label>
          <input type="number" min="1900" max="<?= date('Y')+1 ?>" name="period_to" class="form-control" required value="<?= esc(old('period_to',$user['period_to'] ?? '')) ?>">
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <a href="<?= site_url('profile/senior/personal') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="saveEmpBtn" class="btn btn-primary" type="submit">
          <span id="saveEmpText">Save & Continue</span>
          <span id="saveEmpSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('seniorEmploymentForm');
  const btn = document.getElementById('saveEmpBtn');
  const btnText = document.getElementById('saveEmpText');
  const spinner = document.getElementById('saveEmpSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function showAlert(type, html) {
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }
  function setLoading(on){
    if (on) { btn.setAttribute('disabled','disabled'); btnText.textContent = 'Saving...'; spinner.classList.remove('d-none'); }
    else { btn.removeAttribute('disabled'); btnText.textContent = 'Save & Continue'; spinner.classList.add('d-none'); }
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    alertPlaceholder.innerHTML = '';
    setLoading(true);
    const fd = new FormData(form);
    try {
      const res = await fetch(form.action, {
        method: 'POST', body: fd, credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const ct = res.headers.get('content-type') || '';
      const data = ct.includes('application/json') ? await res.json() : null;
      if (!res.ok) {
        if (data && data.errors) {
          const list = Object.values(data.errors).map(v => `<li>${v}</li>`).join('');
          showAlert('danger', `<ul class="mb-0">${list}</ul>`);
        } else {
          showAlert('danger', data?.message || 'Server error');
        }
        setLoading(false); return;
      }
      showAlert('success', data.message || 'Saved');
      setTimeout(()=> { window.location.href = data.redirect || '<?= site_url('profile/senior/qualifications') ?>'; }, 900);
    } catch (err) {
      console.error(err); showAlert('danger','Network error. Try again.');
    } finally { setLoading(false); }
  });
});
</script>
<?= $this->endSection() ?>
