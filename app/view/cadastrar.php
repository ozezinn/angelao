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
                    <form class="form" id="cadastro-form" action="abc.php?action=cadastrar" method="POST">

                        <div class="input-group">
                            <label for="tipo_usuario">Eu sou um</label>
                            <select name="tipo_usuario" id="tipo_usuario" required>
                                <option value="" disabled <?= $tipoSelecionado === '' ? 'selected' : '' ?>>Selecione uma opção</option>
                                <option value="cliente" <?= $tipoSelecionado === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                <option value="profissional" <?= $tipoSelecionado === 'profissional' ? 'selected' : '' ?>>Profissional</option>
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

                            <div class="input-group">
                                <label>Especialidades (selecione uma ou mais)</label>
                                <div id="especialidades-container" class="checkbox-container">
                                    <div class="checkbox-item"><input type="checkbox" id="esp-casamentos" name="especialidades[]" value="Casamentos"><label for="esp-casamentos">Casamentos</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-corporativos" name="especialidades[]" value="Eventos Corporativos"><label for="esp-corporativos">Eventos Corporativos</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-ensaios" name="especialidades[]" value="Ensaios"><label for="esp-ensaios">Ensaios</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-aniversarios" name="especialidades[]" value="Aniversários"><label for="esp-aniversarios">Aniversários</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-formaturas" name="especialidades[]" value="Formaturas"><label for="esp-formaturas">Formaturas</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-religiosos" name="especialidades[]" value="Eventos Religiosos"><label for="esp-religiosos">Eventos Religiosos</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-chasbebe" name="especialidades[]" value="Chás de Bebê"><label for="esp-chasbebe">Chás de Bebê</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-produtos" name="especialidades[]" value="Produtos"><label for="esp-produtos">Produtos</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-gastronomia" name="especialidades[]" value="Gastronomia"><label for="esp-gastronomia">Gastronomia</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-arquitetura" name="especialidades[]" value="Arquitetura"><label for="esp-arquitetura">Arquitetura</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-moda" name="especialidades[]" value="Moda"><label for="esp-moda">Moda</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-institucional" name="especialidades[]" value="Institucional"><label for="esp-institucional">Institucional</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-esportes" name="especialidades[]" value="Esportes"><label for="esp-esportes">Esportes</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-shows" name="especialidades[]" value="Shows"><label for="esp-shows">Shows</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-drone" name="especialidades[]" value="Drone"><label for="esp-drone">Drone</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-pet" name="especialidades[]" value="Pet"><label for="esp-pet">Pet</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-viagem" name="especialidades[]" value="Viagem"><label for="esp-viagem">Viagem</label></div>
                                    <div class="checkbox-item"><input type="checkbox" id="esp-boudoir" name="especialidades[]" value="Boudoir"><label for="esp-boudoir">Boudoir</label></div>
                                </div>
                                <small id="especialidades-erro" style="color: red; display: none;">Selecione ao menos uma especialidade.</small>
                            </div>

                            <div class="input-group">
                                <label for="estado">Estado (UF)</label>
                                <select name="estado" id="estado">
                                    <option value="">Selecione um estado</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="cidade">Cidade</label>
                                <select name="cidade" id="cidade" disabled>
                                    <option value="">Aguardando estado...</option>
                                </select>
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
    document.addEventListener('DOMContentLoaded', function() {
        // --- ELEMENTOS DO DOM ---
        const form = document.getElementById('cadastro-form');
        const tipoUsuarioSelect = document.getElementById('tipo_usuario');
        const camposProfissional = document.getElementById('campos-profissional');
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');
        const cpfInput = document.getElementById('cpf');
        const cpfErro = document.getElementById('cpf-erro');
        const especialidadesErro = document.getElementById('especialidades-erro');

        // --- LÓGICA DE VISIBILIDADE DO FORMULÁRIO ---
        function atualizarVisibilidadeCampos() {
            const ehProfissional = tipoUsuarioSelect.value === 'profissional';
            camposProfissional.style.display = ehProfissional ? 'block' : 'none';
            // Torna os campos de profissional obrigatórios apenas se estiverem visíveis
            camposProfissional.querySelectorAll('input[type="text"], input[type="password"], select').forEach(input => {
                input.required = ehProfissional;
            });
        }

        tipoUsuarioSelect.addEventListener('change', atualizarVisibilidadeCampos);
        atualizarVisibilidadeCampos(); // Executa na inicialização

        // --- API DE ESTADOS E CIDADES (IBGE) ---
        fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then(res => res.json())
            .then(estados => {
                estados.forEach(estado => {
                    estadoSelect.innerHTML += `<option value="${estado.sigla}">${estado.nome}</option>`;
                });
            });

        estadoSelect.addEventListener('change', function() {
            const uf = this.value;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            cidadeSelect.disabled = true;

            if (!uf) {
                cidadeSelect.innerHTML = '<option value="">Aguardando estado...</option>';
                return;
            }

            fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`)
                .then(res => res.json())
                .then(cidades => {
                    cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                    cidades.forEach(cidade => {
                        cidadeSelect.innerHTML += `<option value="${cidade.nome}">${cidade.nome} - ${uf}</option>`;
                    });
                    cidadeSelect.disabled = false;
                });
        });

        // --- VALIDAÇÃO DO FORMULÁRIO ANTES DO ENVIO ---
        form.addEventListener('submit', function(event) {
            if (tipoUsuarioSelect.value === 'profissional') {
                // Validação das especialidades
                const especialidadesMarcadas = document.querySelectorAll('input[name="especialidades[]"]:checked').length;
                if (especialidadesMarcadas === 0) {
                    especialidadesErro.style.display = 'block';
                    event.preventDefault(); // Impede o envio do formulário
                } else {
                    especialidadesErro.style.display = 'none';
                }
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

        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = value;
        });

        cpfInput.addEventListener('blur', function() {
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