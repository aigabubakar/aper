
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
											<h4 class="fw-bold text-white"><?= esc($totalUsers ?? 0) ?></h4>
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
								<div class="col-xxl col-lg-4 col-md-6">
									<div class="card bg-info">
										<div class="card-body">
											<h6 class="fw-medium mb-1 text-white">						
											<!-- Export current list (applies session-based role scope automatically) -->
											<a href="<?= site_url('admin/staff/export') ?>" class="btn btn-outline-secondary">Download CSV</a>
											</h6>
										</div>
									</div>
								</div>
							</div>

							<div class="page-title d-flex align-items-center justify-content-between mb-3">
								<h4>Registered Staff</h4>
								<div class="d-flex gap-2">
									<a href="<?= site_url('admin/admin-users/create') ?>" class="btn btn-secondary">Add Admin</a>
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
									 <th>Full Name</th>
									<!-- <th>Email</th> -->
									<th>Category</th>
									<th>Faculty</th>
									<th>Department</th>
									<th>Status</th>
									<th>Actions</th>
									</thead>
									
									<tbody>
										<?php $i =0;?>
									<?php if (! empty($users) && is_array($users)): ?>
										<?php foreach ($users as $u): ?>
											<tr>
											  <td><?php echo ++$i; ?></td>
											  <td><?= esc($u['fullname']) ?></td>
											  <!-- <td><?= esc($u['email']) ?></td> -->
											  <td><?= esc($u['category'] ?? '-') ?></td>
											  <td><?= esc($u['faculty_name'] ?? ($u['faculty_id'] ?? '-')) ?></td>
											  <td><?= esc($u['department_name'] ?? ($u['department_id'] ?? '-')) ?></td>
											  <td><?= (isset($u['completed_profile']) && $u['completed_profile']) ? '<span class="badge bg-success">Complete</span>' : '<span class="badge badge-sm bg-info">Pending</span>' ?></td>
											 
											<td>
											<a class="btn btn-sm btn-info open-view" data-id="<?= $u['id'] ?>">View</a>
											
											<?php if ((session()->get('admin')['role'] ?? session()->get('role')) === 'superadmin'): ?>
												<a class="btn btn-sm btn-warning open-edit" data-id="<?= $u['id'] ?>">Edit</a>
											<?php endif; ?>

											<a class="btn btn-sm btn-warning open-edit" data-id="<?= $u['id'] ?>">Edit</a>
											
											<?php if ((session()->get('admin')['role'] ?? session()->get('role') ?? '') === 'superadmin'): ?>
												<a href="<?= site_url('admin/staff/'.$u['id'].'/edit') ?>" class="btn btn-sm btn-warning"><i class="isax isax-edit-2"></i> Edit</a>
												<a href="<?= site_url('admin/staff/'.$u['id'].'/delete') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="isax isax-trash"></i> Delete</a>
											<?php endif; ?>

											<!-- single button used to open modal — uses values from the current row ($u) -->
											<button type="button"
													class="btn btn-sm btn-success open-eval"
													data-user-id="<?= esc($u['id']) ?>"
													data-category="<?= esc($u['category'] ?? '') ?>">
												Evaluate
											</button>
											</td>

											
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>
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

			<!-- Generic CRUD Modal (place near end of layout) -->
			<div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 id="crudModalTitle" class="modal-title">Loading...</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div id="crudModalBody" class="modal-body p-3">
					<div class="text-center py-4">Loading…</div>
				</div>
				</div>
			</div>
			</div>
		</div>

		<!-- /Main Wrapper -->
			<!-- Evaluation Modal -->
			<div class="modal fade" id="evaluationModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Evaluate Staff</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="evaluationModalBody" class="modal-body p-3">
					<!-- loaded form HTML will go here -->
					<div class="text-center py-4">Loading...</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="evalCancelBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="button" id="evalSubmitBtn" class="btn btn-primary">Submit Evaluation</button>
				</div>
				</div>
			</div>
			</div>
			

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- jQuery — if your layout already includes jQuery remove this line to avoid duplicate jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 bundle (includes Popper) — MUST be loaded before using bootstrap.Modal in JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables core + Responsive + Bootstrap5 adapter -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- include SweetAlert2 above if you don't already have it -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/*
  Admin dashboard scripts:
  - Ensure Bootstrap JS is loaded BEFORE using bootstrap.Modal
  - If your layout already includes Bootstrap or jQuery remove those CDN lines above
*/

$(document).ready(function () {
  // DataTable init
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

  // custom search binding (your visible search input)
  $('#tableSearch').on('keyup change', function () {
    table.search(this.value).draw();
  });

  // responsive redraw on resize
  $(window).on('resize', function () {
    table.responsive.recalc();
  });


  // ---------- Evaluation modal logic ----------
  // helper to safely get bootstrap Modal constructor; warn if unavailable
  if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
    console.warn('Bootstrap JS appears to be missing. The evaluation modal requires Bootstrap JS (bootstrap.bundle).');
  }

  // Click handler to open modal & load the form
  $(document).on('click', '.open-eval', function (e) {
    e.preventDefault();

    const userId = $(this).data('user-id');
    const category = $(this).data('category') || '';

    // show immediate loading UI in modal body
    $('#evaluationModalBody').html('<div class="text-center py-4">Loading evaluation form&hellip;</div>');

    // build URL (adjust if your route is different)

    const url = '<?= site_url('admin/evaluation/load-form') ?>' + '?id=' + encodeURIComponent(userId) + '&category=' + encodeURIComponent(category);

    // fetch form HTML
    fetch(url, { credentials: 'same-origin' })
      .then(resp => {
        if (!resp.ok) {
          // show friendly message in modal
          const msg = `Server returned ${resp.status} ${resp.statusText}`;
          $('#evaluationModalBody').html(`<div class="alert alert-danger">Unable to load form: ${msg}</div>`);
          console.error('Evaluation form load failed:', resp);
          // still launch modal so user sees the error
          const errModal = new bootstrap.Modal(document.getElementById('evaluationModal'));
          errModal.show();
          return Promise.reject(new Error(msg));
        }
        return resp.text();
      })
      .then(html => {
        $('#evaluationModalBody').html(html);
		  initEvalFormUniversal('#evaluationModalBody');

        // instantiate and show modal (bootstrap must be loaded)
        try {
          const modalEl = document.getElementById('evaluationModal');
          const myModal = new bootstrap.Modal(modalEl, { keyboard: false });
          myModal.show();
          // focus first input if present for UX
          $('#evaluationModalBody').find('input,select,textarea').first().focus();
        } catch (err) {
          console.error('Failed to show modal (bootstrap missing?):', err);
        }
      })
      .catch(err => {
        // If fetch failed due to network, show error inside modal
        console.error('Fetch error while loading evaluation form:', err);
      });
  });

  // Submit evaluation handler
  $(document).on('click', '#evalSubmitBtn', function (e) {
    e.preventDefault();
    const $btn = $(this);
    const form = $('#evaluationModalBody').find('form#evaluationForm');

    if (! form.length) {
      Swal.fire({ icon: 'error', title: 'No form', text: 'Evaluation form not found in modal.' });
      return;
    }

    // Basic HTML5 validation
    if (! form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }

    const fd = new FormData(form[0]);
    const action = form.attr('action') || '<?= site_url('admin/evaluation/submit') ?>';

    $btn.prop('disabled', true).text('Submitting…');

    fetch(action, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json().catch(() => null))
    .then(data => {
      $btn.prop('disabled', false).text('Submit Evaluation');

      if (! data) {
        Swal.fire({ icon: 'error', title: 'Server error', text: 'Unexpected server response. Check console.' });
        return;
      }

      if (! data.success) {
        // show errors in modal
        let out = '';
        if (data.errors && typeof data.errors === 'object') {
          out += '<div class="alert alert-danger"><ul>';
          Object.values(data.errors).forEach(v => out += `<li>${v}</li>`);
          out += '</ul></div>';
        } else {
          out += `<div class="alert alert-danger">${data.message || 'Failed to save evaluation'}</div>`;
        }
        $('#evaluationModalBody').prepend(out);
        return;
      }

      // Success: close modal and show toast
      const modalEl = document.getElementById('evaluationModal');
      const modalInstance = bootstrap.Modal.getInstance(modalEl);
      if (modalInstance) modalInstance.hide();

      Swal.fire({ icon: 'success', title: 'Saved', text: data.message || 'Evaluation saved.' });

      // Optionally refresh the table or the row to show updated state
      // table.ajax?.reload();   // if using ajax source
      // or reload full page:
      // location.reload();
    })
    .catch(err => {
      console.error('Submit evaluation error:', err);
      $btn.prop('disabled', false).text('Submit Evaluation');
      Swal.fire({ icon: 'error', title: 'Network error', text: 'Unable to submit evaluation. Check console.' });
    });
  });

});
</script>


<script>
/**
 * initEvalFormUniversal — initialize live score computation for the evaluation form
 * - Must be called after the partial HTML is injected into the modal body
 * - Looks for score fields using (in order): [data-score="component"], .score-input, [data-component-score]
 * - Finds the visible display element (id inside form) named computedTotalDisplay or element with data-target="computedTotal"
 * - Updates the overall score input inside the form (name=overall_score or id=overall_score)
 */
function initEvalFormUniversal(modalBodySelector = '#evaluationModalBody') {
  const $body = $(modalBodySelector);
  const $form = $body.find('form#evaluationForm').first();

  if (! $form.length) return; // nothing to do

  // find component inputs (tolerant)
  let $components = $form.find('[data-score="component"], .score-input, [data-component-score]');
  // allow selects or inputs with data-component-score attribute that may be non-number
  $components = $components.filter(function(){
    // keep only form controls (input/select/textarea)
    return $(this).is('input,select,textarea');
  });

  // find overall score input (scoped)
  const $overall = $form.find('input[name="overall_score"], #overall_score, input[data-overall="true"]').first();

  // find display element for human-friendly text
  let $display = $form.find('#computedTotalDisplay').first();
  if (! $display.length) {
    $display = $form.find('[data-target="computedTotal"]').first();
  }

  // compute function (scoped)
  function computeAndUpdate() {
    if (! $components.length) {
      if ($display.length) $display.text('—');
      if ($overall.length) $overall.val('');
      return;
    }

    // gather weights and values
    let weightedSum = 0;
    let totalWeight = 0;

    // first pass: sum explicit weights
    $components.each(function () {
      const $el = $(this);
      const rawWeight = $el.data('weight'); // may be undefined
      const weight = (typeof rawWeight === 'undefined' || rawWeight === null) ? null : parseFloat(rawWeight);
      if (weight !== null && !isNaN(weight)) {
        totalWeight += weight;
      }
    });

    // If no explicit weights were provided, we'll give equal weight to all components
    const anyWeightProvided = totalWeight > 0;

    // second pass: compute weighted sum
    $components.each(function () {
      const $el = $(this);
      // accept numeric-like values (for select we parse value)
      let rawVal = $el.val();

      // normalize values: treat empty as 0 (change if you prefer to ignore empty)
      if (rawVal === null || rawVal === '') {
        rawVal = '0';
      }

      let val = parseFloat(rawVal);
      if (isNaN(val)) val = 0;

      let w = $el.data('weight');
      if (typeof w === 'undefined' || w === null) {
        if (anyWeightProvided) {
          // if some weights exist, missing weight => 0 (so it doesn't get counted)
          w = 0;
        } else {
          // no weights at all => equal weight
          w = 1 / $components.length;
        }
      } else {
        w = parseFloat(w) || 0;
      }

      weightedSum += val * w;
      // when we used equal weights above we set totalWeight to 1 below
    });

    if (! anyWeightProvided) {
      totalWeight = 1; // since equal weights sum to 1
    }

    const computed = totalWeight > 0 ? (weightedSum / totalWeight) : 0;
    const clamped = Math.max(0, Math.min(100, computed)); // keep between 0..100
    const rounded = Math.round((clamped + Number.EPSILON) * 10) / 10; // 1 decimal

    if ($display.length) {
      $display.text(rounded.toFixed(1) + ' / 100');
    }

    if ($overall.length) {
      $overall.val(rounded);
      // trigger input event if you have listeners elsewhere
      $overall.trigger('input');
    }
  }

  // bind listeners (namespaced so we can unbind if re-initializing)
  $components.off('.compute').on('input.compute change.compute', function () {
    computeAndUpdate();
  });

  // run once now
  computeAndUpdate();
}

/* -----------------------------
  Usage: call initEvalFormUniversal() immediately after injecting HTML into modal
  Example inside your fetch().then(html => { ... }):
    $('#evaluationModalBody').html(html);
    initEvalFormUniversal('#evaluationModalBody');
    const myModal = new bootstrap.Modal(document.getElementById('evaluationModal'));
    myModal.show();
   ----------------------------- */
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  // helper to fetch and show partial in modal
  async function loadIntoModal(url, title = '') {
    const bodyEl = document.getElementById('crudModalBody');
    const titleEl = document.getElementById('crudModalTitle');
    bodyEl.innerHTML = '<div class="text-center py-4">Loading…</div>';
    if (title) titleEl.textContent = title;

    try {
      const resp = await fetch(url, { credentials: 'same-origin' });
      if (!resp.ok) {
        bodyEl.innerHTML = `<div class="alert alert-danger">Failed to load: ${resp.status}</div>`;
      } else {
        const html = await resp.text();
        bodyEl.innerHTML = html;
      }
      const m = new bootstrap.Modal(document.getElementById('crudModal'));
      m.show();
      return true;
    } catch (err) {
      console.error('loadIntoModal error', err);
      bodyEl.innerHTML = '<div class="alert alert-danger">Network error while loading content.</div>';
      const m = new bootstrap.Modal(document.getElementById('crudModal'));
      m.show();
      return false;
    }
  }

  // Event delegation: open view modal
  document.addEventListener('click', function (ev) {
    const btn = ev.target.closest('.open-view');
    if (!btn) return;
    ev.preventDefault();
    const id = btn.dataset.id;
    if (!id) return;
    loadIntoModal('<?= site_url('admin/staff') ?>/' + encodeURIComponent(id) + '/view', 'View staff');
  });

  // Event delegation: open edit modal
  document.addEventListener('click', function (ev) {
    const btn = ev.target.closest('.open-edit');
    if (!btn) return;
    ev.preventDefault();
    const id = btn.dataset.id;
    if (!id) return;
    loadIntoModal('<?= site_url('admin/staff') ?>/' + encodeURIComponent(id) + '/edit', 'Edit staff');
  });

  // Submit edit form via AJAX (delegated)
  document.addEventListener('submit', async function (ev) {
    const form = ev.target;
    if (! form.matches('#staffEditForm')) return;
    ev.preventDefault();

    const submitBtn = form.querySelector('#staffEditSubmit') || form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    const action = form.getAttribute('action') || window.location.href;
    const fd = new FormData(form);

    try {
      const resp = await fetch(action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const data = await resp.json().catch(() => null);
      if (!data) throw new Error('Invalid server response');

      if (! data.success) {
        // show errors at top of form
        let html = '<div class="alert alert-danger"><ul>';
        if (data.errors && typeof data.errors === 'object') {
          for (const k in data.errors) html += '<li>' + data.errors[k] + '</li>';
        } else {
          html += '<li>' + (data.message || 'Update failed') + '</li>';
        }
        html += '</ul></div>';
        form.insertAdjacentHTML('afterbegin', html);
        if (submitBtn) submitBtn.disabled = false;
        return;
      }

      // success: close modal and update row in DataTable (if present)
      const modalEl = document.getElementById('crudModal');
      const modalInstance = bootstrap.Modal.getInstance(modalEl);
      if (modalInstance) modalInstance.hide();

      // Optionally update the DataTable row in-place if you can find it by id:
      const updated = data.updated || null;
      if (updated) {
        // If you are using DataTables and have a row id or staff id column, update the row
        // This example assumes your DataTable has been saved to variable `table`
        if (window.adminStaffTable && typeof window.adminStaffTable.row === 'function') {
          // find row by matching staff id in the row data
          const table = window.adminStaffTable;
          let rowIndex = null;
          table.rows().every(function (idx, tableLoop, rowLoop) {
            const d = this.data();
            // d can be array or object depending on how you init DataTable. We try both:
            const rid = (d && d[0] && d[0].toString && d[0].toString() === updated.id.toString()) ? d[0] : null;
            // if your table uses objects with d.id:
            if (!rid && d && d.id && d.id.toString() === updated.id.toString()) {
              rowIndex = this;
              return false;
            }
          });
          // fallback: simple reload
          try {
            table.ajax?.reload();
          } catch (e) {
            // ignore
          }
        } else {
          // If you don't use DataTables ajax, reload page or update DOM manually
          location.reload();
        }
      } else {
        // fallback: reload page to reflect changes
        location.reload();
      }

    } catch (err) {
      console.error('submit edit error', err);
      alert('Network/server error while updating. Check console.');
      if (submitBtn) submitBtn.disabled = false;
    }
  });

});
</script>


<?= $this->endSection() ?>
