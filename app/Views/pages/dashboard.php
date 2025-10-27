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
        <div class="row g-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="fs-18 pb-3 border-bottom mb-3">
                  <?php
                      date_default_timezone_set('Africa/Lagos');
                      $currentHour = (int) date('H');
                      if ($currentHour >= 0 && $currentHour < 12) {
                        $greeting = "Good Morning!";
                      } elseif ($currentHour >= 12 && $currentHour < 16) {
                        $greeting = "Good Afternoon!";
                      } else {
                        $greeting = "Good Evening!";
                      }
                      echo esc($greeting) . ' ';
                    ?>
                    <?= esc($user['fullname'] ?? session()->get('fullname') ?? ''); ?>
                </h5>
                <div class="education-flow">
                  <div class="ps-4 pb-3 timeline-flow">
                    <div>
                      
                    <p class="mb-0">Edo State University Annual Performance Evaluation & Review System.</p><br/>
                         <p class="mb-0">pls to continue with your APER kindly  make use of the navigation option to move within the system</p>
                   
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- /.col -->
        </div> <!-- /.row (education) -->
      </div> <!-- /.card-body -->
    </div> <!-- /.card -->

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>
