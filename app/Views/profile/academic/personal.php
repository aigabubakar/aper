<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Academic — Personal<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:1000px;">
  <div class="card-body">
    <h4 class="card-title">Personal Information</h4>
    <p class="text-muted mb-3">Fill your personal details. These will be used for the appraisal and internal records.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="personalForm" action="<?= site_url('profile/academic/personal/save') ?>" method="post" enctype="multipart/form-data" novalidate>
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
              <option value="<?= (int)$f['id'] ?>" <?= (old('faculty_id', $user['faculty_id'] ?? '') == $f['id']) ? 'selected' : '' ?>><?= esc($f['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Department</label>
          <select id="departmentSelect" name="department_id" class="form-select">
            <option value="">-- Select Department --</option>
            <?php foreach ($departments as $d): ?>
              <option value="<?= (int)$d['id'] ?>" <?= (old('department_id', $user['department_id'] ?? '') == $d['id']) ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
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
        <a href="<?= site_url('profile/academic/employment') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="personalSaveBtn" class="btn btn-primary" type="submit">
          <span id="personalSaveText">Save & Continue</span>
          <span id="personalSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>
</div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 (if not already loaded in layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('personalForm');
  const btn = document.getElementById('personalSaveBtn');
  const btnText = document.getElementById('personalSaveText');
  const spinner = document.getElementById('personalSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  // defensive helpers
  function setLoading(on) {
    try {
      if (on) {
        btn && btn.setAttribute('disabled','disabled');
        if (btnText) btnText.textContent = 'Saving...';
        spinner && spinner.classList.remove('d-none');
      } else {
        btn && btn.removeAttribute('disabled');
        if (btnText) btnText.textContent = 'Save & Continue';
        spinner && spinner.classList.add('d-none');
      }
    } catch (e) { console.warn('setLoading error', e); }
  }

  function showAlert(type, html) {
    if (!alertPlaceholder) return;
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }

  if (! form) {
    console.warn('personalForm not found; form will submit normally.');
    return;
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    e.stopPropagation();
    alertPlaceholder && (alertPlaceholder.innerHTML = '');
    setLoading(true);

    const fd = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();
      if (! ct.includes('application/json')) {
        const text = await res.text();
        console.error('Unexpected server response:', text);
        try {
          await Swal.fire({ icon: 'error', title: 'Server error', text: 'Unexpected server response. Check console.' });
        } catch (swErr) { /* ignore */ }
        setLoading(false);
        return;
      }

      const data = await res.json();

      if (! res.ok) {
        if (data.errors) {
          const list = Object.values(data.errors).map(v => `<li>${v}</li>`).join('');
          showAlert('danger', `<ul class="mb-0">${list}</ul>`);
        } else {
          showAlert('danger', data.message || 'Server error');
        }
        setLoading(false);
        return;
      }

      // Success — show feedback then redirect
      try {
        await Swal.fire({
          icon: 'success',
          title: data.message || 'Saved',
          html: `<div>${data.message || 'Saved successfully'}</div>`,
          timer: data.redirectDelay || 1000,
          timerProgressBar: true,
          showConfirmButton: false
        });
      } catch (swErr) {
        console.warn('SweetAlert failed, continuing to redirect', swErr);
      }

      if (data.redirect) {
        window.location.href = data.redirect;
      } else {
        window.location.href = '<?= site_url('profile/academic/employment') ?>';
      }

    } catch (err) {
      console.error('Network/JS error:', err);
      showAlert('danger', 'Network error. Try again.');
      setLoading(false);
    } finally {
      // ensure loading is turned off if alert/redirect didn't happen
      setTimeout(()=>setLoading(false), 300);
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


