<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<script>alert('Acesso restrito à área do administrador.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$nome = $_SESSION['usuario_nome'];

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
<title>Área do Administrador - Luumina</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light" rel="stylesheet">
<link rel="stylesheet" href="../../public/css/input.css">
</head>
<body>
<?php require_once __DIR__ . '/layout/header.php'; ?>

<main class="container py-5">
    <div class="text-center mb-5">
        <h1>Olá, <?= htmlspecialchars($nome) ?>!</h1>
        <p>Bem-vindo à área administrativa.</p>
        <a href="abc.php?action=logout" class="sign">Sair</a>
    </div>

    <section class="mb-5 text-center">
        <h3>Pesquisar Usuário pelo Email</h3>
        <form method="GET" action="gerenciarProfissionais.php" class="d-flex justify-content-center mb-4">
        <input type="text" name="email" class="form-control w-50 me-2" placeholder="Pesquisar por email" value="<?= htmlspecialchars($pesquisa) ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>
    </section>

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
   
        </tbody>
    </table>

    <section class="text-center">
        <h3>Gerenciamento</h3>
        <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
            <a href="abc.php?action=gerenciarProfissionais" class="sign">Gerenciar Profissionais</a>
            <a href="abc.php?action=gerenciarClientes" class="sign">Gerenciar Clientes</a>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
