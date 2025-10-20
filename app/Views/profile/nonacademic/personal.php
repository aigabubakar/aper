<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Personal Info<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px">
  <div class="card-body">
    <h4>Personal Information</h4>
    <?= view('partials/flash') ?>

    <form id="personalForm" method="post" action="<?= site_url('profile/nonacademic/personal/save') ?>">
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Phone</label>
          <input name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Date of birth</label>
          <input type="date" name="dob" class="form-control" value="<?= esc(old('dob', $user['dob'] ?? '')) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">--</option>
            <option value="male" <?= (old('gender',$user['gender'] ?? '')=='male')?'selected':'' ?>>Male</option>
            <option value="female" <?= (old('gender',$user['gender'] ?? '')=='female')?'selected':'' ?>>Female</option>
            <option value="other" <?= (old('gender',$user['gender'] ?? '')=='other')?'selected':'' ?>>Other</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/overview') ?>" class="btn btn-outline-secondary">Back</a>
        <button class="btn btn-primary" id="savePersonalBtn">Save & Continue</button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('personalForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = document.getElementById('savePersonalBtn');
  btn.disabled = true;
  const fd = new FormData(this);
  try {
    const res = await fetch(this.action, { method:'POST', body: fd, credentials:'same-origin', headers:{ 'X-Requested-With':'XMLHttpRequest' }});
    const json = await res.json();
    if (!res.ok) {
      if (json.errors) {
        alert(Object.values(json.errors).flat().join('\n'));
      } else alert(json.message || 'Server error');
      btn.disabled = false;
      return;
    }
    window.location.href = json.redirect || '<?= site_url('profile/nonacademic/employment') ?>';
  } catch (err) {
    console.error(err);
    alert('Network error');
    btn.disabled = false;
  }
});
</script>
<?= $this->endSection() ?>
