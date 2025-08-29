<?php
    require_once '../model/usuarioModel.php';

    class UsuarioController{
        private $controle;

        public function __construct(){
            $this->controle = new UsuarioModel();
            
        }
        public function index(){
            include '../view/cadastrar.php';
            exit();
        }
        public function logar(){
            include '../view/login.php';
            exit();
        }

        public function logout() {
            if (session_start() === PHP_SESSION_NONE){
                session_start();        
            }
            session_destroy();
            header('Location: abc.php?action=abc');

        }
        public function cadastrar(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $nome = $_POST['nome'];
                $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $email = $_POST['email'];
                $tipo = $_POST['tipo'];

                if($tipo === 'profissional' || $tipo === 'cliente') {  
                    $this->controle->inserir($nome, $senha, $email, $tipo);


                    echo "<script>alert('Cadastro realizado com sucesso! Realize o login.');
                    window.location.href='abc.php?action=logar';</script>";
                } else {   
                    echo "<script>alert(`Erro no cadastro de usuário opção '$tipo' inválida !`);
                    window.location.href='abc.php?action=logar';</script>";
                }
            }
        }
        public function autenticar(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $nome = $_POST['nome'];
                $senhaDigitada = $_POST['senha'];

                $usuario = $this->controle->validar($nome, $senhaDigitada);

                    if($usuario){
                        $_SESSION['usuario_id'] = $usuario['idUsuario'];
                        $_SESSION['usuario_nome'] = $usuario['nome'];
                        $_SESSION['usuario_tipo'] = $usuario['descricao'];

                        if($usuario['descricao'] === 'profissional'){
                            //echo "Você validou seu login como professor!";
                            header('Location: abc.php?action=areaProfissional');
                            exit();
                        } elseif ($usuario['descricao'] === 'cliente') {
                            //echo "Você validou seu login como aluno!";
                            header('Location: abc.php?action=areaCliente');
                            exit();
                        } else {
                            echo "<script>alert('Tipo de usuário inválido.');
                            window.location.href='abc.php?action=logar'</script>";
                        }
                    } else {
                        echo "<script>alert('Login ou senha incorretos.');
                        window.location.href='abc.php?action=logar'</script>";
                    }
            }else{
                header('Location: abc.php?action=logar');
                exit();
            }
        }

    }

?>