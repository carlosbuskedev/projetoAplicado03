document.addEventListener('DOMContentLoaded', function () {
    if (!AuthSession.requirePage('leveling')) {
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

/* =============================================
   BRIO – Progressão
   ============================================= */

const STORAGE_KEY   = 'brioProgresso';
const MISSIONS_KEY  = 'brioMissions';
const PROFILE_KEY   = 'brioPerfil';

const RANKS = [
  { nivel: 1,  label: 'NOVATO'      },
  { nivel: 5,  label: 'APRENDIZ'    },
  { nivel: 10, label: 'AVENTUREIRO' },
  { nivel: 20, label: 'VETERANO'    },
  { nivel: 30, label: 'MESTRE'      },
  { nivel: 50, label: 'LENDA'       },
];

const CONQUISTAS = [
  {
    id: 'primeiro_passo',
    icon: 'bi-star-fill',
    title: 'Primeiro Passo',
    desc: 'Conclua sua primeira atividade',
    condicao: (s) => s.atividadesConcluidas >= 1,
  },
  {
    id: 'sem_atrasos',
    icon: 'bi-clock-fill',
    title: 'Sem Atrasos',
    desc: '10 atividades dentro do prazo',
    condicao: (s) => s.atividadesPrazo >= 10,
  },
  {
    id: 'constante',
    icon: 'bi-calendar-check-fill',
    title: 'Constante como Mestre',
    desc: 'Atividades por 7 dias seguidos',
    condicao: (s) => s.diasSeguidos >= 7,
  },
  {
    id: 'semana_prod',
    icon: 'bi-lightning-charge-fill',
    title: 'Semana Produtiva',
    desc: '10 ou mais tarefas na semana',
    condicao: (s) => s.tarefasSemana >= 10,
  },
  {
    id: 'foco_total',
    icon: 'bi-bullseye',
    title: 'Foco Total',
    desc: 'Percentual de foco acima de 90%',
    condicao: (s) => calcPercentualFoco(s) >= 90,
  },
  {
    id: 'disciplinado',
    icon: 'bi-shield-fill-check',
    title: 'Disciplinado',
    desc: 'Percentual de disciplina acima de 80%',
    condicao: (s) => calcPercentualDisciplina(s) >= 80,
  },
  {
    id: 'conclusao_total',
    icon: 'bi-trophy-fill',
    title: 'Conclusão Perfeita',
    desc: '100% de conclusão no prazo',
    condicao: (s) => calcPercentualPrazo(s) >= 100 && s.atividadesConcluidas >= 5,
  },
  {
    id: 'veterano',
    icon: 'bi-award-fill',
    title: 'Veterano',
    desc: '50 atividades concluídas',
    condicao: (s) => s.atividadesConcluidas >= 50,
  },
];


/* ── Utilidades ──────────────────────────────── */

function calcPercentualConclusao(s) {
  if (!s.atividadesIniciadas) return 0;
  return Math.min((s.atividadesConcluidas / s.atividadesIniciadas) * 100, 100);
}

function calcPercentualPrazo(s) {
  if (!s.atividadesConcluidas) return 0;
  return Math.min((s.atividadesPrazo / s.atividadesConcluidas) * 100, 100);
}

function calcPercentualFoco(s) {
  const total = s.atividadesConcluidas + s.interrupcoes;
  if (!total) return 0;
  return Math.min((s.atividadesConcluidas / total) * 100, 100);
}

function calcPercentualDisciplina(s) {
  if (!s.atividadesIniciadas) return 0;
  return Math.max(((s.atividadesIniciadas - s.interrupcoes) / s.atividadesIniciadas) * 100, 0);
}

function calcNivel(xp) {
  return Math.floor(xp / 100) + 1;
}

function calcRank(nivel) {
  let rank = RANKS[0].label;
  for (const r of RANKS) {
    if (nivel >= r.nivel) rank = r.label;
  }
  return rank;
}

function calcXPMissoes() {
  try {
    const missions = JSON.parse(localStorage.getItem(MISSIONS_KEY) || '[]');
    return missions
      .filter(m => m.status === 'feito')
      .reduce((acc, m) => acc + (parseInt(m.score) || 0), 0);
  } catch { return 0; }
}

/* ── Carregar / salvar estado ────────────────── */

function loadStats() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
  } catch { return {}; }
}

function saveStats(s) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(s));
}

function loadPerfil() {
  try {
    return JSON.parse(localStorage.getItem(PROFILE_KEY) || '{}');
  } catch { return {}; }
}

function savePerfil(p) {
  localStorage.setItem(PROFILE_KEY, JSON.stringify(p));
}

/* ── Ler campos do formulário ────────────────── */

function getField(id) {
  const el = document.getElementById(id);
  return el ? (parseInt(el.value) || 0) : 0;
}

function readFormStats() {
  return {
    atividadesIniciadas:  getField('atividadesIniciadas'),
    atividadesConcluidas: getField('atividadesConcluidas'),
    atividadesPrazo:      getField('atividadesPrazo'),
    interrupcoes:         getField('interrupcoes'),
    tarefasHoje:          getField('tarefasHoje'),
    tarefasSemana:        getField('tarefasSemana'),
    diasSeguidos:         loadStats().diasSeguidos || 0,
  };
}

/* ── Preencher campos ────────────────────────── */

function populateFields(s) {
  const ids = [
    'atividadesIniciadas', 'atividadesConcluidas',
    'atividadesPrazo', 'interrupcoes',
    'tarefasHoje', 'tarefasSemana',
  ];
  ids.forEach(function (id) {
    const el = document.getElementById(id);
    if (el && s[id] !== undefined) el.value = s[id];
  });
}

/* ── Atualizar estatísticas calculadas ───────── */

function updateComputedStats(s) {
  // Percentuais calculados
  document.getElementById('percentualConclusao').textContent =
    calcPercentualConclusao(s).toFixed(2) + '%';

  document.getElementById('percentualPrazo').textContent =
    calcPercentualPrazo(s).toFixed(2) + '%';

  document.getElementById('percentualFoco').textContent =
    calcPercentualFoco(s).toFixed(2) + '%';

  document.getElementById('percentualDisciplina').textContent =
    calcPercentualDisciplina(s).toFixed(2) + '%';

  // Campos espelho
  document.getElementById('atividadesConcluidasMirror').textContent =
    s.atividadesConcluidas || 0;

  document.getElementById('atividadesIniciadasMirror').textContent =
    s.atividadesIniciadas || 0;

  // XP e nível
  const xpMissoes = calcXPMissoes();
  const xpTotal   = xpMissoes + (s.atividadesConcluidas * 5);
  const nivel     = calcNivel(xpTotal);
  const xpAtual   = xpTotal % 100;

  document.getElementById('xpTotal').textContent    = xpTotal;
  document.getElementById('nivelDisplay').textContent = nivel;
  document.getElementById('rankLabel').textContent   = calcRank(nivel);
  document.getElementById('xpDisplay').textContent  = xpAtual + ' / 100';
  document.getElementById('xpBarFill').style.width  = xpAtual + '%';
}

/* ── Renderizar conquistas ───────────────────── */

function renderConquistas(s) {
  const track = document.getElementById('conquistasTrack');
  track.innerHTML = '';

  CONQUISTAS.forEach(c => {
    const desbloqueada = c.condicao(s);
    const card = document.createElement('div');
    card.className = 'conquista-card ' + (desbloqueada ? 'desbloqueada' : 'bloqueada');
    card.innerHTML = `
      <i class="bi ${c.icon} conquista-icon"></i>
      <div class="conquista-title">${c.title}</div>
      <div class="conquista-desc">${c.desc}</div>
      <div class="conquista-badge">${desbloqueada ? '✓ OBTIDA' : '🔒 BLOQUEADA'}</div>
    `;
    track.appendChild(card);
  });
}

/* ── Renderizar histórico ────────────────────── */

function renderHistorico() {
  const lista = document.getElementById('historicoLista');

  let missions = [];
  try {
    missions = JSON.parse(localStorage.getItem(MISSIONS_KEY) || '[]');
  } catch { missions = []; }

  const concluidas = missions
    .filter(m => m.status === 'feito')
    .slice(-10)
    .reverse();

  if (concluidas.length === 0) {
    lista.innerHTML = `
      <div class="historico-vazio">
        Nenhuma atividade concluída ainda.<br><br>Complete missões para<br>ver seu histórico aqui!
      </div>`;
    return;
  }

  lista.innerHTML = concluidas.map((m, i) => {
    const prazo    = m.deadline ? m.deadline.split('-').reverse().join('/') : '—';
    const cat      = m.category || '—';
    const dif      = { facil: 'Fácil', medio: 'Média', dificil: 'Difícil', 'muito-dificil': 'Muito Difícil' }[m.difficulty] || '—';
    const score    = m.score ? m.score + ' XP' : '0 XP';
    return `
      <div class="historico-item">
        <div class="historico-num">#${String(i + 1).padStart(2, '0')}</div>
        <div class="historico-info">
          <div class="historico-titulo">${m.title || 'Sem título'}</div>
          <div class="historico-meta">
            <span><i class="bi bi-tag-fill"></i> ${cat}</span>
            <span><i class="bi bi-calendar3"></i> ${prazo}</span>
            <span><i class="bi bi-bar-chart-fill"></i> ${dif}</span>
          </div>
        </div>
        <div class="historico-xp">+${score}</div>
      </div>`;
  }).join('');
}

/* ── Foto pixelada ───────────────────────────── */

function aplicarFotoPixelada(file, callback) {
  const reader = new FileReader();
  reader.onload = function (e) {
    const img = new Image();
    img.onload = function () {
      const canvas      = document.getElementById('avatarCanvas');
      const ctx         = canvas.getContext('2d');
      const tamanhoFinal      = 160;
      const tamanhoPixelado   = 32;

      const mini = document.createElement('canvas');
      mini.width  = tamanhoPixelado;
      mini.height = tamanhoPixelado;
      const ctxMini = mini.getContext('2d');

      // Recorte quadrado centralizado
      const menor = Math.min(img.width, img.height);
      const ox    = (img.width  - menor) / 2;
      const oy    = (img.height - menor) / 2;
      ctxMini.drawImage(img, ox, oy, menor, menor, 0, 0, tamanhoPixelado, tamanhoPixelado);

      ctx.imageSmoothingEnabled = false;
      ctx.clearRect(0, 0, tamanhoFinal, tamanhoFinal);
      ctx.drawImage(mini, 0, 0, tamanhoPixelado, tamanhoPixelado, 0, 0, tamanhoFinal, tamanhoFinal);

      document.getElementById('avatarPlaceholder').classList.add('hidden');
      if (callback) callback(canvas.toDataURL());
    };
    img.src = e.target.result;
  };
  reader.readAsDataURL(file);
}

/* ── Restaurar foto salva ────────────────────── */

function restaurarFoto(dataUrl) {
  if (!dataUrl) return;
  const img = new Image();
  img.onload = function () {
    const canvas = document.getElementById('avatarCanvas');
    const ctx    = canvas.getContext('2d');
    ctx.imageSmoothingEnabled = false;
    ctx.clearRect(0, 0, 160, 160);
    ctx.drawImage(img, 0, 0, 160, 160);
    document.getElementById('avatarPlaceholder').classList.add('hidden');
  };
  img.src = dataUrl;
}

/* ── Atualização em tempo real ───────────────── */

function onInputChange() {
  const s = readFormStats();
  updateComputedStats(s);
  renderConquistas(s);
}

/* ── Inicialização ───────────────────────────── */

document.addEventListener('DOMContentLoaded', function () {

  // Carregar dados salvos
  const stats  = loadStats();
  const perfil = loadPerfil();

  populateFields(stats);
  updateComputedStats(stats);
  renderConquistas(stats);
  renderHistorico();

  // Nome
  if (perfil.nome) {
    document.getElementById('nomeUsuario').value = perfil.nome;
  }

  // Foto
  if (perfil.foto) {
    restaurarFoto(perfil.foto);
  }

  // Input de foto
  document.getElementById('fotoInput').addEventListener('change', function () {
    if (!this.files[0]) return;
    aplicarFotoPixelada(this.files[0], function (dataUrl) {
      const perfil = loadPerfil();
      perfil.foto  = dataUrl;
      savePerfil(perfil);
    });
  });

  // Clique no avatar também abre o seletor de arquivo
  document.getElementById('avatarWrapper').addEventListener('click', function () {
    document.getElementById('fotoInput').click();
  });

  // Atualização em tempo real ao mudar campos (auto-save)
  [
    'atividadesIniciadas', 'atividadesConcluidas',
    'atividadesPrazo', 'interrupcoes',
    'tarefasHoje', 'tarefasSemana',
  ].forEach(function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', function () {
      onInputChange();
      saveStats(readFormStats());
    });
  });

  // Salvar progresso
  document.getElementById('btnSalvar').addEventListener('click', function () {
    const s = readFormStats();
    saveStats(s);

    const nome   = document.getElementById('nomeUsuario').value.trim();
    const perfil = loadPerfil();
    perfil.nome  = nome;
    savePerfil(perfil);

    const msg = document.getElementById('savedMsg');
    msg.classList.remove('d-none');
    setTimeout(function () { msg.classList.add('d-none'); }, 2000);

    renderConquistas(s);
    renderHistorico();
  });

});

 // Drag-to-scroll nas conquistas
  (function () {
    const track  = document.getElementById('conquistasTrack');
    let arrastando = false;
    let startX     = 0;
    let scrollLeft = 0;

    track.addEventListener('mousedown', function (e) {
      arrastando     = true;
      startX         = e.pageX - track.offsetLeft;
      scrollLeft     = track.scrollLeft;
      track.classList.add('arrastando');
    });

    track.addEventListener('mouseleave', function () {
      arrastando = false;
      track.classList.remove('arrastando');
    });

    track.addEventListener('mouseup', function () {
      arrastando = false;
      track.classList.remove('arrastando');
    });

    track.addEventListener('mousemove', function (e) {
      if (!arrastando) return;
      e.preventDefault();
      const x    = e.pageX - track.offsetLeft;
      const walk = (x - startX) * 1.5; // multiplicador de velocidade
      track.scrollLeft = scrollLeft - walk;
    });
  })();