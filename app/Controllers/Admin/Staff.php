<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use App\Models\StaffModel;

class Staff extends AdminBaseController
{
    protected $staffModel;

    public function __construct()
    {
        parent::__construct();
        $this->staffModel = new StaffModel();
        helper(['form','url','text']);
    }

    public function index()
    {
        $this->guard();

        // Prefer 'users' table if it exists, else use 'staffs'
        $db = \Config\Database::connect();
        $table = $db->tableExists('users') ? 'users' : 'staffs';
        $rows = $db->table($table)->orderBy('id','DESC')->get()->getResultArray();

        return view('admin/staff/index', [
            'users' => $rows,
            'totalUsers' => count($rows),
            'currentFacultyId' => session()->get('admin')['faculty_id'] ?? '',
            'currentDepartmentId' => session()->get('admin')['department_id'] ?? '',
        ]);
    }


    /**
     * Export staff list as CSV (single canonical method)
     * Route used in your view: site_url('admin/staff/export')
     */
    public function export()
    {
        $this->guard();

        $session = session();
        $admin = $session->get('admin') ?: null;
        $role = $admin['role'] ?? $session->get('admin_role') ?? $session->get('admin_role') ?? null;

        if (! $admin && ! $role) {
            return redirect()->to('/admin/login')->with('errors', ['Please login as admin to export data.']);
        }

        // Roles allowed to export (adjust as you want)
        $allowedRoles = ['superadmin','dean','hod','admin'];
        if (! in_array($role, $allowedRoles)) {
            return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
        }

        $db = \Config\Database::connect();
        $table = $db->tableExists('users') ? 'users' : ($db->tableExists('staffs') ? 'staffs' : null);
        if (! $table) {
            return redirect()->back()->with('errors', ['No users/staffs table found to export.']);
        }

        // Build builder
        $builder = $db->table($table);
        $builder->select('id, staff_id, fullname, email, category, role, faculty_id, department_id, phone, period_from, period_to, created_at');

        // Optional GET filters
        $qFaculty = $this->request->getGet('faculty') ?: null;
        $qDepartment = $this->request->getGet('department') ?: null;

        // Role scoping
        if ($role === 'dean') {
            $myFaculty = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
            if ($qFaculty && $myFaculty && $qFaculty != $myFaculty) {
                return redirect()->back()->with('errors', ['You are not allowed to export other faculties.']);
            }
            if ($myFaculty) $builder->where('faculty_id', $myFaculty);
            elseif ($qFaculty) $builder->where('faculty_id', $qFaculty);
        } elseif ($role === 'hod') {
            $myDept = $admin['department_id'] ?? $session->get('department_id') ?? null;
            if ($qDepartment && $myDept && $qDepartment != $myDept) {
                return redirect()->back()->with('errors', ['You are not allowed to export other departments.']);
            }
            if ($myDept) $builder->where('department_id', $myDept);
            elseif ($qDepartment) $builder->where('department_id', $qDepartment);
        } elseif ($role === 'admin') {
            // you might choose to allow admin; currently deny for safety
            return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
        } else {
            // superadmin: allow optional filters if provided
            if ($qFaculty) $builder->where('faculty_id', $qFaculty);
            if ($qDepartment) $builder->where('department_id', $qDepartment);
        }

        $rows = $builder->orderBy('id','ASC')->get()->getResultArray();

        // Resolve faculty/department names if possible
        $facultyNames = [];
        $departmentNames = [];
        if (! empty($rows) && $db->tableExists('faculties')) {
            $fAll = $db->table('faculties')->get()->getResultArray();
            foreach ($fAll as $f) $facultyNames[$f['id']] = $f['name'];
        }
        if (! empty($rows) && $db->tableExists('departments')) {
            $dAll = $db->table('departments')->get()->getResultArray();
            foreach ($dAll as $d) $departmentNames[$d['id']] = $d['name'];
        }

        // Build CSV in memory and return via Response
        $fh = fopen('php://temp', 'w+');
        // BOM for Excel
        fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // header
        $headers = ['ID','Staff ID','Fullname','Email','Category','Role','Faculty','Department','Phone','Period From','Period To','Created At'];
        fputcsv($fh, $headers);

        foreach ($rows as $r) {
            $facultyLabel = $facultyNames[$r['faculty_id']] ?? ($r['faculty_id'] ?? '');
            $departmentLabel = $departmentNames[$r['department_id']] ?? ($r['department_id'] ?? '');

            $line = [
                $r['id'] ?? '',
                $r['staff_id'] ?? '',
                $r['fullname'] ?? '',
                $r['email'] ?? '',
                $r['category'] ?? '',
                $r['role'] ?? '',
                $facultyLabel,
                $departmentLabel,
                $r['phone'] ?? '',
                $r['period_from'] ?? '',
                $r['period_to'] ?? '',
                $r['created_at'] ?? '',
            ];
            fputcsv($fh, $line);
        }

        rewind($fh);
        $csvData = stream_get_contents($fh);
        fclose($fh);

        $filename = 'staff_export_' . date('Ymd_His') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($csvData);
    }

    // ... keep other methods (create, store, etc.) here ...
}
