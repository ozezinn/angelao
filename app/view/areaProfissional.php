<?php
// O PHP no topo permanece o mesmo
$abrirModalPerfil = false;

if (empty(trim($biografia)) || empty(trim($localizacao)) || $foto_perfil === 'view/img/profile-placeholder.jpg') {
    $abrirModalPerfil = true;
}

// Variáveis para os novos cards de estatística
$total_fotos = count($portfolio_imagens);
$total_solicitacoes = count($solicitacoes);

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
    <link rel="stylesheet" href="../../public/css/style4.css">
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>
    <br><br><br><br>

    <main class="pt-5 mt-4">
        <div id="alert-placeholder" class="container"
            style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
        </div>

        <div class="container">

            <header class="profile-header card shadow-sm mb-5">
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

            <section class="row g-4 mb-5">
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
                                <h5 class="stat-card-title">--</h5>
                                <p class="stat-card-text">Visualizações (Em breve)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="portfolio-grid" class="mb-5">
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
                        <?php foreach ($portfolio_imagens as $imagem): ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card portfolio-card h-100">
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
                    <div class="text-center p-4 bg-light rounded">
                        <p class="text-muted mb-0">Ainda não há itens no seu portfólio. Clique em "Adicionar Foto" para
                            começar.</p>
                    </div>
                <?php endif; ?>
            </section>

            <section id="solicitacoes-recebidas" class="mt-5">
                <h2 class="mb-4">Solicitações de Orçamento Recebidas</h2>
                <div class="row g-4">
                    <?php if (!empty($solicitacoes)): ?>
                        <?php foreach ($solicitacoes as $solicitacao): ?>
                            <?php
                            // Define um ícone e cor para o status
                            $status_info = [
                                'novo' => ['icon' => 'bi-envelope-fill', 'color' => 'primary', 'text' => 'Nova Solicitação'],
                                'respondido' => ['icon' => 'bi-arrow-down-left-circle-fill', 'color' => 'success', 'text' => 'Respondido'],
                                'em_negociacao' => ['icon' => 'bi-chat-dots-fill', 'color' => 'info', 'text' => 'Em Negociação'],
                                'finalizado' => ['icon' => 'bi-check-circle-fill', 'color' => 'secondary', 'text' => 'Finalizado'],
                                'arquivado' => ['icon' => 'bi-archive-fill', 'color' => 'secondary', 'text' => 'Arquivado'],
                            ];
                            $status_atual = $solicitacao['status_solicitacao'] ?? 'novo';
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
                                       Recebido em: <?= !empty($solicitacao['data_solicitacao']) ? date('d/m/Y', strtotime($solicitacao['data_solicitacao'])) : 'Data não disponível' ?>
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
                </div>
            </section>

        </div>

        <?php include __DIR__ . '/modals/editProfileModal.php'; ?>
        <?php include __DIR__ . '/modals/addPortfolioModal.php'; ?>
        <?php include __DIR__ . '/modals/managePortfolioModal.php'; ?>
        <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    </main>

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
            const managePortfolioModalEl = document.getElementById('managePortfolioModal'); // Referência ao modal de gerenciamento

            // --- Função para configurar o modal para ADICIONAR ---
            const setupModalForAdd = () => {
                portfolioForm.action = 'abc.php?action=uploadFotoPortfolio';
                modalTitleEl.textContent = 'Adicionar Nova Foto ao Portfólio';
                itemIdInput.value = ''; // Limpa ID do item
                titleInput.value = '';
                descriptionInput.value = '';
                serviceSelect.selectedIndex = 0; // Volta para "Selecione..."
                fileInputContainer.style.display = 'block'; // Mostra input de arquivo
                fileInput.required = true; // Torna arquivo obrigatório
                submitButton.textContent = 'Adicionar Foto';
                submitButton.classList.remove('btn-success'); // Garante que não fique verde
                submitButton.classList.add('btn-primary');
            };

            // --- Função para configurar o modal para EDITAR ---
            const setupModalForEdit = (itemData) => {
                portfolioForm.action = 'abc.php?action=updatePortfolioItem';
                modalTitleEl.textContent = 'Editar Item do Portfólio';
                itemIdInput.value = itemData.itemId; // Define o ID do item
                titleInput.value = itemData.itemTitle;
                descriptionInput.value = itemData.itemDescription;
                serviceSelect.value = itemData.itemServiceId; // Seleciona a especialidade
                fileInputContainer.style.display = 'none'; // Esconde input de arquivo
                fileInput.required = false; // Arquivo não é obrigatório na edição
                submitButton.textContent = 'Salvar Alterações';
                submitButton.classList.remove('btn-primary');
                submitButton.classList.add('btn-success'); // Botão verde para salvar
            };

            // --- Event Listener para ABRIR modal (seja Adicionar ou Editar) ---
            addPortfolioModalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Botão que acionou o modal

                // Verifica se o botão tem a classe 'edit-portfolio-btn'
                if (button && button.classList.contains('edit-portfolio-btn')) {
                    // Modo EDITAR
                    const itemData = {
                        itemId: button.getAttribute('data-item-id'),
                        itemTitle: button.getAttribute('data-item-title'),
                        itemDescription: button.getAttribute('data-item-description'),
                        itemServiceId: button.getAttribute('data-item-service-id')
                    };
                    setupModalForEdit(itemData);
                    // Fecha o modal de gerenciamento se estiver aberto
                    const manageModalInstance = bootstrap.Modal.getInstance(managePortfolioModalEl);
                    if (manageModalInstance) {
                        manageModalInstance.hide();
                    }
                } else {
                    // Modo ADICIONAR (acionado pelo botão "Adicionar Foto")
                    setupModalForAdd();
                }
            });


            // --- Event Listener para LIMPAR o modal ao FECHAR (opcional, mas bom) ---
            addPortfolioModalEl.addEventListener('hidden.bs.modal', function () {
                setupModalForAdd(); // Reseta para o estado de adicionar ao fechar
            });


            // --- Lógica de Alertas (com novos status) ---
            const showAlert = (message, type = 'success') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                // ... (código da função showAlert permanece o mesmo) ...
                if (!alertPlaceholder) return;
                alertPlaceholder.innerHTML = ''; // Limpa alertas anteriores
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
                // --- Alertas de Perfil ---
                case 'success': showAlert('Perfil atualizado com sucesso!', 'success'); break;
                case 'profile_too_large': showAlert('Erro: A foto de perfil excede o limite de 5MB.', 'danger'); break;
                case 'profile_invalid_type': showAlert('Erro: Tipo de arquivo da foto é inválido.', 'danger'); break;
                case 'profile_dir_error': showAlert('Erro no servidor ao salvar foto de perfil (diretório).', 'danger'); break;
                case 'profile_upload_fail': showAlert('Erro ao salvar a foto de perfil.', 'danger'); break;
                case 'profile_upload_error': showAlert(`Erro no upload da foto de perfil (Código: ${errorCode}).`, 'danger'); break;

                // --- Alertas de Portfólio (Adicionar, Excluir, Editar) ---
                case 'upload_success': showAlert('Foto adicionada ao portfólio!', 'success'); break;
                case 'deleted': showAlert('Foto do portfólio excluída.', 'success'); break;
                case 'update_success': showAlert('Item do portfólio atualizado com sucesso!', 'success'); break; // Novo status Edição OK
                case 'update_error': showAlert('Erro ao atualizar item do portfólio.', 'danger'); break;      // Novo status Edição Erro
                case 'missing_title': showAlert('Erro: Título da foto é obrigatório.', 'warning'); break;
                case 'missing_service': showAlert('Erro: Selecione a especialidade relacionada.', 'warning'); break;
                case 'file_too_large': showAlert('Erro: Arquivo do portfólio excede 5MB.', 'danger'); break;
                case 'invalid_file_type': showAlert('Erro: Tipo de arquivo inválido para portfólio.', 'danger'); break;
                case 'portfolio_dir_error': showAlert('Erro no servidor ao salvar foto do portfólio (diretório).', 'danger'); break;
                case 'upload_fail': showAlert('Erro ao salvar imagem do portfólio.', 'danger'); break;
                case 'file_error': showAlert(`Erro no upload do portfólio (Código: ${errorCode}).`, 'danger'); break;
                case 'notfound': showAlert('Erro: Item do portfólio não encontrado.', 'danger'); break;
                case 'invalidid': showAlert('Erro: ID inválido para item do portfólio.', 'danger'); break;
                case 'dberror': showAlert('Ocorreu um erro no banco de dados. Tente novamente.', 'danger'); break; // Erro genérico DB
            }

            // --- Lógica para abrir modal de perfil incompleto ---
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