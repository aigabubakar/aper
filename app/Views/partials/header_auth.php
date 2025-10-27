<?php
$session = session();
$name = esc($session->get('fullname') ?? $session->get('email'));
$role = esc($session->get('role') ?? '');
?>

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

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
								<a class="logo-white header-logo" href="">
								</a>
								<a class="logo-dark header-logo" href="">
									<img src="<?= base_url('assets/img/logo.jpg') ?>" alt="Logo" class="img-fluid">
								</a>
							</div>
						</div>
						<div class="main-menu-wrapper">								
							<div class="menu-header">
								<a href="" class="menu-logo">
									<img src="<?= base_url('assets/img/logo.jpg') ?>" alt="Logo" class="img-fluid">
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
											<img src="<?= base_url('assets/img/user/user-02.jpg') ?>" alt="Img" class="img-fluid rounded-circle">
									</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
									<div class="profile-header d-flex align-items-center">
										<div class="avatar">
											<img src="<?= base_url('assets/img/user/user-02.jpg') ?>" alt="Img" class="img-fluid rounded-circle">
										</div>
										<div>
											<h6><?= esc($session->get('fullname')) ?></h6>
											<p><?= esc($session->get('email')) ?></p>
										</div>
									</div>
								
									<div class="profile-footer">
										<a href="<?= site_url('logout') ?>" class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100"><i class="isax isax-logout me-2"></i>Logout</a>
									</div>
							</div>
						</div>
					  </div>
					</div>
				</div>
			</header>
			<!-- /Header -->
		
	<!-- Main Wrapper -->
		<div class="main-wrapper">

			<div class="content">
				<div class="container">
					<div class="instructor-profile">
						<div class="row align-items-center row-gap-3">
								<div class="col-md-6">
									<div class="d-flex align-items-center">
										<span class="avatar flex-shrink-0 avatar-xxl avatar-rounded me-3 border border-white border-3 position-relative">
											<img src="assets/img/user/user-01.jpg" alt="img">
											<span class="verify-tick"><i class="isax isax-verify5"></i></span>
										</span>
										<div>
											<h5 class="mb-1 text-white d-inline-flex align-items-center"> <?= esc($user['fullname']) ?><span></h5>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-center flex-wrap gap-3 justify-content-md-end">
										<a href="" class="btn btn-white rounded-pill">Acount Type</a>
										<a href="" class="btn btn-secondary rounded-pill">User</a>
									</div>
								</div>
						</div>
					</div>


				
			