<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO – Diagnóstico Semanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <style>
        .diagnostic-card {
            background: #faf8f5;
            border: 5px solid #e8dfd6;
            box-shadow: 8px 8px 0 #e8dfd6;
            padding: 2rem;
            margin-bottom: 2rem;
            font-family: 'Press Start 2P', cursive;
        }
        .week-selector {
            max-width: 320px;
            margin-bottom: 1.5rem;
        }
        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .day-card {
            border: 4px solid #d9c5b0;
            background: #f5ebe0;
            padding: 1rem;
            text-align: center;
            color: #6f5a42;
            font-family: 'Press Start 2P', cursive;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
            min-height: 110px;
        }
        .day-card.current {
            border-color: #3c76d8;
            background: #d8e7ff;
            color: #1a3f7d;
            transform: scale(1.08);
            box-shadow: 0 0 0 4px rgba(60, 118, 216, 0.15);
        }
        .day-card.completed {
            border-color: #2d8a3b;
            background: #dbf2db;
            color: #1f5f31;
        }
        .day-card.missed {
            border-color: #b72e2e;
            background: #f8d6d6;
            color: #7d2020;
        }
        .day-card.upcoming {
            border-color: #8c8c8c;
            background: #e6e6e6;
            color: #5f5f5f;
        }
        .day-card span {
            display: block;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }
        .day-description {
            background: #faf8f5;
            border: 5px solid #e8dfd6;
            padding: 1.5rem;
            min-height: 140px;
            margin-bottom: 1.5rem;
        }
        .status-actions button {
            min-width: 160px;
            font-family: 'Press Start 2P', cursive;
        }
    </style>
    <script src="/js/dev-guard.js"></script>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Diagnóstico Semanal</h1>

        <div class="diagnostic-card">
            <div class="week-selector">
                <label class="form-label" for="weekSelect">Semana</label>
                <select id="weekSelect" class="form-select"></select>
            </div>

            <div id="diagnosticText">
                <h2 class="mb-3">Diagnóstico</h2>
                <p id="diagnosticContent">Carregando diagnóstico...</p>
            </div>
        </div>

        <div class="days-grid" id="daysGrid"></div>

        <div class="day-description" id="dayDescription">
            <h3 id="dayTitle">Dia atual</h3>
            <p id="dayMessage">Selecione a semana ou marque o dia atual conforme necessário.</p>
        </div>

        <div class="status-actions d-flex gap-3">
            <button id="btnCompleted" class="btn btn-success">Marcar como realizada</button>
            <button id="btnMissed" class="btn btn-danger">Marcar como não realizada</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/weekly_diagnostic.js"></script>
</body>
</html>
