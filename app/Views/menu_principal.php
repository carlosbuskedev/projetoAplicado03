<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Menu Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/principal.css">
</head>
<body>
    <div class="pixel-menu-container">
        <!-- Header com botão de Login -->
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="pixel-title mb-0">BRIO</h1>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn pixel-btn pixel-btn-login" id="btnConta">
                            Conta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Principal -->
        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="pixel-menu-box">
                        <h2 class="pixel-menu-title text-center mb-5">Menu Principal</h2>
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <a  href="/quests"
                                        class="pixel-menu-item" 
                                        data-option="missions">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Missões</h3>
                                        <p class="pixel-menu-item-description">Crie suas missões</p>
                                    </div>
                                    <div class="pixel-arrow">▶</div>
                                </a>
                            </div>
                            <div class="col-12">
                                <a  href="/journey"
                                        class="pixel-menu-item" 
                                        data-option="journey">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Iniciar Jornada</h3>
                                        <p class="pixel-menu-item-description">Comece uma nova aventura e explore as missões disponíveis</p>
                                    </div>
                                    <div class="pixel-arrow">▶</div>
                                </a>
                            </div>
                            <div class="col-12">
                                <button class="pixel-menu-item" 
                                        data-option="progression">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Progressão</h3>
                                        <p class="pixel-menu-item-description">Veja seu progresso no jogo</p>
                                    </div>
                                    <div class="pixel-arrow">▶</div>
                                </button>
                            </div>
                            <div class="col-12" id="menuUsuarios" style="display: none;">
                                <a href="/usuarios" class="pixel-menu-item" data-option="users">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Usuários</h3>
                                        <p class="pixel-menu-item-description">Cadastre e gerencie os jogadores</p>
                                    </div>
                                    <div class="pixel-arrow">▶</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/menu_principal.js"></script>
</body>
</html>
