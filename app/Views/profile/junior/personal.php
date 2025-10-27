<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Junior Staff — Personal Info<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
  <!-- Sidebar (the sidebar partial already contains the column wrapper: col-lg-3) -->
  <?= view('layouts/sidebar') ?>

  <!-- Main column -->
  <div class="col-lg-9">
    <div class="page-title d-flex align-items-center justify-content-between mb-3">
      <!-- you can place breadcrumbs / page actions here -->
    </div>
    <div class="card mx-auto" style="max-width:900px;">
  <div class="card-body">
    <h4 class="card-title">Junior Non-Academic — Personal Information</h4>
    <p class="text-muted mb-3">Fill these personal details. Fields marked * are required.</p>

    <!-- alert placeholder -->
    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="juniorPersonalForm" action="<?= site_url('profile/junior/personal/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" value="<?= esc($user['fullname'] ?? '') ?>" disabled>
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" disabled>
        </div>

        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Date of Birth</label>
          <input type="date" name="dob" class="form-control" value="<?= esc(old('dob', $user['dob'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">-- Select --</option>
            <option value="male" <?= (old('gender', $user['gender'] ?? '') == 'male') ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= (old('gender', $user['gender'] ?? '') == 'female') ? 'selected' : '' ?>>Female</option>
            <option value="other" <?= (old('gender', $user['gender'] ?? '') == 'other') ? 'selected' : '' ?>>Other</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Faculty</label>
          <select id="facultySelect" name="faculty_id" class="form-select">
            <option value="">-- Select Faculty --</option>
            <?php foreach ($faculties as $f): ?>
              <option value="<?= $f['id'] ?>" <?= (old('faculty_id', $user['faculty_id'] ?? '') == $f['id']) ? 'selected' : '' ?>><?= esc($f['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Department</label>
          <select id="departmentSelect" name="department_id" class="form-select">
            <option value="">-- Select Department --</option>
            <?php foreach ($departments as $d): ?>
              <option value="<?= $d['id'] ?>" <?= (old('department_id', $user['department_id'] ?? '') == $d['id']) ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Designation</label>
          <input name="designation" class="form-control" value="<?= esc(old('designation',$user['designation'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Grade Level</label>
          <input name="grade_level" class="form-control" value="<?= esc(old('grade_level',$user['grade_level'] ?? '')) ?>">
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
        <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
        <button id="saveBtn" class="btn btn-primary" type="submit">
          <span id="saveText">Save & Continue</span>
          <span id="saveSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>



<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('juniorPersonalForm');
  const saveBtn = document.getElementById('saveBtn');
  const saveText = document.getElementById('saveText');
  const saveSpinner = document.getElementById('saveSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function showAlert(type, html) {
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth',block:'center'});
  }

  function setLoading(on) {
    if (on) {
      saveBtn.setAttribute('disabled','disabled');
      saveText.textContent = 'Saving...';
      saveSpinner.classList.remove('d-none');
    } else {
      saveBtn.removeAttribute('disabled');
      saveText.textContent = 'Save & Continue';
      saveSpinner.classList.add('d-none');
    }
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    alertPlaceholder.innerHTML = '';
    setLoading(true);

    const fd = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
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
        setLoading(false);
        return;
      }

      // success
      showAlert('success', data.message || 'Saved');
      setTimeout(()=> {
        window.location.href = data.redirect || '<?= site_url('profile/junior/employment') ?>';
      }, 900);

    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Try again.');
    } finally {
      setLoading(false);
    }
  });

  // optional: load departments when faculty changes
  const facultySelect = document.getElementById('facultySelect');
  facultySelect?.addEventListener('change', async function(){
    const fid = this.value;
    const deptSel = document.getElementById('departmentSelect');
    if (! fid) { deptSel.innerHTML = '<option value="">-- Select Department --</option>'; return; }
    try {
      const res = await fetch('<?= site_url('api/departments/by-faculty') ?>/' + fid, { credentials: 'same-origin' });
      if (!res.ok) return;
      const data = await res.json();
      deptSel.innerHTML = '<option value="">-- Select Department --</option>';
      for (const d of data) {
        const opt = document.createElement('option');
        opt.value = d.id; opt.textContent = d.name;
        deptSel.appendChild(opt);
      }
    } catch (e) { console.warn(e); }
  });
});
</script>
<?= $this->endSection() ?>