<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Edit Admin User<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white"><i class="isax isax-user-edit me-2"></i>Edit Admin User</h5>
                    <a href="<?= site_url('admin/admin-users') ?>" class="btn btn-sm btn-outline-light"><i class="isax isax-arrow-left-2 me-1"></i>Back to List</a>
                </div>
                <div class="card-body p-4">
                    
                    <?php 
                    $errors = session()->getFlashdata('errors') ?? []; 
                    ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <i class="isax isax-info-circle me-2 fs-5"></i>
                                <span class="fw-medium">Please correct the errors highlighted below.</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('admin/admin-users/' . $adminUser['id'] . '/update') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Username</label>
                                <input name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" placeholder="e.g. johndoe" value="<?= esc(old('username') ?? $adminUser['username'] ?? '') ?>">
                                <?php if (isset($errors['username'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['username']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Full Name</label>
                                <input name="fullname" class="form-control <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>" placeholder="e.g. John Doe" value="<?= esc(old('fullname') ?? $adminUser['fullname'] ?? '') ?>">
                                <?php if (isset($errors['fullname'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['fullname']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Email Address</label>
                                <input name="email" type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" placeholder="e.g. johndoe@example.com" value="<?= esc(old('email') ?? $adminUser['email'] ?? '') ?>">
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Role</label>
                                <select id="adminRoleSelect" name="role" class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>">
                                    <option value="" disabled>-- Select Role --</option>
                                    <option value="superadmin" <?= (old('role') ?? $adminUser['role']) === 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                                    <option value="dean" <?= (old('role') ?? $adminUser['role']) === 'dean' ? 'selected' : '' ?>>Dean</option>
                                    <option value="hod" <?= (old('role') ?? $adminUser['role']) === 'hod' ? 'selected' : '' ?>>HOD</option>
                                    <option value="admin" <?= (old('role') ?? $adminUser['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['role']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Scoped fields (visible or contextual depending on role) -->
                        <div class="row">
                            <div class="col-md-6 mb-3" id="facultyWrapper">
                                <label class="form-label fw-medium">Faculty <small class="text-muted">(Required for Dean)</small></label>
                                <select name="faculty" class="form-select <?= isset($errors['faculty']) ? 'is-invalid' : '' ?>">
                                    <option value="">-- Select Faculty (None) --</option>
                                    <?php foreach ($faculties as $fac): ?>
                                        <option value="<?= esc($fac['id']) ?>" <?= (old('faculty') ?? $adminUser['faculty']) == $fac['id'] ? 'selected' : '' ?>><?= esc($fac['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['faculty'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['faculty']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 mb-3" id="departmentWrapper">
                                <label class="form-label fw-medium">Department <small class="text-muted">(Required for HOD)</small></label>
                                <select name="department" class="form-select <?= isset($errors['department']) ? 'is-invalid' : '' ?>">
                                    <option value="">-- Select Department (None) --</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= esc($dept['id']) ?>" <?= (old('department') ?? $adminUser['department']) == $dept['id'] ? 'selected' : '' ?>><?= esc($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['department'])): ?>
                                    <div class="invalid-feedback"><?= esc($errors['department']) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                            <input name="password" type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" placeholder="Minimum 6 characters">
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg"><i class="isax isax-tick-circle me-2"></i>Update Admin User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('adminRoleSelect');
    const facultyWrapper = document.getElementById('facultyWrapper');
    const departmentWrapper = document.getElementById('departmentWrapper');
    
    const facultySelect = facultyWrapper.querySelector('select');
    const departmentSelect = departmentWrapper.querySelector('select');

    function toggleScopeFields() {
        const role = roleSelect.value;
        
        if (role === 'dean') {
            // Show Faculty, hide/disable Department
            facultyWrapper.style.display = 'block';
            facultySelect.disabled = false;
            
            departmentWrapper.style.display = 'none';
            departmentSelect.disabled = true;
            departmentSelect.value = "";
        } else if (role === 'hod') {
            // Hide/disable Faculty, show Department
            facultyWrapper.style.display = 'none';
            facultySelect.disabled = true;
            facultySelect.value = "";
            
            departmentWrapper.style.display = 'block';
            departmentSelect.disabled = false;
        } else {
            // Hide/disable both for superadmin/admin
            facultyWrapper.style.display = 'none';
            facultySelect.disabled = true;
            facultySelect.value = "";
            
            departmentWrapper.style.display = 'none';
            departmentSelect.disabled = true;
            departmentSelect.value = "";
        }
    }

    roleSelect.addEventListener('change', toggleScopeFields);
    toggleScopeFields();
});
</script>
<?= $this->endSection() ?>
