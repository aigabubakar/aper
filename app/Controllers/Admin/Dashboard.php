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

        $totalUsers = $db->table('users')->countAll();
        $recentUsers = $db->table('users')->orderBy('created_at','DESC')->limit(5)->get()->getResultArray();

        // Use query builder join to fetch faculty/department names if tables exist
        $builder = $db->table('users as u');
        $builder->select('u.*, f.name as faculty_name, d.name as department_name');
        // left join because some users may not have faculty/department set
        if ($db->tableExists('faculties')) {
            $builder->join('faculties f', 'f.id = u.faculty_id', 'left');
        }
        if ($db->tableExists('departments')) {
            $builder->join('departments d', 'd.id = u.department_id', 'left');
        }
        $builder->orderBy('u.id', 'DESC');
        $users = $builder->get()->getResultArray();

        // ensure the variables used by the view always exist
        $currentFacultyId = session()->get('faculty_id') ?? '';
        $currentDepartmentId = session()->get('department_id') ?? '';

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
