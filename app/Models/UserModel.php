<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFieldss = [
        'staff_id',
        'fullname',
        'email',
        'password',
        'role',
        'is_active', 
        'phone',
        'category',
        'period_from',
        'period_to',
        'verify_token',
        'email_verified_at'
    ];
        


    protected $allowedFields = [
    // basics
    'staff_id',
    'fullname',
    'email',
    'password',
    'phone',
    'category',
    'period_from',
    'period_to',
    'dob','gender',
    'department',
    'designation',
    'grade_level',
    'faculty_id',
    'department_id',
    'verify_token',
    'email_verified_at',
    'completed_profile',
    'created_at',
    'updated_at',
    'main_job',
    'position_institution',
    'adhoc_job',
    'activities_within_university',
    'activities_outside_university',
    'professional_experience',
    'trainings',
    'certifications',
    'period_from',
    'period_to',

    'publications','dissertation',
    'articles','books_monographs',
    'number_pub_accepted',
    'number_of_points',
    'postgraduate_supervisor',
    'participation',
    'other_remark',
    
    'exp_out_institution_name1',
    'exp_out_designation1',
    'exp_out_specialization1',
    'exp_out_date1',
    'exp_out_institution_name2',
    'exp_out_designation2',
    'exp_out_specialization2',
    'exp_out_date2',
    'professional_experience',
    'academic_rank',
    'date_of_first_appointment',
    'current_appointment_date',
    'current_appointment_grade',
    'courses_taught','teaching_load',
    'research_areas','supervisions_count',
    'postgraduate_supervisor','grants',
    'publications','dissertation','articles',
    'books_monographs','number_pub_accepted',
    'number_of_points',
    'conference_taken',
    'contributions_to_knowledge',
    'professional_experience',
    'participation',
    'completed_profile',
    'completed_at',
    'appointment_confirmed',
    'appointment_confirmed_at',   

    'qual1','qual1_grade','qual1_institution','qual1_date',
    'qual2','qual2_grade','qual2_institution','qual2_date',
    'qual3','qual3_grade','qual3_institution','qual3_date',
    'qual4','qual4_grade','qual4_institution','qual4_date',
    'qual5','qual5_grade','qual5_institution','qual5_date',
    'prof_qual1','prof_qual1_body','prof_qual1_date',
    'prof_qual2','prof_qual2_body','prof_qual2_date',
    'prof_qual3','prof_qual3_body','prof_qual3_date',
    'prof_qual4','prof_qual4_body','prof_qual4_date',
    'prof_qual5','prof_qual5_body','prof_qual5_date',
];
     

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find user by email (trimmed, lowercase-safe)
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email)
    {
        $email = trim(strtolower($email));
        return $this->where('LOWER(email)', $email)->first();
    }

    /**
     * Create a user record from an existing staff record.
     * Accepts plain password and hashes it before insert.
     *
     * @param array|int $staff  Staff array (from StaffModel) OR staff id
     * @param string $plainPassword
     * @param string $role
     * @return int|false Insert ID or false on failure
     */
    public function createFromStaff($staff, string $plainPassword, string $role = 'staff')
    {
        // If an ID was passed, fetch the staff row
        if (is_int($staff) || ctype_digit((string)$staff)) {
            $staffModel = new \App\Models\StaffModel();
            $staff = $staffModel->find((int)$staff);
            if (! $staff) {
                return false;
            }
        }

        // safety
        if (!is_array($staff) || empty($staff['email'])) {
            return false;
        }

        // prevent duplicate email
        $existing = $this->findByEmail($staff['email']);
        if ($existing) {
            return false;
        }

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $data = [
            'staff_id' => $staff['id'] ?? null,
            'fullname'     => $staff['fullname'] ?? null,
            'email'    => $staff['email'],
            'password' => $hash,
            'role'     => $role,
            'is_active'=> 1,
        ];

        $this->insert($data);
        return $this->getInsertID() ?: false;
    }

    

    

    
}
