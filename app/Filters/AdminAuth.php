<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn') || ! in_array($session->get('role'), ['admin','superadmin'])) {
            // redirect to admin login (works both ajax & normal)
            $url = site_url('admin/login');
            if ($request->isAJAX()) {
                return service('response')->setJSON(['success'=>false,'message'=>'Unauthorized','redirect'=>$url])->setStatusCode(401);
            }
            return redirect()->to($url);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
