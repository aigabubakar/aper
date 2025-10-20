<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Profile Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="card-title">Profile Overview</h4>
          <p class="text-muted mb-3">Manage your profile pages from the links below.</p>

          <?= view('partials/flash') ?>

          <div class="list-group">
            <a href="<?= site_url('profile/personal') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              Personal Details
              <span class="badge bg-<?= !empty($user['phone']) || !empty($user['dob']) || !empty($user['gender']) ? 'success' : 'secondary' ?> rounded-pill">
                <?= !empty($user['phone']) || !empty($user['dob']) || !empty($user['gender']) ? 'Completed' : 'Pending' ?>
              </span>
            </a>

            <a href="<?= site_url('profile/employment') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              Employment Details
              <span class="badge bg-<?= (!empty($user['department']) || !empty($user['period_from'])) ? 'success' : 'secondary' ?> rounded-pill">
                <?= (!empty($user['department']) || !empty($user['period_from'])) ? 'Completed' : 'Pending' ?>
              </span>
            </a>

            <a href="<?= site_url('profile/professional') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              Professional Details
              <span class="badge bg-<?= (!empty($user['qualifications']) || !empty($user['avatar'])) ? 'success' : 'secondary' ?> rounded-pill">
                <?= (!empty($user['qualifications']) || !empty($user['avatar'])) ? 'Completed' : 'Pending' ?>
              </span>
            </a>

            <a href="<?= site_url('dashboard') ?>" class="list-group-item list-group-item-action">
              Back to Dashboard
            </a>
          </div>

          <div class="mt-4">
            <h6>Profile Summary</h6>
            <dl class="row">
              <dt class="col-sm-3">Full name</dt>
              <dd class="col-sm-9"><?= esc($user['fullname'] ?? $user['name'] ?? '—') ?></dd>

              <dt class="col-sm-3">Email</dt>
              <dd class="col-sm-9"><?= esc($user['email'] ?? '—') ?></dd>

              <dt class="col-sm-3">Category</dt>
              <dd class="col-sm-9"><?= esc($user['category'] ?? '—') ?></dd>

              <dt class="col-sm-3">Profile complete</dt>
              <dd class="col-sm-9"><?= !empty($user['completed_profile']) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?></dd>
            </dl>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>




<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Profile Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px">
  <div class="card-body">
    <h4>Welcome, <?= esc($user['fullname'] ?? $user['email']) ?></h4>
    <p>Category: <strong><?= esc($user['category'] ?? 'N/A') ?></strong></p>

    <p>
      <a href="<?= site_url('profile/' . ($user['category'] ?? 'nonacademic') . '/personal') ?>" class="btn btn-primary">Continue Profile</a>
      <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-secondary">Go to Dashboard</a>
    </p>
  </div>
</div>
<?= $this->endSection() ?>
