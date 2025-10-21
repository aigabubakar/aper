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
								<a class="logo-white header-logo" href="index.html">
									<img src="assets/img/logo.svg" class="logo" alt="Logo">
								</a>
								<a class="logo-dark header-logo" href="index.html">
									<img src="assets/img/logo-white.svg" class="logo" alt="Logo">
								</a>
							</div>
						</div>
						<div class="main-menu-wrapper">								
							<div class="menu-header">
								<a href="index.html" class="menu-logo">
									<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
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
										<img src="assets/img/user/user-02.jpg" alt="Img" class="img-fluid rounded-circle">
									</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
									<div class="profile-header d-flex align-items-center">
										<div class="avatar">
											<img src="assets/img/user/user-02.jpg" alt="Img" class="img-fluid rounded-circle">
										</div>
										<div>
											<h6>Ronald Richard</h6>
											<p>studentdemo@example.com</p>
										</div>
									</div>
									<ul class="profile-body">
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="student-profile.html"><i class="isax isax-security-user me-2"></i>My Profile</a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="student-quiz.html"><i class="isax isax-award me-2"></i>Quiz Attempts</a>
										</li>
									
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="student-messages.html"><i class="isax isax-messages-3 me-2"></i>Messages<span class="message-count">2</span></a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="student-settings.html"><i class="isax isax-setting-2 me-2"></i>Settings</a>
										</li>										
									</ul>
									<div class="profile-footer">
										<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="login.html"><i class="isax isax-arrow-2 me-2"></i>Log in as Instructor</a>
										<a href="index.html" class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100"><i class="isax isax-logout me-2"></i>Logout</a>
									</div>
                 </div>
               </div>
						</div>
					</div>
				</div>
			</header>
			<!-- /Header -->
		   
			<!-- Breadcrumb -->
			<div class="breadcrumb-bar text-center">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-12">
						
						</div>
					</div>
				</div>
			</div>
			<!-- /Breadcrumb -->

			<div class="content">
				<div class="container">
			