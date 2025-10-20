<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: abc.php?action=logar");
    exit;
}

$nome = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha - Área do Cliente</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../public/css/input.css" rel="stylesheet">
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>
    <main class="container my-5">
        <h2 class="mb-4">Alterar Senha</h2>

        <form method="POST" action="abc.php?action=alterarSenha" class="w-50">
            <div class="mb-3">
                <label class="form-label">Senha atual:</label>
                <input type="password" name="senha_atual" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nova senha:</label>
                <input type="password" name="nova_senha" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar nova senha:</label>
                <input type="password" name="confirma_senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Alterar</button>
            <a href="abc.php?action=areaCliente" class="btn btn-secondary ms-2">Voltar</a>
        </form>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>