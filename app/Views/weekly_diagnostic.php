<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO – Diagnóstico Semanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/weekly_diagnostic.css">
    <script src="/js/dev-guard.js"></script>
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
                    <div class="col text-center">
                        <h1 class="pixel-title mb-0">Diagnóstico Semanal</h1>
                    </div>
                    <div class="col-auto d-none d-md-block" style="width: 120px;"></div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <div class="mindful-mascot">
                        <button type="button" class="sound-toggle" id="soundToggle" title="Ativar/desativar som ambiente">🔇</button>
                        <div class="water-ambience"></div>
                        <div class="mindful-mascot-frame"><img src="/images/mindfulness-mascot-meditating.png" alt="Mascote Mindfulness meditando"></div>
                    </div>
                    <audio id="ambientAudio" loop>
                        <source src="/audio/mindfulness-ambiente.mp3" type="audio/mpeg">
                    </audio>

                    <div class="diagnostic-card">
                        <div class="week-selector">
                            <label class="pixel-label" for="weekSelect">Semana</label>
                            <select id="weekSelect" class="pixel-input"></select>
                        </div>

                        <div id="diagnosticText">
                            <h2 class="rpg-section-title mb-3"><i class="bi bi-clipboard2-pulse"></i> Diagnóstico</h2>
                            <p id="diagnosticContent" class="pixel-diagnostic-text">Carregando diagnóstico...</p>
                        </div>
                    </div>

                    <h3 class="pixel-subtitle">Sua Semana</h3>
                    <div class="days-grid" id="daysGrid"></div>

                    <div class="day-description" id="dayDescription">
                        <h3 id="dayTitle" class="pixel-day-title">Dia atual</h3>
                        <p id="dayMessage" class="pixel-day-message">Selecione a semana ou marque o dia atual conforme necessário.</p>
                    </div>

                    <div class="status-actions d-flex flex-wrap gap-3 justify-content-center">
                        <button id="btnCompleted" class="pixel-btn pixel-btn-success">✔ Marcar como realizada</button>
                        <button id="btnMissed" class="pixel-btn pixel-btn-danger">✕ Marcar como não realizada</button>
                        <button id="btnFormulario" class="pixel-btn pixel-btn-primary" style="display: none;">↻ Recomeçar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/weekly_diagnostic.js"></script>
    <script>
        document.getElementById('soundToggle').addEventListener('click', function () {
            const audio = document.getElementById('ambientAudio');
            const btn = this;
            if (audio.paused) {
                audio.play().then(() => { btn.textContent = '🔊'; }).catch(() => { btn.textContent = '🔇'; });
            } else {
                audio.pause();
                btn.textContent = '🔇';
            }
        });
    </script>
</body>
</html>
