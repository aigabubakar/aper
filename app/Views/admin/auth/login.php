<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
	<div class="instructor-profile">
						<div class="row align-items-center row-gap-3">
							<div class="col-md-6">
								<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-1 text-white d-inline-flex align-items-center pull-center">ADMINISTARTOR LOGIN </h5>
									</div>
								</div>
							</div>
							
						</div>
					</div>
  <div class="col-md-4">
    <div class="card mt-5">
      <div class="card-body">
        <h5 class="card-title mb-3">Admin Sign In</h5>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $err) echo "<div>$err</div>"; ?>
          </div>
        <?php endif; ?>

        <form action="<?= site_url('admin/login') ?>" method="post" autocomplete="off">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
		<footer class="footer">
			<div class="footer-bg">
				<img src="assets/img/bg/footer-bg-01.png" class="footer-bg-1" alt="">
				<img src="assets/img/bg/footer-bg-02.png" class="footer-bg-2" alt="">
			</div>
		
			<div class="footer-bottom">
				<div class="container">
					<div class="row row-gap-2">
						<div class="col-md-6">
							<div class="text-center text-md-start">
								<p class="text-white">Copyright &copy; <?= date('Y') ?> APER System. All rights reserved.</p>
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
		</div>
		</div>
	</div>
</footer>
	<!-- /Footer -->
<?= $this->endSection() ?>



