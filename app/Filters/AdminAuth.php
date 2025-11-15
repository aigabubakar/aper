<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    /**
     * Run the filter.
     *
     * @param RequestInterface  $request
     * @param array|null        $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // adjust according to how you store admin session
        $admin = $session->get('admin');

        if (empty($admin) || ! is_array($admin)) {
            // If AJAX, return JSON unauthorized
            if ($request->isAJAX()) {
                $response = service('response');
                return $response->setStatusCode(401)
                                ->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            // otherwise redirect to admin login
            return redirect()->to('/admin/login');
        }

        // allow request to continue
        return null;
    }

    /**
     * We don't need to modify the response after controller; leave blank.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
    
}
