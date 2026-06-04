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
        'category',
        'estimated_time',
        'difficulty',
        'priority',
        'status',
        'deadline',
        'experience',
    ];

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    public function createQuest(array $data): int|false
    {
        return $this->insert($data);
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }
}
