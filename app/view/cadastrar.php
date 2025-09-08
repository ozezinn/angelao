<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com/cadastrar</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link id="color-theme" rel="stylesheet" href="../../public/css/input.css">
</head>

<body>
    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main>
        <section id="servicos">
            <div class="container">
                <div class="form-container">
                    <p class="title">Cadastrar</p>
                    <?php $tipoSelecionado = $_GET['tipo'] ?? ''; ?>
                    <form class="form" action="abc.php?action=cadastrar" method="POST">

                        <div class="input-group">
                            <label id="txt" for="tipo">Eu sou um &nbsp &nbsp</label><br><br>
                            <select name="tipo_usuario" id="tipo" required>
                                <option value="" disabled <?= $tipoSelecionado === '' ? 'selected' : '' ?>>Selecione uma opção</option>
                                <option value="cliente" <?= $tipoSelecionado === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                <option value="profissional" <?= $tipoSelecionado === 'profissional' ? 'selected' : '' ?>>Profissional</option>
                                <!--<option value="admin" <?= $tipoSelecionado === 'admin' ? 'selected' : '' ?>>administrador</option>-->
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="nome">Nome</label>
                            <input name="nome" type="text" required />
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input name="email" type="email" required />
                        </div>

                        <div class="input-group">
                            <label for="senha">Senha</label>
                            <input name="senha" type="password" required />
                        </div>

                        <div class="input-group" id="cpf-group" style="display:none;">
                            <label for="cpf">CPF</label>
                            <input name="cpf" id="cpf" type="text" maxlength="14" placeholder="000.000.000-00" />
                            <small id="cpf-erro" style="color: red; display: none;">CPF inválido</small>
                        </div>

                        <button style="margin-top:6%;" class="sign" type="submit">Cadastrar-se</button>
                    </form>

                    <div class="social-message">
                        <div class="line"></div>
                        <a class="message" href="luumina.php">luumina</a>
                        <div class="line"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const tipoSelect = document.getElementById('tipo');
        const cpfGroup = document.getElementById('cpf-group');
        const cpfInput = document.getElementById('cpf');
        const cpfErro = document.getElementById('cpf-erro');

        if (tipoSelect.value === 'profissional') {
            cpfGroup.style.display = 'block';
        }

        tipoSelect.addEventListener('change', function() {
            cpfGroup.style.display = this.value === 'profissional' ? 'block' : 'none';
        });

        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
            let soma = 0;
            for (let i = 0; i < 9; i++) soma += parseInt(cpf.charAt(i)) * (10 - i);
            let resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.charAt(9))) return false;
            soma = 0;
            for (let i = 0; i < 10; i++) soma += parseInt(cpf.charAt(i)) * (11 - i);
            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.charAt(10))) return false;
            return true;
        }

        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });

        cpfInput.addEventListener('blur', function() {
            if (cpfGroup.style.display === 'block' && !validarCPF(cpfInput.value)) {
                cpfErro.style.display = 'block';
            } else {
                cpfErro.style.display = 'none';
            }
        });
    </script>
</body>
</html>