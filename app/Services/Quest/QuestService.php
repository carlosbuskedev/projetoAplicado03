<?php

namespace App\Services\Quest;

use App\Models\Backend\QuestModel;
use Config\Auth;

class QuestService
{
    private QuestModel $questModel;

    private Auth $authConfig;

    public function __construct()
    {
        $this->questModel = new QuestModel();
        $this->authConfig = config('Auth');
    }

    public function create(array $data): array
    {
        $validation = $this->validatePayload($data);

        if (! $validation['success']) {
            return $validation;
        }

        $payload = $validation['data'];

        $id = $this->questModel->createQuest($payload);

        if ($id === false) {
            return $this->failure('Não foi possível cadastrar a quest.', 500);
        }

        return $this->success('Quest cadastrada com sucesso.', [
            'quest' => $this->questModel->findById((int) $id),
        ], 201);
    }

    private function validatePayload(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $shortDescription = trim((string) ($data['description'] ?? $data['short_description'] ?? ''));
        $category = trim((string) ($data['category'] ?? ''));
        $hours = isset($data['estimatedHours']) ? (int) $data['estimatedHours'] : (int) ($data['hours'] ?? 0);
        $minutes = isset($data['estimatedMinutes']) ? (int) $data['estimatedMinutes'] : (int) ($data['minutes'] ?? 0);
        $difficulty = trim((string) ($data['difficulty'] ?? 'medio'));
        $priority = trim((string) ($data['priority'] ?? 'media'));
        $status = trim((string) ($data['status'] ?? 'a-fazer'));
        $deadline = trim((string) ($data['deadline'] ?? ''));
        $experience = isset($data['experience']) ? (int) $data['experience'] : (int) ($data['score'] ?? 0);

        if ($title === '') {
            return $this->failure('Título é obrigatório.', 422);
        }

        if ($hours < 0 || $minutes < 0 || $minutes > 59) {
            return $this->failure('Tempo estimado inválido.', 422);
        }

        if (! in_array($difficulty, ['facil', 'medio', 'dificil', 'muito-dificil'], true)) {
            return $this->failure('Dificuldade inválida.', 422);
        }

        if (! in_array($priority, ['baixa', 'media', 'alta'], true)) {
            return $this->failure('Prioridade inválida.', 422);
        }

        if (! in_array($status, ['a-fazer', 'fazendo', 'feito'], true)) {
            return $this->failure('Status inválido.', 422);
        }

        if ($deadline === '') {
            return $this->failure('Prazo é obrigatório.', 422);
        }

        $deadlineDate = \DateTime::createFromFormat('Y-m-d', $deadline);

        if ($deadlineDate === false || $deadlineDate->format('Y-m-d') !== $deadline) {
            return $this->failure('Prazo inválido.', 422);
        }

        if ($experience < 0) {
            return $this->failure('Experiência inválida.', 422);
        }

        return $this->success('Validação OK.', [
            'title'             => $title,
            'short_description' => $shortDescription !== '' ? $shortDescription : null,
            'category'          => $category !== '' ? $category : null,
            'estimated_time'    => sprintf('%02d:%02d:00', $hours, $minutes),
            'difficulty'        => $this->normalizeDifficulty($difficulty),
            'priority'          => $this->normalizePriority($priority),
            'status'            => $this->normalizeStatus($status),
            'deadline'          => $deadline,
            'experience'        => $experience,
        ]);
    }

    private function normalizeDifficulty(string $difficulty): int
    {
        return match ($difficulty) {
            'facil' => 1,
            'medio' => 2,
            'dificil' => 3,
            'muito-dificil' => 4,
            default => 2,
        };
    }

    private function normalizePriority(string $priority): string
    {
        return match ($priority) {
            'baixa' => 'low',
            'media' => 'medium',
            'alta' => 'high',
            default => 'medium',
        };
    }

    private function normalizeStatus(string $status): string
    {
        return match ($status) {
            'a-fazer' => 'to_do',
            'fazendo' => 'in_progress',
            'feito' => 'done',
            default => 'to_do',
        };
    }

    private function success(string $message, array $data = [], int $code = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
        ];
    }

    private function failure(string $message, int $code): array
    {
        return [
            'success' => false,
            'message' => $message,
            'code'    => $code,
            'data'    => [],
        ];
    }
}
