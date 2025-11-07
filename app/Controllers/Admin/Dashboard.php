<?php namespace App\Controllers\Admin;

class Dashboard extends AdminBaseController
{
    public function index()
    {
        // guard called by AdminBaseController->guard() or adminAuth filter depending on your setup
        $this->guard();

        // quick stats (example)
        $db = \Config\Database::connect();
        $totalUsers = $db->table('users')->countAll();
        $recentUsers = $db->table('users')->orderBy('created_at','DESC')->limit(5)->get()->getResultArray();

        return view('admin/dashboard/index', [
            'totalUsers' => $totalUsers,
            'recentUsers' => $recentUsers,
        ]);
    }
}






