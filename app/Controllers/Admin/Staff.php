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
        $table = $db->tableExists('staffs') ? 'staffs': 'users';
        $rows = $db->table($table)->orderBy('id','DESC')->get()->getResultArray();

        return view('admin/staff/index', [
            'staff' => $rows,
            'totalStaff' => count($rows),
            'currentFacultyId' => session()->get('admin')['faculty_id'] ?? '',
            'currentDepartmentId' => session()->get('admin')['department_id'] ?? '',
        ]);
    }


   /**
     * Export staff list as CSV (single canonical method)
     * Route used in your view: site_url('admin/staff/export')
    * Export staff/users as a custom CSV with filters and safe fields.
    * Usage examples:
    */

 public function export()
{
    $this->guard();

    $db = \Config\Database::connect();
    $table = $db->tableExists('users') ? 'users' : ($db->tableExists('staffs') ? 'staffs' : null);
    if (! $table) {
        return redirect()->back()->with('errors', ['No users/staffs table found to export.']);
    }

    $session = session();
    $scope = $this->getAdminScope();           // get role + scope + apply closure
    $role = $scope['role'];

    // If role not allowed, block (adjust allowed roles as you want)
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! in_array($role, $allowedRoles)) {
        return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    }

    // builder
    $builder = $db->table($table . ' as u');
    $builder->select('u.id, u.staff_id, u.fullname, u.email, u.category, u.role, u.faculty_id, u.department_id, u.phone, u.period_from, u.period_to, u.created_at');

    // optional joins for names (not required)
    if ($db->tableExists('faculties')) $builder->join('faculties f', 'f.id = u.faculty_id', 'left');
    if ($db->tableExists('departments')) $builder->join('departments d', 'd.id = u.department_id', 'left');

    // apply role-based scope; pass GET filters so superadmin can filter
    $getFilters = [
        'faculty' => $this->request->getGet('faculty'),
        'department' => $this->request->getGet('department'),
    ];
    ($scope['apply'])($builder, $getFilters);

    $rows = $builder->orderBy('u.id','ASC')->get()->getResultArray();

    // resolve faculty/department names if available
    $facultyNames = [];
    $departmentNames = [];
    if ($db->tableExists('faculties')) {
        $allF = $db->table('faculties')->select('id,name')->get()->getResultArray();
        foreach ($allF as $f) $facultyNames[$f['id']] = $f['name'];
    }
    if ($db->tableExists('departments')) {
        $allD = $db->table('departments')->select('id,name')->get()->getResultArray();
        foreach ($allD as $d) $departmentNames[$d['id']] = $d['name'];
    }

    // build CSV
    $fh = fopen('php://temp', 'w+');
    fwrite($fh, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel
    $headers = ['ID','Staff ID','Fullname','Email','Category','Role','Faculty','Department','Phone','Period From','Period To','Created At'];
    fputcsv($fh, $headers);

    foreach ($rows as $r) {
        $facultyLabel = $facultyNames[$r['faculty_id']] ?? $r['faculty_id'] ?? '';
        $deptLabel = $departmentNames[$r['department_id']] ?? $r['department_id'] ?? '';

        $line = [
            $r['id'] ?? '',
            $r['staff_id'] ?? '',
            $r['fullname'] ?? '',
            $r['email'] ?? '',
            $r['category'] ?? '',
            $r['role'] ?? '',
            $facultyLabel,
            $deptLabel,
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




public function export6()
{
    $this->guard(); // ensure admin + role checks already in AdminBaseController

    $session = session();
    $admin = $session->get('admin') ?? null;
    $role  = $session->get('admin_role') ?? $session->get('admin')['role'] ?? null;

    // Authorization: adjust allowed roles as needed
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! $role || ! in_array($role, $allowedRoles)) {
        return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    }

    $db = \Config\Database::connect();

    // Prefer 'users' table; fallback to 'staffs'
    $table = $db->tableExists('users') ? 'users' : ($db->tableExists('staffs') ? 'staffs' : null);
    if (! $table) {
        return redirect()->back()->with('errors', ['No users or staffs table found to export.']);
    }

    // Explicit exclude sensitive columns regardless of table structure
    $excluded = [
        'password',
        'verify_token',
        'reset_token',
        'role',
        'reset_token_expires',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'email_verified_at',
        'created_at',
        'deleted_at'
    ];

    // Desired CSV columns (preferred order). Keys are DB column names, values are friendly labels.
    // Add/remove columns here as desired. Columns not present in the table will be silently skipped.
    $columns = [
        'id'                   => 'ID',
        'staff_id'             => 'Staff ID',
        'staff_number'         => 'Staff Number',
        'fullname'             => 'Full Name',
        'name'                 => 'Name',
        'email'                => 'Email',
        'gender'               => 'Gender',
        'category'             => 'Category',
        'role'                 => 'Role',
        'faculty_id'           => 'Faculty',       // will resolve to name if faculties table exists
        'faculty'              => 'Faculty',       // fallback if text column exists
        'department_id'        => 'Department',    // will resolve
        'department'           => 'Department',    // fallback if text column exists
        'designation'          => 'Designation',
        'grade_level'          => 'Grade Level',
        'present_salary'       => 'Present Salary',
        'step'                 => 'Step',
        'period_from'          => 'Reporting From',
        'period_to'            => 'Reporting To',
        'phone'                => 'Phone',
        'alternatephonenumber' => 'Alternate Phone',
        'created_at'           => 'Created At',
        // Add academic / qualification fields you want exported (examples)
        'qual1'                => 'Qual 1',
        'qual1_grade'          => 'Qual 1 Grade',
        'qual1_institution'    => 'Qual 1 Institution',
        'qual1_date'           => 'Qual 1 Date',
        // ... add more if desired
    ];

    // Get actual columns in the table and build list to select
    try {
        $tableFields = $db->getFieldNames($table);
    } catch (\Throwable $e) {
        log_message('error', 'ExportCsv getFieldNames failed: ' . $e->getMessage());
        $tableFields = [];
    }

    // Build final select columns in order, skipping excluded and nonexistent fields
    $selectCols = [];
    foreach ($columns as $col => $label) {
        if (in_array($col, $tableFields) && ! in_array($col, $excluded)) {
            $selectCols[] = $col;
        }
    }
    if (empty($selectCols)) {
        // fallback: select all non-excluded fields
        foreach ($tableFields as $f) {
            if (! in_array($f, $excluded)) $selectCols[] = $f;
        }
    }

    // Build query with optional filters
    $builder = $db->table($table)->select($selectCols);

    // Read filters from GET
    $qFaculty    = $this->request->getGet('faculty') ?: null;
    $qDepartment = $this->request->getGet('department') ?: null;
    $qCategory   = $this->request->getGet('category') ?: null;
    $qFromYear   = $this->request->getGet('period_from') ?: null;
    $qToYear     = $this->request->getGet('period_to') ?: null;

    // Role scoping (dean/hod limited to their own faculty/department if stored on session/admin)
    if ($role === 'dean') {
        $myFaculty = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
        if ($myFaculty && in_array('faculty_id', $tableFields)) {
            $builder->where('faculty_id', $myFaculty);
        } elseif ($myFaculty && in_array('faculty', $tableFields)) {
            $builder->where('faculty', $myFaculty);
        }
        // allowed query filter if matches dean's faculty
        if ($qFaculty && $qFaculty != $myFaculty) {
            return redirect()->back()->with('errors', ['You are not allowed to export other faculties.']);
        }
    } elseif ($role === 'hod') {
        $myDept = $admin['department_id'] ?? $session->get('department_id') ?? null;
        if ($myDept && in_array('department_id', $tableFields)) {
            $builder->where('department_id', $myDept);
        } elseif ($myDept && in_array('department', $tableFields)) {
            $builder->where('department', $myDept);
        }
        if ($qDepartment && $qDepartment != $myDept) {
            return redirect()->back()->with('errors', ['You are not allowed to export other departments.']);
        }
    } // superadmin/admin allowed full (unless you want to restrict)

    // Apply explicit GET filters (only if column exists)
    if ($qFaculty) {
        if (in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $qFaculty);
        elseif (in_array('faculty', $tableFields)) $builder->where('faculty', $qFaculty);
    }
    if ($qDepartment) {
        if (in_array('department_id', $tableFields)) $builder->where('department_id', $qDepartment);
        elseif (in_array('department', $tableFields)) $builder->where('department', $qDepartment);
    }
    if ($qCategory && in_array('category', $tableFields)) {
        $builder->where('category', $qCategory);
    }
    // If user wants range by created_at year
    if ($qFromYear && in_array('created_at', $tableFields)) {
        $builder->where("YEAR(created_at) >=", (int)$qFromYear);
    }
    if ($qToYear && in_array('created_at', $tableFields)) {
        $builder->where("YEAR(created_at) <=", (int)$qToYear);
    }

    $rows = $builder->orderBy('id','ASC')->get()->getResultArray();

    // Resolve faculty/dept names if necessary:
    $facultyNames = [];
    $departmentNames = [];
    if (in_array('faculty_id', $tableFields) && $db->tableExists('faculties')) {
        $frows = $db->table('faculties')->select('id,name')->get()->getResultArray();
        foreach ($frows as $f) $facultyNames[$f['id']] = $f['name'];
    }
    if (in_array('department_id', $tableFields) && $db->tableExists('departments')) {
        $drows = $db->table('departments')->select('id,name')->get()->getResultArray();
        foreach ($drows as $d) $departmentNames[$d['id']] = $d['name'];
    }

    // Build CSV in memory
    $fh = fopen('php://temp', 'w+');
    // BOM for Excel
    fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // --- CUSTOM HEADER LINES (human readable) ---
    $universityName = 'Edo State University Iyamho';
    $reportTitle = 'APER Staff Export';
    $generatedAt = date('Y-m-d H:i:s');
    fputcsv($fh, [$universityName]);
    fputcsv($fh, [$reportTitle]);
    fputcsv($fh, ['Generated:', $generatedAt]);
    // show applied filters
    $appliedFilters = [];
    if ($qFaculty) $appliedFilters[] = "Faculty={$qFaculty}";
    if ($qDepartment) $appliedFilters[] = "Department={$qDepartment}";
    if ($qCategory) $appliedFilters[] = "Category={$qCategory}";
    if ($qFromYear || $qToYear) $appliedFilters[] = "Period={$qFromYear}-{$qToYear}";
    if (! empty($appliedFilters)) {
        fputcsv($fh, ['Filters:', implode('; ', $appliedFilters)]);
    }
    // blank row to separate
    fputcsv($fh, []);

    // Write column header (friendly labels) — use the $columns mapping for labels, fallback to column name
    $headerRow = [];
    foreach ($selectCols as $c) {
        $headerRow[] = $columns[$c] ?? $c;
    }
    fputcsv($fh, $headerRow);

    // Write rows (resolve faculty/department names where applicable)
    foreach ($rows as $r) {
        $line = [];
        foreach ($selectCols as $c) {
            $val = $r[$c] ?? '';
            if ($c === 'faculty_id') {
                // replace id with name if mapping exists
                $val = $facultyNames[$val] ?? $val;
            } elseif ($c === 'department_id') {
                $val = $departmentNames[$val] ?? $val;
            }
            $line[] = $val;
        }
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

 public function export5()
{
    $db = \Config\Database::connect();
    $builder = $db->table('users');

    // Sensitive fields to EXCLUDE from export
    $excluded = [
        'role',
        'alternatephonenumber',
        'numlogin',
        'numlogin',
        'reg_date',
        'reg_date',
        'password',
        'verify_token',
        'email_verified_at',
        'deleted_at',
        'reset_token',
        'reset_token_expires',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    // Get all columns from the table
    $fields = $db->getFieldNames('users');

    // Remove sensitive fields
    $allowedFields = array_diff($fields, $excluded);

    // Fetch only allowed fields
    $builder->select(implode(',', $allowedFields));
    $query = $builder->get();
    $results = $query->getResultArray();

    // CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="staff_export.csv"');

    $file = fopen('php://output', 'w');

    // Write CSV heading
    fputcsv($file, $allowedFields);

    // Write the data
    foreach ($results as $row) {
        fputcsv($file, $row);
    }

    fclose($file);
    exit();
}

 public function export4()
{
    $this->guard();

    $session = session();
    $admin = $session->get('admin') ?: null;
    $role = $admin['role'] ?? $session->get('admin_role') ?? null;

    if (! $admin && ! $role) {
        return redirect()->to('/admin/login')->with('errors', ['Please login as admin to export data.']);
    }

    // Allowed roles (adjust if needed)
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! in_array($role, $allowedRoles)) {
        return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    }

    $db = \Config\Database::connect();

    // Must export from users table (you have many columns there)
    if (! $db->tableExists('users')) {
        return redirect()->back()->with('errors', ['No users table found to export.']);
    }

    // Read actual columns for users table (preserves DB order)
    try {
        $tableFields = $db->getFieldNames('users');
    } catch (\Throwable $e) {
        log_message('error', 'Export: getFieldNames failed: ' . $e->getMessage());
        return redirect()->back()->with('errors', ['Unable to read users table structure.']);
    }

    if (empty($tableFields)) {
        return redirect()->back()->with('errors', ['No columns found on users table.']);
    }

    // Build query selecting exactly the columns in DB order
    $builder = $db->table('users')->select($tableFields);

    // Optional GET filters
    $qFaculty = $this->request->getGet('faculty') ?: null;
    $qDepartment = $this->request->getGet('department') ?: null;

    // Role scoping: dean -> faculty, hod -> department, superadmin -> all
    if ($role === 'dean') {
        $myFaculty = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
        if ($qFaculty && $myFaculty && $qFaculty != $myFaculty) {
            return redirect()->back()->with('errors', ['You are not allowed to export other faculties.']);
        }
        // since users table stores textual 'faculty' column, accept either match by id or name
        if ($myFaculty && in_array('faculty', $tableFields)) {
            $builder->where('faculty', $myFaculty);
        } elseif ($qFaculty && in_array('faculty', $tableFields)) {
            $builder->where('faculty', $qFaculty);
        }
    } elseif ($role === 'hod') {
        $myDept = $admin['department_id'] ?? $session->get('department_id') ?? null;
        if ($qDepartment && $myDept && $qDepartment != $myDept) {
            return redirect()->back()->with('errors', ['You are not allowed to export other departments.']);
        }
        if ($myDept && in_array('department', $tableFields)) {
            $builder->where('department', $myDept);
        } elseif ($qDepartment && in_array('department', $tableFields)) {
            $builder->where('department', $qDepartment);
        }
    } elseif ($role === 'admin') {
        // keep admin allowed — adjust if you want to deny
    } // superadmin gets all

    $rows = $builder->orderBy('id', 'ASC')->get()->getResultArray();

    // Build human-friendly header labels from DB column names:
    $headerRow = [];
    foreach ($tableFields as $col) {
        // Convert snake_case to Title Case for headers
        $label = str_replace('_', ' ', $col);
        $label = ucwords($label);
        $headerRow[] = $label;
    }

    // Prepare CSV in memory (use php://temp for large data)
    $fh = fopen('php://temp', 'w+');
    // BOM for Excel (UTF-8)
    fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Write header
    fputcsv($fh, $headerRow);

    // Write rows preserving column order
    foreach ($rows as $r) {
        $line = [];
        foreach ($tableFields as $col) {
            $val = $r[$col] ?? '';

            // If faculty/department are stored as ids (unlikely in your schema) and tables exist,
            // you could resolve here. But your DESCRIBE shows textual 'faculty'/'department', so
            // we use them as-is.
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val);
            }

            $line[] = $val;
        }
        fputcsv($fh, $line);
    }

    rewind($fh);
    $csvData = stream_get_contents($fh);
    fclose($fh);

    $filename = 'users_export_' . date('Ymd_His') . '.csv';

    return $this->response
        ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
        ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
        ->setHeader('Pragma', 'no-cache')
        ->setHeader('Expires', '0')
        ->setBody($csvData);
}

 public function export3()
{
    $this->guard();

    $session = session();
    $admin = $session->get('admin') ?: null;
    $role = $admin['role'] ?? $session->get('admin_role') ?? null;

    if (! $admin && ! $role) {
        return redirect()->to('/admin/login')->with('errors', ['Please login as admin to export data.']);
    }

    // Allowed roles (adjust as needed)
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! in_array($role, $allowedRoles)) {
        return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    }

    $db = \Config\Database::connect();

    // Pick source table: prefer 'users', then 'staffs'
    if ($db->tableExists('users')) {
        $table = 'users';
    } elseif ($db->tableExists('staffs')) {
        $table = 'staffs';
    } else {
        return redirect()->back()->with('errors', ['No users or staffs table found to export.']);
    }

    // read actual columns on the chosen table
    try {
        $tableFields = $db->getFieldNames($table);
    } catch (\Throwable $e) {
        log_message('error', 'Export: getFieldNames failed: ' . $e->getMessage());
        $tableFields = [];
    }

    // Preferred column order (we will pick whichever of these actually exist)
    $desired = [
        'id','staff_id','staff_number','fullname','name','email',
        'category','role','faculty_id','faculty','faculty_name','department_id','department','department_name',
        'phone','period_from','period_to','created_at'
    ];

    // Build $selectCols preserving desired order and only including columns that exist
    $selectCols = [];
    foreach ($desired as $c) {
        if (in_array($c, $tableFields) && ! in_array($c, $selectCols)) {
            $selectCols[] = $c;
        }
    }
    // If none of desired exist, fall back to selecting all table fields
    if (empty($selectCols)) {
        $selectCols = $tableFields;
    }

    // Build query
    $builder = $db->table($table)->select($selectCols);

    // Optional GET filters
    $qFaculty = $this->request->getGet('faculty') ?: null;
    $qDepartment = $this->request->getGet('department') ?: null;

    // Role scoping: dean -> faculty, hod -> department, superadmin -> all
    if ($role === 'dean') {
        $myFaculty = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
        if ($qFaculty && $myFaculty && $qFaculty != $myFaculty) {
            return redirect()->back()->with('errors', ['You are not allowed to export other faculties.']);
        }
        if ($myFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $myFaculty);
        elseif ($qFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $qFaculty);
    } elseif ($role === 'hod') {
        $myDept = $admin['department_id'] ?? $session->get('department_id') ?? null;
        if ($qDepartment && $myDept && $qDepartment != $myDept) {
            return redirect()->back()->with('errors', ['You are not allowed to export other departments.']);
        }
        if ($myDept && in_array('department_id', $tableFields)) $builder->where('department_id', $myDept);
        elseif ($qDepartment && in_array('department_id', $tableFields)) $builder->where('department_id', $qDepartment);
    } elseif ($role === 'admin') {
        // Keep as-is; optionally restrict
    } else {
        // superadmin: allow optional filters (only if columns present)
        if ($qFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $qFaculty);
        if ($qDepartment && in_array('department_id', $tableFields)) $builder->where('department_id', $qDepartment);
    }

    $rows = $builder->orderBy('id', 'ASC')->get()->getResultArray();

    // Detect how faculty/department are stored and prepare resolution maps where needed
    $hasFacultyId     = in_array('faculty_id', $tableFields);
    $hasFacultyName   = in_array('faculty', $tableFields) || in_array('faculty_name', $tableFields);
    $hasDeptId        = in_array('department_id', $tableFields);
    $hasDeptName      = in_array('department', $tableFields) || in_array('department_name', $tableFields);

    $facultyNames = [];
    $departmentNames = [];

    if ($hasFacultyId && $db->tableExists('faculties')) {
        $frows = $db->table('faculties')->select('id,name')->get()->getResultArray();
        foreach ($frows as $f) $facultyNames[$f['id']] = $f['name'];
    }
    if ($hasDeptId && $db->tableExists('departments')) {
        $drows = $db->table('departments')->select('id,name')->get()->getResultArray();
        foreach ($drows as $d) $departmentNames[$d['id']] = $d['name'];
    }

    // Header label mapping (friendly names)
    $colLabels = [
        'id' => 'ID',
        'staff_id' => 'Staff ID',
        'staff_number' => 'Staff Number',
        'fullname' => 'Fullname',
        'name' => 'Name',
        'email' => 'Email',
        'category' => 'Category',
        'role' => 'Role',
        'faculty_id' => 'Faculty',
        'faculty' => 'Faculty',
        'faculty_name' => 'Faculty',
        'department_id' => 'Department',
        'department' => 'Department',
        'department_name' => 'Department',
        'phone' => 'Phone',
        'period_from' => 'Period From',
        'period_to' => 'Period To',
        'created_at' => 'Created At'
    ];

    // Prepare CSV in memory
    $fh = fopen('php://temp', 'w+');
    // BOM for Excel compatibility
    fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Write header row
    $headerRow = [];
    foreach ($selectCols as $c) {
        $headerRow[] = $colLabels[$c] ?? $c;
    }
    fputcsv($fh, $headerRow);

    // Write data rows (resolve faculty/department intelligently)
    foreach ($rows as $r) {
        $line = [];
        foreach ($selectCols as $c) {
            $val = $r[$c] ?? '';

            // Try to resolve faculty:
            if (in_array($c, ['faculty_id','faculty','faculty_name'])) {
                // If table stores textual faculty in 'faculty' or 'faculty_name', prefer it
                if (!empty($r['faculty']) && !is_numeric($r['faculty'])) {
                    $val = $r['faculty'];
                } elseif (!empty($r['faculty_name']) && !is_numeric($r['faculty_name'])) {
                    $val = $r['faculty_name'];
                } elseif (isset($r['faculty_id']) && $r['faculty_id'] !== '' && isset($facultyNames[$r['faculty_id']])) {
                    $val = $facultyNames[$r['faculty_id']];
                } else {
                    // fallback to any present field
                    $val = $r['faculty'] ?? $r['faculty_name'] ?? ($r['faculty_id'] ?? '');
                }
            }

            // Try to resolve department:
            if (in_array($c, ['department_id','department','department_name'])) {
                if (!empty($r['department']) && !is_numeric($r['department'])) {
                    $val = $r['department'];
                } elseif (!empty($r['department_name']) && !is_numeric($r['department_name'])) {
                    $val = $r['department_name'];
                } elseif (isset($r['department_id']) && $r['department_id'] !== '' && isset($departmentNames[$r['department_id']])) {
                    $val = $departmentNames[$r['department_id']];
                } else {
                    $val = $r['department'] ?? $r['department_name'] ?? ($r['department_id'] ?? '');
                }
            }

            // Ensure scalar
            if (is_array($val) || is_object($val)) {
                $val = json_encode($val);
            }

            $line[] = $val;
        }
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

public function export2()
{
    $this->guard();

    $session = session();
    $admin = $session->get('admin') ?: null;
    $role = $admin['role'] ?? $session->get('admin_role') ?? null;

    if (! $admin && ! $role) {
        return redirect()->to('/admin/login')->with('errors', ['Please login as admin to export data.']);
    }

    // Allowed roles (adjust as needed)
    $allowedRoles = ['superadmin','dean','hod','admin'];
    if (! in_array($role, $allowedRoles)) {
        return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    }

    $db = \Config\Database::connect();

    // Pick source table: prefer 'users', then 'staffs'
    if ($db->tableExists('users')) {
        $table = 'users';
    } elseif ($db->tableExists('staffs')) {
        $table = 'staffs';
    } else {
        return redirect()->back()->with('errors', ['No users or staffs table found to export.']);
    }

    // read actual columns on the chosen table
    try {
        $tableFields = $db->getFieldNames($table);
    } catch (\Throwable $e) {
        log_message('error', 'Export: getFieldNames failed: ' . $e->getMessage());
        $tableFields = [];
    }

    // Desired columns in preferred order
    $desired = [
        'id','staff_id','staff_number','fullname','name','email',
        'category','role','faculty_id','department_id',
        'phone','period_from','period_to','created_at'
    ];

    // Intersect to only select columns that actually exist
    $selectCols = array_values(array_intersect($desired, $tableFields));

    // If nothing from desired list exists, select all (safe fallback)
    if (empty($selectCols)) {
        $selectCols = ['*'];
    }

    // Build query
    $builder = $db->table($table)->select($selectCols);

    // Optional GET filters
    $qFaculty = $this->request->getGet('faculty') ?: null;
    $qDepartment = $this->request->getGet('department') ?: null;

    // Role scoping: dean -> faculty, hod -> department, superadmin -> all
    if ($role === 'dean') {
        $myFaculty = $admin['faculty_id'] ?? $session->get('faculty_id') ?? null;
        if ($qFaculty && $myFaculty && $qFaculty != $myFaculty) {
            return redirect()->back()->with('errors', ['You are not allowed to export other faculties.']);
        }
        if ($myFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $myFaculty);
        elseif ($qFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $qFaculty);
    } elseif ($role === 'hod') {
        $myDept = $admin['department_id'] ?? $session->get('department_id') ?? null;
        if ($qDepartment && $myDept && $qDepartment != $myDept) {
            return redirect()->back()->with('errors', ['You are not allowed to export other departments.']);
        }
        if ($myDept && in_array('department_id', $tableFields)) $builder->where('department_id', $myDept);
        elseif ($qDepartment && in_array('department_id', $tableFields)) $builder->where('department_id', $qDepartment);
    } elseif ($role === 'admin') {
        // You can change this to allow admin; currently allow but no extra scoping.
        // If you want admin to be denied, uncomment the next lines:
        // return redirect()->to('/admin/login')->with('errors', ['Unauthorized to export staff data.']);
    } else {
        // superadmin: allow optional filters (only if columns present)
        if ($qFaculty && in_array('faculty_id', $tableFields)) $builder->where('faculty_id', $qFaculty);
        if ($qDepartment && in_array('department_id', $tableFields)) $builder->where('department_id', $qDepartment);
    }

    $rows = $builder->orderBy('id', 'ASC')->get()->getResultArray();

    // If faculty/department id columns exist, try to resolve names
    $facultyNames = [];
    $departmentNames = [];
    if (in_array('faculty_id', $tableFields) && $db->tableExists('faculties')) {
        $frows = $db->table('faculties')->select('id,name')->get()->getResultArray();
        foreach ($frows as $f) $facultyNames[$f['id']] = $f['name'];
    }
    if (in_array('department_id', $tableFields) && $db->tableExists('departments')) {
        $drows = $db->table('departments')->select('id,name')->get()->getResultArray();
        foreach ($drows as $d) $departmentNames[$d['id']] = $d['name'];
    }

    // Build CSV header labels mapped to selected columns
    $colLabels = [
        'id' => 'ID',
        'staff_id' => 'Staff ID',
        'staff_number' => 'Staff Number',
        'fullname' => 'Fullname',
        'name' => 'Name',
        'email' => 'Email',
        'category' => 'Category',
        'faculty_id' => 'Faculty',
        'department_id' => 'Department',
        'phone' => 'Phone',
        'period_from' => 'Period From',
        'period_to' => 'Period To',
        'created_at' => 'Created At'
    ];

    // Prepare CSV in memory
    $fh = fopen('php://temp', 'w+');
    // BOM for Excel compatibility
    fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Write header row
    $headerRow = [];
    foreach ($selectCols as $c) {
        $headerRow[] = $colLabels[$c] ?? $c;
    }
    fputcsv($fh, $headerRow);

    // Write data rows
    foreach ($rows as $r) {
        $line = [];
        foreach ($selectCols as $c) {
            if ($c === 'faculty_id') {
                // show resolved name if available
                $val = $facultyNames[$r['faculty_id']] ?? ($r['faculty_id'] ?? '');
            } elseif ($c === 'department_id') {
                $val = $departmentNames[$r['department_id']] ?? ($r['department_id'] ?? '');
            } else {
                $val = $r[$c] ?? '';
            }
            $line[] = $val;
        }
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

    public function export1()
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



    /**
     * Show create staff form
     */
    public function create()
    {
        $this->guard(); // keep your existing guard/authorization

        $faculties = [];
        $departments = [];

        if (class_exists(\App\Models\FacultyModel::class)) {
            $faculties = (new \App\Models\FacultyModel())->findAll();
        }
        if (class_exists(\App\Models\DepartmentModel::class)) {
            $departments = (new \App\Models\DepartmentModel())->findAll();
        }

        return view('admin/staff/create', [
            'faculties' => $faculties,
            'departments' => $departments,
        ]);
    }

    /**
     * Persist new staff
     */
    public function store()
    {
        $this->guard();

        if ($this->request->getMethod() !== 'post') {
            return redirect()->back();
        }

        // NOTE: use 'staff_number' to match your DB/model
        $rules = [
            'fullname'      => 'required|min_length[3]|max_length[255]',
            'email'         => 'required|valid_email|max_length[255]',
            'staff_number'  => 'required|max_length[120]',
            ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'errors' => $errors])->setStatusCode(422);
            }
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // normalize inputs
        $fullname     = trim($this->request->getPost('fullname'));
        $email        = strtolower(trim($this->request->getPost('email')));
        $staffNumber  = trim($this->request->getPost('staff_number'));
        $phone        = trim($this->request->getPost('phone')) ?: null;
        $facultyId    = $this->request->getPost('faculty_id') ?: null;
        $departmentId = $this->request->getPost('department_id') ?: null;
        $role         = $this->request->getPost('role') ?: 'staff';

        // duplicates
        if ($this->staffModel->findByEmail($email)) {
            $msg = 'A user with that email already exists.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(409);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        if ($this->staffModel->where('staff_number', $staffNumber)->first()) {
            $msg = 'A user with that Staff Number already exists.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(409);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }
        

        $insert = [
            'fullname'      => $fullname,
            'email'         => $email,
            'staff_number'  => $staffNumber,
           
            // if your model uses $useTimestamps, created_at will be filled automatically,
            // but setting it explicitly is harmless:
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        try {
            $this->staffModel->insert($insert);
            $newId = $this->staffModel->getInsertID();
        } catch (\Throwable $e) {
            log_message('error', 'Admin Staff store error: ' . $e->getMessage());
            $msg = (ENVIRONMENT === 'development') ? $e->getMessage() : 'Server error while saving staff.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('errors', [$msg]);
        }

        // success
        $msg = 'Staff added successfully.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true, 'message' => $msg, 'redirect' => site_url('admin/staff')])->setStatusCode(200);
        }

        return redirect()->to(site_url('admin/staff/create'))->with('success', $msg);
    }

    // TODO: implement edit(), update(), view(), delete() methods below
}

    

