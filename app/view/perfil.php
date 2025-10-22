<?php
// Todas as variáveis ($nome, $foto_perfil, etc.) vêm do controller showPublicProfile().

// Pega o email do usuário logado (se houver) para preencher o formulário
$email_solicitante_logado = $_SESSION['usuario_email'] ?? '';
$isUserLoggedIn = isset($_SESSION['usuario_id']); // Verifica se o usuário está logado
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?= htmlspecialchars($nome) ?> - Luumina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style4.css">
</head>

<body>
    <div id="alert-placeholder" class="container"
        style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
    </div>

    <?php
    // Decide qual header carregar: o logado ou o deslogado
    if ($isUserLoggedIn) {
        require_once __DIR__ . '/layout/headerLog.php';
    } else {
        require_once __DIR__ . '/layout/header.php';
    }
    ?>
    
    <main class="admin-container">
        
        <header class="profile-header mb-5">
            <div class="row align-items-center">
                <div class="col-lg-3 text-center">
                    <img src="../../<?= htmlspecialchars($foto_perfil) ?>"
                        alt="Foto de Perfil de <?= htmlspecialchars($nome) ?>" class="profile-picture">
                </div>
                <div class="col-lg-9">
                    <div class="profile-info">
                        <h1 class="profile-name"><?= htmlspecialchars($nome) ?></h1>
                        <p class="profile-location text-muted">
                            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($localizacao) ?>
                        </p>
                        <p class="profile-bio"><?= htmlspecialchars($biografia) ?></p>
                        <div class="profile-specialties">
                            <?php foreach ($especialidades as $especialidade): ?>
                                <span class="badge bg-dark"><?= htmlspecialchars($especialidade) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4">
                            <a href="#solicitar-orcamento" class="btn btn-primary btn-lg">
                                <i class="bi bi-calendar-check"></i> Solicitar Orçamento
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="portfolio-grid" class="content-card mb-5">
            <h2 class="text-center mb-4">Portfólio</h2>
            <?php if (!empty($portfolio_imagens)): ?>
                <div class="row g-4">
                    <?php foreach ($portfolio_imagens as $imagem): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card portfolio-card">
                                <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($imagem['titulo']) ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Este profissional ainda não adicionou itens ao portfólio.</p>
            <?php endif; ?>
        </section>

        <section id="solicitar-orcamento" class="content-card">
            <h2 class="text-center mb-4">Entre em Contato</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="abc.php?action=solicitarOrcamento" method="POST">

                        <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">

                        <input type="hidden" name="id_profissional"
                            value="<?= htmlspecialchars($id_profissional) ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nome_solicitante" class="form-label">Seu Nome</label>
                                <input type="text" class="form-control" id="nome_solicitante"
                                    name="nome_solicitante" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email_solicitante" class="form-label">Seu Email</label>
                                
                                <?php if ($isUserLoggedIn && !empty($email_solicitante_logado)): ?>
                                    <input type="email" class="form-control-plaintext" id="email_solicitante"
                                           name="email_solicitante" value="<?= htmlspecialchars($email_solicitante_logado) ?>" readonly required>
                                <?php else: ?>
                                    <input type="email" class="form-control" id="email_solicitante"
                                           name="email_solicitante" value="" placeholder="seu@email.com" required>
                                <?php endif; ?>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefone_solicitante" class="form-label">Seu Telefone
                                    (Opcional)</label>
                                <input type="tel" class="form-control" id="telefone_solicitante"
                                    name="telefone_solicitante">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_evento" class="form-label">Data do Evento</label>
                                <input type="date" class="form-control" id="data_evento" name="data_evento">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_evento" class="form-label">Tipo de Evento/Serviço</label>
                            <select class="form-select" id="tipo_evento" name="tipo_evento" required>
                                <option value="" disabled selected>Selecione uma especialidade</option>
                                <?php foreach ($especialidades as $especialidade): ?>
                                    <option value="<?= htmlspecialchars($especialidade) ?>">
                                        <?= htmlspecialchars($especialidade) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5"
                                placeholder="Descreva o que você precisa, inclua detalhes como local, número de convidados, etc."
                                required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Solicitação</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <?php
        // Inclui o modal de ações do usuário APENAS se ele estiver logado
        if ($isUserLoggedIn) {
            include __DIR__ . '/modals/userActionsModal.php';
        }
        ?>
    </main> <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Função para criar e exibir o alerta na tela
            const showAlert = (message, type = 'success') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;

                // Limpa alertas antigos antes de mostrar um novo
                alertPlaceholder.innerHTML = '';

                const wrapper = document.createElement('div');
                let iconClass = 'bi-check-circle-fill'; // Ícone de sucesso por padrão
                if (type === 'danger') {
                    iconClass = 'bi-exclamation-triangle-fill'; // Ícone para erros
                }

                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">`,
                    `   <i class="bi ${iconClass} me-2"></i>`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');

                alertPlaceholder.append(wrapper);

                // Remove o alerta automaticamente após 5 segundos
                setTimeout(() => {
                    wrapper.remove();
                }, 5000);
            };

            // Verifica os parâmetros da URL para decidir qual alerta mostrar
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');

            if (status === 'orcamento_success') {
                showAlert('Sua solicitação de orçamento foi enviada com sucesso!', 'success');
            } else if (status === 'orcamento_error') {
                showAlert('Ocorreu um erro ao enviar sua solicitação. Tente novamente.', 'danger');
            }
        });
    </script>

</body>

</html>