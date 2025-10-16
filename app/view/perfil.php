<?php
// Todas as variáveis ($nome, $foto_perfil, etc.) vêm do controller showPublicProfile().
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
    <link rel="stylesheet" href="../../public/css/style4.css"> </head>
<body>
    <?php 
    // Decide qual header carregar: o logado ou o deslogado
    if (isset($_SESSION['usuario_id'])) {
        require_once __DIR__ . '/layout/header.php';
    } else {
        require_once __DIR__ . '/layout/header.php';
    }
    ?>
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
                                <div class="mt-4">
                                    <a href="#solicitar-orcamento" class="btn btn-primary btn-lg">
                                        <i class="bi bi-calendar-check"></i> Solicitar Orçamento
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <section id="portfolio-grid">
                <h2 class="text-center mb-4">Portfólio</h2>
                <?php if (!empty($portfolio_imagens)) : ?>
                    <div class="row g-4">
                        <?php foreach ($portfolio_imagens as $imagem) : ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="card portfolio-card">
                                    <img src="../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($imagem['titulo']) ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="text-center text-muted">Este profissional ainda não adicionou itens ao portfólio.</p>
                <?php endif; ?>
            </section>
            
            <section id="solicitar-orcamento" class="my-5">
                 <hr class="my-5">
                 <h2 class="text-center mb-4">Entre em Contato</h2>
                 <p class="text-center text-muted">Funcionalidade de orçamento em desenvolvimento.</p>
                 </section>
        </div>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>