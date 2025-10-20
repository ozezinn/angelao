<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userType = $_SESSION['usuario_tipo'] ?? null;
$currentAction = $_GET['action'] ?? ''; 
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
    
    <style>
        :root {
            --cor-principal: #424242;
            --cinza-escuro: #343a40;
            --branco: #fff;
        }

        .nav-link.panel-return-link {
            font-weight: 600;
            background-color: var(--cor-principal);
            color: var(--branco) !important;
            border: 1px solid var(--cor-principal);
            padding: 0.4rem 0.8rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .nav-link.panel-return-link:hover {
            background-color: var(--cinza-escuro);
            border-color: var(--cinza-escuro);
            color: var(--branco) !important;
        }
    </style>
</head>

<body>
    <header>
        <nav id="main-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="luumina.php">
                    <img class="navbar-logo" src="../view/img/logoBranca.png" alt="Luumina Logo">
                    <span class="ms-2">luumina</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <?php
                        $isAdminPage = in_array($currentAction, ['admin', 'gerenciarProfissionais', 'gerenciarClientes']);
                        if ($userType === 'admin' && !$isAdminPage) {
                            echo '
                            <li class="nav-item me-2">
                                <a class="nav-link panel-return-link" href="abc.php?action=admin">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Painel Admin
                                </a>
                            </li>';
                        }
                        
                        elseif ($userType === 'profissional' && $currentAction !== 'areaProfissional') {
                            echo '
                            <li class="nav-item me-2">
                                <a class="nav-link panel-return-link" href="abc.php?action=areaProfissional">
                                    <i class="bi bi-person-workspace me-1"></i> Meu Painel
                                </a>
                            </li>';
                        }
                        ?>

                        <li class="nav-item">
                            <a class="nav-link" href="abc.php?action=areaCliente">Encontrar Fotógrafos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="comoFunciona.php">Como Funciona</a>
                        </li>

                        <?php
                        if ($userType !== 'profissional' && $userType !== 'admin') {
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="abc.php?action=index&tipo=profissional">Para Fotógrafos</a>
                            </li>';
                        }
                        ?>
                    </ul>
                    
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#userActionsModal">
                                <i class="bi bi-person-circle me-2"></i>
                                Olá, <?php echo isset($_SESSION['usuario_nome']) ? explode(' ', $_SESSION['usuario_nome'])[0] : 'Usuário'; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>