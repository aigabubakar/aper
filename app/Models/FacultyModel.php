<?php namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model
{
    protected $table = 'faculties';
    protected $primaryKey = 'id';
    protected $useTimestamps = false;

    // fields you will write to users or to manage faculties
    protected $allowedFields = ['name','code','created_at'];
}
