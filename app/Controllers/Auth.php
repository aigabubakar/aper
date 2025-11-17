<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->session = session();
        helper(['form', 'url']);
    }

    public function login()
    {
        // if already logged in as admin redirect
        if ($this->session->get('admin')) {
            return redirect()->to('/admin');
        }
        return view('admin/auth/login');
    }

    

public function checkEmail()
{
    try {
        if ($this->request->getMethod() !== 'post') {
            return view('auth/check_email');
        }

        $rules = ['email' => 'required|valid_email'];
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $email = trim(strtolower($this->request->getPost('email')));
        $staff = $this->staffModel->where('LOWER(email)', $email)->first();

        if (! $staff) {
            $msg = 'Email not found in staff records. Please contact ICT';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(404);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // if a user already exists, tell client and provide login URL
        $existingUser = $this->userModel->where('LOWER(email)', $email)->first();
        if ($existingUser) {
            $msg = 'An account already exists for this email. Redirecting you to login...';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'already_registered' => true,
                    'message' => $msg,
                    'redirect' => site_url('login')
                ])->setStatusCode(200);
            }
            return redirect()->to('/login')->with('success', 'An account already exists. Please login.');
        }

        // OK — set temporary staff id for registration (6 minutes)
        $this->session->setTempdata('register_staff_id', (int)$staff['id'], 600);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email verified. Please hold while we Redirecting to registration...',
                'redirect' => site_url('register')
            ])->setStatusCode(200);
        }

        return redirect()->to('/register');

    } catch (\Throwable $e) {
        log_message('error', 'checkEmail exception: ' . $e->getMessage());
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Server error — check logs.'])->setStatusCode(500);
        }
        throw $e;
    }
}


    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $admin = $this->adminModel->where('LOWER(email)', $email)->first();

        if (! $admin || ! password_verify($password, $admin['password'])) {
            return redirect()->back()->withInput()->with('errors', ['Invalid credentials']);
        }

        // successful login — set session consistently
        $this->session->set('admin', [
            'id'    => (int)$admin['id'],
            'name'  => $admin['name'] ?? ($admin['fullname'] ?? ''),
            'email' => $admin['email'],
            'role'  => $admin['role'] ?? 'admin',
            'last_login' => $admin['last_login'] ?? null,
        ]);

        // convenient top-level session keys (used in many views)
        $this->session->set([
            'isAdminLoggedIn' => true,
            'admin_id' => (int)$admin['id'],
            'fullname' => $admin['name'] ?? ($admin['fullname'] ?? $admin['email']),
            'email' => $admin['email'],
            'role' => $admin['role'] ?? 'admin',
        ]);

        // update last login time
        try {
            $this->adminModel->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);
        } catch (\Throwable $e) {
            // ignore non-critical errors updating last_login
            log_message('error', 'Admin login last_login update failed: '.$e->getMessage());
        }

        $this->session->regenerate();

        return redirect()->to('/admin/dashboard')->with('success', 'Welcome back!');
    }

    public function logout()
    {
        // remove admin session keys only
        $this->session->remove(['admin', 'isAdminLoggedIn', 'admin_id', 'fullname', 'email', 'role']);
        $this->session->destroy();

        return redirect()->to('/admin/login')->with('success', 'You have been logged out.');
    }
}
