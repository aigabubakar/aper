<?php namespace App\Models;
use CodeIgniter\Model;

class SeniorNonAcademicProfileModel extends Model
{
    protected $table = 'senior_nonacademic_profiles';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
      'user_id','faculty_id','department_id','phone','dob','gender','department','designation','grade_level',
      'supervisory_roles','trainings','certifications','period_from','period_to',
      'qualifications','avatar'
    ];
}
