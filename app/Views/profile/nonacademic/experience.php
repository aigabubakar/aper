<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Experience & Activities<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px">
  <div class="card-body">
    <h4>Experience & Activities</h4>
    <?= view('partials/flash') ?>

    <form id="expForm" method="post" action="<?= site_url('profile/nonacademic/experience/save') ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label">Experience / Activities (list, describe roles, responsibilities)</label>
        <textarea name="experience_activities" class="form-control" rows="8"><?= esc(old('experience_activities',$user['experience_activities'] ?? '')) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Emergency Contact</label>
        <input name="emergency_contact" class="form-control" value="<?= esc(old('emergency_contact',$user['emergency_contact'] ?? '')) ?>">
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/nonacademic/qualifications') ?>" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-success" id="saveExpBtn">Finish & Save</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('expForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = document.getElementById('saveExpBtn');
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
    // redirect to overview
    window.location.href = json.redirect || '<?= site_url('profile/overview') ?>';
  } catch (err) {
    console.error(err);
    alert('Network error'); btn.disabled = false;
  }
});
</script>
<?= $this->endSection() ?>
