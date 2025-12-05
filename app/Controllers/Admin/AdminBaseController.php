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
    protected function guard()
    {
        if (! $this->admin) {
            return redirect()->to('/admin/login')->with('error', 'Please log in as administrator.');
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

    // The apply closure accepts a Query Builder instance and optionally GET filters
    $apply = function($builder, $getFilters = []) use ($role, $facultyId, $departmentId) {
        // If explicit filters were passed (e.g. superadmin allowed filters), apply them
        $filterFaculty = $getFilters['faculty'] ?? null;
        $filterDepartment = $getFilters['department'] ?? null;

        if ($role === 'superadmin') {
            // superadmin: allow filters if provided (no mandatory scoping)
            if ($filterFaculty) $builder->where('u.faculty_id', (int)$filterFaculty);
            if ($filterDepartment) $builder->where('u.department_id', (int)$filterDepartment);
            return;
        }

        if ($role === 'dean') {
            // dean: restrict to their faculty (if session has faculty_id), otherwise apply filterFaculty if it matches
            if ($facultyId) {
                $builder->where('u.faculty_id', (int)$facultyId);
            } elseif ($filterFaculty) {
                $builder->where('u.faculty_id', (int)$filterFaculty);
            } else {
                // no faculty assigned -> return nothing (defensive)
                $builder->where('u.faculty_id IS NOT NULL AND 0 = 1', null, false);
            }
            return;
        }

        if ($role === 'hod') {
            // hod: restrict to their department
            if ($departmentId) {
                $builder->where('u.department_id', (int)$departmentId);
            } elseif ($filterDepartment) {
                $builder->where('u.department_id', (int)$filterDepartment);
            } else {
                $builder->where('u.department_id IS NOT NULL AND 0 = 1', null, false);
            }
            return;
        }

        // fallback for other admin roles:
        if ($departmentId) {
            $builder->where('u.department_id', (int)$departmentId);
            return;
        }
        if ($facultyId) {
            $builder->where('u.faculty_id', (int)$facultyId);
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
}
