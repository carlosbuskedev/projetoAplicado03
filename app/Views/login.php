<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/login.css">
</head>

<body>
    <div class="pixel-menu-container">
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3 text-center">
                <h1 class="pixel-title mb-0">BRIO</h1>
            </div>
        </div>

        <div class="container login-page-wrap">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="pixel-menu-box login-box">
                        <h2 class="pixel-menu-title text-center mb-2">Login</h2>
                        <p class="login-subtitle text-center mb-4">Entre para continuar sua jornada</p>

                        <div id="loginAlert" class="alert d-none pixel-alert" role="alert"></div>

                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label pixel-label">E-mail</label>
                                <input type="email" class="form-control pixel-input" id="email"
                                    placeholder="seuemail@exemplo.com" required autocomplete="email">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label pixel-label">Senha</label>
                                <input type="password" class="form-control pixel-input" id="password"
                                    placeholder="Digite sua senha" required autocomplete="current-password">
                            </div>
                            <button type="submit" class="btn pixel-btn pixel-btn-primary w-100" id="btnEntrar">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/login.js"></script>
</body>

</html>
