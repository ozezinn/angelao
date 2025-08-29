<?php
if (session_start() === PHP_SESSION_NONE){
    session_start();

}
    define('BASE_URL', 'http://localhost:8080/'); // Está definido para o endereçamento do meu servidor (deve mudar) 

    require_once '../controller/usuarioController.php';

    $controle = new UsuarioController();
    $action = $_GET['action'] ?? 'index';

    switch ($action){
        case 'index';
            $controle->index();
            break;
        case 'cadastrar';
            $controle->cadastrar();
            break;
        case 'logar';
            $controle->logar();
            break;
        case 'autenticar';
            $controle->autenticar();
            break;
        case 'logout':
            $controle->logout();
            break;
        case 'areaProfissional';
            include 'view/areaProfissional.php';
            break;
        case 'areaCliente';
            include '../view/areaCliente.php';
            break;
        default:
            echo "404 - Página não encontrada";
            break;

            
    }

?>