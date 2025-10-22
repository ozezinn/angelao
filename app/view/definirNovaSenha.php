<?php
// O controller (showDefinirNovaSenha) deve definir essa variável
if (!isset($token)) {
    die("Token inválido ou ausente.");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Definir Nova Senha - luumina.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/input.css">
</head>
<body>
    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="form-container">
        <p class="title">Definir Nova Senha</p>
        <form class="form" action="abc.php?action=handleDefinirNovaSenha" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            
            <div class="input-group">
                <label for="nova_senha">Nova Senha</label>
                <input type="password" name="nova_senha" id="nova_senha" required>
            </div>
            <div class="input-group">
                <label for="confirma_senha">Confirmar Nova Senha</label>
                <input type="password" name="confirma_senha" id="confirma_senha" required>
            </div>
            <button class="sign mt-3">Salvar Nova Senha</button>
        </form>
    </div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>