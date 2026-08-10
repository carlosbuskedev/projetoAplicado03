<?php

namespace App\Models;

use CodeIgniter\Model;

class WeeklyActivitiesModel extends Model
{
    protected $table = 'weekly_activities';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'theme_behavioral_questions_id',
        'objective',
        'task',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}
