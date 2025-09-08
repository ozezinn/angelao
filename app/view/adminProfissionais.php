<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<script>alert('Acesso restrito.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

require_once '../model/usuarioModel.php';
$model = new UsuarioModel();
$profissionais = $model->buscarTodosProfissionais();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Profissionais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <main class="container py-5">
        <h2>Profissionais Cadastrados</h2>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($profissionais as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['cpf']) ?></td>
                        <td>
                            <a href="abc.php?action=editarProfissional&id=<?= $p['id_usuario'] ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="abc.php?action=excluirProfissional&id=<?= $p['id_usuario'] ?>" class="btn btn-sm btn-danger">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>
</body>
</html>
