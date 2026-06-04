<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Services\Quest\QuestService;
use CodeIgniter\API\ResponseTrait;

class Quests extends BaseController
{
    use ResponseTrait;

    private QuestService $questService;

    public function __construct()
    {
        $this->questService = new QuestService();
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        return $this->respondFromService($this->questService->create($data));
    }

    public function update($id)
    {
        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();

        return $this->respondFromService($this->questService->update((int) $id, $data));
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
