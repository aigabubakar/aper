<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AcademicProfileModel;
use App\Models\SeniorNonAcademicProfileModel;
use App\Models\JuniorNonAcademicProfileModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;
use CodeIgniter\Controller;

class ProfileController extends BaseController
{
    protected $userModel;
    protected $academicModel;
    protected $seniorModel;
    protected $juniorModel;
    protected $facultyModel;
    protected $departmentModel;
    protected $session;
    protected $user;
    

    public function __construct()
    {
        helper(['form','url']);
        $this->userModel = new UserModel();
        $this->academicModel = new AcademicProfileModel();
        $this->seniorModel = new SeniorNonAcademicProfileModel();
        $this->juniorModel = new JuniorNonAcademicProfileModel();
        $this->facultyModel = new FacultyModel();
        $this->departmentModel = new DepartmentModel();
        $this->session = session();
        $uid = (int)$this->session->get('user_id');
        $this->user = $uid ? $this->userModel->find($uid) : null;
    }

    protected function requireLogin()
    {
        if (! $this->session->get('isLoggedIn') || ! $this->user) {
            return redirect()->to('/login')->with('errors', ['Please login to continue.']);
        }
        return null;
    }

    protected function userCategory()
    {
        // category stored in users.category or session
        return $this->user['category'] ?? $this->session->get('category') ?? 'non_academic';
    }

    protected function categoryModel()
    {
        $cat = $this->userCategory();
        if ($cat === 'academic') return $this->academicModel;
        if ($cat === 'senior_non_academic') return $this->seniorModel;
        if ($cat === 'junior_non_academic') return $this->juniorModel;
        // fallback: treat as senior non-academic
        return $this->seniorModel;
    }

    protected function getOrCreateProfile()
    {
        $model = $this->categoryModel();
        $profile = $model->where('user_id', $this->user['id'])->first();
        if (! $profile) {
            $model->insert(['user_id' => $this->user['id']]);
            $profile = $model->find($model->getInsertID());
        }
        return $profile;
    }

    // First stage form (collect faculty/department/period)
    public function firstStage()
    {
        if ($r = $this->requireLogin()) return $r;

        $profile = $this->getOrCreateProfile();
        $faculties = $this->facultyModel->findAll();
        // optionally filter departments by selected faculty
        $departments = $this->departmentModel->findAll();

        return view('profile/first_stage', [
            'user' => $this->user,
            'profile' => $profile,
            'faculties' => $faculties,
            'departments' => $departments,
            'category' => $this->userCategory(),
        ]);
    }

    // Save first stage
    public function saveFirstStage()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405);
        }
        if ($r = $this->requireLogin()) return $r;

        $rules = [
            'faculty_id' => 'permit_empty|integer',
            'department_id' => 'permit_empty|integer',
            'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
            'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors',$errors);
        }

        $pf = (int)$this->request->getPost('period_from');
        $pt = (int)$this->request->getPost('period_to');
        if ($pf > $pt) {
            $msg = 'Reporting period start year cannot be greater than end year.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors',[$msg]);
        }

        $data = [
            'faculty_id' => $this->request->getPost('faculty_id') ?: null,
            'department_id' => $this->request->getPost('department_id') ?: null,
            'period_from' => $pf,
            'period_to' => $pt,
        ];

        $model = $this->categoryModel();
        try {
            $profile = $model->where('user_id', $this->user['id'])->first();
            if ($profile) {
                $model->update($profile['id'], $data);
            } else {
                $data['user_id'] = $this->user['id'];
                $model->insert($data);
            }

            // mark completed_profile in users if you want to treat firstStage as "done"
            // $this->userModel->update($this->user['id'], ['completed_profile' => 1]);

            $redirect = site_url($this->nextStagePath());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$redirect])->setStatusCode(200);
            }
            return redirect()->to($redirect)->with('success','Saved');
        } catch (\Throwable $e) {
            log_message('error','saveFirstStage error: '.$e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success'=>false,'message'=>'Server error while saving'])->setStatusCode(500);
            }
            return redirect()->back()->with('errors',['Server error while saving']);
        }
    }

    // choose next stage path based on category
    protected function nextStagePath()
    {
        $cat = $this->userCategory();
        return match ($cat) {
            'academic' => 'profile/academic/employment',
            'senior_non_academic' => 'profile/senior/employment',
            'junior_non_academic' => 'profile/junior/employment',
            default => 'profile/overview',
        };
    }
/////////////////////////////////////////////////////////////////



    /**
 * Render Senior Non-Academic personal form (first stage for senior non-academic staff)
 */
public function seniorPersonal()
{
    // require login
    if (! session()->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        session()->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // ensure user category matches or let them still access (you can enforce stricter check)
    if (($user['category'] ?? '') !== 'senior_non_academic') {
        // optional: redirect them to correct category form
        // return redirect()->to(site_url('profile/'.$user['category'].'/personal'));
    }

    // load lookup data
    $faculties = (new FacultyModel())->findAll();
    $departments = (new DepartmentModel())->findAll();

    return view('profile/senior/personal', [
        'user' => $user,
        'faculties' => $faculties,
        'departments' => $departments,
    ]);
}

/**
 * POST handler to save Senior personal form (AJAX + normal POST)
 */



 public function saveSeniorPersonal()
{
    // only POST
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    // must be logged in
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['success'=>false,'message'=>'User not found'])->setStatusCode(404);
    }

    // explicit validation rules
    $rules = [
        'phone' => 'permit_empty|max_length[80]',
        'dob' => 'permit_empty|valid_date',
        'gender' => 'permit_empty|in_list[male,female,other]',
        'department' => 'permit_empty|max_length[255]',
        'designation' => 'permit_empty|max_length[255]',
        'grade_level' => 'permit_empty|max_length[80]',
        'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'faculty' => 'permit_empty|integer',
        'department' => 'permit_empty|integer',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning', 'saveSeniorPersonal validation failed for user '.$userId.': '.json_encode($errors));
        return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
    }

    $periodFrom = (int) $this->request->getPost('period_from');
    $periodTo = (int) $this->request->getPost('period_to');
    if ($periodFrom > $periodTo) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
    }

    // Fields we intend to update
    $update = [
        'phone' => $this->request->getPost('phone') ?: null,
        'dob' => $this->request->getPost('dob') ?: null,
        'gender' => $this->request->getPost('gender') ?: null,
        'department' => $this->request->getPost('department') ?: null,
        'designation' => $this->request->getPost('designation') ?: null,
        'grade_level' => $this->request->getPost('grade_level') ?: null,
        'period_from' => $periodFrom,
        'period_to' => $periodTo,
        'faculty' => $this->request->getPost('faculty_id') ?: null,
        'department' => $this->request->getPost('department_id') ?: null,
    ];

    // sanity: check DB columns exist (helps detect migrations mismatch)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveSeniorPersonal DB connect failed: '.$e->getMessage());
        return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }
    if (! empty($missing)) {
        $m = implode(', ', $missing);
        log_message('error','saveSeniorPersonal missing columns in users table: '.$m);
        return $this->response->setJSON([
            'success'=>false,
            'message'=>'Database schema mismatch: missing columns: '.$m,
            'missing_columns'=>$missing
        ])->setStatusCode(500);
    }

    // perform update inside try/catch and return full error details to logs
    try {
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveSeniorPersonal update failed for user '.$userId.': '.$e->getMessage()."\n".$e->getTraceAsString());
        return $this->response->setJSON([
            'success'=>false,
            'message'=>'Failed to save data',
            'detail' => ENVIRONMENT === 'development' ? $e->getMessage() : null
        ])->setStatusCode(500);
    }

    // success -> maybe mark profile progress (optional)
    // $this->userModel->update($userId, ['completed_profile' => 1]);

    $next = site_url('profile/senior/employment'); // adjust if needed
    return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next])->setStatusCode(200);
}

/**
 * Render Senior Non-Academic employment form (next stage after personal)
 */

 public function seniorEmployment()
{
         $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // load helper data if available (departments, faculties)
    $faculties = $this->facultyModel ? $this->facultyModel->findAll() : [];
    $departments = $this->departmentModel ? $this->departmentModel->findAll() : [];

    return view('profile/senior/employment', [
        'user' => $user,
        'faculties' => $faculties,
        'departments' => $departments,
    ]);
}

/**
 * POST /profile/junior/employment/save
 */
public function saveSeniorEmployment()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    // validation rules
    $rules = [
        'present_salary' => 'permit_empty|numeric',
        'contiss' => 'permit_empty|max_length[50]',
        'step' => 'permit_empty|max_length[50]',       
        'first_appointment_grade' => 'permit_empty|max_length[50]',
        'date_of_first_appointment' => 'permit_empty|valid_date',       
        'last_promotion_grade' => 'permit_empty|max_length[50]',
        'last_promotion_date' => 'permit_empty|valid_date',       
        'current_appointment_grade' => 'permit_empty|max_length[50]',
        'current_appointment_date' => 'permit_empty|valid_date',
        'appointment_confirmed' => 'permit_empty|in_list[0,1,yes,no]',
        'appointment_confirmed_at' => 'permit_empty|valid_date',
        
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning','saveSeniorEmployment validation failed: '.json_encode($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // business rules: normalize appointment_confirmed
    $raw = $this->request->getPost('appointment_confirmed');
    $apc = null;
    if ($raw === '1' || $raw === 'yes' || $raw === 'on') $apc = 1;
    elseif ($raw === '0' || $raw === 'no' || $raw === '') $apc = 0;

    $apcDate = $this->request->getPost('appointment_confirmed_at') ?: null;
    if ($apc === 1 && empty($apcDate)) {
        $err = ['appointment_confirmed_at' => 'Please provide confirmation date.'];
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$err])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors', $err);
    }
    if ($apc !== 1) $apcDate = null;

    // prepare update payload
    $update = [
        'present_salary' => $this->request->getPost('present_salary') !== '' ? $this->request->getPost('present_salary') : null,
        'contiss' => $this->request->getPost('contiss') ?: null,
        'step' => $this->request->getPost('step') ?: null,
        'date_of_first_appointment' => $this->request->getPost('date_of_first_appointment') ?: null,
        'first_appointment_grade' => $this->request->getPost('first_appointment_grade') ?: null,
        'last_promotion_date' => $this->request->getPost('last_promotion_date') ?: null,
        'last_promotion_grade' => $this->request->getPost('last_promotion_grade') ?: null,
        'current_appointment_date' => $this->request->getPost('current_appointment_date') ?: null,
        'current_appointment_grade' => $this->request->getPost('current_appointment_grade') ?: null,
        'appointment_confirmed' => $apc ?? 0,
        'appointment_confirmed_at' => $apcDate,
        
    ];

    // guard columns exist
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveSeniorEmployment DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error','saveSeniorEmployment missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'=>false,
                'message'=>'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns'=>$missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors',['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // update DB
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveSeniorEmployment update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Server error while saving.']);
    }

    // success - next step
    $next = site_url('profile/senior/qualifications');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next,'redirectDelay'=>1000])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Saved');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////



// Show Qualifications form
public function seniorQualifications()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/senior/qualifications', [
        'user' => $user,
    ]);
}

// POST handler to save Qualifications
public function saveSeniorQualifications()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success' => false, 'message' => 'Method not allowed'])->setStatusCode(405);
    }
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
    }

    // validation rules (each qualification entry is optional)
    $rules = [];
    for ($i = 1; $i <= 5; $i++) {
        $rules["qual{$i}"] = 'permit_empty|max_length[255]';               // qualification name
        $rules["qual{$i}_grade"] = 'permit_empty|max_length[50]';         // grade
        $rules["qual{$i}_institution"] = 'permit_empty|max_length[255]';  // institution
        $rules["qual{$i}_date"] = 'permit_empty|valid_date';             // date
    }
    for ($i = 1; $i <= 5; $i++) {
        $rules["prof_qual{$i}"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_body"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_date"] = 'permit_empty|valid_date';
    }

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // build payload
    $update = [];
    for ($i = 1; $i <= 5; $i++) {
        $update["qual{$i}"] = $this->request->getPost("qual{$i}") ?: null;
        $update["qual{$i}_grade"] = $this->request->getPost("qual{$i}_grade") ?: null;
        $update["qual{$i}_institution"] = $this->request->getPost("qual{$i}_institution") ?: null;
        $update["qual{$i}_date"] = $this->request->getPost("qual{$i}_date") ?: null;
    }
    for ($i = 1; $i <= 5; $i++) {
        $update["prof_qual{$i}"] = $this->request->getPost("prof_qual{$i}") ?: null;
        $update["prof_qual{$i}_body"] = $this->request->getPost("prof_qual{$i}_body") ?: null;
        $update["prof_qual{$i}_date"] = $this->request->getPost("prof_qual{$i}_date") ?: null;
    }

    // DB column guard (helpful error if migrations missing)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveSeniorQualifications DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }
    if (! empty($missing)) {
        log_message('error','saveSeniorQualifications missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns' => $missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', ['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // perform update
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveSeniorQualifications update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Server error while saving.']);
    }

    $next = site_url('profile/senior/experience');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Qualifications saved','redirect'=>$next,'redirectDelay'=>900])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Qualifications saved');
}
/**


**
 * Render Senior Non-Academic — Qualifications page
 */
public function seniorQualifications1()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // optionally ensure category
    // if (($user['category'] ?? '') !== 'senior_non_academic') { ... }

    return view('profile/senior/qualifications', [
        'user' => $user,
    ]);
}

/**
 * Save Senior Non-Academic — Qualifications (AJAX + POST)
 */
public function saveSeniorQualifications1()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success' => false, 'message' => 'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['success' => false, 'message' => 'User not found'])->setStatusCode(404);
    }

    // validation rules (adjust lengths to taste)
    $rules = [
        'publications' => 'permit_empty|max_length[4000]',
        'dissertation' => 'permit_empty|max_length[4000]',
        'articles' => 'permit_empty|max_length[4000]',
        'books_monographs' => 'permit_empty|max_length[4000]',
        'number_pub_accepted' => 'permit_empty|integer',
        'number_of_points' => 'permit_empty|integer',
        'postgraduate_supervisor' => 'permit_empty|in_list[yes,no]',
        'participation' => 'permit_empty|max_length[2000]',
        'other_remark' => 'permit_empty|max_length[2000]',
    ];

    if (! $this->validate($rules)) {
        return $this->response->setJSON(['success' => false, 'errors' => $this->validator->getErrors()])->setStatusCode(422);
    }

    // collect updates
    $update = [
        'publications' => $this->request->getPost('publications') ?: null,
        'dissertation' => $this->request->getPost('dissertation') ?: null,
        'articles' => $this->request->getPost('articles') ?: null,
        'books_monographs' => $this->request->getPost('books_monographs') ?: null,
        'number_pub_accepted' => $this->request->getPost('number_pub_accepted') ?: null,
        'number_of_points' => $this->request->getPost('number_of_points') ?: null,
        'postgraduate_supervisor' => $this->request->getPost('postgraduate_supervisor') ?: null,
        'participation' => $this->request->getPost('participation') ?: null,
        'other_remark' => $this->request->getPost('other_remark') ?: null,
    ];

    // ensure DB columns exist — quick guard (returns helpful JSON on mismatch)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error', 'saveSeniorQualifications DB error: '.$e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'Database error'])->setStatusCode(500);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error', 'saveSeniorQualifications missing columns: '.implode(',',$missing));
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Database schema mismatch: missing columns: '.implode(', ',$missing),
            'missing_columns' => $missing
        ])->setStatusCode(500);
    }

    try {
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error', 'saveSeniorQualifications update failed for user '.$userId.': '.$e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save data'])->setStatusCode(500);
    }

    // next stage
    $next = site_url('profile/senior/experience'); // change as appropriate

    return $this->response->setJSON(['success' => true, 'message' => 'Saved', 'redirect' => $next])->setStatusCode(200);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Senior Non-Academic — Experience (GET)
 */
public function seniorExperience()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/senior/experience', [
        'user' => $user,
    ]);
}

/**
 * Senior Non-Academic — Save Experience (POST, AJAX+normal)
 */
 public function saveSeniorExperience()
{
    // Debug mode: verbose output
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['ok'=>false,'msg'=>'Method not allowed','method'=>$this->request->getMethod()])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['ok'=>false,'msg'=>'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['ok'=>false,'msg'=>'User not found'])->setStatusCode(404);
    }

    // collect input (no validation to let us see raw)
    $update = [
        'exp_out_institution_name1' => $this->request->getPost('exp_out_institution_name1'),
        'exp_out_designation1' => $this->request->getPost('exp_out_designation1'),
        'exp_out_specialization1' => $this->request->getPost('exp_out_specialization1'),
        'exp_out_date1' => $this->request->getPost('exp_out_date1'),
        'exp_out_institution_name2' => $this->request->getPost('exp_out_institution_name2'),
        'exp_out_designation2' => $this->request->getPost('exp_out_designation2'),
        'exp_out_specialization2' => $this->request->getPost('exp_out_specialization2'),
        'exp_out_date2' => $this->request->getPost('exp_out_date2'),
        'professional_experience' => $this->request->getPost('professional_experience'),
    ];

    // Log incoming data
    log_message('debug','DEBUG saveSeniorExperience called for user '.$userId.' payload: '.json_encode($update));

    // DB columns
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','DEBUG DB connect failed: '.$e->getMessage());
        return $this->response->setJSON(['ok'=>false,'msg'=>'DB connect failed','error'=>$e->getMessage()])->setStatusCode(500);
    }

    // compute missing
    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }

    // Attempt update but catch any exception
    $updateResult = null;
    $error = null;
    try {
        $this->userModel->update($userId, $update);
        $updateResult = $this->userModel->find($userId);
    } catch (\Throwable $e) {
        $error = $e->getMessage();
        log_message('error','DEBUG update failed: '.$e->getMessage()."\n".$e->getTraceAsString());
    }

    // Build verbose JSON to inspect client-side
    $resp = [
        'ok' => $error === null,
        'message' => $error ? 'Update failed' : 'Update attempted',
        'missing_columns' => $missing,
        'posted' => $update,
        'db_columns_sample' => array_values(array_slice($cols, 0, 40)),
        'update_result_sample' => $updateResult ? array_intersect_key($updateResult, array_flip(array_keys($update))) : null,
        'error' => $error,
        // include the redirect so your client still can read it
        'redirect' => site_url('profile/success'),
        'redirectDelay' => 1200,
    ];

    return $this->response->setJSON($resp)->setStatusCode($error ? 500 : 200);
}

///////////////////////////////////////////  Academic Section    ////////////////////////////////////////////////

// inside App\Controllers\ProfileController 

public function academicPersonal()
{
    // require login
    if (! session()->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        session()->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // ensure user category matches or let them still access (you can enforce stricter check)
    if (($user['category'] ?? '') !== 'academic') {
        // optional: redirect them to correct category form
        // return redirect()->to(site_url('profile/'.$user['category'].'/personal'));
    }

    // load lookup data
    $faculties = (new FacultyModel())->findAll();
    $departments = (new DepartmentModel())->findAll();

    return view('profile/academic/personal', [
        'user' => $user,
        'faculties' => $faculties,
        'departments' => $departments,
    ]);
}

/**
 * POST handler to save Senior personal form (AJAX + normal POST)
 */

 public function saveAcademicPersonal()
{
    // only POST
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    // must be logged in
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['success'=>false,'message'=>'User not found'])->setStatusCode(404);
    }

    // explicit validation rules
    $rules = [
        'phone' => 'permit_empty|max_length[80]',
        'dob' => 'permit_empty|valid_date',
        'gender' => 'permit_empty|in_list[male,female,other]',
        'department' => 'permit_empty|max_length[255]',
        'designation' => 'permit_empty|max_length[255]',
        'grade_level' => 'permit_empty|max_length[80]',
        'period_from' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to' => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'faculty' => 'permit_empty|integer',
        'department' => 'permit_empty|integer',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning', 'saveSeniorPersonal validation failed for user '.$userId.': '.json_encode($errors));
        return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
    }

    $periodFrom = (int) $this->request->getPost('period_from');
    $periodTo = (int) $this->request->getPost('period_to');
    if ($periodFrom > $periodTo) {
        $msg = 'Reporting period start year cannot be greater than end year.';
        return $this->response->setJSON(['success'=>false,'message'=>$msg])->setStatusCode(422);
    }

    // Fields we intend to update
    $update = [
        'phone' => $this->request->getPost('phone') ?: null,
        'dob' => $this->request->getPost('dob') ?: null,
        'gender' => $this->request->getPost('gender') ?: null,
        'department' => $this->request->getPost('department') ?: null,
        'designation' => $this->request->getPost('designation') ?: null,
        'grade_level' => $this->request->getPost('grade_level') ?: null,
        'period_from' => $periodFrom,
        'period_to' => $periodTo,
        'faculty' => $this->request->getPost('faculty_id') ?: null,
        'department' => $this->request->getPost('department_id') ?: null,
    ];

    // sanity: check DB columns exist (helps detect migrations mismatch)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveacAdemicPersonal DB connect failed: '.$e->getMessage());
        return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }
    if (! empty($missing)) {
        $m = implode(', ', $missing);
        log_message('error','saveacAdemicPersonal missing columns in users table: '.$m);
        return $this->response->setJSON([
            'success'=>false,
            'message'=>'Database schema mismatch: missing columns: '.$m,
            'missing_columns'=>$missing
        ])->setStatusCode(500);
    }

    // perform update inside try/catch and return full error details to logs
    try {
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveacAdemicPersonal update failed for user '.$userId.': '.$e->getMessage()."\n".$e->getTraceAsString());
        return $this->response->setJSON([
            'success'=>false,
            'message'=>'Failed to save data',
            'detail' => ENVIRONMENT === 'development' ? $e->getMessage() : null
        ])->setStatusCode(500);
    }

    // success -> maybe mark profile progress (optional)
    // $this->userModel->update($userId, ['completed_profile' => 1]);

    $next = site_url('profile/academic/employment'); // adjust if needed
    return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next])->setStatusCode(200);
}

// Render and POST handler for employment history (Academic)
public function academicEmployment()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // load faculties / departments for selects (best effort)
    try {
        $facultyModel = new \App\Models\FacultyModel();
        $departmentModel = new \App\Models\DepartmentModel();
        $faculties = $facultyModel->findAll();
        $departments = $departmentModel->where('faculty_id', $user['faculty_id'] ?? 0)->findAll();
    } catch (\Throwable $e) {
        log_message('error','academicEmployment: faculty/department model load failed: '.$e->getMessage());
        $faculties = [];
        $departments = [];
    }

    return view('profile/academic/employment', [
        'user' => $user,
        'faculties' => $faculties,
        'departments' => $departments,
    ]);
}

public function saveAcademicEmployment()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    // validation rules
    $rules = [
        'present_salary' => 'permit_empty|numeric',
        'contiss' => 'permit_empty|max_length[50]',
        'step' => 'permit_empty|max_length[50]',
        'first_appointment_date' => 'permit_empty|valid_date',
        'first_appointment_grade' => 'permit_empty|max_length[50]',
        'last_promotion_date' => 'permit_empty|valid_date',
        'last_promotion_grade' => 'permit_empty|max_length[50]',
        'current_appointment_date' => 'permit_empty|valid_date',
        'current_appointment_grade' => 'permit_empty|max_length[50]',
        'appointment_confirmed' => 'permit_empty|in_list[0,1,yes,no]',
        'appointment_confirmed_at' => 'permit_empty|valid_date',
        
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning','saveAcademicEmployment validation failed: '.json_encode($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // business rules: normalize appointment_confirmed
    $raw = $this->request->getPost('appointment_confirmed');
    $apc = null;
    if ($raw === '1' || $raw === 'yes' || $raw === 'on') $apc = 1;
    elseif ($raw === '0' || $raw === 'no' || $raw === '') $apc = 0;

    $apcDate = $this->request->getPost('appointment_confirmed_at') ?: null;
    if ($apc === 1 && empty($apcDate)) {
        $err = ['appointment_confirmed_at' => 'Please provide confirmation date.'];
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$err])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors', $err);
    }
    if ($apc !== 1) $apcDate = null;

    // prepare update payload
    $update = [
        'present_salary' => $this->request->getPost('present_salary') !== '' ? $this->request->getPost('present_salary') : null,
        'contiss' => $this->request->getPost('contiss') ?: null,
        'step' => $this->request->getPost('step') ?: null,
        'date_of_first_appointment' => $this->request->getPost('first_appointment_date') ?: null,
        'first_appointment_grade' => $this->request->getPost('first_appointment_grade') ?: null,
        'last_promotion_date' => $this->request->getPost('last_promotion_date') ?: null,
        'last_promotion_grade' => $this->request->getPost('last_promotion_grade') ?: null,
        'current_appointment_date' => $this->request->getPost('current_appointment_date') ?: null,
        'current_appointment_grade' => $this->request->getPost('current_appointment_grade') ?: null,
        'appointment_confirmed' => $apc ?? 0,
        'appointment_confirmed_at' => $apcDate,
        
    ];

    // guard columns exist
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveAcademicEmployment DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error','saveAcademicEmployment missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'=>false,
                'message'=>'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns'=>$missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors',['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // update DB
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicEmployment update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Server error while saving.']);
    }

    // success - next step
    $next = site_url('profile/academic/qualifications');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next,'redirectDelay'=>1000])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Saved');
}

// Show Qualifications form
public function academicQualifications()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/academic/qualifications', [
        'user' => $user,
    ]);
}

// POST handler to save Qualifications
public function saveAcademicQualifications()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success' => false, 'message' => 'Method not allowed'])->setStatusCode(405);
    }
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
    }

    // validation rules (each qualification entry is optional)
    $rules = [];
    for ($i = 1; $i <= 5; $i++) {
        $rules["qual{$i}"] = 'permit_empty|max_length[255]';               // qualification name
        $rules["qual{$i}_grade"] = 'permit_empty|max_length[50]';         // grade
        $rules["qual{$i}_institution"] = 'permit_empty|max_length[255]';  // institution
        $rules["qual{$i}_date"] = 'permit_empty|valid_date';             // date
    }
    for ($i = 1; $i <= 5; $i++) {
        $rules["prof_qual{$i}"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_body"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_date"] = 'permit_empty|valid_date';
    }

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // build payload
    $update = [];
    for ($i = 1; $i <= 5; $i++) {
        $update["qual{$i}"] = $this->request->getPost("qual{$i}") ?: null;
        $update["qual{$i}_grade"] = $this->request->getPost("qual{$i}_grade") ?: null;
        $update["qual{$i}_institution"] = $this->request->getPost("qual{$i}_institution") ?: null;
        $update["qual{$i}_date"] = $this->request->getPost("qual{$i}_date") ?: null;
    }
    for ($i = 1; $i <= 5; $i++) {
        $update["prof_qual{$i}"] = $this->request->getPost("prof_qual{$i}") ?: null;
        $update["prof_qual{$i}_body"] = $this->request->getPost("prof_qual{$i}_body") ?: null;
        $update["prof_qual{$i}_date"] = $this->request->getPost("prof_qual{$i}_date") ?: null;
    }

    // DB column guard (helpful error if migrations missing)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveAcademicQualifications DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }
    if (! empty($missing)) {
        log_message('error','saveAcademicQualifications missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns' => $missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', ['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // perform update
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicQualifications update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Server error while saving.']);
    }

    $next = site_url('profile/academic/experience');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Qualifications saved','redirect'=>$next,'redirectDelay'=>900])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Qualifications saved');
}

public function academicQualifications1()
{
    $session = session(); if (! $session->get('isLoggedIn')) return redirect()->to('/login');
    $user = $this->userModel->find((int)$session->get('user_id'));
    return view('profile/academic/qualifications',['user'=>$user]);
}

public function saveAcademicQualifications1()
{
    if ($this->request->getMethod() !== 'post') return $this->response->setStatusCode(405);
    if (! session()->get('isLoggedIn')) return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);

    $rules = [
      'publications' => 'permit_empty',
      'dissertation' => 'permit_empty',
      'articles' => 'permit_empty',
      'books_monographs' => 'permit_empty',
      'number_pub_accepted' => 'permit_empty|integer',
      'number_of_points' => 'permit_empty|integer',
      'other_remark' => 'permit_empty|max_length[2000]',
    ];
    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors',$errors);
    }

    $userId = (int) session()->get('user_id');
    $update = [
      'publications' => $this->request->getPost('publications') ?: null,
      'dissertation' => $this->request->getPost('dissertation') ?: null,
      'articles' => $this->request->getPost('articles') ?: null,
      'books_monographs' => $this->request->getPost('books_monographs') ?: null,
      'number_pub_accepted' => $this->request->getPost('number_pub_accepted') ?: null,
      'number_of_points' => $this->request->getPost('number_of_points') ?: null,
      'other_remark' => $this->request->getPost('other_remark') ?: null,
    ];

    try { $cols = \Config\Database::connect()->getFieldNames('users'); } catch(\Throwable $e) { return $this->response->setJSON(['success'=>false,'message'=>'DB error'])->setStatusCode(500); }
    $missing = array_filter(array_keys($update), fn($c)=> ! in_array($c,$cols));
    if ($missing) return $this->response->setJSON(['success'=>false,'message'=>'Database schema mismatch','missing_columns'=>$missing])->setStatusCode(500);

    try {
        $this->userModel->update($userId,$update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicQualifications: '.$e->getMessage());
        return $this->response->setJSON(['success'=>false,'message'=>'Failed to save'])->setStatusCode(500);
    }

    $next = site_url('profile/academic/experience');
    if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next])->setStatusCode(200);
    return redirect()->to($next)->with('success','Saved');
}


public function academicTeachingResearch()
{
    $session = session(); if (! $session->get('isLoggedIn')) return redirect()->to('/login');
    $user = $this->userModel->find((int)$session->get('user_id'));
    return view('profile/academic/teaching_research',['user'=>$user]);
}

public function saveAcademicTeachingResearch()
{
    if ($this->request->getMethod() !== 'post') return $this->response->setStatusCode(405);
    if (! session()->get('isLoggedIn')) return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);

    $rules = [
      'courses_taught' => 'permit_empty|max_length[2000]',
      'teaching_load' => 'permit_empty|integer',
      'research_areas' => 'permit_empty|max_length[4000]',
      'supervisions_count' => 'permit_empty|integer',
      'postgraduate_supervisor' => 'permit_empty|in_list[yes,no]',
      'grants' => 'permit_empty|max_length[4000]',
    ];
    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors',$errors);
    }

    $userId = (int) session()->get('user_id');
    $update = [
      'courses_taught' => $this->request->getPost('courses_taught') ?: null,
      'teaching_load' => $this->request->getPost('teaching_load') ?: null,
      'research_areas' => $this->request->getPost('research_areas') ?: null,
      'supervisions_count' => $this->request->getPost('supervisions_count') ?: null,
      'postgraduate_supervisor' => $this->request->getPost('postgraduate_supervisor') ?: null,
      'grants' => $this->request->getPost('grants') ?: null,
    ];

    try { $cols = \Config\Database::connect()->getFieldNames('users'); } catch(\Throwable $e) { return $this->response->setJSON(['success'=>false,'message'=>'DB error'])->setStatusCode(500); }
    $missing = array_filter(array_keys($update), fn($c)=> ! in_array($c,$cols));
    if ($missing) return $this->response->setJSON(['success'=>false,'message'=>'Database schema mismatch','missing_columns'=>$missing])->setStatusCode(500);

    try {
        $this->userModel->update($userId,$update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicTeachingResearch: '.$e->getMessage());
        return $this->response->setJSON(['success'=>false,'message'=>'Failed to save'])->setStatusCode(500);
    }

    $next = site_url('profile/academic/qualifications');
    if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next])->setStatusCode(200);
    return redirect()->to($next)->with('success','Saved');
}


/**
 * Render Academic Experience/Activities form
 */
public function academicExperience()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/academic/experience', [
        'user' => $user,
    ]);
}

/**
 * Save Academic Experience/Activities
 */
public function saveAcademicExperience()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    $rules = [
        // Experience
        'teaching_experience' => 'permit_empty|max_length[200000]',
        'professional_experience' => 'permit_empty|max_length[200000]',

        // Research
        'dissertation' => 'permit_empty|max_length[200000]',
        'articles' => 'permit_empty|max_length[200000]',
        'books_monographs' => 'permit_empty|max_length[200000]',
        'papers_accepted' => 'permit_empty|max_length[200000]',

        // Contribution / conference
        'contribution_to_knowledge' => 'permit_empty|max_length[200000]',
        'unpub_paper_conference' => 'permit_empty|max_length[v]',

        // Activities
        'activities_within_university' => 'permit_empty|max_length[200000]',
        'activities_outside_university' => 'permit_empty|max_length[200000]',
        'courses_conferences' => 'permit_empty|max_length[200000]',

        // optional extras
        'other_notes' => 'permit_empty|max_length[200000]',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning','saveAcademicExperience validation failed for user '.session()->get('user_id').': '.json_encode($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // build update payload
    $update = [
        'teaching_experience' => $this->request->getPost('teaching_experience') ?: null,
        'professional_experience' => $this->request->getPost('professional_experience') ?: null,
        'dissertation' => $this->request->getPost('dissertation') ?: null,
        'articles' => $this->request->getPost('articles') ?: null,
        'books_monographs' => $this->request->getPost('books_monographs') ?: null,
        'papers_accepted' => $this->request->getPost('papers_accepted') ?: null,
        'contribution_to_knowledge' => $this->request->getPost('contribution_to_knowledge') ?: null,
        'unpub_paper_conference' => $this->request->getPost('unpub_paper_conference') ?: null,
        'activities_within_university' => $this->request->getPost('activities_within_university') ?: null,
        'activities_outside_university' => $this->request->getPost('activities_outside_university') ?: null,
        'courses_conferences' => $this->request->getPost('courses_conferences') ?: null,
        'other_notes' => $this->request->getPost('other_notes') ?: null,
    ];

    // guard: check DB columns exist
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveAcademicExperience DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error','saveAcademicExperience missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'=>false,
                'message'=>'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns'=>$missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors',['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // perform update
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicExperience update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Server error while saving.']);
    }

    // success -> show final success page
    $next = site_url('profile/success');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next,'redirectDelay'=>900])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Experience saved');
}

/* ---------------------------
       Junior Personal (GET)  URL: /profile/junior/personal
       --------------------------- */
    
       public function juniorPersonal()
    {
        // require login
    if (! session()->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }
    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        session()->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }
        // load supporting data (faculties, departments) if models available
        $faculties = $this->facultyModel ? $this->facultyModel->findAll() : [];
        $departments = $this->departmentModel ? $this->departmentModel->findAll() : [];

        return view('profile/junior/personal', [
            'user' => $user,
            'faculties' => $faculties,
            'departments' => $departments,
        ]);
    }

    /* ---------------------------
       Junior Personal (POST save)  URL: /profile/junior/personal/save
       --------------------------- */
    
       /**
 * POST /profile/junior/personal/save
 */
public function saveJuniorPersonal()
{
    // allow only POST
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success' => false, 'message' => 'Method not allowed'])->setStatusCode(405);
    }

    // auth guard
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['success' => false, 'message' => 'User not found'])->setStatusCode(404);
    }

    // validation rules
    $rules = [
        'phone'        => 'permit_empty|max_length[80]',
        'dob'          => 'permit_empty|valid_date',
        'gender'       => 'permit_empty|in_list[male,female,other]',
        'faculty_id'   => 'permit_empty|is_natural_no_zero',
        'department_id'=> 'permit_empty|is_natural_no_zero',
        'designation'  => 'permit_empty|max_length[120]',
        'grade_level'  => 'permit_empty|max_length[80]',
        'period_from'  => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
        'period_to'    => 'required|integer|greater_than_equal_to[1900]|less_than_equal_to[9999]',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning', 'saveJuniorPersonal validation failed for user ' . $userId . ': ' . json_encode($errors));
        return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
    }

    $periodFrom = (int) $this->request->getPost('period_from');
    $periodTo   = (int) $this->request->getPost('period_to');
    if ($periodFrom > $periodTo) {
        $msg = 'Start year cannot be greater than end year.';
        return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(422);
    }

    // prepare payload (normalize empty -> null)
    $update = [
        'phone' => $this->request->getPost('phone') ?: null,
        'dob' => $this->request->getPost('dob') ?: null,
        'gender' => $this->request->getPost('gender') ?: null,
        'faculty' => $this->request->getPost('faculty_id') ?: null,
        'department' => $this->request->getPost('department_id') ?: null,
        'designation' => $this->request->getPost('designation') ?: null,
        'grade_level' => $this->request->getPost('grade_level') ?: null,
        'period_from' => $periodFrom,
        'period_to' => $periodTo,
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    // Defensive: check DB columns present before updating (helps catch migration issues)
    try {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($this->userModel->table ?? 'users');
    } catch (\Throwable $e) {
        log_message('error', 'saveJuniorPersonal DB connect failed: ' . $e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'Database connection error'])->setStatusCode(500);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $fields)) {
            $missing[] = $col;
        }
    }
    if (! empty($missing)) {
        log_message('error', 'saveJuniorPersonal missing columns for user ' . $userId . ': ' . implode(', ', $missing));
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Database schema mismatch: missing columns: ' . implode(', ', $missing),
            'missing_columns' => $missing
        ])->setStatusCode(500);
    }

    // perform update
    try {
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error', 'saveJuniorPersonal update failed for user ' . $userId . ': ' . $e->getMessage());
        if (ENVIRONMENT === 'development') {
            return $this->response->setJSON(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()])->setStatusCode(500);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Failed to save data'])->setStatusCode(500);
    }

    // success — redirect to next stage (adjust route if needed)
    $next = site_url('profile/junior/employment');
    return $this->response->setJSON(['success' => true, 'message' => 'Saved', 'redirect' => $next])->setStatusCode(200);
}


/**
 * GET /profile/junior/employment
 */
public function juniorEmployment()
{
         $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // load helper data if available (departments, faculties)
    $faculties = $this->facultyModel ? $this->facultyModel->findAll() : [];
    $departments = $this->departmentModel ? $this->departmentModel->findAll() : [];

    return view('profile/junior/employment', [
        'user' => $user,
        'faculties' => $faculties,
        'departments' => $departments,
    ]);
}

/**
 * POST /profile/junior/employment/save
 */
public function saveJuniorEmployment()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    // validation rules
    $rules = [
        'present_salary' => 'permit_empty|numeric',
        'contiss' => 'permit_empty|max_length[50]',
        'step' => 'permit_empty|max_length[50]',
        'first_appointment_date' => 'permit_empty|valid_date',
        'first_appointment_grade' => 'permit_empty|max_length[50]',
        'last_promotion_date' => 'permit_empty|valid_date',
        'last_promotion_grade' => 'permit_empty|max_length[50]',
        'current_appointment_date' => 'permit_empty|valid_date',
        'current_appointment_grade' => 'permit_empty|max_length[50]',
        'appointment_confirmed' => 'permit_empty|in_list[0,1,yes,no]',
        'appointment_confirmed_at' => 'permit_empty|valid_date',
        
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning','saveJuniorEmployment validation failed: '.json_encode($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // business rules: normalize appointment_confirmed
    $raw = $this->request->getPost('appointment_confirmed');
    $apc = null;
    if ($raw === '1' || $raw === 'yes' || $raw === 'on') $apc = 1;
    elseif ($raw === '0' || $raw === 'no' || $raw === '') $apc = 0;

    $apcDate = $this->request->getPost('appointment_confirmed_at') ?: null;
    if ($apc === 1 && empty($apcDate)) {
        $err = ['appointment_confirmed_at' => 'Please provide confirmation date.'];
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$err])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors', $err);
    }
    if ($apc !== 1) $apcDate = null;

    // prepare update payload
    $update = [
        'present_salary' => $this->request->getPost('present_salary') !== '' ? $this->request->getPost('present_salary') : null,
        'contiss' => $this->request->getPost('contiss') ?: null,
        'step' => $this->request->getPost('step') ?: null,
        'date_of_first_appointment' => $this->request->getPost('first_appointment_date') ?: null,
        'first_appointment_grade' => $this->request->getPost('first_appointment_grade') ?: null,
        'last_promotion_date' => $this->request->getPost('last_promotion_date') ?: null,
        'last_promotion_grade' => $this->request->getPost('last_promotion_grade') ?: null,
        'current_appointment_date' => $this->request->getPost('current_appointment_date') ?: null,
        'current_appointment_grade' => $this->request->getPost('current_appointment_grade') ?: null,
        'appointment_confirmed' => $apc ?? 0,
        'appointment_confirmed_at' => $apcDate,
        
    ];

    // guard columns exist
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveJuniorEmployment DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error','saveJuniorEmployment missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'=>false,
                'message'=>'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns'=>$missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors',['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // update DB
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveJuniorEmployment update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Server error while saving.']);
    }

    // success - next step
    $next = site_url('profile/junior/qualifications');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next,'redirectDelay'=>1000])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Saved');
}

// Show Qualifications form
public function juniorQualifications()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/junior/qualifications', [
        'user' => $user,
    ]);
}

// POST handler to save Qualifications
public function saveJuniorQualifications()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success' => false, 'message' => 'Method not allowed'])->setStatusCode(405);
    }
    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated'])->setStatusCode(401);
    }

    // validation rules (each qualification entry is optional)
    $rules = [];
    for ($i = 1; $i <= 5; $i++) {
        $rules["qual{$i}"] = 'permit_empty|max_length[255]';               // qualification name
        $rules["qual{$i}_grade"] = 'permit_empty|max_length[50]';         // grade
        $rules["qual{$i}_institution"] = 'permit_empty|max_length[255]';  // institution
        $rules["qual{$i}_date"] = 'permit_empty|valid_date';             // date
    }
    for ($i = 1; $i <= 5; $i++) {
        $rules["prof_qual{$i}"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_body"] = 'permit_empty|max_length[255]';
        $rules["prof_qual{$i}_date"] = 'permit_empty|valid_date';
    }

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // build payload
    $update = [];
    for ($i = 1; $i <= 5; $i++) {
        $update["qual{$i}"] = $this->request->getPost("qual{$i}") ?: null;
        $update["qual{$i}_grade"] = $this->request->getPost("qual{$i}_grade") ?: null;
        $update["qual{$i}_institution"] = $this->request->getPost("qual{$i}_institution") ?: null;
        $update["qual{$i}_date"] = $this->request->getPost("qual{$i}_date") ?: null;
    }
    for ($i = 1; $i <= 5; $i++) {
        $update["prof_qual{$i}"] = $this->request->getPost("prof_qual{$i}") ?: null;
        $update["prof_qual{$i}_body"] = $this->request->getPost("prof_qual{$i}_body") ?: null;
        $update["prof_qual{$i}_date"] = $this->request->getPost("prof_qual{$i}_date") ?: null;
    }

    // DB column guard (helpful error if migrations missing)
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveJuniorQualifications DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }
    if (! empty($missing)) {
        log_message('error','saveJuniorQualifications missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns' => $missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors', ['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // perform update
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveJuniorQualifications update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors', ['Server error while saving.']);
    }

    $next = site_url('profile/junior/experience');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Qualifications saved','redirect'=>$next,'redirectDelay'=>900])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Qualifications saved');
}
/**
 * Render junior Experience/Activities form
 */

/**
 * Save junior Experience/Activities
 */
 public function juniorExperience()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/junior/experience', [
        'user' => $user,
    ]);
}

/**
 * Save junior Experience/Activities
 */
 public function saveJuniorExperience()

{
    // Debug mode: verbose output
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['ok'=>false,'msg'=>'Method not allowed','method'=>$this->request->getMethod()])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['ok'=>false,'msg'=>'Not authenticated'])->setStatusCode(401);
    }

    $userId = (int) session()->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        return $this->response->setJSON(['ok'=>false,'msg'=>'User not found'])->setStatusCode(404);
    }

    // collect input (no validation to let us see raw)
    $update = [
        'exp_out_institution_name1' => $this->request->getPost('exp_out_institution_name1'),
        'exp_out_designation1' => $this->request->getPost('exp_out_designation1'),
        'exp_out_specialization1' => $this->request->getPost('exp_out_specialization1'),
        'exp_out_date1' => $this->request->getPost('exp_out_date1'),
        'exp_out_institution_name2' => $this->request->getPost('exp_out_institution_name2'),
        'exp_out_designation2' => $this->request->getPost('exp_out_designation2'),
        'exp_out_specialization2' => $this->request->getPost('exp_out_specialization2'),
        'exp_out_date2' => $this->request->getPost('exp_out_date2'),
        'professional_experience' => $this->request->getPost('professional_experience'),
    ];

    // Log incoming data
    log_message('debug','DEBUG saveJuniorExperience called for user '.$userId.' payload: '.json_encode($update));

    // DB columns
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','DEBUG DB connect failed: '.$e->getMessage());
        return $this->response->setJSON(['ok'=>false,'msg'=>'DB connect failed','error'=>$e->getMessage()])->setStatusCode(500);
    }

    // compute missing
    $missing = [];
    foreach (array_keys($update) as $col) {
        if (! in_array($col, $cols)) $missing[] = $col;
    }

    // Attempt update but catch any exception
    $updateResult = null;
    $error = null;
    try {
        $this->userModel->update($userId, $update);
        $updateResult = $this->userModel->find($userId);
    } catch (\Throwable $e) {
        $error = $e->getMessage();
        log_message('error','DEBUG update failed: '.$e->getMessage()."\n".$e->getTraceAsString());
    }

    // Build verbose JSON to inspect client-side
    $resp = [
        'ok' => $error === null,
        'message' => $error ? 'Update failed' : 'Update attempted',
        'missing_columns' => $missing,
        'posted' => $update,
        'db_columns_sample' => array_values(array_slice($cols, 0, 40)),
        'update_result_sample' => $updateResult ? array_intersect_key($updateResult, array_flip(array_keys($update))) : null,
        'error' => $error,
        // include the redirect so your client still can read it
        'redirect' => site_url('profile/success'),
        'redirectDelay' => 1200,
    ];

    return $this->response->setJSON($resp)->setStatusCode($error ? 500 : 200);
}

public function juniorExperience1()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/junior/experience', [
        'user' => $user,
    ]);
}

/**
 * Save junior Experience/Activities
 */
public function saveJuniorExperience1()
{
    if ($this->request->getMethod() !== 'post') {
        return $this->response->setJSON(['success'=>false,'message'=>'Method not allowed'])->setStatusCode(405);
    }

    if (! session()->get('isLoggedIn')) {
        return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
    }

    $rules = [
        // Experience
        'teaching_experience' => 'permit_empty|max_length[4000]',
        'professional_experience' => 'permit_empty|max_length[4000]',

        // Research
        'dissertation' => 'permit_empty|max_length[2000]',
        'articles' => 'permit_empty|max_length[2000]',
        'books_monographs' => 'permit_empty|max_length[2000]',
        'papers_accepted' => 'permit_empty|max_length[2000]',

        // Contribution / conference
        'contribution_to_knowledge' => 'permit_empty|max_length[4000]',
        'unpub_paper_conference' => 'permit_empty|max_length[2000]',

        // Activities
        'activities_within_university' => 'permit_empty|max_length[3000]',
        'activities_outside_university' => 'permit_empty|max_length[3000]',
        'courses_conferences' => 'permit_empty|max_length[2000]',

        // optional extras
        'other_notes' => 'permit_empty|max_length[2000]',
    ];

    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        log_message('warning','saveAcademicExperience validation failed for user '.session()->get('user_id').': '.json_encode($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        }
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    // build update payload
    $update = [
        'teaching_experience' => $this->request->getPost('teaching_experience') ?: null,
        'professional_experience' => $this->request->getPost('professional_experience') ?: null,
        'dissertation' => $this->request->getPost('dissertation') ?: null,
        'articles' => $this->request->getPost('articles') ?: null,
        'books_monographs' => $this->request->getPost('books_monographs') ?: null,
        'papers_accepted' => $this->request->getPost('papers_accepted') ?: null,
        'contribution_to_knowledge' => $this->request->getPost('contribution_to_knowledge') ?: null,
        'unpub_paper_conference' => $this->request->getPost('unpub_paper_conference') ?: null,
        'activities_within_university' => $this->request->getPost('activities_within_university') ?: null,
        'activities_outside_university' => $this->request->getPost('activities_outside_university') ?: null,
        'courses_conferences' => $this->request->getPost('courses_conferences') ?: null,
        'other_notes' => $this->request->getPost('other_notes') ?: null,
    ];

    // guard: check DB columns exist
    try {
        $db = \Config\Database::connect();
        $cols = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error','saveAcademicExperience DB connect failed: '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Database connection error'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Database connection error.']);
    }

    $missing = [];
    foreach (array_keys($update) as $c) {
        if (! in_array($c, $cols)) $missing[] = $c;
    }
    if (! empty($missing)) {
        log_message('error','saveAcademicExperience missing columns: '.implode(', ',$missing));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'=>false,
                'message'=>'Database schema mismatch: missing columns: '.implode(', ',$missing),
                'missing_columns'=>$missing
            ])->setStatusCode(500);
        }
        return redirect()->back()->withInput()->with('errors',['Database schema mismatch: '.implode(', ',$missing)]);
    }

    // perform update
    try {
        $userId = (int) session()->get('user_id');
        $this->userModel->update($userId, $update);
    } catch (\Throwable $e) {
        log_message('error','saveAcademicExperience update failed for user '.$userId.': '.$e->getMessage());
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        return redirect()->back()->withInput()->with('errors',['Server error while saving.']);
    }

    // success -> show final success page
    $next = site_url('profile/success');
    if ($this->request->isAJAX()) {
        return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next,'redirectDelay'=>900])->setStatusCode(200);
    }
    return redirect()->to($next)->with('success','Experience saved');
}

public function juniorQualifications1()
{
    $session = session(); if (! $session->get('isLoggedIn')) return redirect()->to('/login');
    $user = $this->userModel->find((int)$session->get('user_id'));
    return view('profile/junior/qualifications',['user'=>$user]);
}

public function saveJuniorQualifications1()
{
    if ($this->request->getMethod() !== 'post') return $this->response->setStatusCode(405);
    if (! session()->get('isLoggedIn')) return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);

    $rules = [
      'publications' => 'permit_empty',
      'dissertation' => 'permit_empty',
      'articles' => 'permit_empty',
      'books_monographs' => 'permit_empty',
      'number_pub_accepted' => 'permit_empty|integer',
      'number_of_points' => 'permit_empty|integer',
      'other_remark' => 'permit_empty|max_length[2000]',
    ];
    if (! $this->validate($rules)) {
        $errors = $this->validator->getErrors();
        if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>false,'errors'=>$errors])->setStatusCode(422);
        return redirect()->back()->withInput()->with('errors',$errors);
    }

    $userId = (int) session()->get('user_id');
    $update = [
      'publications' => $this->request->getPost('publications') ?: null,
      'dissertation' => $this->request->getPost('dissertation') ?: null,
      'articles' => $this->request->getPost('articles') ?: null,
      'books_monographs' => $this->request->getPost('books_monographs') ?: null,
      'number_pub_accepted' => $this->request->getPost('number_pub_accepted') ?: null,
      'number_of_points' => $this->request->getPost('number_of_points') ?: null,
      'other_remark' => $this->request->getPost('other_remark') ?: null,
    ];

    try { $cols = \Config\Database::connect()->getFieldNames('users'); } catch(\Throwable $e) { return $this->response->setJSON(['success'=>false,'message'=>'DB error'])->setStatusCode(500); }
    $missing = array_filter(array_keys($update), fn($c)=> ! in_array($c,$cols));
    if ($missing) return $this->response->setJSON(['success'=>false,'message'=>'Database schema mismatch','missing_columns'=>$missing])->setStatusCode(500);

    try {
        $this->userModel->update($userId,$update);
    } catch (\Throwable $e) {
        log_message('error','saveJuniorQualifications: '.$e->getMessage());
        return $this->response->setJSON(['success'=>false,'message'=>'Failed to save'])->setStatusCode(500);
    }

    $next = site_url('profile/junior/experience');
    if ($this->request->isAJAX()) return $this->response->setJSON(['success'=>true,'message'=>'Saved','redirect'=>$next])->setStatusCode(200);
    return redirect()->to($next)->with('success','Saved');
}




/**
 * Simple success/complete page after finishing profile stages
 */
public function profileSuccess()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login.']);
    }

    $user = $this->userModel->find((int)$session->get('user_id'));
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    return view('profile/success', ['user' => $user]);
}

/**
 * Congratulatory success page after completing registration stages
 */
public function success()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // Optionally mark completed_profile = 1 if this is final stage
    // $this->userModel->update($userId, ['completed_profile' => 1]);

    return view('profile/success', [
        'user' => $user,
    ]);
}

/**
 * Print-friendly profile summary
 */

 public function printSummary()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        // DO NOT destroy session here (avoid accidental logout). Just redirect.
        log_message('warning', "printSummary: user not found for id={$userId}");
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // Normalize object -> array
    if (is_object($user)) $user = (array) $user;

    // Defaults
    $facultyName = $user['faculty_name'] ?? null;
    $departmentName = $user['department_name'] ?? null;

    try {
        // If faculty_name not already available, try to load via FacultyModel (if it exists)
        if (empty($facultyName) && ! empty($user['faculty_id']) && class_exists(\App\Models\FacultyModel::class)) {
            $fm = new \App\Models\FacultyModel();
            $f = $fm->find((int)$user['faculty_id']);
            $facultyName = $f['name'] ?? null;
        }

        // If department_name not already available, try to load via DepartmentModel (if it exists)
        if (empty($departmentName) && ! empty($user['department_id']) && class_exists(\App\Models\DepartmentModel::class)) {
            $dm = new \App\Models\DepartmentModel();
            $d = $dm->find((int)$user['department_id']);
            $departmentName = $d['name'] ?? null;
        }
    } catch (\Throwable $e) {
        log_message('warning', 'printSummary relation lookup failed: ' . $e->getMessage());
    }

    // Inject friendly names into $user array so existing views that read $user[...] will work
    $user['faculty_name'] = $facultyName;
    $user['department_name'] = $departmentName;

    return view('profile/print_summary', [
        'user' => $user,
        'facultyName' => $facultyName,
        'departmentName' => $departmentName,
    ]);
}

 


public function printSummary1()
{
    $session = session();
    if (! $session->get('isLoggedIn')) {
        return redirect()->to('/login')->with('errors', ['Please login to continue.']);
    }

    $userId = (int) $session->get('user_id');
    $user = $this->userModel->find($userId);
    if (! $user) {
        $session->destroy();
        return redirect()->to('/login')->with('errors', ['User not found.']);
    }

    // Optionally load faculty/department names if you have models
    $facultyName = null;
    $departmentName = null;
    try {
        if (! empty($user['faculty_id'])) {
            $fm = new \App\Models\FacultyModel();
            $f = $fm->find($user['faculty_id']);
            $facultyName = $f['name'] ?? null;
        }
        if (! empty($user['department_id'])) {
            $dm = new \App\Models\DepartmentModel();
            $d = $dm->find($user['department_id']);
            $departmentName = $d['name'] ?? null;
        }
    } catch (\Throwable $e) {
        // ignore — optional models may not exist
    }

    return view('profile/print_summary', [
        'user' => $user,
        'facultyName' => $facultyName,
        'departmentName' => $departmentName,
    ]);
}



}
