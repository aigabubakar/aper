<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Evaluation extends BaseController
{
    protected $request;
    protected $evaluationModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->request = Services::request();
        $this->session = session();

        // optional: use EvaluationModel (create below) to persist
        if (class_exists('\App\Models\EvaluationModel')) {
            $this->evaluationModel = new \App\Models\EvaluationModel();
        }

        // user model used to load staff info; falls back gracefully
        if (class_exists('\App\Models\UserModel')) {
            $this->userModel = new \App\Models\UserModel();
        } elseif (class_exists('\App\Models\StaffModel')) {
            $this->userModel = new \App\Models\StaffModel();
        }

        helper(['form', 'url']);
    }

    /**
     * AJAX: return HTML partial for evaluation form
     * GET: ?id=123&category=academic
     */
    public function loadForm()
    {
        // require admin login (adjust to your guard implementation)
        if (! $this->session->get('isAdminLoggedIn') && ! $this->session->get('isLoggedIn')) {
            // return HTML fragment with message (modal expects HTML)
            return view('admin/evaluation/partial_error', ['message' => 'Please login as admin to evaluate.']);
        }

        $id = (int) $this->request->getGet('id');
        $category = $this->request->getGet('category') ?? 'generic';

        if (! $id) {
            return view('admin/evaluation/partial_error', ['message' => 'Missing staff id.']);
        }

        $staff = $this->userModel ? $this->userModel->find($id) : null;
        if (! $staff) {
            return view('admin/evaluation/partial_error', ['message' => 'Staff record not found.']);
        }

        // map category to view file (sanitise category)
        $map = [
            'academic' => 'admin/evaluation/form_academic',
            'senior_non_academic' => 'admin/evaluation/form_senior_non_academic',
            'junior_non_academic' => 'admin/evaluation/form_junior_non_academic',
        ];
        $viewName = $map[$category] ?? 'admin/evaluation/form_generic';

        // ensure view exists; fallback to generic
        if (! is_file(APPPATH . "Views/{$viewName}.php")) {
            $viewName = 'admin/evaluation/form_generic';
        }

        // Return HTML partial (no layout)
        return view($viewName, [
            'staff' => $staff,
            'category' => $category,
        ]);
    }

    /**
     * AJAX: handle evaluation submission
     * Expects AJAX POST; returns JSON
     */
    public function submit()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request'])->setStatusCode(400);
        }

        // require admin login
        if (! $this->session->get('isAdminLoggedIn') && ! $this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login as admin'])->setStatusCode(401);
        }

        $post = $this->request->getPost();
        $category = $post['category'] ?? 'generic';

        // base rules; extend per category
        $rules = [
            'staff_id' => 'required|integer',
            'category' => 'required'
        ];

        if ($category === 'academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['teaching'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['research'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
        } elseif ($category === 'senior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['admin_performance'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
        } elseif ($category === 'junior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['discipline'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
        } else {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
        }

        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        // Prepare record to save
        $record = [
            'staff_id' => (int)$post['staff_id'],
            'category' => $category,
            'overall_score' => $post['overall_score'] ?? null,
            'comments' => $post['comments'] ?? null,
            'meta' => json_encode(array_intersect_key($post, array_flip(['teaching','research','admin_performance','discipline']))),
            'created_by' => $this->session->get('admin_id') ?? $this->session->get('user_id') ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // if model exists, save it; otherwise write directly to DB
        try {
            if ($this->evaluationModel) {
                $this->evaluationModel->insert($record);
            } else {
                $db = \Config\Database::connect();
                $db->table('evaluations')->insert($record);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Evaluation save failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error saving evaluation'])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Evaluation saved successfully']);
    }
}
