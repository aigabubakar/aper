<?php
$session = session();
$isLoggedIn = (bool) $session->get('isLoggedIn');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta Tags -->
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <meta name="author" content="Aigboje Abubakar" />
  <title><?= $this->renderSection('title') ?> - Edo State University APER Registration</title>

  <!-- Favicon -->
  <link rel="icon" href="<?= base_url('favicon.png') ?>" />

  <!-- Google fonts (one copy each) -->
  <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">

  <!-- Vendor & Plugin CSS -->
  <link href="<?= base_url('assets/css/fontawesome.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/icofont.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/themify.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/flag-icon.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/feather-icon.css') ?>" rel="stylesheet">
	<link rel="stylesheet" href="https://jqueryvalidation.org/files/demo/site-demos.css">


  <!-- Plugins css -->
  <link href="<?= base_url('assets/css/vendors/slick.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/slick-theme.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/scrollbar.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/animate.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/jquery.dataTables.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/select.bootstrap5.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/vendors/flatpickr/flatpickr.min.css') ?>" rel="stylesheet">

  <!-- Bootstrap and App CSS -->
  <link href="<?= base_url('assets/css/vendors/bootstrap.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/opensans-font.css') ?>" rel="stylesheet">
 
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
   <link href="<?= base_url('assets/css/formstyle.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/css/color-1.css') ?>" media="screen" rel="stylesheet">
  <link href="<?= base_url('assets/css/responsive.css') ?>" rel="stylesheet">

  <?= $this->renderSection('styles') ?>
</head>

<body>
  <!-- loader starts-->
  <div class="loader-wrapper">
    <div class="loader-index"><span></span></div>
    <svg>
      <defs></defs>
      <filter id="goo">
        <feGaussianBlur in="SourceGraphic" stdDeviation="11" result="blur" />
        <feColorMatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
      </filter>
    </svg>
  </div>
  <!-- loader ends-->

  <!-- HEADER / NAVBAR -->
  <!-- header -->
<?php if ($isLoggedIn): ?>
  <?= $this->include('partials/header_auth') ?>
<?php else: ?>
  <?= $this->include('partials/header_guest') ?>
<?php endif; ?>

  <!-- Main Content -->
  <div class="container">
    <?= $this->renderSection('content') ?>
  </div>

  <!-- FOOTER -->
<?php if ($isLoggedIn): ?>
  <?= $this->include('partials/footer_auth') ?>
<?php else: ?>
  <?= $this->include('partials/footer_guest') ?>
<?php endif; ?>

  <!-- JS: core libs first -->
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/icons/feather-icon/feather.min.js') ?>"></script>

  <!-- Scrollbar / sidebar / config -->
  <script src="<?= base_url('assets/js/scrollbar/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/scrollbar/custom.js') ?>"></script>
  <script src="<?= base_url('assets/js/config.js') ?>"></script>

  <!-- Plugins JS -->
  <script src="<?= base_url('assets/js/sidebar-menu.js') ?>"></script>
  <script src="<?= base_url('assets/js/sidebar-pin.js') ?>"></script>
  <script src="<?= base_url('assets/js/slick/slick.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/slick/slick.js') ?>"></script>
  <script src="<?= base_url('assets/js/header-slick.js') ?>"></script>
  <script src="<?= base_url('assets/js/chart/apex-chart/apex-chart.js') ?>"></script>
  <script src="<?= base_url('assets/js/chart/apex-chart/stock-prices.js') ?>"></script>
  <script src="<?= base_url('assets/js/chart/apex-chart/moment.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/counter/counter-custom.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatable/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatable/datatables/dataTables.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatable/datatables/dataTables.select.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatable/datatables/select.bootstrap5.js') ?>"></script>
  <script src="<?= base_url('assets/js/datatable/datatables/datatable.custom.js') ?>"></script>
  <script src="<?= base_url('assets/js/flat-pickr/flatpickr.js') ?>"></script>
  <script src="<?= base_url('assets/js/dashboard/dashboard_3.js') ?>"></script>
  <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script src="<?= base_url('assets/js/form-wizard/form-wizard.js') ?>"></script>
    <script src="<?= base_url('assets/js/form-wizard/image-upload.js') ?>"></script>
    <script src="<?= base_url('assets/js/form-validation-custom.js') ?>"></script>
    <script src="<?= base_url('assets/js/height-equal.js') ?>"></script>
    <script src="<?= base_url('') ?>"></script>
    <script src="<?= base_url('') ?>"></script>


 	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
	<script>
		// just for the demos, avoids form submit
		jQuery.validator.setDefaults({
		  	debug: true,
		  	success:  function(label){
        		label.attr('id', 'valid');
   		 	},
		});
		$( "#myform" ).validate({
		  	rules: {
			    password: "required",
		    	comfirm_password: {
		      		equalTo: "#password"
		    	}
		  	},
		  	messages: {
		  		first_name: {
		  			required: "Please enter a firstname"
		  		},
		  		last_name: {
		  			required: "Please enter a lastname"
		  		},
		  		your_email: {
		  			required: "Please provide an email"
		  		},
		  		password: {
	  				required: "Please enter a password"
		  		},
		  		comfirm_password: {
		  			required: "Please enter a password",
		      		equalTo: "Wrong Password"
		    	}
		  	}
		});
	</script>


  <!-- app scripts -->
  <script src="<?= base_url('assets/js/script.js') ?>"></script>
  <script src="<?= base_url('assets/js/script1.js') ?>"></script>

  <!-- page-specific scripts -->
  <?= $this->renderSection('scripts') ?>

  <!-- small inline util script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const emailInput = document.getElementById('email');
      if (emailInput) emailInput.focus();

      const alert = document.querySelector('.alert.alert-danger');
      if (alert) alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  </script>


<?php if (session()->getTempdata('just_registered')): ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  // basic bootstrap toast creation
  const toastHtml = `
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1055">
    <div id="regToast" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Registration successful — continuing setup...
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>`;
  document.body.insertAdjacentHTML('beforeend', toastHtml);
  // auto remove after short time (optional)
  setTimeout(()=> {
    const t = document.getElementById('regToast');
    if (t) t.remove();
  }, 4000);
});
</script>
<?php endif; ?>

</body>
</html>
