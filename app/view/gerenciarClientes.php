<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<script>alert('Acesso restrito à área do administrador.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

require_once '../model/usuarioModel.php';
$model = new UsuarioModel();

$pesquisa = $_GET['email'] ?? '';
if ($pesquisa) {
    $clientes = array_filter($model->buscarTodosClientes(), function($c) use ($pesquisa) {
        return str_contains(strtolower($c['email']), strtolower($pesquisa));
    });
} else {
    $clientes = $model->buscarTodosClientes();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Clientes - Luumina</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../public/css/style4.css">
</head>
<body>
<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Clientes Cadastrados</h2>

    <form method="GET" action="gerenciarClientes.php" class="d-flex justify-content-center mb-4">
        <input type="text" name="email" class="form-control w-50 me-2" placeholder="Pesquisar por email" value="<?= htmlspecialchars($pesquisa) ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($clientes): ?>
            <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['nome']) ?></td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td>
                    <a href="abc.php?action=editarUsuario&id=<?= $c['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="abc.php?action=excluirCliente&id=<?= $c['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este usuário?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3" class="text-center">Nenhum cliente encontrado</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="abc.php?action=admin" class="btn btn-secondary">Voltar</a>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
