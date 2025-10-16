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
                            <img src="../<?= htmlspecialchars($foto_perfil) ?>" alt="Foto de Perfil de <?= htmlspecialchars($nome) ?>" class="profile-picture">
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
                                        <img src="../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="d-block w-100 portfolio-carousel-img" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
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
            
            <section id="painel-profissional" class="row g-4">
                 </section>
        </div>

        <?php include __DIR__ . '/modals/editProfileModal.php'; ?>
        <?php include __DIR__ . '/modals/addPortfolioModal.php'; ?>
        <?php include __DIR__ . '/modals/managePortfolioModal.php'; ?>
        <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    </main>

    


     <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($abrirModalPerfil): ?>
    <script>
        // Espera o documento estar totalmente carregado para garantir que o Bootstrap JS esteja pronto
        document.addEventListener('DOMContentLoaded', function() {
            // Cria uma instância do modal do Bootstrap a partir do seu ID
            var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            
            // Mostra o modal
            editProfileModal.show();
        });
    </script>
    <?php endif; ?>
    </body>
</html>