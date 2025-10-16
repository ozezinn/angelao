<?php
// Garante que a sessão seja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado e se é do tipo 'cliente'
// Se não, redireciona para a página de login
if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    echo "<script>alert('Acesso restrito à área do cliente.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

// Armazena o nome do usuário da sessão em uma variável para uso fácil
$nome = $_SESSION['usuario_nome'];
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
                            <img src="../view/img/categorias/casamento.jpeg" alt="Casamentos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Casamentos</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/corporativo.jpg" alt="Eventos Corporativos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Eventos Corporativos</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/ensaio.jpg" alt="Ensaios">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Ensaios</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/aniversario.jpg" alt="Aniversários">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Aniversários</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/formatura.jpg" alt="Formaturas">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Formaturas</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/eventos-religiosos.jpg" alt="Batizados e Eventos Religiosos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Eventos Religiosos</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/chas-de-bebe.jpg" alt="Chás de Bebê e Chás Revelação">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Chás de Bebê</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/produtos.jpg" alt="Fotografia de Produtos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Produtos</h5>
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
                            <img src="../view/img/categorias/arquitetura.jpg" alt="Arquitetura e Imóveis">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Arquitetura</h5>
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
                            <img src="../view/img/categorias/institucional.jpg" alt="Institucional">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Institucional</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/esportes.jpg" alt="Fotografia Esportiva">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Esportes</h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/shows.jpg" alt="Shows e Espetáculos">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Shows</h5>
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
                            <img src="../view/img/categorias/pet.jpg" alt="Fotografia Pet">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Pet</h5>
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
                    <div class="col-lg-2 col-md-4 col-6">
                        <a href="#" class="category-card">
                            <img src="../view/img/categorias/boudoir.jpg" alt="Boudoir: Ensaios sensuais e de autoestima">
                            <div class="category-card-overlay">
                                <h5 class="category-card-title">Boudoir</h5>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
  <div class="modal fade" id="userActionsModal" tabindex="-1" aria-labelledby="userActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userActionsModalLabel">Minha Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">Olá, <strong><?php echo $nome; ?></strong>! O que você gostaria de fazer?</p>
                
                <div class="list-group">
                    <a href="abc.php?action=editarPerfil" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-pencil-square me-3"></i>
                        Editar Perfil
                    </a>

                    <a href="abc.php?action=alterarSenha" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-key-fill me-3"></i>
                        Alterar Senha
                    </a>

                    <a href="abc.php?action=logout" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-box-arrow-right me-3"></i>
                        Sair (Logout)
                    </a>
                    <a href="abc.php?action=excluirConta" class="list-group-item list-group-item-action list-group-item-danger d-flex align-items-center mt-3">
                        <i class="bi bi-exclamation-triangle-fill me-3"></i>
                        Excluir Minha Conta
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>