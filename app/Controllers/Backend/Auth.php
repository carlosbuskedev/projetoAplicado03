<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Services\Auth\LoginService;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    private LoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    public function login()
    {
        $data     = $this->request->getJSON(true) ?? [];
        $email    = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        $result = $this->loginService->authenticate($email, $password);

        if (! $result['success']) {
            return $this->fail($result['message'], $result['code']);
        }

        return $this->respond([
            'status'  => true,
            'message' => $result['message'],
            'data'    => $result['data'],
        ], $result['code']);
    }

    public function me()
    {
        $result = $this->loginService->getAuthenticatedProfile();

        if (! $result['success']) {
            return $this->fail($result['message'], $result['code']);
        }

        return $this->respond([
            'status' => true,
            'data'   => $result['data'],
        ]);
    }

    public function admin()
    {
        return $this->respond([
            'status'  => true,
            'message' => 'Área administrativa — acesso permitido.',
        ]);
    }
}
