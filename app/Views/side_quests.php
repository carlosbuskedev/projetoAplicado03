<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO – Missão Secundária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <link rel="stylesheet" href="/css/sidequests.css">
    <script src="/js/dev-guard.js"></script>
</head>
<body>
    <div class="pixel-menu-container">
        <!-- Header -->
        <div class="pixel-header">
            <div class="container-fluid px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <a href="/home" class="pixel-btn pixel-btn-back">◀ Voltar</a>
                    </div>
                    <div class="col text-center">
                        <h1 class="pixel-title mb-0">Missão Secundária</h1>
                    </div>
                    <div class="col-auto d-none d-md-block" style="width: 120px;"></div>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">

                    <?php if (!empty($message)) : ?>
                        <div class="alert alert-warning"><?= esc($message) ?></div>
                    <?php endif; ?>

                    <?php if (empty($questions)) : ?>
                        <div class="pixel-card text-center">
                            <p class="pixel-empty-text">Nenhuma pergunta disponível no momento.</p>
                            <a href="/home" class="pixel-btn pixel-btn-back">◀ Voltar ao Menu</a>
                        </div>
                    <?php else: ?>
                        <p class="pixel-quest-intro text-center">
                            Responda com sinceridade. Isso vai revelar seu maior desvio de foco da semana e liberar a sua trilha de recuperação.
                        </p>

                        <form id="sideQuestForm" novalidate class="mt-4">
                            <?php foreach ($questions as $idx => $q): ?>
                                <div class="pixel-question-card">
                                    <span class="pixel-question-badge">Pergunta <?= $idx + 1 ?> / <?= count($questions) ?></span>
                                    <p class="pixel-question-text"><?= esc($q['description']) ?></p>

                                    <div class="pixel-scale-group">
                                        <?php foreach ($scales as $scale): ?>
                                            <div class="pixel-scale-choice">
                                                <input class="pixel-scale-input"
                                                       type="radio"
                                                       name="answer[<?= $q['id'] ?>]"
                                                       data-question-id="<?= $q['id'] ?>"
                                                       id="q<?= $q['id'] ?>s<?= $scale['id'] ?>"
                                                       value="<?= $scale['id'] ?>" required>
                                                <label class="pixel-scale-option" for="q<?= $q['id'] ?>s<?= $scale['id'] ?>">
                                                    <span class="pixel-scale-score"><?= esc($scale['score']) ?></span>
                                                    <span class="pixel-scale-text">
                                                        <?= esc($scale['description']) ?>
                                                        <em><?= esc($scale['frequency']) ?></em>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="d-flex flex-wrap justify-content-center gap-3 mt-5">
                                <button type="submit" class="pixel-btn pixel-btn-primary">Enviar Respostas</button>
                                <a href="/home" class="pixel-btn pixel-btn-cancel">Cancelar</a>
                            </div>
                        </form>

                        <div id="sideQuestAlert" class="mt-4"></div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/sidequests.js?1"></script>
</body>
</html>
