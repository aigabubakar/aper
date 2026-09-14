<?php
// header for non-authenticated visitors
?>
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
							
						</div>
						<div class="main-menu-wrapper">								
							<div class="menu-header">
								<a href="index.html" class="menu-logo">
									<img src="assets/img/logo.jpg" class="img-fluid" alt="Logo">
								</a>
								<a id="menu_close" class="menu-close" href="javascript:void(0);">
									<i class="fas fa-times"></i>
								</a>
							</div>		
						</div>
						<div class="header-btn d-flex align-items-center">
							<?php if (session()->get('isAdminLoggedIn')): ?>
								<div class="icon-btn me-3">
									<a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle activate">
										<i class="isax isax-sun-15"></i>
									</a>
									<a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle">
										<i class="isax isax-moon"></i>
									</a>
								</div>
								<span class="me-3 text-dark d-none d-lg-inline fw-medium">
									Hello, <?= esc(session()->get('admin_name') ?? 'Admin') ?>
								</span>
								<a href="<?= site_url('admin/logout') ?>" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center">
									<i class="isax isax-logout me-1"></i> Logout
								</a>
							<?php else: ?>
								<a href="<?= site_url('admin/login') ?>" class="btn btn-light d-inline-flex align-items-center me-2">
									<i class="isax isax-lock-circle me-2"></i>Admin Sign In
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</header>
			<!-- /Header -->	