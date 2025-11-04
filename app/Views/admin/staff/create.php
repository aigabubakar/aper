<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4>Add New Staff</h4>
<form action="<?= site_url('admin/staff/store') ?>" method="post">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label>Fullname</label>
    <input type="text" name="fullname" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Category</label>
    <select name="category" class="form-control">
      <option value="academic">Academic</option>
      <option value="non-academic">Non Academic</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Role</label>
    <select name="role" class="form-control">
      <option value="staff">Staff</option>
      <option value="hod">HOD</option>
      <option value="dean">Dean</option>
    </select>
  </div>
  <button type="submit" class="btn btn-success">Save</button>
</form>

<?= $this->endSection() ?>
