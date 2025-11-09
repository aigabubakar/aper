<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
	<div class="row justify-content-center"> 
		<div class="instructor-profile"> 
			<div class="row align-items-center row-gap-3"> 
				<div class="col-md-6"> 
					<div class="d-flex align-items-center"> 
						<div> <h5 class="mb-1 text-white d-inline-flex align-items-center pull-center">ADMINISTARTOR LOGIN </h5> 
					    </div> 
					</div> 
				</div> 
			</div> 
		</div> 
	   <div class="col-md-4">
      <div class="card mt-5">
        <div class="card-body">
          <h5 class="card-title mb-3">Admin Sign In</h5>

          <!-- flash messages -->
          <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
              <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                <div><?= esc($err) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
          <?php endif; ?>

          <form id="adminLoginForm" action="<?= site_url('admin/login') ?>" method="post" autocomplete="off" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control" required autofocus value="<?= esc(old('email')) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>

            <button id="loginBtn" type="submit" class="btn btn-primary w-100">
              <span id="loginBtnText">Login</span>
              <span id="loginSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer mt-4">
    <div class="footer-bg">
      <img src="<?= base_url('assets/img/bg/footer-bg-01.png') ?>" class="footer-bg-1" alt="">
      <img src="<?= base_url('assets/img/bg/footer-bg-02.png') ?>" class="footer-bg-2" alt="">
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row row-gap-2">
          <div class="col-md-6">
            <div class="text-center text-md-start">
              <p class="text-white">Copyright &copy; <?= date('Y') ?> APER System. All rights reserved.</p>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>
      </div>
    </div>
  </footer>
</div>
<!-- /Footer -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('adminLoginForm');
  const btn = document.getElementById('loginBtn');
  const btnText = document.getElementById('loginBtnText');
  const spinner = document.getElementById('loginSpinner');

  form.addEventListener('submit', function(e){
    // Simple client-side prevention of double submits
    if (btn.getAttribute('data-loading') === '1') {
      e.preventDefault();
      return;
    }
    btn.setAttribute('data-loading','1');
    btn.setAttribute('disabled','disabled');
    btnText.textContent = 'Signing in...';
    spinner.classList.remove('d-none');
  });
});
</script>
<?= $this->endSection() ?>
