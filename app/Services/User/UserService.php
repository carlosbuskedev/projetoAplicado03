<?php

namespace App\Services\User;

use App\Models\Backend\UserModel;
use Config\Auth;

/**
 * Regras de negócio do CRUD de usuários.
 * Acesso ao banco apenas via UserModel.
 */
class UserService
{
    private UserModel $userModel;

    private Auth $authConfig;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->authConfig = config('Auth');
    }

    public function listAll(): array
    {
        $users = $this->userModel->findAllOrdered();

        return $this->success('Usuários listados.', ['users' => $users]);
    }

    public function findById(int $id): array
    {
        $user = $this->findOrFail($id);

        if (! $user['success']) {
            return $user;
        }

        return $this->success('Usuário encontrado.', ['user' => $user['data']]);
    }

    public function create(array $data): array
    {
        $validation = $this->validatePayload($data, true);

        if (! $validation['success']) {
            return $validation;
        }

        $payload = $validation['data'];

        if ($this->userModel->emailExists($payload['email'])) {
            return $this->failure('Este e-mail já está cadastrado.', 409);
        }

        $id = $this->userModel->createUser([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'password' => password_hash($payload['password'], PASSWORD_DEFAULT),
            'role'     => $payload['role'],
        ]);

        if ($id === false) {
            return $this->failure('Não foi possível cadastrar o usuário.', 500);
        }

        return $this->success('Usuário cadastrado com sucesso.', [
            'user' => $this->sanitizeUser($this->userModel->findById((int) $id)),
        ], 201);
    }

    public function update(int $id, array $data): array
    {
        $existing = $this->findOrFail($id);

        if (! $existing['success']) {
            return $existing;
        }

        $validation = $this->validatePayload($data, false);

        if (! $validation['success']) {
            return $validation;
        }

        $payload = $validation['data'];

        if ($this->userModel->emailExists($payload['email'], $id)) {
            return $this->failure('Este e-mail já está em uso por outro usuário.', 409);
        }

        $updateData = [
            'name'  => $payload['name'],
            'email' => $payload['email'],
            'role'  => $payload['role'],
        ];

        if ($payload['password'] !== '') {
            $updateData['password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
        }

        if (! $this->userModel->updateUser($id, $updateData)) {
            return $this->failure('Não foi possível atualizar o usuário.', 500);
        }

        return $this->success('Usuário atualizado com sucesso.', [
            'user' => $this->sanitizeUser($this->userModel->findById($id)),
        ]);
    }

    public function delete(int $id, ?int $currentUserId = null): array
    {
        $existing = $this->findOrFail($id);

        if (! $existing['success']) {
            return $existing;
        }

        if ($currentUserId !== null && $id === $currentUserId) {
            return $this->failure('Você não pode excluir o próprio usuário logado.', 403);
        }

        if (! $this->userModel->deleteUser($id)) {
            return $this->failure('Não foi possível excluir o usuário.', 500);
        }

        return $this->success('Usuário excluído com sucesso.');
    }

    private function validatePayload(array $data, bool $isCreate): array
    {
        $name     = trim((string) ($data['name'] ?? ''));
        $email    = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $role     = trim((string) ($data['role'] ?? 'user'));

        if ($name === '' || $email === '') {
            return $this->failure('Nome e e-mail são obrigatórios.', 422);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->failure('E-mail inválido.', 422);
        }

        if ($isCreate && $password === '') {
            return $this->failure('Senha é obrigatória no cadastro.', 422);
        }

        if ($password !== '' && strlen($password) < 6) {
            return $this->failure('A senha deve ter no mínimo 6 caracteres.', 422);
        }

        if (! in_array($role, $this->authConfig->roles, true)) {
            return $this->failure('Perfil inválido. Use admin ou user.', 422);
        }

        return [
            'success' => true,
            'data'    => [
                'name'     => $name,
                'email'    => $email,
                'password' => $password,
                'role'     => $role,
            ],
        ];
    }

    private function findOrFail(int $id): array
    {
        $user = $this->userModel->findById($id);

        if ($user === null) {
            return $this->failure('Usuário não encontrado.', 404);
        }

        return [
            'success' => true,
            'data'    => $this->sanitizeUser($user),
        ];
    }

    private function sanitizeUser(?array $user): ?array
    {
        if ($user === null) {
            return null;
        }

        unset($user['password']);

        return $user;
    }

    private function success(string $message, array $data = [], int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
        ];
    }

    private function failure(string $message, int $code): array
    {
        return [
            'success' => false,
            'message' => $message,
            'code'    => $code,
        ];
    }
}
