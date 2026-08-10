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
        return Number.isInteger(week) && week > 0 ? week : 0;
    }

    function setSelectedWeek(week) {
        const params = new URLSearchParams(window.location.search);
        params.set('week', String(week));
        window.history.replaceState(null, '', `${window.location.pathname}?${params.toString()}`);
        currentWeek = week;
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
        const weeks = [];
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

    function updateDiagnostic(diagnosticText = null) {
        diagnosticContent.textContent = diagnosticText;
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

    async function initializeWeeklyDiagnostic() {
        const user = AuthSession.getUser();

        const payload = {
            user_id: user ? user.id : null
        };

        try {
            const response = await AuthSession.apiRequest('/api/weekly-diagnostic/initialize', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const body = await response.json();

            if (!response.ok || !body.success) {
                console.warn('Falha ao inicializar o diagnóstico semanal.', body.message);
                
                window.location.href = '/home';
            }
        } catch (error) {
            console.warn('Erro ao inicializar o diagnóstico semanal.', error);
            
            window.location.href = '/home';
        }
    }

    async function loadWeeksFromApi() {
        const user = AuthSession.getUser();

        const payload = {
            user_id: user ? user.id : null
        };

        try {
            const response = await AuthSession.apiRequest('/api/weekly-diagnostic/weeks', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const body = await response.json();

            if (!response.ok || !body.success) {
                console.warn('Não foi possível obter semanas do servidor.', body.message);
                return null;
            }

            const weeks = Array.isArray(body.data.weeks) ? body.data.weeks.map(w => Number(w)) : [];
            if (weeks.length === 0) {
                return null;
            }

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

            if (!weeks.includes(currentWeek)) {
                currentWeek = weeks[weeks.length - 1] || 1;
                setSelectedWeek(currentWeek);
            }

        } catch (err) {
            console.warn('Erro ao carregar as semanas disponíveis via API', err);
        }
    }

    async function loadActivitiesFromApi() {
        const user = AuthSession.getUser();

        const payload = {
            user_id: user ? user.id : null,
            week: currentWeek
        };

        try {
            const response = await AuthSession.apiRequest('/api/weekly-diagnostic/activities', {
                method: 'POST',
                body: JSON.stringify(payload),
            });
            const body = await response.json();

            if (!response.ok || !body.success) {
                console.warn('Não foi possível obter semanas do servidor.', body.message);
                return null;
            }

            updateDiagnostic(body.data.diagnostic);

        } catch (err) {
            console.warn('Erro ao carregar as atividades via API', err);
        }
    }

    weekSelect.addEventListener('change', async function () {
        const week = Number(this.value);
        setSelectedWeek(week);
        await loadActivitiesFromApi();

        currentStatuses = buildStatuses(week);
        renderDays();
        updateDayDescription();
    });

    btnCompleted.addEventListener('click', function () {
        setDayStatus(currentDay, true);
    });

    btnMissed.addEventListener('click', function () {
        setDayStatus(currentDay, false);
    });

    async function start() {
        await initializeWeeklyDiagnostic();
        
        await loadWeeksFromApi();
        await loadActivitiesFromApi();

        currentStatuses = buildStatuses(currentWeek);        
        renderDays();
        updateDayDescription();
    }

    start();
});