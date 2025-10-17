<?php
// Inicia a sessão para verificar se o usuário está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isUserLoggedIn = isset($_SESSION['usuario_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
</head>

<body>

    <?php
    // Decide qual header carregar com base na sessão
    if ($isUserLoggedIn) {
        require_once __DIR__ . '/layout/headerLog.php';
    } else {
        require_once __DIR__ . '/layout/header.php';
    }
    ?>

    <main>
        <div id="alert-placeholder" class="container" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: 80%;"></div>


        <section id="hero" class="d-flex align-items-center justify-content-center text-center">
            <div class="container">
                <h1 class="display-5 fw-bold mb-4">O fotógrafo ideal para<br>eternizar seus momentos.</h1>

                <div class="hero-search-container">
                    <?php
                    // Define o placeholder e o estado do campo de busca com base no login
                    $searchPlaceholder = $isUserLoggedIn ? "Busque por nome do profissional..." : "Faça login para pesquisar";
                    $searchDisabled = !$isUserLoggedIn ? "disabled" : "";
                    ?>
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="form-control form-control-lg hero-search-input" id="searchInput" placeholder="<?= $searchPlaceholder ?>" autocomplete="off" <?= $searchDisabled ?>>
                    <div id="searchResults" class="search-results-container"></div>
                </div>
            </div>
        </section>

        <section id="categories" class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Especialidades</h2>
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

                    foreach ($categorias as $index => $cat) :
                        $link = $isUserLoggedIn ? "abc.php?action=showProfissionaisPorEspecialidade&especialidade=" . urlencode($cat) : "#";
                    ?>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="<?= $link ?>" class="category-card" data-especialidade="<?= htmlspecialchars($cat) ?>">
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
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="ajaxBusca.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isUserLoggedIn = <?= json_encode($isUserLoggedIn) ?>;
            const showAlert = (message, type) => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                alertPlaceholder.innerHTML = '';
                const wrapper = document.createElement('div');
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show" role="alert">`,
                    `   <div><i class="bi bi-exclamation-triangle-fill me-2"></i> ${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');
                alertPlaceholder.append(wrapper);
                setTimeout(() => {
                    wrapper.remove();
                }, 5000);
            };

            // Adiciona listener para os cards de categoria
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', function(event) {
                    if (!isUserLoggedIn) {
                        event.preventDefault();
                        showAlert('Você precisa estar logado para explorar as especialidades. <a href="login.php" class="alert-link">Fazer login</a>.', 'warning');
                    }
                });
            });

            // Adiciona listener para a barra de pesquisa (caso esteja desabilitada)
            const searchInput = document.getElementById('searchInput');
            if (searchInput.disabled) {
                searchInput.addEventListener('click', function(event){
                    showAlert('Você precisa estar logado para pesquisar por profissionais. <a href="login.php" class="alert-link">Fazer login</a>.', 'warning');
                });
            }
        });
    </script>

</body>

</html>