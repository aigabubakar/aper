
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Senior — Experience<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
  <!-- Sidebar (the sidebar partial already contains the column wrapper: col-lg-3) -->
  <?= view('layouts/sidebar') ?>

  <!-- Main column -->
  <div class="col-lg-9">
    <div class="page-title d-flex align-items-center justify-content-between mb-3">
      <!-- you can place breadcrumbs / page actions here -->
    </div>

    <div class="card mx-auto" style="max-width:1000px;">
  <div class="card-body">
    <h4 class="card-title">Experience / Activities</h4>
    <p class="text-muted mb-3">Add your external professional experience and descriptions.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="expForm" action="<?= site_url('profile/junior/experience/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-12"><h6>External Experience #1</h6></div>

        <div class="col-md-6">
          <label class="form-label">Institution Name</label>
          <input name="exp_out_institution_name1" class="form-control" value="<?= esc(old('exp_out_institution_name1', $user['exp_out_institution_name1'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Designation</label>
          <input name="exp_out_designation1" class="form-control" value="<?= esc(old('exp_out_designation1', $user['exp_out_designation1'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Specialization</label>
          <input name="exp_out_specialization1" class="form-control" value="<?= esc(old('exp_out_specialization1', $user['exp_out_specialization1'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Date (end or period)</label>
          <input type="date" name="exp_out_date1" class="form-control" value="<?= esc(old('exp_out_date1', $user['exp_out_date1'] ?? '')) ?>">
        </div>

        <div class="col-12"><h6 class="mt-3">External Experience #2</h6></div>

        <div class="col-md-6">
          <label class="form-label">Institution Name</label>
          <input name="exp_out_institution_name2" class="form-control" value="<?= esc(old('exp_out_institution_name2', $user['exp_out_institution_name2'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Designation</label>
          <input name="exp_out_designation2" class="form-control" value="<?= esc(old('exp_out_designation2', $user['exp_out_designation2'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Specialization</label>
          <input name="exp_out_specialization2" class="form-control" value="<?= esc(old('exp_out_specialization2', $user['exp_out_specialization2'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Date (end or period)</label>
          <input type="date" name="exp_out_date2" class="form-control" value="<?= esc(old('exp_out_date2', $user['exp_out_date2'] ?? '')) ?>">
        </div>

        <div class="col-12">
          <label class="form-label">Professional Experience (summary)</label>
          <textarea name="professional_experience" class="form-control" rows="4"><?= esc(old('professional_experience', $user['professional_experience'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <a href="<?= site_url('profile/junior/qualifications') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="saveExpBtn" class="btn btn-primary" type="submit">
          <span id="saveExpText">Save & Continue</span>
          <span id="saveExpSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
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
<!-- Ensure SweetAlert2 loaded: -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('expForm') || document.querySelector('form');
  if (!form) return console.warn('No form found for AJAX handler');

  const submitBtn = form.querySelector('button[type="submit"]');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function setLoading(on) {
    if (!submitBtn) return;
    if (on) { submitBtn.setAttribute('disabled','disabled'); }
    else { submitBtn.removeAttribute('disabled'); }
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    e.stopPropagation();

    if (alertPlaceholder) alertPlaceholder.innerHTML = '';
    setLoading(true);

    try {
      const fd = new FormData(form);
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();
      let data = null;
      if (ct.includes('application/json')) {
        data = await res.json();
        console.log('AJAX response JSON:', data);
      } else {
        const text = await res.text();
        console.error('AJAX response not JSON:', text);
        await Swal.fire({icon:'error',title:'Unexpected server response',text:'Server did not return JSON. Check console.'});
        setLoading(false);
        return;
      }

      // if HTTP error
      if (!res.ok) {
        console.warn('Server returned non-2xx status', res.status);
        if (data.missing_columns) {
          await Swal.fire({icon:'error', title:'DB schema issue', html:'Missing columns: <pre>'+data.missing_columns.join(', ')+'</pre>'});
        } else if (data.errors) {
          const list = Object.values(data.errors).map(v=>`<li>${v}</li>`).join('');
          await Swal.fire({icon:'error', title:'Validation error', html:'<ul style="text-align:left">'+list+'</ul>'});
        } else {
          await Swal.fire({icon:'error', title:'Error', text: data.message || 'Server error'});
        }
        setLoading(false);
        return;
      }

      // success path
      if (data.success || data.ok) {
        await Swal.fire({
          icon: 'success',
          title: 'Saved',
          html: '<div>'+ (data.message || 'Saved successfully') +'</div>',
          timer: data.redirectDelay || 1200,
          timerProgressBar: true,
          showConfirmButton: false
        });

        if (data.redirect) {
          try {
            const redirectUrl = new URL(data.redirect, location.href);
            // only allow same-origin redirects by default
            if (redirectUrl.origin === location.origin) {
              window.location.href = data.redirect;
            } else {
              // if you really want to follow cross-origin, remove this guard
              console.warn('Refusing to redirect to different origin:', redirectUrl.href);
              window.location.href = data.redirect;
            }
            return;
          } catch (err) {
            console.error('Bad redirect URL', data.redirect, err);
          }
        }

        // fallback
        window.location.href = '<?= site_url('profile/success') ?>';
        return;
      }

      // fallback if JSON shape unexpected
      console.warn('Unexpected response shape', data);
      await Swal.fire({icon:'info', title:'Notice', text: data.message || 'Operation completed' });

    } catch (err) {
      console.error('AJAX submit error', err);
      await Swal.fire({icon:'error', title:'Network error', text:'See console for details.'});
    } finally {
      setLoading(false);
    }
  });
});
</script>

<?= $this->endSection() ?>

