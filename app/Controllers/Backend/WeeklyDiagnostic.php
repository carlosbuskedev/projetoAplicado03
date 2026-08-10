<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\WeeklyDiagnosticStatusModel;
use App\Models\WeeklyActivitiesModel;
use App\Services\SideQuest\SideQuestService;

class WeeklyDiagnostic extends BaseController
{
    private SideQuestService $sideQuestService;

    public function __construct()
    {
        $this->sideQuestService = new SideQuestService();
    }

    public function getWeeks()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $userId = $data['user_id'] ?? null;

        $availableWeeks = $this->sideQuestService->getAvailableWeeks($userId);

        if (empty($availableWeeks)) {
            $selectedWeek = 1;
        } else {
            $selectedWeek = (int) end($availableWeeks);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'weeks' => $availableWeeks,
                'selectedWeek' => $selectedWeek
            ],
        ]);
    }

    public function activities()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $userId = $data['user_id'] ?? null;
        $week = $data['week'] ?? null;

        $availableWeeks = $this->sideQuestService->getAvailableWeeks($userId);

        if (empty($availableWeeks)) {
            $selectedWeek = 1;
        } else {
            $selectedWeek = $week > 0 && in_array((int) $week, $availableWeeks, true)
                ? (int) $week
                : (int) end($availableWeeks);
        }

        $diagnostic = $this->sideQuestService->getWeeklyDiagnostic($userId, $selectedWeek);
        $daysStatus = $this->sideQuestService->getWeekDaysStatus($userId, $selectedWeek);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'weeks' => $availableWeeks,
                'selectedWeek' => $selectedWeek,
                'diagnostic' => $diagnostic['feedback'] ?? null,
                'daysStatus' => $daysStatus,
            ],
        ]);
    }

    public function initialize()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
                
        $userId = $data['user_id'] ?? null;
        $themeId = $data['theme_id'] ?? null;

        if ($userId === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Usuário não autenticado.',
            ])->setStatusCode(401);
        }

        // busca a última semana cadastrada para o usuário
        $week = $this->sideQuestService->getLastWeek($userId);

        if ($week === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nenhuma semana respondida foi não encontrada para o usuário.',
            ])->setStatusCode(500);
        }

        $statusModel = new WeeklyDiagnosticStatusModel();
        $existing = $statusModel
            ->where('users_id', $userId)
            ->where('week', $week)
            ->first();

        if ($existing !== null) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Diagnóstico semanal já inicializado.',
            ]);
        }

        $diagnostic = $this->sideQuestService->getWeeklyDiagnostic($userId, $week);

        $activitiesModel = new WeeklyActivitiesModel();
        $tasks = $activitiesModel
            ->where('theme_behavioral_questions_id', $diagnostic['id'])
            ->orderBy('RAND()', 'ASC', false)
            ->findAll();

        if (empty($tasks)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nenhuma atividade registrada.',
            ]);
        }

        $today = new \DateTimeImmutable('today');
        $now = date('Y-m-d H:i:s');
        $batch = [];

        for ($day = 1; $day <= 7; $day++) {
            $deadline = $today->modify(sprintf('+%d days', $day - 1))->format('Y-m-d');
            $batch[] = [
                'users_id' => $userId,
                'weekly_activities_id' => $tasks[$day - 1]['id'] ?? null,
                'week' => $week,
                'day' => $day,
                'deadline' => $deadline,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($statusModel->insertBatch($batch) === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Não foi possível inicializar o diagnóstico semanal.',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Diagnóstico semanal inicializado com sucesso.',
        ]);
    }

    public function updateStatus()
    {
        $userId = service('authSession')->id();
        $data = $this->request->getJSON(true) ?? [];
        $day = isset($data['day']) ? (int) $data['day'] : null;
        $week = isset($data['week']) ? (int) $data['week'] : null;
        $completed = isset($data['completed']) ? filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN) : null;

        if ($day === null || $week === null || $completed === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dia, semana ou status inválido.',
            ])->setStatusCode(422);
        }

        $result = $this->sideQuestService->markDayStatus($userId, $week, $day, $completed);

        return $this->response->setJSON($result)
            ->setStatusCode($result['success'] ? 200 : 400);
    }
}
