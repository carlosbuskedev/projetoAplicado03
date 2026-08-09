<?php

namespace App\Models;

use CodeIgniter\Model;

class BehavioralQuestionModel extends Model
{
    protected $table = 'behavioral_questions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['theme_behavioral_question_id', 'description', 'feedback', 'created_at', 'updated_at'];
    protected $useTimestamps = false;
}
