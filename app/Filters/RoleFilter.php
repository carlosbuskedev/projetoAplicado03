<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('authSession');

        if (! $auth->isAuthenticated()) {
            return $this->forbidden('Sessão não autenticada.');
        }

        $allowedRoles = $arguments ?? [];

        if ($allowedRoles === [] || ! $auth->hasRole(...$allowedRoles)) {
            return $this->forbidden('Você não tem permissão para acessar este recurso.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function forbidden(string $message): ResponseInterface
    {
        return service('response')
            ->setStatusCode(403)
            ->setJSON([
                'status'  => false,
                'message' => $message,
            ]);
    }
}
