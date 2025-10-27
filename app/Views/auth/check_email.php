<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Verify Email<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- login page start-->



    <!-- Main Wrapper -->
		<div class="main-wrapper">
            <div class="login-content">
                <div class="row">
                     <!-- Login Banner -->
                    <div class="col-md-6 login-bg d-none d-lg-flex">
                        <div class="login-carousel">
                            <div>
                                <div class="login-carousel-section mb-3">
                                    <div class="login-banner">
                                        <img src="assets/img/auth/auth-1.svg" class="img-fluid" alt="Logo">
                                    </div>
                                    <div class="mentor-course text-center">
                                        <h3 class="mb-2">Welcome to <br><?= date('Y') ?><span class="text-secondary">Edo State </span> University Iyahmo.</h3>
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
                                    <div class="d-flex align-items-center justify-content-between login-header">
                                          <img class="bg-img-cover bg-center" class="img-fluid" src="assets/img/logo.jpg" class="img-fluid" alt="Logo">
                                    </div>
                                    <h1 class="fs-32 fw-bold topic">Verify Your Email Account</h1>
                                    <div id="alert-placeholder"></div>
                                    
                                    <form id="checkEmailForm" action="<?= base_url('check-email') ?>" class="mb-3 pb-3">
                                             <?= csrf_field() ?>
                                        <div class="mb-3 position-relative">
                                          <label for="email" class="text-danger ms-1">Institution Email</label>
                                          <input type="email"
                                                name="email"
                                                id="email"
                                                class="form-control form-control-lg
                                                required
                                                value="<?= esc(old('email')) ?>">
                                              <span><i class="isax isax-sms input-icon text-gray-7 fs-14"></i></span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-4">
                                        </div>
                                        
                                        <div class="d-grid">
                                        <button id="verifyBtn" class="btn btn-secondary btn-lg btn-primary" type="submit"><i class="isax isax-arrow-right-3 ms-1"></i>
                                        <span id="btn-text">Verify Email</span>
                                        <span id="btn-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                       </button>
                                        </div>
                                    </form>
                                        
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
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('checkEmailForm');
  const btn = document.getElementById('verifyBtn');
  const btnText = document.getElementById('btn-text');
  const btnSpinner = document.getElementById('btn-spinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function showAlert(type, html) {
    alertPlaceholder.innerHTML = `
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${html}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
    alertPlaceholder.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function setLoading(on) {
    if (on) {
      btn.setAttribute('disabled', 'disabled');
      btnText.textContent = 'Checking...';
      btnSpinner.classList.remove('d-none');
    } else {
      btn.removeAttribute('disabled');
      btnText.textContent = 'Verify Email';
      btnSpinner.classList.add('d-none');
    }
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    alertPlaceholder.innerHTML = '';
    setLoading(true);

    const fd = new FormData(form);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin', // ensures cookies (CSRF) are sent
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const contentType = res.headers.get('content-type') || '';
      // if server returned JSON
      if (contentType.includes('application/json')) {
        const data = await res.json();

        // handle non-OK (4xx/5xx)
        if (!res.ok) {
          if (data.errors) {
            const list = Object.values(data.errors).map(v => `<li>${v}</li>`).join('');
            showAlert('danger', `<ul class="mb-0">${list}</ul>`);
          } else if (data.message) {
            showAlert('danger', data.message);
          } else {
            showAlert('danger', 'Server returned an error. Please try again.');
          }
          setLoading(false);
          return;
        }

        // If user already registered -> redirect to login
        if (data.already_registered) {
          showAlert('info', `<strong>Notice:</strong> ${data.message} Redirecting to login...`);
          setTimeout(() => {
            window.location.href = data.redirect || '<?= site_url('login') ?>';
          }, 2000);
          return;
        }

        // Successful verification -> redirect to register
        if (data.success) {
          showAlert('success', `<strong>Success:</strong> ${data.message}`);
          setTimeout(() => {
            window.location.href = data.redirect || '<?= site_url('register') ?>';
          }, 2000);
          return;
        }

        // Unexpected JSON shape
        showAlert('danger', 'Unexpected response from server. Please try again or contact ICT.');
        setLoading(false);
        return;
      }

      // Non-JSON response (debug-friendly): show raw server response (trimmed)
      const text = await res.text();
      const preview = text.length > 1000 ? text.substring(0, 1000) + '...' : text;
      showAlert('danger', `<strong>Server response (non-JSON):</strong><pre style="white-space:pre-wrap">${preview}</pre>`);
      setLoading(false);

    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Check your connection and try again.');
    } finally {
      setLoading(false);
    }
  });
});
</script>
<?= $this->endSection() ?>
