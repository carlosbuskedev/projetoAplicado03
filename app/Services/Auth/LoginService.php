<?php

namespace App\Services\Auth;

use App\Models\Backend\UserModel;
use Config\Auth;

class LoginService
{
    private UserModel $userModel;

    private JwtService $jwtService;

    private Auth $authConfig;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->jwtService = new JwtService();
        $this->authConfig = config('Auth');
    }

    public function authenticate(string $email, string $password): array
    {
        if ($email === '' || $password === '') {
            return $this->failure('E-mail e senha são obrigatórios.', 422);
        }

        $user = $this->userModel->findByEmail($email);

        if ($user === null || ! password_verify($password, $user['password'])) {
            return $this->failure('Credenciais inválidas.', 401);
        }

        unset($user['password']);

        $token = $this->jwtService->generate($user);

        return [
            'success' => true,
            'message' => 'Login realizado com sucesso.',
            'code'    => 200,
            'data'    => [
                'token'       => $token,
                'expires_in'  => $this->authConfig->jwtTTL,
                'user'        => [
                    'id'    => (int) $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role'],
                ],
                'permissions' => $this->authConfig->permissions[$user['role']] ?? [],
            ],
        ];
    }

    public function getAuthenticatedProfile(): array
    {
        $auth = service('authSession');
        $user = $auth->user();

        if ($user === null) {
            return $this->failure('Não autenticado.', 401);
        }

        $role = $auth->role();

        return [
            'success' => true,
            'message' => 'Sessão válida.',
            'code'    => 200,
            'data'    => [
                'user' => [
                    'id'    => $auth->id(),
                    'name'  => $user->name ?? null,
                    'email' => $user->email ?? null,
                    'role'  => $role,
                ],
                'permissions' => $this->authConfig->permissions[$role] ?? [],
            ],
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
