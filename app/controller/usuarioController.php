<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../model/usuarioModel.php';

class UsuarioController
{
    private $controle;
    public function __construct()
    {
        $this->controle = new UsuarioModel();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        include '../view/cadastrar.php';
        exit();
    }

    public function logar()
    {
        include '../view/login.php';
        exit();
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: abc.php?action=logar');
        exit();
    }
    public function excluirUsuario()
    {
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito!'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        $id = $_GET['id'] ?? null;
        $tipo_retorno = $_GET['type'] ?? 'gerenciarProfissionais';

        if ($id) {
            $this->controle->excluirUsuario($id);
        }

        if ($tipo_retorno === 'cliente') {
            header('Location: abc.php?action=gerenciarClientes');
        } else {
            header('Location: abc.php?action=gerenciarProfissionais');
        }
        exit();
    }


    public function editarUsuario()
    {
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito!'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $_SESSION['editar_id'] = $id;
            include '../view/editarUsuario.php';
            exit();
        } else {
            header('Location: abc.php?action=admin');
            exit();
        }
    }

    public function cadastrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome  = $_POST['nome'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $email = $_POST['email'];
            $tipo  = $_POST['tipo_usuario'];
            $cpf   = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;

            $usuarioExistente = $this->controle->buscarPorEmail($email);

            if ($usuarioExistente) {
                echo "<script>alert('Este e-mail já está em uso. Por favor, utilize outro ou faça login.');
                  window.history.back();</script>";
                exit();
            }

            if ($tipo === 'cliente' || $tipo === 'profissional') {
                $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, $cpf);
            } else if ($tipo === 'admin') {
                $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, null);
            } else {
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

    public function showPublicProfile()
    {
        $id_usuario = $_GET['id'] ?? null;

        if (!$id_usuario || !filter_var($id_usuario, FILTER_VALIDATE_INT)) {
            http_response_code(404);
            echo "Perfil não encontrado.";
            exit();
        }

        $profissional_data = $this->controle->getProfissionalData($id_usuario);

        if (!$profissional_data) {
            http_response_code(404);
            echo "Perfil de profissional não encontrado.";
            exit();
        }

        $id_profissional = $profissional_data['id_profissional'];
        $nome = $profissional_data['nome'] ?? 'Nome não encontrado';
        $foto_perfil = $profissional_data['foto_perfil'] ?? 'view/img/profile-placeholder.jpg';
        $biografia = $profissional_data['biografia'] ?? '';
        $localizacao = $profissional_data['localizacao'] ?? '';

        $especialidades = $this->controle->getProfissionalEspecialidades($id_profissional);
        $portfolio_imagens = $this->controle->getPortfolioItems($id_profissional);

        require_once '../view/perfil.php';
    }
    
    public function searchProfissionais()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Content-Type: application/json');
            echo json_encode([]); 
            exit();
        }

        $term = $_GET['term'] ?? '';
        $resultados = $this->controle->searchProfissionais($term);
        header('Content-Type: application/json');
        echo json_encode($resultados);
        exit();
    }

    public function showAreaProfissional()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_profissional = $_SESSION['profissional_id'];

        $profissional_data = $this->controle->getProfissionalData($id_usuario);

        if (!$profissional_data) {
            echo "<script>alert('Erro ao carregar dados do profissional.'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        $nome = $profissional_data['nome'] ?? 'Nome não encontrado';
        $foto_perfil = $profissional_data['foto_perfil'] ?? 'view/img/profile-placeholder.jpg';
        $biografia = $profissional_data['biografia'] ?? '';
        $localizacao = $profissional_data['localizacao'] ?? '';
        
        // Adicionado para dividir a localização em cidade e estado para o modal
        $cidade_usuario = '';
        $estado_usuario = '';
        if ($localizacao) {
            $partes = explode(',', $localizacao);
            $cidade_usuario = trim($partes[0]);
            $estado_usuario = isset($partes[1]) ? trim($partes[1]) : '';
        }

        $especialidades = $this->controle->getProfissionalEspecialidades($id_profissional);
        $portfolio_imagens = $this->controle->getPortfolioItems($id_profissional);

        $todas_especialidades = $this->controle->getAllEspecialidades();
        $todos_servicos = $this->controle->getAllServicos();

        require_once '../view/areaProfissional.php';
    }

    public function showAreaCliente()
    {
        $profissionais = [];
        $especialidade_buscada = '';
        require_once '../view/areaCliente.php';
    }

    public function showProfissionaisPorEspecialidade()
    {
        $especialidade = $_GET['especialidade'] ?? null;
        if (!$especialidade) {
            header('Location: abc.php?action=areaCliente');
            exit();
        }
        $profissionais = $this->controle->buscarPorEspecialidade($especialidade);
        $especialidade_buscada = $especialidade; 

        require_once '../view/areaCliente.php';
    }

    public function autenticar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $login = $_POST['nome'] ?? ''; 
            $senhaDigitada = $_POST['senha'];

            $usuario = $this->controle->validar($login, $senhaDigitada);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];

                if ($usuario['tipo_usuario'] === 'profissional') {
                    $profissional = $this->controle->buscarProfissionalPorUsuarioId($usuario['id_usuario']);

                    if ($profissional) {
                        $_SESSION['profissional_id'] = $profissional['id_profissional'];
                    } else {
                        session_destroy();
                        echo "<script>alert('Erro de configuração da conta profissional. Contate o suporte.');
                          window.location.href='abc.php?action=logar';</script>";
                        exit();
                    }
                }

                if ($usuario['tipo_usuario'] === 'profissional') {
                    header('Location: abc.php?action=areaProfissional');
                } elseif ($usuario['tipo_usuario'] === 'cliente') {
                    header('Location: abc.php?action=areaCliente');
                } elseif ($usuario['tipo_usuario'] === 'admin') {
                    header('Location: abc.php?action=admin');
                }
                exit();
            } else {
                echo "<script>alert('Login ou senha incorretos.');
                  window.location.href='abc.php?action=logar';</script>";
                exit();
            }
        } else {
            header('Location: abc.php?action=logar');
            exit();
        }
    }

    public function alterarSenha()
    {
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

    public function areaAdmin()
    {
        if ($_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito ao administrador.');
        window.location.href='abc.php?action=logar';</script>";
            exit();
        }
        $usuarios = $this->controle->buscarTodosProfissionais();
        include '../view/areaAdmin.php';
    }

    public function excluirProfissional($idUsuario)
    {
        if ($_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito ao administrador.');
        window.location.href='abc.php?action=logar';</script>";
            exit();
        }
        $this->controle->excluirUsuario($idUsuario);
        header('Location: abc.php?action=areaAdmin');
    }

    public function updateProfile()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
        header('Location: abc.php?action=logar');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: abc.php?action=areaProfissional&status=error');
        exit();
    }

    $id_usuario = $_SESSION['usuario_id'];
    $id_profissional = $_SESSION['profissional_id'];
    $nome = $_POST['nome'];
    
    // Combina cidade e estado para formar a localização
    $cidade = $_POST['cidade'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $localizacao = '';
    if ($cidade && $estado) {
        $localizacao = $cidade . ', ' . $estado;
    }

    $biografia = $_POST['biografia'];
    $especialidades = $_POST['especialidades'] ?? [];

    $caminho_foto = null;
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        
        // --- CORREÇÃO DEFINITIVA DO CAMINHO DE UPLOAD ---
        // Este caminho agora aponta corretamente para a pasta 'public' na raiz do projeto
        $upload_dir = __DIR__ . '/../../public/uploads/profiles/';
        
        if (!is_dir($upload_dir)) {
            // O 'true' no final garante que a estrutura de diretórios seja criada se não existir
            mkdir($upload_dir, 0775, true); 
        }
        
        $nome_arquivo = uniqid() . '-' . basename($_FILES['foto_perfil']['name']);
        $caminho_completo = $upload_dir . $nome_arquivo;

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_completo)) {
            // O caminho salvo no banco deve ser relativo à raiz do projeto
            $caminho_foto = 'public/uploads/profiles/' . $nome_arquivo;
        }
    }

    $model = new UsuarioModel();
    $sucesso = $model->updateProfissional($id_usuario, $id_profissional, $nome, $localizacao, $biografia, $caminho_foto, $especialidades);

    if ($sucesso) {
        $_SESSION['usuario_nome'] = $nome;
        header('Location: abc.php?action=areaProfissional&status=success');
    } else {
        header('Location: abc.php?action=areaProfissional&status=dberror');
    }
    exit();
}


    public function uploadFotoPortfolio()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['profissional_id'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_profissional = $_SESSION['profissional_id'];
        $titulo = trim($_POST['titulo']);
        $descricao = trim($_POST['descricao']) ?? '';
        $id_servico = $_POST['id_servico'] ?? null;
        $arquivo = $_FILES['arquivo_foto'];

        if (empty($titulo)) {
            header('Location: abc.php?action=areaProfissional&status=missing_title');
            exit();
        }

        if (isset($arquivo) && $arquivo['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/portfolio/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0775, true);
            }

            $tamanho_maximo = 5 * 1024 * 1024;
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if ($arquivo['size'] > $tamanho_maximo) {
                header('Location: abc.php?action=areaProfissional&status=file_too_large');
                exit();
            }

            $tipo_real = mime_content_type($arquivo['tmp_name']);
            if (!in_array($tipo_real, $tipos_permitidos)) {
                header('Location: abc.php?action=areaProfissional&status=invalid_file_type');
                exit();
            }

            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_arquivo_novo = uniqid('portfolio_', true) . '.' . $extensao;
            $caminho_completo = $upload_dir . $nome_arquivo_novo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
                $caminho_db = 'public/uploads/portfolio/' . $nome_arquivo_novo;

                $sucesso = $this->controle->addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_db, 'foto');

                if ($sucesso) {
                    header('Location: abc.php?action=areaProfissional&status=upload_success');
                } else {
                    header('Location: abc.php?action=areaProfissional&status=dberror');
                }
            } else {
                header('Location: abc.php?action=areaProfissional&status=upload_fail');
            }
        } else {
            header('Location: abc.php?action=areaProfissional&status=file_error');
        }
        exit();
    }
    public function excluirMinhaConta()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];

        $sucesso = $this->controle->excluirUsuario($id_usuario);

        if ($sucesso) {
            $_SESSION = [];
            session_destroy();

            echo "<script>alert('Sua conta foi excluída com sucesso.');
                  window.location.href='abc.php?action=logar';</script>";
        } else {
            echo "<script>alert('Ocorreu um erro ao tentar excluir sua conta. Tente novamente.');
                  window.location.href='abc.php?action=areaProfissional';</script>";
        }
        exit();
    }

    public function deletePortfolioItem()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['profissional_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_item = $_GET['id'] ?? null;
        $id_profissional = $_SESSION['profissional_id'];

        if (!$id_item) {
            header('Location: abc.php?action=areaProfissional&status=invalidid');
            exit();
        }

        $model = new UsuarioModel();
        $caminho_arquivo = $model->deletePortfolioItem($id_item, $id_profissional);

        if ($caminho_arquivo) {
            $caminho_completo = __DIR__ . '/../' . $caminho_arquivo;
            if (file_exists($caminho_completo)) {
                unlink($caminho_completo);
            }
            header('Location: abc.php?action=areaProfissional&status=deleted');
        } else {
            header('Location: abc.php?action=areaProfissional&status=notfound');
        }
        exit();
    }
}
?>