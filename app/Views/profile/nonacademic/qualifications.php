<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Qualifications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px">
  <div class="card-body">
    <h4>Qualifications</h4>
    <?= view('partials/flash') ?>

    <form id="qualForm" method="post" action="<?= site_url('profile/nonacademic/qualifications/save') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Qualifications / Certificates</label>
        <textarea name="qualifications" class="form-control" rows="6"><?= esc(old('qualifications',$user['qualifications'] ?? '')) ?></textarea>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/nonacademic/employment') ?>" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-primary" id="saveQualBtn">Save & Continue</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('qualForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = document.getElementById('saveQualBtn');
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
    window.location.href = json.redirect || '<?= site_url('profile/nonacademic/experience') ?>';
  } catch (err) {
    console.error(err);
    alert('Network error'); btn.disabled = false;
  }
});
</script>
<?= $this->endSection() ?>
