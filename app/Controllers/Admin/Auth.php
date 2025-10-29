<?php namespace App\Controllers\Admin;

use App\Models\AdminUserModel;

class Auth extends AdminBaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminUserModel();
    }

    public function login()
    {
        // show login form; if logged in, redirect to admin dashboard
        if ($this->adminSession->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/auth/login');
    }

    public function attempt()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->where('LOWER(email)', $email)->first();
        if (! $admin || ! password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('errors', ['Invalid credentials.']);
        }

        if ((int)($admin['is_active'] ?? 1) === 0) {
            return redirect()->back()->withInput()->with('errors', ['Account disabled.']);
        }

        // set separate admin session keys
        $this->adminSession->set([
            'isAdminLoggedIn' => true,
            'admin_id'        => $admin['id'],
            'admin_fullname'  => $admin['fullname'],
            'admin_email'     => $admin['email'],
            'admin_role'      => $admin['role'],
        ]);
        $this->adminSession->regenerate();

        return redirect()->to('/admin/dashboard')->with('success','Welcome back, '.$admin['fullname']);
    }

    public function logout()
    {
        $this->adminSession->remove(['isAdminLoggedIn','admin_id','admin_fullname','admin_email','admin_role']);
        $this->adminSession->destroy();
        return redirect()->to('/admin/login')->with('success','Logged out.');
    }
}
