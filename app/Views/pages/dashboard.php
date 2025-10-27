<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
  <!-- Sidebar (the sidebar partial already contains the column wrapper: col-lg-3) -->
  <?= view('layouts/sidebar') ?>

  <!-- Main column -->
  <div class="col-lg-9">
    <div class="page-title d-flex align-items-center justify-content-between mb-3">
      <!-- you can place breadcrumbs / page actions here -->
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="fs-18 pb-3 border-bottom mb-3">Basic Information</h5>

        <div class="row g-3">

          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="fs-18 pb-3 border-bottom mb-3">Education</h5>
                <div class="education-flow">
                  <div class="ps-4 pb-3 timeline-flow">
                    <div>
                      <h6 class="mb-1">BCA - Bachelor of Computer Applications</h6>
                      <p>International University - (2004 - 2010)</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- /.col -->

          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="fs-18 pb-3 border-bottom mb-3">Education</h5>
                <div class="education-flow">
                  <div class="ps-4 pb-3 timeline-flow">
                    <div>
                      <h6 class="mb-1">BCA - Bachelor of Computer Applications</h6>
                      <p>International University - (2004 - 2010)</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- /.col -->

        </div> <!-- /.row (education) -->

        <div class="row mt-4">
          <div class="col-xxl-5 col-xl-8">
            <div class="card o-hidden welcome-card">
              <div class="card-body d-flex align-items-center">
                <div class="w-100">
                  <h4 class="mb-2 f-w-500 f-22">
                    <?php
                      date_default_timezone_set('Africa/Lagos');
                      $currentHour = (int) date('H');
                      if ($currentHour >= 0 && $currentHour < 12) {
                        $greeting = "Good Morning!";
                      } elseif ($currentHour >= 12 && $currentHour < 17) {
                        $greeting = "Good Afternoon!";
                      } else {
                        $greeting = "Good Evening!";
                      }
                      echo esc($greeting) . ' ';
                    ?>
                    <?= esc($user['fullname'] ?? session()->get('fullname') ?? ''); ?>
                  </h4>
                  <p class="mb-0">Edo State University Annual Performance Evaluation & Review System.</p>
                </div>
                <img class="welcome-img ms-3" src="<?= base_url('assets/images/dashboard-3/widget.svg') ?>" alt="widget" style="max-height:100px">
              </div>
            </div>
          </div>

          <div class="col-xxl-6 col-xl-4 col-sm-6">
            <div class="card mx-auto" style="max-width:800px;">
              <div class="card-body text-center">
                <div class="successful-form img-fluid">
                  <img src="<?= base_url('assets/images/gif/dashboard-8/successful.gif') ?>" alt="Congrats" style="max-width:180px" class="mb-3">
                  <h2 class="mb-2">Congratulations, <?= esc($user['fullname'] ?? session()->get('fullname') ?? $user['email'] ?? session()->get('email') ?? '') ?>!</h2>
                  <p>Well done! You have successfully completed your <?= date('Y') ?> APER registration.</p>
                </div>

                <p class="text-muted mb-4">Thank you — your details have been saved.</p>

                <p>
                  <a href="<?= site_url('profile/print-summary') ?>" class="btn btn-outline-secondary">Print Summary</a>
                </p>
              </div>
            </div>
          </div>
        </div> <!-- /.row (welcome + congrats) -->

      </div> <!-- /.card-body -->
    </div> <!-- /.card -->

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>
