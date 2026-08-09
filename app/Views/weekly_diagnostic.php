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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!AuthSession.requirePage('weekly_diagnostic')) {
                return;
            }

            const weekSelect = document.getElementById('weekSelect');
            const daysGrid = document.getElementById('daysGrid');
            const dayTitle = document.getElementById('dayTitle');
            const dayMessage = document.getElementById('dayMessage');
            const diagnosticContent = document.getElementById('diagnosticContent');
            const btnCompleted = document.getElementById('btnCompleted');
            const btnMissed = document.getElementById('btnMissed');

            const diagnostics = [
                'O seu diagnóstico aponta que o design de "feed sem fim" capturou sua atenção automatizada. A funcionalidade de rolagem infinita elimina a sensação de que o conteúdo é finito, criando um ciclo contínuo de consumo.',
                'O seu diagnóstico indica uma forte reatividade aos alertas digitais. Cada notificação atua no seu cérebro como um gatilho de recompensa que desperta curiosidade e libera dopamina por antecipação.',
                'O seu diagnóstico revela traços da Síndrome de FoMO. A ansiedade de perder algo online gera um ciclo de retorno constante às telas.',
                'O seu diagnóstico sugere uma saturação mental. O consumo excessivo de materiais triviais compromete memória, raciocínio e atenção.',
                'O seu diagnóstico aponta uma dependência do ciclo de recompensas variáveis das redes sociais. Curtidas e comentários funcionam como feedback imediato que mantém você engajado.',
            ];

            const dayMessages = {
                1: 'Excelente! O primeiro passo é sempre o mais difícil para o cérebro, pois exige romper o piloto automático. Você acabou de iniciar a construção de uma nova via neural de foco. Nos vemos amanhã!',
                2: 'Muito bem! A repetição é a chave para mudar hábitos. Ao voltar hoje, você sinalizou para a sua mente que a sua atenção é uma prioridade. Continue assim!',
                3: 'Ótimo trabalho! O terceiro dia costuma trazer resistência, pois o cérebro sente falta da dopamina fácil das telas. Você venceu esse impulso hoje. Orgulhe-se!',
                4: 'Você passou da metade do caminho! Sua capacidade de sustentar a atenção e resistir a distrações está ficando mais forte a cada dia. O controle está voltando para as suas mãos.',
                5: 'Incrível! Com 5 dias seguidos, o seu circuito de recompensa começa a se reequilibrar, buscando satisfação na conclusão de metas reais, e não apenas no mundo virtual. Quase lá!',
                6: 'Dia de manutenção concluído! Desacelerar hoje é fundamental para ativar seu sistema nervoso de descanso e restaurar sua energia mental. Aproveite a clareza de hoje.',
                7: 'Desafio semanal concluído com sucesso! Você provou que é capaz de guiar a própria atenção. Sua recompensa acaba de ser desbloqueada. Aproveite, você mereceu cada etapa desse processo!',
            };

            let currentWeek = getSelectedWeek();
            let currentDay = 1;
            let currentStatuses = [];

            function getSelectedWeek() {
                const params = new URLSearchParams(window.location.search);
                const week = Number(params.get('week'));
                return Number.isInteger(week) && week > 0 ? week : 4;
            }

            function setSelectedWeek(week) {
                const params = new URLSearchParams(window.location.search);
                params.set('week', String(week));
                window.history.replaceState(null, '', `${window.location.pathname}?${params.toString()}`);
                currentWeek = week;
            }

            function getDiagnosticText(week) {
                return diagnostics[(week - 1) % diagnostics.length];
            }

            function buildStatuses(week) {
                const statuses = [];
                const current = Math.min(week + 1, 7);

                for (let day = 1; day <= 7; day += 1) {
                    if (day < current) {
                        const completed = day % 2 === 1;
                        statuses.push({
                            day,
                            status: completed ? 'completed' : 'missed',
                            label: completed ? 'Cumprido' : 'Não cumprido',
                            current: false,
                        });
                    } else if (day === current) {
                        statuses.push({
                            day,
                            status: 'current',
                            label: 'Atual',
                            current: true,
                        });
                    } else {
                        statuses.push({
                            day,
                            status: 'upcoming',
                            label: 'Por vir',
                            current: false,
                        });
                    }
                }

                return statuses;
            }

            function renderWeekOptions() {
                const weeks = [1, 2, 3, 4];
                weekSelect.innerHTML = '';

                weeks.forEach(week => {
                    const option = document.createElement('option');
                    option.value = week;
                    option.textContent = `Semana ${week}`;
                    if (week === currentWeek) {
                        option.selected = true;
                    }
                    weekSelect.appendChild(option);
                });
            }

            function renderDays() {
                daysGrid.innerHTML = '';
                currentStatuses.forEach(day => {
                    const card = document.createElement('div');
                    card.className = `day-card ${day.status}`;
                    if (day.current) {
                        card.classList.add('current');
                    }
                    card.dataset.day = day.day;
                    card.innerHTML = `<div>Dia ${day.day}</div><span>${day.label}</span>`;
                    daysGrid.appendChild(card);
                });

                const active = currentStatuses.find(day => day.current);
                currentDay = active ? active.day : 1;
            }

            function updateDiagnostic() {
                diagnosticContent.textContent = getDiagnosticText(currentWeek);
            }

            function updateDayDescription() {
                const active = currentStatuses.find(day => day.current) || currentStatuses[0];
                if (!active) {
                    dayTitle.textContent = 'Dia atual';
                    dayMessage.textContent = 'Nenhum dia disponível no momento.';
                    return;
                }

                dayTitle.textContent = `Dia ${active.day}`;
                dayMessage.textContent = dayMessages[active.day];
            }

            function setDayStatus(dayNumber, completed) {
                currentStatuses = currentStatuses.map(day => {
                    if (day.day !== dayNumber) {
                        return day;
                    }

                    return {
                        ...day,
                        status: completed ? 'completed' : 'missed',
                        label: completed ? 'Cumprido' : 'Não cumprido',
                        current: true,
                    };
                });

                renderDays();
                updateDayDescription();
            }

            weekSelect.addEventListener('change', function () {
                const week = Number(this.value);
                setSelectedWeek(week);
                currentStatuses = buildStatuses(week);
                updateDiagnostic();
                renderDays();
                updateDayDescription();
            });

            btnCompleted.addEventListener('click', function () {
                setDayStatus(currentDay, true);
            });

            btnMissed.addEventListener('click', function () {
                setDayStatus(currentDay, false);
            });

            renderWeekOptions();
            currentStatuses = buildStatuses(currentWeek);
            updateDiagnostic();
            renderDays();
            updateDayDescription();
        });
    </script>
</body>
</html>
