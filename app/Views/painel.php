<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/painel.css">
</head>

<body>
    <div class="pixel-menu-container painel-container">
        <div class="pixel-header painel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="pixel-title mb-0 painel-title">BRIO ADMIN</h1>
                    </div>
                    <div class="col-auto d-flex align-items-center gap-2">
                        <span class="painel-user-badge" id="adminEmail"></span>
                        <button type="button" class="btn pixel-btn pixel-btn-login" id="btnLogout">Sair</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="pixel-menu-box painel-menu-box">
                        <h2 class="pixel-menu-title text-center mb-2">Painel Administrativo</h2>
                        <p class="painel-subtitle text-center mb-5">Gerencie o sistema BRIO</p>

                        <div class="row g-4">
                            <div class="col-12">
                                <a href="/usuarios" class="pixel-menu-item painel-menu-item">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Usuários</h3>
                                        <p class="pixel-menu-item-description">Cadastrar, editar e excluir usuários</p>
                                    </div>
                                    <div class="pixel-arrow">▶</div>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="/home" class="pixel-menu-item painel-menu-item">
                                    <div class="pixel-menu-item-content">
                                        <h3 class="pixel-menu-item-title">Ver menu do jogo</h3>
                                        <p class="pixel-menu-item-description">Acessar o menu principal do BRIO</p>
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
    <script src="/js/painel.js"></script>
</body>

</html>
