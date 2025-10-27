

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Home<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  .text-center-container {
  text-align: center;
}
</style>

			<section class="about-section-two pb-0">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6">
						<div class="p-3 p-sm-4 position-relative">
							<div class="position-absolute top-0 start-0 z-n1">
								<img src="assets/img/shapes/shape-1.svg" alt="img">
							</div>
							<div class="position-absolute bottom-0 end-0 z-n1">
								<img src="assets/img/shapes/shape-2.svg" alt="img">
							</div>
							<div class="position-absolute bottom-0 start-0 mb-md-5 ms-md-n5">
								<img src="assets/img/icons/icon-1.svg" alt="img">
							</div>
							<img class="img-fluid img-radius" src="assets/img/auth/auth-1.svg" alt="img">
						</div>
						</div>
						<div class="col-lg-6">
            <!-- Login -->
                        <div class="login-wrapper">
                            <div class="loginbox">
                                <div class="w-90">
                                    <div class="d-flex align-items-center justify-content-between login-header">
                                    <div class=>
                                       <img class="bg-img-cover text-center-container"  src="assets/img/logo.jpg" class="img-fluid" alt="Logo">

                                    </div>
                                      </div>
                                    </div>
                                    <h4>Sign in to APER</h4>
                                    <?= view('partials/flash') ?>
                                    <form action="<?= base_url('login') ?>" class="theme-form" method="post" novalidate>
                                        <?= csrf_field() ?>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative">
                                                 <input class="form-control form-control-lg"  id="email" name="email"  placeholder="name@edouniversity.edu.ng" type="email" value="<?= esc(old('email')) ?>" class="form-control" required autofocus>
                                                <span><i class="isax isax-sms input-icon text-gray-7 fs-14"></i></span>
                                            </div>
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label">Password <span class="text-danger ms-1">*</span></label>
                                            <div class="position-relative" id="passwordInput">
                                                <input class="pass-inputs form-control form-control-lg"  id="password" name="password" type="password" placeholder="password" class="form-control" required>
                                                <span class="isax toggle-passwords isax-eye-slash fs-14"></span>
                                            </div>	
                                        </div>
                                       
                                        <div class="d-grid">
                                            <button class="btn btn-secondary btn-lg btn-primary" type="submit">Sign in to Continue <i class="isax isax-arrow-right-3 ms-1"></i></button>
                                        </div>
                                    </form>

                                    <div class="fs-14 fw-normal d-flex align-items-center justify-content-center">
                                      <p class="mt-4 mb-0 text-center">Don't have account? Please click here to start your evaluation process.<br/><a class="ms-2" href="<?= base_url('/check-email'); ?>">Apply for Evaluation</a></p>                     
                                    </div>  
                                    <!-- /Login -->
                                </div>
                            </div>
                        </div>
                    </div>

              
                 <br/>  <br/>
							<div class="ps-0 ps-lg-2 pt-4 pt-lg-0 ps-xl-5">
                  <div class="section-header">
                      <h3 class="mb-2">Welcome to <?= date('Y')?> <span class="text-secondary">Edo State </span> University Iyamho APER SYSTEM.</h3>
                          <p>Edo State University Annual Performance Evaluation & Review System.</p>
                  </div>
								<div class="d-flex align-items-center about-us-banner">
									<div>
                    <span class="bg-secondary-transparent rounded-3 p-2 about-icon d-flex justify-content-center align-items-center">
												<i class="isax isax-book-1 fs-24"></i>
										</span>
									</div>
									<div class="ps-3">
										<h6 class="mb-2">Quick Tip</h6>
										<p>Before you can make use of the login option to login into your profile, you to have created a profile on th system.</p>
                   	<p>The fact that you did APER in the previous year doesn't tranlate to automatic login  as each Evaluation is for a perticular year</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
<?= $this->endSection() ?>





