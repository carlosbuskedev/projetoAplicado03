<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Iniciar Jornada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/principal.css">
    <link rel="stylesheet" href="/css/jornada.css">
</head>
<body>
    <div class="pixel-menu-container">
        <!-- Header -->
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a class="btn pixel-btn pixel-btn-back" href="/menu">
                            ← Voltar
                        </a>
                    </div>
                    <div class="col">
                        <h1 class="pixel-title mb-0 text-center">INICIAR JORNADA</h1>
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
                        <?php foreach ($cards as $card): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('<?= $card['titulo'] ?>', <?= $card['tempo'] ?>)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title"><?= $card['titulo'] ?></h3>
                                <p class="activity-card-description"><?= $card['descricao'] ?></p>
                                <div class="activity-card-time"><?= $card['tempo'] ?> min</div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Pomodoro Timer -->
        <div id="pomodoroModal" class="pomodoro-modal">
            <div class="pomodoro-modal-content">
                <button class="pomodoro-close-btn" onclick="closePomodoroModal()">×</button>
                
                <h2 class="pomodoro-title" id="activityName">Timer Pomodoro</h2>
                
                <!-- Tomate Animado -->
                <div class="tomato-container">
                    <div class="tomato">
                        🍅
                    </div>
                </div>

                <!-- Timer Display -->
                <div class="timer-display" id="timerDisplay">25:00</div>

                <!-- Controles -->
                <div class="timer-controls">
                    <button class="pixel-btn timer-btn timer-btn-start" id="startBtn" onclick="startTimer()">
                        Iniciar
                    </button>
                    <button class="pixel-btn timer-btn timer-btn-pause" id="pauseBtn" onclick="pauseTimer()" style="display: none;">
                        Pausar
                    </button>
                    <button class="pixel-btn timer-btn timer-btn-reset" onclick="resetTimer()">
                        Resetar
                    </button>
                </div>

                <!-- Cards de Estatísticas -->
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-card-value" id="pomodorosCompleted">0</div>
                        <div class="stat-card-label">Pomodoros</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-value" id="totalMinutes">0</div>
                        <div class="stat-card-label">Minutos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-value" id="currentStreak">0</div>
                        <div class="stat-card-label">Sequência</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/jornada.js"></script>
</body>
</html>
