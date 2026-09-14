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

                <?php if (isset($user['evaluation_overall_score']) && $user['evaluation_overall_score'] !== null && $user['evaluation_overall_score'] !== ''): ?>
                  <div class="card mt-4 border border-primary-subtle shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                      <h6 class="mb-0 text-white d-inline-flex align-items-center">
                        <i class="isax isax-award me-2 fs-5"></i> Annual Performance Evaluation Feedback
                      </h6>
                      <?php if (! empty($user['staff_evaluation_comment'])): ?>
                        <span class="badge bg-success-subtle text-success border border-success d-inline-flex align-items-center" style="background-color: rgba(25, 135, 84, 0.15) !important;">
                          <i class="isax isax-lock me-1"></i> Completed & Locked
                        </span>
                      <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning border border-warning d-inline-flex align-items-center" style="background-color: rgba(255, 193, 7, 0.15) !important;">
                          <i class="isax isax-timer me-1"></i> Awaiting Your Comment
                        </span>
                      <?php endif; ?>
                    </div>
                    <div class="card-body">
                      <div class="row align-items-center mb-4">
                        <div class="col-md-4 text-center border-end">
                          <h6 class="text-muted mb-1 text-uppercase fs-12">Overall Evaluation Score</h6>
                          <div class="display-4 fw-bold text-primary mb-0"><?= esc($user['evaluation_overall_score']) ?><span class="fs-24">%</span></div>
                          <p class="text-muted fs-13 mb-0">Evaluated on <?= date('d M, Y', strtotime($user['evaluation_at'])) ?></p>
                        </div>
                        <div class="col-md-8 ps-md-4">
                          <h6 class="text-muted mb-2 text-uppercase fs-12">Evaluator Remarks & Comments</h6>
                          <blockquote class="blockquote fs-15 mb-0 bg-light p-3 rounded border-start border-3 border-primary text-dark">
                            "<?= esc($user['evaluation_comments'] ?? 'No comments provided.') ?>"
                          </blockquote>
                        </div>
                      </div>

                      <!-- Detailed score breakdown if available -->
                      <?php 
                        $meta = [];
                        if (! empty($user['evaluation_meta'])) {
                            if (is_string($user['evaluation_meta'])) {
                                $meta = json_decode($user['evaluation_meta'], true) ?: [];
                            } elseif (is_array($user['evaluation_meta'])) {
                                $meta = $user['evaluation_meta'];
                            }
                        }
                      ?>
                      
                      <?php if (($user['category'] ?? '') === 'academic'): ?>
                        <h6 class="border-bottom pb-2 mb-3 text-secondary d-inline-flex align-items-center w-100">
                          <i class="isax isax-teacher me-2 fs-5"></i> Academic Performance Breakdown
                        </h6>
                        <div class="row row-gap-3">
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Teaching</span>
                              <strong class="fs-18 text-dark"><?= esc($user['evaluation_teaching'] ?? $meta['teaching_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Research</span>
                              <strong class="fs-18 text-dark"><?= esc($user['evaluation_research'] ?? $meta['research_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Supervision</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['supervision_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Service</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['service_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                        </div>
                      <?php elseif (in_array($user['category'] ?? '', ['senior_non_academic', 'junior_non_academic', 'non_academic'])): ?>
                        <h6 class="border-bottom pb-2 mb-3 text-secondary d-inline-flex align-items-center w-100">
                          <i class="isax isax-clipboard me-2 fs-5"></i> Non-Academic Criteria Breakdown
                        </h6>
                        <div class="row row-gap-3">
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Skills & Competence</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['skills_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Punctuality</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['punctuality_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Teamwork</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['teamwork_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                          <div class="col-6 col-md-3">
                            <div class="p-2 border rounded bg-light text-center">
                              <span class="fs-12 text-muted text-uppercase d-block mb-1">Initiative</span>
                              <strong class="fs-18 text-dark"><?= esc($meta['initiative_score'] ?? '-') ?>%</strong>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>

                      <!-- Staff Comment Input / display -->
                      <div class="mt-4 pt-3 border-top">
                        <?php if (! empty($user['staff_evaluation_comment'])): ?>
                          <label class="form-label text-uppercase fs-12 text-muted fw-semibold">Your Acknowledgment & Comments</label>
                          <div class="p-3 bg-success-subtle text-success-emphasis border border-success-subtle rounded" style="background-color: rgba(25, 135, 84, 0.1) !important; color: #198754 !important;">
                            <?= esc($user['staff_evaluation_comment']) ?>
                          </div>
                        <?php else: ?>
                          <form id="staffCommentForm" action="<?= site_url('profile/save-evaluation-comment') ?>" method="post" class="needs-validation" novalidate>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                              <label for="staff_comment" class="form-label fw-semibold text-dark fs-14">Your Acknowledgment & Comments <span class="text-danger">*</span></label>
                              <textarea class="form-control" id="staff_comment" name="staff_comment" rows="3" placeholder="Enter your response, feedback or comments on this evaluation..." required></textarea>
                              <div class="form-text text-danger fs-12">
                                <i class="isax isax-info-circle me-1"></i> Important: Once submitted, your comment is locked and the evaluation cannot be modified by the department or yourself anymore.
                              </div>
                            </div>
                            <div class="d-flex justify-content-end">
                              <button type="submit" id="staffCommentSubmit" class="btn btn-primary d-inline-flex align-items-center">
                                <i class="isax isax-tick-circle me-2"></i> Submit Response & Lock Evaluation
                              </button>
                            </div>
                          </form>
                        <?php endif; ?>
                      </div>

                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div> <!-- /.col -->

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('staffCommentForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      
      const commentInput = document.getElementById('staff_comment');
      const comment = commentInput.value.trim();
      if (! comment) {
        Swal.fire({
          icon: 'warning',
          title: 'Empty Comment',
          text: 'Please enter your response/comment before submitting.'
        });
        return;
      }

      Swal.fire({
        title: 'Submit & Lock?',
        text: 'Important: Once submitted, your comment is locked and the evaluation cannot be modified by the department or yourself anymore.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Submit and Lock',
        cancelButtonText: 'Cancel'
      }).then(async (result) => {
        if (result.isConfirmed) {
          const submitBtn = document.getElementById('staffCommentSubmit');
          if (submitBtn) submitBtn.disabled = true;

          const fd = new FormData(form);
          try {
            const resp = await fetch(form.action, {
              method: 'POST',
              body: fd,
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await resp.json().catch(() => null);
            if (data && data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Your response was submitted and the evaluation is now locked.',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data ? data.message : 'Failed to save comment.'
              });
              if (submitBtn) submitBtn.disabled = false;
            }
          } catch (err) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'An error occurred during submission.'
            });
            if (submitBtn) submitBtn.disabled = false;
          }
        }
      });
    });
  }
});
</script>
        </div> <!-- /.row (education) -->
      </div> <!-- /.card-body -->
    </div> <!-- /.card -->

  </div> <!-- /.col-lg-9 -->
</div> <!-- /.row -->
</div> <!-- /.row -->
</div> <!-- /.row -->

<?= $this->endSection() ?>
