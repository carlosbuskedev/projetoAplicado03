<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/users.css">
</head>

<body>
    <div class="pixel-menu-container">
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a class="btn pixel-btn pixel-btn-back" href="/home-admin">← Voltar</a>
                    </div>
                    <div class="col">
                        <h1 class="pixel-title mb-0 text-center">USUÁRIOS</h1>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn pixel-btn pixel-btn-login" id="btnConta">Conta</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="pixel-menu-box">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <h2 class="pixel-menu-title mb-0">Gerenciar usuários</h2>
                    <button type="button" class="btn pixel-btn pixel-btn-primary" id="btnNovoUsuario">
                        + Novo usuário
                    </button>
                </div>

                <div id="alertBox" class="alert d-none" role="alert" aria-live="polite"></div>

                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle pixel-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Perfil</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal formulário (fora do container para não vazar alertas para a tela principal) -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content pixel-modal">
                <div class="modal-header border-0">
                    <h5 class="modal-title pixel-modal-title" id="userModalTitle">Usuário</h5>
                    <button type="button" class="btn-close pixel-close-btn" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="formAlert" class="alert d-none mb-3" role="alert" aria-live="assertive"></div>
                    <form id="userForm" novalidate>
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label for="userName" class="form-label pixel-label">Nome</label>
                            <input type="text" class="form-control pixel-input" id="userName" autocomplete="name">
                            <span class="field-error d-none" id="userNameError" role="alert">Nome é obrigatório.</span>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label pixel-label">E-mail</label>
                            <input type="email" class="form-control pixel-input" id="userEmail" autocomplete="email">
                            <span class="field-error d-none" id="userEmailError" role="alert">E-mail inválido.</span>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label pixel-label">Senha</label>
                            <input type="password" class="form-control pixel-input" id="userPassword"
                                placeholder="Mínimo 6 caracteres" autocomplete="new-password">
                            <small class="text-muted" id="passwordHint"></small>
                            <span class="field-error d-none" id="userPasswordError" role="alert">A senha deve ter no mínimo 6 caracteres.</span>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label pixel-label">Perfil</label>
                            <select class="form-select pixel-input" id="userRole">
                                <option value="user">Usuário</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                        <button type="submit" class="btn pixel-btn pixel-btn-primary w-100">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/users.js"></script>
</body>

</html>
