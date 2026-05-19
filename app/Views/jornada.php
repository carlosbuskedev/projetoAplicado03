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
                        <a class="btn pixel-btn pixel-btn-back" href="/">
                            ← Voltar
                        </a>
                    </div>
                    <div class="col">
                        <h1 class="pixel-title mb-0 text-center">INICIAR JORNADA</h1>
                    </div>
                    <div class="col-auto">
                        <button class="btn pixel-btn pixel-btn-login" onclick="openLoginModal()">
                            Login
                        </button>
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
                        <!-- Card 1 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Estudar React', 25)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Estudar React</h3>
                                <p class="activity-card-description">Aprender componentes e hooks</p>
                                <div class="activity-card-time">25 min</div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Exercícios Físicos', 30)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Exercícios Físicos</h3>
                                <p class="activity-card-description">Treino completo do dia</p>
                                <div class="activity-card-time">30 min</div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Meditação', 15)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Meditação</h3>
                                <p class="activity-card-description">Relaxar e focar a mente</p>
                                <div class="activity-card-time">15 min</div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Leitura', 45)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Leitura</h3>
                                <p class="activity-card-description">Ler um capítulo novo</p>
                                <div class="activity-card-time">45 min</div>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Projeto Pessoal', 50)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Projeto Pessoal</h3>
                                <p class="activity-card-description">Desenvolver nova feature</p>
                                <div class="activity-card-time">50 min</div>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="activity-card" onclick="openPomodoroModal('Idiomas', 20)">
                                <div class="activity-card-icon"></div>
                                <h3 class="activity-card-title">Idiomas</h3>
                                <p class="activity-card-description">Praticar conversação</p>
                                <div class="activity-card-time">20 min</div>
                            </div>
                        </div>
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

        <!-- Modal de Login (reutilizado) -->
        <div id="loginModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content pixel-modal">
                    <div class="modal-header border-0">
                        <h5 class="modal-title pixel-modal-title">Login</h5>
                        <button type="button" class="btn-close pixel-close-btn" onclick="closeLoginModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form onsubmit="handleLogin(event)">
                            <div class="mb-3">
                                <label for="username" class="form-label pixel-label">Usuário</label>
                                <input type="text" class="form-control pixel-input" id="username" placeholder="Digite seu usuário" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label pixel-label">Senha</label>
                                <input type="password" class="form-control pixel-input" id="password" placeholder="Digite sua senha" required>
                            </div>
                            <button type="submit" class="btn pixel-btn pixel-btn-primary w-100">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jornada.js"></script>
</body>
</html>
