<?php

namespace App\Services\Auth;

class AuthSession
{
    private ?object $user = null;

    public function setUser(object $user): void
    {
        $this->user = $user;
    }

    public function user(): ?object
    {
        return $this->user;
    }

    public function id(): ?int
    {
        return isset($this->user->sub) ? (int) $this->user->sub : null;
    }

    public function role(): ?string
    {
        return $this->user->role ?? null;
    }

    public function isAuthenticated(): bool
    {
        return $this->user !== null;
    }

    public function hasRole(string ...$roles): bool
    {
        $role = $this->role();

        return $role !== null && in_array($role, $roles, true);
    }

    public function can(string $permission): bool
    {
        $role = $this->role();

        if ($role === null) {
            return false;
        }

        return config('Auth')->roleHasPermission($role, $permission);
    }
}
