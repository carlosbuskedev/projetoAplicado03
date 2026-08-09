<?php

namespace App\Models;

use CodeIgniter\Model;

class ThemeBehavioralQuestionModel extends Model
{
    protected $table = 'theme_behavioral_questions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['description', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
