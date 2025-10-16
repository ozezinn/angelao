<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
</head>

<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main>
        <section id="hero" class="d-flex align-items-center justify-content-center text-center">
            <div class="container">
                <h1 class="display-5 fw-bold mb-4">O fotógrafo ideal para<br>eternizar seus momentos.</h1>
                <div class="hero-search-container">
                    <i class="bi bi-search search-icon"></i>
                    <input type="search" class="form-control form-control-lg hero-search-input"
                        placeholder="Busque por tipo de evento, cidade ou nome do profissional...">
                </div>
            </div>
        </section>

        <section id="categories" class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Especialidades</h2>
                </div>
                <div class="row g-4">
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/aniversario.jpg" alt="Aniversários">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Aniversário</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/arquitetura.jpg" alt="Arquitetura e Imóveis">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Arquitetura</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/boudoir.jpg"
                                alt="Boudoir: Ensaios sensuais e de autoestima">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Boudoir</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/casamento.jpeg" alt="Casamentos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Casamento</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/chas-de-bebe.jpg" alt="Chás de Bebê e Chás Revelação">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Chá de Bebê</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/drone.jpg" alt="Fotografia com Drone (Aérea)">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Drone</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/ensaio.jpg" alt="Ensaios">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Ensaio</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/esportes.jpg" alt="Fotografia Esportiva">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Esporte</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/corporativo.jpg" alt="Eventos Corporativos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Evento Corporativo</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/eventos-religiosos.jpg"
                                alt="Batizados e Eventos Religiosos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Evento Religioso</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/formatura.jpg" alt="Formaturas">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Formatura</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/gastronomia.jpg" alt="Gastronomia">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Gastronomia</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/institucional.jpg" alt="Institucional">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Institucional</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/moda.jpg" alt="Moda">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Moda</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/pet.jpg" alt="Fotografia Pet">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Pet</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/produtos.jpg" alt="Fotografia de Produtos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Produto</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/shows.jpg" alt="Shows e Espetáculos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Show</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/viagem.jpg" alt="Fotografia de Viagem e Turismo">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Viagem</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>