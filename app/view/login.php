<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com/login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light" rel="stylesheet">


    <link rel="stylesheet" href="../../public/css/input.css">
</head>

<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <div class="form-container">
        <p class="title">Login</p>
        <form class="form"action="abc.php?action=autenticar" method="post">
            <div class="input-group">
                <label for="nome">Nome</label>
                <input type="text" name="nome" id="nome" placeholder="">
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="">
                <div class="forgot">
                    <a rel="noopener noreferrer" href="">Recuperar Senha?</a>
                </div>
            </div>
            <button class="sign">Login</button>
        </form>
        <p class="signup">Voce ainda não tem conta?
            <a rel="noopener noreferrer" href="cadastrar.php" class="">Cadastrar-se</a>
        </p>
    </div>

    <footer class="footer-custom text-center p-4">
        <p class="mb-0">© 2025 luumina. Todos direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>