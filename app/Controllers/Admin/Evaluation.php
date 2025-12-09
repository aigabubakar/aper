<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Services;

class Evaluation extends BaseController
{
    protected $request;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->request = Services::request();
        $this->session = session();

        // prefer UserModel (we persist into users table)
        if (class_exists('\App\Models\UserModel')) {
            $this->userModel = new \App\Models\UserModel();
        } elseif (class_exists('\App\Models\StaffModel')) {
            $this->userModel = new \App\Models\StaffModel();
        } else {
            $this->userModel = null;
        }

        helper(['form', 'url']);
    }

    /**
     * AJAX: return HTML partial for evaluation form
     * GET params: id, category
     */
    public function loadForm()
    {
        // must be logged in as admin (adjust to your guard)
        if (! $this->session->get('isAdminLoggedIn') && ! $this->session->get('isLoggedIn')) {
            return view('admin/evaluation/partial_error', ['message' => 'Please login as admin to evaluate.']);
        }

        $id = (int) $this->request->getGet('id');
        $category = $this->request->getGet('category') ?? 'generic';

        if (! $id) {
            return view('admin/evaluation/partial_error', ['message' => 'Missing staff id.']);
        }

        if (! $this->userModel) {
            return view('admin/evaluation/partial_error', ['message' => 'User model not available.']);
        }

        $staff = $this->userModel->find($id);
        if (! $staff) {
            return view('admin/evaluation/partial_error', ['message' => 'Staff record not found.']);
        }

        // map category -> view file
        $map = [
            'academic' => 'admin/evaluation/form_academic',
            'senior_non_academic' => 'admin/evaluation/form_senior_non_academic',
            'junior_non_academic' => 'admin/evaluation/form_junior_non_academic',
        ];
        $viewName = $map[$category] ?? 'admin/evaluation/form_generic';

        // fallback if file missing
        if (! is_file(APPPATH . "Views/{$viewName}.php")) {
            $viewName = 'admin/evaluation/form_generic';
        }

        // pass current evaluation values from user row so form can pre-fill
        return view($viewName, [
            'staff'    => $staff,
            'category' => $category,
        ]);
    }

    /**
     * AJAX: handle evaluation submission and save into users table
     * Expects AJAX POST; returns JSON
     */
    public function submit()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request'])->setStatusCode(400);
        }

        // auth
        if (! $this->session->get('isAdminLoggedIn') && ! $this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please login as admin'])->setStatusCode(401);
        }

        if (! $this->userModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'User model not available'])->setStatusCode(500);
        }

        $post = $this->request->getPost();
        $staffId = (int) ($post['staff_id'] ?? 0);
        $category = $post['category'] ?? 'generic';

        if (! $staffId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing staff id'])->setStatusCode(422);
        }

        // base rules
        $rules = [
            'staff_id' => 'required|integer',
            'category' => 'required'
        ];

        // category-specific fields and validation
        $extraFields = []; // will collect field names to persist into evaluation_meta if needed

        if ($category === 'academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['teaching'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['research'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $extraFields = ['teaching', 'research'];
        } elseif ($category === 'senior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['admin_performance'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $extraFields = ['admin_performance'];
        } elseif ($category === 'junior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['discipline'] = 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $extraFields = ['discipline'];
        } else {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
        }

        // validate
        if (! $this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(422);
        }

        // fetch user to ensure it still exists
        $user = $this->userModel->find($staffId);
        if (! $user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff not found'])->setStatusCode(404);
        }

        // Build update payload for users table.
        // We store main fields under column names prefixed with evaluation_*
        // (Make sure these columns exist on your users table - SQL provided below).
        $update = [];

        // Common
        $update['evaluation_overall_score'] = (int) $post['overall_score'];
        $update['evaluation_comments'] = trim($post['comments'] ?? '') ?: null;
        $update['evaluation_by'] = $this->session->get('admin_id') ?? $this->session->get('user_id') ?? null;
        $update['evaluation_at'] = date('Y-m-d H:i:s');

        // Category-specific direct columns (if present in DB)
        if ($category === 'academic') {
            $update['evaluation_teaching'] = isset($post['teaching']) && $post['teaching'] !== '' ? (int)$post['teaching'] : null;
            $update['evaluation_research'] = isset($post['research']) && $post['research'] !== '' ? (int)$post['research'] : null;
        } elseif ($category === 'senior_non_academic') {
            $update['evaluation_admin_performance'] = isset($post['admin_performance']) && $post['admin_performance'] !== '' ? (int)$post['admin_performance'] : null;
        } elseif ($category === 'junior_non_academic') {
            $update['evaluation_discipline'] = isset($post['discipline']) && $post['discipline'] !== '' ? (int)$post['discipline'] : null;
        }

        // Store any extra metrics into JSON meta column (evaluation_meta) — merge with existing meta if present
        $meta = [];
        foreach ($extraFields as $f) {
            if (isset($post[$f]) && $post[$f] !== '') {
                $meta[$f] = (int)$post[$f];
            }
        }
        // also store raw submitted category and overall_score for completeness
        $meta['category'] = $category;
        $meta['overall_score'] = (int)$post['overall_score'];

        // merge with existing evaluation_meta if present and is JSON
        if (! empty($user['evaluation_meta'])) {
            $existing = $user['evaluation_meta'];
            if (is_string($existing)) {
                $decoded = json_decode($existing, true);
                if (is_array($decoded)) {
                    $meta = array_merge($decoded, $meta);
                }
            } elseif (is_array($existing)) {
                $meta = array_merge($existing, $meta);
            }
        }
        $update['evaluation_meta'] = json_encode($meta);

        // Also save category into users table if you want (optional)
        $update['category'] = $category;

        // perform update
        try {
            $this->userModel->update($staffId, $update);
        } catch (\Throwable $e) {
            log_message('error', 'Evaluation save failed (users table): ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error saving evaluation'])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Evaluation saved successfully']);
    }
}
