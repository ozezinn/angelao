[File: ozezinn/angelao/angelao-0ce7c72fda66249d4d8f62d0788b945d586dce88/app/view/cadastrar.php]
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
    <div id="alert-placeholder" class="container"
        style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
    </div>
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

                            <div id="senha-requisitos"
                                style="font-size: 0.85rem; margin-top: 10px; color: var(--cor-cinza-escuro);">
                                <strong style="font-family: 'Poppins', sans-serif;">A senha deve conter:</strong>
                                <ul
                                    style="padding-left: 20px; margin-bottom: 0; margin-top: 5px; list-style-type: '» ';">
                                    <li id="req-length">Pelo menos 8 caracteres</li>
                                    <li id="req-lowercase">Pelo menos 1 letra minúscula (a-z)</li>
                                    <li id="req-uppercase">Pelo menos 1 letra maiúscula (A-Z)</li>
                                    <li id="req-number">Pelo menos 1 número (0-9)</li>
                                </ul>
                            </div>
                        </div>

                        <div id="campos-profissional" style="display:none;">
                            <hr class="form-divider">
                            <div class="input-group">
                                <label for="cpf">CPF</label>
                                <input name="cpf" id="cpf" type="text" maxlength="14" placeholder="000.000.000-00" />
                                <small id="cpf-erro" style="color: red; display: none;">CPF inválido</small>
                            </div>
                        </div>

                        <div class="input-group" style="margin-top: 1.5rem; display: flex; align-items: center; flex-direction: row; gap: 10px;">
                            <input type="checkbox" name="termos" id="termos" style="width: auto; height: auto;">
                            <label for="termos" style="margin-bottom: 0; font-size: 0.85rem; font-weight: normal; line-height: 1.4;">
                                Eu li e concordo com os 
                                <a href="termos.php" target="_blank" style="color: var(--cor-preto); font-weight: 600;">Termos de Uso</a> e 
                                <a href="politica.php" target="_blank" style="color: var(--cor-preto); font-weight: 600;">Política de Privacidade</a>.
                            </label>
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
            const termosCheckbox = document.getElementById('termos'); // <-- NOVO CHECKBOX

            // --- NOVOS ELEMENTOS PARA SENHA ---
            const senhaInput = document.getElementById('senha');
            // (Certifique-se de ter adicionado o HTML para 'senha-requisitos' no seu formulário)
            const reqContainer = document.getElementById('senha-requisitos');
            const reqLength = document.getElementById('req-length');
            const reqLowercase = document.getElementById('req-lowercase');
            const reqUppercase = document.getElementById('req-uppercase');
            const reqNumber = document.getElementById('req-number');

            // --- LÓGICA DE VISIBILIDADE DO FORMULÁRIO (existente) ---
            function atualizarVisibilidadeCampos() {
                const ehProfissional = tipoUsuarioSelect.value === 'profissional';
                camposProfissional.style.display = ehProfissional ? 'block' : 'none';
                // Torna o campo CPF obrigatório apenas se estiver visível
                cpfInput.required = ehProfissional;
            }

            tipoUsuarioSelect.addEventListener('change', atualizarVisibilidadeCampos);
            atualizarVisibilidadeCampos(); // Executa na inicialização

            // --- NOVA FUNÇÃO DE ALERTA ---
            const showAlert = (message, type = 'danger') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;
                alertPlaceholder.innerHTML = ''; // Limpa alertas anteriores

                const wrapper = document.createElement('div');
                let iconClass = 'bi-exclamation-triangle-fill'; // Ícone padrão
                if (type === 'success') {
                    iconClass = 'bi-check-circle-fill';
                }

                // (Assumindo que os ícones do Bootstrap estão carregados)
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">`,
                    `   <i class="bi ${iconClass} me-2"></i>`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');

                alertPlaceholder.append(wrapper);

                // Auto-dispensar o alerta
                setTimeout(() => {
                    const alertInstance = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
                    if (alertInstance) {
                        alertInstance.close();
                    }
                }, 5000); // 5 segundos
            };

            // --- NOVA LÓGICA DE STATUS DA URL ---
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');

            if (status === 'weak_password') {
                showAlert('A senha é muito fraca. Por favor, cumpra todos os requisitos.', 'danger');
            }
            if (status === 'terms_required') { // <-- NOVO STATUS DE ERRO
                showAlert('Você deve aceitar os Termos de Uso para se cadastrar.', 'danger');
            }

            // --- NOVA FUNÇÃO DE VALIDAÇÃO DE SENHA (AO DIGITAR) ---
            function validarSenhaAoDigitar() {
                const senha = senhaInput.value;
                let formValido = true;

                // 1. Validar Comprimento
                if (reqLength) {
                    if (senha.length >= 8) {
                        reqLength.style.color = 'green';
                        reqLength.style.textDecoration = 'line-through';
                    } else {
                        reqLength.style.color = '#424242';
                        reqLength.style.textDecoration = 'none';
                        formValido = false;
                    }
                }

                // 2. Validar Minúscula
                if (reqLowercase) {
                    if (/[a-z]/.test(senha)) {
                        reqLowercase.style.color = 'green';
                        reqLowercase.style.textDecoration = 'line-through';
                    } else {
                        reqLowercase.style.color = '#424242';
                        reqLowercase.style.textDecoration = 'none';
                        formValido = false;
                    }
                }

                // 3. Validar Maiúscula
                if (reqUppercase) {
                    if (/[A-Z]/.test(senha)) {
                        reqUppercase.style.color = 'green';
                        reqUppercase.style.textDecoration = 'line-through';
                    } else {
                        reqUppercase.style.color = '#424242';
                        reqUppercase.style.textDecoration = 'none';
                        formValido = false;
                    }
                }

                // 4. Validar Número
                if (reqNumber) {
                    if (/[0-9]/.test(senha)) {
                        reqNumber.style.color = 'green';
                        reqNumber.style.textDecoration = 'line-through';
                    } else {
                        reqNumber.style.color = '#424242';
                        reqNumber.style.textDecoration = 'none';
                        formValido = false;
                    }
                }

                return formValido; // Retorna se a senha é válida
            }

            // Adiciona o listener de 'keyup' ao campo de senha
            if (senhaInput) {
                senhaInput.addEventListener('keyup', validarSenhaAoDigitar);
            }

            // --- VALIDAÇÃO DO FORMULÁRIO ANTES DO ENVIO (Modificado) ---
            form.addEventListener('submit', function (event) {
                const ehProfissional = tipoUsuarioSelect.value === 'profissional';
                const senhaValida = validarSenhaAoDigitar(); // Chama a validação da senha
                const termosValido = termosCheckbox.checked; // <-- VALIDA O CHECKBOX
                let cpfValido = true;

                // Validação do CPF (existente)
                if (ehProfissional && cpfInput.value && !validarCPF(cpfInput.value)) {
                    cpfErro.style.display = 'block';
                    cpfValido = false;
                }

                // Impede o envio se o CPF, a Senha ou os Termos forem inválidos
                if (!cpfValido || !senhaValida || !termosValido) {
                    event.preventDefault(); // Impede o envio do formulário

                    if (!senhaValida && reqContainer) {
                        // Feedback visual extra
                        reqContainer.style.border = '1px solid red';
                        reqContainer.style.padding = '5px';
                        reqContainer.style.borderRadius = '5px';
                        showAlert('A senha não cumpre todos os requisitos.', 'danger');
                    }
                    
                    if (!termosValido) { // <-- MOSTRA O ALERTA DOS TERMOS
                        showAlert('Você deve aceitar os Termos de Uso e a Política de Privacidade.', 'danger');
                    }
                }
            });

            // --- MÁSCARA E VALIDAÇÃO DE CPF (CÓDIGO COMPLETO - existente) ---
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