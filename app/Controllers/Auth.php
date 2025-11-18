<?php namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $staffModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        // initialize models + session
        $this->staffModel = new StaffModel();
        $this->userModel  = new UserModel();
        $this->session    = session();
        helper(['form', 'url', 'text']);
    }

    /**
     * Show login form (GET)
     */
    public function login()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Handle login form (POST)
     */
    public function attemptLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        // basic rate limit via session
        $attemptKey = 'login_attempts_' . md5($this->request->getIPAddress());
        $attempts = (int) $this->session->get($attemptKey, 0);
        if ($attempts >= 6) {
            $msg = 'Too many login attempts. Try again later.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(429);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        $user = $this->userModel->where('LOWER(email)', $email)->first();
        if (! $user) {
            $this->session->set($attemptKey, $attempts + 1);
            $msg = 'Invalid login credentials.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(401);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // optional active check
        if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
            $msg = 'Account disabled. Contact admin.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(403);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // verify password
        if (! password_verify($password, $user['password'])) {
            $this->session->set($attemptKey, $attempts + 1);
            $msg = 'Invalid login credentials.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(401);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // successful login — clear attempts and set session safely
        $this->session->remove($attemptKey);

        $role     = is_array($user) ? ($user['role'] ?? 'staff') : ($user->role ?? 'staff');
        $category = is_array($user) ? ($user['category'] ?? 'non_academic') : ($user->category ?? 'non_academic');

        $this->session->set([
            'isLoggedIn' => true,
            'user_id'    => is_array($user) ? ($user['id'] ?? null) : ($user->id ?? null),
            'fullname'   => is_array($user) ? ($user['fullname'] ?? ($user['name'] ?? $user['email'] ?? '')) : ($user->fullname ?? ($user->name ?? $user->email ?? '')),
            'email'      => is_array($user) ? ($user['email'] ?? null) : ($user->email ?? null),
            'role'       => $role,
            'category'   => $category,
            // keep a compact 'user' array in session for view convenience
            'user'       => is_array($user) ? $user : (array) $user,
        ]);

        // regenerate session id
        $this->session->regenerate();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => 'Welcome back!', 'redirect'=>site_url('dashboard')])->setStatusCode(200);
        }

        return redirect()->to('/dashboard')->with('success', 'Welcome back!');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $this->session->remove(['isLoggedIn','user_id','fullname','email','role','category','user']);
        $this->session->destroy();

        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    /**
     * Show / handle staff email check used for pre-registration
     */
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
                $msg = 'Email not found in staff records. Please contact ICT.';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(404);
                }
                return redirect()->back()->withInput()->with('errors', [$msg]);
            }

            // existing user?
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

            // set tempdata with staff id (10 minutes)
            $this->session->setTempdata('register_staff_id', (int)$staff['id'], 600);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email verified. Redirecting to registration...',
                    'redirect' => site_url('register')
                ])->setStatusCode(200);
            }

            return redirect()->to('/register');

        } catch (\Throwable $e) {
            log_message('error', 'checkEmail exception: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>'Server error — check logs.'])->setStatusCode(500);
            }
            return redirect()->back()->with('errors', ['Server error.']);
        }
    }

    /**
     * Show registration form (reads staff id from session)
     */
    public function register()
    {
        $staffId = $this->session->getTempdata('register_staff_id');
        if (! $staffId) {
            return redirect()->to('/check-email')->with('errors', ['Please verify your email first.']);
        }

        $staff = $this->staffModel->find((int)$staffId);
        if (! $staff) {
            $this->session->removeTempdata('register_staff_id');
            return redirect()->to('/check-email')->with('errors', ['Staff record not found.']);
        }

        return view('auth/register', ['staff' => $staff]);
    }

    /**
     * Persist registration to users table and auto-login
     *
     * Fields expected: fullname, email, staff_id, phone, category, period_from, period_to, password, password_confirm
     */
    public function saveRegistration()
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'staff_id' => 'required|max_length[120]',
            'phone' => 'permit_empty|max_length[80]',
            'category' => 'required|in_list[academic,senior_non_academic,junior_non_academic,non_academic]',
            'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
            'period_to'   => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // ensure temp staff id exists
        $staffId = $this->session->getTempdata('register_staff_id');
        if (! $staffId) {
            $msg = 'Session expired. Please verify your email again.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(401);
            }
            return redirect()->to('/check-email')->with('errors', [$msg]);
        }

        $staff = $this->staffModel->find((int)$staffId);
        if (! $staff) {
            $this->session->removeTempdata('register_staff_id');
            $msg = 'Staff record not found.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(404);
            }
            return redirect()->to('/check-email')->with('errors', [$msg]);
        }

        // prevent duplicates
        $email = strtolower(trim($this->request->getPost('email')));
        $staffIdInput = trim($this->request->getPost('staff_id'));
        if ($this->userModel->where('email', $email)->first()) {
            $msg = 'An account with that email already exists. Please login.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg,'redirect'=>site_url('login')])->setStatusCode(409);
            }
            return redirect()->to('/login')->with('success', $msg);
        }

        if ($this->userModel->where('staff_id', $staffIdInput)->first()) {
            $msg = 'An account with that Staff ID already exists. Please contact ICT.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(409);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // collect inputs
        $fullname = trim($this->request->getPost('fullname'));
        $phone    = trim($this->request->getPost('phone')) ?: null;
        $category = $this->request->getPost('category');
        $periodFrom = (int) $this->request->getPost('period_from');
        $periodTo   = (int) $this->request->getPost('period_to');
        $password = $this->request->getPost('password');

        if ($periodFrom > $periodTo) {
            $msg = 'Reporting period start year cannot be greater than end year.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $verifyToken = bin2hex(random_bytes(24));

        $insert = [
            'staff_id' => $staffIdInput,
            'fullname'  => $fullname,
            'email'     => $email,
            'password'  => $hash,
            'phone'     => $phone,
            'category'  => $category,
            'period_from' => $periodFrom,
            'period_to'   => $periodTo,
            'verify_token' => $verifyToken,
            'email_verified_at' => null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $this->userModel->insert($insert);
            $userId = $this->userModel->getInsertID();
        } catch (\Throwable $e) {
            log_message('error', 'saveRegistration insert failed: '.$e->getMessage());
            $msg = (ENVIRONMENT === 'development') ? 'Server error: '.$e->getMessage() : 'Server error while creating account.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // fetch created user
        $user = $this->userModel->find((int)$userId);
        if (! $user) {
            try { $this->userModel->delete($userId); } catch (\Throwable $_) {}
            $msg = 'Account created but could not log you in. Contact admin.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(500);
            }
            return redirect()->back()->with('errors', [$msg]);
        }

        // clear temp session
        $this->session->removeTempdata('register_staff_id');

        // set transient flag so next page can show a toast
        $this->session->setTempdata('just_registered', true, 5);

        // auto-login
        $this->session->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id'],
            'fullname'   => $user['fullname'] ?? $user['email'],
            'email'      => $user['email'],
            'role'       => $user['role'] ?? 'staff',
            'category'   => $user['category'] ?? $category,
            'user'       => $user,
        ]);
        $this->session->regenerate();

        // decide next route based on category
        switch ($category) {
            case 'academic':
                $next = site_url('profile/academic/personal');
                break;
            case 'senior_non_academic':
                $next = site_url('profile/senior/personal');
                break;
            case 'junior_non_academic':
                $next = site_url('profile/junior/personal');
                break;
            case 'non_academic':
            default:
                $next = site_url('profile/nonacademic/personal');
                break;
        }

        $redirectDelay = 1500; // ms

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successful. Redirecting to profile setup...',
                'redirect' => $next,
                'redirectDelay' => $redirectDelay,
            ])->setStatusCode(200);
        }

        // set tempdata for non-AJAX UX (optional)
        $this->session->setTempdata('post_register_redirect', $next, 10);
        $this->session->setTempdata('post_register_redirect_delay', $redirectDelay, 10);

        return redirect()->to($next)->with('success', 'Registration successful. Redirecting to profile setup...');
    }

    /**
     * Verify email token
     */
    public function verify($token = null)
    {
        if (! $token) {
            return redirect()->to('/')->with('error', 'Invalid verification link.');
        }

        $user = $this->userModel->where('verify_token', $token)->first();
        if (! $user) {
            return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
        }

        $this->userModel->update($user['id'], [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'verify_token' => null,
        ]);

        // optionally log the user in after verification
        $this->session->set([
            'isLoggedIn' => true,
            'user_id' => $user['id'],
            'fullname' => $user['fullname'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'staff',
            'category' => $user['category'] ?? 'non_academic',
            'user' => $user,
        ]);
        $this->session->regenerate();

        return redirect()->to('/dashboard')->with('success', 'Email verified. Welcome!');
    }
}
