function calculateExperience() {
  /* ── Cálculo automático de pontuação ─────────────── */
  const hoursInput = document.getElementById('hours');
  const minutesInput = document.getElementById('minutes');
  const experienceInput = document.getElementById('experience');
  
  const hours = parseInt(hoursInput?.value, 10) || 0;
  const minutes = parseInt(minutesInput?.value, 10) || 0;
  const difficultyValue = document.querySelector('input[name="difficulty"]:checked')?.value || 'facil';

  // Converter tempo para minutos
  const totalMinutes = hours * 60 + minutes;

  // Mapear dificuldade para valor numérico
  const difficultyMap = {
    'facil': 1,
    'medio': 2,
    'dificil': 3,
    'muito-dificil': 4
  };

  const difficultyLevel = difficultyMap[difficultyValue] || 1;

  // Calcular pontuação: tempo (minutos) * dificuldade
  const score = totalMinutes * difficultyLevel;

  // Atualizar o campo de experience
  if (experienceInput) {
    experienceInput.value = score;
  }
}

document.addEventListener('DOMContentLoaded', function () {
  if (!AuthSession.requirePage('quests')) {
    return;
  }

  /* ── Radio customizado ─────────────────────────────── */
  document.querySelectorAll('.pixel-radio-item').forEach(function (item) {
    item.addEventListener('click', function () {
      const group = this.dataset.group;

      // Remove seleção de todos os itens do mesmo grupo
      document.querySelectorAll('.pixel-radio-item[data-group="' + group + '"]')
        .forEach(function (el) { el.classList.remove('selected'); });

      // Marca este como selecionado e marca o radio interno
      this.classList.add('selected');
      this.querySelector('input[type="radio"]').checked = true;
    });
  });

  /* ── Submissão do formulário ───────────────────────── */
  const form          = document.getElementById('missionForm');
  const titleInput    = document.getElementById('title');
  const titleError    = document.getElementById('titleError');
  const deadlineInput = document.getElementById('deadline');
  const deadlineError = document.getElementById('deadlineError');
  
  form.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (titleInput.value.trim() === '') {
      titleInput.classList.add('is-invalid');
      titleError.classList.remove('d-none');
    }

    if (deadlineInput.value.trim() === '') {
      deadlineInput.classList.add('is-invalid');
      deadlineError.classList.remove('d-none');
    }

    if (titleInput.value.trim() === '' || deadlineInput.value.trim() === '') {
      return;
    }
    
    titleInput.classList.remove('is-invalid');
    titleError.classList.add('d-none');
    deadlineInput.classList.remove('is-invalid');
    deadlineError.classList.add('d-none');

    const user = AuthSession.getUser();

    const payload = {
      title:            titleInput.value.trim(),
      description:      document.getElementById('description').value.trim(),
      category:         document.getElementById('category').value.trim(),
      estimatedHours:   parseInt(document.getElementById('hours').value, 10) || 0,
      estimatedMinutes: parseInt(document.getElementById('minutes').value, 10) || 0,
      difficulty:       document.querySelector('input[name="difficulty"]:checked')?.value || 'medio',
      priority:         document.querySelector('input[name="priority"]:checked')?.value || 'media',
      status:           document.querySelector('input[name="status"]:checked')?.value || 'a-fazer',
      deadline:         document.getElementById('deadline').value,
      experience:       parseInt(document.getElementById('experience').value, 10) || 0,
      user_id:          user ? user.id : null
    };

    try {
      const response = await AuthSession.apiRequest('/api/quests', {
        method: 'POST',
        body: JSON.stringify(payload),
      });

      const body = await parseResponse(response);

      window.location.href = '/menu-principal';
    } catch (error) {
      alert(error.message);
    }
  });

  titleInput.addEventListener('input', function () {
    if (this.value.trim() !== '') {
      this.classList.remove('is-invalid');
      titleError.classList.add('d-none');
    }
  });

  deadlineInput.addEventListener('input', function () {
    if (this.value.trim() !== '') {
      this.classList.remove('is-invalid');
      deadlineError.classList.add('d-none');
    }
  });

  async function parseResponse(response) {
    const body = await response.json();

    if (!response.ok) {
      throw new Error(body.message || 'Erro na requisição.');
    }

    return body;
  }
});