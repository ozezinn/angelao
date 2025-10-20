<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $isUserLoggedIn = isset($_SESSION['usuario_id']);

    if ($isUserLoggedIn) {
        require_once __DIR__ . '/layout/headerLog.php';
    } else {
        require_once __DIR__ . '/layout/header.php';
    }
    ?>
<link rel="stylesheet" href="../../public/css/style.css">
    <main>
        <section id="hero" class="d-flex align-items-center justify-content-center text-center">
            <div class="hero-content">
                <h1 class="display-4 fw-bold">Eternizando suas memorias </h1>
                <p class="hero-contentt" class="lead my-4">Encontre um Profissional Aqui.</p>
                <nav class="navbar">

                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24">
                        <g>
                            <path
                                d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                            </path>
                        </g>
                    </svg>
                    <input placeholder="Search" type="search" class="input" width="200px">
                </nav>
            </div>
        </section>

        <section id="produtos" class="py-5 bg-light-custom">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-titlee">Nossos Profissionais</h2>
                    <p class="text-muted">Aqui vai ficar alguns profisionais em destaque.</p>
                </div>
                <div class="row">
                </div>
            </div>
        </section>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<div class="noticia-container">
    <h2 class="noticia-titulo"><?php //echo htmlspecialchars($noticia['titulo']); ?></h2>
    <div class="noticia-paragrafo"><?php //echo  ($noticia['texto']); ?></div>
</div>
<?php include __DIR__ . '/modals/userActionsModal.php'; ?>
<?php require_once __DIR__ . '/layout/footer.php';?>
