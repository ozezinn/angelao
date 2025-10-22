<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - luumina.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/input.css">
</head>
<body>
    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="form-container">
        <p class="title">Recuperar Senha</p>
        <form class="form" action="abc.php?action=handleRecuperarSenha" method="post">
            <p class="text-center text-muted mb-3" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">
                Digite seu email e enviaremos um link para você redefinir sua senha.
            </p>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>
            <button class="sign mt-3">Enviar Link de Recuperação</button>
        </form>
        <p class="signup">Lembrou a senha?
            <a rel="noopener noreferrer" href="login.php" class="">Faça login</a>
        </p>
    </div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>