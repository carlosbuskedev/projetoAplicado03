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

    public function update(int $id, array $data): array
    {
        $quest = $this->questModel->findById($id);

        if ($quest === null) {
            return $this->failure('Quest não encontrada.', 404);
        }

        $payload = [];

        if (array_key_exists('started_date', $data)) {
            $startedDate = trim((string) ($data['started_date'] ?? ''));
            if ($startedDate !== '' && $quest['started_date'] === null) {
                $startedAt = \DateTime::createFromFormat('Y-m-d', $startedDate);
                if ($startedAt === false || $startedAt->format('Y-m-d') !== $startedDate) {
                    return $this->failure('Data de início inválida.', 422);
                }

                $payload['started_date'] = $startedDate;
            }
        }

        if (array_key_exists('interruptions_count', $data)) {
            $interruptionsCount = (int) ($data['interruptions_count'] ?? 0);
            if ($interruptionsCount < 0) {
                return $this->failure('Número de interrupções inválido.', 422);
            }

            $payload['interruptions_count'] = $interruptionsCount;
        }

        if (array_key_exists('remaining_time', $data)) {
            $remainingTime = trim((string) ($data['remaining_time'] ?? ''));
            if ($remainingTime !== '') {
                if (! preg_match('/^\d{2}:\d{2}:\d{2}$/', $remainingTime)) {
                    return $this->failure('Tempo restante inválido.', 422);
                }

                $payload['remaining_time'] = $remainingTime;
            } else {
                $payload['remaining_time'] = null;
            }
        }

        if (array_key_exists('completed_date', $data)) {
            $completedDate = trim((string) ($data['completed_date'] ?? ''));
            if ($completedDate !== '') {
                if ($quest['completed_date'] !== null) {
                    return $this->failure('Quest já concluída.', 422);
                }

                if ($quest['started_date'] === null) {
                    return $this->failure('Quest precisa ser iniciada antes de concluir.', 422);
                }

                $completedAt = \DateTime::createFromFormat('Y-m-d', $completedDate);
                if ($completedAt === false || $completedAt->format('Y-m-d') !== $completedDate) {
                    return $this->failure('Data de conclusão inválida.', 422);
                }

                $payload['completed_date'] = $completedDate;
            }
        }

        if (empty($payload)) {
            return $this->failure('Nenhum dado para atualizar.', 422);
        }

        if ($this->questModel->updateQuest($id, $payload) === false) {
            return $this->failure('Não foi possível atualizar a quest.', 500);
        }

        return $this->success('Quest atualizada com sucesso.', [
            'quest' => $this->questModel->findById($id),
        ]);
    }

    private function validatePayload(array $data): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $shortDescription = trim((string) ($data['description'] ?? $data['short_description'] ?? ''));
        $hours = isset($data['estimatedHours']) ? (int) $data['estimatedHours'] : (int) ($data['hours'] ?? 0);
        $minutes = isset($data['estimatedMinutes']) ? (int) $data['estimatedMinutes'] : (int) ($data['minutes'] ?? 0);
        $difficulty = trim((string) ($data['difficulty'] ?? 'medio'));
        $priority = trim((string) ($data['priority'] ?? 'media'));
        $deadline = trim((string) ($data['deadline'] ?? ''));
        $experience = isset($data['experience']) ? (int) $data['experience'] : (int) ($data['experience'] ?? 0);
        $user_id = trim((string) ($data['user_id'] ?? ''));

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

        if (empty($user_id)) {
            return $this->failure('Usuário é obrigatório.', 422);
        }

        return $this->success('Validação OK.', [
            'title'             => $title,
            'short_description' => $shortDescription !== '' ? $shortDescription : null,
            'estimated_time'    => sprintf('%02d:%02d:00', $hours, $minutes),
            'difficulty'        => $this->normalizeDifficulty($difficulty),
            'priority'          => $this->normalizePriority($priority),
            'deadline'          => $deadline,
            'experience'        => $experience,
            'user_id'           => $user_id,
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
