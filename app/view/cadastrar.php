<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com/cadastrar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light"
        rel="stylesheet">
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
                    <form class="form" id="cadastro-form" action="abc.php?action=cadastrar" method="POST">

                        <div class="input-group">
                            <label for="tipo_usuario">Eu sou um</label>
                            <select name="tipo_usuario" id="tipo_usuario" required>
                                <option value="" disabled <?= $tipoSelecionado === '' ? 'selected' : '' ?>>Selecione uma
                                    opção</option>
                                <option value="cliente" <?= $tipoSelecionado === 'cliente' ? 'selected' : '' ?>>Cliente
                                </option>
                                <option value="profissional" <?= $tipoSelecionado === 'profissional' ? 'selected' : '' ?>>
                                    Profissional</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="nome">Nome Completo</label>
                            <input name="nome" id="nome" type="text" required />
                        </div>

                        <div class="input-group">
                            <label for="email">Email</label>
                            <input name="email" id="email" type="email" required />
                        </div>

                        <div class="input-group">
                            <label for="senha">Senha</label>
                            <input name="senha" id="senha" type="password" required />
                        </div>

                        <div id="campos-profissional" style="display:none;">
                            <hr class="form-divider">
                            <div class="input-group">
                                <label for="cpf">CPF</label>
                                <input name="cpf" id="cpf" type="text" maxlength="14" placeholder="000.000.000-00" />
                                <small id="cpf-erro" style="color: red; display: none;">CPF inválido</small>
                            </div>
                        </div>

                        <button style="margin-top:2rem;" class="sign" type="submit">Cadastrar-se</button>
                    </form>
                    <p class="signup-link">Já tem uma conta? <a href="login.php">Faça login</a></p>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- ELEMENTOS DO DOM ---
            const form = document.getElementById('cadastro-form');
            const tipoUsuarioSelect = document.getElementById('tipo_usuario');
            const camposProfissional = document.getElementById('campos-profissional');
            const cpfInput = document.getElementById('cpf');
            const cpfErro = document.getElementById('cpf-erro');

            // --- LÓGICA DE VISIBILIDADE DO FORMULÁRIO ---
            function atualizarVisibilidadeCampos() {
                const ehProfissional = tipoUsuarioSelect.value === 'profissional';
                camposProfissional.style.display = ehProfissional ? 'block' : 'none';
                // Torna o campo CPF obrigatório apenas se estiver visível
                cpfInput.required = ehProfissional;
            }

            tipoUsuarioSelect.addEventListener('change', atualizarVisibilidadeCampos);
            atualizarVisibilidadeCampos(); // Executa na inicialização

            // --- VALIDAÇÃO DO FORMULÁRIO ANTES DO ENVIO ---
            form.addEventListener('submit', function (event) {
                const ehProfissional = tipoUsuarioSelect.value === 'profissional';
                 // Se for profissional, o CPF tiver algum valor e este for inválido, impede o envio.
                if (ehProfissional && cpfInput.value && !validarCPF(cpfInput.value)) {
                    cpfErro.style.display = 'block';
                    event.preventDefault(); // Impede o envio do formulário
                }
            });

            // --- MÁSCARA E VALIDAÇÃO DE CPF (CÓDIGO COMPLETO) ---
            function validarCPF(cpf) {
                cpf = cpf.replace(/\D/g, '');
                if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
                let soma = 0,
                    resto;
                for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(9, 10))) return false;
                soma = 0;
                for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(10, 11))) return false;
                return true;
            }

            cpfInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            });

            cpfInput.addEventListener('blur', function () {
                const ehProfissional = tipoUsuarioSelect.value === 'profissional';
                if (ehProfissional && cpfInput.value && !validarCPF(cpfInput.value)) {
                    cpfErro.style.display = 'block';
                } else {
                    cpfErro.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>