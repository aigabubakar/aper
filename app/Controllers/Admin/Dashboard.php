<?php namespace App\Controllers\Admin;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Dashboard extends AdminBaseController
{
    public function index()
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
