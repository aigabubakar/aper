


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
										<img src="assets/img/user/user-01.jpg" alt="Img" class="img-fluid rounded-circle">
									</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
									<div class="profile-header d-flex align-items-center">
										<div class="avatar">
											<img src="assets/img/user/user-01.jpg" alt="Img" class="img-fluid rounded-circle">
										</div>
										<div>
											<h6>Eugene Andre</h6>
											<p>instructerdemo@example.com</p>
										</div>
									</div>
									<ul class="profile-body">
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-profile.html"><i class="isax isax-security-user me-2"></i>My Profile</a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-course.html"><i class="isax isax-teacher me-2"></i>Courses</a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium2" href="instructor-earnings.html"><i class="isax isax-dollar-circle me-2"></i>Earnings</a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-payout.html"><i class="isax isax-coin me-2"></i>Payouts</a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-message.html"><i class="isax isax-messages-3 me-2"></i>Messages<span class="message-count">2</span></a>
										</li>
										<li>
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-settings.html"><i class="isax isax-setting-2 me-2"></i>Settings</a>
										</li>										
									</ul>
									<div class="profile-footer">
										<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="login.html"><i class="isax isax-arrow-2 me-2"></i>Log in as Student</a>
										<a href="index.html" class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100"><i class="isax isax-logout me-2"></i>Logout</a>
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
							<img src="assets/img/bg/card-bg-01.png" class="instructor-profile-bg-1" alt="">
						</div>
						<div class="row align-items-center row-gap-3">
								<div class="col-md-6">
									<div class="d-flex align-items-center">
										<span class="avatar flex-shrink-0 avatar-xxl avatar-rounded me-3 border border-white border-3 position-relative">
											<img src="assets/img/user/user-01.jpg" alt="img">
											<span class="verify-tick"><i class="isax isax-verify5"></i></span>
										</span>
										<div>
											<h5 class="mb-1 text-white d-inline-flex align-items-center"><a href="instructor-details.html">Eugene Andre</a><a href="instructor-profile.html" class="link-light fs-16 ms-2"><i class="isax isax-edit-2"></i></a></h5>
											<p class="text-light">Instructor</p>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="d-flex align-items-center flex-wrap gap-3 justify-content-md-end">
										<a href="" class="btn btn-white rounded-pill">Acount Type</a>
										<a href="" class="btn btn-secondary rounded-pill">Admin</a>
									</div>
								</div>
						</div>
					</div>
					<div class="row">
						<div class="page-title d-flex align-items-center justify-content-between">
								<h5 class="fw-bold"></h5>
								<div>
									<a href="" class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_announcement">
										<i class="isax isax-add-circle me-1"></i>Add New A Staff
									</a>
								</div>
							</div>
					
						<div class="col-lg-12">
                            <div class="statements">
                            <h5 class="page-title">Total Registered staff  <?= esc($totalUsers) ?></h5>
                            <div class="table-top">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="mb-3">
                                            <div class="dropdown me-3">
                                                <a href="javascript:void(0);" class="dropdown-toggle btn d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Payment Method
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Paypal</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Bank Transfer</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Stripe</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="dropdown me-3">
                                                <a href="javascript:void(0);" class="dropdown-toggle btn d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                                Status
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Completed</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Pending</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <i class="isax isax-search-normal-14"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-md" placeholder="Search">
                                    </div>
                                </div>
                            </div>
                           </div>
                           <div class="table-responsive custom-table">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Course</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD01</a></td>
                                        <td><a href="course-details.html">Information About UI/UX Design<br> Degree</a></td>
                                        <td>22 Aug 2025</td>
                                        <td>$160</td>
                                        <td>Paypal</td>
                                        <td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Completed</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD009</a></td>
                                        <td><a href="course-details.html">Build Responsive Real World Websites<br> with Crash Course</a></td>
                                        <td>10 Aug 2025</td>
                                        <td>$180</td>
                                        <td>Bank Transfer</td>
                                        <td><span class="badge badge-sm bg-info d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Pending</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD008</a></td>
                                        <td><a href="course-details.html">C# Developers Double Your Coding<br> with Visual Studio</a></td>
                                        <td>26 Jul 2025</td>
                                        <td>$200</td>
                                        <td>Stripe</td>
                                        <td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Completed</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD007</a></td>
                                        <td><a href="course-details.html">Wordpress for Beginners - Master<br> Wordpress Quickly</a></td>
                                        <td>12 Jul 2025</td>
                                        <td>$220</td>
                                        <td>Paypal</td>
                                        <td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Completed</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD006</a></td>
                                        <td><a href="course-details.html">Introduction to Python Programming</a></td>
                                        <td>02 Jul 2025</td>
                                        <td>$170</td>
                                        <td>Stripe</td>
                                        <td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Completed</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="order"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_invoice">#ORD005</a></td>
                                        <td><a href="course-details.html">Learn JavaScript and Express to<br> become a Expert</a></td>
                                        <td>25 Jun 2025</td>
                                        <td>$150</td>
                                        <td>Bank Transfer</td>
                                        <td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Completed</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon" data-bs-toggle="modal" data-bs-target="#view_invoice"><i class="isax isax-eye"></i></a>
                                                <a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon"><i class="isax isax-import"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
				<img src="assets/img/bg/footer-bg-01.png" class="footer-bg-1" alt="">
				<img src="assets/img/bg/footer-bg-02.png" class="footer-bg-2" alt="">
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

