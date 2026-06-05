<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    public string $jwtSecret = 'altere-esta-chave-no-env';

    public int $jwtTTL = 28800;

    public array $roles = ['admin', 'user'];

    public array $permissions = [
        'admin' => ['dashboard', 'journey', 'users', 'home-admin', 'settings', 'profile', 'quests', 'leveling'],
        'user'  => ['dashboard', 'journey', 'profile', 'quests', 'leveling'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->jwtSecret = env('JWT_SECRET', $this->jwtSecret);
        $this->jwtTTL    = (int) env('JWT_TTL', (string) $this->jwtTTL);
    }

    public function roleHasPermission(string $role, string $permission): bool
    {
        return in_array($permission, $this->permissions[$role] ?? [], true);
    }
}
