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

}
