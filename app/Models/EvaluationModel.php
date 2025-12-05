<?php namespace App\Models;

use CodeIgniter\Model;

class EvaluationModel extends Model
{
    protected $table = 'evaluations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['staff_id','category','overall_score','comments','meta','created_by','created_at','updated_at'];
    protected $useTimestamps = false; // we set created_at manually above
}
