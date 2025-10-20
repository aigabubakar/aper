<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Complete Registration<?= $this->endSection() ?>

<?= $this->section('content') ?>


  <!-- login page start-->
    <div class="page-content">
		<div class="form-v4-content">
    <div class="container-fluid p-0"> 
      <div class="row m-0">
        <div class="col-xl-5"><img class="bg-img-cover bg-center" src="../assets/images/login/conf_talk.jpg" alt="looginpage"></div>
        <div class="col-xl-7 p-0"> 
          <div class="login-card login-dark">
            <div>
              <div class="login-main create-account"> 
            <h5 class="card-title mb-3">Complete Your Registration</h5>
              <?= view('partials/flash') // optional partial to render flash messages ?>

              <form class="theme-form" id="registerForm" action="<?= base_url('save-registration') ?>" method="post" novalidate>
                <?= csrf_field() ?>

                <?php
                    $startYear = 2016;                   // adjust to your earliest allowed year
                    $currentYear = (int) date('Y');
                    //$maxYear = $currentYear + 1;         // allow reporting up to next year if needed
                    $maxYear = $currentYear ;    
                    $selectedFrom = old('period_from') ?? ($staff['period_from'] ?? '');
                    $selectedTo   = old('period_to')   ?? ($staff['period_to']   ?? '');
                ?>
                        <p>Enter your personal details to create account</p>
                        <div class="form-group">
                          <label class="col-form-label pt-0">Your Name</label> 
                          <div class="row g-2">
                            <div class="col-sm-12">
                              <input class="form-control" type="text" required="" name="fullname" value="<?= esc(old('fullname', $staff['fullname'] ?? '')) ?>">
                            </div>
                            <label class="col-form-label pt-0">Staff Id Number</label> 
                            <div class="col-sm-6">
                              <input class="form-control" type="text" name="staff_id" value="<?= esc(old('staff_number', $staff['staff_number'] ?? '')) ?>" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="col-form-label">Email Address</label>
                          <input class="form-control" type="email"name="email" value="<?= esc(old('email', $staff['email'] ?? '')) ?>" readonly>
                        </div>
                        <div class="form-group">
                          <label class="col-form-label">Phone Number</label>
                          <div class="form-input position-relative">
                            <input class="form-control" type="text" name="phone" id="phone" class="form-control" placeholder="+2348012345678" value="<?= esc(old('phone', $staff['phone'] ?? '')) ?>">
                          </div>
                        </div>

                    <div class="mb-3">
                      <label for="category" class="form-label">Category of staff</label>
                      <select name="category" id="category" class="form-select" required>
                        <option value="">-- Select category --</option>
                        <option value="academic" <?= old('category')=='academic' ? 'selected' : '' ?>>Academic</option>
                        <option value="senior_non_academic" <?= old('category')=='senior_non_academic' ? 'selected' : '' ?>>Senior Non-Academic</option>
                        <option value="junior_non_academic" <?= old('category')=='junior_non_academic' ? 'selected' : '' ?>>Junior Non-Academic</option>
                      </select>
                    </div>

                        <div class="row">
                            <div class="mb-3 col-md-6">
                              <label for="period_from" class="form-label">Reporting Period - From (Year)</label>
                              <select name="period_from" id="period_from" class="form-select" required>
                                <option value="">-- Select year --</option>
                                <?php for ($y = $maxYear; $y >= $startYear; $y--): ?>
                                  <option value="<?= $y ?>" <?= ($selectedFrom == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                              </select>
                          </div>

                        <div class="mb-3 col-md-6">
                              <label for="period_to" class="form-label">Reporting Period - To (Year)</label>
                              <select name="period_to" id="period_to" class="form-select" required>
                                <option value="">-- Select year --</option>
                                <?php for ($y = $maxYear; $y >= $startYear; $y--): ?>
                                  <option value="<?= $y ?>" <?= ($selectedTo == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                              </select>
                            </div>
                          </div>                          
                          
                        <label class="col-form-label pt-0">Password</label> 
                          <div class="row g-2">
                            <div class="col-sm-6">
                              <input class="form-control" type="password" name="password" id="password" Placeholder="Password" value="<?= esc(old('password', $staff['password'] ?? '')) ?>" class="form-control" required>
                            <div class="show-hide"><span class="show"></span></div>
                              </div>
                            <div class="col-sm-6">
                              <input class="form-control" type="password" name="password_confirm" Placeholder="Password Confirm" value="<?= esc(old('password_confirm', $staff['password_confirm'] ?? '')) ?>" id="password_confirm" class="form-control" required>
                            </div>
                          </div>
                        <div class="form-group mb-0">
                          <div class="form-check">
                          </div>
                          <button id="registerBtn" class="btn btn-primary btn-block w-100" type="submit">Create Account</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
     </div>
  </div>

<script>
// small client side convenience: basic match check + spinner
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('registerForm');
  const btn = document.getElementById('registerBtn');
  const spinner = document.getElementById('regSpinner');

  form.addEventListener('submit', function(e) {
    const pwd = document.getElementById('password').value;
    const pwdc = document.getElementById('password_confirm').value;
    if (pwd !== pwdc) {
      e.preventDefault();
      alert('Passwords do not match');
      return false;
    }
    btn.setAttribute('disabled','disabled');
    spinner.classList.remove('d-none');
  });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('registrationForm') || document.querySelector('form');
  if (!form) return;

  // replace default submit if you want AJAX handling
  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    const btn = form.querySelector('button[type="submit"]');
    const originalBtnText = btn && btn.innerHTML;
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
    }

    const fd = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      const ct = (res.headers.get('content-type') || '');
      let data = null;
      if (ct.includes('application/json')) data = await res.json();
      else {
        // show raw response if not JSON (debug)
        const text = await res.text();
        showToast('danger', 'Server returned unexpected response. Check console.');
        console.error('Non-JSON server response:', text);
        if (btn) { btn.disabled = false; btn.innerHTML = originalBtnText; }
        return;
      }

      if (!res.ok) {
        // validation or server error
        if (data.errors) {
          const list = Object.values(data.errors).map(v => `<li>${v}</li>`).join('');
          showToast('danger', `<ul class="mb-0">${list}</ul>`);
        } else {
          showToast('danger', data.message || 'Server error');
        }
        if (btn) { btn.disabled = false; btn.innerHTML = originalBtnText; }
        return;
      }

      // success
      const delay = parseInt(data.redirectDelay ?? 1200, 10); // ms
      showToast('success', data.message || 'Registration successful. Redirecting...');

      // small delay for UX then redirect
      setTimeout(() => {
        window.location.href = data.redirect || '<?= site_url('profile/first-stage') ?>';
      }, delay);

    } catch (err) {
      console.error(err);
      showToast('danger', 'Network error. Try again.');
      if (btn) { btn.disabled = false; btn.innerHTML = originalBtnText; }
    }
  });

  // small helper for bootstrap toasts (injects into DOM)
  function showToast(type, html) {
    // remove existing toast container if present
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      container.className = 'position-fixed bottom-0 end-0 p-3';
      container.style.zIndex = 1060;
      document.body.appendChild(container);
    }
    const id = 'toast-' + Date.now();
    const toastHtml = `
      <div id="${id}" class="toast align-items-center text-bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">${html}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>`;
    container.insertAdjacentHTML('beforeend', toastHtml);
    // auto-remove after 4s
    setTimeout(() => {
      const t = document.getElementById(id);
      if (t) t.remove();
      // remove container if no toasts
      if (container && container.children.length === 0) container.remove();
    }, 4000);
  }
});
</script>

<?= $this->endSection() ?>


