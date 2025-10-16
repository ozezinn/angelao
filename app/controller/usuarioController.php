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
     public function excluirUsuario() {
        // Verificação de segurança: Apenas admins podem excluir
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito!'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        $id = $_GET['id'] ?? null;
        $tipo_retorno = $_GET['type'] ?? 'gerenciarProfissionais'; // Para saber para onde voltar

        if ($id) {
            $this->controle->excluirUsuario($id); // Usa a instância do model já criada no construtor
        }

        // Redireciona para a página de gerenciamento correta
        if ($tipo_retorno === 'cliente') {
            header('Location: abc.php?action=gerenciarClientes');
        } else {
            header('Location: abc.php?action=gerenciarProfissionais');
        }
        exit();
    }

    
    public function editarUsuario() {
        // Verificação de segurança
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            echo "<script>alert('Acesso restrito!'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            $_SESSION['editar_id'] = $id;
            // Assumindo que você tem um formulário de edição em 'editarUsuario.php'
            include '../view/editarUsuario.php'; 
            exit();
        } else {
            // Se não houver ID, volta para o painel de admin
            header('Location: abc.php?action=admin');
            exit();
        }
    }

    public function cadastrar() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome  = $_POST['nome'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $tipo  = $_POST['tipo_usuario'];
        $cpf   = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;

        // ---> INÍCIO DA VERIFICAÇÃO DE E-MAIL <---
        $usuarioExistente = $this->controle->buscarPorEmail($email);

        if ($usuarioExistente) {
            // Se o e-mail já existe, exibe um alerta e interrompe o script
            echo "<script>alert('Este e-mail já está em uso. Por favor, utilize outro ou faça login.');
                  window.history.back();</script>"; // Volta para a página do formulário
            exit();
        }
        // ---> FIM DA VERIFICAÇÃO DE E-MAIL <---

        // Se o e-mail não existe, o código continua normalmente
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


    public function teste() {
    echo "Entrou no index<br>";
    if (file_exists('../view/cadastrar.php')) {
        echo "Arquivo existe!<br>";
    } else {
        echo "Arquivo NÃO encontrado!<br>";
    }
    exit();
}
 public function showAreaProfissional() {
        // Garante que apenas um profissional logado possa acessar
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
            header('Location: abc.php?action=logar');
            exit();
        }

        // Busca todos os dados necessários usando o Model
        $id_usuario = $_SESSION['usuario_id'];
        $id_profissional = $_SESSION['profissional_id'];

        $profissional_data = $this->controle->getProfissionalData($id_usuario);
        
        // Se por algum motivo não encontrar os dados, redireciona
        if (!$profissional_data) {
             echo "<script>alert('Erro ao carregar dados do profissional.'); window.location.href='abc.php?action=logar';</script>";
             exit();
        }
        
        $nome = $profissional_data['nome'] ?? 'Nome não encontrado';
$foto_perfil = $profissional_data['foto_perfil'] ?? 'view/img/profile-placeholder.jpg';
$biografia = $profissional_data['biografia'] ?? ''; // Garante uma string vazia se for null
$localizacao = $profissional_data['localizacao'] ?? ''; // Garante uma string vazia se for null

        // Busca os dados relacionados
        $especialidades = $this->controle->getProfissionalEspecialidades($id_profissional);
        $portfolio_imagens = $this->controle->getPortfolioItems($id_profissional);
        
        // Busca os catálogos para os modais
        $todas_especialidades = $this->controle->getAllEspecialidades();
        $todos_servicos = $this->controle->getAllServicos();

        // Finalmente, carrega a view. Todas as variáveis acima estarão disponíveis nela.
        require_once '../view/areaProfissional.php';
    }

public function autenticar() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Usei 'email' para a busca, que é mais seguro e padrão do que 'nome'.
        // Se seu formulário de login envia 'nome', considere mudar para 'email'.
        // Se quiser manter o login por 'nome', troque a variável e o método no model.
        $email = $_POST['email']; 
        $senhaDigitada = $_POST['senha'];

        // Sugestão: Crie um método "buscarPorEmail" no seu Model.
        // Por agora, vou adaptar para o seu método "validar" que usa 'nome'.
        // ATENÇÃO: Login por nome pode ter problemas se existirem nomes duplicados.
        $nome = $_POST['nome'] ?? $_POST['email']; // Supondo que o campo possa ser nome ou email
        $usuario = $this->controle->validar($nome, $senhaDigitada);

        if ($usuario) {
            // Sessão iniciada no construtor, apenas preenchemos os dados
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];

            // ======================================================
            // LÓGICA CORRIGIDA E ADICIONADA AQUI
            // ======================================================
            if ($usuario['tipo_usuario'] === 'profissional') {
                // Busca o ID do profissional correspondente
                $profissional = $this->controle->buscarProfissionalPorUsuarioId($usuario['id_usuario']);
                
                if ($profissional) {
                    // Salva o id_profissional na sessão
                    $_SESSION['profissional_id'] = $profissional['id_profissional'];
                } else {
                    // Erro crítico: Usuário é profissional mas não tem perfil.
                    // Isso indica um problema de dados. É mais seguro deslogar.
                    session_destroy();
                    echo "<script>alert('Erro de configuração da conta profissional. Contate o suporte.');
                          window.location.href='abc.php?action=logar';</script>";
                    exit();
                }
            }
            // ======================================================

            // Redirecionamento após salvar todos os dados necessários
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
            exit(); // Adicionado exit() para garantir que o script pare
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
 public function updateProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        

        // 1. VERIFICAÇÃO DE SEGURANÇA: Garante que apenas um profissional logado pode fazer isso
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
            header('Location: ' . BASE_URL . 'abc.php?action=logar');
            exit();
        }
        
        // 2. VERIFICAÇÃO DO MÉTODO: Apenas aceita requisições POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=error');
            exit();
        }

        // 3. COLETA DE DADOS DO FORMULÁRIO
        $id_usuario = $_SESSION['usuario_id'];
        $id_profissional = $_SESSION['profissional_id']; // Assumindo que você salva o id_profissional na sessão
        $nome = $_POST['nome'];
        $localizacao = $_POST['localizacao'];
        $biografia = $_POST['biografia'];
        $especialidades = $_POST['especialidades'] ?? []; // Array de especialidades selecionadas

        // 4. LÓGICA DE UPLOAD DA FOTO DE PERFIL
        $caminho_foto = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../public/uploads/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $nome_arquivo = uniqid() . '-' . basename($_FILES['foto_perfil']['name']);
            $caminho_completo = $upload_dir . $nome_arquivo;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_completo)) {
                $caminho_foto = 'public/uploads/profiles/' . $nome_arquivo;
            }
        }

        // 5. CHAMA O MODEL PARA ATUALIZAR O BANCO DE DADOS
        $model = new UsuarioModel();
        $sucesso = $model->updateProfissional($id_usuario, $id_profissional, $nome, $localizacao, $biografia, $caminho_foto, $especialidades);

        // 6. REDIRECIONA O USUÁRIO
        if ($sucesso) {
            // Atualiza o nome na sessão, caso tenha mudado
            $_SESSION['usuario_nome'] = $nome;
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=success');
        } else {
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=dberror');
        }
        exit();
    }

     public function uploadFotoPortfolio() {
        // 1. VERIFICAÇÃO DE SEGURANÇA E SESSÃO
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['profissional_id'])) {
            header('Location: ' . BASE_URL . 'abc.php?action=logar');
            exit();
        }

        // 2. COLETA DE DADOS DO FORMULÁRIO
        $id_profissional = $_SESSION['profissional_id'];
        $titulo = trim($_POST['titulo']);
        $descricao = trim($_POST['descricao']) ?? '';
        $id_servico = $_POST['id_servico'] ?? null;
        $arquivo = $_FILES['arquivo_foto'];

        // Validação básica dos campos de texto
        if (empty($titulo)) {
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=missing_title');
            exit();
        }

        // 3. VALIDAÇÃO E PROCESSAMENTO DO ARQUIVO
        if (isset($arquivo) && $arquivo['error'] === UPLOAD_ERR_OK) {
            // Define o diretório de uploads
            $upload_dir = __DIR__ . '/../public/uploads/portfolio/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0775, true); // Cria o diretório se não existir
            }

            // Validações de segurança do arquivo
            $tamanho_maximo = 5 * 1024 * 1024; // 5 MB
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if ($arquivo['size'] > $tamanho_maximo) {
                header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=file_too_large');
                exit();
            }
            
            $tipo_real = mime_content_type($arquivo['tmp_name']);
            if (!in_array($tipo_real, $tipos_permitidos)) {
                header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=invalid_file_type');
                exit();
            }

            // Cria um nome de arquivo único para evitar sobreposições
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_arquivo_novo = uniqid('portfolio_', true) . '.' . $extensao;
            $caminho_completo = $upload_dir . $nome_arquivo_novo;

            // 4. MOVE O ARQUIVO PARA O DIRETÓRIO PERMANENTE
            if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
                $caminho_db = 'public/uploads/portfolio/' . $nome_arquivo_novo;

                // 5. CHAMA O MODEL PARA SALVAR NO BANCO DE DADOS
                $sucesso = $this->controle->addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_db);

                if ($sucesso) {
                    header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=upload_success');
                } else {
                    header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=dberror');
                }
            } else {
                header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=upload_fail');
            }
        } else {
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=file_error');
        }
        exit();
       // 5. CHAMA O MODEL PARA SALVAR NO BANCO DE DADOS
    // ======================================================
    // MUDANÇA AQUI: Adicionamos 'foto' como o último parâmetro
    // ======================================================
    $sucesso = $this->controle->addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_db, 'foto');

    if ($sucesso) {
        header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=upload_success');
    } else {
        header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=dberror');
    }
        
    }
    public function excluirMinhaConta() {
        // 1. VERIFICAÇÃO DE SEGURANÇA: Garante que há um usuário logado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        // 2. PEGA O ID DA SESSÃO (MAIS SEGURO)
        $id_usuario = $_SESSION['usuario_id'];

        // 3. CHAMA O MODEL PARA EXCLUIR O USUÁRIO
        // Podemos reutilizar o método que o admin usa, pois ele faz a mesma coisa.
        $sucesso = $this->controle->excluirUsuario($id_usuario);

        if ($sucesso) {
            // 4. LIMPEZA TOTAL: Destrói a sessão e todos os seus dados
            $_SESSION = [];
            session_destroy();

            // 5. REDIRECIONA para a página de login com uma mensagem de sucesso
            echo "<script>alert('Sua conta foi excluída com sucesso.');
                  window.location.href='abc.php?action=logar';</script>";
        } else {
            // Se falhou, informa o erro e redireciona de volta
            echo "<script>alert('Ocorreu um erro ao tentar excluir sua conta. Tente novamente.');
                  window.location.href='abc.php?action=areaProfissional';</script>"; // ou areaCliente
        }
        exit();
    }

    /**
     * Exclui um item do portfólio.
     */
    public function deletePortfolioItem() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. VERIFICAÇÃO DE SEGURANÇA
        if (!isset($_SESSION['profissional_id']) || $_SESSION['usuario_tipo'] !== 'profissional') {
            header('Location: ' . BASE_URL . 'abc.php?action=logar');
            exit();
        }

        // 2. COLETA DE DADOS
        $id_item = $_GET['id'] ?? null;
        $id_profissional = $_SESSION['profissional_id'];

        if (!$id_item) {
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=invalidid');
            exit();
        }

        // 3. CHAMA O MODEL PARA EXCLUIR O ITEM
        $model = new UsuarioModel();
        $caminho_arquivo = $model->deletePortfolioItem($id_item, $id_profissional);

        // 4. EXCLUI O ARQUIVO FÍSICO DO SERVIDOR
        if ($caminho_arquivo) {
            $caminho_completo = __DIR__ . '/../' . $caminho_arquivo;
            if (file_exists($caminho_completo)) {
                unlink($caminho_completo);
            }
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=deleted');
        } else {
            // O item não foi encontrado ou não pertencia ao profissional
            header('Location: ' . BASE_URL . 'abc.php?action=areaProfissional&status=notfound');
        }
        exit();
    }
    

}
?>