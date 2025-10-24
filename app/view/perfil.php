<?php
// Todas as variáveis ($nome, $foto_perfil, etc.) vêm do controller showPublicProfile().

// Pega o email do usuário logado (se houver) para preencher o formulário
$email_solicitante_logado = $_SESSION['usuario_email'] ?? '';
$isUserLoggedIn = isset($_SESSION['usuario_id']); // Verifica se o usuário está logado

// Contagens para os Stat Cards
$total_fotos = count($portfolio_imagens);
$total_especialidades = count($especialidades);
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
    <style>
        /* Estilo para a coluna "pegajosa" (sticky) */
        .sticky-top-column {
            position: -webkit-sticky;
            position: sticky;
            top: 100px;
            /* 80px (navbar) + 20px (espaçamento) */
            z-index: 1000;
        }
    </style>
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

        <header class="profile-header mb-4">
            <div class="row align-items-center">
                <div class="col-lg-3 text-center">
                    <img src="<?= strpos($foto_perfil, 'public/') === 0 ? '../../' . htmlspecialchars($foto_perfil) : '../' . htmlspecialchars($foto_perfil) ?>"
                        alt="Foto de Perfil de <?= htmlspecialchars($nome) ?>" class="profile-picture">
                </div>
                <div class="col-lg-9">
                    <div class="profile-info">
                        <h1 class="profile-name"><?= htmlspecialchars($nome) ?></h1>
                        <p class="profile-location text-muted">
                            <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($localizacao) ?>
                        </p>
                        <div class="profile-specialties">
                            <?php foreach ($especialidades as $especialidade): ?>
                                <span class="badge bg-dark"><?= htmlspecialchars($especialidade) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="row g-4">

            <div class="col-lg-8">

                <section class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="stat-card h-100">
                            <div class="stat-card-body">
                                <div class="stat-card-icon text-primary">
                                    <i class="bi bi-images"></i>
                                </div>
                                <div>
                                    <h5 class="stat-card-title"><?= $total_fotos ?></h5>
                                    <p class="stat-card-text">Fotos no Portfólio</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card h-100">
                            <div class="stat-card-body">
                                <div class="stat-card-icon text-success">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <div>
                                    <h5 class="stat-card-title"><?= $total_especialidades ?></h5>
                                    <p class="stat-card-text">Especialidades</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="sobre-mim" class="content-card mb-4">
                    <h2 class="mb-3">Sobre <?= htmlspecialchars(explode(' ', $nome)[0]) ?></h2>
                    <p class="profile-bio-full">
                        <?= nl2br(htmlspecialchars($biografia)) ?>
                    </p>
                </section>

                <section id="portfolio-grid" class="content-card mb-4">
                    <h2 class="mb-4">Portfólio</h2>
                    <?php if (!empty($portfolio_imagens)): ?>
                        <div class="row g-3">
                            <?php foreach ($portfolio_imagens as $imagem): ?>
                                <div class="col-md-6 col-lg-4">
                                    <a href="#" class="portfolio-card-link" data-bs-toggle="modal"
                                        data-bs-target="#portfolioModal"
                                        data-bs-img-src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>"
                                        data-bs-img-title="<?= htmlspecialchars($imagem['titulo']) ?>">

                                        <div class="card portfolio-card">
                                            <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>"
                                                class="card-img-top" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                            <div class="card-body-overlay">
                                                <h5 class="card-title-overlay"><?= htmlspecialchars($imagem['titulo']) ?></h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Este profissional ainda não adicionou itens ao portfólio.</p>
                    <?php endif; ?>
                </section>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top-column">
                    <section id="solicitar-orcamento" class="content-card">
                        <h2 class="text-center mb-4">Entre em Contato</h2>
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <form action="abc.php?action=solicitarOrcamento" method="POST">

                                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario) ?>">
                                    <input type="hidden" name="id_profissional"
                                        value="<?= htmlspecialchars($id_profissional) ?>">

                                    <div class="mb-3">
                                        <label for="nome_solicitante" class="form-label">Seu Nome</label>
                                        <input type="text" class="form-control" id="nome_solicitante"
                                            name="nome_solicitante" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email_solicitante" class="form-label">Seu Email</label>
                                        <?php if ($isUserLoggedIn && !empty($email_solicitante_logado)): ?>
                                            <input type="email" class="form-control-plaintext" id="email_solicitante"
                                                name="email_solicitante"
                                                value="<?= htmlspecialchars($email_solicitante_logado) ?>" readonly
                                                required>
                                        <?php else: ?>
                                            <input type="email" class="form-control" id="email_solicitante"
                                                name="email_solicitante" value="" placeholder="seu@email.com" required>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="telefone_solicitante" class="form-label">Seu Telefone
                                            (Opcional)</label>
                                        <input type="tel" class="form-control" id="telefone_solicitante"
                                            name="telefone_solicitante" placeholder="(XX) XXXXX-XXXX">
                                    </div>
                                    <div class="mb-3">
                                        <label for="data_evento" class="form-label">Data do Evento (Opcional)</label>
                                        <input type="date" class="form-control" id="data_evento" name="data_evento"
                                            min="<?= date('Y-m-d') ?>">
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
                                        <textarea class="form-control" id="mensagem" name="mensagem" rows="4"
                                            placeholder="Descreva o que você precisa..." required></textarea>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary w-100">Enviar Solicitação</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div> <?php
        // Inclui o modal de ações do usuário APENAS se ele estiver logado
        if ($isUserLoggedIn) {
            include __DIR__ . '/modals/userActionsModal.php';
        }
        ?>

        <div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background-color: transparent; border: none;">
                    <div class="modal-header" style="border-bottom: none;">
                        <h5 class="modal-title" id="portfolioModalLabel" style="color: white; font-weight: 600;"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" class="img-fluid rounded modal-portfolio-img" alt="Foto do Portfólio"
                            style="max-height: 80vh;">
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Função para criar e exibir o alerta na tela
            const showAlert = (message, type = 'success') => {
                // (Código do alerta permanece o mesmo)
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;
                alertPlaceholder.innerHTML = '';
                const wrapper = document.createElement('div');
                let iconClass = 'bi-check-circle-fill';
                if (type === 'danger') {
                    iconClass = 'bi-exclamation-triangle-fill';
                }
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">`,
                    `   <i class="bi ${iconClass} me-2"></i>`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');
                alertPlaceholder.append(wrapper);
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

            // NOVO: Script para o Modal Lightbox do Portfólio
            const portfolioModal = document.getElementById('portfolioModal');
            if (portfolioModal) {
                portfolioModal.addEventListener('show.bs.modal', function (event) {
                    // Botão que acionou o modal
                    const link = event.relatedTarget;

                    // Extrai informações dos atributos data-bs-*
                    const imgSrc = link.getAttribute('data-bs-img-src');
                    const imgTitle = link.getAttribute('data-bs-img-title');

                    // Atualiza o conteúdo do modal
                    const modalTitle = portfolioModal.querySelector('.modal-title');
                    const modalImage = portfolioModal.querySelector('.modal-portfolio-img');

                    modalTitle.textContent = imgTitle;
                    modalImage.src = imgSrc;
                    modalImage.alt = imgTitle;
                });
            }
        });
    </script>

</body>

</html>