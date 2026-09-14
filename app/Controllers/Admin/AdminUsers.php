<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\AdminUserModel;

class AdminUsers extends AdminBaseController
{
    protected $adminModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new AdminUserModel();
    }

    // List admins
    public function index()
    {
        $this->guard("superadmin"); // only superadmin allowed

        $admins = $this->adminModel->findAll();

        // Fetch faculties and departments to map IDs to names
        $db = \Config\Database::connect();
        $faculties = [];
        $departments = [];

        if ($db->tableExists('faculties')) {
            $fRows = $db->table('faculties')->select('id, name')->get()->getResultArray();
            $faculties = array_column($fRows, 'name', 'id');
        }

        if ($db->tableExists('departments')) {
            $dRows = $db->table('departments')->select('id, name')->get()->getResultArray();
            $departments = array_column($dRows, 'name', 'id');
        }

        // Map IDs to names in admin rows
        foreach ($admins as &$admin) {
            $fid = $admin['faculty'];
            $did = $admin['department'];
            $admin['faculty_name'] = (!empty($fid) && isset($faculties[$fid])) ? $faculties[$fid] : '-';
            $admin['department_name'] = (!empty($did) && isset($departments[$did])) ? $departments[$did] : '-';
        }

        return view('admin/admin_users/index', [
            'admins' => $admins
        ]);
    }

    // Show add form
    public function create()
    {
        $this->guard("superadmin");

        $db = \Config\Database::connect();
        $faculties = $db->tableExists('faculties') ? $db->table('faculties')->orderBy('name', 'ASC')->get()->getResultArray() : [];
        $departments = $db->tableExists('departments') ? $db->table('departments')->orderBy('name', 'ASC')->get()->getResultArray() : [];

        return view('admin/admin_users/create', [
            'faculties' => $faculties,
            'departments' => $departments
        ]);
    }

    // Save admin
    public function store()
    {
        $this->guard("superadmin");

        $rules = [
            'username' => 'required|min_length[3]|max_length[120]|is_unique[admin_users.username]',
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[admin_users.email]',
            'role'     => 'required|in_list[superadmin,dean,hod,admin]',
            'password' => 'required|min_length[6]',
        ];

        $role = $this->request->getPost('role');
        if ($role === 'dean') {
            $rules['faculty'] = 'required|integer';
        } elseif ($role === 'hod') {
            $rules['department'] = 'required|integer';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/admin-users/create')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $role = $data['role'];

        $this->adminModel->insert([
            'username'   => trim($data['username']),
            'fullname'   => trim($data['fullname']),
            'email'      => strtolower(trim($data['email'])),
            'role'       => $role,
            'faculty'    => ($role === 'dean') ? ($data['faculty'] ?: '') : '',
            'department' => ($role === 'hod') ? ($data['department'] ?: '') : '',
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'is_active'  => 1
        ]);

        return redirect()->to('/admin/admin-users')
            ->with('success', 'Admin created successfully');
    }

    // Show edit form
    public function edit($id = null)
    {
        $this->guard("superadmin");

        $id = (int)$id;
        $adminUser = $this->adminModel->find($id);

        if (! $adminUser) {
            return redirect()->to('/admin/admin-users')->with('error', 'Admin user not found');
        }

        $db = \Config\Database::connect();
        $faculties = $db->tableExists('faculties') ? $db->table('faculties')->orderBy('name', 'ASC')->get()->getResultArray() : [];
        $departments = $db->tableExists('departments') ? $db->table('departments')->orderBy('name', 'ASC')->get()->getResultArray() : [];

        return view('admin/admin_users/edit', [
            'adminUser'   => $adminUser,
            'faculties'   => $faculties,
            'departments' => $departments
        ]);
    }

    // Save edited admin
    public function update($id = null)
    {
        $this->guard("superadmin");

        $id = (int)$id;
        $adminUser = $this->adminModel->find($id);

        if (! $adminUser) {
            return redirect()->to('/admin/admin-users')->with('error', 'Admin user not found');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[120]|is_unique[admin_users.username,id,{$id}]",
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => "required|valid_email|is_unique[admin_users.email,id,{$id}]",
            'role'     => 'required|in_list[superadmin,dean,hod,admin]',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        $role = $this->request->getPost('role');
        if ($role === 'dean') {
            $rules['faculty'] = 'required|integer';
        } elseif ($role === 'hod') {
            $rules['department'] = 'required|integer';
        }

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/admin-users/' . $id . '/edit')->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        $role = $data['role'];

        $updateData = [
            'username'   => trim($data['username']),
            'fullname'   => trim($data['fullname']),
            'email'      => strtolower(trim($data['email'])),
            'role'       => $role,
            'faculty'    => ($role === 'dean') ? ($data['faculty'] ?: '') : '',
            'department' => ($role === 'hod') ? ($data['department'] ?: '') : '',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($data['password']) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->adminModel->update($id, $updateData);

        return redirect()->to('/admin/admin-users')
            ->with('success', 'Admin updated successfully');
    }

    // Delete admin user (strictly superadmin, blocks self-deletion)
    public function delete($id = null)
    {
        $this->guard("superadmin");

        $id = (int)$id;
        $currentAdminId = (int)(session()->get('admin')['id'] ?? session()->get('admin_id') ?? 0);

        if ($id === $currentAdminId) {
            return redirect()->to('/admin/admin-users')
                ->with('error', 'You cannot delete your own administrator account.');
        }

        $adminUser = $this->adminModel->find($id);
        if (! $adminUser) {
            return redirect()->to('/admin/admin-users')->with('error', 'Admin user not found');
        }

        $this->adminModel->delete($id);

        return redirect()->to('/admin/admin-users')
            ->with('success', 'Admin deleted successfully');
    }
}
