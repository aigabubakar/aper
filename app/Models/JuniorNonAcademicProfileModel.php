<?php namespace App\Models;
use CodeIgniter\Model;

class JuniorNonAcademicProfileModel extends Model
{
    protected $table = 'junior_nonacademic_profiles';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
      'user_id','faculty_id','department_id','phone','dob','gender','department','designation',
      'mentor_name','mentor_contact','period_from','period_to',
      'qualifications','experience_activities','avatar'
    ];
}



