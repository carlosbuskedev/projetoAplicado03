<?php

namespace App\Models;

use CodeIgniter\Model;

class BehavioralResponseModel extends Model
{
    protected $table = 'behavioral_responses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['behavioral_questions_id', 'behavioral_responses_scale_id', 'week', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
