document.addEventListener('DOMContentLoaded', function () {

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
  const successBanner = document.getElementById('successBanner');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    // Validação do título (único campo obrigatório)
    if (titleInput.value.trim() === '') {
      titleInput.classList.add('is-invalid');
      titleError.classList.remove('d-none');
      titleInput.focus();
      return;
    }

    titleInput.classList.remove('is-invalid');
    titleError.classList.add('d-none');

    // Coleta os dados
    const mission = {
      title:            titleInput.value.trim(),
      description:      document.getElementById('description').value.trim(),
      category:         document.getElementById('category').value.trim(),
      estimatedHours:   parseInt(document.getElementById('hours').value) || 0,
      estimatedMinutes: parseInt(document.getElementById('minutes').value) || 0,
      difficulty:       document.querySelector('input[name="difficulty"]:checked')?.value || 'medio',
      priority:         document.querySelector('input[name="priority"]:checked')?.value || 'media',
      status:           document.querySelector('input[name="status"]:checked')?.value || 'a-fazer',
      responsible:      document.getElementById('responsible').value.trim(),
      deadline:         document.getElementById('deadline').value,
      score:            parseInt(document.getElementById('score').value) || 0,
      createdAt:        new Date().toISOString(),
    };

    // Salva no localStorage
    const missions = JSON.parse(localStorage.getItem('brioMissions') || '[]');
    missions.push(mission);
    localStorage.setItem('brioMissions', JSON.stringify(missions));

    // Feedback visual
    successBanner.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Reseta o formulário após 2 segundos
    setTimeout(function () {
      successBanner.classList.add('d-none');
      form.reset();

      // Restaura seleção padrão dos radios visuais
      document.querySelectorAll('.pixel-radio-item').forEach(function (el) {
        el.classList.remove('selected');
      });
      document.querySelector('.pixel-radio-item[data-value="medio"]').classList.add('selected');
      document.querySelector('.pixel-radio-item[data-value="media"]').classList.add('selected');
      document.querySelector('.pixel-radio-item[data-value="a-fazer"]').classList.add('selected');
    }, 2000);
  });

  // Remove classe de erro ao digitar
  titleInput.addEventListener('input', function () {
    if (this.value.trim() !== '') {
      this.classList.remove('is-invalid');
      titleError.classList.add('d-none');
    }
  });

});