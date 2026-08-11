<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Services\SideQuest\SideQuestService;
use CodeIgniter\API\ResponseTrait;

class SideQuests extends BaseController
{
    use ResponseTrait;

    private SideQuestService $sideQuestService;

    public function __construct()
    {
        $this->sideQuestService = new SideQuestService();
    }

    public function status()    
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $userId = $data['user_id'] ?? null;

        if ($userId === null) {
            return $this->fail('Usuário não autenticado.', 401);
        }

        $model = new \App\Models\BehavioralResponseModel();
        $hasResponses = $model->where('users_id', $userId)->countAllResults() > 0;

        return $this->respond([
            'success' => true,
            'hasResponses' => $hasResponses,
            'redirectTo' => $hasResponses ? '/weekly-diagnostic' : '/side-quests',
        ], 200);
    }

    public function create()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        return $this->respondFromService($this->sideQuestService->create($data));
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
