<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Senior — Qualifications<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:1000px;">
  <div class="card-body">
    <h4 class="card-title">Qualifications & Publications</h4>
    <p class="text-muted mb-3">List publications, dissertations, books and supervisor status.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="qualForm" action="<?= site_url('profile/senior/qualifications/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Publications (summary)</label>
          <textarea name="publications" class="form-control" rows="4"><?= esc(old('publications',$user['publications'] ?? '')) ?></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Dissertation / Thesis</label>
          <textarea name="dissertation" class="form-control" rows="3"><?= esc(old('dissertation',$user['dissertation'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label">Articles</label>
          <textarea name="articles" class="form-control" rows="3"><?= esc(old('articles',$user['articles'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
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
          <label class="form-label">Participation / Conferences</label>
          <textarea name="participation" class="form-control" rows="3"><?= esc(old('participation',$user['participation'] ?? '')) ?></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Other Remarks</label>
          <textarea name="other_remark" class="form-control" rows="3"><?= esc(old('other_remark',$user['other_remark'] ?? '')) ?></textarea>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <a href="<?= site_url('profile/senior/employment') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="qualSaveBtn" class="btn btn-primary" type="submit">
          <span id="qualSaveText">Save & Continue</span>
          <span id="qualSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </form>
  </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>






<script>
document.addEventListener('DOMContentLoaded', function() {
  // change selector if your form id differs
  const form = document.getElementById('expForm') || document.querySelector('form');
  if (!form) return console.warn('No form found for AJAX handler.');

  // UI elements
  const submitBtn = form.querySelector('button[type="submit"]');
  const submitText = submitBtn ? submitBtn.querySelector('span') : null;

  // helper to set loading state
  function setLoading(on, text = 'Saving...') {
    if (!submitBtn) return;
    if (on) {
      submitBtn.setAttribute('disabled', 'disabled');
      if (submitText) submitText.textContent = text;
      // add spinner if not present
      if (!submitBtn.querySelector('.ajax-spinner')) {
        const sp = document.createElement('span');
        sp.className = 'ajax-spinner spinner-border spinner-border-sm ms-2';
        sp.setAttribute('role','status');
        sp.setAttribute('aria-hidden','true');
        submitBtn.appendChild(sp);
      }
    } else {
      submitBtn.removeAttribute('disabled');
      if (submitText) submitText.textContent = submitBtn.dataset.originalText || 'Save & Continue';
      const sp = submitBtn.querySelector('.ajax-spinner');
      if (sp) sp.remove();
    }
  }

  // Save original button text
  if (submitBtn && !submitBtn.dataset.originalText) {
    submitBtn.dataset.originalText = submitBtn.textContent.trim();
  }

  // Prevent default submit and do AJAX instead
  form.addEventListener('submit', async function(e) {
    e.preventDefault(); // CRITICAL
    e.stopPropagation();

    // clear any existing alerts from previous code
    const alertPlaceholder = document.getElementById('alert-placeholder');
    if (alertPlaceholder) alertPlaceholder.innerHTML = '';

    setLoading(true);

    const fd = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',     // ensure session + CSRF cookie sent
        headers: { 'X-Requested-With': 'XMLHttpRequest' } // signals server to reply JSON
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();
      let data = null;

      // If JSON, parse it
      if (ct.includes('application/json')) {
        data = await res.json();
      } else {
        // Non-JSON: helpful debug - show server HTML inside modal and stop
        const text = await res.text();
        console.error('Unexpected non-JSON server response:', text);
        await Swal.fire({
          icon: 'error',
          title: 'Server response unexpected',
          html: `<pre style="white-space:pre-wrap;max-height:300px;overflow:auto;">${escapeHtml(text).slice(0,2000)}</pre>`,
          confirmButtonText: 'OK'
        });
        setLoading(false);
        return;
      }

      // If status not OK (4xx/5xx), show validation or error
      if (!res.ok) {
        if (data.errors) {
          // Validation errors object -> flatten for display
          const html = '<ul style="text-align:left;margin:0;">' +
                Object.values(data.errors).map(v => `<li>${escapeHtml(v)}</li>`).join('') +
                '</ul>';
          await Swal.fire({ icon: 'error', title: 'Validation error', html, confirmButtonText: 'Fix' });
        } else {
          await Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Server error' });
        }
        setLoading(false);
        return;
      }

      // Success flow (HTTP 200 with success true)
      if (data && data.success) {
        const delay = parseInt(data.redirectDelay ?? 1200, 10);

        // show nice success SweetAlert
        await Swal.fire({
          icon: 'success',
          title: 'Saved',
          html: `<div>${escapeHtml(data.message ?? 'Saved successfully')}</div>`,
          timer: Math.max(900, delay),
          timerProgressBar: true,
          showConfirmButton: false,
          allowOutsideClick: false
        });

        // perform redirect (if provided) after delay or immediately if none
        if (data.redirect) {
          // small safety: only trust same-origin URLs
          if (new URL(data.redirect, location.href).origin === location.origin) {
            window.location.href = data.redirect;
          } else {
            console.warn('Redirect URL is cross-origin; navigating anyway:', data.redirect);
            window.location.href = data.redirect;
          }
          return;
        } else {
          // fallback: go to dashboard
          window.location.href = '<?= site_url('dashboard') ?>';
          return;
        }
      }

      // If data.success is not present or false (unexpected)
      await Swal.fire({ icon: 'info', title: 'Notice', text: data.message ?? 'Operation completed' });
      setLoading(false);

    } catch (err) {
      console.error('AJAX submit error', err);
      await Swal.fire({ icon: 'error', title: 'Network error', text: 'Check console and try again.' });
      setLoading(false);
    }
  });

  // small helper to escape HTML when inserting server text into modal
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;')
      .replace(/'/g,'&#39;');
  }
});
</script>


<?= $this->endSection() ?>
