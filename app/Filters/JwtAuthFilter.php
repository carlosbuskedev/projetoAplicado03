<?php

namespace App\Filters;

use App\Services\Auth\JwtService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JwtAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $jwtService = new JwtService();
        $token      = $jwtService->getBearerToken($request);

        if ($token === null) {
            return $this->unauthorized('Token não informado.');
        }

        $payload = $jwtService->decode($token);

        if ($payload === null) {
            return $this->unauthorized('Token inválido ou expirado.');
        }

        service('authSession')->setUser($payload);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function unauthorized(string $message): ResponseInterface
    {
        return service('response')
            ->setStatusCode(401)
            ->setJSON([
                'status'  => false,
                'message' => $message,
            ]);
    }
}
