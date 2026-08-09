<?php

namespace App\Models;

use CodeIgniter\Model;

class WeeklyDiagnosticStatusModel extends Model
{
    protected $table = 'weekly_diagnostic_status';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'users_id',
        'week',
        'day',
        'completed',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';
}
