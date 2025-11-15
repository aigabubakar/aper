

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
			<!-- Header -->
			<header class="header-two">
				<div class="container">
					<div class="header-nav">
						<div class="navbar-header">
							<a id="mobile_btn" href="javascript:void(0);">
								<span class="bar-icon">
									<span></span>
									<span></span>
									<span></span>
								</span>
							</a>
							<div class="navbar-logo">
								<a class="logo-white header-logo" href="<?= site_url('/admin') ?>">
									<img src="<?= base_url('assets/img/logo.jpg') ?>" class="logo" alt="Logo">
								</a>
								<a class="logo-dark header-logo" href="<?= site_url('/admin') ?>">
									<img src="<?= base_url('assets/img/logo.jpg') ?>" class="logo" alt="Logo">
								</a>
							</div>
						</div>
						<div class="main-menu-wrapper">								
							<div class="menu-header">
								<a href="<?= site_url('/admin') ?>" class="menu-logo">
									<img src="<?= base_url('assets/img/logo.svg') ?>" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>
							
						</div>
						<div class="header-btn d-flex align-items-center">
							<div class="icon-btn me-2">
								<a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle activate">
									<i class="isax isax-sun-15"></i>
								</a>
								<a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle">
									<i class="isax isax-moon"></i>
								</a>
							</div>
							
							<div class="dropdown profile-dropdown">
                      <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown">
                        <span class="avatar">
									      	<img src="<?= base_url('assets/img/user/user-01.jpg') ?>" alt="Img" class="img-fluid rounded-circle">
								      	</span>
                     </a>
                                <div class="dropdown-menu dropdown-menu-end">
									<div class="profile-header d-flex align-items-center">
										<div class="avatar">
											<img src="<?= base_url('assets/img/user/user-01.jpg') ?>" alt="Img" class="img-fluid rounded-circle">
										</div>
										<div>
											<h6><?= esc(session()->get('fullname') ?? 'Admin') ?></h6>
											<p><?= esc(session()->get('email') ?? '') ?></p>
										</div>
									</div>
									
									<div class="profile-footer">
										
										<a href="<?= site_url('admin/logout') ?>" class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100"><i class="isax isax-logout me-2"></i>Logout</a>
									</div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</header>
			<!-- /Header -->
                 
			<div class="content">
				<div class="container">
					<div class="instructor-profile">
						<div class="instructor-profile-bg">
							<img src="<?= base_url('assets/img/bg/card-bg-01.png') ?>" class="instructor-profile-bg-1" alt="">
						</div>
						<div class="row align-items-center row-gap-3">
								<div class="col-md-6">
									<div class="d-flex align-items-center">
										<span class="avatar flex-shrink-0 avatar-xxl avatar-rounded me-3 border border-white border-3 position-relative">
											<img src="<?= base_url('assets/img/user/user-01.jpg') ?>" alt="img">
											<span class="verify-tick"><i class="isax isax-verify5"></i></span>
										</span>
										<div>
											<h5 class="mb-1 text-white d-inline-flex align-items-center"><?= esc(session()->get('fullname') ?? 'Administrator') ?><a href="<?= site_url('admin/profile') ?>" class="link-light fs-16 ms-2"></a></h5>
											<p class="text-light"><?= esc(session()->get('role') ?? 'admin') ?></p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-center flex-wrap gap-3 justify-content-md-end">
										<a href="#" class="btn btn-white rounded-pill">Account Type</a>
										<a href="#" class="btn btn-secondary rounded-pill"><?= esc(session()->get('role') ?? 'Admin') ?></a>
									</div>
								</div>
						</div>
					</div>

					<div class="row">
						<div class="page-title d-flex align-items-center justify-content-between mb-3">
								<h4>Add a Staff</h4>
								
							</div>

							<div class="row mb-3">
								<div class="col-md-8"></div>
								<div class="card">
                  <div class="col-md-3"></div>
                  <div class="card-body">
                    <h4 class="card-title">Add New Staff</h4>

            <!-- Errors -->
            <?php if (session()->getFlashdata('errors')): ?>
              <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $err) echo "<div>$err</div>"; ?>
              </div>
            <?php endif; ?>

            <!-- Success (displayed as hidden input for JS too) -->
            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('admin/staff/store') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Fullname</label>
                  <input name="fullname" class="form-control" value="<?= old('fullname') ?>" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Email</label>
                  <input name="email" type="email" class="form-control" value="<?= old('email') ?>" required>
                </div>

                <div class="col-md-4">
                  <label class="form-label">Staff ID</label>
                  <!-- fixed: name matches model/validation: staff_number -->
                  <input name="staff_number" class="form-control" value="<?= old('staff_number') ?>" required>
                </div>

                <div class="col-12 d-flex justify-content-end mt-3">
                  <a href="<?= site_url('admin/staff') ?>" class="btn btn-outline-secondary me-2">Back</a>
                  <button class="btn btn-primary">Save Staff</button>
                </div>
              </div>
            </form>
                  </div>
                  <div class="col-md-3"></div>
                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			</div>
			<!-- Footer -->
		<footer class="footer">
			<div class="footer-bg">
				<img src="<?= base_url('assets/img/bg/footer-bg-01.png') ?>" class="footer-bg-1" alt="">
				<img src="<?= base_url('assets/img/bg/footer-bg-02.png') ?>" class="footer-bg-2" alt="">
			</div>
			<div class="footer-bottom">
				<div class="container">
					<div class="row row-gap-2">
						<div class="col-md-4">
						</div>
						<div class="col-md-6">
							<div class="text-center text-md-start">
								<p class="text-white">Copyright &copy; <?= date('Y') ?> APER System. All rights reserved.</p>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</footer>
		<!-- /Footer -->


		</div>
		<!-- /Main Wrapper -->

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<!-- SweetAlert2 for toast -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Show SweetAlert toast when server set success flash
  <?php if ($msg = session()->getFlashdata('success')): ?>
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: <?= json_encode($msg) ?>,
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true
    });
  <?php endif; ?>
});
</script>
<?= $this->endSection() ?>

