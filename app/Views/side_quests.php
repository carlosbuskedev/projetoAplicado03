<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRIO – Missão Secundária</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/home.css">
    <!--<link rel="stylesheet" href="/css/sidequests.css" />-->
    <script src="/js/dev-guard.js"></script>
</head>
<body>
    <div class="container py-5">
        <h1>Quest Secundária</h1>
        <?php if (!empty($message)) : ?>
            <div class="alert alert-warning"><?= esc($message) ?></div>
        <?php endif; ?>

        <?php if (empty($questions)) : ?>
            <p>Nenhuma pergunta disponível.</p>
            <a href="/home" class="btn btn-secondary">Voltar</a>
        <?php else: ?>
            <form id="sideQuestForm" novalidate>
                <?php foreach ($questions as $idx => $q): ?>
                    <div class="mb-4">
                        <p><strong>Q<?= $idx+1 ?>:</strong> <?= esc($q['description']) ?></p>
                        <?php foreach ($scales as $scale): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" 
                                       name="answer[<?= $q['id'] ?>]" 
                                       data-question-id="<?= $q['id'] ?>"
                                       id="q<?= $q['id'] ?>s<?= $scale['id'] ?>" 
                                       value="<?= $scale['id'] ?>" required>
                                <label class="form-check-label" for="q<?= $q['id'] ?>s<?= $scale['id'] ?>">
                                    <?= esc($scale['score']) ?> - <?= esc($scale['description']) ?> (<?= esc($scale['frequency']) ?>)
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Enviar Respostas</button>
                <a href="/home" class="btn btn-link">Cancelar</a>
            </form>

            <div id="sideQuestAlert" class="mt-3"></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/session.js"></script>
    <script src="/js/sidequests.js?1"></script>
</body>
</html>
