<?php namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ProfileWizard extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
        helper(['form','url']);
    }

    /**
     * Build per-category steps configuration
     */
    protected function getStepsConfig(string $category)
    {
        $commonPersonal = [
            'title'  => 'Personal',
            'fields' => ['phone','dob','gender'],
            'rules'  => [
                'phone' => 'permit_empty|max_length[50]',
                'dob'   => 'permit_empty|valid_date',
                'gender'=> 'permit_empty|in_list[male,female,other]',
            ],
        ];

        $academicEmployment = [
            'title' => 'Employment (Academic)',
            'fields' => ['department','designation','period_from','period_to','academic_rank','courses_taught'],
            'rules' => [
                'department' => 'required|max_length[120]',
                'designation' => 'permit_empty|max_length[120]',
                // category removed from per-step validation to avoid conflicts
                'period_from' => 'required|integer',
                'period_to' => 'required|integer',
                'academic_rank' => 'permit_empty|max_length[80]',
                'courses_taught' => 'permit_empty|max_length[1000]',
            ],
        ];

        $nonAcademicEmployment = [
            'title' => 'Employment',
            'fields' => ['department','designation','period_from','period_to'],
            'rules' => [
                'department' => 'required|max_length[120]',
                'designation' => 'permit_empty|max_length[120]',
                'period_from' => 'required|integer',
                'period_to' => 'required|integer',
            ],
        ];

        $professionalCommon = [
            'title' => 'Professional',
            'fields' => ['qualifications','avatar','emergency_contact'],
            'rules' => [
                'qualifications' => 'permit_empty|max_length[2000]',
                // avatar file rules are enforced separately during file handling; keep gentle rule here
                'avatar' => 'permit_empty',
                'emergency_contact' => 'permit_empty|max_length[100]',
            ],
        ];

        switch ($category) {
            case 'academic':
                return [
                    1 => $commonPersonal,
                    2 => $academicEmployment,
                    3 => $professionalCommon,
                ];
            case 'junior_non_academic':
                return [
                    1 => $commonPersonal,
                    2 => $nonAcademicEmployment,
                    3 => $professionalCommon,
                ];
            case 'non_academic':
            default:
                return [
                    1 => $commonPersonal,
                    2 => $nonAcademicEmployment,
                    3 => $professionalCommon,
                ];
        }
    }

    /**
     * Render the wizard. Always passes stepsConfig to the view.
     */
    public function index()
    {
        if (! $this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('errors', ['Please login to continue.']);
        }

        $userId = (int) $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        if (! $user) {
            $this->session->destroy();
            return redirect()->to('/login')->with('errors', ['User not found.']);
        }

        $category = $user['category'] ?? $this->session->get('category') ?? 'non_academic';
        $stepsConfig = $this->getStepsConfig($category);

        // If the user has just registered we might have set a tempdata flag 'just_registered'
        $justRegistered = $this->session->getTempdata('just_registered') ?? false;
        // If you used setTempdata('just_registered', true, $seconds), you can pass that lifetime to the view if needed.
        $showSeconds = $this->session->getTempdata('just_registered_seconds') ?? 3;

        // PASS ALL required variables to the view
        return view('auth/wizard', [
            'user' => $user,
            'category' => $category,
            'stepsConfig' => $stepsConfig,
            'justRegistered' => $justRegistered,
            'showSeconds' => (int)$showSeconds,
        ]);
    }

    /**
     * Save a step (AJAX)
     */
    public function saveStep()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405);
        }
        if (! $this->session->get('isLoggedIn')) {
            return $this->response->setJSON(['success'=>false,'message'=>'Not authenticated'])->setStatusCode(401);
        }

        $step = (int) $this->request->getPost('step');
        $userId = (int) $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        if (! $user) {
            return $this->response->setJSON(['success'=>false,'message'=>'User not found'])->setStatusCode(404);
        }

        $category = $user['category'] ?? $this->session->get('category') ?? 'non_academic';
        $stepsConfig = $this->getStepsConfig($category);

        if (! isset($stepsConfig[$step])) {
            return $this->response->setJSON(['success'=>false,'message'=>'Invalid step'])->setStatusCode(400);
        }

        $rules = $stepsConfig[$step]['rules'] ?? [];
        if (! empty($rules) && ! $this->validate($rules)) {
            return $this->response->setJSON(['success'=>false,'errors'=>$this->validator->getErrors()])->setStatusCode(422);
        }

        $fields = $stepsConfig[$step]['fields'] ?? [];
        $data = [];
        foreach ($fields as $f) {
            // Skip avatar here — handled below
            if ($f === 'avatar') continue;

            $val = $this->request->getPost($f);
            if ($val === null) continue;

            // type casts for known numeric fields
            if (in_array($f, ['period_from','period_to'])) {
                $data[$f] = $val === '' ? null : (int)$val;
            } else {
                $data[$f] = $val === '' ? null : $val;
            }
        }

        // handle avatar file upload (store in public/uploads/avatars so base_url() works)
        if (in_array('avatar', $fields)) {
            $avatar = $this->request->getFile('avatar');
            if ($avatar && $avatar->isValid() && ! $avatar->hasMoved()) {
                // verify basic file constraints
                if (! $avatar->isValid()) {
                    return $this->response->setJSON(['success'=>false,'message'=>'Invalid avatar upload'])->setStatusCode(422);
                }
                $targetDir = FCPATH . 'uploads/avatars';
                if (! is_dir($targetDir)) {
                    if (! mkdir($targetDir, 0755, true) && ! is_dir($targetDir)) {
                        // failed to create directory
                        log_message('error', 'Failed to create avatar upload directory: ' . $targetDir);
                        return $this->response->setJSON(['success'=>false,'message'=>'Server error saving avatar'])->setStatusCode(500);
                    }
                }

                $name = $avatar->getRandomName();
                try {
                    $avatar->move($targetDir, $name);
                } catch (\Throwable $e) {
                    log_message('error', 'Avatar move failed: ' . $e->getMessage());
                    return $this->response->setJSON(['success'=>false,'message'=>'Server error saving avatar: '.$e->getMessage()])->setStatusCode(500);
                }

                // store path relative to public (so base_url() . '/' . avatar works)
                $data['avatar'] = 'uploads/avatars/' . $name;

                // remove previous avatar if present (use FCPATH + stored relative path)
                if (! empty($user['avatar'])) {
                    $oldPath = FCPATH . $user['avatar'];
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }
        }

        // If $data empty, still respond success (no-op), but nothing to update
        if (empty($data)) {
            // still treat as saved
            $lastStep = max(array_keys($stepsConfig));
            if ($step === $lastStep) {
                $this->userModel->update($userId, ['completed_profile' => 1]);
                $ref = $this->userModel->find($userId);
                $this->session->set('fullname', $ref['fullname'] ?? $ref['name'] ?? $ref['email']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Profile complete',
                    'showFinal' => true,
                    'redirect' => site_url('dashboard'),
                    'redirectDelay' => 3000
                ])->setStatusCode(200);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Saved', 'nextStep' => $step + 1])->setStatusCode(200);
        }

        // persist update
        try {
            $this->userModel->update($userId, $data);
        } catch (\Throwable $e) {
            // log details
            log_message('error','Wizard save error (DB): '.$e->getMessage()."\n".$e->getTraceAsString());
            // in development return message for debugging, otherwise generic
            if (ENVIRONMENT === 'development') {
                return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data: '.$e->getMessage()])->setStatusCode(500);
            }
            return $this->response->setJSON(['success'=>false,'message'=>'Failed to save data'])->setStatusCode(500);
        }

        $lastStep = max(array_keys($stepsConfig));
        if ($step === $lastStep) {
            // mark profile complete and return showFinal to allow client to display congratulations screen
            $this->userModel->update($userId, ['completed_profile' => 1]);
            $ref = $this->userModel->find($userId);
            $this->session->set('fullname', $ref['fullname'] ?? $ref['name'] ?? $ref['email']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile complete',
                'showFinal' => true,
                'redirect' => site_url('dashboard'),
                'redirectDelay' => 3000
            ])->setStatusCode(200);
        }

        return $this->response->setJSON(['success'=>true,'message'=>'Saved','nextStep'=>$step+1])->setStatusCode(200);
    }
}
