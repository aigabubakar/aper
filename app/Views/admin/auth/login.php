<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Login<?= $this->endSection() ?>
<?= $this->section('content') ?>
	<!-- Main Wrapper -->
		<div class="main-wrapper">

			<div class="content">
				<div class="container">
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
					<div class="row">
						<!-- Sidebar -->
						<div class="col-lg-2 theiaStickySidebar">
						
						</div>
						<!-- /Sidebar -->
						
						<div class="col-lg-8">
							<div class="page-title d-flex align-items-center justify-content-between">
							<div class="col-md-6">
								<div class="card">
								<div class="card-body">
									<h5 class="card-title">Admin Panel Sign In</h5>
									<?= view('partials/flash') ?>
									<form method="post" action="<?= site_url('admin/login') ?>">
									<?= csrf_field() ?>
									<div class="mb-3">
										<label>Email</label>
										<input name="email" type="email" class="form-control" value="<?= esc(old('email')) ?>" required>
									</div>
									<div class="mb-3">
										<label>Password</label>
										<input name="password" type="password" class="form-control" required>
									</div>
									<div class="d-flex justify-content-between align-items-center">           
										<a href="<?= site_url('/') ?>">Back to site</a>
										<button class="btn btn-primary">Sign In</button>
									</div>
									</form>
								</div>
								</div>
							</div>
							</div>						
						</div>
						<!-- Sidebar -->
						<div class="col-lg-2 theiaStickySidebar">
						
						</div>
						<!-- /Sidebar -->
					</div>
				</div>
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
		</footer>
		<!-- /Footer -->
<?= $this->endSection() ?>



