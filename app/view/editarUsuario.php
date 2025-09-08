<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../model/usuarioModel.php';

$model = new UsuarioModel();
$idUsuario = $_SESSION['editar_id'] ?? null;
if (!$idUsuario) header('Location: abc.php?action=admin');

$usuario = $model->buscarPorId($idUsuario);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $model->atualizarUsuario($idUsuario, $nome, $email);
    header('Location: abc.php?action=gerenciarProfissionais'); 
    exit();
}
?>

<form method="POST">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
    <button type="submit">Salvar</button>
</form>
