<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\AdminUserModel;

class AdminUsers extends AdminBaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminUserModel();
    }

    // List admins
    public function index()
    {
        $this->guard("superadmin"); // only superadmin allowed

        $admins = $this->adminModel->findAll();

        return view('admin/admin_users/index', [
            'admins' => $admins
        ]);
    }

    // Show add form
    public function create()
    {
        $this->guard("superadmin");

        return view('admin/admin_users/create');
    }

    // Save admin
    public function store()
    {
        $this->guard("superadmin");

        $data = $this->request->getPost();

        $this->adminModel->save([
            'username'   => $data['username'],
            'fullname'   => $data['fullname'],
            'email'      => $data['email'],
            'role'       => $data['role'],
            'faculty'    => $data['faculty'],
            'department' => $data['department'],
            'password'   => password_hash($data['password'], PASSWORD_DEFAULT),
            'is_active'  => 1
        ]);

        return redirect()->to('/admin/admin-users')
            ->with('success', 'Admin created successfully');
    }
}
