<?= $this->extend('layouts/admin_main') ?>
<?= $this->section('content') ?>

<h4>Staff Management</h4>
<a href="<?= site_url('admin/staff/create') ?>" class="btn btn-primary mb-3">Add Staff</a>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Fullname</th>
      <th>Email</th>
      <th>Category</th>
      <th>Role</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($staff as $s): ?>
    <tr>
      <td><?= $s['id'] ?></td>
      <td><?= esc($s['fullname']) ?></td>
      <td><?= esc($s['email']) ?></td>
      <td><?= esc($s['category']) ?></td>
      <td><?= esc($s['role']) ?></td>
      <td>
        <a href="<?= site_url('admin/staff/edit/'.$s['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="<?= site_url('admin/staff/delete/'.$s['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">Delete</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>





<?= $this->endSection() ?>
