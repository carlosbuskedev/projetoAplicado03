<?php

namespace App\Models;

use CodeIgniter\Model;

class BehavioralResponsesScaleModel extends Model
{
    protected $table = 'behavioral_responses_scale';
    protected $primaryKey = 'id';
    protected $allowedFields = ['score', 'description', 'frequency', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
