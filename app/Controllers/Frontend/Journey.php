<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\Backend\QuestModel;

class Journey extends BaseController
{
    private QuestModel $questModel;

    public function __construct()
    {
        $this->questModel = new QuestModel();
    }

    public function index(): string
    {
        // Buscar apenas quests não concluídas
        $quests = $this->questModel->where('completed_date', null)->findAll();

        // Transformar os dados das quests para o formato esperado pela view
        $cards = $this->formatQuestsForView($quests);

        return view('journey', ['cards' => $cards]);
    }

    private function formatQuestsForView(array $quests): array
    {
        $formatted = [];

        foreach ($quests as $quest) {
            // Converter o tempo estimado (HH:MM:SS) para minutos
            $estimatedMinutes = $this->timeToMinutes($quest['estimated_time']);

            $formatted[] = [
                'id'                 => $quest['id'],
                'titulo'             => $quest['title'],
                'descricao'          => $quest['short_description'] ?? $quest['title'],
                'tempo'              => $estimatedMinutes,
                'difficulty'         => $quest['difficulty'],
                'priority'           => $quest['priority'],
                'deadline'           => $quest['deadline'],
                'experience'         => $quest['experience'],
                'estimated_time'     => $quest['estimated_time'],
                'remaining_time'     => $quest['remaining_time'],
                'interruptions_count'=> $quest['interruptions_count'] ?? 0,
                'started_date'       => $quest['started_date'] ?? null,
            ];
        }

        return $formatted;
    }

    private function timeToMinutes(string $time): int
    {
        // Formato esperado: HH:MM:SS
        $parts = explode(':', $time);
        $hours = (int) ($parts[0] ?? 0);
        $minutes = (int) ($parts[1] ?? 0);

        return ($hours * 60) + $minutes;
    }
}
