<?php

namespace App\Models\Backend;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    /** Campos retornados em listagens públicas (sem senha). */
    private array $publicFields = [
        'id',
        'name',
        'email',
        'role',
        'created_at',
        'updated_at',
    ];

    public function findAllOrdered(): array
    {
        return $this->select($this->publicFields)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $builder = $this->where('email', $email);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->first() !== null;
    }

    public function createUser(array $data): int|false
    {
        return $this->insert($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }
}
