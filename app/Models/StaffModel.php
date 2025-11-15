<?php namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table      = 'staffs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    
    protected $allowedFields = [
        'staff_number',
        'fullname',
        'email',
        'category',
        'role',
        'password',
        'faculty_id',
        'department_id',
        'created_at',
        'updated_at',
        'department',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find staff by email (trimmed, lowercase-safe)
     */
    public function findByEmail(string $email)
    {
        $email = trim(strtolower($email));
        return $this->where('LOWER(email)', $email)->first();
    }

    /**
     * Add a new staff to the staffs table
     *
     * @param string $fullname
     * @param string $email
     * @param string $staffNumber
     * @return int|false Returns the inserted ID or false on failure
     */
    public function addStaff(string $fullname, string $email, string $staffNumber)
    {
        // Prevent duplicate emails
        if ($this->findByEmail($email)) {
            return false; // Email already exists
        }

        $data = [
            'fullname'      => trim($fullname),
            'email'         => trim(strtolower($email)),
            'staff_number'  => trim($staffNumber),
        ];

        if ($this->insert($data)) {
            return $this->getInsertID();
        }

        return false;
    }
}
