<!-- <?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Manage Users<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
  <h4>Users</h4>
  <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">Add User</a>
</div>

<?= view('partials/flash') ?>

<form method="get" class="mb-3">
  <div class="input-group">
    <input name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Search name or email">
    <button class="btn btn-outline-secondary">Search</button>
  </div>
</form>

<table class="table table-striped">
  <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Category</th><th>Action</th></tr></thead>
  <tbody>
    <?php foreach ($users as $i => $u): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= esc($u['fullname']) ?></td>
        <td><?= esc($u['email']) ?></td>
        <td><?= esc($u['role']) ?></td>
        <td><?= esc($u['category']) ?></td>
        <td>
          <a href="<?= site_url('admin/users/edit/'.$u['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <form action="<?= site_url('admin/users/delete/'.$u['id']) ?>" method="post" style="display:inline" onsubmit="return confirm('Delete user?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $pager->links() ?>

<?= $this->endSection() ?> -->



<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Manage Users<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between mb-3">
  <h4>Users</h4>
  <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">Add User</a>
</div>

<?= view('partials/flash') ?>

<form method="get" class="mb-3">
  <div class="input-group">
    <input name="q" value="<?= esc($q) ?>" class="form-control" placeholder="Search name, email or staff id">
    <button class="btn btn-outline-secondary">Search</button>
  </div>
</form>

<table class="table table-striped">
  <thead><tr><th>#</th><th>Staff ID</th><th>Name</th><th>Email</th><th>Category</th><th>Phone</th><th>Created</th><th>Action</th></tr></thead>
  <tbody>
    <?php foreach ($users as $i => $u): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= esc($u['staff_id']) ?></td>
        <td><?= esc($u['fullname']) ?></td>
        <td><?= esc($u['email']) ?></td>
        <td><?= esc($u['category']) ?></td>
        <td><?= esc($u['phone']) ?></td>
        <td><?= esc($u['created_at']) ?></td>
        <td>
          <a href="<?= site_url('admin/users/show/'.$u['id']) ?>" class="btn btn-sm btn-info">View</a>
          <a href="<?= site_url('admin/users/edit/'.$u['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
          <form action="<?= site_url('admin/users/delete/'.$u['id']) ?>" method="post" style="display:inline" onsubmit="return confirm('Delete user?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?= $pager->links() ?>



<?= $this->endSection() ?>
