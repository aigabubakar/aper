<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Manage Admin Users<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white"><i class="isax isax-profile-2user me-2"></i>Manage Admin Users</h5>
            <div>
                <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-sm btn-outline-light me-2"><i class="isax isax-category me-1"></i>Dashboard</a>
                <a href="<?= site_url('admin/admin-users/create') ?>" class="btn btn-sm btn-light"><i class="isax isax-add me-1"></i>Add Admin</a>
            </div>
        </div>
        <div class="card-body p-4">
            
            <!-- Success/Error Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="isax isax-tick-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <i class="isax isax-info-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="adminTable" class="table table-striped table-bordered align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Username</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 15%">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($admins as $i => $admin): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><strong><?= esc($admin['username']) ?></strong></td>
                            <td><?= esc($admin['fullname']) ?></td>
                            <td><?= esc($admin['email']) ?></td>
                            <td>
                                <span class="badge rounded-pill bg-<?= $admin['role'] === 'superadmin' ? 'danger' : ($admin['role'] === 'admin' ? 'primary' : 'info') ?>">
                                    <?= strtoupper(esc($admin['role'])) ?>
                                </span>
                            </td>
                            <td><?= esc($admin['faculty_name'] ?? '-') ?></td>
                            <td><?= esc($admin['department_name'] ?? '-') ?></td>
                            <td>
                                <span class="badge bg-<?= $admin['is_active'] ? 'success' : 'secondary' ?>">
                                    <?= $admin['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= site_url('admin/admin-users/' . $admin['id'] . '/edit') ?>" class="btn btn-outline-warning" title="Edit Admin">
                                        <i class="isax isax-edit"></i> Edit
                                    </a>
                                    <a href="<?= site_url('admin/admin-users/' . $admin['id'] . '/delete') ?>" 
                                       class="btn btn-outline-danger" 
                                       title="Delete Admin"
                                       onclick="return confirm('Are you sure you want to delete this administrator? This action cannot be undone.')">
                                        <i class="isax isax-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Load DataTables Adapter in script section -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#adminTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: -1 } // Actions column not orderable
        ]
    });
});
</script>
<?= $this->endSection() ?>
