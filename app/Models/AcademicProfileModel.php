<?php namespace App\Models;
use CodeIgniter\Model;

class AcademicProfileModel extends Model
{
    protected $table = 'academic_profiles';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
      'user_id','faculty_id','department_id','phone','dob','gender','department','designation','academic_rank',
      'courses_taught','research_areas','publications','period_from','period_to',
      'qualifications','avatar'
    ];
}

