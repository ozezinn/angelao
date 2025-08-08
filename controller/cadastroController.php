<?php
// Inclui a sua classe de conexão
require_once 'Conexao.php';

// Inclui a classe do modelo que acabamos de criar
require_once 'usuarioModel.php';

// Verifica se a requisição foi feita via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Instancia a classe de conexão para obter o objeto PDO
    $conexao = new Conexao();
    $conn = $conexao->getConn();

    // Instancia o modelo de usuário, passando a conexão
    $usuarioModel = new UsuarioModel($conn);

    // Recebe e sanitiza os dados do formulário
    $nome = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT); // Você precisará adicionar um campo 'senha' no formulário
    $tipo_usuario = 'cliente'; // Define um tipo padrão para o usuário

    // Validação básica
    if (empty($nome) || $email === false || empty($senha)) {
        die("Erro: Por favor, preencha todos os campos obrigatórios.");
    }

    // Chama o método de cadastro do modelo
    if ($usuarioModel->cadastrar($nome, $email, $senha, $tipo_usuario)) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Falha no cadastro. Tente novamente.";
    }

} else {
    // Se não for POST, redireciona de volta para o formulário
    header("Location: cadastrar.html");
    exit();
}
