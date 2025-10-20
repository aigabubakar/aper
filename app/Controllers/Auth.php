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
        helper(['form', 'url']);
    }

    
     /**
 * Show and handle email-check form (GET/POST)
 * Handles both normal POST and AJAX POST requests.
 */



 // app/Controllers/Auth.php (inside Auth class)



/**
 * Show login form (GET)
 */
public function login()
{
    // If already logged in, redirect to dashboard
    if ($this->session->get('isLoggedIn')) {
        return redirect()->to('/dashboard');
    }

    return view('auth/login'); // simple view with form
}

/**
 * Handle login form (POST)
 */
public function attemptLogin()
{
    helper('text'); // for random_string if needed
    $rules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_length[6]',
    ];

    // Basic validation
    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    $email = strtolower(trim($this->request->getPost('email')));
    $password = $this->request->getPost('password');

    // Simple rate-limit using session (dev-friendly). For production, use cache/redis.
    $attemptKey = 'login_attempts_' . md5($this->request->getIPAddress());
    $attempts = (int) $this->session->get($attemptKey, 0);

    if ($attempts >= 6) {
        return redirect()->back()->withInput()->with('errors', ['Too many login attempts. Try again later.']);
    }

    $user = $this->userModel->where('LOWER(email)', $email)->first();

    if (! $user) {
        // increment attempts
        $this->session->set($attemptKey, $attempts + 1);
        // optionally track timestamp for expiry (not shown here)
        return redirect()->back()->withInput()->with('errors', ['Invalid login credentials.']);
    }

    // check active flag (if you use it)
    if (isset($user['is_active']) && (int)$user['is_active'] === 0) {
        return redirect()->back()->withInput()->with('errors', ['Account disabled. Contact admin.']);
    }

    // verify password
    if (! password_verify($password, $user['password'])) {
        $this->session->set($attemptKey, $attempts + 1);
        return redirect()->back()->withInput()->with('errors', ['Invalid login credentials.']);
    }

    // OPTIONAL: require email verification
    // if (empty($user['email_verified_at'])) {
    //     return redirect()->back()->withInput()->with('errors', ['Please verify your email before logging in.']);
    // }

    // Successful login: clear attempts, set session
    $this->session->remove($attemptKey);
    $this->session->set([
        'isLoggedIn' => true,
        'user_id'    => $user['id'],
        'fullname'   => $user['fullname'] ?? $user['name'] ?? $user['email'],
        'email'      => $user['email'],
        'role'       => $user['role'] ?? 'staff',
    ]);

    // regenerate session id to prevent fixation
    $this->session->regenerate();

    // redirect to dashboard
    return redirect()->to('/dashboard')->with('success', 'Welcome back!');
}

/**
 * Logout user
 */
public function logout()
{
    // clear session keys used
    $this->session->remove(['isLoggedIn','user_id','fullname','email','role']);
    $this->session->destroy(); // optional: destroy all session data

    // Redirect back to login page
    return redirect()->to('/login')->with('success', 'You have been logged out.');
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





public function checkEmail122()
{
    if ($this->request->getMethod() === 'post') {
        $rules = ['email' => 'required|valid_email'];
        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors'  => $errors,
                ])->setStatusCode(422);
            }

            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $email = trim(strtolower($this->request->getPost('email')));
        $staff = $this->staffModel->where('LOWER(email)', $email)->first();

        if (! $staff) {
            $msg = 'Email not found in staff records. Please contact ICT.';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $msg,
                ])->setStatusCode(404);
            }

            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // already registered?
        if ($this->userModel->where('LOWER(email)', $email)->first()) {
            $msg = 'An account already exists for this email. Please login.';

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'already_registered' => true,
                    'message' => $msg,
                ])->setStatusCode(200);
            }

            return redirect()->back()->with('success', $msg);
        }

        // OK — set session staff id for registration, then respond
        $this->session->setTempdata('register_staff_id', (int)$staff['id'], 600); // expires in 10 min

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email verified. Please hold while we Redirecting to registration...',
                'redirect' => site_url('register'),
            ])->setStatusCode(200);
        }

        // non-AJAX fallback
        return redirect()->to('/register');
    }

    // GET request — show form
    return view('auth/check_email');
}

    /**
     * Show registration form (reads staff id from session)
     */
    public function register()
    {
        $staffId = $this->session->get('register_staff_id');
        if (! $staffId) {
            return redirect()->to('/check-email')->with('errors', ['Please verify your email first.']);
        }

        $staff = $this->staffModel->find((int)$staffId);
        if (! $staff) {
            // clear session to be safe
            $this->session->remove('register_staff_id');
            return redirect()->to('/check-email')->with('errors', ['Staff record not found.']);
        }

        return view('auth/register', ['staff' => $staff]);
    }

    /**
     * Persist registration to users table
     */

public function saveRegistration()
{
    // validation rules
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

    // normalized inputs
    $fullname = trim($this->request->getPost('fullname'));
    $email    = strtolower(trim($this->request->getPost('email')));
    $staffId  = trim($this->request->getPost('staff_id'));
    $phone    = trim($this->request->getPost('phone')) ?: null;
    $category = $this->request->getPost('category');
    $periodFrom = (int) $this->request->getPost('period_from');
    $periodTo   = (int) $this->request->getPost('period_to');
    $password = $this->request->getPost('password');

    // range check
    if ($periodFrom > $periodTo) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    // prevent duplicates by email or staff_id
    // we store emails lowercased on insert so checking 'email' is OK
    if ($this->userModel->where('email', $email)->first()) {
        $msg = 'An account with that email already exists. Please login.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg,'redirect'=>site_url('login')])->setStatusCode(409);
        }
        return redirect()->to('/login')->with('success', $msg);
    }

    if ($this->userModel->where('staff_id', $staffId)->first()) {
        $msg = 'An account with that Staff ID already exists. Please contact ICt.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(409);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    // prepare insert
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $insert = [
        'staff_id' => $staffId,
        'fullname'  => $fullname,
        'email'     => $email,
        'password'  => $hash,
        'phone'     => $phone,
        'category'  => $category,
        'period_from' => $periodFrom,
        'period_to'   => $periodTo,
        'verify_token' => bin2hex(random_bytes(24)),
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

    // fetch created user and sanity-check
    $user = $this->userModel->find((int)$userId);
    if (! $user) {
        // attempt to cleanup (best-effort)
        try { $this->userModel->delete($userId); } catch (\Throwable $_) {}
        $msg = 'Account created but could not log you in. Contact admin.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(500);
        }
        return redirect()->back()->with('errors', [$msg]);
    }

    // set a short-lived tempdata for the toast on the next page
    $this->session->setTempdata('just_registered', true, 5); // 5 seconds

    // auto-login (set session, regenerate)
    $this->session->set([
        'isLoggedIn' => true,
        'user_id' => $user['id'],
        'email' => $user['email'],
        'fullname' => $user['fullname'] ?? $user['email'],
        'category' => $user['category'] ?? $category,
    ]);
    $this->session->regenerate();

    // decide next route based on category (customize routes as you have them)
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

    // Add redirect delay for UX (milliseconds)
    $redirectDelay = 1500;

    // For non-AJAX: keep the redirect info in tempdata so the next page can show toast then redirect after delay
    if (! $this->request->isAJAX()) {
        $this->session->setTempdata('post_register_redirect', $next, 10);
        $this->session->setTempdata('post_register_redirect_delay', $redirectDelay, 10);
        // normal redirect (the page the user lands on should read the tempdata and show the toast + effect)
        return redirect()->to($next)->with('success', 'Registration successful. Redirecting to profile setup...');
    }

    // AJAX response: include redirectDelay for client-side JS to wait before redirecting
    $msg = 'Registration successful. Redirecting to profile setup...';
    return $this->response->setJSON([
        'success' => true,
        'message' => $msg,
        'redirect' => $next,
        'redirectDelay' => $redirectDelay
    ])->setStatusCode(200);
}




     public function saveRegistration2222()
{
    // validation rules
    $rules = [
        'phone' => 'permit_empty|max_length[50]',
        // allow senior_non_academic as well
        'category' => 'required|in_list[academic,senior_non_academic,non_academic,junior_non_academic]',
        'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
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

    // prevent double-registration
    if ($this->userModel->where('LOWER(email)', trim(strtolower($staff['email'])))->first()) {
        $this->session->removeTempdata('register_staff_id');
        $msg = 'Account already exists. Please login.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'already_registered' => true,
                'message' => $msg,
                'redirect' => site_url('login')
            ])->setStatusCode(200);
        }
        return redirect()->to('/login')->with('success', $msg);
    }

    // collect inputs
    $phone = $this->request->getPost('phone') ?: null;
    $category = $this->request->getPost('category') ?: null;
    $period_from = (int) $this->request->getPost('period_from');
    $period_to   = (int) $this->request->getPost('period_to');
    $password = $this->request->getPost('password');

    if ($period_from > $period_to) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $verifyToken = bin2hex(random_bytes(32));
    $staffFullname = $staff['fullname'] ?? $staff['name'] ?? ($staff['staff_number'] ?? null);

    $insertData = [
        'staff_id' => $staff['id'],
        'fullname' => $staffFullname,
        'email' => $staff['email'],
        'password' => $hash,
        // role intentionally omitted so admin can assign later
        'phone' => $phone,
        'category' => $category,
        'period_from' => $period_from,
        'period_to' => $period_to,
        'verify_token' => $verifyToken,
        'email_verified_at' => null,
    ];

    // Insert user and handle errors
    try {
        $this->userModel->insert($insertData);
        $userId = $this->userModel->getInsertID();
    } catch (\Throwable $e) {
        log_message('error', 'saveRegistration insert failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        $msg = (ENVIRONMENT === 'development') ? 'Server error while creating account: ' . $e->getMessage() : 'Server error while creating account';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    // fetch the created user row to use when setting session
    $user = $this->userModel->find((int)$userId);
    if (! $user) {
        // attempt cleanup: delete the inserted user to avoid orphan record
        try { $this->userModel->delete((int)$userId); } catch (\Throwable $_) {}
        $msg = 'Account created but login failed.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
        }
        return redirect()->to('/login')->with('success', 'Account created. Please login.');
    }

    // clear temp session and set transient flag for wizard
    $this->session->removeTempdata('register_staff_id');
    $showSeconds = 5;
    $this->session->setTempdata('just_registered', true, $showSeconds);

    // auto-login: set session using the freshly fetched $user
    $this->session->set([
        'isLoggedIn' => true,
        'user_id'    => $user['id'],
        'fullname'   => $user['fullname'] ?? $user['name'] ?? $user['email'],
        'email'      => $user['email'],
        'category'   => $user['category'] ?? $category,
    ]);
    // regenerate session id after login
    $this->session->regenerate();

    // redirect to the profile wizard (or first-stage)
    $redirectUrl = site_url('profile/wizard'); // adjust to profile/first-stage if you prefer
    $msg = 'Registration successful. Redirecting to profile setup...';

    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success' => true, 'message' => $msg, 'redirect' => $redirectUrl])->setStatusCode(200);
    }

    return redirect()->to($redirectUrl)->with('success', $msg);
}



     public function saveRegistration11()
{
    $rules = [
        'phone' => 'permit_empty|max_length[50]',
        'category' => 'required|alpha_dash',
        'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
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

    // ensure session temp staff id exists
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

    // prevent race double-registration
    if ($this->userModel->where('LOWER(email)', trim(strtolower($staff['email'])))->first()) {
        $this->session->removeTempdata('register_staff_id');
        $msg = 'Account already exists. Please login.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'already_registered' => true,
                'message' => $msg,
                'redirect' => site_url('login')
            ])->setStatusCode(200);
        }
        return redirect()->to('/login')->with('success', $msg);
    }

    // inputs
    $phone = $this->request->getPost('phone') ?: null;
    $category = $this->request->getPost('category');
    $period_from = (int) $this->request->getPost('period_from');
    $period_to   = (int) $this->request->getPost('period_to');
    $password = $this->request->getPost('password');

    // ensure valid period range
    if ($period_from > $period_to) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $verifyToken = bin2hex(random_bytes(32));

    // be tolerant about staff name key (fullname vs name)
    $staffFullname = $staff['fullname'] ?? $staff['name'] ?? ($staff['staff_number'] ?? 'Staff');

    $insertData = [
        'staff_id' => $staff['id'],
        'fullname' => $staffFullname,
        'email'    => $staff['email'],
        'password' => $hash,
        'role'     => 'staff',
        'phone'    => $phone,
        'category' => $category,
        'period_from' => $period_from,
        'period_to' => $period_to,
        'verify_token' => $verifyToken,
        'email_verified_at' => null,
    ];

    try {
        $this->userModel->insert($insertData);
        $userId = $this->userModel->getInsertID();
    } catch (\Throwable $e) {
        log_message('error', 'saveRegistration insert failed: ' . $e->getMessage());
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Server error while creating account.'])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', ['Server error while creating account.']);
    }

    // clear temp session
    $this->session->removeTempdata('register_staff_id');

    // ----------------------------
    // AUTO-LOGIN (set session, redirect to dashboard)
    // ----------------------------
    // fetch the inserted user row (optional but useful to get full record)
    $user = $this->userModel->find($userId);
    if (! $user) {
        // extremely unlikely, but handle gracefully
        $msg = 'Account created but login failed. Please login manually.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => $msg, 'redirect' => site_url('login')])->setStatusCode(200);
        }
        return redirect()->to('/login')->with('success', $msg);
    }

    // set session data (minimal required)
    $this->session->set([
        'isLoggedIn' => true,
        'user_id'    => $user['id'],
        'fullname'   => $user['fullname'] ?? $user['name'] ?? $user['email'],
        'email'      => $user['email'],
        'role'       => $user['role'] ?? 'staff',
    ]);

    // regenerate session id to prevent fixation
    $this->session->regenerate();

    // respond (AJAX or normal)
    $msg = 'Registration complete. Welcome!';
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success' => true, 'message' => $msg, 'redirect' => site_url('dashboard')])->setStatusCode(200);
    }

    return redirect()->to('/dashboard')->with('success', $msg);
}




     public function saveRegistration1221()
{
    $rules = [
        'phone' => 'permit_empty|max_length[50]',
        'category' => 'required|alpha_dash',
        'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // ensure session temp staff id exists
    $staffId = $this->session->getTempdata('register_staff_id');
    if (! $staffId) {
        $msg = 'Session expired. Please verify your email again.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(401);
        }
        return redirect()->to('/check-email')->with('errors', [$msg]);
    }

    $staff = $this->staffModel->find((int)$staffId);
    if (! $staff) {
        $this->session->removeTempdata('register_staff_id');
        $msg = 'Staff record not found.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(404);
        }
        return redirect()->to('/check-email')->with('errors', [$msg]);
    }

    // prevent race double-registration
    if ($this->userModel->where('LOWER(email)', trim(strtolower($staff['email'])))->first()) {
        $this->session->removeTempdata('register_staff_id');
        $msg = 'Account already exists. Please login.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'already_registered'=>true,'message'=>$msg,'redirect'=>site_url('login')])->setStatusCode(200);
        }
        return redirect()->to('/login')->with('success', $msg);
    }

    // inputs
    $phone = $this->request->getPost('phone') ?: null;
    $category = $this->request->getPost('category');
    $period_from = (int) $this->request->getPost('period_from');
    $period_to   = (int) $this->request->getPost('period_to');
    $password = $this->request->getPost('password');

    // ensure valid period range
    if ($period_from > $period_to) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', [$msg]);
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $verifyToken = bin2hex(random_bytes(32));

    $insertData = [
        'staff_id' => $staff['id'],
        'fullname'     => $staff['fullname'],
        'email'    => $staff['email'],
        'password' => $hash,
        'role'     => 'staff',
        'phone'    => $phone,
        'category' => $category,
        'period_from' => $period_from,
        'period_to' => $period_to,
        'verify_token' => $verifyToken,
        'email_verified_at' => null,
    ];

    try {
        $this->userModel->insert($insertData);
        $userId = $this->userModel->getInsertID();
    } catch (\Throwable $e) {
        log_message('error', 'saveRegistration insert failed: ' . $e->getMessage());
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'message'=>'Server error while creating account.'])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', ['Server error while creating account.']);
    }

    // clear temp session
    $this->session->removeTempdata('register_staff_id');

    // Optionally send verification email here (uncomment when SMTP configured)

    $msg = 'Registration successful. Please login to continue.';
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>$msg,'redirect'=>site_url('login')])->setStatusCode(200);
    }

    return redirect()->to('/login')->with('success', $msg);
}

     public function saveRegistration2()
{
    $rules = [
        'phone' => 'permit_empty|max_length[50]',
        'category' => 'required|alpha_dash',
        'period_from' => 'required|valid_date',
        'period_to' => 'required|valid_date',
        'password' => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // session temp staff id used earlier in checkEmail()
    $staffId = $this->session->getTempdata('register_staff_id');
    if (! $staffId) {
        return redirect()->to('/check-email')->with('errors', ['Session expired. Please verify your email again.']);
    }

    $staff = $this->staffModel->find((int)$staffId);
    if (! $staff) {
        $this->session->remove('register_staff_id');
        return redirect()->to('/check-email')->with('errors', ['Staff record not found.']);
    }

    // ensure not already registered
    if ($this->userModel->where('LOWER(email)', trim(strtolower($staff['email'])))->first()) {
        $this->session->remove('register_staff_id');
        return redirect()->to('/check-email')->with('success', 'Account already exists. Please login.');
    }

    // collect sanitized inputs
    $phone = $this->request->getPost('phone');
    $category = $this->request->getPost('category');
    $period_from = $this->request->getPost('period_from');
    $period_to = $this->request->getPost('period_to');

    $password = $this->request->getPost('password');
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // create a random verify token (store even if you auto-login)
    $verifyToken = bin2hex(random_bytes(32));

    // insert user (you can wrap in transaction if doing more operations)
    $insertData = [
        'staff_id' => $staff['id'],
        'fullname'     => $staff['fullname'],
        'email'    => $staff['email'],
        'password' => $hash,
        'role'     => 'staff',
        'phone'    => $phone ?: null,
        'category' => $category,
        'period_from' => $period_from,
        'period_to' => $period_to,
        'verify_token' => $verifyToken,
        'email_verified_at' => null,
    ];

    $this->userModel->insert($insertData);
    $userId = $this->userModel->getInsertID();

    // Optionally send verification email (enable SMTP and uncomment)
    /*
    $emailService = \Config\Services::email();
    $emailService->setFrom('no-reply@aper.test','APER System');
    $emailService->setTo($staff['email']);
    $emailService->setSubject('Verify your APER account');
    $message = view('emails/verify_email', ['fullname'=>$staff['fullname'], 'token'=>$verifyToken]);
    $emailService->setMessage($message);
    $emailService->send();
    */

    // Auto-login: set session values
    $this->session->set([
        'isLoggedIn' => true,
        'user_id' => $userId,
        'fullname' => $staff['fullname'],
        'email' => $staff['email'],
        'role' => 'staff',
    ]);

    // Regenerate session id and clear tempdata
    $this->session->regenerate();
    $this->session->removeTempdata('register_staff_id');

    // Redirect to dashboard
    return redirect()->to('/dashboard')->with('success', 'Registration complete. Welcome!');
}



    public function verify($token = null)
{
    if (! $token) {
        return redirect()->to('/')->with('error', 'Invalid verification link.');
    }

    $user = $this->userModel->where('verify_token', $token)->first();
    if (! $user) {
        return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
    }

    // mark verified
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
        'role' => $user['role'],
    ]);

    return redirect()->to('/dashboard')->with('success', 'Email verified. Welcome!');
}


    public function verify1($token = null)
{
    if (! $token) {
        return redirect()->to('/')->with('error', 'Invalid verification link.');
    }

    $user = $this->userModel->where('verify_token', $token)->first();
    if (! $user) {
        return redirect()->to('/')->with('error', 'Invalid or expired verification token.');
    }

    // mark verified
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
        'role' => $user['role'],
    ]);

    return redirect()->to('/dashboard')->with('success', 'Email verified. Welcome!');
}

}
