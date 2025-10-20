

<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6">

                  <h3> </h3>
                                          </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">                                       
                        <svg class="stroke-icon">
                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid dashboard-3">
            <div class="row">
              <div class="col-xxl-5 col-ed-6 col-xl-8 box-col-7">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="card o-hidden welcome-card">            
                      <div class="card-body">
                        <h4 class="mb-3 mt-1 f-w-500 mb-0 f-22">

                        <?php date_default_timezone_set('Africa/Lagos');
                          // Get the current hour
                          $currentHour = date('H');
                          // Determine the greeting based on the hour
                          if ($currentHour >= 0 && $currentHour < 12) {
                              $greeting = "Good Morning!";
                          } elseif ($currentHour >= 12 && $currentHour < 17) {
                              $greeting = "Good Afternoon!";
                          } else {
                              $greeting = "Good Evening!";
                          }
                          // Display the greeting
                          echo $greeting;
                          ?> &nbsp;&nbsp;&nbsp;&nbsp;
                          
                          
                        <?= esc($user['fullname']) ?><span> 
                          <img src="../assets/images/dashboard-3/hand.svg" alt="hand vector"></span></h4>
                        <p>Edo State University Annual Performance Evaluation & Review System.</p>
                      </div><img class="welcome-img" src="../assets/images/dashboard-3/widget.svg" alt="search image">
                    </div>
                  </div>
                  
                </div>
              </div><br/>
              <div class="col-xxl-6 col-ed-4 col-xl-4 col-sm-6 box-col-5">

                  <div class="card mx-auto" style="max-width:800px;">
                    <div class="card-body text-center">
                      
                        <div class="successful-form img-fluid"> <img src="<?= base_url('assets/images/gif/dashboard-8/successful.gif') ?>" alt="Congrats" style="max-width:180px" class="mb-3">
                            <h2 class="mb-2">Congratulations, <?= esc($user['fullname'] ?? $user['email']) ?>!</h2>
                              <p>Well done! You have successfully completed your <?= date('Y') ?> APER registration.</p>
                        </div>
                            <p class="text-muted mb-4">Thank you — your details have been saved.</p>

                      <p>
                        <a href="<?= site_url('profile/print-summary') ?>" class="btn btn-outline-secondary">Print Summary</a>
                      </p>
                    </div>
                  </div>
                </div>
                

                <div class="card default-inline-calender"> 
                  <div class="card-body">
                    <div class="input-group main-inline-calender">
                      <input class="form-control" id="inline-calender1" type="date">
                    </div>
                  </div>
                </div> 
              </div>
            </div>
          </div>
          <!-- Container-fluid Ends-->
        </div>
       
    
<?= $this->endSection() ?>

