<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../model/usuarioModel.php';

class UsuarioController {
    private $controle;
    public function __construct() {
        $this->controle = new UsuarioModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        include '../view/cadastrar.php';
        exit();
    }

    public function logar() {
        include '../view/login.php';
        exit();
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: abc.php?action=logar');
        exit();
    }

    public function cadastrar() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome  = $_POST['nome'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $tipo  = $_POST['tipo_usuario'];
        $cpf   = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;

        // Se for cliente ou profissional → precisa de CPF
        if ($tipo === 'cliente' || $tipo === 'profissional') {
            $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, $cpf);
        } 
        // Se for admin → não precisa de CPF
        else if ($tipo === 'admin') {
            $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, null);
        } 
        // Qualquer outro tipo é inválido
        else {
            echo "<script>alert('Tipo de usuário inválido!');
            window.location.href='abc.php?action=index';</script>";
            exit();
        }

        if ($inserido) {
            echo "<script>alert('Cadastro realizado com sucesso! Realize o login.');
            window.location.href='abc.php?action=logar';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar usuário. Verifique os dados e tente novamente.');
            window.location.href='abc.php?action=index';</script>";
        }
    } else {
        header('Location: abc.php?action=index');
        exit();
    }
}


    public function teste() {
    echo "Entrou no index<br>";
    if (file_exists('../view/cadastrar.php')) {
        echo "Arquivo existe!<br>";
    } else {
        echo "Arquivo NÃO encontrado!<br>";
    }
    exit();
}

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = $_POST['nome'];
            $senhaDigitada = $_POST['senha'];

            $usuario = $this->controle->validar($nome, $senhaDigitada);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];

                if ($usuario['tipo_usuario'] === 'profissional') {
                    header('Location: abc.php?action=areaProfissional');
                } elseif ($usuario['tipo_usuario'] === 'cliente') {
                    header('Location: abc.php?action=areaCliente');
                } elseif ($usuario['tipo_usuario'] === 'admin') {
                    header('Location: abc.php?action=admin');
                }
                 else {
                    echo "<script>alert('Tipo de usuário inválido.');
                    window.location.href='abc.php?action=logar';</script>";
                }
                exit();
            } else {
                echo "<script>alert('Login ou senha incorretos.');
                window.location.href='abc.php?action=logar';</script>";
            }
        } else {
            header('Location: abc.php?action=logar');
            exit();
        }
    }

    public function alterarSenha() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idUsuario = $_SESSION['usuario_id'];
            $senhaAtual = $_POST['senha_atual'];
            $novaSenha = $_POST['nova_senha'];
            $confirmaSenha = $_POST['confirma_senha'];

            if ($novaSenha !== $confirmaSenha) {
                echo "<script>alert('As senhas não coincidem.');
                window.location.href='abc.php?action=formAlterarSenha';</script>";
                exit;
            }

            if ($this->controle->verificarSenha($idUsuario, $senhaAtual)) {
                if ($this->controle->alterarSenha($idUsuario, $novaSenha)) {
                    echo "<script>alert('Senha alterada com sucesso!');
                    window.location.href='abc.php?action=logar';</script>";
                } else {
                    echo "<script>alert('Erro ao alterar a senha.');
                    window.location.href='abc.php?action=formAlterarSenha';</script>";
                }
            } else {
                echo "<script>alert('Senha atual incorreta.');
                window.location.href='abc.php?action=formAlterarSenha';</script>";
            }
        } else {
            include '../view/alterarSenha.php';
            exit;
        }
    }

    public function areaAdmin() {
    if ($_SESSION['usuario_tipo'] !== 'admin') {
        echo "<script>alert('Acesso restrito ao administrador.');
        window.location.href='abc.php?action=logar';</script>";
        exit();
    }
    $usuarios = $this->controle->buscarTodosProfissionais();
    include '../view/areaAdmin.php';
}

public function excluirProfissional($idUsuario) {
    if ($_SESSION['usuario_tipo'] !== 'admin') {
        echo "<script>alert('Acesso restrito ao administrador.');
        window.location.href='abc.php?action=logar';</script>";
        exit();
    }
    $this->controle->excluirUsuario($idUsuario);
    header('Location: abc.php?action=areaAdmin');
}

}
?>