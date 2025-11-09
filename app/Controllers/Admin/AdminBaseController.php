<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected $admin;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->admin = session()->get('admin');
    }

    /**
     * Protect admin-only routes
     */
    protected function guard()
    {
        if (! $this->admin) {
            return redirect()->to('/admin/login')->with('error', 'Please log in as administrator.');
        }
    }

    /**
     * Render admin views easily
     */
    protected function render(string $view, array $data = [])
    {
        $data['admin'] = $this->admin;
        echo view('admin/layouts/header', $data);
        echo view($view, $data);
        echo view('admin/layouts/footer');
    }
}
