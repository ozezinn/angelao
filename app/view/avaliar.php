<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// As variáveis $solicitacao e $profissional_data são injetadas pelo controller showAvaliacao()
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente' || !isset($solicitacao)) {
    echo "<script>alert('Acesso negado.'); window.location.href='abc.php?action=logar';</script>";
    exit();
}

// IDs necessários para o formulário
$id_solicitacao = $solicitacao['id_solicitacao'];
$id_cliente = $_SESSION['usuario_id'];
$id_profissional = $solicitacao['id_profissional'];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Profissional - Luumina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/admin.css">
    <style>
        /* CSS para o sistema de avaliação por estrelas */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            /* Faz as estrelas preencherem da direita para a esquerda */
            justify-content: center;
            font-size: 2.5rem;
            cursor: pointer;
        }

        .star-rating input[type="radio"] {
            display: none;
            /* Esconde os botões de rádio originais */
        }

        .star-rating label {
            color: #ccc;
            padding: 0 0.25rem;
            transition: color 0.2s ease;
        }

        /* Quando o mouse passa por cima, todas as estrelas "anteriores" (à direita) acendem */
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
        }

        /* Quando um rádio é selecionado, todas as estrelas "anteriores" (à direita) ficam acesas */
        .star-rating input[type="radio"]:checked~label {
            color: #ffc107;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . '/layout/headerLog.php'; ?>
    <br><br><br><br>

    <main class="container admin-container" style="max-width: 800px;">

        <header class="admin-header text-center">
            <h1 class="display-6 fw-bold">Avaliar Serviço</h1>
            <p class="lead text-muted">Compartilhe sua experiência com
                <strong><?= htmlspecialchars($profissional_data['nome']) ?></strong> sobre o serviço
                "<?= htmlspecialchars($solicitacao['tipo_evento']) ?>".</p>
        </header>

        <section class="card">
            <div class="card-body p-4">

                <form id="formAvaliacao" action="abc.php?action=submitAvaliacao" method="POST"
                    enctype="multipart/form-data">

                    <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
                    <input type="hidden" name="id_cliente" value="<?= $id_cliente ?>">
                    <input type="hidden" name="id_profissional" value="<?= $id_profissional ?>">

                    <div class="mb-4 text-center">
                        <label class="form-label fs-5">Sua nota (1 a 5 estrelas)</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="nota_estrelas" value="5" required><label for="star5"
                                title="5 estrelas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" id="star4" name="nota_estrelas" value="4"><label for="star4"
                                title="4 estrelas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" id="star3" name="nota_estrelas" value="3"><label for="star3"
                                title="3 estrelas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" id="star2" name="nota_estrelas" value="2"><label for="star2"
                                title="2 estrelas"><i class="bi bi-star-fill"></i></label>
                            <input type="radio" id="star1" name="nota_estrelas" value="1"><label for="star1"
                                title="1 estrela"><i class="bi bi-star-fill"></i></label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="comentario" class="form-label fs-5">Seu comentário</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="5"
                            placeholder="Descreva como foi sua experiência com o profissional..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="fotos_avaliacao" class="form-label fs-5">Fotos (Opcional - Máx. 3)</label>
                        <p class="small text-muted">Envie até 3 fotos que você mais gostou do trabalho finalizado.</p>
                        <input class="form-control" type="file" id="fotos_avaliacao" name="fotos_avaliacao[]" multiple
                            accept="image/jpeg, image/png, image/webp">
                        <div id="file-limit-alert" class="alert alert-danger mt-2" style="display: none;">
                            Você só pode enviar no máximo 3 fotos.
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="abc.php?action=minhaCaixaDeEntrada" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-fill"></i> Enviar Avaliação
                        </button>
                    </div>

                </form>

            </div>
        </section>
    </main>

    <?php include __DIR__ . '/modals/userActionsModal.php'; ?>
    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formAvaliacao');
            const fileInput = document.getElementById('fotos_avaliacao');
            const alertDiv = document.getElementById('file-limit-alert');

            fileInput.addEventListener('change', function () {
                if (fileInput.files.length > 3) {
                    alertDiv.style.display = 'block';
                    fileInput.value = ""; // Limpa os arquivos selecionados
                } else {
                    alertDiv.style.display = 'none';
                }
            });

            form.addEventListener('submit', function (e) {
                // Revalida no envio, caso o usuário tenha contornado o 'change'
                if (fileInput.files.length > 3) {
                    e.preventDefault();
                    alertDiv.style.display = 'block';
                    fileInput.value = "";
                }
            });
        });
    </script>
</body>

</html>