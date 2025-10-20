<?php namespace App\Models;

use CodeIgniter\Model;

class NonAcademicProfileModel extends Model
{
    protected $table = 'nonacademic_profiles';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id','phone','dob','gender','department','designation',
        'period_from','period_to','qualifications','experience_activities',
        'emergency_contact','avatar'
    ];
}
