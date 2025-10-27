<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Complete Registration<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Main Wrapper -->
		<div class="main-wrapper">
            <div class="login-content">
                <div class="row">
                                        <div class="col-md-6 login-bg d-none d-lg-flex">
                        <div class="login-carousel">
                            <div>
                                <div class="login-carousel-section mb-3">
                                    <div class="login-banner">
                                        <img src="assets/img/auth/auth-1.svg" class="img-fluid" alt="Logo">
                                    </div>
                                    <div class="mentor-course text-center">
                                        <h3 class="mb-2">Welcome to <br><?= date('Y')?>  <span class="text-secondary">Edo State </span> University Iyahmo.</h3>
                                         <p>Edo State University Annual Performance Evaluation & Review System.</p>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <!-- /Login Banner -->
        
                    <div class="col-md-6 login-wrap-bg">
                        <!-- Login -->
                        <div class="login-wrapper">
                            <div class="loginbox">
                                <div class="w-100">
                                  <div class="row">
                                      <div class="d-flex align-items-center justify-content-between login-header">
                                       <img class="bg-img-cover text-center-container"  src="assets/img/logo.jpg" class="img-fluid" alt="Logo">
                                    </div>
                                  </div>
                                    
                                    <h1 class="fs-32 fw-bold topic">Sign up</h1>
                                   <?= view('partials/flash') // optional partial to render flash messages ?>
                                   <form class="mb-3 pb-3" id="registerForm" action="<?= base_url('save-registration') ?>" method="post" novalidate>
                                      <?= csrf_field() ?>
                                      <?php
                                          $startYear = 2016;                   // adjust to your earliest allowed year
                                          $currentYear = (int) date('Y');
                                          //$maxYear = $currentYear + 1;         // allow reporting up to next year if needed
                                          $maxYear = $currentYear ;    
                                          $selectedFrom = old('period_from') ?? ($staff['period_from'] ?? '');
                                          $selectedTo   = old('period_to')   ?? ($staff['period_to']   ?? '');
                                      ?>

                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Full Name<span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative">
                                                <input class="form-control form-control-lg" type="text" required="" name="fullname" value="<?= esc(old('fullname', $staff['fullname'] ?? '')) ?>">
                                                <span><i class="isax isax-user input-icon text-gray-7 fs-14"></i></span>
                                            </div>
                                        </div>

                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Staff Id Number<span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative">
                                                <input class="form-control form-control-lg" type="text" name="staff_id" value="<?= esc(old('staff_number', $staff['staff_number'] ?? '')) ?>">
                                                <span><i class="isax isax-user input-icon text-gray-7 fs-14"></i></span>
                                            </div>

                                             <label class="form-label">Phone Number<span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative">
                                                <input class="form-control form-control-lg" type="text" name="phone" id="phone" class="form-control" placeholder="+2348012345678" value="<?= esc(old('phone', $staff['phone'] ?? '')) ?>">
                                                <span><i class="isax isax-phone input-icon text-gray-7 fs-14"></i></span>
                                            </div>
                                        </div>

                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative">
                                                <input class="form-control form-control-lg" type="email"name="email" value="<?= esc(old('email', $staff['email'] ?? '')) ?>" readonly>
                                                <span><i class="isax isax-sms input-icon text-gray-7 fs-14"></i></span>
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

                                        <div class="mb-3 position-relative">
                                            <label class="form-label">New Password <span class="text-danger"> *</span></label>
                                            <div class="position-relative" id="passwordInput">
                                                <input type="password" name="password" id="password" class="pass-inputs form-control form-control-lg">
                                                <span class="isax toggle-passwords isax-eye-slash text-gray-7 fs-14"></span>
                                            </div>
                                            <div class="password-strength" id="passwordStrength">
                                                <span id="poor"></span>
                                                <span id="weak"></span>
                                                <span id="strong"></span>
                                                <span id="heavy"></span>
                                            </div>
                                            <div class="mt-2" id="passwordInfo"></div>	
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Confirm Password <span class="text-danger"> *</span></label>
                                            <div class="position-relative">
                                                <input type="password" name="password_confirm" class="pass-inputa form-control form-control-lg">
                                                <span class="isax toggle-passworda isax-eye-slash text-gray-7 fs-14"></span>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                           <button id="registerBtn" class="btn btn-lg btn-primary btn-block w-100" type="submit">Create Account</button>
                                        </div>
                                    </form>


                                    <div class="fs-14 fw-normal d-flex align-items-center justify-content-center">
                                        Already you have an account?<a href="/login" class="link-2 ms-1"> Login</a>
                                    </div>
    
                                    <!-- /Login -->
        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	   <!-- /Main Wrapper -->

  

     


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

