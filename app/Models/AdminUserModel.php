<?php namespace App\Models;

use CodeIgniter\Model;


class AdminUserModel extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'username', 'fullname', 'email', 'password', 'role',
        'department', 'faculty', 'is_active',
        'created_at', 'updated_at', 'last_login'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function findByEmail($email)
    {
        return $this->where('email', strtolower($email))->first();
    }
}
