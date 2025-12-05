<?php namespace App\Controllers\Admin;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Dashboard extends AdminBaseController
{
    
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

// in App\Controllers\Admin\Dashboard.php

public function viewForm($id)
{
    $um = new \App\Models\UserModel();
    $user = $um->find((int)$id);
    if (! $user) return $this->response->setStatusCode(404)->setBody('Not found');

    // render only partial (no full layout)
    return view('admin/dashboard/partials/view_form', ['user'=>$user]);
}

public function editForm($id)
{
    $um = new \App\Models\UserModel();
    $user = $um->find((int)$id);
    if (! $user) return $this->response->setStatusCode(404)->setBody('Not found');

    // render only partial (no full layout)
    return view('admin/dashboard/partials/edit_form', ['user'=>$user]);
}

public function update($id)
{
    helper('form');
    $um = new \App\Models\UserModel();
    $user = $um->find((int)$id);
    if (! $user) return $this->response->setJSON(['success'=>false,'message'=>'User not found'])->setStatusCode(404);

    $rules = [
      'fullname' => 'required|min_length[3]',
      'email' => 'required|valid_email',
      // etc — adapt to fields you allow
    ];
    if (! $this->validate($rules)) {
      return $this->response->setJSON(['success'=>false,'errors'=>$this->validator->getErrors()])->setStatusCode(422);
    }

    $data = [
      'fullname' => $this->request->getPost('fullname'),
      'email' => strtolower($this->request->getPost('email')),
      // other fields...
    ];

    try {
      $um->update($id, $data);
      return $this->response->setJSON(['success'=>true,'message'=>'Saved','updated'=>['id'=>$id]]);
    } catch (\Throwable $e) {
      log_message('error','Update failed: '.$e->getMessage());
      return $this->response->setJSON(['success'=>false,'message'=>'Server error'])->setStatusCode(500);
    }
}


public function index1()
    {
        // guard called by AdminBaseController->guard()
        $this->guard();

        $db = \Config\Database::connect();

        // total users (choose appropriate table - prefer 'users' if present)
        $usersTable = $db->tableExists('users') ? 'users' : ($db->tableExists('staffs') ? 'staffs' : null);

        $totalUsers = 0;
        $recentUsers = [];
        $users = [];

        if ($usersTable) {
            $totalUsers = $db->table($usersTable)->countAll();
            $recentUsers = $db->table($usersTable)->orderBy('created_at','DESC')->limit(5)->get()->getResultArray();

            // Build query and join faculty/department only if columns exist
            $builder = $db->table($usersTable . ' as u');
            $builder->select('u.*');

            $userFields = $db->getFieldNames($usersTable);

            if ($db->tableExists('faculties') && in_array('faculty_id', $userFields)) {
                $builder->select('f.name as faculty_name');
                $builder->join('faculties f', 'f.id = u.faculty_id', 'left');
            }
            if ($db->tableExists('departments') && in_array('department_id', $userFields)) {
                $builder->select('d.name as department_name');
                $builder->join('departments d', 'd.id = u.department_id', 'left');
            }

            $builder->orderBy('u.id', 'DESC');
            $users = $builder->get()->getResultArray();
        }

        // ensure the variables used by the view always exist
        $currentFacultyId = session()->get('admin')['faculty_id'] ?? session()->get('faculty_id') ?? '';
        $currentDepartmentId = session()->get('admin')['department_id'] ?? session()->get('department_id') ?? '';

        return view('admin/dashboard/index', [
            'admin' => session()->get('admin') ?? null,
            'users' => $users,
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'currentFacultyId' => $currentFacultyId,
            'currentDepartmentId' => $currentDepartmentId,
        ]);
    }


}

