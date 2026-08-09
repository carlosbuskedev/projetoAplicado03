<?php

namespace App\Models\Backend;

use CodeIgniter\Model;

class SideQuestModel extends Model
{
    protected $table = 'behavioral_responses';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'behavioral_questions_id',
        'behavioral_responses_scale_id',
        'users_id',
        'week',
        'created_at',
        'updated_at',
    ];

    protected $returnType = 'array';

    protected $useTimestamps = false;

    public function findLastWeekByUser(int $userId): ?array
    {
        return $this->where('users_id', $userId)
            ->orderBy('week', 'DESC')
            ->first();
    }

    public function insertResponses(array $responses): bool
    {
        return $this->insertBatch($responses) !== false;
    }
}
