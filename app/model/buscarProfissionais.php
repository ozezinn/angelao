<?php

// Garante que o script retorne dados no formato JSON
header('Content-Type: application/json');

require_once '../model/usuarioModel.php';

// Pega o termo de busca enviado via GET pelo JavaScript
$nome = $_GET['nome'] ?? '';

// Validação simples: não buscar se o termo for muito curto
if (strlen($nome) < 2) {
    echo json_encode([]); // Retorna um array JSON vazio
    exit;
}

$model = new UsuarioModel();

// Você precisará criar este método no seu Model
$profissionais = $model->buscarProfissionaisPorNome($nome);

// Retorna os resultados codificados em JSON
echo json_encode($profissionais);