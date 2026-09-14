<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminBaseController extends BaseController
{
    protected $admin;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->admin = session()->get('admin');
    }

    /**
     * Protect admin-only routes
     */
    protected function guard($requiredRole = null)
    {
        if (! $this->admin) {
            if (service('request')->isAJAX()) {
                service('response')
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Please log in as administrator.'])
                    ->send();
                exit();
            }
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException(
                redirect()->to('/admin/login')->with('error', 'Please log in as administrator.')
            );
        }

        if ($requiredRole && (!isset($this->admin['role']) || $this->admin['role'] !== $requiredRole)) {
            if (service('request')->isAJAX()) {
                service('response')
                    ->setStatusCode(403)
                    ->setJSON(['success' => false, 'message' => 'Access denied: Insufficient permissions.'])
                    ->send();
                exit();
            }
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException(
                redirect()->to('/admin')->with('error', 'Access denied: Insufficient permissions.')
            );
        }
    }

    /**
 * Return the admin role + scope and a small helper to apply it to a Query Builder.
 *
 * Usage:
 *   $scope = $this->getAdminScope();
 *   $builder = $db->table('users as u')->select('u.*');
 *   $scope['apply']($builder);
 */
protected function getAdminScope(): array
{
    $session = session();
    $admin = $session->get('admin') ?? [];

    $role = $admin['role'] ?? $session->get('admin_role') ?? $session->get('role') ?? null;
    $facultyId = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
    $departmentId = $admin['department_id'] ?? $session->get('department_id') ?? null;

    $facultyName = null;
    $departmentName = null;
    $db = \Config\Database::connect();
    if ($facultyId && $db->tableExists('faculties')) {
        $f = $db->table('faculties')->select('name')->where('id', $facultyId)->get()->getRowArray();
        if ($f) $facultyName = $f['name'];
    }
    if ($departmentId && $db->tableExists('departments')) {
        $d = $db->table('departments')->select('name')->where('id', $departmentId)->get()->getRowArray();
        if ($d) $departmentName = $d['name'];
    }

    // The apply closure accepts a Query Builder instance and optionally GET filters
    $apply = function($builder, $getFilters = []) use ($role, $facultyId, $departmentId, $facultyName, $departmentName) {
        // If explicit filters were passed (e.g. superadmin allowed filters), apply them
        $filterFaculty = $getFilters['faculty'] ?? null;
        $filterDepartment = $getFilters['department'] ?? null;

        if ($role === 'superadmin') {
            // superadmin: allow filters if provided (no mandatory scoping)
            if ($filterFaculty) $builder->where('u.faculty', (int)$filterFaculty);
            if ($filterDepartment) $builder->where('u.department', (int)$filterDepartment);
            return;
        }

        if ($role === 'dean') {
            // dean: restrict to their faculty (if session has faculty_id), otherwise apply filterFaculty if it matches
            if ($facultyId) {
                $builder->groupStart()
                        ->where('u.faculty', (int)$facultyId)
                        ->orWhere('u.faculty', (string)$facultyId);
                if ($facultyName) $builder->orWhere('u.faculty', $facultyName);
                $builder->groupEnd();
            } elseif ($filterFaculty) {
                $builder->where('u.faculty', (int)$filterFaculty);
            } else {
                // no faculty assigned -> return nothing (defensive)
                $builder->where('u.faculty IS NOT NULL AND 0 = 1', null, false);
            }
            return;
        }

        if ($role === 'hod') {
            // hod: restrict to their department
            if ($departmentId) {
                $builder->groupStart()
                        ->where('u.department', (int)$departmentId)
                        ->orWhere('u.department', (string)$departmentId);
                if ($departmentName) $builder->orWhere('u.department', $departmentName);
                $builder->groupEnd();
            } elseif ($filterDepartment) {
                $builder->where('u.department', (int)$filterDepartment);
            } else {
                $builder->where('u.department IS NOT NULL AND 0 = 1', null, false);
            }
            return;
        }

        // fallback for other admin roles:
        if ($departmentId) {
            $builder->groupStart()
                    ->where('u.department', (int)$departmentId)
                    ->orWhere('u.department', (string)$departmentId);
            if ($departmentName) $builder->orWhere('u.department', $departmentName);
            $builder->groupEnd();
            return;
        }
        if ($facultyId) {
            $builder->groupStart()
                    ->where('u.faculty', (int)$facultyId)
                    ->orWhere('u.faculty', (string)$facultyId);
            if ($facultyName) $builder->orWhere('u.faculty', $facultyName);
            $builder->groupEnd();
            return;
        }

        // if nothing available, allow everything or deny — here we choose to deny by default
        $builder->where('u.id IS NOT NULL'); // no-op; change to deny if you prefer
    };

    return [
        'role' => $role,
        'faculty_id' => $facultyId,
        'department_id' => $departmentId,
        'apply' => $apply,
    ];
}




    /**
     * Render admin views easily
     */
    protected function render(string $view, array $data = [])
    {
        $data['admin'] = $this->admin;
        echo view('admin/layouts/header', $data);
        echo view($view, $data);
        echo view('admin/layouts/footer');
    }

    /**
     * Check if a staff record is within the current admin's scope
     */
    protected function isWithinScope(array $staff): bool
    {
        $session = session();
        $admin = $session->get('admin') ?? [];
        $role = $admin['role'] ?? $session->get('admin_role') ?? $session->get('role') ?? null;
        $facultyId = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
        $departmentId = $admin['department_id'] ?? $session->get('department_id') ?? null;

        if ($role === 'superadmin') {
            return true;
        }

        $db = \Config\Database::connect();

        if ($role === 'dean') {
            if (!$facultyId) return false;
            $sFac = $staff['faculty'] ?? null;
            $sFacId = $staff['faculty_id'] ?? null;
            if ((int)$sFac === (int)$facultyId || (int)$sFacId === (int)$facultyId || (string)$sFac === (string)$facultyId) return true;
            if ($db->tableExists('faculties')) {
                $f = $db->table('faculties')->select('name')->where('id', $facultyId)->get()->getRowArray();
                if ($f && strcasecmp((string)$sFac, $f['name']) === 0) return true;
            }
            return false;
        }

        if ($role === 'hod') {
            if (!$departmentId) return false;
            $sDept = $staff['department'] ?? null;
            $sDeptId = $staff['department_id'] ?? null;
            if ((int)$sDept === (int)$departmentId || (int)$sDeptId === (int)$departmentId || (string)$sDept === (string)$departmentId) return true;
            if ($db->tableExists('departments')) {
                $d = $db->table('departments')->select('name')->where('id', $departmentId)->get()->getRowArray();
                if ($d && strcasecmp((string)$sDept, $d['name']) === 0) return true;
            }
            return false;
        }

        // fallback deny for other roles
        return false;
    }

    /**
     * Stop request if a staff member is outside the current admin's scope
     */
    protected function guardStaffScope(array $staff)
    {
        if (!$this->isWithinScope($staff)) {
            if (service('request')->isAJAX()) {
                service('response')
                    ->setStatusCode(403)
                    ->setJSON(['success' => false, 'message' => 'Access denied: Staff member is outside of your department/faculty scope.'])
                    ->send();
                exit();
            }
            throw new \CodeIgniter\HTTP\Exceptions\RedirectException(
                redirect()->to('/admin')->with('error', 'Access denied: Staff member is outside of your department/faculty scope.')
            );
        }
    }
}
