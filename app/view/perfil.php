<?php
// O Controller 'showPublicProfile' (versão ACIMA) é quem envia TODAS estas variáveis:
// $nome, $foto_perfil, $biografia, $localizacao, $id_usuario, $id_profissional,
// $especialidades, $portfolio_imagens, $avaliacoes,
// $media_estrelas, $total_avaliacoes, $total_portfolio, $total_especialidades

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email_solicitante_logado = $_SESSION['usuario_email'] ?? '';
$isUserLoggedIn = isset($_SESSION['usuario_id']);

// Função helper para exibir estrelas
function renderStars($nota) {
    $nota_int = intval(floor($nota)); // Pega a parte inteira
    $decimal = $nota - $nota_int; // Pega a parte decimal
    
    $html = '<div class="review-stars text-warning">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $nota_int) {
            $html .= '<i class="bi bi-star-fill"></i>'; // Estrela preenchida
        } else if ($i == ($nota_int + 1) && $decimal >= 0.5) {
             $html .= '<i class="bi bi-star-half"></i>'; // Meia estrela
        } else {
             $html .= '<i class="bi bi-star"></i>'; // Estrela vazia
        }
    }
    $html .= '</div>';
    return $html;
}
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
    <link rel="stylesheet" href="../../public/css/style4.css?v=1.1">
    <style>
        .sticky-top-column {
            position: -webkit-sticky;
            position: sticky;
            top: 100px;
            z-index: 1000;
        }
        .portfolio-thumbnail {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 0.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .portfolio-thumbnail:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>

<body>
    <div id="alert-placeholder" class="container"
        style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
    </div>

    <?php
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
                            <?php foreach (array_slice($especialidades, 0, 5) as $especialidade): ?>
                                <span class="badge bg-dark"><?= htmlspecialchars($especialidade) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($especialidades) > 5): ?>
                                <span class="badge bg-secondary">+<?= count($especialidades) - 5 ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="row g-4">

            <div class="col-lg-8">

                <div class="profile-stats-bar shadow-sm">
                    <div class="stat-item">
                        <span class="stat-number text-warning">
                            <?= htmlspecialchars($media_estrelas) ?>
                            <i class="bi bi-star-fill" style="font-size: 1.25rem;"></i>
                        </span>
                        <span class="stat-label">(<?= $total_avaliacoes ?> Avaliações)</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_portfolio ?></span>
                        <span class="stat-label">Fotos no Portfólio</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?= $total_especialidades ?></span>
                        <span class="stat-label">Especialidades</span>
                    </div>
                </div>


                <ul class="nav nav-tabs profile-tabs mb-4" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="sobre-tab" data-bs-toggle="tab" href="#sobre-content" role="tab" aria-controls="sobre-content" aria-selected="true">Sobre</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="portfolio-tab" data-bs-toggle="tab" href="#portfolio-content" role="tab" aria-controls="portfolio-content" aria-selected="false">Portfólio</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="avaliacoes-tab" data-bs-toggle="tab" href="#avaliacoes-content" role="tab" aria-controls="avaliacoes-content" aria-selected="false">Avaliações (<?= $total_avaliacoes ?>)</a>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabContent">

                    <div class="tab-pane fade show active" id="sobre-content" role="tabpanel" aria-labelledby="sobre-tab">
                        
                        <section id="sobre" class="content-card mb-4">
                            <h2 class="mb-3">Sobre <?= htmlspecialchars(explode(' ', $nome)[0]) ?></h2>
                            <p class="profile-bio-full">
                                <?= nl2br(htmlspecialchars($biografia)) ?>
                            </p>
                        </section>

                        <section id="especialidades" class="content-card mb-4">
                            <h2 class="mb-4">Todas as Especialidades</h2>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if (empty($especialidades)): ?>
                                    <p class="text-muted">Nenhuma especialidade cadastrada.</p>
                                <?php else: ?>
                                    <?php foreach ($especialidades as $especialidade): ?>
                                        <span class="badge bg-dark rounded-pill fs-6 px-3 py-2">
                                            <?= htmlspecialchars($especialidade) ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>

                    <div class="tab-pane fade" id="portfolio-content" role="tabpanel" aria-labelledby="portfolio-tab">
                        <section id="portfolio-grid" class="content-card mb-4">
                            <h2 class="mb-4">Portfólio</h2>
                            <?php if (empty($portfolio_imagens)): ?>
                                <p class="text-center text-muted">Este profissional ainda não adicionou itens ao portfólio.</p>
                            <?php else: ?>
                                <div class="row g-3">
                                    <?php 
                                    // ======================================================
                                    // CORREÇÃO APLICADA AQUI
                                    // $portfolio_imagens é um array de strings (caminhos)
                                    // ======================================================
                                    foreach ($portfolio_imagens as $imagem_path): 
                                    ?>
                                        <div class="col-md-6 col-lg-4">
                                            <a href="#" class="portfolio-card-link" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#imageModal"
                                                data-bs-image="../../<?= htmlspecialchars($imagem_path) ?>"
                                                data-bs-title="Foto do Portfólio"> 
                                                
                                                <img src="../../<?= htmlspecialchars($imagem_path) ?>"
                                                    class="portfolio-thumbnail" alt="Foto do Portfólio">
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </section>
                    </div>

                    <div class="tab-pane fade" id="avaliacoes-content" role="tabpanel" aria-labelledby="avaliacoes-tab">
                        <section id="avaliacoes" class="content-card mb-4">
                            <h2 class="mb-4">Avaliações de Clientes</h2>
                            
                            <?php if (empty($avaliacoes)): ?>
                                <p class="text-center text-muted">Este profissional ainda não recebeu avaliações.</p>
                            <?php else: ?>
                                <?php foreach ($avaliacoes as $avaliacao): ?>
                                    <div class="review-card mb-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <?= renderStars($avaliacao['nota_estrelas']) ?>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao'])) ?>
                                            </small>
                                        </div>
                                        <div class="review-body mt-3">
                                            <p class="review-comment">"<?= nl2br(htmlspecialchars($avaliacao['comentario'])) ?>"</p>
                                            
                                            <?php if (!empty($avaliacao['fotos'])): ?>
                                                <div class="review-photos mt-3">
                                                    <p class="small fw-bold mb-2">Fotos enviadas pelo cliente:</p>
                                                    <div class="row g-2">
                                                        <?php foreach ($avaliacao['fotos'] as $foto_path): ?>
                                                            <div class="col-auto">
                                                                <a href="#" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#imageModal"
                                                                    data-bs-image="../../<?= htmlspecialchars($foto_path) ?>"
                                                                    data-bs-title="Foto da Avaliação">
                                                                    
                                                                    <img src="../../<?= htmlspecialchars($foto_path) ?>" alt="Foto da avaliação" class="review-photo-thumbnail">
                                                                </a>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="review-footer mt-3">
                                            <strong class="text-muted">
                                                - <?= htmlspecialchars(explode(' ', $avaliacao['nome_cliente'])[0]) ?>
                                            </strong>
                                        </div>
                                    </div>
                                    <?php if (next($avaliacoes)): ?>
                                        <hr class="my-4">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </section>
                    </div>

                </div> </div>
            
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
                                        <label for="data_evento" class="form-label">Data do Evento (Opcional)</albel>
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
        </div> 
        
        <?php
        if ($isUserLoggedIn) {
            include __DIR__ . '/modals/userActionsModal.php';
        }
        ?>

        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background-color: transparent; border: none;">
                    <div class="modal-header" style="border-bottom: none;">
                        <h5 class="modal-title" id="imageModalLabel" style="color: white; font-weight: 600;"></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="" id="modalImage" class="img-fluid rounded modal-portfolio-img" alt="Foto Ampliada"
                            style="max-height: 90vh; width: auto;">
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Função de Alerta
            const showAlert = (message, type = 'success') => {
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

            // Status de Alerta (orçamento)
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');
            if (status === 'orcamento_success') {
                showAlert('Sua solicitação de orçamento foi enviada com sucesso!', 'success');
            } else if (status === 'orcamento_error') {
                showAlert('Ocorreu um erro ao enviar sua solicitação. Tente novamente.', 'danger');
            }

            // Script do Modal
            const imageModal = document.getElementById('imageModal');
            if (imageModal) {
                const modalTitleEl = imageModal.querySelector('.modal-title');
                const modalImageEl = imageModal.querySelector('#modalImage');

                imageModal.addEventListener('show.bs.modal', function (event) {
                    const link = event.relatedTarget;
                    const imgSrc = link.getAttribute('data-bs-image');
                    const imgTitle = link.getAttribute('data-bs-title') || 'Visualizar Imagem';

                    modalTitleEl.textContent = imgTitle;
                    modalImageEl.src = imgSrc;
                    modalImageEl.alt = imgTitle;
                });
            }
        });
    </script>

</body>

</html>