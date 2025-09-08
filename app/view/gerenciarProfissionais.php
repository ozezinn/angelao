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
    $profissionais = array_filter($model->buscarTodosProfissionais(), function($p) use ($pesquisa) {
        return str_contains(strtolower($p['email']), strtolower($pesquisa));
    });
} else {
    $profissionais = $model->buscarTodosProfissionais();
}
$pesquisaa = $_GET['email'] ?? '';
if ($pesquisaa) {
    $clientes = array_filter($model->buscarTodosClientes(), function($c) use ($pesquisaa) {
        return str_contains(strtolower($c['email']), strtolower($pesquisaa));
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
<title>Gerenciar Profissionais - Luumina</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../public/css/style4.css">
</head>
<body>
<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Profissionais Cadastrados</h2>

    <form method="GET" action="gerenciarProfissionais.php" class="d-flex justify-content-center mb-4">
        <input type="text" name="email" class="form-control w-50 me-2" placeholder="Pesquisar por email" value="<?= htmlspecialchars($pesquisa) ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($profissionais): ?>
            <?php foreach ($profissionais as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td><?= htmlspecialchars($p['email']) ?></td>
                <td><?= htmlspecialchars($p['cpf']) ?></td>
                <td>
                    <a href="abc.php?action=editarUsuario&id=<?= $p['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="abc.php?action=excluirProfissional&id=<?= $p['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir este usuário?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">Nenhum profissional encontrado</td></tr>
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