<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session   = session();
        helper(['form','url']);
    }

    protected function guardAdmin()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('errors', ['Please login to continue.']);
        }
        if ($this->session->get('role') !== 'admin') {
            // you can change behaviour as needed
            throw \CodeIgniter\Exceptions\PageForbiddenException::forPageForbidden();
        }
    }

    // LIST
    public function index()
    {
        $this->guardAdmin();

        $q = $this->request->getGet('q');
        $perPage = 20;
        if ($q) {
            $users = $this->userModel->like('fullname', $q)
                                     ->orLike('email', $q)
                                     ->orderBy('created_at','DESC')
                                     ->paginate($perPage);
        } else {
            $users = $this->userModel->orderBy('created_at','DESC')->paginate($perPage);
        }

        return view('admin/users/index', [
            'users' => $users,
            'pager' => $this->userModel->pager,
            'q'     => $q,
        ]);
    }

    // CREATE form
    public function create()
    {
        $this->guardAdmin();
        return view('admin/users/form', ['method'=>'create']);
    }

    // STORE new user
    public function store()
    {
        $this->guardAdmin();

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,hod,dean,staff]',
            'category' => 'required|in_list[academic,senior_non_academic,junior_non_academic,non_academic]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'fullname' => $this->request->getPost('fullname'),
            'email'    => strtolower($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'category' => $this->request->getPost('category'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/users')->with('success', 'User created successfully.');
    }

    // EDIT form
    public function edit($id = null)
    {
        $this->guardAdmin();

        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors', ['User not found.']);

        return view('admin/users/form', ['method'=>'edit','user'=>$user]);
    }

    // UPDATE user
    public function update($id = null)
    {
        $this->guardAdmin();

        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors', ['User not found.']);

        $rules = [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[admin,hod,dean,staff]',
            'category' => 'required|in_list[academic,senior_non_academic,junior_non_academic,non_academic]',
        ];

        // if email changed, ensure unique
        $email = strtolower($this->request->getPost('email'));
        if ($email !== $user['email']) {
            $rules['email'] .= '|is_unique[users.email]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $update = [
            'fullname' => $this->request->getPost('fullname'),
            'email'    => $email,
            'role'     => $this->request->getPost('role'),
            'category' => $this->request->getPost('category'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // update password if provided
        $password = $this->request->getPost('password');
        if ($password) $update['password'] = password_hash($password, PASSWORD_DEFAULT);

        $this->userModel->update($id, $update);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
    }

    // DELETE user (POST)
    public function delete($id = null)
    {
        $this->guardAdmin();

        if (! $this->userModel->find($id)) {
            return $this->response->setStatusCode(404)->setBody('User not found');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User deleted.');
    }
}
