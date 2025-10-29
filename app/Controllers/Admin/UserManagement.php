<?php namespace App\Controllers\Admin;

use App\Models\UserModel;

class UserManagement extends AdminBaseController
{
    protected $userModel;

    public function __construct()
    {
        // AdminBaseController initController runs helper and session
        $this->userModel = new UserModel();
    }

    protected function rulesForCreate()
    {
        return [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'staff_id' => 'required|max_length[120]|is_unique[users.staff_id]',
            'password' => 'required|min_length[6]',
            'category' => 'required|in_list[academic,senior_non_academic,junior_non_academic,non_academic]',
        ];
    }

    protected function rulesForUpdate($id)
    {
        // email and staff_id uniqueness must ignore current record
        return [
            'fullname' => 'required|min_length[3]|max_length[255]',
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'staff_id' => "required|max_length[120]|is_unique[users.staff_id,id,{$id}]",
            'category' => 'required|in_list[academic,senior_non_academic,junior_non_academic,non_academic]',
        ];
    }

    public function index()
    {
        $this->guard();

        $q = $this->request->getGet('q');
        $perPage = 20;
        $page = (int) ($this->request->getGet('page') ?? 1);

        $builder = $this->userModel;
        if ($q) {
            $builder = $builder->like('fullname', $q)->orLike('email', $q)->orLike('staff_id', $q);
        }
        $users = $builder->orderBy('created_at','DESC')->paginate($perPage);
        $pager = $this->userModel->pager;

        return view('admin/users/index', [
            'users' => $users,
            'pager' => $pager,
            'q'     => $q,
        ]);
    }

    public function create()
    {
        $this->guard();
        return view('admin/users/form', ['method'=>'create','user'=>null]);
    }

    public function store()
    {
        $this->guard();
        $rules = $this->rulesForCreate();
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'staff_id' => $this->request->getPost('staff_id'),
            'fullname' => $this->request->getPost('fullname'),
            'email'    => strtolower(trim($this->request->getPost('email'))),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'category' => $this->request->getPost('category'),
            'phone'    => $this->request->getPost('phone') ?: null,
            'period_from' => $this->request->getPost('period_from') ?: null,
            'period_to' => $this->request->getPost('period_to') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->userModel->insert($data);
        return redirect()->to('/admin/users')->with('success','User created.');
    }

    public function show($id = null)
    {
        $this->guard();
        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors',['User not found']);
        return view('admin/users/show', ['user'=>$user]);
    }

    public function edit($id = null)
    {
        $this->guard();
        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors',['User not found']);
        return view('admin/users/form', ['method'=>'edit','user'=>$user]);
    }

    public function update($id = null)
    {
        $this->guard();
        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors',['User not found']);

        $rules = $this->rulesForUpdate($id);
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $update = [
            'staff_id' => $this->request->getPost('staff_id'),
            'fullname' => $this->request->getPost('fullname'),
            'email'    => strtolower(trim($this->request->getPost('email'))),
            'category' => $this->request->getPost('category'),
            'phone'    => $this->request->getPost('phone') ?: null,
            'period_from' => $this->request->getPost('period_from') ?: null,
            'period_to' => $this->request->getPost('period_to') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // optional password change
        $pw = $this->request->getPost('password');
        if (! empty($pw)) $update['password'] = password_hash($pw, PASSWORD_DEFAULT);

        $this->userModel->update($id, $update);
        return redirect()->to('/admin/users')->with('success','User updated.');
    }

    public function delete($id = null)
    {
        $this->guard();
        $user = $this->userModel->find($id);
        if (! $user) return redirect()->to('/admin/users')->with('errors',['User not found']);
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success','User deleted.');
    }
}
