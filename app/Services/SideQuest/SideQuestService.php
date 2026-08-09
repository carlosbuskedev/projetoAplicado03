<?php

namespace App\Services\SideQuest;

use App\Models\Backend\SideQuestModel;
use App\Models\BehavioralQuestionModel;
use App\Models\BehavioralResponsesScaleModel;
use App\Models\ThemeBehavioralQuestionModel;
use Config\Auth;

class SideQuestService
{
    private SideQuestModel $sideQuestModel;
    private ThemeBehavioralQuestionModel $themeModel;
    private BehavioralQuestionModel $questionModel;
    private BehavioralResponsesScaleModel $scaleModel;
    private Auth $authConfig;

    public function __construct()
    {
        $this->sideQuestModel = new SideQuestModel();
        $this->themeModel = new ThemeBehavioralQuestionModel();
        $this->questionModel = new BehavioralQuestionModel();
        $this->scaleModel = new BehavioralResponsesScaleModel();
        $this->authConfig = config('Auth');
    }

    public function getQuestions(int $limit = 10): array
    {
        $themes = $this->themeModel->findAll();

        if (empty($themes)) {
            return [];
        }

        shuffle($themes);
        $themes = array_slice($themes, 0, $limit);

        $questions = [];
        foreach ($themes as $theme) {
            $themeQuestions = $this->questionModel
                ->where('theme_behavioral_question_id', $theme['id'])
                ->findAll();

            if (! empty($themeQuestions)) {
                //sortear posição randomica no array de perguntas do tema
                shuffle($themeQuestions);
                $questions[] = $themeQuestions[0];
            }
        }

        return $questions;
    }

    public function getScales(): array
    {
        return $this->scaleModel
            ->orderBy('score', 'ASC')
            ->findAll();
    }

    public function create(array $data): array
    {
        $answers = $data['responses'] ?? [];
        $userId = service('authSession')->id();

        if (empty($answers) || ! is_array($answers)) {
            return $this->failure('Nenhuma resposta enviada.', 422);
        }

        $lastWeek = $this->sideQuestModel->findLastWeekByUser($userId);
        $nextWeek = 1;

        if (! empty($lastWeek) && isset($lastWeek['week'])) {
            $nextWeek = ((int) $lastWeek['week']) + 1;
        }

        $now = date('Y-m-d H:i:s');
        $batch = [];

        foreach ($answers as $answer) {
            $batch[] = [
                'behavioral_questions_id' => (int) $answer['question_id'],
                'behavioral_responses_scale_id' => (int) $answer['scale_id'],
                'users_id' => $userId,
                'week' => $nextWeek,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($batch)) {
            return $this->failure('Nenhuma resposta válida encontrada.', 422);
        }

        if (! $this->sideQuestModel->insertResponses($batch)) {
            return $this->failure('Não foi possível salvar as respostas.', 500);
        }

        return $this->success('Respostas salvas com sucesso.');
    }

    private function success(string $message, array $data = [], int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ];
    }

    private function failure(string $message, int $code): array
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
            'data' => [],
        ];
    }
}
