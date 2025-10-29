<?php namespace App\Models;

use CodeIgniter\Model;

class AdminUserModel extends Model
{
    protected $table = 'admin_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username','fullname','email','password','role','is_active','created_at','updated_at'];
    protected $returnType = 'array';
    protected $useTimestamps = false;
}
