// Journey Page JavaScript

document.addEventListener('DOMContentLoaded', function () {
    if (!AuthSession.requirePage('journey')) {
        return;
    }

    setupAccountButton();
});

function setupAccountButton() {
    const btn = document.getElementById('btnConta');
    if (!btn) {
        return;
    }

    const user = AuthSession.getUser();
    btn.textContent = user?.email ?? 'Conta';
    btn.onclick = () => {
        if (confirm('Deseja sair?')) {
            AuthSession.clearSession();
            window.location.href = '/login';
        }
    };
}

// Variáveis do Timer
let timerInterval = null;
let timeRemaining = 0;
let initialTime = 0;
let isRunning = false;
let currentActivity = '';
let currentQuestData = null;
let currentQuestCard = null;

// Estatísticas
let pomodorosCompleted = 0;
let totalMinutes = 0;
let currentStreak = 0;

// Mapeamento de dificuldade
const difficultyMap = {
    1: '⭐ Fácil',
    2: '⭐⭐ Médio',
    3: '⭐⭐⭐ Difícil',
    4: '⭐⭐⭐⭐ Muito Difícil'
};

// Mapeamento de prioridade
const priorityMap = {
    'low': '🟢 Baixa',
    'medium': '🟡 Média',
    'high': '🔴 Alta'
};

// Função para abrir o modal do Pomodoro
function openPomodoroModal(questId) {
    const questCard = document.querySelector(`[data-quest-id="${questId}"]`);
    if (!questCard) {
        console.error('Quest card not found');
        return;
    }

    const remainingTimeString = questCard.dataset.questRemainingTime || '';
    const estimatedTimeString = questCard.dataset.questEstimatedTime || '00:00:00';
    const initialTimeString = remainingTimeString !== '' ? remainingTimeString : estimatedTimeString;

    currentQuestData = {
        id: questCard.dataset.questId,
        title: questCard.dataset.questTitle,
        xp: parseInt(questCard.dataset.questXp, 10),
        difficulty: parseInt(questCard.dataset.questDifficulty, 10),
        priority: questCard.dataset.questPriority,
        deadline: questCard.dataset.questDeadline,
        estimated_time: estimatedTimeString,
        remaining_time: remainingTimeString !== '' ? remainingTimeString : null,
        interruptions_count: parseInt(questCard.dataset.questInterruptions, 10) || 0,
        started_date: questCard.dataset.questStartedDate || null,
    };
    currentQuestCard = questCard;

    currentActivity = currentQuestData.title;
    initialTime = timeStringToSeconds(initialTimeString);
    timeRemaining = initialTime;
    
    document.getElementById('activityName').textContent = currentActivity;
    document.getElementById('questXP').textContent = currentQuestData.xp;
    document.getElementById('questDeadline').textContent = formatDeadline(currentQuestData.deadline);
    document.getElementById('questInterruptions').textContent = currentQuestData.interruptions_count;
    
    const priorityBadge = document.getElementById('priorityBadge');
    priorityBadge.textContent = priorityMap[currentQuestData.priority] || 'Normal';
    priorityBadge.className = `priority-badge ${currentQuestData.priority}`;
    
    const difficultyBadge = document.getElementById('difficultyBadge');
    difficultyBadge.textContent = difficultyMap[currentQuestData.difficulty] || '-';
    difficultyBadge.className = `difficulty-badge`;
    
    updateTimerDisplay();
    resetControls();
    
    const modal = document.getElementById('pomodoroModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Função para formatar a data de prazo
function formatDeadline(dateString) {
    if (!dateString) {
        return '-';
    }

    const date = new Date(dateString);
    if (Number.isNaN(date.getTime())) {
        return '-';
    }

    return date.toLocaleDateString('pt-BR');
}

function timeStringToSeconds(timeString) {
    const parts = timeString.split(':').map((value) => parseInt(value, 10) || 0);
    return (parts[0] || 0) * 3600 + (parts[1] || 0) * 60 + (parts[2] || 0);
}

function secondsToTimeString(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const secs = seconds % 60;

    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

async function persistStartedDate() {
    if (!currentQuestData || !currentQuestData.id || currentQuestData.started_date) {
        return;
    }

    const today = new Date().toISOString().slice(0, 10);

    try {
        const response = await AuthSession.apiRequest(`/api/quests/${currentQuestData.id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ started_date: today }),
        });

        const body = await response.json();

        if (!response.ok) {
            console.error(body.message || 'Falha ao gravar data de início.');
            return;
        }

        currentQuestData.started_date = today;
        if (currentQuestCard) {
            currentQuestCard.dataset.questStartedDate = today;
        }
    } catch (error) {
        console.error('Erro ao persistir started_date:', error);
    }
}

async function persistPauseState() {
    if (!currentQuestData || !currentQuestData.id) {
        return;
    }

    const newInterruptions = (currentQuestData.interruptions_count || 0) + 1;
    const remainingTime = secondsToTimeString(Math.max(0, timeRemaining));

    try {
        const response = await AuthSession.apiRequest(`/api/quests/${currentQuestData.id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                interruptions_count: newInterruptions,
                remaining_time: remainingTime,
            }),
        });

        const body = await response.json();

        if (!response.ok) {
            console.error(body.message || 'Falha ao atualizar pausa da quest.');
            return;
        }

        currentQuestData.interruptions_count = newInterruptions;
        currentQuestData.remaining_time = remainingTime;
        document.getElementById('questInterruptions').textContent = newInterruptions;

        if (currentQuestCard) {
            currentQuestCard.dataset.questInterruptions = String(newInterruptions);
            currentQuestCard.dataset.questRemainingTime = remainingTime;
        }
    } catch (error) {
        console.error('Erro ao persistir estado de pausa:', error);
    }
}

async function persistCompleteState() {
    if (!currentQuestData || !currentQuestData.id) {
        return;
    }

    if (!currentQuestData.started_date) {
        alert('Você precisa iniciar a missão antes de concluir.');
        return;
    }

    const remainingTime = secondsToTimeString(Math.max(0, timeRemaining));
    const today = new Date().toISOString().slice(0, 10);

    try {
        const response = await AuthSession.apiRequest(`/api/quests/${currentQuestData.id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                completed_date: today,
                remaining_time: remainingTime,
            }),
        });

        const body = await response.json();

        if (!response.ok) {
            console.error(body.message || 'Falha ao concluir a quest.');
            alert(body.message || 'Erro ao concluir a missão.');
            return;
        }

        currentQuestData.completed_date = today;
        currentQuestData.remaining_time = remainingTime;

        if (currentQuestCard) {
            currentQuestCard.dataset.questRemainingTime = remainingTime;
        }

        // Remover card da tela porque a missão foi concluída
        if (currentQuestCard) {
            const wrapper = currentQuestCard.closest('.col-12, .col-md-6, .col-lg-4');
            if (wrapper && wrapper.parentNode) {
                wrapper.parentNode.removeChild(wrapper);
            } else if (currentQuestCard.parentNode) {
                currentQuestCard.parentNode.removeChild(currentQuestCard);
            }
        }

        closePomodoroModal();
    } catch (error) {
        console.error('Erro ao persistir conclusão:', error);
    }
}

function completeQuest() {
    if (!currentQuestData) {
        return;
    }

    if (!currentQuestData.started_date) {
        alert('Inicie a missão antes de concluir.');
        return;
    }

    if (isRunning) {
        pauseTimer();
    }

    persistCompleteState();
}

// Função para fechar o modal do Pomodoro
async function closePomodoroModal() {
    if (isRunning) {
        await handlePauseClick();
    }
    
    const modal = document.getElementById('pomodoroModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    currentQuestData = null;
}

// Função para iniciar o timer
async function startTimer() {
    if (isRunning) return;

    if (currentQuestData && !currentQuestData.started_date) {
        await persistStartedDate();
    }
    
    isRunning = true;
    
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('pauseBtn').style.display = 'inline-block';
    
    timerInterval = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();
        
        if (timeRemaining <= 0) {
            finishPomodoro();
        }
    }, 1000);
}

// Função para pausar o timer
function pauseTimer() {
    if (!isRunning) return;
    
    isRunning = false;
    clearInterval(timerInterval);
    
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('pauseBtn').style.display = 'none';
}

async function handlePauseClick() {
    if (!isRunning) return;

    pauseTimer();
    await persistPauseState();
}

// Função para finalizar um Pomodoro
function finishPomodoro() {
    pauseTimer();
    
    // Atualizar estatísticas
    pomodorosCompleted++;
    totalMinutes += Math.floor(initialTime / 60);
    currentStreak++;
    
    updateStats();
    
    // Tocar som ou mostrar notificação
    alert('🎉 Pomodoro Completo! \n\nParabéns por completar: ' + currentActivity);
    
    // Resetar timer
    timeRemaining = initialTime;
    updateTimerDisplay();
}

// Função para atualizar o display do timer
function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    
    const display = 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
    
    document.getElementById('timerDisplay').textContent = display;
}

// Função para atualizar estatísticas
function updateStats() {
    document.getElementById('pomodorosCompleted').textContent = pomodorosCompleted;
    document.getElementById('totalMinutes').textContent = totalMinutes;
    document.getElementById('currentStreak').textContent = currentStreak;
}

// Função para resetar controles
function resetControls() {
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('pauseBtn').style.display = 'none';
}

// Fechar modal ao pressionar ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const pomodoroModal = document.getElementById('pomodoroModal');
        if (pomodoroModal.classList.contains('show')) {
            closePomodoroModal();
        }
    }
});

// Prevenir fechamento acidental durante timer ativo
window.addEventListener('beforeunload', function(event) {
    if (isRunning) {
        event.preventDefault();
        event.returnValue = 'Você tem um timer ativo. Tem certeza que deseja sair?';
        return event.returnValue;
    }
});