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
  <link rel="stylesheet" href="/css/home.css">
  <link rel="stylesheet" href="/css/leveling.css" />
  <link rel="stylesheet" href="/css/journey.css" />
  <script src="/js/dev-guard.js"></script>
</head>
<body>

  <!-- HEADER -->
  <header class="pixel-header">
    <div class="container-fluid px-4 py-3">
      <div class="row align-items-center">
        <div class="col-auto">
          <a href="/home" class="pixel-btn pixel-btn-back">◀ Voltar</a>
        </div>
        <div class="col text-center">
          <h1 class="pixel-title mb-0">BRIO</h1>
        </div>
        <div class="col-auto">
          <button type="button" class="btn pixel-btn pixel-btn-login" id="btnConta">Conta</button>
        </div>
      </div>
    </div>
  </header>

  <!-- CONTEÚDO -->
  <main class="container py-4 pb-5">

    <!-- FICHA DO PERSONAGEM -->
    <div class="row g-4 mb-4">

      <!-- Coluna esquerda: Avatar + Nome + Nível + XP -->
      <div class="col-12 col-md-4">
        <div class="pixel-card rpg-character-panel h-100">
          <div class="rpg-section-title mb-3">
            <i class="bi bi-person-fill"></i> PERSONAGEM
          </div>

          <!-- Avatar pixelado -->
          <div class="text-center mb-3">
            <div class="pixel-avatar-wrapper" id="avatarWrapper">
              <canvas id="avatarCanvas" width="160" height="160"></canvas>
              <div class="pixel-avatar-placeholder" id="avatarPlaceholder">
                <i class="bi bi-person-square"></i>
                <span>CLIQUE PARA<br>ADICIONAR<br>FOTO</span>
              </div>
            </div>
            <input type="file" id="fotoInput" accept="image/*" class="d-none" />
            <button class="pixel-btn pixel-btn-sm mt-2"
                    onclick="document.getElementById('fotoInput').click()">
              <i class="bi bi-camera-fill"></i> Foto
            </button>
          </div>

          <!-- Nome -->
          <div class="mb-3">
            <label class="pixel-label" for="nomeUsuario">Aventureiro</label>
            <input type="text" id="nomeUsuario" class="pixel-input text-center"
                   placeholder="Seu nome..." maxlength="16" disabled />
          </div>

          <!-- Nível -->
          <div class="rpg-level-badge mb-3">
            <span class="rpg-level-label">NÍVEL</span>
            <span class="rpg-level-value" id="nivelDisplay">1</span>
            <span class="rpg-level-label" id="rankLabel">NOVATO</span>
          </div>

          <!-- Barra de XP -->
          <div class="mb-2">
            <div class="pixel-label d-flex justify-content-between">
              <span>XP</span>
              <span id="xpDisplay">0 / 100</span>
            </div>
            <div class="pixel-xp-track">
              <div class="pixel-xp-fill" id="xpBarFill" style="width:0%"></div>
            </div>
          </div>
          <div class="rpg-hint">XP TOTAL: <span id="xpTotal">0</span></div>
         
        </div>
      </div>

      <!-- Coluna direita: Atributos -->
      <div class="col-12 col-md-8">
        <div class="pixel-card h-100">
          <div class="rpg-section-title mb-4">
            <i class="bi bi-bar-chart-fill"></i> ATRIBUTOS
          </div>

          <!-- DISCIPLINA -->
          <div class="rpg-attr-block mb-4">
            <div class="rpg-attr-header">
              <i class="bi bi-shield-fill-check"></i> DISCIPLINA
            </div>

            <!-- CONCLUSÃO -->
            <div class="rpg-sub-header mt-3">★ CONCLUSÃO</div>
            <div class="row g-2 mt-1">
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Atividades<br>Iniciadas</span>
                  <input type="number" id="atividadesIniciadas"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Atividades<br>Concluídas</span>
                  <input type="number" id="atividadesConcluidas"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box rpg-stat-computed">
                  <span class="rpg-stat-label">Percentual de<br>Conclusão</span>
                  <span class="rpg-stat-value" id="percentualConclusao">0.00%</span>
                </div>
              </div>
            </div>

            <!-- PRAZO -->
            <div class="rpg-sub-header mt-3">★ PRAZO</div>
            <div class="row g-2 mt-1">
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Concluídas<br>no Prazo</span>
                  <input type="number" id="atividadesPrazo"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Atividades<br>Concluídas</span>
                  <input type="number" id="atividadesConcluidasMirror"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box rpg-stat-computed">
                  <span class="rpg-stat-label">Percentual<br>no Prazo</span>
                  <span class="rpg-stat-value" id="percentualPrazo">0.00%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- CONCENTRAÇÃO -->
          <div class="rpg-attr-block mb-4">
            <div class="rpg-attr-header">
              <i class="bi bi-bullseye"></i> CONCENTRAÇÃO
            </div>

            <!-- INTERRUPÇÕES -->
            <div class="rpg-sub-header rpg-sub-header-warn mt-3">
              ★ INTERRUPÇÕES
            </div>
            <div class="row g-2 mt-1">
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Interrupções<br/>totais</span>
                  <input type="number" id="interrupcoes"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Atividades<br>Iniciadas</span>
                  <input type="number" id="atividadesIniciadasMirror"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6 col-sm-4">
                <div class="rpg-stat-box rpg-stat-computed">
                  <span class="rpg-stat-label">Percentual de<br>Disciplina</span>
                  <span class="rpg-stat-value" id="percentualDisciplina">0.00%</span>
                </div>
              </div>
            </div>

                        <!-- FOCO -->
            <div class="rpg-sub-header mt-3">★ FOCO</div>
            <div class="row g-2 mt-1">
              <div class="col-12 col-sm-4 offset-sm-8">
                <div class="rpg-stat-box rpg-stat-computed">
                  <span class="rpg-stat-label">Percentual<br>de Foco</span>
                  <span class="rpg-stat-value" id="percentualFoco">0.00%</span>
                </div>
              </div>
            </div>
          </div>

          <!-- PRODUTIVIDADE -->
          <div class="rpg-attr-block">
            <div class="rpg-attr-header">
              <i class="bi bi-lightning-charge-fill"></i> PRODUTIVIDADE
            </div>
            <div class="row g-2 mt-2">
              <div class="col-6">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Tarefas concluídas<br>Hoje</span>
                  <input type="number" id="tarefasHoje"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
              <div class="col-6">
                <div class="rpg-stat-box">
                  <span class="rpg-stat-label">Tarefas concluídas<br>Semana</span>
                  <input type="number" id="tarefasSemana"
                        class="rpg-stat-input" min="0" value="0" disabled />
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- CONQUISTAS -->
    <div class="pixel-card mb-4">
      <div class="rpg-section-title mb-3">
        <i class="bi bi-trophy-fill"></i> CONQUISTAS
      </div>
      <div class="conquistas-track" id="conquistasTrack">
        <!-- gerado por JS -->
      </div>
    </div>

    <!-- HISTÓRICO -->
    <div class="pixel-card">
      <div class="rpg-section-title mb-3">
        <i class="bi bi-clock-history"></i> HISTÓRICO DE ATIVIDADES
      </div>
      <div id="historicoLista">
        <!-- gerado por JS -->
      </div>
    </div>

  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/js/session.js"></script>
  <script src="/js/leveling.js"></script>
</body>
</html>