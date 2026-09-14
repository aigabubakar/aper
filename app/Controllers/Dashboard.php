<?php namespace App\Controllers;

use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // quick check: ensure logged in
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/check-email')->with('errors', ['Please login first.']);
        }

        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));
        if (! $user) {
            session()->destroy();
            return redirect()->to('/check-email')->with('errors', ['User session invalid.']);
        }

        return view('pages/dashboard', ['user' => $user]);
    }

    /**
     * Staff saves comment on evaluation, locking it from further alterations.
     * POST /profile/save-evaluation-comment
     */
    public function saveEvaluationComment()
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please log in first.'])->setStatusCode(401);
        }

        $uid = session()->get('user_id');
        $userModel = new UserModel();
        $user = $userModel->find($uid);

        if (! $user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found.'])->setStatusCode(404);
        }

        // check if already evaluated
        if (empty($user['evaluation_overall_score']) && $user['evaluation_overall_score'] !== 0 && $user['evaluation_overall_score'] !== '0') {
            return $this->response->setJSON(['success' => false, 'message' => 'You have not been evaluated yet.'])->setStatusCode(400);
        }

        // check if already locked
        if (! empty($user['staff_evaluation_comment'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'This evaluation is already locked.'])->setStatusCode(403);
        }

        $comment = trim($this->request->getPost('staff_comment') ?? '');
        if (empty($comment)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comment cannot be empty.'])->setStatusCode(422);
        }

        try {
            $userModel->update($uid, [
                'staff_evaluation_comment' => $comment,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'saveEvaluationComment error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error while saving comment.'])->setStatusCode(500);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Comment saved and evaluation locked successfully.']);
    }
}
