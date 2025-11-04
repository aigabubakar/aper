<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StaffModel;

class AdminStaffController extends BaseController
{
    protected $staffModel;

    public function __construct()
    {
        $this->staffModel = new StaffModel();
    }

    public function index()
    {
        $data['staff'] = $this->staffModel->findAll();
        $data['title'] = 'Manage Staff';
        return view('admin/staff/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Add New Staff';
        return view('admin/staff/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'fullname' => 'required',
            'email' => 'required|valid_email|is_unique[staff.email]',
            'category' => 'required',
            'role' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $this->staffModel->save([
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'category' => $this->request->getPost('category'),
            'role' => $this->request->getPost('role'),
            'password' => password_hash('default123', PASSWORD_DEFAULT),
        ]);

        return redirect()->to('admin/staff')->with('success', 'Staff added successfully');
    }

    public function edit($id)
    {
        $data['staff'] = $this->staffModel->find($id);
        $data['title'] = 'Edit Staff';
        return view('admin/staff/edit', $data);
    }

    public function update($id)
    {
        $this->staffModel->update($id, [
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'category' => $this->request->getPost('category'),
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to('admin/staff')->with('success', 'Staff updated successfully');
    }

    public function delete($id)
    {
        $this->staffModel->delete($id);
        return redirect()->to('admin/staff')->with('success', 'Staff deleted successfully');
    }
}
