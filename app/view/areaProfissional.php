<?php
// O Controller 'showAreaProfissional' (versão ACIMA) é quem envia TODAS estas variáveis:
// $nome, $foto_perfil, $biografia, $localizacao, $id_usuario, $id_profissional,
// $especialidades, $portfolio_imagens, $avaliacoes, $solicitacoes, $todos_servicos,
// $abrirModalPerfil, $total_fotos, $total_solicitacoes,
// $cidade_usuario, $estado_usuario
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Profissional - Luumina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../public/css/admin.css">
    <link rel="stylesheet" href="../../public/css/style4.css">
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>

    <main class="container admin-container">
        <div id="alert-placeholder" class="container"
            style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
        </div>

        <header class="profile-header card shadow-sm mb-4">
            <div class="card-body">
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
                            <p class="profile-bio"><?= htmlspecialchars($biografia) ?></p>
                            <div class="profile-specialties">
                                <?php foreach ($especialidades as $especialidade): ?>
                                    <span class="badge bg-dark"><?= htmlspecialchars($especialidade) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal"
                                    data-bs-target="#editProfileModal">
                                    <i class="bi bi-pencil-square"></i> Editar Perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="row g-4 mb-4">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <div class="stat-card-body">
                        <div class="stat-card-icon text-success">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                        <div>
                            <h5 class="stat-card-title"><?= $total_solicitacoes ?></h5>
                            <p class="stat-card-text">Solicitações Recebidas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card h-100">
                    <div class="stat-card-body">
                        <div class="stat-card-icon text-info">
                            <i class="bi bi-eye-fill"></i>
                        </div>
                        <div>
                            <a href="abc.php?action=verPerfil&id=<?= $_SESSION['usuario_id'] ?>" target="_blank"
                                class="btn btn-outline-info">
                                <i class="bi bi-eye-fill me-1"></i> Ver meu Perfil Público
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="portfolio-grid" class="content-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="m-0">Meu Portfólio</h2>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#addPortfolioModal">
                        <i class="bi bi-plus-circle"></i> Adicionar Foto
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#managePortfolioModal">
                        <i class="bi bi-images"></i> Gerenciar Fotos
                    </button>
                </div>
            </div>

            <?php if (!empty($portfolio_imagens)): ?>
                <div class="row g-4">
                    <?php
                    // Mostra apenas as 6 mais recentes; o resto é visível em "Gerenciar"
                    foreach (array_slice($portfolio_imagens, 0, 6) as $imagem):
                        ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card portfolio-card h-100">
                                <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($imagem['titulo']) ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate"><?= htmlspecialchars($imagem['titulo']) ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center p-4 bg-light rounded">
                    <p class="text-muted mb-0">Ainda não há itens no seu portfólio. Clique em "Adicionar Foto" para
                        começar.</p>
                </div>
            <?php endif; ?>
        </section>

        <section id="solicitacoes-recebidas" class="content-card">
            <h2 class="mb-4">Solicitações de Orçamento Recebidas</h2>
            <div class="row g-4">
                <?php if (!empty($solicitacoes)): ?>
                    <?php
                    // Mostra apenas as 6 mais recentes
                    foreach (array_slice($solicitacoes, 0, 6) as $solicitacao):
                        ?>
                        <?php
                        $status_info = [
                            'novo' => ['icon' => 'bi-envelope-fill', 'color' => 'primary', 'text' => 'Nova Solicitação'],
                            'respondido' => ['icon' => 'bi-arrow-down-left-circle-fill', 'color' => 'success', 'text' => 'Respondido'],
                            'em_negociacao' => ['icon' => 'bi-chat-dots-fill', 'color' => 'info', 'text' => 'Em Negociação'],
                            'concluido_aguardando_prof' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'concluido_aguardando_cliente' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'dispensado_aguardando_prof' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'dispensado_aguardando_cliente' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'finalizado_concluido' => ['icon' => 'bi-check-circle-fill', 'color' => 'primary', 'text' => 'Concluído'],
                            'finalizado_dispensado' => ['icon' => 'bi-archive-fill', 'color' => 'muted', 'text' => 'Dispensado'],
                            'finalizado_avaliado' => ['icon' => 'bi-patch-check-fill', 'color' => 'success', 'text' => 'Avaliado'],
                            'arquivado' => ['icon' => 'bi-archive-fill', 'color' => 'muted', 'text' => 'Arquivado'],
                        ];
                        $status_atual = $solicitacao['status_solicitacao'] ?? 'novo';
                        if (!array_key_exists($status_atual, $status_info)) {
                            $status_atual = 'novo';
                        }
                        $status = $status_info[$status_atual];
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm solicitation-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-dark">
                                        <i class="bi bi-briefcase-fill me-2"></i>
                                        <strong><?= htmlspecialchars($solicitacao['tipo_evento']) ?></strong>
                                    </h6>
                                    <span class="badge bg-<?= $status['color'] ?>">
                                        <i class="bi <?= $status['icon'] ?> me-1"></i>
                                        <?= $status['text'] ?>
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($solicitacao['nome_solicitante']) ?></h5>

                                    <ul class="list-unstyled text-muted small mb-3">
                                        <?php if (!empty($solicitacao['data_evento'])): ?>
                                            <li class="mb-2">
                                                <i class="bi bi-calendar-event-fill me-2 text-dark"></i>
                                                Evento em:
                                                <strong><?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?></strong>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                    <hr class="my-2">

                                    <p class="card-text small solicitation-message flex-grow-1">
                                        <?= nl2br(htmlspecialchars($solicitacao['mensagem'])) ?>
                                    </p>

                                    <a href="abc.php?action=verConversa&id=<?= $solicitacao['id_solicitacao'] ?>"
                                        class="btn btn-primary mt-3">
                                        <i class="bi bi-chat-fill me-1"></i> Ver Conversa
                                    </a>
                                </div>
                                <div class="card-footer text-muted text-end small">
                                    Recebido em:
                                    <?= !empty($solicitacao['data_solicitacao']) ? date('d/m/Y', strtotime($solicitacao['data_solicitacao'])) : 'Data não disponível' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col">
                        <div class="text-center p-4 bg-light rounded">
                            <p class="text-muted mb-0">Você ainda não recebeu nenhuma solicitação de orçamento.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (count($solicitacoes) > 6): ?>
                    <div class="col-12 text-center mt-4">
                        <a href="abc.php?action=minhaCaixaDeEntrada" class="btn btn-outline-dark">
                            Ver todas as (<?= $total_solicitacoes ?>) solicitações
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <?php include __DIR__ . '/modals/editProfileModal.php'; ?>
    <?php include __DIR__ . '/modals/addPortfolioModal.php'; ?>
    <?php include __DIR__ . '/modals/managePortfolioModal.php'; ?>
    <?php include __DIR__ . '/modals/userActionsModal.php'; ?>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addPortfolioModalEl = document.getElementById('addPortfolioModal');
            const portfolioForm = document.getElementById('portfolioForm');
            const modalTitleEl = document.getElementById('addPortfolioModalLabel');
            const itemIdInput = document.getElementById('portfolioItemId');
            const titleInput = document.getElementById('tituloFoto');
            const descriptionInput = document.getElementById('descricaoFoto');
            const serviceSelect = document.getElementById('servicoRelacionado');
            const fileInputContainer = document.getElementById('arquivoFotoContainer');
            const fileInput = document.getElementById('arquivoFoto');
            const submitButton = document.getElementById('portfolioSubmitButton');
            const managePortfolioModalEl = document.getElementById('managePortfolioModal');

            const setupModalForAdd = () => {
                portfolioForm.action = 'abc.php?action=uploadFotoPortfolio';
                modalTitleEl.textContent = 'Adicionar Nova Foto ao Portfólio';
                itemIdInput.value = '';
                titleInput.value = '';
                descriptionInput.value = '';
                serviceSelect.selectedIndex = 0;
                fileInputContainer.style.display = 'block';
                fileInput.required = true;
                submitButton.textContent = 'Adicionar Foto';
                submitButton.classList.remove('btn-success');
                submitButton.classList.add('btn-primary');
            };

            const setupModalForEdit = (itemData) => {
                portfolioForm.action = 'abc.php?action=updatePortfolioItem';
                modalTitleEl.textContent = 'Editar Item do Portfólio';
                itemIdInput.value = itemData.itemId;
                titleInput.value = itemData.itemTitle;
                descriptionInput.value = itemData.itemDescription;
                serviceSelect.value = itemData.itemServiceId;
                fileInputContainer.style.display = 'none';
                fileInput.required = false;
                submitButton.textContent = 'Salvar Alterações';
                submitButton.classList.remove('btn-primary');
                submitButton.classList.add('btn-success');
            };

            addPortfolioModalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (button && button.classList.contains('edit-portfolio-btn')) {
                    const itemData = {
                        itemId: button.getAttribute('data-item-id'),
                        itemTitle: button.getAttribute('data-item-title'),
                        itemDescription: button.getAttribute('data-item-description'),
                        itemServiceId: button.getAttribute('data-item-service-id')
                    };
                    setupModalForEdit(itemData);
                    const manageModalInstance = bootstrap.Modal.getInstance(managePortfolioModalEl);
                    if (manageModalInstance) {
                        manageModalInstance.hide();
                    }
                } else {
                    setupModalForAdd();
                }
            });

            addPortfolioModalEl.addEventListener('hidden.bs.modal', function () {
                setupModalForAdd();
            });

            const showAlert = (message, type = 'success') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;
                alertPlaceholder.innerHTML = '';
                const wrapper = document.createElement('div');
                let iconClass = 'bi-check-circle-fill';
                if (type === 'danger') {
                    iconClass = 'bi-exclamation-triangle-fill';
                } else if (type === 'warning') {
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
                    const alertInstance = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
                    if (alertInstance) {
                        alertInstance.close();
                    }
                }, 5000);
            };

            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');
            const errorCode = params.get('code');

            switch (status) {
                case 'success': showAlert('Perfil atualizado com sucesso!', 'success'); break;
                case 'profile_too_large': showAlert('Erro: A foto de perfil excede o limite de 5MB.', 'danger'); break;
                case 'profile_invalid_type': showAlert('Erro: Tipo de arquivo da foto é inválido.', 'danger'); break;
                case 'profile_dir_error': showAlert('Erro no servidor ao salvar foto de perfil (diretório).', 'danger'); break;
                case 'profile_upload_fail': showAlert('Erro ao salvar a foto de perfil.', 'danger'); break;
                case 'profile_upload_error': showAlert(`Erro no upload da foto de perfil (Código: ${errorCode}).`, 'danger'); break;
                case 'upload_success': showAlert('Foto adicionada ao portfólio!', 'success'); break;
                case 'deleted': showAlert('Foto do portfólio excluída.', 'success'); break;
                case 'update_success': showAlert('Item do portfólio atualizado com sucesso!', 'success'); break;
                case 'update_error': showAlert('Erro ao atualizar item do portfólio.', 'danger'); break;
                case 'missing_title': showAlert('Erro: Título da foto é obrigatório.', 'warning'); break;
                case 'missing_service': showAlert('Erro: Selecione a especialidade relacionada.', 'warning'); break;
                case 'file_too_large': showAlert('Erro: Arquivo do portfólio excede 5MB.', 'danger'); break;
                case 'invalid_file_type': showAlert('Erro: Tipo de arquivo inválido para portfólio.', 'danger'); break;
                case 'portfolio_dir_error': showAlert('Erro no servidor ao salvar foto do portfólio (diretório).', 'danger'); break;
                case 'upload_fail': showAlert('Erro ao salvar imagem do portfólio.', 'danger'); break;
                case 'file_error': showAlert(`Erro no upload do portfólio (Código: ${errorCode}).`, 'danger'); break;
                case 'notfound': showAlert('Erro: Item do portfólio não encontrado.', 'danger'); break;
                case 'invalidid': showAlert('Erro: ID inválido para item do portfólio.', 'danger'); break;
                case 'dberror': showAlert('Ocorreu um erro no banco de dados. Tente novamente.', 'danger'); break;
            }

            <?php if ($abrirModalPerfil): ?>
                setTimeout(() => {
                    showAlert('Complete seu perfil para começar! Adicione uma foto, biografia e localização.', 'warning');
                    var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                    editProfileModal.show();
                }, 500);
            <?php endif; ?>
        });
    </script>
</body>

</html>