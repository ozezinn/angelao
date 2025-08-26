<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com/cadastrarProfissional</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light"
        rel="stylesheet">


    <link id="color-theme" rel="stylesheet" href="../../public/css/input.css">
</head>

<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="page-content">
        <section id="servicos">
            <div class="container">
                <div class="form-container">
                    <p class="title">Cadastrar Profissional</p>
                    <form class="form" action="../controller/cadastroController.php" method="POST">
                        <div class="input-group">
                            <label for="nome">Nome</label>
                            <input placeholder="" name="nome" type="text" />
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input placeholder="" name="email" type="email" />
                        </div>

                        <div class="input-group">
                            <label for="senha">senha</label>
                            <input placeholder="" name="senha" type="text" />
                        </div>
                        <div class="input-group">
                            <label for="cpf">CPF</label>
                            <input placeholder="" name="cpf" type="text" />
                        </div>
                        <button style="margin-top:6%;" class="sign" type="submit">cadastrar-se</button>
                    </form>
                    <div class="social-message">
                        <div class="line"></div>
                        <a class="message" href="luumina.html">luumina</a>
                        <div class="line"></div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <footer class="footer-custom text-center p-4">
        <p class="mb-0">© 2025 luumina. todos direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>