<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-md-3">
    <div class="card p-3">
      <h5>Total Users</h5>
      <h2><?= esc($totalUsers) ?></h2>
    </div>
  </div>

  <div class="col-md-9">
    <div class="card">
      <div class="card-body">
        <h5>Recent Users</h5>
        <ul class="list-group">
          <?php foreach ($recentUsers as $u): ?>
            <li class="list-group-item">
              <?= esc($u['fullname']) ?> — <?= esc($u['email']) ?>
              <a class="btn btn-sm btn-outline-secondary float-end" href="<?= site_url('admin/users/show/'.$u['id']) ?>">View</a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>




<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Junior — Employment<?= $this->endSection() ?>
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
								<a href="" class="menu-logo">
									<img src="assets/img/logo.jpg" class="img-fluid" alt="Logo">
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
											<a class="dropdown-item d-inline-flex align-items-center rounded fw-medium" href="instructor-settings.html"><i class="isax isax-setting-2 me-2"></i>Settings</a>
										</li>										
									</ul>
									<div class="profile-footer">
										<a href="" class="btn btn-secondary d-inline-flex align-items-center justify-content-center w-100"><i class="isax isax-logout me-2"></i>Logout</a>
									</div>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</header>
			<!-- /Header -->

<div class="row">
  <!-- Sidebar (the sidebar partial already contains the column wrapper: col-lg-3) -->
  <?= view('layouts/sidebar') ?>

  <!-- Main column -->
  <div class="col-lg-9">
    <div class="page-title d-flex align-items-center justify-content-between mb-3">
      <!-- you can place breadcrumbs / page actions here -->
    </div>
			

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
										<h5 class="mb-1 text-white d-inline-flex align-items-center">Eugene Andre<a href="instructor-profile.html" class="link-light fs-16 ms-2"><i class="isax isax-edit-2"></i></a></h5>
										<p class="text-light">Instructor</p>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="d-flex align-items-center flex-wrap gap-3 justify-content-md-end">
									<a href="add-course.html" class="btn btn-white rounded-pill">Add New Course</a>
									<a href="student-dashboard.html" class="btn btn-secondary rounded-pill">Student Dashboard</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
												
						<div class="col-lg-12">
							<div class="page-title d-flex align-items-center justify-content-between">
								<h5 class="fw-bold">Announcements</h5>
								<div>
									<a href="javascript:void(0);" class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_announcement">
										<i class="isax isax-add-circle me-1"></i>Add Announcement
									</a>
								</div>
							</div>
							<div class="row">
								<div class="col-md-8">
									<div class="mb-3">
										<div class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle btn rounded border d-inline-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
												Status
											</a>
											<ul class="dropdown-menu dropdown-menu-end p-3">
												<li>
													<a href="javascript:void(0);" class="dropdown-item rounded-1">Published</a>
												</li>
												<li>
													<a href="javascript:void(0);" class="dropdown-item rounded-1">Draft</a>
												</li>
											</ul>
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
							<div class="table-responsive custom-table">
								<table class="table">
									<thead class="thead-light">
										<tr>
											<th>Date</th>
											<th>Announcements</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>22 Aug 2025, 05:40 PM </td>
											<td>
												<div>
													<h6 class="mb-1"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_announcement">Welcome to Introduction to Programming</a></h6>
													<p>Course: Introduction to Programming - Python & Java</p>
												</div>
											</td>
											<td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Published</span></td>
											<td>
												<div class="d-flex align-items-center">
													<a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon"><i class="isax isax-edit-2" data-bs-toggle="modal" data-bs-target="#edit_announcement"></i></a>
													<a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="isax isax-trash"></i></a>
												</div>
											</td>
										</tr>
										<tr>
											<td>10 Aug 2025, 10:15 AM</td>
											<td>
												<div>
													<h6 class="mb-1"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_announcement">Essay Assignment Due Date Approaching</a></h6>
													<p>Course: Sketch from A to Z (2024): Become an app designer</p>
												</div>
											</td>
											<td><span class="badge badge-sm bg-skyblue d-inline-flex align-items-center me-1"><i class="fa-solid fa-circle fs-5 me-1"></i>Draft</span></td>
											<td>
												<div class="d-flex align-items-center">
													<a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon"><i class="isax isax-edit-2" data-bs-toggle="modal" data-bs-target="#edit_announcement"></i></a>
													<a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="isax isax-trash"></i></a>
												</div>
											</td>
										</tr>
										<tr>
											<td>26 Jul 2025, 01:30 PM</td>
											<td>
												<div>
													<h6 class="mb-1"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#view_announcement">Final Exam Schedule and Preparation Tips</a></h6>
													<p>Course: Learn Angular Fundamentals Beginners Guide</p>
												</div>
											</td>
											<td><span class="badge badge-sm bg-success d-inline-flex align-items-center"><i class="fa-solid fa-circle fs-5 me-1"></i>Published</span></td>
											<td>
												<div class="d-flex align-items-center">
													<a href="javascript:void(0);" class="d-inline-flex fs-14 me-1 action-icon"><i class="isax isax-edit-2" data-bs-toggle="modal" data-bs-target="#edit_announcement"></i></a>
													<a href="javascript:void(0);" class="d-inline-flex fs-14 action-icon" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="isax isax-trash"></i></a>
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
								<p class="text-white">Copyright &copy; 2025 DreamsLMS. All rights reserved.</p>
							</div>
						</div>
						<div class="col-md-6">
							<div>
								<ul class="d-flex align-items-center justify-content-center justify-content-md-end footer-link">
									<li><a href="terms-and-conditions.html">Terms & Conditions</a></li>
									<li><a href="privacy-policy.html">Privacy Policy</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- /Footer -->

			<!-- Add Announcement -->
			<div class="modal fade" id="add_announcement">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="fw-bold">Add New Announcement</h5>
							<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
								<i class="isax isax-close-circle5"></i>
							</button>
						</div>
						<form action="instructor-announcements.html">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Course <span class="text-danger"> *</span></label>
											<select class="select">
												<option>Select</option>
												<option>Information About UI/UX Design Degree</option>
												<option>Wordpress for Beginners - Master Wordpress Quickly</option>
												<option>Introduction to Python Programming</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Announcement Title <span class="text-danger"> *</span></label>
											<input type="text" class="form-control">
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Description</label>
											<textarea class="form-control" placeholder="Enter Description"></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-0">
											<label class="form-label">Status <span class="text-danger"> *</span></label>
											<select class="select">
												<option>Select</option>
												<option>Published</option>
												<option>Draft</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn bg-gray-100 rounded-pill me-2" type="button" data-bs-dismiss="modal">Cancel</button>
								<button class="btn btn-secondary rounded-pill" type="submit">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /Add Announcement -->

			<!-- Edit Announcement -->
			<div class="modal fade" id="edit_announcement">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="fw-bold">Edit Announcement</h5>
							<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
								<i class="isax isax-close-circle5"></i>
							</button>
						</div>
						<form action="instructor-announcements.html">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Course <span class="text-danger"> *</span></label>
											<select class="select">
												<option>Select</option>
												<option selected>Information About UI/UX Design Degree</option>
												<option>Wordpress for Beginners - Master Wordpress Quickly</option>
												<option>Introduction to Python Programming</option>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Announcement Title <span class="text-danger"> *</span></label>
											<input type="text" class="form-control" value="Welcome to Introduction to Programming">
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-3">
											<label class="form-label">Description</label>
											<textarea class="form-control">Enter Description</textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="mb-0">
											<label class="form-label">Status <span class="text-danger"> *</span></label>
											<select class="select">
												<option>Select</option>
												<option selected>Published</option>
												<option>Draft</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn bg-gray-100 rounded-pill me-2" type="button" data-bs-dismiss="modal">Cancel</button>
								<button class="btn btn-secondary rounded-pill" type="submit">Save Changes</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /Edit Announcement -->

			<!-- Announcement Details -->
			<div class="modal fade" id="view_announcement">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="fw-bold">Announcement Details</h5>
							<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
								<i class="isax isax-close-circle5"></i>
							</button>
						</div>
						<div class="modal-body">
							<div class="mb-3">
								<h6 class="mb-1">Course</h6>
								<p>Introduction to Programming - Python & Java</p>
							</div>
							<div class="mb-3">
								<h6 class="mb-1">Title</h6>
								<p>Guest Lecture Announcement</p>
							</div>
							<div class="mb-3">
								<h6 class="mb-1">Description</h6>
								<p>I am excited to inform you that we will be having a guest lecture from , an expert . 
									This will be an excellent opportunity to gain insight into and ask any questions you might have. 
									Please make every effort to attend, as participation will count towards.
								</p>
							</div>
							<div class="mb-0">
								<h6 class="mb-1">Added On</h6>
								<p>26 Jul 2025, 01:30 PM</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Announcement Details -->

			<!-- Delete Modal -->
			<div class="modal fade" id="delete_modal">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-body text-center custom-modal-body">
							<span class="avatar avatar-lg bg-danger-transparent rounded-circle mb-2">
								<i class="isax isax-trash fs-24 text-danger"></i>
							</span>
							<div>
								<h4 class="mb-2">Delete Announcements</h4>
								<p class="mb-3">Are you sure you want to delete announcements?</p>
								<div class="d-flex align-items-center justify-content-center">
									<a href="javascript:void(0);" class="btn bg-gray-100 rounded-pill me-2" data-bs-dismiss="modal">Cancel</a>
									<a href="javascript:void(0);" class="btn btn-secondary rounded-pill">Yes, Delete</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /Delete Modal -->





    

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>

