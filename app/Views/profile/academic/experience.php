
<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Academic — Qualifications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:1000px;">
  <div class="card-body">
    <h4 class="card-title">Qualifications & Publications</h4>
    <p class="text-muted mb-3">List publications, thesis, books and other research outputs.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="qualForm" action="<?= site_url('profile/academic/qualifications/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Publications (summary)</label>
          <textarea name="publications" class="form-control" rows="4"><?= esc(old('publications',$user['publications'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Dissertation / Thesis</label>
          <textarea name="dissertation" class="form-control" rows="3"><?= esc(old('dissertation',$user['dissertation'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Articles</label>
          <textarea name="articles" class="form-control" rows="3"><?= esc(old('articles',$user['articles'] ?? '')) ?></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Books / Monographs</label>
          <textarea name="books_monographs" class="form-control" rows="3"><?= esc(old('books_monographs',$user['books_monographs'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-4">
          <label class="form-label">Number of Publications Accepted</label>
          <input type="number" name="number_pub_accepted" class="form-control" value="<?= esc(old('number_pub_accepted',$user['number_pub_accepted'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Number of Points</label>
          <input type="number" name="number_of_points" class="form-control" value="<?= esc(old('number_of_points',$user['number_of_points'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
          <label class="form-label">Postgraduate Supervisor?</label>
          <select name="postgraduate_supervisor" class="form-select">
            <option value="">-- Select --</option>
            <option value="yes" <?= (old('postgraduate_supervisor',$user['postgraduate_supervisor'] ?? '') == 'yes') ? 'selected' : '' ?>>Yes</option>
            <option value="no" <?= (old('postgraduate_supervisor',$user['postgraduate_supervisor'] ?? '') == 'no') ? 'selected' : '' ?>>No</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Other Remarks</label>
          <textarea name="other_remark" class="form-control" rows="3"><?= esc(old('other_remark',$user['other_remark'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <a href="<?= site_url('profile/academic/teaching_research') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="qualSaveBtn" class="btn btn-primary" type="submit">
          <span id="qualSaveText">Save & Continue</span>
          <span id="qualSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('qualForm');
  const btn = document.getElementById('qualSaveBtn');
  const txt = document.getElementById('qualSaveText');
  const spinner = document.getElementById('qualSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function setLoading(on){
    if (on) { btn.setAttribute('disabled','disabled'); txt.textContent='Saving...'; spinner.classList.remove('d-none'); }
    else { btn.removeAttribute('disabled'); txt.textContent='Save & Continue'; spinner.classList.add('d-none'); }
  }
  async function showErrors(errors){
    const html = '<ul class="mb-0">'+Object.values(errors).map(v=>`<li>${v}</li>`).join('')+'</ul>';
    alertPlaceholder.innerHTML = `<div class="alert alert-danger">${html}</div>`;
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault(); e.stopPropagation();
    alertPlaceholder.innerHTML=''; setLoading(true);
    try {
      const fd = new FormData(form);
      const res = await fetch(form.action,{method:'POST',body:fd,credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'}});
      const ct = (res.headers.get('content-type')||'').toLowerCase();
      if (!ct.includes('application/json')) {
        const text = await res.text(); console.error('Unexpected response',text);
        Swal.fire({icon:'error',title:'Server error',text:'Unexpected server response. Check console.'}); setLoading(false); return;
      }
      const data = await res.json();
      if (!res.ok) {
        if (data.errors) { await Swal.fire({icon:'error',title:'Validation error',html:'<pre style="text-align:left">'+Object.values(data.errors).join('<br>')+'</pre>'}); }
        else { Swal.fire({icon:'error',title:'Error',text:data.message||'Server error'}); }
        setLoading(false); return;
      }
      // success
      await Swal.fire({icon:'success',title:'Saved',text:data.message||'Saved',timer:data.redirectDelay||1000,showConfirmButton:false,timerProgressBar:true});
      if (data.redirect) { window.location.href = data.redirect; } else { window.location.href = '<?= site_url('profile/academic/experience') ?>'; }
    } catch (err) {
      console.error(err); Swal.fire({icon:'error',title:'Network error',text:'See console'}); setLoading(false);
    }
  });
});
</script>
<?= $this->endSection() ?>




