<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn') && in_array(session()->get('role'), ['admin','superadmin'])) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/auth/login'); // create view below
    }

    public function attemptLogin()
    {
        $rules = ['email' => 'required|valid_email', 'password' => 'required|min_length[6]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        // find admin record in admins table OR in users with role=admin
        $adminModel = new \App\Models\AdminModel();
        $admin = $adminModel->where('LOWER(email)', $email)->first();

        // fallback to users where role is admin
        if (! $admin) {
            $uModel = new \App\Models\UserModel();
            $admin = $uModel->where('LOWER(email)', $email)->where('role','admin')->first();
        }

        if (! $admin || ! password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('errors', ['Invalid credentials']);
        }

        // set session (store user array)
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $admin['id'],
            'fullname'   => $admin['fullname'] ?? $admin['name'] ?? $admin['email'],
            'email'      => $admin['email'],
            'role'       => $admin['role'] ?? 'admin',
            'user'       => $admin,
        ]);
        session()->regenerate();

        return redirect()->to('/admin/dashboard')->with('success', 'Welcome back!');
    }

    public function logout()
    {
        session()->remove(['isLoggedIn','user_id','fullname','email','role','user']);
        session()->destroy();
        return redirect()->to('/admin/login')->with('success','Logged out');
    }
}
