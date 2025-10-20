<?php namespace App\Models;

use CodeIgniter\Model;

class StaffModel extends Model
{
    protected $table      = 'staffs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'staff_number',
        'name',
        'email',
        'department',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Find staff by email (trimmed, lowercase-safe)
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email)
    {
        $email = trim(strtolower($email));
        return $this->where('LOWER(email)', $email)->first();
    }
}
