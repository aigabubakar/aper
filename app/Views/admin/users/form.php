<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= ($method==='create') ? 'Add User' : 'Edit User' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<h4><?= ($method==='create') ? 'Add User' : 'Edit User' ?></h4>
<?= view('partials/flash') ?>

<form action="<?= ($method==='create') ? site_url('admin/users/store') : site_url('admin/users/update/'.$user['id']) ?>" method="post">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="form-label">Full Name</label>
    <input name="fullname" class="form-control" value="<?= esc(old('fullname', $user['fullname'] ?? '')) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select" required>
      <?php $sel = old('role', $user['role'] ?? 'staff'); ?>
      <option value="staff" <?= $sel==='staff' ? 'selected' : '' ?>>Staff</option>
      <option value="hod" <?= $sel==='hod' ? 'selected' : '' ?>>HOD</option>
      <option value="dean" <?= $sel==='dean' ? 'selected' : '' ?>>Dean</option>
      <option value="admin" <?= $sel==='admin' ? 'selected' : '' ?>>Admin</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category" class="form-select" required>
      <?php $cat = old('category', $user['category'] ?? 'academic'); ?>
      <option value="academic" <?= $cat==='academic' ? 'selected' : '' ?>>Academic</option>
      <option value="senior_non_academic" <?= $cat==='senior_non_academic' ? 'selected' : '' ?>>Senior Non-Academic</option>
      <option value="junior_non_academic" <?= $cat==='junior_non_academic' ? 'selected' : '' ?>>Junior Non-Academic</option>
      <option value="non_academic" <?= $cat==='non_academic' ? 'selected' : '' ?>>Non-Academic</option>
    </select>
  </div>

  <?php if ($method === 'create'): ?>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input name="password" type="password" class="form-control" required>
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label class="form-label">Password (leave blank to keep current)</label>
      <input name="password" type="password" class="form-control">
    </div>
  <?php endif; ?>

  <button class="btn btn-success">Save</button>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
</form>

<?= $this->endSection() ?>


<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?><?= $method==='create' ? 'Add User' : 'Edit User' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<h4><?= $method==='create' ? 'Add User' : 'Edit User' ?></h4>
<?= view('partials/flash') ?>

<form action="<?= $method==='create' ? site_url('admin/users/store') : site_url('admin/users/update/'.$user['id']) ?>" method="post">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label>Staff ID</label>
    <input name="staff_id" class="form-control" value="<?= esc(old('staff_id', $user['staff_id'] ?? '')) ?>" required>
  </div>

  <div class="mb-3">
    <label>Full name</label>
    <input name="fullname" class="form-control" value="<?= esc(old('fullname', $user['fullname'] ?? '')) ?>" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input name="email" class="form-control" type="email" value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
  </div>

  <div class="mb-3">
    <label>Category</label>
    <?php $cat = old('category', $user['category'] ?? 'non_academic'); ?>
    <select name="category" class="form-select" required>
      <option value="academic" <?= $cat==='academic' ? 'selected' : '' ?>>Academic</option>
      <option value="senior_non_academic" <?= $cat==='senior_non_academic' ? 'selected' : '' ?>>Senior Non-Academic</option>
      <option value="junior_non_academic" <?= $cat==='junior_non_academic' ? 'selected' : '' ?>>Junior Non-Academic</option>
      <option value="non_academic" <?= $cat==='non_academic' ? 'selected' : '' ?>>Non-Academic</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Phone</label>
    <input name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
  </div>

  <div class="mb-3">
    <label>Period from (Year)</label>
    <input name="period_from" type="number" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_from', $user['period_from'] ?? '')) ?>">
  </div>

  <div class="mb-3">
    <label>Period to (Year)</label>
    <input name="period_to" type="number" min="1900" max="<?= date('Y')+1 ?>" class="form-control" value="<?= esc(old('period_to', $user['period_to'] ?? '')) ?>">
  </div>

  <?php if ($method === 'create'): ?>
    <div class="mb-3">
      <label>Password</label>
      <input name="password" type="password" class="form-control" required>
    </div>
  <?php else: ?>
    <div class="mb-3">
      <label>New Password (leave blank to keep current)</label>
      <input name="password" type="password" class="form-control">
    </div>
  <?php endif; ?>

  <button class="btn btn-success">Save</button>
  <a href="<?= site_url('admin/users') ?>" class="btn btn-secondary">Cancel</a>
</form>
<?= $this->endSection() ?>

