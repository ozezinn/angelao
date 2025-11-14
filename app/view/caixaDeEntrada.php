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

        <div id="alert-placeholder" class="container"
            style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;">
        </div>

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
                        // ==================================================
                        // INÍCIO DA LÓGICA CORRIGIDA
                        // ==================================================
                
                        // 1. Define o Título (Quem é a outra pessoa)
                        $titulo_conversa = '';
                        if ($tipo_usuario === 'profissional') {
                            $titulo_conversa = 'Conversa com ' . htmlspecialchars($conversa['nome_solicitante']);
                        } else {
                            $titulo_conversa = 'Conversa com ' . htmlspecialchars($conversa['nome_profissional']);
                        }

                        // 2. Define o Status (Ícone e Cor)
                        $status_info = [
                            'novo' => ['icon' => 'bi-envelope-fill', 'color' => 'primary', 'text' => 'Nova Solicitação'],
                            'respondido' => ['icon' => 'bi-arrow-down-left-circle-fill', 'color' => 'success', 'text' => 'Respondido'],
                            'em_negociacao' => ['icon' => 'bi-chat-dots-fill', 'color' => 'info', 'text' => 'Em Negociação'],

                            // Status de encerramento
                            'concluido_aguardando_prof' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'concluido_aguardando_cliente' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'dispensado_aguardando_prof' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],
                            'dispensado_aguardando_cliente' => ['icon' => 'bi-hourglass-split', 'color' => 'warning', 'text' => 'Aguardando Confirmação'],

                            // Status finais
                            'finalizado_concluido' => ['icon' => 'bi-check-circle-fill', 'color' => 'primary', 'text' => 'Concluído (Avaliar)'],
                            'finalizado_dispensado' => ['icon' => 'bi-archive-fill', 'color' => 'muted', 'text' => 'Finalizado (Dispensado)'],
                            'finalizado_avaliado' => ['icon' => 'bi-patch-check-fill', 'color' => 'success', 'text' => 'Concluído e Avaliado'],
                            'arquivado' => ['icon' => 'bi-archive-fill', 'color' => 'muted', 'text' => 'Arquivado'],
                        ];
                        $status_atual = $conversa['status_solicitacao'] ?? 'novo';
                        // Garante que um status desconhecido não quebre a página
                        if (!array_key_exists($status_atual, $status_info)) {
                            $status_atual = 'novo';
                        }
                        $status = $status_info[$status_atual];


                        // 3. Define a Ação (Link, Texto do Botão e Cor de Destaque)
                        $link_acao = "abc.php?action=verConversa&id=" . $conversa['id_solicitacao'];
                        $texto_acao = "Ver Conversa";
                        $classe_acao = "list-group-item-action"; // Classe padrão
                
                        // Se for Cliente e o status for 'finalizado_concluido', o link muda para AVALIAR
                        if ($tipo_usuario === 'cliente' && $status_atual === 'finalizado_concluido') {
                            $link_acao = "abc.php?action=showAvaliacao&id=" . $conversa['id_solicitacao'];
                            $texto_acao = "Avaliar Profissional <i class='bi bi-star-fill ms-1'></i>";
                            $classe_acao = "list-group-item-success"; // Destaca em verde
                
                        } else if ($status_atual === 'finalizado_avaliado') {
                            $texto_acao = "Ver Conversa (Trabalho Avaliado)";
                        }
                        // ==================================================
                        // FIM DA LÓGICA CORRIGIDA
                        // ==================================================
                        ?>

                        <a href="<?= $link_acao ?>" class="list-group-item <?= $classe_acao ?> flex-column align-items-start">

                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= $titulo_conversa ?></h5>
                                <small
                                    class="text-muted"><?= date('d/m/Y', strtotime($conversa['data_solicitacao'] ?? $conversa['data_envio'])) ?></small>
                            </div>

                            <p class="mb-1">
                                <strong>Assunto:</strong> <?= htmlspecialchars($conversa['tipo_evento']) ?>
                            </p>

                            <small class="text-<?= $status['color'] ?>">
                                <i class="bi <?= $status['icon'] ?> me-1"></i>
                                <?= $status['text'] ?>
                            </small>

                            <div class="mt-2 fw-bold text-primary">
                                <?= $texto_acao ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showAlert = (message, type = 'success') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;
                alertPlaceholder.innerHTML = '';
                const wrapper = document.createElement('div');
                let iconClass = 'bi-check-circle-fill';
                if (type === 'danger') {
                    iconClass = 'bi-exclamation-triangle-fill';
                } else if (type === 'warning') {
                    iconClass = 'bi-exclamation-triangle-fill';
                }
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">`,
                    `   <i class="bi ${iconClass} me-2"></i>`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');
                alertPlaceholder.append(wrapper);
                setTimeout(() => {
                    const alertInstance = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
                    if (alertInstance) {
                        alertInstance.close();
                    }
                }, 5000);
            };

            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');

            if (status === 'avaliacao_sucesso') {
                showAlert('Avaliação enviada com sucesso! Obrigado.', 'success');
            } else if (status === 'avaliacao_erro' || status === 'avaliacao_db_erro') {
                showAlert('Ocorreu um erro ao enviar sua avaliação.', 'danger');
            } else if (status === 'avaliacao_ja_feita') {
                showAlert('Este trabalho já foi avaliado ou não pode ser avaliado.', 'warning');
            }
        });
    </script>
</body>

</html>