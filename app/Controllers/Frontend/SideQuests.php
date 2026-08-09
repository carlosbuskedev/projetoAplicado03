<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\ThemeBehavioralQuestionModel;
use App\Models\BehavioralQuestionModel;
use App\Models\BehavioralResponsesScaleModel;
use App\Models\BehavioralResponseModel;

class SideQuests extends BaseController
{
    public function index(): string
    {
        return view('side_quests');
    }

    public function start()
    {
        $themeModel = new ThemeBehavioralQuestionModel();
        $questionModel = new BehavioralQuestionModel();
        $scaleModel = new BehavioralResponsesScaleModel();

        $themes = $themeModel->findAll();
        if (empty($themes)) {
            return view('side_quests_form', [
                'questions' => [],
                'scales' => [],
                'message' => 'Nenhum tema ou pergunta disponível.'
            ]);
        }

        // shuffle themes and pick up to 10
        shuffle($themes);
        $themes = array_slice($themes, 0, 10);

        $questions = [];
        foreach ($themes as $theme) {
            $qs = $questionModel->where('theme_behavioral_question_id', $theme['id'])->findAll();
            if (!empty($qs)) {
                $q = $qs[array_rand($qs)];
                $questions[] = $q;
            }
        }

        $scales = $scaleModel->orderBy('score', 'ASC')->findAll();

        return view('side_quests_form', [
            'questions' => $questions,
            'scales' => $scales,
            'message' => null,
        ]);
    }

    public function submit()
    {
        $post = $this->request->getPost();
        $answers = $post['answer'] ?? [];
        $week = isset($post['week']) ? (int)$post['week'] : 1;

        if (empty($answers) || !is_array($answers)) {
            return redirect()->to('/side-quests')->with('error', 'Nenhuma resposta enviada.');
        }

        $model = new BehavioralResponseModel();
        $now = date('Y-m-d H:i:s');
        foreach ($answers as $questionId => $scaleId) {
            $data = [
                'behavioral_questions_id' => (int)$questionId,
                'behavioral_responses_scale_id' => (int)$scaleId,
                'week' => $week,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $model->insert($data);
        }

        return redirect()->to('/home')->with('success', 'Respostas salvas com sucesso.');
    }
}
