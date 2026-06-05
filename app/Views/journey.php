<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Iniciar Jornada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/journey.css">
</head>
<body>
    <div class="pixel-menu-container">
        <!-- Header -->
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a href="/home" class="pixel-btn pixel-btn-back">◀ Voltar</a>
                    </div>
                    <div class="col">
                        <h1 class="pixel-title mb-0 text-center">Iniciar Jornada</h1>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn pixel-btn pixel-btn-login" id="btnConta">Conta</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Painel de Atividades -->
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <h2 class="pixel-section-title text-center mb-4">Escolha sua Atividade</h2>
                    
                    <div class="row g-4">
                        <?php if (empty($cards)): ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <p>Nenhuma missão disponível. <a href="/quests">Crie uma nova missão</a></p>
                            </div>
                        </div>
                        <?php else: ?>
                            <?php foreach ($cards as $card): ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="activity-card" 
                                     onclick="openPomodoroModal(<?= (int)$card['id'] ?>)"
                                     data-quest-id="<?= (int)$card['id'] ?>"
                                     data-quest-title="<?= htmlspecialchars($card['titulo']) ?>"
                                     data-quest-time="<?= (int)$card['tempo'] ?>"
                                     data-quest-estimated-time="<?= htmlspecialchars($card['estimated_time']) ?>"
                                     data-quest-remaining-time="<?= htmlspecialchars($card['remaining_time'] ?? '') ?>"
                                     data-quest-xp="<?= (int)$card['experience'] ?>"
                                     data-quest-difficulty="<?= (int)$card['difficulty'] ?>"
                                     data-quest-priority="<?= htmlspecialchars($card['priority']) ?>"
                                     data-quest-deadline="<?= htmlspecialchars($card['deadline']) ?>"
                                     data-quest-interruptions="<?= (int) $card['interruptions_count'] ?>"
                                     data-quest-started-date="<?= htmlspecialchars($card['started_date'] ?? '') ?>">
                                    <div class="activity-card-icon"></div>
                                    <h3 class="activity-card-title"><?= htmlspecialchars($card['titulo']) ?></h3>
                                    <p class="activity-card-description"><?= htmlspecialchars($card['descricao']) ?></p>
                                    <div class="activity-card-time"><?= (int) $card['tempo'] ?> min</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pomodoro Timer -->
        <div id="pomodoroModal" class="pomodoro-modal">
            <div class="pomodoro-modal-content">
                <div class="quest-modal-header">
                    <div class="quest-badges-top">
                        <div class="priority-badge" id="priorityBadge">
                            Normal
                        </div>
                        <div class="difficulty-badge" id="difficultyBadge">
                            Normal
                        </div>
                    </div>
                    <button class="pomodoro-close-btn" onclick="closePomodoroModal()">×</button>
                </div>

                <h2 class="pomodoro-title" id="activityName">Timer Pomodoro</h2>
                                
                <!-- Tomate Animado -->
                <div class="tomato-container">
                    <div class="tomato">
                       <img 
                            class="tomato-gif"
                            src="/images/tomate.gif" 
                            alt="🍅"
                        >
                    </div>
                </div>

                <!-- Timer Display -->
                <div class="timer-display" id="timerDisplay">25:00</div>

                <!-- Controles -->
                <div class="timer-controls">
                    <button class="pixel-btn timer-btn timer-btn-start" id="startBtn" onclick="startTimer()">
                        Iniciar
                    </button>
                    <button class="pixel-btn timer-btn timer-btn-pause" id="pauseBtn" onclick="handlePauseClick()" style="display: none;">
                        Pausar
                    </button>
                    <button class="pixel-btn timer-btn timer-btn-complete" id="completeBtn" onclick="completeQuest()">
                        Concluir
                    </button>
                </div>

                <!-- Informações da Quest -->
                <div class="row g-2">
                    <div class="col-4">
                        <div class="quest-info-box">
                            <div class="quest-info-label">XP</div>
                            <div class="quest-info-value" id="questXP">0</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quest-info-box">
                            <div class="quest-info-label">Prazo</div>
                            <div class="quest-info-value" id="questDeadline">-</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="quest-info-box">
                            <div class="quest-info-label">Interrupções</div>
                            <div class="quest-info-value" id="questInterruptions">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/journey.js"></script>
</body>
</html>
