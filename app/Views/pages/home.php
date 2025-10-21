

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Home<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- login page start-->
    <div class="main-wrapper">
            <div class="login-content">
                <div class="row">
                    <!-- Login Banner -->
                    <div class="col-md-6 login-bg d-none d-lg-flex">
                        <div class="login-carousel">
                            <div>
                                <div class="login-carousel-section mb-3">
                                    <div class="login-banner">
                                        <img src="assets/img/auth/auth-1.svg" class="img-fluid" alt="Logo">
                                    </div>
                                    <div class="mentor-course text-center">
                                        <h3 class="mb-2">Welcome to <br><?= date('Y') ?><span class="text-secondary">Edo State </span> University Iyahmo.</h3>
                                         <p>Edo State University Annual Performance Evaluation & Review System.</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="login-carousel-section mb-3">
                                    <div class="login-banner">
                                        <img src="assets/img/auth/auth-1.svg" class="img-fluid" alt="Logo">
                                    </div>
                                    <div class="mentor-course text-center">
                                        <h3 class="mb-2">Welcome to <br><?= date('Y') ?><span class="text-secondary">Edo State </span> University Iyahmo.</h3>
                                         <p>Edo State University Annual Performance Evaluation & Review System.</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="login-carousel-section mb-3">
                                    <div class="login-banner">
                                        <img src="assets/img/auth/auth-1.svg" class="img-fluid" alt="Logo">
                                    </div>
                                    <div class="mentor-course text-center">
                                        <h3 class="mb-2">Welcome to <br><?= date('Y') ?><span class="text-secondary">Edo State </span> University Iyahmo.</h3>
                                         <p>Edo State University Annual Performance Evaluation & Review System.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Login Banner -->
        
                    <div class="col-md-6 login-wrap-bg">
                        <!-- Login -->
                        <div class="login-wrapper">
                            <div class="loginbox">
                                <div class="w-100">
                                    <div class="d-flex align-items-center justify-content-between login-header">
                                    <img class="bg-img-cover bg-center" src=" <?= base_url('assets/images/') ?>" alt="looginpage"></div>
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
                </div>
            </div>
        </div>
	   <!-- /Main Wrapper -->
    
<?= $this->endSection() ?>





