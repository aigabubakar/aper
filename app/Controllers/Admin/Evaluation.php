<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use Config\Services;

class Evaluation extends AdminBaseController
{
    protected $request;
    protected $session;
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
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
        $this->guard();

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

        $this->guardStaffScope($staff);

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
        $this->guard();

        if (! $this->userModel) {
            return $this->response->setJSON(['success' => false, 'message' => 'User model not available'])->setStatusCode(500);
        }

        $post = $this->request->getPost();
        $staffId = (int) ($post['staff_id'] ?? 0);
        $category = $post['category'] ?? 'generic';

        if (! $staffId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing staff id'])->setStatusCode(422);
        }

        // fetch user to ensure it still exists and check if locked
        $user = $this->userModel->find($staffId);
        if (! $user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Staff not found'])->setStatusCode(404);
        }

        if (! empty($user['staff_evaluation_comment'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'This evaluation has been acknowledged and locked by the staff member, and cannot be modified.'])->setStatusCode(403);
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
            $rules['teaching_score'] = 'required|integer';
            $rules['research_score'] = 'required|integer';
            $rules['supervision_score'] = 'required|integer';
            $rules['service_score'] = 'required|integer';
            $extraFields = ['teaching_score', 'research_score', 'supervision_score', 'service_score'];
        } elseif ($category === 'senior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['skills_score'] = 'required|integer';
            $rules['punctuality_score'] = 'required|integer';
            $rules['teamwork_score'] = 'required|integer';
            $rules['initiative_score'] = 'required|integer';
            $extraFields = ['skills_score', 'punctuality_score', 'teamwork_score', 'initiative_score'];
        } elseif ($category === 'junior_non_academic') {
            $rules['overall_score'] = 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]';
            $rules['skills_score'] = 'required|integer';
            $rules['punctuality_score'] = 'required|integer';
            $rules['teamwork_score'] = 'required|integer';
            $rules['initiative_score'] = 'required|integer';
            $extraFields = ['skills_score', 'punctuality_score', 'teamwork_score', 'initiative_score'];
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

        // guard scope
        $this->guardStaffScope($user);

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
            $update['evaluation_teaching'] = (int) $post['teaching_score'];
            $update['evaluation_research'] = (int) $post['research_score'];
        } elseif ($category === 'senior_non_academic') {
            $update['evaluation_admin_performance'] = (int) $post['overall_score'];
        } elseif ($category === 'junior_non_academic') {
            $update['evaluation_discipline'] = (int) $post['overall_score'];
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
