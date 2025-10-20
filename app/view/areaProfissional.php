<?php
$abrirModalPerfil = false;

if (empty(trim($biografia)) || empty(trim($localizacao)) || $foto_perfil === 'view/img/profile-placeholder.jpg') {
    $abrirModalPerfil = true;
}
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
                                    <button type="button" class="btn btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                        <i class="bi bi-pencil-square"></i> Editar Perfil
                                    </button>
                                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">
                                        <i class="bi bi-plus-circle"></i> Adicionar Foto
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#managePortfolioModal">
                                        <i class="bi bi-images"></i> Gerenciar Fotos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($portfolio_imagens)) : ?>
                        <hr class="my-4">
                        <h4 class="text-center mb-4">Meu Portfólio</h4>
                        <div id="portfolioCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($portfolio_imagens as $index => $imagem) : ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                         <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="d-block w-100 portfolio-carousel-img" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#portfolioCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php else : ?>
                        <hr class="my-4">
                        <p class="text-center text-muted">Ainda não há itens no seu portfólio. Que tal adicionar alguns?</p>
                    <?php endif; ?>
                </div>
            </header>
            
            <section id="solicitacoes-recebidas" class="mt-5">
                <h2 class="mb-4">Solicitações de Orçamento Recebidas</h2>
                <div class="row g-4">
                    <?php if (!empty($solicitacoes)) : ?>
                        <?php foreach ($solicitacoes as $solicitacao) : ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header">
                                        <strong>Evento:</strong> <?= htmlspecialchars($solicitacao['tipo_evento']) ?>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Solicitante:</strong> <?= htmlspecialchars($solicitacao['nome_solicitante']) ?></p>
                                        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($solicitacao['email_solicitante']) ?>"><?= htmlspecialchars($solicitacao['email_solicitante']) ?></a></p>
                                        <?php if (!empty($solicitacao['telefone_solicitante'])) : ?>
                                            <p><strong>Telefone:</strong> <?= htmlspecialchars($solicitacao['telefone_solicitante']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($solicitacao['data_evento'])) : ?>
                                            <p><strong>Data do Evento:</strong> <?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?></p>
                                        <?php endif; ?>
                                        <hr>
                                        <p class="card-text">
                                            <?= nl2br(htmlspecialchars($solicitacao['mensagem'])) ?>
                                        </p>
                                    </div>
                                    <div class="card-footer text-muted text-end">
                                        Solicitado em: <?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col">
                            <p class="text-center text-muted">Você ainda não recebeu nenhuma solicitação de orçamento.</p>
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