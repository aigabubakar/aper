<?php namespace App\Controllers\Admin;

use App\Models\UserModel;

class Dashboard extends AdminBaseController
{
    public function index()
    {
        // guard called by AdminBaseController->guard() 
        $this->guard();

         // quick stats (example)
        $db = \Config\Database::connect();
        $totalUsers = $db->table('users')->countAll();
        $recentUsers = $db->table('users')->orderBy('created_at','DESC')->limit(5)->get()->getResultArray();

        $userModel = new UserModel();
        $users = $userModel->orderBy('id', 'DESC')->findAll();

        return view('admin/dashboard/index', [
            'admin' => $this->admin,
            'users' => $users,
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
            'currentFacultyId' => session()->get('faculty_id') ?? '',
           'currentDepartmentId' => session()->get('department_id') ?? '',
        ]);
    }
}


