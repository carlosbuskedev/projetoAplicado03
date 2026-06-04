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
    // Buscar o elemento com os dados da quest
    const questCard = document.querySelector(`[data-quest-id="${questId}"]`);
    if (!questCard) {
        console.error('Quest card not found');
        return;
    }

    // Extrair dados do card
    currentQuestData = {
        id: questCard.dataset.questId,
        title: questCard.dataset.questTitle,
        time: parseInt(questCard.dataset.questTime, 10),
        xp: parseInt(questCard.dataset.questXp, 10),
        difficulty: parseInt(questCard.dataset.questDifficulty, 10),
        priority: questCard.dataset.questPriority,
        deadline: questCard.dataset.questDeadline
    };

    currentActivity = currentQuestData.title;
    initialTime = currentQuestData.time * 60; // Converter para segundos
    timeRemaining = initialTime;
    
    // Atualizar o título
    document.getElementById('activityName').textContent = currentActivity;
    
    // Atualizar informações da quest
    document.getElementById('questXP').textContent = currentQuestData.xp;
    document.getElementById('questDeadline').textContent = formatDeadline(currentQuestData.deadline);
    document.getElementById('questInterruptions').textContent = '0'; // Será carregado do banco depois
    
    // Atualizar prioridade
    const priorityBadge = document.getElementById('priorityBadge');
    priorityBadge.textContent = priorityMap[currentQuestData.priority] || 'Normal';
    priorityBadge.className = `priority-badge ${currentQuestData.priority}`;
    
    // Atualizar dificuldade
    const difficultyBadge = document.getElementById('difficultyBadge');
    difficultyBadge.textContent = difficultyMap[currentQuestData.difficulty] || '-';
    difficultyBadge.className = `difficulty-badge`;
    
    // Atualizar o display do timer
    updateTimerDisplay();
    
    // Resetar controles
    resetControls();
    
    // Abrir modal
    const modal = document.getElementById('pomodoroModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Função para formatar a data de prazo
function formatDeadline(dateString) {
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    } catch (e) {
        return dateString;
    }
}

// Função para fechar o modal do Pomodoro
function closePomodoroModal() {
    // Pausar o timer se estiver rodando
    if (isRunning) {
        pauseTimer();
    }
    
    // Fechar modal
    const modal = document.getElementById('pomodoroModal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    // Limpar dados da quest
    currentQuestData = null;
}

// Função para iniciar o timer
function startTimer() {
    if (isRunning) return;
    
    isRunning = true;
    
    // Atualizar botões
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('pauseBtn').style.display = 'inline-block';
    
    // Iniciar contagem
    timerInterval = setInterval(() => {
        timeRemaining--;
        updateTimerDisplay();
        
        // Verificar se acabou
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
    
    // Atualizar botões
    document.getElementById('startBtn').style.display = 'inline-block';
    document.getElementById('pauseBtn').style.display = 'none';
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