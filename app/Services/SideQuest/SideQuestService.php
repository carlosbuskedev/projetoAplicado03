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

    public function getAvailableWeeks(int $userId): array
    {
        $weeks = $this->sideQuestModel->getWeeksByUser($userId);
        return array_values(array_unique(array_map('intval', array_column($weeks, 'week'))));
    }

    public function getWeeklyDiagnostic(int $userId, int $week): string
    {
        $feedbacks = $this->themeModel->findAll();

        if (empty($feedbacks)) {
            return 'Ainda não há diagnóstico disponível para esta semana.';
        }

        shuffle($feedbacks);
        return $feedbacks[0]['feedback'] ?? 'Ainda não há diagnóstico disponível para esta semana.';
    }

    private function getDayTitle(int $day): string
    {
        $titles = [
            1 => 'Rompendo a inércia',
            2 => 'Reforço da neuroplasticidade',
            3 => 'O ponto de resistência',
            4 => 'A virada da semana',
            5 => 'Consolidação do hábito',
            6 => 'Respiro parassimpático - Fim de semana',
            7 => 'A Grande Conquista',
        ];

        return $titles[$day] ?? 'Dia atual';
    }

    private function getDayMessage(int $day): string
    {
        $messages = [
            1 => 'Excelente! O primeiro passo é sempre o mais difícil para o cérebro, pois exige romper o piloto automático. Você acabou de iniciar a construção de uma nova via neural de foco. Nos vemos amanhã!',
            2 => 'Muito bem! A repetição é a chave para mudar hábitos. Ao voltar hoje, você sinalizou para a sua mente que a sua atenção é uma prioridade. Continue assim!',
            3 => 'Ótimo trabalho! O terceiro dia costuma trazer resistência, pois o cérebro sente falta da dopamina fácil das telas. Você venceu esse impulso hoje. Orgulhe-se!',
            4 => 'Você passou da metade do caminho! Sua capacidade de sustentar a atenção e resistir a distrações está ficando mais forte a cada dia. O controle está voltando para as suas mãos.',
            5 => 'Incrível! Com 5 dias seguidos, o seu circuito de recompensa começa a se reequilibrar, buscando satisfação na conclusão de metas reais, e não apenas no mundo virtual. Quase lá!',
            6 => 'Dia de manutenção concluído! Desacelerar hoje é fundamental para ativar seu sistema nervoso de descanso e restaurar sua energia mental. Aproveite a clareza de hoje.',
            7 => 'Desafio semanal concluído com sucesso! Você provou que é capaz de guiar a própria atenção. Sua recompensa acaba de ser desbloqueada. Aproveite, você mereceu cada etapa desse processo!',
        ];

        return $messages[$day] ?? '';
    }

    public function getLastWeek(int $userId): ?int
    {
        $lastWeek = $this->sideQuestModel->findLastWeekByUser($userId);
        return $lastWeek ? (int) $lastWeek['week'] : null;
    }

    public function create(array $data): array
    {
        $answers = $data['responses'] ?? [];
        $userId = service('authSession')->id();

        if (empty($answers) || ! is_array($answers)) {
            return $this->failure('Nenhuma resposta enviada.', 422);
        }

        $lastWeek = $this->getLastWeek($userId);
        $nextWeek = 1;

        if ($lastWeek !== null) {
            $nextWeek = ((int) $lastWeek) + 1;
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

    public function getWeekDaysStatus(int $userId, int $week): array
    {
        // Load weekly diagnostic status records if any
        $statusModel = new \App\Models\WeeklyDiagnosticStatusModel();
        $records = $statusModel
            ->where('users_id', $userId)
            ->where('week', $week)
            ->orderBy('day', 'ASC')
            ->findAll();

        $byDay = [];
        foreach ($records as $r) {
            $byDay[(int) $r['day']] = $r;
        }

        $today = (new \DateTimeImmutable('today'))->format('Y-m-d');
        $days = [];

        for ($day = 1; $day <= 7; $day++) {
            $record = $byDay[$day] ?? null;
            $deadline = $record['deadline'] ?? null;
            $completed = isset($record['completed']) ? (int) $record['completed'] : 0;

            if ($record !== null) {
                if ($completed === 1) {
                    $status = 'completed';
                } else {
                    if ($deadline < $today) {
                        $status = 'missed';
                    } elseif ($deadline === $today) {
                        $status = 'current';
                    } else {
                        $status = 'upcoming';
                    }
                }
            } else {
                // no record: assume upcoming unless day equals 1 and week is current week
                $status = 'upcoming';
            }

            $days[] = [
                'day' => $day,
                'status' => $status,
                'label' => $status === 'completed' ? 'Cumprido' : ($status === 'missed' ? 'Não cumprido' : ($status === 'current' ? 'Atual' : 'Por vir')),
                'current' => false,
                'title' => $this->getDayTitle($day),
                'message' => $this->getDayMessage($day),
                'deadline' => $deadline,
                'completed' => $completed,
            ];
        }

        // determine current day: prefer exact deadline == today, else first upcoming, else last completed
        $indexCurrent = null;
        foreach ($days as $i => $d) {
            if (isset($d['deadline']) && $d['deadline'] === $today) {
                $indexCurrent = $i;
                break;
            }
        }

        if ($indexCurrent === null) {
            foreach ($days as $i => $d) {
                if ($d['status'] === 'upcoming') {
                    $indexCurrent = $i;
                    break;
                }
            }
        }

        if ($indexCurrent === null) {
            // fallback: last completed
            for ($i = count($days) - 1; $i >= 0; $i--) {
                if ($days[$i]['status'] === 'completed') {
                    $indexCurrent = $i;
                    break;
                }
            }
        }

        if ($indexCurrent === null) {
            $indexCurrent = 0;
        }

        $days[$indexCurrent]['current'] = true;

        return $days;
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
