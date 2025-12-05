<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3>Add Admin User</h3>

<form action="<?= site_url('admin/admin-users/store') ?>" method="post">

    <div class="mb-3">
        <label>Username</label>
        <input name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Fullname</label>
        <input name="fullname" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input name="email" type="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="superadmin">Super Admin</option>
            <option value="dean">Dean</option>
            <option value="hod">HOD</option>
            <option value="admin">Admin</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Faculty</label>
        <input name="faculty" class="form-control">
    </div>

    <div class="mb-3">
        <label>Department</label>
        <input name="department" class="form-control">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input name="password" type="password" class="form-control" required>
    </div>

    <button class="btn btn-primary">Save Admin</button>

</form>

<?= $this->endSection() ?>
