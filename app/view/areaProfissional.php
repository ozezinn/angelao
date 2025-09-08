<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'profissional') {
    echo "<script>alert('Acesso restrito à área do profissional.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$nome = $_SESSION['usuario_nome'];


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Profissional - Luumina</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style4.css">
</head>

<body>
    <?php require_once __DIR__ . '../layout/header.php'; ?>

    <main class="pt-5 mt-5">
        <div class="container text-center">
            <div class="welcome-box my-4">
                <h1>Olá, <?= htmlspecialchars($nome) ?>!</h1>
                <p>Bem-vindo à sua área de trabalho como <strong>profissional</strong>.</p>
                <nav>
                    <ul class="list-unstyled">
                        <li><a href="abc.php?action=alterarSenha" class="btn btn-outline-dark me-2">Alterar Senha</a></li>
                        <li><a href="abc.php?action=logout" class="btn btn-danger">Sair</a></li>
                    </ul>
                </nav>
            </div>

            <section id="painel-profissional" class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Adicionar Portfólio</h5>
                            <p class="card-text">Publique seus trabalhos (fotos ou vídeos) para os clientes verem.</p>
                            <a href="abc.php?action=addPortfolio" class="btn btn-dark">Adicionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Gerenciar Portfólio</h5>
                            <p class="card-text">Edite ou exclua trabalhos já cadastrados.</p>
                            <a href="abc.php?action=gerenciarPortfolio" class="btn btn-dark">Gerenciar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Solicitações de Orçamento</h5>
                            <p class="card-text">Veja e responda os pedidos feitos pelos clientes.</p>
                            <a href="abc.php?action=solicitacoes" class="btn btn-dark">Ver Solicitações</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 text-center">
                        <div class="card-body">
                            <h5 class="card-title">Editar Perfil</h5>
                            <p class="card-text">Atualize sua biografia, especialidades e informações de contato.</p>
                            <a href="abc.php?action=editarPerfil" class="btn btn-dark">Editar</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>