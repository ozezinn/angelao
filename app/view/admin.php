<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validação de segurança
if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'admin') {
    echo "<script>alert('Acesso restrito à área do administrador.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$nome_admin = $_SESSION['usuario_nome'];

require_once '../model/usuarioModel.php';
$model = new UsuarioModel();

$pesquisa = $_GET['pesquisa'] ?? '';
$aba_ativa = $_GET['aba'] ?? 'profissionais'; // 'profissionais' ou 'clientes'

// Busca os dados
$profissionais = $model->buscarTodosProfissionais();
$clientes = $model->buscarTodosClientes();

// Filtra os resultados se houver uma pesquisa
if ($pesquisa) {
    $profissionais = array_filter($profissionais, function($p) use ($pesquisa) {
        return stripos($p['email'], $pesquisa) !== false || stripos($p['nome'], $pesquisa) !== false;
    });
    $clientes = array_filter($clientes, function($c) use ($pesquisa) {
        return stripos($c['email'], $pesquisa) !== false || stripos($c['nome'], $pesquisa) !== false;
    });
}

// Contagens para os cartões
$total_profissionais = count($profissionais);
$total_clientes = count($clientes);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel de Administração - Luumina</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
<?php 
require_once __DIR__ . '/layout/headerLog.php'; 
?>

<main class="container admin-container">
    <header class="admin-header">
        <h1 class="display-6 fw-bold">Painel Administrativo</h1>
        <p class="lead text-muted">Olá, <strong><?= htmlspecialchars($nome_admin) ?></strong>! Gerencie os usuários da plataforma.</p>
    </header>

    <section class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="stat-card-icon text-primary">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <h5 class="stat-card-title"><?= $total_profissionais ?></h5>
                        <p class="stat-card-text">Profissionais Cadastrados</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card h-100">
                <div class="stat-card-body">
                    <div class="stat-card-icon text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <h5 class="stat-card-title"><?= $total_clientes ?></h5>
                        <p class="stat-card-text">Clientes Cadastrados</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-content">
        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $aba_ativa === 'profissionais' ? 'active' : '' ?>" id="profissionais-tab" data-bs-toggle="tab" data-bs-target="#profissionais" type="button" role="tab" aria-controls="profissionais" aria-selected="true">
                    <i class="bi bi-briefcase-fill me-2"></i>Profissionais
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $aba_ativa === 'clientes' ? 'active' : '' ?>" id="clientes-tab" data-bs-toggle="tab" data-bs-target="#clientes" type="button" role="tab" aria-controls="clientes" aria-selected="false">
                    <i class="bi bi-people-fill me-2"></i>Clientes
                </button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">
            
            <div class="tab-pane fade <?= $aba_ativa === 'profissionais' ? 'show active' : '' ?>" id="profissionais" role="tabpanel" aria-labelledby="profissionais-tab">
                <div class="card border-top-0 rounded-top-0">
                    <div class="card-body">
                        <form method="GET" action="abc.php" class="mb-4">
                            <input type="hidden" name="action" value="admin">
                            <input type="hidden" name="aba" value="profissionais">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="pesquisa" class="form-control" placeholder="Buscar por nome ou email do profissional..." value="<?= htmlspecialchars($pesquisa) ?>">
                                <button type="submit" class="btn btn-outline-dark">Pesquisar</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CPF</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($profissionais)): ?>
                                    <?php foreach ($profissionais as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['nome']) ?></td>
                                        <td><?= htmlspecialchars($p['email']) ?></td>
                                        <td><?= htmlspecialchars($p['cpf']) ?></td>
                                        <td class="text-center">
                                            <a href="abc.php?action=editarUsuario&id=<?= $p['id_usuario'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="abc.php?action=excluirProfissional&id=<?= $p['id_usuario'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este profissional?');">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center">Nenhum profissional encontrado.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $aba_ativa === 'clientes' ? 'show active' : '' ?>" id="clientes" role="tabpanel" aria-labelledby="clientes-tab">
                 <div class="card border-top-0 rounded-top-0">
                    <div class="card-body">
                        <form method="GET" action="abc.php" class="mb-4">
                            <input type="hidden" name="action" value="admin">
                            <input type="hidden" name="aba" value="clientes">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" name="pesquisa" class="form-control" placeholder="Buscar por nome ou email do cliente..." value="<?= htmlspecialchars($pesquisa) ?>">
                                <button type="submit" class="btn btn-outline-dark">Pesquisar</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($clientes)): ?>
                                    <?php foreach ($clientes as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['nome']) ?></td>
                                        <td><?= htmlspecialchars($c['email']) ?></td>
                                        <td class="text-center">
                                            <a href="abc.php?action=editarUsuario&id=<?= $c['id_usuario'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="abc.php?action=excluirCliente&id=<?= $c['id_usuario'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center">Nenhum cliente encontrado.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php 

include __DIR__ . '/modals/userActionsModal.php'; 
?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>