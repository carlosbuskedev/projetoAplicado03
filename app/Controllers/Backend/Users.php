<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Services\User\UserService;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait;

    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        return $this->respondFromService($this->userService->listAll());
    }

    public function show($id = null)
    {
        return $this->respondFromService($this->userService->findById((int) $id));
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        return $this->respondFromService($this->userService->create($data));
    }

    public function update($id = null)
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        return $this->respondFromService($this->userService->update((int) $id, $data));
    }

    public function delete($id = null)
    {
        $currentUserId = service('authSession')->id();

        return $this->respondFromService(
            $this->userService->delete((int) $id, $currentUserId)
        );
    }

    private function respondFromService(array $result)
    {
        if (! $result['success']) {
            return $this->fail($result['message'], $result['code']);
        }

        $response = [
            'status'  => true,
            'message' => $result['message'],
        ];

        if (! empty($result['data'])) {
            $response['data'] = $result['data'];
        }

        return $this->respond($response, $result['code']);
    }
}
