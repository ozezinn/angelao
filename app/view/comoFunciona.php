<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Como Funciona - luumina.com</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="../../public/css/style.css">
</head>

<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="page-content">
        <section class="page-header text-center py-5 bg-light">
            <div class="container">
                <h1 class="display-4 fw-bold">Conectando você ao fotógrafo perfeito</h1>
                <p class="lead text-muted">Nossa missão é simples: ser a ponte entre seu momento especial e o profissional ideal para eternizá-lo.</p>
            </div>
        </section>

        <section class="how-it-works py-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">É simples, rápido e direto</h2>
                    <p class="text-muted">Encontre o que precisa em apenas 3 passos.</p>
                </div>
                <div class="row text-center g-4">
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <i class="bi bi-search fs-1 text-primary"></i>
                            </div>
                            <h3 class="h4">1. Explore e Descubra</h3>
                            <p>Navegue por especialidades, cidades ou veja os portfólios dos nossos fotógrafos. Use nossa busca para encontrar exatamente o que você procura.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <i class="bi bi-chat-dots fs-1 text-primary"></i>
                            </div>
                            <h3 class="h4">2. Inicie a Conversa</h3>
                            <p>Gostou de um profissional? Envie uma mensagem diretamente pelo perfil dele. Apresente sua ideia, tire dúvidas e solicite um orçamento sem compromisso.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="step-card">
                            <div class="step-icon mb-3">
                                <!-- ÍCONE ALTERADO AQUI -->
                                <i class="bi bi-file-earmark-text fs-1 text-primary"></i>
                            </div>
                            <h3 class="h4">3. Negocie e Contrate</h3>
                            <p>Toda a negociação de valores, prazos e detalhes do serviço é feita diretamente entre você e o fotógrafo. Total liberdade e transparência para ambos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="our-philosophy py-5 bg-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h2 class="section-title mb-3">Nossa Filosofia: A Ponte, Não o Destino</h2>
                        <p class="lead">Acreditamos que a melhor relação profissional nasce de uma boa conversa. Por isso, a Luumina funciona como uma grande vitrine para talentos incríveis da fotografia.</p>
                        <ul class="list-unstyled philosophy-list">
                            <li class="d-flex align-items-start mb-3">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong>Conexão Direta:</strong> Facilitamos o primeiro contato, mas a partir daí, a relação é de vocês.</div>
                            </li>
                            <li class="d-flex align-items-start mb-3">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong>Sem Intermediação Financeira:</strong> Não processamos pagamentos nem cobramos comissões sobre os trabalhos fechados. O valor combinado é 100% do fotógrafo.</div>
                            </li>
                             <li class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <div><strong>Foco na Divulgação:</strong> Nossa paixão é dar visibilidade ao trabalho dos profissionais, permitindo que mais pessoas encontrem o fotógrafo perfeito para suas necessidades.</div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-6 text-center d-none d-lg-block">
                        <img src="../view/img/filosofia.jpeg" class="img-fluid rounded" alt="Imagem abstrata representando conexão">
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

