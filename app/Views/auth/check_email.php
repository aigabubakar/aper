<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Verify Email<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- login page start-->
     <div class="page-content">
		<div class="form-v4-content">
    <div class="container-fluid">
      <div class="row">
        <div class="text-center">
            <h1>Welcome to APER  <?= date('Y') ?></h1>
            <p class="lead">Edo State University Annual Performance Evaluation & Review System</p>
        </div>
        <div class="col-xl-2">
            <img class="bg-img-cover bg-center" src=" <?= base_url('assets/images/loginbg_img3.png') ?>" alt="looginpage"></div>
        <div class="col-xl-7 p-0">    
          <div class="login-card login-dark">
            <div>
              <div class="login-main"> 
               
              <div class="card-body">
        <h5 class="card-title">Staff Email Verification</h5>
        <p class="text-muted">Enter your institution email to begin registration.</p>

        <!-- Feedback placeholders -->
        <div id="alert-placeholder"></div>

        <form id="checkEmailForm" action="<?= base_url('check-email') ?>" method="post" novalidate>
          <?= csrf_field() ?>

          <div class="mb-3">
            <label for="email" class="form-label">Institution Email</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control"
                   required
                   value="<?= esc(old('email')) ?>">
          </div>

          <button id="verifyBtn" class="btn btn-primary" type="submit">
            <span id="btn-text">Verify Email</span>
            <span id="btn-spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
          </button>
        </form>
      </div>
              </div>
              <div class="col-xl-2">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
  </div>

<div class="row justify-content-center">
 

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
