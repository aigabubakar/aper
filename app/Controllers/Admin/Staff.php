<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Models\StaffModel;

class Staff extends BaseAdminController
{
    public function index()
    {
        $model = new StaffModel();
        $data = [
            'title' => 'Staff List',
            'staff' => $model->findAll()
        ];

        return $this->render('admin/staff/index', $data);
    }

    public function create()
    {
        return $this->render('admin/staff/form', ['title' => 'Add Staff']);
    }

    public function store()
    {
        $model = new StaffModel();
        $data = $this->request->getPost();
        $model->insert($data);

        return redirect()->to('/admin/staff')->with('success', 'Staff created successfully');
    }

    public function edit($id)
    {
        $model = new StaffModel();
        $data = [
            'title' => 'Edit Staff',
            'staff' => $model->find($id)
        ];
        return $this->render('admin/staff/form', $data);
    }

    public function update($id)
    {
        $model = new StaffModel();
        $data = $this->request->getPost();
        $model->update($id, $data);
        return redirect()->to('/admin/staff')->with('success', 'Staff updated successfully');
    }

    public function view($id)
    {
        $model = new StaffModel();
        $data = [
            'title' => 'View Staff',
            'staff' => $model->find($id)
        ];
        return $this->render('admin/staff/view', $data);
    }

    public function delete($id)
    {
        $model = new StaffModel();
        $model->delete($id);
        return redirect()->to('/admin/staff')->with('success', 'Staff deleted successfully');
    }
}
