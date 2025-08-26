<?php
// Inicia a sessão para podermos usar variáveis de sessão para mensagens
session_start();

// Inclui o Model de Usuário
require_once '../model/usuarioModel.php';

// 1. VERIFICAÇÃO: Checa se o formulário foi realmente enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 2. VALIDAÇÃO: Pega os dados do formulário e verifica se não estão vazios
    $nome = !empty($_POST['nome']) ? trim($_POST['nome']) : null;
    $email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    $senha = !empty($_POST['senha']) ? trim($_POST['senha']) : null;
    $tipoUsuario = !empty($_POST['tipoUsuario']) ? trim($_POST['tipoUsuario']) : null;

    // Verifica se todos os campos foram preenchidos
    if ($nome && $email && $senha && $tipo_usuario) {

        // Validação adicional para o formato do email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensagem_erro'] = "Formato de e-mail inválido.";
            header('Location: ../view/cadastrar.html');
            exit();
        }

        // Validação para o tipo de usuário (para garantir que seja um valor esperado)
        $tipos_permitidos = ['cliente', 'profissional'];
        if (!in_array($tipo_usuario, $tipos_permitidos)) {
            $_SESSION['mensagem_erro'] = "Tipo de usuário inválido.";
            header('Location: ../view/cadastrar.html');
            exit();
        }

        // 3. PROCESSAMENTO: Se os dados são válidos, continua com o cadastro

        // Cria um novo objeto Usuario
        $usuario = new Usuario();

        // Define as propriedades do objeto com os dados do formulário
        $usuario->setNome($nome);
        $usuario->setEmail($email);
        $usuario->setSenha($senha);
        $usuario->setTipoUsuario($tipo_usuario);

        // Tenta cadastrar o usuário no banco de dados
        if ($usuario->cadastrar()) {
            // Se o cadastro for bem-sucedido, redireciona para a página de login com uma mensagem de sucesso
            $_SESSION['mensagem_sucesso'] = "Cadastro realizado com sucesso! Faça o login.";
            header('Location: ../view/login.html'); // Mude para a sua página de login
            exit();
        } else {
            // Se o cadastro falhar (ex: email já existe), define uma mensagem de erro
            $_SESSION['mensagem_erro'] = "Não foi possível realizar o cadastro. O e-mail já pode estar em uso.";
            header('Location: ../view/cadastrar.html');
            exit();
        }
    } else {
        // Se algum campo estiver faltando, define uma mensagem de erro e redireciona
        $_SESSION['mensagem_erro'] = "Por favor, preencha todos os campos.";
        header('Location: ../view/cadastrar.html');
        exit();
    }
} else {
    // Se alguém tentar acessar este arquivo diretamente sem enviar o formulário, redireciona
    header('Location: ../view/cadastrar.html');
    exit();
}
