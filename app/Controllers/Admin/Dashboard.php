<?php namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\FacultyModel;
use App\Models\DepartmentModel;

class Dashboard extends AdminBaseController
{
    protected $userModel;
    protected $facultyModel;
    protected $departmentModel;

    public function __construct()
    {
        parent::__construct(); // if AdminBaseController sets session/admin guard helpers
        $this->userModel = new UserModel();
        // only if you have these models
        if (class_exists(FacultyModel::class)) $this->facultyModel = new FacultyModel();
        if (class_exists(DepartmentModel::class)) $this->departmentModel = new DepartmentModel();

        helper(['form', 'url', 'text']);
    }
public function index()
{
    $this->guard();

    // safe fetch with normalized name labels

$db = \Config\Database::connect();
$builder = $db->table('users as u');
$builder->select('u.*');

// try to join faculties/departments and alias the name columns
if ($db->tableExists('faculties')) {
    // ensure alias 'f' and select its name
    $builder->select('f.name as faculty_name');
    $builder->join('faculties f', 'f.id = u.faculty', 'left');
}
if ($db->tableExists('departments')) {
    $builder->select('d.name as department_name');
    $builder->join('departments d', 'd.id = u.department', 'left');
}

$builder->orderBy('u.id', 'DESC');
$rows = $builder->get()->getResultArray();

// If join didn't produce names (some installs may store textual faculty/department in users table),
// build normalized labels so view only needs faculty_name / department_name.
$facultyMap = $departmentMap = [];
if ($db->tableExists('faculties')) {
    $frows = $db->table('faculties')->select('id,name')->get()->getResultArray();
    $facultyMap = array_column($frows, 'name', 'id'); // id => name
}
if ($db->tableExists('departments')) {
    $drows = $db->table('departments')->select('id,name')->get()->getResultArray();
    $departmentMap = array_column($drows, 'name', 'id');
}

// Normalize each user row
$users = [];
foreach ($rows as $r) {
    // prefer joined alias if present
    $facultyName = $r['faculty_name'] ?? null;
    $departmentName = $r['department_name'] ?? null;

    // fallback 1: if users table stores textual 'faculty' or 'department' fields
    if (empty($facultyName) && ! empty($r['faculty'])) {
        $facultyName = $r['faculty'];
    }
    if (empty($departmentName) && ! empty($r['department'])) {
        $departmentName = $r['department'];
    }

    // fallback 2: try map lookup from FK
    if (empty($facultyName) && ! empty($r['faculty_id'])) {
        $facultyName = $facultyMap[$r['faculty_id']] ?? null;
    }
    if (empty($departmentName) && ! empty($r['department_id'])) {
        $departmentName = $departmentMap[$r['department_id']] ?? null;
    }

    // final fallback
    $r['faculty_name'] = $facultyName ?? '-';
    $r['department_name'] = $departmentName ?? '-';

    // Some apps use staff_number vs staff_id — normalize a display field
    if (empty($r['staff_id']) && ! empty($r['staff_number'])) {
        $r['staff_id'] = $r['staff_number'];
    }

    // ensure fullname key exists
    if (empty($r['fullname']) && ! empty($r['name'])) {
        $r['fullname'] = $r['name'];
    }

    $users[] = $r;
}

// pass normalized $users to view
return view('admin/dashboard/index', [
    'admin' => session()->get('admin') ?? null,
    'users' => $users,
    'totalUsers' => $db->table('users')->countAll(),
    'recentUsers' => $db->table('users')->orderBy('created_at','DESC')->limit(5)->get()->getResultArray(),
    'currentFacultyId' => session()->get('faculty_id') ?? '',
    'currentDepartmentId' => session()->get('department_id') ?? '',
]);

}

    /**
     * Return the edit form partial (for modal)
     * GET /admin/staff/{id}/edit-form  -> route to Dashboard::editForm/$1
     */


     public function viewForm($id)
{
    $um = new \App\Models\UserModel();
    $user = $um->find((int)$id);
    if (! $user) return $this->response->setStatusCode(404)->setBody('Not found');

    // render only partial (no full layout)
    return view('admin/dashboard/partials/view_form', ['user'=>$user]);
}

    public function editForm($id = null)
    {
        $this->guard();

        $id = (int)$id;
        if (! $id) {
            return $this->response->setStatusCode(400)->setBody('Invalid id');
        }

        $user = $this->userModel->find($id);
        if (! $user) {
            return $this->response->setStatusCode(404)->setBody('User not found');
        }

        // Faculties/departments if available (for selects)
        $faculties = $this->facultyModel ? $this->facultyModel->findAll() : [];
        $departments = $this->departmentModel ? $this->departmentModel->findAll() : [];

        // Render only the partial used in modal (same partial you used before)
        return view('admin/dashboard/partials/edit_form', [
            'user' => $user,
            'faculties' => $faculties,
            'departments' => $departments,
            'method' => 'edit',
        ]);
    }

    /**
     * Update user (called via AJAX from modal form)
     * POST /admin/staff/{id}/update  -> route to Dashboard::update/$1
     */
    public function update($id = null)
    {
        $this->guard();

        helper('form');
        $id = (int)$id;
        if (! $id) {
            return $this->response->setJSON(['success'=>false,'message'=>'Invalid ID'])->setStatusCode(400);
        }

        $user = $this->userModel->find($id);
        if (! $user) {
            return $this->response->setJSON(['success'=>false,'message'=>'User not found'])->setStatusCode(404);
        }

        // validation rules
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|max_length[255]',
            'staff_id' => 'required|max_length[120]',
            'phone' => 'permit_empty|max_length[50]',
            'faculty_id' => 'permit_empty|integer',
            'department_id' => 'permit_empty|integer',
            'period_from' => 'permit_empty|integer',
            'period_to' => 'permit_empty|integer',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setJSON(['success'=>false,'errors'=>$this->validator->getErrors()])->setStatusCode(422);
        }

        // normalize inputs
        $fullname = trim($this->request->getPost('fullname'));
        $email = strtolower(trim($this->request->getPost('email')));
        $staffIdInput = trim($this->request->getPost('staff_id'));
        $phone = trim($this->request->getPost('phone')) ?: null;
        $facultyId = $this->request->getPost('faculty_id') ?: null;
        $departmentId = $this->request->getPost('department_id') ?: null;
        $periodFrom = $this->request->getPost('period_from') ?: null;
        $periodTo = $this->request->getPost('period_to') ?: null;

        // duplicate checks (exclude current record)
        $existsEmail = $this->userModel->where('LOWER(email)', strtolower($email))->where('id !=', $id)->first();
        if ($existsEmail) {
            return $this->response->setJSON(['success'=>false,'message'=>'Email already in use'])->setStatusCode(409);
        }
        // staff_id column name may be staff_number in DB; adjust accordingly
        $existsStaff = $this->userModel->where('staff_id', $staffIdInput)->where('id !=', $id)->first();
        if ($existsStaff) {
            return $this->response->setJSON(['success'=>false,'message'=>'Staff ID already in use'])->setStatusCode(409);
        }

        // prepare update array
        $update = [
            'fullname' => $fullname,
            'email' => $email,
            'staff_id' => $staffIdInput,
            'phone' => $phone,
            'faculty_id' => $facultyId,
            'department_id' => $departmentId,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Optional password reset: only set if non-empty
        $pw = $this->request->getPost('password');
        if ($pw && trim($pw) !== '') {
            $update['password'] = password_hash(trim($pw), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->update($id, $update);
            $updated = $this->userModel->find($id);
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard update error: '.$e->getMessage());
            return $this->response->setJSON(['success'=>false,'message'=>'Server error while updating'])->setStatusCode(500);
        }

        // return the updated minimal payload so client can update row in-place
        return $this->response->setJSON([
            'success' => true,
            'message' => 'User updated successfully',
            'updated' => [
                'id' => $id,
                'fullname' => $updated['fullname'] ?? null,
                'email' => $updated['email'] ?? null,
                'staff_id' => $updated['staff_id'] ?? null,
                'faculty_id' => $updated['faculty_id'] ?? null,
                'department_id' => $updated['department_id'] ?? null,
            ],
        ])->setStatusCode(200);
    }
}
