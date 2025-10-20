<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validação de segurança: Garante que apenas o admin possa acessar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<script>alert('Acesso restrito!'); window.location.href='abc.php?action=logar';</script>";
    exit();
}

require_once '../model/usuarioModel.php';

$model = new UsuarioModel();
$idUsuario = $_SESSION['editar_id'] ?? null;

// Se não houver um ID de usuário na sessão, redireciona de volta para o painel principal
if (!$idUsuario) {
    header('Location: abc.php?action=admin');
    exit();
}

// Busca os dados atuais do usuário para preencher o formulário
$usuario = $model->buscarPorId($idUsuario);

// Se o usuário não for encontrado, redireciona
if (!$usuario) {
    unset($_SESSION['editar_id']); // Limpa a sessão
    header('Location: abc.php?action=admin');
    exit();
}

// Lógica de atualização quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    if ($model->atualizarUsuario($idUsuario, $nome, $email)) {
        // Lógica para redirecionar de volta para a aba correta
        $redirectAction = $usuario['tipo_usuario'] === 'profissional' ? 'gerenciarProfissionais' : 'gerenciarClientes';
        unset($_SESSION['editar_id']); // Limpa a sessão após o sucesso
        header("Location: abc.php?action={$redirectAction}&status=success");
        exit();
    } else {
        $errorMessage = "Ocorreu um erro ao tentar atualizar o usuário.";
    }
}

// Define para qual página o botão "Voltar" deve levar
$backAction = $usuario['tipo_usuario'] === 'profissional' ? 'gerenciarProfissionais' : 'gerenciarClientes';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário - Luumina Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/layout/headerLog.php'; ?>

<main class="container admin-container">
    <header class="admin-header">
        <h1 class="display-6 fw-bold">Editar Usuário</h1>
        <p class="lead text-muted">Altere as informações de <?= htmlspecialchars($usuario['nome']) ?>.</p>
    </header>

    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?= $errorMessage ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Endereço de Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Tipo de Usuário</label>
                    <input type="text" class="form-control" value="<?= ucfirst($usuario['tipo_usuario']) ?>" disabled readonly>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="abc.php?action=<?= $backAction ?>" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>