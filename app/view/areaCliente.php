<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    echo "<script>alert('Acesso restrito à área do cliente.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$nome = $_SESSION['usuario_nome'];
// As variáveis $profissionais e $especialidade_buscada são injetadas pelo controller
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo(a) à Luumina</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
    <style>
        /* Estilos adicionais para a página de resultados */
        .professional-result-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border-radius: 12px;
        }

        .professional-result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .professional-result-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .badge-especialidade {
            font-size: 0.75rem;
            padding: 0.3em 0.6em;
        }
    </style>
</head>

<body>

    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>

    <main>
        <section id="hero" class="d-flex align-items-center justify-content-center text-center">
            <div class="container">
                <h1 class="display-5 fw-bold mb-4">
                    Olá, <?php echo explode(' ', $nome)[0]; ?>!<br>
                    <span class="h2 fw-normal">Encontre o fotógrafo para seus momentos.</span>
                </h1>
                <div class="hero-search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="form-control form-control-lg hero-search-input" id="searchInput" placeholder="Busque por nome do profissional..." autocomplete="off">
                    <div id="searchResults" class="search-results-container"></div>
                </div>
            </div>
        </section>

        <?php if (!empty($profissionais)) : ?>
            <section id="resultados-especialidade" class="py-5 bg-light">
                <div class="container">
                    <h2 class="section-title mb-4">Resultados para "<?= htmlspecialchars($especialidade_buscada) ?>"</h2>
                    <div class="row g-4">
                        <?php foreach ($profissionais as $prof) : ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card professional-result-card h-100">
                                    <img src="../<?= htmlspecialchars($prof['foto_perfil'] ?? 'view/img/profile-placeholder.jpg') ?>" alt="Foto de <?= htmlspecialchars($prof['nome']) ?>">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= htmlspecialchars($prof['nome']) ?></h5>
                                        <p class="card-text text-muted small mb-2"><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($prof['localizacao'] ?? 'Não informado') ?></p>
                                        <div class="mt-auto">
                                            <p class="small mb-1"><strong>Especialidades:</strong></p>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php
                                                $especs = explode(', ', $prof['especialidades']);
                                                foreach ($especs as $esp) : ?>
                                                    <span class="badge bg-dark badge-especialidade"><?= htmlspecialchars($esp) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            <a href="abc.php?action=verPerfil&id=<?= $prof['id_usuario'] ?>" class="btn btn-sm btn-outline-dark mt-3 w-100">Ver Perfil</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php elseif (!empty($especialidade_buscada)): ?>
            <section id="resultados-vazios" class="py-5 bg-light">
                <div class="container text-center">
                    <h2 class="section-title mb-4">Nenhum resultado para "<?= htmlspecialchars($especialidade_buscada) ?>"</h2>
                    <p class="text-muted">Tente navegar por outra especialidade.</p>
                </div>
            </section>
        <?php endif; ?>


        <section id="categories" class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Navegue por Especialidades</h2>
                </div>
                <div class="row g-4">
                    <?php
                    $categorias = [
                        "Aniversário", "Arquitetura", "Boudoir", "Casamento", "Chá de Bebê",
                        "Drone", "Ensaio", "Esporte", "Evento Corporativo", "Evento Religioso",
                        "Formatura", "Gastronomia", "Institucional", "Moda", "Pet",
                        "Produto", "Show", "Viagem"
                    ];
                    $imagens = [
                        "aniversario.jpg", "arquitetura.jpg", "boudoir.jpg", "casamento.jpeg", "chas-de-bebe.jpg",
                        "drone.jpg", "ensaio.jpg", "esportes.jpg", "corporativo.jpg", "eventos-religiosos.jpg",
                        "formatura.jpg", "gastronomia.jpg", "institucional.jpg", "moda.jpg", "pet.jpg",
                        "produtos.jpg", "shows.jpg", "viagem.jpg"
                    ];

                    foreach ($categorias as $index => $cat) : ?>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="abc.php?action=showProfissionaisPorEspecialidade&especialidade=<?= urlencode($cat) ?>" class="category-card">
                                <img src="../view/img/categorias/<?= $imagens[$index] ?>" alt="<?= htmlspecialchars($cat) ?>">
                                <div class="category-card-overlay">
                                    <h5 class="category-card-title"><?= htmlspecialchars($cat) ?></h5>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php include __DIR__ . '/modals/userActionsModal.php'; ?>

    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ajaxBusca.js"></script>
</body>

</html>