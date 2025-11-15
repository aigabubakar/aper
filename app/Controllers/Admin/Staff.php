<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController; // or BaseController if you don't have AdminBaseController
use App\Models\StaffModel;

class Staff extends AdminBaseController
{
    protected $staffModel;

    public function __construct()
    {
        parent::__construct(); // if AdminBaseController does guard/session setup
        $this->staffModel = new StaffModel();
        helper(['form','url','text']);
    }

    // Index: listing (controller should pass $users to view used by DataTable)
    public function index()
    {

        $users = $this->staffModel->orderBy('id','DESC')->findAll();
        $totalUsers = count($users);

        return view('admin/staff/index', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'currentFacultyId' => session()->get('faculty_id') ?? '',
           'currentDepartmentId' => session()->get('department_id') ?? '',
        ]);
    }


    /**
     * Show create staff form
     */
    public function create()
    {
        $this->guard(); // keep your existing guard/authorization

        $faculties = [];
        $departments = [];

        if (class_exists(\App\Models\FacultyModel::class)) {
            $faculties = (new \App\Models\FacultyModel())->findAll();
        }
        if (class_exists(\App\Models\DepartmentModel::class)) {
            $departments = (new \App\Models\DepartmentModel())->findAll();
        }

        return view('admin/staff/create', [
            'faculties' => $faculties,
            'departments' => $departments,
        ]);
    }

    /**
     * Persist new staff
     */
    public function store()
    {
        $this->guard();

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        // NOTE: use 'staff_number' to match your DB/model
        $rules = [
            'fullname'      => 'required|min_length[3]|max_length[255]',
            'email'         => 'required|valid_email|max_length[255]',
            'staff_number'  => 'required|max_length[120]',
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // normalize inputs
        $fullname     = trim($this->request->getPost('fullname'));
        $email        = strtolower(trim($this->request->getPost('email')));
        $staffNumber  = trim($this->request->getPost('staff_number'));
        

        // duplicates
        if ($this->staffModel->findByEmail($email)) {
            $msg = 'A user with that email already exists.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(409);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        if ($this->staffModel->where('staff_number', $staffNumber)->first()) {
            $msg = 'A user with that Staff Number already exists.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(409);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

      
        $insert = [
            'fullname'      => $fullname,
            'email'         => $email,
            'staff_number'  => $staffNumber,

            // if your model uses $useTimestamps, created_at will be filled automatically,
            // but setting it explicitly is harmless:
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        try {
            $this->staffModel->insert($insert);
            $newId = $this->staffModel->getInsertID();
        } catch (\Throwable $e) {
            log_message('error', 'Admin Staff store error: ' . $e->getMessage());
            $msg = (ENVIRONMENT === 'development') ? $e->getMessage() : 'Server error while saving staff.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // success
        $msg = 'Staff added successfully.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => $msg, 'redirect' => site_url('admin/staff')])->setStatusCode(200);
        }

        return redirect()->to(site_url('admin/staff/create'))->with('success', $msg);
    }


    /**
 * Export staff list as CSV (filtered by admin role/scope and optional query params)
 */
// app/Controllers/Admin/Staff.php (add this method to the controller)


// app/Controllers/Admin/Staff.php
// Paste this method inside the Admin\Staff class

public function export()
{
    // AUTH CHECK – Make sure user is admin or privileged
    $session = session();

    if (!$session->has('isLoggedIn') || $session->get('role') !== 'admin') {
        return redirect()->to('/admin/login')->with('error', 'Unauthorized to export staff data');
    }

    $staffModel = new \App\Models\StaffModel();

    // Optional filters
    $facultyId    = $this->request->getGet('faculty');
    $departmentId = $this->request->getGet('department');

    if ($facultyId) {
        $staffModel->where('faculty_id', $facultyId);
    }
    if ($departmentId) {
        $staffModel->where('department_id', $departmentId);
    }

    $staff = $staffModel->findAll();

    // --------- CSV EXPORT BEGIN ---------
    helper('filesystem');

    $filename = "staff_export_" . date('Y-m-d_H-i-s') . ".csv";

    // Required to clear buffered output
    if (ob_get_length()) {
        ob_end_clean();
    }

    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: text/csv; charset=UTF-8");
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    $file = fopen('php://output', 'w');

    // CSV Header
    fputcsv($file, ['ID', 'Fullname', 'Email', 'Staff Number', 'Department', 'Faculty', 'Created At']);

    foreach ($staff as $row) {
        fputcsv($file, [
            $row['id'],
            $row['fullname'],
            $row['email'],
            $row['staff_number'],
            $row['department_id'],
            $row['faculty_id'],
            $row['created_at'],
        ]);
    }

    fclose($file);

    exit; // 🔥 VERY IMPORTANT
}


/**
 * Backwards compatibility wrapper for old calls to exportCsv.
 * Left intentionally thin — forwards to export().
 */
public function exportCsv()
{
    // If you implemented export() expecting the same parameters, just call it:
    return $this->export();
}


public function exportCsv3()
{
    $session = session();

    // Accept admin stored as session('admin') array or session('role')
    $admin = $session->get('admin') ?: null;
    $role  = $session->get('role') ?: ($admin['role'] ?? null);

    if (! $admin && ! $role) {
        $session->setFlashdata('error', 'Please login as admin to export data.');
        return redirect()->to('/admin/login');
    }

    // Authorisation: adjust roles allowed for export
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! in_array($role, $allowedRoles)) {
        $session->setFlashdata('error', 'Unauthorized to export staff data.');
        return redirect()->to('/admin/login');
    }

    $request = service('request');
    $qFaculty = $request->getGet('faculty') ?: null;
    $qDepartment = $request->getGet('department') ?: null;

    $db = \Config\Database::connect();
    $builder = $db->table('users'); // change to 'staffs' if you want that table

    $builder->select('id, staff_id, fullname, email, category, role, faculty_id, department_id, phone, period_from, period_to, created_at');

    // Role scoping (can be simplified if not needed)
    if ($role === 'dean') {
        $myFaculty = $session->get('faculty_id') ?: ($admin['faculty_id'] ?? null);
        if ($qFaculty && $myFaculty && $qFaculty != $myFaculty) {
            $session->setFlashdata('error','You are not allowed to export other faculties.');
            return redirect()->back();
        }
        if ($myFaculty) $builder->where('faculty_id', $myFaculty);
        elseif ($qFaculty) $builder->where('faculty_id', $qFaculty);
    } elseif ($role === 'hod') {
        $myDept = $session->get('department_id') ?: ($admin['department_id'] ?? null);
        if ($qDepartment && $myDept && $qDepartment != $myDept) {
            $session->setFlashdata('error','You are not allowed to export other departments.');
            return redirect()->back();
        }
        if ($myDept) $builder->where('department_id', $myDept);
        elseif ($qDepartment) $builder->where('department_id', $qDepartment);
    } elseif ($role === 'admin') {
        // Optionally disallow generic admin export:
        $session->setFlashdata('error','Unauthorized to export staff data.');
        return redirect()->to('/admin/login');
    } else {
        // superadmin: allow filters if provided
        if ($qFaculty) $builder->where('faculty_id', $qFaculty);
        if ($qDepartment) $builder->where('department_id', $qDepartment);
    }

    $rows = $builder->orderBy('id','ASC')->get()->getResultArray();

    $filename = 'staff_export_' . date('Ymd_His') . '.csv';
    $f = fopen('php://memory', 'w+');

    // header row
    $headers = ['ID','Staff ID','Fullname','Email','Category','Role','Faculty ID','Department ID','Phone','Period From','Period To','Created At'];
    fputcsv($f, $headers);

    foreach ($rows as $r) {
        $line = [
            $r['id'] ?? '',
            $r['staff_id'] ?? '',
            $r['fullname'] ?? '',
            $r['email'] ?? '',
            $r['category'] ?? '',
            $r['role'] ?? '',
            $r['faculty_id'] ?? '',
            $r['department_id'] ?? '',
            $r['phone'] ?? '',
            $r['period_from'] ?? '',
            $r['period_to'] ?? '',
            $r['created_at'] ?? '',
        ];
        fputcsv($f, $line);
    }

    rewind($f);
    $csvData = stream_get_contents($f);
    fclose($f);

    return $this->response
        ->setHeader('Content-Type', 'text/csv')
        ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->setHeader('Pragma', 'no-cache')
        ->setHeader('Expires', '0')
        ->setBody($csvData);
}

public function export2()
{
    $session = session();

    // 1) Authentication: admin session might be stored as 'admin' (array) or just role keys in session
    $admin = $session->get('admin') ?: null;
    $role  = $session->get('role') ?: ($admin['role'] ?? null);
    $adminId = $session->get('admin')['id'] ?? $session->get('user_id') ?? null;

    // If not logged in as an admin, redirect to admin login
    if (! $admin && ! $role) {
        // Keep UX: flash message then redirect
        $session->setFlashdata('error', 'Please login as admin to export data.');
        return redirect()->to('/admin/login');
    }

    // 2) Authorization: decide who can export
    // - superadmin -> export everything
    // - dean -> export only faculty (own faculty or provided faculty param if allowed)
    // - hod  -> export only department
    // - others -> deny
    $allowedRoles = ['superadmin', 'dean', 'hod', 'admin']; // adjust to your app roles
    if (! in_array($role, $allowedRoles)) {
        $session->setFlashdata('error', 'Unauthorized to export staff data.');
        return redirect()->to('/admin/login');
    }

    // 3) Read optional filters from query string
    $request = service('request');
    $qFaculty = $request->getGet('faculty') ?: null;
    $qDepartment = $request->getGet('department') ?: null;

    // 4) Build the query with role scoping
    $db = \Config\Database::connect();
    $builder = $db->table('users'); // or 'staffs' if you export from staffs table

    // Base select (choose the columns you want in CSV)
    $builder->select('id, staff_id, fullname, email, category, faculty_id, department_id, role, phone, period_from, period_to, created_at');

    // Role based scoping
    if ($role === 'dean') {
        // if dean has faculty in session, restrict to that faculty unless explicit allowed
        $myFaculty = $session->get('faculty_id') ?: ($admin['faculty_id'] ?? null);
        if ($qFaculty) {
            // if query param present, use it only if it matches dean's faculty (safety)
            if ($myFaculty && $qFaculty != $myFaculty) {
                $session->setFlashdata('error','You are not allowed to export other faculties.');
                return redirect()->back();
            }
            $builder->where('faculty_id', $qFaculty);
        } elseif ($myFaculty) {
            $builder->where('faculty_id', $myFaculty);
        }
    } elseif ($role === 'hod') {
        $myDept = $session->get('department_id') ?: ($admin['department_id'] ?? null);
        if ($qDepartment) {
            if ($myDept && $qDepartment != $myDept) {
                $session->setFlashdata('error','You are not allowed to export other departments.');
                return redirect()->back();
            }
            $builder->where('department_id', $qDepartment);
        } elseif ($myDept) {
            $builder->where('department_id', $myDept);
        }
    } elseif ($role === 'admin') {
        // generic admin might be limited. Deny export unless superadmin/dean/hod
        $session->setFlashdata('error','Unauthorized to export staff data.');
        return redirect()->to('/admin/login');
    } // superadmin -> no additional where

    // if user requested explicit filters and role allowed it, include them
    if ($qFaculty && $role === 'superadmin') {
        $builder->where('faculty_id', $qFaculty);
    }
    if ($qDepartment && $role === 'superadmin') {
        $builder->where('department_id', $qDepartment);
    }

    // 5) fetch results
    $rows = $builder->orderBy('id','ASC')->get()->getResultArray();

    // 6) build CSV
    $filename = 'staff_export_' . date('Ymd_His') . '.csv';
    $delimiter = ',';

    // Prepare output buffer
    $f = fopen('php://memory', 'w+');

    // Header row - adjust columns as needed
    $headers = ['ID','Staff ID','Fullname','Email','Category','Role','Faculty ID','Department ID','Phone','Period From','Period To','Created At'];
    fputcsv($f, $headers, $delimiter);

    // Data rows
    foreach ($rows as $r) {
        $line = [
            $r['id'] ?? '',
            $r['staff_id'] ?? '',
            $r['fullname'] ?? '',
            $r['email'] ?? '',
            $r['category'] ?? '',
            $r['role'] ?? '',
            $r['faculty_id'] ?? '',
            $r['department_id'] ?? '',
            $r['phone'] ?? '',
            $r['period_from'] ?? '',
            $r['period_to'] ?? '',
            $r['created_at'] ?? '',
        ];
        fputcsv($f, $line, $delimiter);
    }

    // reset pointer and output headers for download
    rewind($f);
    $csvData = stream_get_contents($f);
    fclose($f);

    // Send proper headers and the CSV content
    return $this->response
        ->setHeader('Content-Type', 'text/csv')
        ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->setHeader('Pragma', 'no-cache')
        ->setHeader('Expires', '0')
        ->setBody($csvData);
}





public function exportCsv1()
{
    // Ensure only authorized admins
    $this->guard();

    $session = session();
    $role = $session->get('role') ?? 'staff'; // e.g. 'superadmin','admin','dean','hod','staff'
    $adminUser = $session->get('admin') ?? $session->get('user') ?? [];

    // Use the UserModel (or StaffModel) depending on where your "users" live
    $modelClass = \App\Models\UserModel::class;
    if (! class_exists($modelClass)) {
        // fallback to StaffModel
        $modelClass = \App\Models\StaffModel::class;
    }
    $model = new $modelClass();

    // Determine base query and select columns
    $select = [
        'id','fullname','email',
        'staff_id','category','faculty_id','department_id',
        'phone','role','period_from','period_to','created_at'
    ];
    $builder = $model->select($select);

    // Apply role-based scope
    if (in_array($role, ['superadmin', 'admin'])) {
        // full access - no extra where
    } elseif ($role === 'dean') {
        // restrict to faculty assigned to admin (ensure faculty_id exists in session)
        $facultyId = $session->get('faculty_id') ?? ($adminUser['faculty_id'] ?? null);
        if (! $facultyId) {
            return redirect()->back()->with('error', 'Your account is not assigned to a faculty.');
        }
        $builder->where('faculty_id', (int)$facultyId);
    } elseif ($role === 'hod') {
        $departmentId = $session->get('department_id') ?? ($adminUser['department_id'] ?? null);
        if (! $departmentId) {
            return redirect()->back()->with('error', 'Your account is not assigned to a department.');
        }
        $builder->where('department_id', (int)$departmentId);
    } else {
        return redirect()->back()->with('error', 'Unauthorized to export staff data.');
    }

    // Optional query-parameter filters (e.g. ?faculty=1&department=2&category=academic)
    $qFaculty = $this->request->getGet('faculty');
    $qDept    = $this->request->getGet('department');
    $qCat     = $this->request->getGet('category');

    if ($qFaculty) $builder->where('faculty_id', (int)$qFaculty);
    if ($qDept)    $builder->where('department_id', (int)$qDept);
    if ($qCat)     $builder->where('category', $qCat);

    // Order
    $builder->orderBy('fullname', 'ASC');

    $rows = $builder->get()->getResultArray();

    // If you want to resolve faculty/department names, load their models now
    $facultyNames = [];
    $departmentNames = [];
    if (! empty($rows) && class_exists(\App\Models\FacultyModel::class)) {
        $fm = new \App\Models\FacultyModel();
        $allF = $fm->findAll();
        foreach ($allF as $f) $facultyNames[$f['id']] = $f['name'];
    }
    if (! empty($rows) && class_exists(\App\Models\DepartmentModel::class)) {
        $dm = new \App\Models\DepartmentModel();
        $allD = $dm->findAll();
        foreach ($allD as $d) $departmentNames[$d['id']] = $d['name'];
    }

    // Build CSV in memory (php://temp) so we can set proper headers via response
    $fh = fopen('php://temp', 'w+');

    // BOM for Excel (UTF-8)
    fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Header row
    $headers = ['ID','Fullname','Email','Staff ID','Category','Faculty','Department','Phone','Role','Period From','Period To','Created At'];
    fputcsv($fh, $headers);

    // Rows
    foreach ($rows as $r) {
        $facultyLabel = $facultyNames[$r['faculty_id']] ?? $r['faculty_id'] ?? '';
        $departmentLabel = $departmentNames[$r['department_id']] ?? $r['department_id'] ?? '';

        $line = [
            $r['id'] ?? '',
            $r['fullname'] ?? '',
            $r['email'] ?? '',
            $r['staff_id'] ?? $r['staff_number'] ?? '',
            $r['category'] ?? '',
            $facultyLabel,
            $departmentLabel,
            $r['phone'] ?? '',
            $r['role'] ?? '',
            $r['period_from'] ?? '',
            $r['period_to'] ?? '',
            $r['created_at'] ?? '',
        ];
        fputcsv($fh, $line);
    }

    rewind($fh);
    $csv = stream_get_contents($fh);
    fclose($fh);

    $filename = 'staff_export_' . date('Ymd_His') . '.csv';

    return $this->response
        ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
        ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"')
        ->setBody($csv);
}


}
