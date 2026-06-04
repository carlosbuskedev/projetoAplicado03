<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BRIO – Criar Missão</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/css/principal.css">
  <link rel="stylesheet" href="/css/quests.css" />
</head>
<body>

  <!-- HEADER -->
  <header class="pixel-header">
    <div class="container-fluid px-4 py-3">
      <div class="row align-items-center">
        <div class="col-auto">
          <a href="/menu" class="pixel-btn pixel-btn-back">◀ Voltar</a>
        </div>
        <div class="col text-center">
          <h1 class="pixel-title mb-0">BRIO</h1>
        </div>
        <div class="col-auto" style="visibility:hidden;">
          <span class="pixel-btn pixel-btn-back">◀ Voltar</span>
        </div>
      </div>
    </div>
  </header>

  <!-- CONTEÚDO -->
  <main class="container py-4 pb-5">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-9">

        <div class="pixel-form-box">
          <h2 class="pixel-form-title text-center mb-4">Criar Missão</h2>

          <!-- Banner de sucesso (oculto por padrão) -->
          <div id="successBanner" class="pixel-success-banner mb-4 d-none">
            ✓ Missão cadastrada com sucesso!
          </div>

          <form id="missionForm" novalidate>

            <!-- Título -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label" for="title">Título</label>
              <input type="text" id="title" name="title"
                     class="pixel-input" placeholder="Ex: Estudar React" required />
              <span class="pixel-error d-none" id="titleError">Campo obrigatório</span>
            </div>

            <!-- Descrição -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label" for="description">Descrição curta</label>
              <textarea id="description" name="description"
                        class="pixel-input pixel-textarea" rows="3"
                        placeholder="Ex: Aprender componentes e hooks"></textarea>
            </div>

            <!-- Categoria -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label" for="category">Categoria</label>
              <input type="text" id="category" name="category"
                     class="pixel-input" placeholder="Ex: Estudo" />
            </div>

            <!-- Tempo estimado -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label">Tempo estimado</label>
              <div class="pixel-time-row">
                <div class="pixel-time-field">
                  <input type="number" id="hours" name="hours"
                         class="pixel-input pixel-input-number"
                         min="0" max="99" value="0" />
                  <span class="pixel-time-label">h</span>
                </div>
                <span class="pixel-time-sep">:</span>
                <div class="pixel-time-field">
                  <input type="number" id="minutes" name="minutes"
                         class="pixel-input pixel-input-number"
                         min="0" max="59" value="25" />
                  <span class="pixel-time-label">min</span>
                </div>
              </div>
            </div>

            <!-- Dificuldade -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label">Dificuldade</label>
              <div class="pixel-radio-group" id="difficultyGroup">
                <label class="pixel-radio-item" data-group="difficulty" data-value="facil">
                  <input type="radio" name="difficulty" value="facil" />
                  <i class="bi bi-star-fill pixel-star-icon"></i> Fácil
                </label>
                <label class="pixel-radio-item selected" data-group="difficulty" data-value="medio">
                  <input type="radio" name="difficulty" value="medio" checked />
                  <i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i> Média
                </label>
                <label class="pixel-radio-item" data-group="difficulty" data-value="dificil">
                  <input type="radio" name="difficulty" value="dificil" />
                  <i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i> Difícil
                </label>
                <label class="pixel-radio-item" data-group="difficulty" data-value="muito-dificil">
                  <input type="radio" name="difficulty" value="muito-dificil" />
                  <i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i><i class="bi bi-star-fill pixel-star-icon"></i> Muito Difícil
                </label>
              </div>
            </div>

            <!-- Prioridade -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label">Prioridade</label>
              <div class="pixel-radio-group" id="priorityGroup">
                <label class="pixel-radio-item" data-group="priority" data-value="baixa">
                  <input type="radio" name="priority" value="baixa" />
                  🟢 Baixa
                </label>
                <label class="pixel-radio-item selected" data-group="priority" data-value="media">
                  <input type="radio" name="priority" value="media" checked />
                  🟡 Média
                </label>
                <label class="pixel-radio-item" data-group="priority" data-value="alta">
                  <input type="radio" name="priority" value="alta" />
                  🔴 Alta
                </label>
              </div>
            </div>

            <!-- Status -->
            <div class="form-group-pixel mb-4">
              <label class="pixel-label">Status</label>
              <div class="pixel-radio-group" id="statusGroup">
                <label class="pixel-radio-item selected" data-group="status" data-value="a-fazer">
                  <input type="radio" name="status" value="a-fazer" checked />
                  <i class="bi bi-hourglass-top"></i> A fazer
                </label>
                <label class="pixel-radio-item" data-group="status" data-value="fazendo">
                  <input type="radio" name="status" value="fazendo" />
                  <i class="bi bi-hourglass-split"></i> Fazendo
                </label>
                <label class="pixel-radio-item" data-group="status" data-value="feito">
                  <input type="radio" name="status" value="feito" />
                  <i class="bi bi-hourglass-bottom"></i> Feito
                </label>
              </div>
            </div>

            <!-- Responsável + Prazo -->
            <div class="row g-3 mb-4">
              <div class="col-12 col-md-6">
                <div class="form-group-pixel">
                  <label class="pixel-label" for="deadline">Prazo</label>
                  <input type="date" id="deadline" name="deadline"
                         class="pixel-input" required />
                  <span class="pixel-error d-none" id="deadlineError">Campo obrigatório</span>
                </div>
              </div>
              <div class="col-12 col-md-6 d-flex justify-content-md-end">
                <div class="form-group-pixel">
                  <label class="pixel-label" for="experience">Pontuação</label>
                  <div class="pixel-experience-row">
                    <input type="number" id="experience" name="experience"
                          class="pixel-input pixel-input-experience"
                          disabled
                          min="0" max="9999" value="50" />
                    <span class="pixel-xp-badge">XP</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botões -->
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <a href="/menu" class="pixel-btn pixel-btn-cancel w-100 d-block text-center">
                  ✕ Cancelar
                </a>
              </div>
              <div class="col-12 col-md-6">
                <button type="submit" class="pixel-btn pixel-btn-save w-100">
                  ✚ Salvar Missão
                </button>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/session.js"></script>
  <script src="/js/quests.js"></script>
</body>
</html>