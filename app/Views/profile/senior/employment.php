<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Senior Staff — Employment<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
  <!-- Sidebar (the sidebar partial already contains the column wrapper: col-lg-3) -->
  <?= view('layouts/sidebar') ?>

  <!-- Main column -->
  <div class="col-lg-9">
    <div class="page-title d-flex align-items-center justify-content-between mb-3">
      <!-- you can place breadcrumbs / page actions here -->
    </div>
                   
<div class="card mx-auto" style="max-width:1100px;">
  <div class="card-body">
    <h4 class="card-title">Employment History</h4>
    <p class="text-muted mb-3">Enter employment history details (appointment dates, promotions, salary, etc.).</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="employmentForm" action="<?= site_url('profile/senior/employment/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>

      <div class="row g-3 mb-3">
        <div class="col-md-3">
          <label class="form-label">Present Salary</label>
          <input type="number" name="present_salary" class="form-control" step="any" value="<?= esc(old('present_salary',$user['present_salary'] ?? '')) ?>" placeholder="e.g. 100000">
        </div>
        <div class="col-md-3">
          <label class="form-label">CONTISS</label>
          <input type="text" name="contiss" class="form-control" value="<?= esc(old('contiss',$user['contiss'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Step</label>
          <input type="text" name="step" class="form-control" value="<?= esc(old('step',$user['step'] ?? '')) ?>">
        </div>
      </div>

        <legend class="float-none w-auto px-2">Edo State University Employment History</legend>

        <div class="row g-3 mb-2 align-items-end">
            <div class="col-md-4">
            <label class="form-label">First Appointment</label>
            <input type="text" name="first_appointment_grade" class="form-control" value="<?= esc(old('first_appointment_grade',$user['first_appointment_grade'] ?? '')) ?>" placeholder="e.g. 7">
          </div>

          <div class="col-md-4">
            <label class="form-label">First Appointment Date</label>
            <input type="date" name="date_of_first_appointment" class="form-control" value="<?= esc(old('date_of_first_appointment',$user['date_of_first_appointment'] ?? '')) ?>">
          </div>
        </div>

        <div class="row g-3 mb-2">
          <div class="col-md-4">            
            <label class="form-label">Last Promotion</label>
            <input type="text" name="last_promotion_grade" class="form-control" value="<?= esc(old('last_promotion_grade',$user['last_promotion_grade'] ?? '')) ?>">
            </div>
          <div class="col-md-4">
             <label class="form-label">Last Promotion Date</label>
            <input type="date" name="last_promotion_date" class="form-control" value="<?= esc(old('last_promotion_date',$user['last_promotion_date'] ?? '')) ?>">
          </div>
          <div class="col-md-4">&nbsp;</div>
        </div>

        <div class="row g-3 mb-2">
          <div class="col-md-4">
            <label class="form-label">Current Appointment</label>
            <input type="text" name="current_appointment_grade" class="form-control" value="<?= esc(old('current_appointment_grade',$user['current_appointment_grade'] ?? '')) ?>">
              </div>
          <div class="col-md-4"><label class="form-label">Current Appointment Date</label>
            <input type="date" name="current_appointment_date" class="form-control" value="<?= esc(old('current_appointment_date',$user['current_appointment_date'] ?? '')) ?>">
         </div>
        </div>
     
          <div class="row g-6 mb-6">
              <div class="col-md-8">
                    <label class="form-label">Has your appointment been confirmed?</label>
                    <div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="appointment_confirmed" id="apc_yes" value="1" <?= (!empty($user['appointment_confirmed']) && $user['appointment_confirmed']) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="apc_yes">Yes</label>
                        </div>
                        
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="appointment_confirmed" id="apc_no" value="0" <?= empty($user['appointment_confirmed']) ? 'checked' : '' ?>>
                          <label class="form-check-label" for="apc_no">No</label>
                        </div>          
                          
                        <div id="apcDateWrap" class="row g-3 mb-3" style="display: <?= (!empty($user['appointment_confirmed']) && $user['appointment_confirmed']) ? 'flex' : 'none' ?>">
                          <div class="col-md-4">
                              <label class="form-label">When?</label>
                              <input type="date" name="appointment_confirmed_at" id="appointment_confirmed_at" class="form-control" value="<?= esc(old('appointment_confirmed_at',$user['appointment_confirmed_at'] ?? '')) ?>">
                          </div>
                        </div>
               </div>
            </div>
          <div class="col-md-6">
        </div>
    </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/senior/personal') ?>" class="btn btn-outline-secondary">Back</a>
        <div>
          <button id="employmentSaveBtn" class="btn btn-primary" type="submit">
            <span id="employmentSaveText">Save & Continue</span>
            <span id="employmentSpinner" class="spinner-border spinner-border-sm ms-2 d-none" aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </form>
</div>
<!-- Employement Information--> 
    

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('employmentForm');
  const btn = document.getElementById('employmentSaveBtn');
  const btnText = document.getElementById('employmentSaveText');
  const spinner = document.getElementById('employmentSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function setLoading(on){
    if (on) { btn && btn.setAttribute('disabled','disabled'); btnText && (btnText.textContent='Saving...'); spinner && spinner.classList.remove('d-none'); }
    else { btn && btn.removeAttribute('disabled'); btnText && (btnText.textContent='Save & Continue'); spinner && spinner.classList.add('d-none'); }
  }

  function showAlert(type, html){
    if (!alertPlaceholder) return;
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }

  if (!form) { console.warn('employmentForm not present'); return; }

  // appointment confirm UI
  const yes = document.getElementById('apc_yes');
  const no = document.getElementById('apc_no');
  const apcWrap = document.getElementById('apcDateWrap');
  const apcDate = document.getElementById('appointment_confirmed_at');

  function updateApcUI(){
    if (yes && yes.checked) {
      apcWrap.style.display = 'flex';
      apcDate && apcDate.removeAttribute('disabled');
      apcDate && apcDate.setAttribute('required','required');
    } else {
      apcWrap.style.display = 'none';
      if (apcDate) { apcDate.value = ''; apcDate.removeAttribute('required'); apcDate.setAttribute('disabled','disabled'); }
    }
  }
  updateApcUI();
  yes?.addEventListener('change', updateApcUI);
  no?.addEventListener('change', updateApcUI);

  form.addEventListener('submit', async function(e){
    e.preventDefault(); e.stopPropagation();
    alertPlaceholder && (alertPlaceholder.innerHTML='');
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
        console.error('Unexpected response:', text);
        await Swal.fire({icon:'error', title:'Server error', text:'Unexpected server response. Check console.'});
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

      // success
      await Swal.fire({icon:'success', title:data.message||'Saved', timer:data.redirectDelay||1000, showConfirmButton:false, timerProgressBar:true});
      if (data.redirect) window.location.href = data.redirect;
      else window.location.href = '<?= site_url('profile/senior/qualifications') ?>';

    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Check console.');
      setLoading(false);
    } finally {
      setTimeout(()=>setLoading(false), 300);
    }
  });

  // dynamic department load on faculty change (same endpoint you used earlier)
  const facultySelect = document.getElementById('facultySelect');
  const deptSel = document.getElementById('departmentSelect');
  facultySelect?.addEventListener('change', async function(){
    const fid = this.value;
    if (!deptSel) return;
    deptSel.innerHTML = '<option>Loading...</option>';
    if (! fid) { deptSel.innerHTML = '<option value="">-- Select Department --</option>'; return; }
    try {
      const res = await fetch('<?= site_url('api/departments/by-faculty') ?>/' + encodeURIComponent(fid), { credentials: 'same-origin' });
      if (! res.ok) { deptSel.innerHTML = '<option value="">-- Select Department --</option>'; return; }
      const arr = await res.json();
      deptSel.innerHTML = '<option value="">-- Select Department --</option>';
      if (Array.isArray(arr)) {
        for (const d of arr) {
          const o = document.createElement('option'); o.value = d.id; o.textContent = d.name; deptSel.appendChild(o);
        }
      }
    } catch (e) { console.warn('dept load failed', e); deptSel.innerHTML = '<option value="">-- Select Department --</option>'; }
  });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const yesEl = document.getElementById('apc_yes');
  const noEl  = document.getElementById('apc_no');
  const dateGroup = document.getElementById('appointmentConfirmedDateGroup');
  const dateInput = document.getElementById('appointment_confirmed_at');

  function updateAppointmentConfirmedUI() {
    const val = document.querySelector('input[name="appointment_confirmed"]:checked')?.value || '';
    if (val === 'yes') {
      dateGroup.style.display = '';
      dateInput.setAttribute('required','required');
    } else {
      dateGroup.style.display = 'none';
      dateInput.removeAttribute('required');
      dateInput.value = ''; // optional: clear value when 'No'
    }
  }

  // initial state on page load
  updateAppointmentConfirmedUI();

  // attach listeners
  [yesEl, noEl].forEach(r => r && r.addEventListener('change', updateAppointmentConfirmedUI));
});
</script>
<?= $this->endSection() ?>

