<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// O controller 'showCaixaDeEntrada' é responsável por buscar as $conversas
// e já deve ter sido executado para incluir esta view.

if (!isset($_SESSION['usuario_id']) || !isset($conversas)) {
    echo "<script>alert('Você precisa estar logado para acessar esta área.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$tipo_usuario = $_SESSION['usuario_tipo'];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caixa de Entrada - Luumina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/chat.css">
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>
    <br><br><br><br>

    <main class="container admin-container">
        <header class="admin-header">
            <h1 class="display-6 fw-bold">Caixa de Entrada</h1>
            <p class="lead text-muted">Gerencie suas conversas e orçamentos.</p>
        </header>

        <section class="inbox-container">
            <div class="list-group">
                <?php if (empty($conversas)): ?>
                    <div class="list-group-item">
                        <p class="text-center text-muted mb-0">Você não possui nenhuma conversa no momento.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($conversas as $conversa): ?>
                        <?php
                            // Define o título da conversa com base no tipo de usuário
                            $titulo_conversa = '';
                            if ($tipo_usuario === 'profissional') {
                                $titulo_conversa = 'Conversa com ' . htmlspecialchars($conversa['nome_solicitante']);
                            } else {
                                // Assume que o controller/model injetou 'nome_profissional'
                                $titulo_conversa = 'Conversa com ' . htmlspecialchars($conversa['nome_profissional']);
                            }
                            
                            // Define um ícone e cor para o status
                            $status_info = [
                                'novo' => ['icon' => 'bi-envelope-fill', 'color' => 'primary', 'text' => 'Nova Solicitação'],
                                'respondido' => ['icon' => 'bi-arrow-down-left-circle-fill', 'color' => 'success', 'text' => 'Respondido'],
                                'em_negociacao' => ['icon' => 'bi-chat-dots-fill', 'color' => 'info', 'text' => 'Em Negociação'],
                                'finalizado' => ['icon' => 'bi-check-circle-fill', 'color' => 'muted', 'text' => 'Finalizado'],
                                'arquivado' => ['icon' => 'bi-archive-fill', 'color' => 'muted', 'text' => 'Arquivado'],
                            ];
                            $status_atual = $conversa['status_solicitacao'] ?? 'novo';
                            $status = $status_info[$status_atual];
                        ?>

                        <a href="abc.php?action=verConversa&id=<?= $conversa['id_solicitacao'] ?>" class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= $titulo_conversa ?></h5>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($conversa['data_solicitacao'] ?? $conversa['data_envio'])) ?></small>
                            </div>
                            <p class="mb-1">
                                <strong>Assunto:</strong> <?= htmlspecialchars($conversa['tipo_evento']) ?>
                            </p>
                            <small class="text-<?= $status['color'] ?>">
                                <i class="bi <?= $status['icon'] ?> me-1"></i>
                                <?= $status['text'] ?>
                            </small>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>