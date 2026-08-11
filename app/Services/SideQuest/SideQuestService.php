<?php

namespace App\Services\SideQuest;

use App\Models\Backend\SideQuestModel;
use App\Models\BehavioralQuestionModel;
use App\Models\BehavioralResponsesScaleModel;
use App\Models\ThemeBehavioralQuestionModel;
use App\Models\WeeklyActivitiesModel;
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

    public function getWeeklyDiagnostic(int $userId, int $week): ?array
    {
        if ($userId === null || $week <= 0) {
            return null;
        }

        $themeOrder = <<<'SQL'
CASE
    WHEN theme_behavioral_questions.id = 6 THEN 1
    WHEN theme_behavioral_questions.id = 8 THEN 2
    WHEN theme_behavioral_questions.id = 2 THEN 3
    WHEN theme_behavioral_questions.id = 1 THEN 4
    WHEN theme_behavioral_questions.id = 3 THEN 5
    WHEN theme_behavioral_questions.id = 5 THEN 6
    WHEN theme_behavioral_questions.id = 4 THEN 7
    WHEN theme_behavioral_questions.id = 9 THEN 8
    WHEN theme_behavioral_questions.id = 7 THEN 9
    WHEN theme_behavioral_questions.id = 10 THEN 10
    ELSE 99
END
SQL;

        $rows = $this->sideQuestModel
            ->select('theme_behavioral_questions.id AS id, theme_behavioral_questions.feedback AS feedback')
            ->join(
                'behavioral_questions',
                'behavioral_responses.behavioral_questions_id = behavioral_questions.id'
            )
            ->join(
                'theme_behavioral_questions',
                'behavioral_questions.theme_behavioral_question_id = theme_behavioral_questions.id'
            )
            ->join(
                'behavioral_responses_scale',
                'behavioral_responses.behavioral_responses_scale_id = behavioral_responses_scale.id'
            )
            ->where('behavioral_responses.users_id', $userId)
            ->where('behavioral_responses.week', $week)
            ->orderBy('behavioral_responses_scale.score', 'DESC')
            ->orderBy($themeOrder, 'ASC', false)
            ->first();

        if (empty($rows)) {
            return null;
        }
        
        return $rows;
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

    public function markDayStatus(int $userId, int $week, int $day, bool $completed): array
    {
        if ($userId <= 0 || $week <= 0 || $day <= 0) {
            return $this->failure('Dados do dia ou semana inválidos.', 422);
        }

        $statusModel = new \App\Models\WeeklyDiagnosticStatusModel();
        $record = $statusModel
            ->where('users_id', $userId)
            ->where('week', $week)
            ->where('day', $day)
            ->first();

        if ($record === null) {
            return $this->failure('Registro do dia não encontrado para este usuário.', 404);
        }

        $updated = $statusModel->update($record['id'], [
            'completed' => $completed ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($updated === false) {
            return $this->failure('Não foi possível atualizar o status do dia.', 500);
        }

        return $this->success('Status atualizado com sucesso.', [
            'day' => $day,
            'week' => $week,
            'completed' => $completed ? 1 : 0,
        ]);
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
        $activityModel = new WeeklyActivitiesModel();

        $records = $statusModel
            ->where('users_id', $userId)
            ->where('week', $week)
            ->orderBy('day', 'ASC')
            ->findAll();

        $activityIds = array_filter(array_column($records, 'weekly_activities_id'), fn ($id) => $id !== null);
        $activities = $activityIds ? $activityModel->whereIn('id', $activityIds)->findAll() : [];
        $activityById = [];
        foreach ($activities as $activity) {
            $activityById[(int) $activity['id']] = $activity;
        }

        $byDay = [];
        foreach ($records as $r) {
            $byDay[(int) $r['day']] = $r;
        }

        $today = (new \DateTimeImmutable('today'))->format('Y-m-d');
        $days = [];

        for ($day = 1; $day <= 7; $day++) {
            $record = $byDay[$day] ?? null;
            $deadline = $record['deadline'] ?? null;
            $completed = $record['completed'];

            if ($record !== null) {
                if (is_numeric($completed) && $completed == 1) {
                    $status = 'completed';
                } elseif ($deadline < $today || (is_numeric($completed) && $completed == 0)) {
                    $status = 'missed';
                } elseif ($deadline === $today) {
                    $status = 'current';
                } else {
                    $status = 'upcoming';
                }
            } else {
                // no record: assume upcoming unless day equals 1 and week is current week
                $status = 'upcoming';
            }

            $activity = null;
            if ($record !== null && ! empty($record['weekly_activities_id'])) {
                $activity = $activityById[(int) $record['weekly_activities_id']] ?? null;
            }

            $days[] = [
                'day' => $day,
                'status' => $status,
                'label' => $status === 'completed' ? 'Cumprido' : ($status === 'missed' ? 'Não cumprido' : ($status === 'current' ? 'Atual' : 'Por vir')),
                'current' => ($deadline === $today),
                'title' => $this->getDayTitle($day),
                'message' => $this->getDayMessage($day),
                'deadline' => $deadline,
                'completed' => $completed,
                'objective' => $activity['objective'] ?? null,
                'task' => $activity['task'] ?? null,
            ];
        }

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
