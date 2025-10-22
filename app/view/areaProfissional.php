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
        <div class="container">

            <header class="profile-header card shadow-sm mb-5">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-3 text-center">
                            <img src="../../<?= htmlspecialchars($foto_perfil) ?>" alt="Foto de Perfil de <?= htmlspecialchars($nome) ?>" class="profile-picture">
                        </div>
                        <div class="col-lg-9">
                            <div class="profile-info">
                                <h1 class="profile-name"><?= htmlspecialchars($nome) ?></h1>
                                <p class="profile-location text-muted">
                                    <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($localizacao) ?>
                                </p>
                                <p class="profile-bio"><?= htmlspecialchars($biografia) ?></p>
                                <div class="profile-specialties">
                                    <?php foreach ($especialidades as $especialidade) : ?>
                                        <span class="badge bg-dark"><?= htmlspecialchars($especialidade) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editProfileModal">
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">
                            <i class="bi bi-plus-circle"></i> Adicionar Foto
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#managePortfolioModal">
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
                        <p class="text-muted mb-0">Ainda não há itens no seu portfólio. Clique em "Adicionar Foto" para começar.</p>
                    </div>
                <?php endif; ?>
            </section>
            
            <section id="solicitacoes-recebidas" class="mt-5">
                <h2 class="mb-4">Solicitações de Orçamento Recebidas</h2>
                <div class="row g-4">
                    <?php if (!empty($solicitacoes)) : ?>
                        <?php foreach ($solicitacoes as $solicitacao) : ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm solicitation-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-dark">
                                            <i class="bi bi-briefcase-fill me-2"></i>
                                            <strong><?= htmlspecialchars($solicitacao['tipo_evento']) ?></strong>
                                        </h6>
                                        <span class="badge bg-primary">Novo</span> 
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= htmlspecialchars($solicitacao['nome_solicitante']) ?></h5>
                                        
                                        <ul class="list-unstyled text-muted small mb-3">
                                            <li class="mb-1">
                                                <i class="bi bi-envelope-fill me-2 text-primary"></i>
                                                <a href="mailto:<?= htmlspecialchars($solicitacao['email_solicitante']) ?>"><?= htmlspecialchars($solicitacao['email_solicitante']) ?></a>
                                            </li>
                                            <?php if (!empty($solicitacao['telefone_solicitante'])) : ?>
                                            <li class="mb-1">
                                                <i class="bi bi-telephone-fill me-2 text-success"></i>
                                                <?= htmlspecialchars($solicitacao['telefone_solicitante']) ?>
                                            </li>
                                            <?php endif; ?>
                                            <?php if (!empty($solicitacao['data_evento'])) : ?>
                                            <li class="mb-2">
                                                <i class="bi bi-calendar-event-fill me-2 text-dark"></i>
                                                Evento em: <strong><?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?></strong>
                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                        
                                        <hr class="my-2">
                                        
                                        <p class="card-text small solicitation-message flex-grow-1">
                                            <?= nl2br(htmlspecialchars($solicitacao['mensagem'])) ?>
                                        </p>
                                    </div>
                                    <div class="card-footer text-muted text-end small">
                                        Recebido em: <?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
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
    </body>
</html>