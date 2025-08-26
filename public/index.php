<?php
// Definir o caminho base
define('BASE_PATH',__DIR__.'/../');

// Incluir o controlador
    require_once BASE_PATH . 'app/controller/homeController.php';

// Criar a instância do controlador e chama o método 'index'
$controller = new homeController();
$controller->index();

?>