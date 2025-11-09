<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, go to dashboard
        if (session()->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/auth/login');
    }

    public function attemptLogin()
    {
        $model = new AdminModel();

        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        // Look up admin record
        $admin = $model->where('email', $email)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            // Regenerate session ID to prevent fixation
            session()->regenerate();

            // Save minimal session data
            session()->set([
                'isAdminLoggedIn' => true,
                'admin_id'        => $admin['id'],
                'admin_name'      => $admin['fullname'],
                'admin_email'     => $admin['email'],
                'admin_role'      => $admin['role'],
            ]);

            // Update last login timestamp
            $model->update($admin['id'], ['last_login' => date('Y-m-d H:i:s')]);

            return redirect()->to('/admin/dashboard');
        }

        // Invalid credentials
        return redirect()
            ->back()
            ->with('errors', ['Invalid email or password.']);
    }
    /**
 * Compatibility wrapper so older routes/forms posting to "attempt" keep working.
 */
public function attempt()
{
    return $this->attemptLogin();
}


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('message', 'You have been logged out.');
    }
}
