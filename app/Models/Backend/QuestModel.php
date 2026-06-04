<?php

namespace App\Models\Backend;

use CodeIgniter\Model;

class QuestModel extends Model
{
    protected $table = 'quests';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title',
        'short_description',
        'estimated_time',
        'difficulty',
        'priority',
        'deadline',
        'experience',
        'user_id',
        'started_date',
        'completed_date',
        'interruptions_count',
        'remaining_time',
    ];

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    public function createQuest(array $data): int|false
    {
        return $this->insert($data);
    }

    public function updateQuest(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }
}
