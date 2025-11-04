<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>User Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4>User Details</h4>

<table class="table">
  <tr><th>Staff ID</th><td><?= esc($user['staff_id']) ?></td></tr>
  <tr><th>Fullname</th><td><?= esc($user['fullname']) ?></td></tr>
  <tr><th>Email</th><td><?= esc($user['email']) ?></td></tr>
  <tr><th>Category</th><td><?= esc($user['category']) ?></td></tr>
  <tr><th>Phone</th><td><?= esc($user['phone']) ?></td></tr>
  <tr><th>Period</th><td><?= esc($user['period_from']) ?> — <?= esc($user['period_to']) ?></td></tr>
  <tr><th>Created</th><td><?= esc($user['created_at']) ?></td></tr>
</table>

<p>
  <a href="<?= site_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-warning">Edit</a>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">Back</a>
</p>
<?= $this->endSection() ?>
