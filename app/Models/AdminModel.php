<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table      = 'admin_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fullname','email','password','role','created_at','last_login'];
    protected $useTimestamps = false;
}
