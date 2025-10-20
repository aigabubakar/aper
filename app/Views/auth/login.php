<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- login page start-->
      <div class="page-content">
		<div class="form-v4-content">
    <div class="container-fluid">
      <div class="row">
        <div class="text-center">
            <h1>Welcome to APER  <?= date('Y') ?></h1>
            <p class="lead">Edo State University Annual Performance Evaluation & Review System</p>
        </div>
        <div class="col-xl-5">
            <img class="bg-img-cover bg-center" src=" <?= base_url('assets/images/loginbg_img3.png') ?>" alt="looginpage"></div>
        <div class="col-xl-7 p-0">    
          <div class="login-card login-dark">
            <div>
              <div class="login-main"> 
                 <?= view('partials/flash') ?>

                <form action="<?= base_url('login') ?>" class="theme-form" method="post" novalidate>
                     <?= csrf_field() ?>

                  <h4>Sign in to aper</h4>
                  <p>Enter your email & password to login</p>
                  <div class="form-group">
                    <label class="col-form-label">Email Address</label>
                      <input class="form-control"  id="email" name="email"  placeholder="name@edouniversity.edu.ng" type="email" value="<?= esc(old('email')) ?>" class="form-control" required autofocus>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Password</label>
                    <div class="form-input position-relative">
                        <input class="form-control"  id="password" name="password" type="password" placeholder="password" class="form-control" required>
                      <div class="show-hide"><span class="show">                        
                     </span></div>
                    </div>
                  </div>
                  <div class="form-group mb-0">
                   <div class="form-check">
                      <input class="checkbox-primary form-check-input" id="checkbox1" type="checkbox" name="remember" id="remember" class="form-check-input" <?= old('remember') ? 'checked' : '' ?>>
                      <label class="text-muted form-check-label" for="checkbox1">Remember password</label>
                    </div> 
                    <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Sign in to Continue</button>
                  </div>
                  <p class="mt-4 mb-0 text-center">Don't have account? Please click here to start your evaluation process.<a class="ms-2" href="<?= base_url('/check-email'); ?>">Apply for Evaluation</a></p>                     
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
 </div>
  </div>
 </div>
<?= $this->endSection() ?>


