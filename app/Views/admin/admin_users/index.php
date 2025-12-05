<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h3>Admin Users</h3>
    <a href="<?= site_url('admin/admin-users/create') ?>" class="btn btn-primary">Add Admin</a>
</div>

<table id="adminTable" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Fullname</th>
            <th>Email</th>
            <th>Role</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($admins as $i => $admin): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= $admin['fullname'] ?></td>
            <td><?= $admin['email'] ?></td>
            <td><?= strtoupper($admin['role']) ?></td>
            <td><?= $admin['faculty'] ?></td>
            <td><?= $admin['department'] ?></td>
            <td><?= $admin['is_active'] ? 'Active' : 'Inactive' ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    $('#adminTable').DataTable();
});
</script>

<?= $this->endSection() ?>
