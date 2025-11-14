<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['usuario_id']) || !isset($solicitacao)) {
    echo "<script>alert('Acesso negado.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversa - Luumina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/chat.css">
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>
    <br><br><br><br>

    <main class="container admin-container">

        <header class="admin-header d-flex justify-content-between align-items-center">
            <div>
                <h1 class="display-6 fw-bold">Conversa</h1>
                <p class="lead text-muted mb-0">Assunto: <?= htmlspecialchars($solicitacao['tipo_evento']) ?></p>
            </div>
            <a href="abc.php?action=minhaCaixaDeEntrada" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </header>

        <section class="chat-container card">
            <div class="card-body">

                <div class="chat-message received">
                    <div class="message-bubble">
                        <div class="message-sender"><?= htmlspecialchars($solicitacao['nome_solicitante']) ?></div>
                        <p class="message-content">
                            <strong>Tipo de Evento:</strong> <?= htmlspecialchars($solicitacao['tipo_evento']) ?><br>
                            <?php if (!empty($solicitacao['data_evento'])): ?>
                                <strong>Data do Evento:</strong>
                                <?= date('d/m/Y', strtotime($solicitacao['data_evento'])) ?><br>
                            <?php endif; ?>
                            <?php if (!empty($solicitacao['telefone_solicitante'])): ?>
                                <strong>Telefone:</strong> <?= htmlspecialchars($solicitacao['telefone_solicitante']) ?><br>
                            <?php endif; ?>
                            <strong>Email:</strong> <?= htmlspecialchars($solicitacao['email_solicitante']) ?>
                        </p>
                        <hr class="my-2">
                        <p class="message-content"><?= nl2br(htmlspecialchars($solicitacao['mensagem'])) ?></p>
                    </div>
                    <div class="message-time">
                        <?= !empty($solicitacao['data_solicitacao']) ? date('d/m/Y H:i', strtotime($solicitacao['data_solicitacao'])) : 'Data indisponível' ?>
                    </div>
                </div>

                <?php foreach ($mensagens as $msg): ?>
                    <?php
                    $isSent = $msg['id_remetente'] == $id_usuario_logado;
                    $messageClass = $isSent ? 'sent' : 'received';
                    $senderName = $isSent ? 'Você' : htmlspecialchars($msg['nome_remetente']);
                    ?>
                    <div class="chat-message <?= $messageClass ?>">
                        <div class="message-bubble">
                            <div class="message-sender"><?= $senderName ?></div>
                            <p class="message-content"><?= nl2br(htmlspecialchars($msg['mensagem'])) ?></p>
                        </div>
                        <div class="message-time"><?= date('d/m/Y H:i', strtotime($msg['data_envio'])) ?></div>
                    </div>
                <?php endforeach; ?>

            </div>
            <?php
            // Pega o status atual e o tipo de usuário
            $status_atual = $solicitacao['status_solicitacao'];
            $tipo_usuario = $_SESSION['usuario_tipo'];
            $id_solicitacao = $solicitacao['id_solicitacao'];

            // Define o que será exibido
            $mostrar_formulario_resposta = true;
            $interface_status = ''; // Onde vamos construir os botões ou mensagens
            
            // Lógica de exibição baseada no status
            switch ($status_atual) {
                // Status Abertos: Mostrar botões de encerramento
                case 'novo':
                case 'respondido':
                case 'em_negociacao':
                    $interface_status = "
                        <div class='text-center small text-muted mb-2'>Deseja encerrar esta negociação?</div>
                        <div class='d-flex justify-content-center gap-2'>
                            <a href='abc.php?action=solicitarEncerramento&id=$id_solicitacao&status=concluido' class='btn btn-sm btn-success'>
                                <i class='bi bi-check-circle'></i> Marcar como Concluído
                            </a>
                            <a href='abc.php?action=solicitarEncerramento&id=$id_solicitacao&status=dispensado' class='btn btn-sm btn-outline-danger'>
                                <i class='bi bi-x-circle'></i> Marcar como Dispensado
                            </a>
                        </div>
                    ";
                    break;

                // Status de Confirmação (Trabalho Concluído)
                case 'concluido_aguardando_prof':
                    if ($tipo_usuario === 'profissional') {
                        $interface_status = "
                            <div class='alert alert-warning text-center mb-0'>
                                <i class='bi bi-person-fill-check'></i> O cliente marcou este trabalho como <strong>concluído</strong>. Você confirma?
                                <div class='mt-2'>
                                    <a href='abc.php?action=solicitarEncerramento&id=$id_solicitacao&status=confirmar_concluido' class='btn btn-sm btn-success'>Sim, confirmo</a>
                                </div>
                            </div>
                        ";
                    } else {
                        $interface_status = "<div class='alert alert-info text-center mb-0'><i class='bi bi-hourglass-split'></i> Você marcou como concluído. Aguardando confirmação do profissional.</div>";
                    }
                    $mostrar_formulario_resposta = false;
                    break;

                case 'concluido_aguardando_cliente':
                    if ($tipo_usuario === 'cliente') {
                        $interface_status = "
                            <div class='alert alert-warning text-center mb-0'>
                                <i class='bi bi-briefcase-fill'></i> O profissional marcou este trabalho como <strong>concluído</strong>. Você confirma?
                                <div class='mt-2'>
                                    <a href='abc.php?action=solicitarEncerramento&id=$id_solicitacao&status=confirmar_concluido' class='btn btn-sm btn-success'>Sim, confirmo</a>
                                </div>
                            </div>
                        ";
                    } else {
                        $interface_status = "<div class='alert alert-info text-center mb-0'><i class='bi bi-hourglass-split'></i> Você marcou como concluído. Aguardando confirmação do cliente.</div>";
                    }
                    $mostrar_formulario_resposta = false;
                    break;

                // Status Finais (Concluído e Dispensado)
                case 'finalizado_concluido':
                    if ($tipo_usuario === 'cliente') {
                        // Cliente vê o botão para avaliar
                        $interface_status = "
                            <div class='alert alert-success text-center mb-0'>
                                <i class='bi bi-check-all'></i> Trabalho finalizado!
                                <p class='mb-0 mt-2'>Por favor, avalie o profissional para encerrar o processo.</p>
                                <a href='abc.php?action=showAvaliacao&id=$id_solicitacao' class='btn btn-primary mt-2'>
                                    <i class='bi bi-star-fill'></i> Avaliar Profissional
                                </a>
                            </div>
                        ";
                    } else {
                        // Profissional vê a mensagem de aguardo
                        $interface_status = "<div class='alert alert-success text-center mb-0'><i class='bi bi-check-all'></i> Trabalho finalizado. Aguardando avaliação do cliente.</div>";
                    }
                    $mostrar_formulario_resposta = false;
                    break;

                case 'finalizado_avaliado':
                    $interface_status = "<div class='alert alert-success text-center mb-0'><i class='bi bi-patch-check-fill'></i> Trabalho finalizado e avaliado. Obrigado!</div>";
                    $mostrar_formulario_resposta = false;
                    break;


                // (Podemos adicionar os status 'dispensado_...' aqui depois, o fluxo é o mesmo)
            
                case 'finalizado_dispensado':
                    $interface_status = "<div class='alert alert-secondary text-center mb-0'><i class='bi bi-archive-fill'></i> Negociação encerrada (não realizada).</div>";
                    $mostrar_formulario_resposta = false;
                    break;
            }
            ?>

            <?php if (!empty($interface_status)): ?>
                <div class="card-footer bg-light p-3">
                    <?= $interface_status ?>
                </div>
            <?php endif; ?>

            <?php if ($mostrar_formulario_resposta): ?>
                <div class="card-footer chat-reply-form">
                    <form action="abc.php?action=enviarMensagem" method="POST">
                        <input type="hidden" name="id_solicitacao"
                            value="<?= htmlspecialchars($solicitacao['id_solicitacao']) ?>">
                        <input type="hidden" name="id_destinatario" value="<?= htmlspecialchars($id_outra_pessoa) ?>">

                        <div class="input-group">
                            <textarea name="mensagem" class="form-control" placeholder="Digite sua resposta..." rows="3"
                                required></textarea>
                            <button class="btn btn-primary" type="submit" id="button-addon2">
                                Enviar <i class="bi bi-send-fill ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </main>



    <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pega o ID da solicitação e o ID do usuário logado (passados pelo PHP)
            const idSolicitacao = <?= json_encode($solicitacao['id_solicitacao']) ?>;
            const idUsuarioLogado = <?= json_encode($id_usuario_logado) ?>;

            // Encontra o container do chat
            const chatBody = document.querySelector('.chat-container .card-body');

            // Função para pegar o ID da última mensagem na tela
            let ultimoIdMensagem = <?= $mensagens[count($mensagens) - 1]['id_mensagem'] ?? 0 ?>;

            // Função para renderizar uma nova mensagem
            function adicionarMensagemAoChat(msg) {
                const isSent = msg.id_remetente == idUsuarioLogado;
                const messageClass = isSent ? 'sent' : 'received';
                const senderName = isSent ? 'Você' : msg.nome_remetente; // Use htmlspecialchars no PHP se preferir

                const dataEnvio = new Date(msg.data_envio).toLocaleString('pt-BR', {
                    day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });

                const chatMessage = document.createElement('div');
                chatMessage.className = `chat-message ${messageClass}`;
                chatMessage.innerHTML = `
                    <div class="message-bubble">
                        <div class="message-sender">${senderName}</div>
                        <p class="message-content">${msg.mensagem.replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="message-time">${dataEnvio}</div>
                `;

                chatBody.appendChild(chatMessage);

                // Atualiza o ID da última mensagem
                ultimoIdMensagem = msg.id_mensagem;
            }

            // Função que busca novas mensagens
            async function fetchNovasMensagens() {
                try {
                    const url = `abc.php?action=getNovasMensagens&id_solicitacao=${idSolicitacao}&ultimo_id=${ultimoIdMensagem}`;
                    const response = await fetch(url);

                    if (!response.ok) return;

                    const novasMensagens = await response.json();

                    if (novasMensagens.length > 0) {
                        novasMensagens.forEach(adicionarMensagemAoChat);
                        // Rola para o final do chat
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                } catch (error) {
                    console.error('Erro ao buscar mensagens:', error);
                }
            }

            // Inicia o "polling": verifica por novas mensagens a cada 5 segundos
            setInterval(fetchNovasMensagens, 5000); // 5000 ms = 5 segundos

            // Rola para o final ao carregar a página
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    </script>
</body>

</html>