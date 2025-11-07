<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BaseAdminController extends BaseController
{
    protected $session;
    protected $userModel;

    public function initController(...$params)
    {
        parent::initController(...$params);
        $this->session = session();
        $this->userModel = new \App\Models\UserModel();
        // optional: set default view data
        $this->data['adminUser'] = $this->session->get('user') ?? [
            'fullname' => $this->session->get('fullname'),
            'role' => $this->session->get('role'),
        ];
    }
}
