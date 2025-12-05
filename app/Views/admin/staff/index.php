<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<!-- DataTables + Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- small tweak so DataTables fits your theme table styles -->
<style>
  .custom-table .dataTables_wrapper .dataTables_filter { display: none; } /* we use our own search input */
  .table thead th { vertical-align: middle; }
  .avatar-xxl img { width: 96px; height: 96px; object-fit: cover; }
</style>
<?= $this->endSection() ?>


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
						
						<div class="col-lg-12">
							<div class="row mb-3">
								<div class="col-xxl col-lg-4 col-md-6">
									<div class="card bg-success">
										<div class="card-body">
											<h6 class="fw-medium mb-1 text-white">Total Registered staff</h6>
											<h4 class="fw-bold text-white"><?= esc($totalStaff ?? 0) ?></h4>
										</div>
									</div>
								</div>
								<div class="col-xxl col-lg-4 col-md-6">
									<div class="card bg-secondary">
										<div class="card-body">
											<h6 class="fw-medium mb-1 text-white">Pending</h6>
											<h4 class="fw-bold text-white">0</h4>
										</div>
									</div>
								</div>

								<!-- <div class="col-xxl col-lg-4 col-md-6">
									<div class="card bg-info">
										<div class="card-body">
											<h6 class="fw-medium mb-1 text-white">						
											 Export current list (applies session-based role scope automatically) -->
											<!-- <a href="<?= site_url('admin/staff/export') ?>" class="btn btn-outline-secondary">Export CSV</a>
											</h6> -->
											<!-- Export with filters (example) -->
											<!-- <a href="<?= site_url('admin/staff/export') . '?faculty=' . $currentFacultyId . '&department=' . $currentDepartmentId ?>" class="btn btn-outline-secondary">Export filtered CSV</a> 
										</div>
									</div>
								</div> -->
							</div>

							<div class="page-title d-flex align-items-center justify-content-between mb-3">
								<h4>Registered Staff</h4>
								<div class="d-flex gap-2">
                <a href="<?= site_url('admin/') ?>" class="btn btn-primary">Dashboard</a>

									<a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary">Add Staff</a>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-md-8"></div>
								<div class="col-md-4">
									<div class="input-icon mb-3">
										<span class="input-icon-addon">
											<i class="isax isax-search-normal-14"></i>
										</span>
										<input id="tableSearch" type="search" class="form-control form-control-md" placeholder="Search staff...">
									</div>
								</div>
							</div>

							<div class="table-responsive custom-table">
								<table id="adminStaffTable" class="table table-striped table-bordered nowrap" style="width:100%">

								<thead class="thead-light">                
                  <th>#</th>
                  <th>Fullname</th>
                  <th>Email</th>
                  <!-- <th>Category</th>
                  <th>Role</th> -->
                  <th>Action</th>
    							</thead>									
									<tbody>
									<?php $i =0;?>

                    <?php foreach ($staff as $s): ?>
                      <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?= esc($s['fullname']) ?></td>
                        <td><?= esc($s['email']) ?></td>
                        <td>
                          <a href="<?= site_url('admin/staff/edit/'.$s['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                          <a href="<?= site_url('admin/staff/delete/'.$s['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">Delete</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>

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
<!-- WARNING: If your layout already loads jQuery, remove the jQuery <script> below to avoid duplicates -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables core + Responsive + Bootstrap5 adapter -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
  const table = $('#adminStaffTable').DataTable({
    responsive: true,
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    order: [[0, 'asc']],
    columnDefs: [
      { orderable: false, targets: -1 } // actions not orderable
    ],
    dom: "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
         "<'row'<'col-12'tr>>" +
         "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search staff..."
    }
  });

  // Wire custom search input to DataTables search (we hide default search)
  $('#tableSearch').on('keyup change', function () {
    table.search(this.value).draw();
  });

  // Optional: ensure responsive redraw on tab/menu toggle
  $(window).on('resize', function () {
    table.responsive.recalc();
  });
});
</script>
<?= $this->endSection() ?>






