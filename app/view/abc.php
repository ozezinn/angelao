<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_URL', 'http://localhost:8080/'); // Ajuste conforme seu servidor

require_once '../controller/usuarioController.php';

$controle = new UsuarioController();
$action = $_GET['action'] ?? 'index';

switch ($action) {

    case 'index':
        $controle->index();
        break;

    case 'cadastrar':
        $controle->cadastrar();
        break;

    case 'logar':
        $controle->logar();
        break;

    case 'autenticar':
        $controle->autenticar();
        break;

    case 'logout':
        $controle->logout();
        break;

    case 'areaProfissional':
        include '../view/areaProfissional.php';
        break;

    case 'areaCliente':
        include '../view/areaCliente.php';
        break;

    case 'alterarSenha':
        $controle->alterarSenha();
        break;

    case 'admin':
        include '../view/admin.php';
        break;

    case 'gerenciarProfissionais':
        include '../view/gerenciarProfissionais.php';
        break;

    case 'gerenciarClientes':
    include '../view/gerenciarClientes.php';
    break;

    case 'excluirProfissional':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new UsuarioModel();
            $model->excluirUsuario($id);
        }
        header('Location: abc.php?action=gerenciarProfissionais');
        exit();
        break;

   case 'excluirCliente':
    $id = $_GET['id'] ?? null;
    if ($id) {
        require_once '../model/usuarioModel.php';
        $model = new UsuarioModel();
        $model->excluirUsuario($id);
    }
    header('Location: abc.php?action=gerenciarClientes');
    exit();
    break;


    case 'editarUsuario':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $_SESSION['editar_id'] = $id;
            header('Location: editarUsuario.php');
            exit();
        }
        break;

    default:
        echo "404 - Página não encotrada";
        break;
}
?>