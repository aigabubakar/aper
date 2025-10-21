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

  <!-- Bootstrap CSS -->
		<link rel="stylesheet" href="">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
		
		<!-- Fontawesome CSS -->
    <link href="<?= base_url('assets/plugins/fontawesome/css/fontawesome.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/fontawesome/css/all.min.css') ?>" rel="stylesheet">


		<!-- Select2 CSS -->
    <link href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>" rel="stylesheet">

        <!-- Slick CSS -->
		<link href="<?= base_url('assets/plugins/slick/slick.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/plugins/slick/slick-theme.css') ?>" rel="stylesheet">

		<!-- Feathericon CSS -->
        <link href="<?= base_url('assets/plugins/feather/feather.css') ?>" rel="stylesheet">

		<!-- Tabler Icon CSS -->
    <link href="<?= base_url('assets/plugins/tabler-icons/tabler-icons.css') ?>" rel="stylesheet">

        <!-- Iconsax CSS -->
    <link href="<?= base_url('assets/css/iconsax.css') ?>" rel="stylesheet">

		<!-- Main CSS -->
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">


  <?= $this->renderSection('styles') ?>
</head>

<body>
 

  <!-- HEADER / NAVBAR -->
  <!-- header -->
<?php if ($isLoggedIn): ?>
  <?= $this->include('partials/header_auth') ?>
<?php endif; ?>

  <!-- Main Content -->
  <div class="container">
    <?= $this->renderSection('content') ?>
  </div>

  <!-- FOOTER -->
<?php if ($isLoggedIn): ?>
  <?= $this->include('partials/footer_auth') ?>
<?php endif; ?>

<!-- jQuery -->
		<script src="<?= base_url('assets/js/jquery-3.7.1.min.js') ?>"></script>
		<!-- Bootstrap Core JS -->
		<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
		<!-- Select2 JS -->
	  	<script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
        <!-- Slick Slider -->
		<script src="<?= base_url('assets/plugins/slick/slick.js') ?>"></script>
        <!-- Validation-->
		<script src="<?= base_url('assets/js/validation.js') ?>"></script>	
		<!-- Custom JS -->
		<script src="<?= base_url('assets/js/script.js') ?>"></script>

  <!-- JS: core libs first -->
 
  <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
