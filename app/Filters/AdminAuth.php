<?php namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminAuth implements FilterInterface
{
    /**
     * Check admin session + role restrictions
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $admin   = $session->get('admin');   // example: ['id'=>1, 'fullname'=>'...', 'role'=>'superadmin']

        // 1. Not logged in at all
        if (empty($admin) || !isset($admin['role'])) {
            return redirect()->to('/admin/login');
        }

        //---------------------------------------------------------
        // 2. ROLE CHECK (only superadmin allowed)
        //---------------------------------------------------------
        $allowedRoles = ['superadmin', 'admin', 'hod', 'dean'];  // <== YOU CAN ADD MORE ROLES HERE

        if (!in_array($admin['role'], $allowedRoles)) {

            // If AJAX request → return JSON 403
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'success' => false,
                        'message' => 'You are not authorized to access this resource.'
                    ]);
            }

            // Normal redirect with flash message
            $session->setFlashdata('error', 'Access denied: Insufficient permissions.');
            return redirect()->to('/admin'); // back to admin dashboard
        }

        return null; // allow request
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no post-processing needed
    }
}



//<?php namespace App\Filters;

//use CodeIgniter\HTTP\RequestInterface;
//use CodeIgniter\HTTP\ResponseInterface;
//use CodeIgniter\Filters\FilterInterface;

// class AdminAuth implements FilterInterface
// {
//     public function before(RequestInterface $request, $arguments = null)
//     {
//         $session = session();
//         if (! $session->get('isLoggedIn') || ! in_array($session->get('role'), ['admin','superadmin'])) {
//             // redirect to admin login (works both ajax & normal)
//             $url = site_url('admin/login');
//             if ($request->isAJAX()) {
//                 return service('response')->setJSON(['success'=>false,'message'=>'Unauthorized','redirect'=>$url])->setStatusCode(401);
//             }
//             return redirect()->to($url);
//         }
//     }

//     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
//     {
//         // no-op
//     }
// }



// <?php namespace App\Filters;

// use CodeIgniter\HTTP\RequestInterface;
// use CodeIgniter\HTTP\ResponseInterface;
// use CodeIgniter\Filters\FilterInterface;

// class AdminAuth implements FilterInterface
// {
//     /**
//      * Run the filter.
//      *
//      * @param RequestInterface  $request
//      * @param array|null        $arguments
//      * @return mixed
//      */
//     public function before(RequestInterface $request, $arguments = null)
//     {
//         $session = session();
//         // adjust according to how you store admin session
//         $admin = $session->get('admin');

//         if (empty($admin) || ! is_array($admin)) {
//             // If AJAX, return JSON unauthorized
//             if ($request->isAJAX()) {
//                 $response = service('response');
//                 return $response->setStatusCode(401)
//                                 ->setJSON(['success' => false, 'message' => 'Unauthorized']);
//             }

//             // otherwise redirect to admin login
//             return redirect()->to('/admin/login');
//         }

//         // allow request to continue
//         return null;
//     }

//     /**
//      * We don't need to modify the response after controller; leave blank.
//      */
//     public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
//     {
//         // no-op
//     }
// }
