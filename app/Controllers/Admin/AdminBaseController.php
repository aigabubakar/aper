<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected $adminSession;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->adminSession = session();
        helper(['url','form']);
    }

    protected function guard()
    {
        if (! $this->adminSession->get('isAdminLoggedIn')) {
            return redirect()->to('/admin/login');
        }
    }
}
