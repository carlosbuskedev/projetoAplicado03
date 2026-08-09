<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Services\SideQuest\SideQuestService;

class WeeklyDiagnostic extends BaseController
{
    private SideQuestService $sideQuestService;

    public function __construct()
    {
        $this->sideQuestService = new SideQuestService();
    }

    public function summary()
    {
        $userId = service('authSession')->id();
        $week = (int) $this->request->getGet('week');

        $availableWeeks = $this->sideQuestService->getAvailableWeeks($userId);

        if (empty($availableWeeks)) {
            $selectedWeek = 1;
        } else {
            $selectedWeek = $week > 0 && in_array($week, $availableWeeks, true)
                ? $week
                : (int) end($availableWeeks);
        }

        $diagnostic = $this->sideQuestService->getWeeklyDiagnostic($userId, $selectedWeek);
        $daysStatus = $this->sideQuestService->getWeekDaysStatus($userId, $selectedWeek);

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'weeks' => $availableWeeks,
                'selectedWeek' => $selectedWeek,
                'diagnostic' => $diagnostic,
                'daysStatus' => $daysStatus,
            ],
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
