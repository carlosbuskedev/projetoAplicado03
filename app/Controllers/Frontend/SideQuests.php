<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Services\SideQuest\SideQuestService;

class SideQuests extends BaseController
{
    private SideQuestService $sideQuestService;

    public function __construct()
    {
        $this->sideQuestService = new SideQuestService();
    }

    public function index(): string
    {
        $questions = $this->sideQuestService->getQuestions();
        $scales = $this->sideQuestService->getScales();

        return view('side_quests', [
            'questions' => $questions,
            'scales' => $scales,
            'message' => empty($questions) ? 'Nenhum tema ou pergunta disponível.' : null,
        ]);
    }

    public function submit()
    {
        $answers = $this->request->getPost('answer') ?? [];

        $userId = service('authSession')->id();
        if ($userId === null) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'É necessário estar logado para responder.',
                ])->setStatusCode(401);
            }

            return redirect()->to('/login')->with('error', 'É necessário estar logado para responder.');
        }

        $result = $this->sideQuestService->saveResponses($userId, $answers);

        if (! $result['success']) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON($result)->setStatusCode($result['code']);
            }

            return redirect()->to('/side-quests')->with('error', $result['message']);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($result);
        }

        return redirect()->to('/home')->with('success', $result['message']);
    }
}
