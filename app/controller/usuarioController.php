<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../model/usuarioModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
try {
    // Aponta para o diretório raiz (onde está o composer.json)
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();
} catch (Exception $e) {
    // Isso acontece se o .env não existir
    error_log("Aviso: Nao foi possivel carregar o arquivo .env: " . $e->getMessage());
}

class UsuarioController
{
    private $controle;
    private $db;

    // Modifique o construtor para receber o PDO
    public function __construct($pdo)
    {
        $this->db = $pdo; // Guarde a conexão
        // Passe a conexão ao criar o model
        $this->controle = new UsuarioModel($this->db);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ... (outras funções como index, logar, logout, etc. permanecem iguais) ...
    public function index()
    {
        if (isset($_SESSION['usuario_id'])) {
            $tipo = $_SESSION['usuario_tipo'] ?? 'cliente';
            switch ($tipo) {
                case 'admin':
                    header('Location: abc.php?action=admin');
                    break;
                case 'profissional':
                    header('Location: abc.php?action=areaProfissional');
                    break;
                case 'cliente':
                default:
                    header('Location: abc.php?action=areaCliente');
                    break;
            }
            exit();
        }
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
            $nome = $_POST['nome'];
            $senha_raw = $_POST['senha']; // <-- Pegar a senha crua
            $email = $_POST['email'];
            $tipo = $_POST['tipo_usuario'];
            $cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : null;

            // --- INÍCIO DA VALIDAÇÃO DE SENHA ---
            $erros_senha = [];
            if (strlen($senha_raw) < 8) {
                $erros_senha[] = "pelo menos 8 caracteres";
            }
            if (!preg_match('/[A-Z]/', $senha_raw)) {
                $erros_senha[] = "pelo menos uma letra maiúscula";
            }
            if (!preg_match('/[a-z]/', $senha_raw)) {
                $erros_senha[] = "pelo menos uma letra minúscula";
            }
            if (!preg_match('/[0-9]/', $senha_raw)) {
                $erros_senha[] = "pelo menos um número";
            }

            if (!empty($erros_senha)) {
                // A senha é fraca. Rejeita o cadastro e volta para a view.
                $tipo_param = !empty($tipo) ? '&tipo=' . urlencode($tipo) : '';
                // Redireciona de volta para a PÁGINA de cadastro com um status de erro
                header('Location: ../view/cadastrar.php?status=weak_password' . $tipo_param);
                exit();
            }
            // --- FIM DA VALIDAÇÃO DE SENHA ---

            // --- INÍCIO DA VALIDAÇÃO DE TERMOS ---
            if (!isset($_POST['termos'])) {
                $tipo_param = !empty($tipo) ? '&tipo=' . urlencode($tipo) : '';
                // Redireciona de volta com um erro específico de termos
                header('Location: ../view/cadastrar.php?status=terms_required' . $tipo_param);
                exit();
            }
            // --- FIM DA VALIDAÇÃO DE TERMOS ---

            // Se a senha for forte, CRIE O HASH
            $senha = password_hash($senha_raw, PASSWORD_DEFAULT); // <-- Hash criado aqui

            $usuarioExistente = $this->controle->buscarPorEmail($email);

            if ($usuarioExistente) {
                // ALTERADO: Redireciona com status de erro para a página de login
                header('Location: ../view/login.php?status=email_exists');
                exit();
            }

            if ($tipo === 'cliente' || $tipo === 'profissional') {
                $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, $cpf); // Usa $senha (hash)
            } else if ($tipo === 'admin') {
                $inserido = $this->controle->inserir($nome, $senha, $email, $tipo, null); // Usa $senha (hash)
            } else {
                // Redireciona com erro se o tipo for inválido
                header('Location: abc.php?action=index&status=invalid_type');
                exit();
            }

            if ($inserido) {
                // ALTERADO: Redireciona com status de sucesso para a página de login
                header('Location: ../view/login.php?status=registered');
            } else {
                // ALTERADO: Redireciona com status de erro genérico
                header('Location: abc.php?action=index&status=dberror');
            }
            exit();
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
            echo "Perfil não encontrado (ID inválido).";
            exit();
        }

        // 1. Busca os dados base (nome, bio, etc.)
        $profissional_data = $this->controle->getProfissionalData($id_usuario);

        if (!$profissional_data) {
            http_response_code(404);
            echo "Perfil de profissional não encontrado.";
            exit();
        }

        $id_profissional = $profissional_data['id_profissional'];

        // 2. Desempacota os dados base
        $nome = $profissional_data['nome'] ?? 'Nome não encontrado';
        $foto_perfil = $profissional_data['foto_perfil'] ?? 'view/img/profile-placeholder.jpg';
        $biografia = $profissional_data['biografia'] ?? 'Nenhuma biografia fornecida.';
        $localizacao = $profissional_data['localizacao'] ?? 'Localização não informada';

        // ==================================================================
        // AQUI ESTÁ A CORREÇÃO PRINCIPAL
        // Precisamos chamar as funções do Model para buscar estes dados.
        // ==================================================================
        $especialidades = $this->controle->getProfissionalEspecialidades($id_profissional);
        $portfolio_imagens = $this->controle->getPortfolioItems($id_profissional);
        $avaliacoes = $this->controle->getAvaliacoesComFotos($id_profissional);
        // ==================================================================

        // 4. Calcula as Estatísticas
        $total_avaliacoes = count($avaliacoes);
        $total_portfolio = count($portfolio_imagens);
        $total_especialidades = count($especialidades);
        $soma_estrelas = 0;
        $media_estrelas = 0;

        if ($total_avaliacoes > 0) {
            foreach ($avaliacoes as $avaliacao) {
                $soma_estrelas += (int) $avaliacao['nota_estrelas'];
            }
            $media_estrelas = round($soma_estrelas / $total_avaliacoes, 1);
        }

        // 5. Envia tudo para a view
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

        // Garante que o ID do profissional está na sessão (força logout se não estiver)
        if (!isset($_SESSION['profissional_id'])) {
            echo "<script>alert('Sua sessão expirou ou é inválida. Por favor, faça login novamente.');
                   window.location.href='abc.php?action=logout';</script>";
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $id_profissional = $_SESSION['profissional_id'];

        // Busca os dados
        $profissional_data = $this->controle->getProfissionalData($id_usuario);

        if (!$profissional_data) {
            echo "<script>alert('Erro ao carregar dados do profissional.'); window.location.href='abc.php?action=logar';</script>";
            exit();
        }

        // Define as variáveis para a view
        $nome = $profissional_data['nome'] ?? 'Nome não encontrado';
        $foto_perfil = $profissional_data['foto_perfil'] ?? 'view/img/profile-placeholder.jpg';
        $biografia = $profissional_data['biografia'] ?? '';
        $localizacao = $profissional_data['localizacao'] ?? '';

        $cidade_usuario = '';
        $estado_usuario = '';
        if ($localizacao) {
            $partes = explode(',', $localizacao);
            $cidade_usuario = trim($partes[0]);
            $estado_usuario = isset($partes[1]) ? trim($partes[1]) : '';
        }

        $especialidades = $this->controle->getProfissionalEspecialidades($id_profissional);
        $portfolio_imagens = $this->controle->getPortfolioItems($id_profissional);
        $todas_especialidades = $this->controle->getAllEspecialidades(); // Para o modal
        $todos_servicos = $this->controle->getAllServicos(); // Para o modal
        $solicitacoes = $this->controle->getSolicitacoesPorProfissional($id_profissional);

        // =============================================================
        // LÓGICA MOVIDA DO TOPO DA VIEW PARA CÁ (A CORREÇÃO)
        // =============================================================
        $abrirModalPerfil = false;
        if (empty(trim($biografia)) || empty(trim($localizacao)) || $foto_perfil === 'view/img/profile-placeholder.jpg') {
            $abrirModalPerfil = true;
        }

        $total_fotos = count($portfolio_imagens);
        $total_solicitacoes = count($solicitacoes);
        // =============================================================

        // Envia tudo para a view
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
            // ... (código existente)
            $login = $_POST['email'] ?? '';
            $senhaDigitada = $_POST['senha'];

            $usuario = $this->controle->validar($login, $senhaDigitada);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
                $_SESSION['usuario_email'] = $usuario['email']; // <-- Adicione esta linha

                if ($usuario['tipo_usuario'] === 'profissional') {
                    $profissional = $this->controle->buscarProfissionalPorUsuarioId($usuario['id_usuario']);

                    if ($profissional) {
                        $_SESSION['profissional_id'] = $profissional['id_profissional'];
                    } else {
                        session_destroy();
                        header('Location: ../view/login.php?status=config_error');
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
                // ALTERADO: Redireciona com status de erro para a página de login
                header('Location: ../view/login.php?status=login_error');
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

        $cidade = $_POST['cidade'] ?? '';
        $estado = $_POST['estado'] ?? '';
        $localizacao = '';
        if ($cidade && $estado) {
            $localizacao = $cidade . ', ' . $estado;
        }

        $biografia = $_POST['biografia'];
        $especialidades = $_POST['especialidades'] ?? [];

        $caminho_foto = null; // Assume que não haverá nova foto por padrão

        // Verifica se um arquivo foi enviado E se não houve erro no upload
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {

            $arquivo = $_FILES['foto_perfil'];
            $tamanho_maximo = 5 * 1024 * 1024; // 5MB em bytes
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $upload_dir = __DIR__ . '/../../public/uploads/profiles/'; // Diretório de destino

            // 1. Validação de Tamanho
            if ($arquivo['size'] > $tamanho_maximo) {
                header('Location: abc.php?action=areaProfissional&status=profile_too_large');
                exit();
            }

            // 2. Validação de Tipo (MIME Type)
            $tipo_real = mime_content_type($arquivo['tmp_name']);
            if (!in_array($tipo_real, $tipos_permitidos)) {
                header('Location: abc.php?action=areaProfissional&status=profile_invalid_type');
                exit();
            }

            // 3. Verificar/Criar Diretório e Permissões
            if (!is_dir($upload_dir)) {
                // Tenta criar o diretório recursivamente
                if (!mkdir($upload_dir, 0775, true)) {
                    // Se falhar ao criar, erro de diretório
                    header('Location: abc.php?action=areaProfissional&status=profile_dir_error');
                    exit();
                }
            } elseif (!is_writable($upload_dir)) {
                // Se o diretório existe mas não temos permissão de escrita
                header('Location: abc.php?action=areaProfissional&status=profile_dir_error');
                exit();
            }

            // 4. Mover o Arquivo
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_arquivo = uniqid('profile_', true) . '.' . $extensao; // Nome único
            $caminho_completo = $upload_dir . $nome_arquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
                // Sucesso! Define o caminho que será salvo no banco
                $caminho_foto = 'public/uploads/profiles/' . $nome_arquivo;
            } else {
                // Falha ao mover o arquivo (pode ser permissão, disco cheio, etc.)
                header('Location: abc.php?action=areaProfissional&status=profile_upload_fail');
                exit();
            }
        } elseif (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Se um arquivo foi enviado mas teve algum erro (diferente de "nenhum arquivo")
            header('Location: abc.php?action=areaProfissional&status=profile_upload_error&code=' . $_FILES['foto_perfil']['error']);
            exit();
        }
        // Se nenhum arquivo foi enviado (UPLOAD_ERR_NO_FILE), $caminho_foto continua null e o processo segue

        // Atualiza o banco de dados (somente se $caminho_foto for definido ou se não houve tentativa de upload)
        // $model = new UsuarioModel(); // <-- COMENTE OU APAGUE ESTA LINHA
        // A função updateProfissional precisa saber lidar com $caminho_foto sendo null (não atualizar o campo)
        $sucesso = $this->controle->updateProfissional($id_usuario, $id_profissional, $nome, $localizacao, $biografia, $caminho_foto, $especialidades);

        if ($sucesso) {
            $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão caso tenha mudado
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
        $id_servico = $_POST['id_servico'] ?? null; // Verifique se selecionou especialidade
        $arquivo = $_FILES['arquivo_foto'];

        // Validação básica dos campos
        if (empty($titulo)) {
            header('Location: abc.php?action=areaProfissional&status=missing_title');
            exit();
        }
        if (empty($id_servico)) {
            header('Location: abc.php?action=areaProfissional&status=missing_service'); // Novo status
            exit();
        }
        if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            // Adiciona tratamento para erros de upload mais genéricos
            $errorCode = $arquivo['error'] ?? 'unknown';
            header('Location: abc.php?action=areaProfissional&status=file_error&code=' . $errorCode);
            exit();
        }

        // --- Processamento do Upload ---
        $upload_dir = __DIR__ . '/../../public/uploads/portfolio/';
        $tamanho_maximo = 5 * 1024 * 1024; // 5MB
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        // 1. Validação de Tamanho
        if ($arquivo['size'] > $tamanho_maximo) {
            header('Location: abc.php?action=areaProfissional&status=file_too_large');
            exit();
        }

        // 2. Validação de Tipo
        $tipo_real = mime_content_type($arquivo['tmp_name']);
        if (!in_array($tipo_real, $tipos_permitidos)) {
            header('Location: abc.php?action=areaProfissional&status=invalid_file_type');
            exit();
        }

        // 3. Verificar/Criar Diretório e Permissões
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0775, true)) {
                header('Location: abc.php?action=areaProfissional&status=portfolio_dir_error'); // Novo status
                exit();
            }
        } elseif (!is_writable($upload_dir)) {
            header('Location: abc.php?action=areaProfissional&status=portfolio_dir_error'); // Novo status
            exit();
        }

        // 4. Mover o Arquivo
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nome_arquivo_novo = uniqid('portfolio_', true) . '.' . $extensao;
        $caminho_completo = $upload_dir . $nome_arquivo_novo;

        if (move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
            $caminho_db = 'public/uploads/portfolio/' . $nome_arquivo_novo;

            // Inserir no banco de dados
            $sucesso = $this->controle->addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_db, 'foto');

            if ($sucesso) {
                header('Location: abc.php?action=areaProfissional&status=upload_success');
            } else {
                // Se falhou no banco, tenta remover o arquivo que foi salvo
                if (file_exists($caminho_completo)) {
                    unlink($caminho_completo);
                }
                header('Location: abc.php?action=areaProfissional&status=dberror');
            }
        } else {
            header('Location: abc.php?action=areaProfissional&status=upload_fail');
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

        if (!$id_item || !filter_var($id_item, FILTER_VALIDATE_INT)) { // Adicionado validação
            header('Location: abc.php?action=areaProfissional&status=invalidid');
            exit();
        }


        $model = $this->controle;
        $caminho_arquivo = $model->deletePortfolioItem($id_item, $id_profissional);

        if ($caminho_arquivo) {
            // Importante: Construir o caminho absoluto corretamente a partir da raiz do projeto
            $caminho_completo = realpath(__DIR__ . '/../../' . $caminho_arquivo);

            if ($caminho_completo && file_exists($caminho_completo)) {
                unlink($caminho_completo); // Tenta deletar o arquivo físico
            }
            header('Location: abc.php?action=areaProfissional&status=deleted');
        } else {
            // Pode ser que o item não exista ou não pertença ao profissional
            header('Location: abc.php?action=areaProfissional&status=notfound');
        }
        exit();
    }

    public function updatePortfolioItem()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verifica se é profissional e se o método é POST
        if (!isset($_SESSION['profissional_id']) || $_SESSION['usuario_tipo'] !== 'profissional' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: abc.php?action=logar');
            exit();
        }

        // Pega os dados do formulário
        $id_item = filter_input(INPUT_POST, 'id_item', FILTER_VALIDATE_INT);
        $id_profissional = $_SESSION['profissional_id']; // Pega da sessão por segurança
        $titulo = trim(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS));
        $descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS)) ?? '';
        $id_servico = filter_input(INPUT_POST, 'id_servico', FILTER_VALIDATE_INT);

        // Validação básica
        if (!$id_item || !$titulo || !$id_servico) {
            header('Location: abc.php?action=areaProfissional&status=update_error'); // Erro genérico
            exit();
        }

        // Chama o Model para atualizar
        $model = $this->controle;
        $sucesso = $model->updatePortfolioItem($id_item, $id_profissional, $titulo, $descricao, $id_servico);

        if ($sucesso) {
            header('Location: abc.php?action=areaProfissional&status=update_success');
        } else {
            header('Location: abc.php?action=areaProfissional&status=update_error');
        }
        exit();
    }

    public function showRecuperarSenha()
    {
        include '../view/recuperarSenha.php';
        exit();
    }
    public function handleRecuperarSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['email'])) {
            header('Location: abc.php?action=recuperarSenha');
            exit();
        }

        $email = $_POST['email'];
        $usuario = $this->controle->buscarPorEmail($email);

        // Mesmo se o email não existir, damos uma resposta genérica
        // para não vazar informação de quais emails estão cadastrados.
        if ($usuario) {
            try {
                // 1. Gerar token seguro
                $token = bin2hex(random_bytes(32)); // Token que vai na URL
                $token_hash = hash('sha256', $token); // Token que vai no BD
                $expires_at = date('Y-m-d H:i:s', time() + 3600); // 1 hora de validade

                // 2. Salvar no banco
                $this->controle->createPasswordResetToken($email, $token_hash, $expires_at);

                $reset_link = "https://www.luumina.online/app/view/abc.php?action=definirNovaSenha&token=" . $token;
                // 4. Montar o corpo do email (HTML)
                $assunto = "Luumina - Redefinicao de Senha";
                $mensagem = "
                    <div style='font-family: Arial, sans-serif; line-height: 1.6;'>
                        <p>Ola,</p>
                        <p>Recebemos uma solicitacao para redefinir sua senha na plataforma Luumina.</p>
                        <p>Clique no botao abaixo para criar uma nova senha. Este link expira em 1 hora:</p>
                        <p style='margin: 25px 0;'>
                            <a href='$reset_link' style='padding: 12px 20px; background-color: #424242; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>
                                Redefinir Senha
                            </a>
                        </p>
                        <p>Se o botao nao funcionar, copie e cole este link no seu navegador:</p>
                        <p style='word-break: break-all;'>$reset_link</p>
                        <hr>
                        <p style='font-size: 0.9em; color: #777;'>Se voce nao solicitou isso, pode ignorar este email com seguranca.</p>
                    </div>
                ";

                // Texto alternativo para clientes de email que não leem HTML
                $mensagem_alt = "Para redefinir sua senha, copie e cole este link no seu navegador: $reset_link";


                // 5. Configurar e enviar o email com PHPMailer
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = $_ENV['SMTP_HOST'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['SMTP_USER'];
                    $mail->Password = $_ENV['SMTP_PASS'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = $_ENV['SMTP_PORT'];
                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);

                    $mail->addAddress($email);

                    $mail->addReplyTo('nao-responda@luumina.com', 'Luumina');

                    // Conteúdo do Email
                    $mail->isHTML(true);
                    $mail->Subject = $assunto;
                    $mail->Body = $mensagem;
                    $mail->AltBody = $mensagem_alt;

                    $mail->send();

                } catch (Exception $e) {
                    error_log("Erro ao ENVIAR email (PHPMailer): " . $mail->ErrorInfo);

                    // PARE A EXECUÇÃO E MOSTRE O ERRO
                    die("FALHA AO ENVIAR EMAIL: " . $mail->ErrorInfo);
                }

            } catch (Exception $e) {
                // Logar o erro de geração de token
                error_log("Erro ao GERAR token de reset: " . $e->getMessage());
            }
        }

        // Redireciona para o login com status de sucesso (resposta genérica)
        header('Location: ../view/login.php?status=reset_sent');
        exit();
    }
    public function showDefinirNovaSenha()
    {
        $token = $_GET['token'] ?? null;
        if (!$token) {
            header('Location: ../view/login.php?status=token_invalid');
            exit();
        }

        $token_hash = hash('sha256', $token);
        $token_data = $this->controle->getResetTokenData($token_hash);

        // Se o token não existe ou expirou
        if (!$token_data) {
            header('Location: ../view/login.php?status=token_invalid');
            exit();
        }

        // Passa o token para a view (que o colocará no form oculto)
        include '../view/definirNovaSenha.php';
        exit();
    }
    public function handleDefinirNovaSenha()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: abc.php?action=logar');
            exit();
        }

        $token = $_POST['token'] ?? null;
        $novaSenha = $_POST['nova_senha'];
        $confirmaSenha = $_POST['confirma_senha'];

        // 1. Validar token
        if (!$token) {
            header('Location: ../view/login.php?status=token_invalid');
            exit();
        }

        $token_hash = hash('sha256', $token);
        $token_data = $this->controle->getResetTokenData($token_hash);

        if (!$token_data) {
            header('Location: ../view/login.php?status=token_invalid');
            exit();
        }

        // 2. Validar senhas
        if ($novaSenha !== $confirmaSenha || empty($novaSenha)) {
            // Redireciona de volta com erro
            header('Location: abc.php?action=definirNovaSenha&token=' . $token . '&status=password_mismatch');
            exit(); // Idealmente, a view 'definirNovaSenha' deveria tratar esse status
        }

        // 3. Tudo OK: Atualizar o banco
        $email = $token_data['email'];
        $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        if ($this->controle->updatePasswordByEmail($email, $novaSenhaHash)) {
            // 4. Sucesso: Deletar o token e redirecionar
            $this->controle->deleteResetToken($email);
            header('Location: ../view/login.php?status=password_updated');
        } else {
            // 5. Erro de banco
            header('Location: ../view/login.php?status=dberror');
        }
        exit();
    }
    public function solicitarOrcamento()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_profissional = filter_input(INPUT_POST, 'id_profissional', FILTER_VALIDATE_INT);
            $nome_solicitante = filter_input(INPUT_POST, 'nome_solicitante', FILTER_SANITIZE_SPECIAL_CHARS);
            $email_solicitante = filter_input(INPUT_POST, 'email_solicitante', FILTER_VALIDATE_EMAIL);
            $telefone_solicitante = filter_input(INPUT_POST, 'telefone_solicitante', FILTER_SANITIZE_SPECIAL_CHARS); // Ajustar validação se necessário
            $tipo_evento = filter_input(INPUT_POST, 'tipo_evento', FILTER_SANITIZE_SPECIAL_CHARS);
            $data_evento = filter_input(INPUT_POST, 'data_evento'); // Validar formato de data se necessário
            $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);
            $id_cliente = $_SESSION['usuario_id'] ?? null; // Já é um INT da sessão ou null
            $id_usuario_redirect = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT); // ID do perfil visitado

            // Verificações básicas
            if (!$id_profissional || !$nome_solicitante || !$email_solicitante || !$mensagem || !$id_usuario_redirect || !$tipo_evento) {
                // Redireciona de volta para o perfil com um erro genérico de dados faltando
                header('Location: abc.php?action=verPerfil&id=' . ($id_usuario_redirect ?? '') . '&status=orcamento_missing_data');
                exit();
            }

            // Validar data se foi fornecida
            if ($data_evento) {
                $dataObj = DateTime::createFromFormat('Y-m-d', $data_evento);
                if (!$dataObj || $dataObj->format('Y-m-d') !== $data_evento || $dataObj < new DateTime('today')) {
                    // Data inválida ou no passado
                    header('Location: abc.php?action=verPerfil&id=' . $id_usuario_redirect . '&status=orcamento_invalid_date');
                    exit();
                }
            } else {
                $data_evento = null; // Garante que seja null se não for fornecida ou inválida
            }


            $inserido = $this->controle->inserirSolicitacaoOrcamento(
                $id_profissional,
                $id_cliente,
                $nome_solicitante,
                $email_solicitante,
                $telefone_solicitante,
                $tipo_evento,
                $data_evento,
                $mensagem
            );

            if ($inserido) {
                header('Location: abc.php?action=verPerfil&id=' . $id_usuario_redirect . '&status=orcamento_success');
            } else {
                header('Location: abc.php?action=verPerfil&id=' . $id_usuario_redirect . '&status=orcamento_error');
            }
            exit();
        } else {
            // Se não for POST, redireciona para a página inicial ou outra apropriada
            header('Location: abc.php');
            exit();
        }
    }

    public function showCaixaDeEntrada()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_usuario = $_SESSION['usuario_id'];
        $tipo_usuario = $_SESSION['usuario_tipo'];
        $conversas = [];

        if ($tipo_usuario === 'profissional') {
            $id_profissional = $_SESSION['profissional_id'];
            $conversas = $this->controle->getSolicitacoesPorProfissional($id_profissional);
        } elseif ($tipo_usuario === 'cliente') {
            $conversas = $this->controle->getSolicitacoesPorCliente($id_usuario);
        }

        // Crie esta nova view
        include '../view/caixaDeEntrada.php';
        exit();
    }

    public function showConversa()
    {
        // 1. Verificação de segurança: O usuário está logado?
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_solicitacao = $_GET['id'] ?? 0;
        // FIX 1: Definir o ID do usuário logado para a view
        $id_usuario_logado = $_SESSION['usuario_id'];

        $solicitacao = $this->controle->buscarSolicitacaoPorId($id_solicitacao);

        if (!$solicitacao) {
            // Se a solicitação não for encontrada
            echo "<script>alert('Conversa não encontrada.'); window.location.href='abc.php?action=minhaCaixaDeEntrada';</script>";
            exit();
        }

        // 2. Lógica de Segurança: O usuário logado pertence a esta conversa?

        // ID do cliente que abriu a solicitação
        $id_cliente_solicitacao = $solicitacao['id_cliente'];

        // ID do profissional que recebeu a solicitação (buscando o id_usuario dele)
        $id_usuario_profissional = $this->controle->getUsuarioIdPorProfissionalId($solicitacao['id_profissional']);

        if ($id_usuario_logado != $id_cliente_solicitacao && $id_usuario_logado != $id_usuario_profissional) {
            // Se o usuário logado NÃO é o cliente E NÃO é o profissional, nega o acesso.
            echo "<script>alert('Acesso negado a esta conversa.'); window.location.href='abc.php?action=minhaCaixaDeEntrada';</script>";
            exit();
        }

        // FIX 2: Definir quem é o destinatário da resposta ($id_outra_pessoa) para a view
        $id_outra_pessoa = 0;
        if ($_SESSION['usuario_tipo'] === 'profissional') {
            // Se o logado é profissional, a outra pessoa é o cliente
            $id_outra_pessoa = $id_cliente_solicitacao;
        } else {
            // Se o logado é cliente, a outra pessoa é o profissional
            $id_outra_pessoa = $id_usuario_profissional;
        }

        // 3. Buscar as mensagens (isto já estava correto)
        $mensagens = $this->controle->buscarMensagensPorSolicitacao($id_solicitacao);

        // ... (Futuramente, aqui pode entrar a lógica para marcar mensagens como 'lida')

        // 4. Incluir a view (agora com $id_usuario_logado e $id_outra_pessoa definidos)
        include '../view/conversa.php';
        exit();
    }
    public function getNovasMensagens()
    {
        if (!isset($_SESSION['usuario_id']) || !isset($_GET['id_solicitacao']) || !isset($_GET['ultimo_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Acesso negado']);
            exit();
        }

        $id_solicitacao = $_GET['id_solicitacao'];
        $ultimo_id = $_GET['ultimo_id'];

        // (Opcional, mas recomendado: Verifique se o usuário logado pertence a esta solicitação)

        $novasMensagens = $this->controle->buscarMensagensDesde($id_solicitacao, $ultimo_id);

        header('Content-Type: application/json');
        echo json_encode($novasMensagens);
        exit();
    }
    public function enviarMensagem()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
            $id_solicitacao = $_POST['id_solicitacao'];
            $id_remetente = $_SESSION['usuario_id'];
            $id_destinatario = $_POST['id_destinatario']; // O formulário na view 'conversa.php' deve incluir isso
            $mensagem = $_POST['mensagem'];

            $this->controle->inserirMensagem($id_solicitacao, $id_remetente, $id_destinatario, $mensagem);

            // Atualiza o status
            if ($_SESSION['usuario_tipo'] === 'profissional') {
                $this->controle->atualizarStatusSolicitacao($id_solicitacao, 'respondido');
            } else {
                $this->controle->atualizarStatusSolicitacao($id_solicitacao, 'em_negociacao');
            }

            // Redireciona de volta para a conversa
            header('Location: abc.php?action=verConversa&id=' . $id_solicitacao);
            exit();
        }
    }
    public function solicitarEncerramento()
    {
        if (!isset($_SESSION['usuario_id']) || !isset($_GET['id']) || !isset($_GET['status'])) {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_solicitacao = $_GET['id'];
        $acao_requerida = $_GET['status']; // 'concluido', 'dispensado', 'confirmar_concluido', 'confirmar_dispensado'
        $id_usuario_logado = $_SESSION['usuario_id'];
        $tipo_usuario = $_SESSION['usuario_tipo'];

        // --- Verificação de segurança: O usuário pertence a esta conversa? ---
        $solicitacao = $this->controle->buscarSolicitacaoPorId($id_solicitacao);
        if (!$solicitacao) {
            header('Location: abc.php?action=minhaCaixaDeEntrada');
            exit();
        }

        $id_cliente = $solicitacao['id_cliente'];
        $id_usuario_profissional = $this->controle->getUsuarioIdPorProfissionalId($solicitacao['id_profissional']);

        if ($id_usuario_logado != $id_cliente && $id_usuario_logado != $id_usuario_profissional) {
            echo "<script>alert('Acesso negado a esta conversa.'); window.location.href='abc.php?action=minhaCaixaDeEntrada';</script>";
            exit();
        }
        // --- Fim da verificação de segurança ---

        $status_atual = $solicitacao['status_solicitacao'];
        $novo_status = $status_atual; // Começa com o status atual

        // Lógica principal de transição de status
        switch ($acao_requerida) {

            // 1. AÇÃO: Marcar como CONCLUÍDO (Início do fluxo)
            case 'concluido':
                if (in_array($status_atual, ['novo', 'respondido', 'em_negociacao'])) {
                    $novo_status = ($tipo_usuario === 'cliente') ? 'concluido_aguardando_prof' : 'concluido_aguardando_cliente';
                }
                break;

            // 2. AÇÃO: Marcar como DISPENSADO (Início do fluxo)
            case 'dispensado':
                if (in_array($status_atual, ['novo', 'respondido', 'em_negociacao'])) {
                    $novo_status = ($tipo_usuario === 'cliente') ? 'dispensado_aguardando_prof' : 'dispensado_aguardando_cliente';
                }
                break;

            // 3. AÇÃO: CONFIRMAR que foi concluído (Fechamento do fluxo)
            case 'confirmar_concluido':
                // Se o profissional estava aguardando e o cliente confirma (ou vice-versa)
                if (
                    ($status_atual === 'concluido_aguardando_cliente' && $tipo_usuario === 'cliente') ||
                    ($status_atual === 'concluido_aguardando_prof' && $tipo_usuario === 'profissional')
                ) {

                    $novo_status = 'finalizado_concluido';
                }
                break;

            // 4. AÇÃO: CONFIRMAR que foi dispensado (Fechamento do fluxo)
            case 'confirmar_dispensado':
                if (
                    ($status_atual === 'dispensado_aguardando_cliente' && $tipo_usuario === 'cliente') ||
                    ($status_atual === 'dispensado_aguardando_prof' && $tipo_usuario === 'profissional')
                ) {

                    $novo_status = 'finalizado_dispensado';
                }
                break;
        }

        // Se o status mudou, atualiza o banco
        if ($novo_status !== $status_atual) {
            $this->controle->atualizarStatusSolicitacao($id_solicitacao, $novo_status);
        }

        // Redireciona de volta para a conversa
        header('Location: abc.php?action=verConversa&id=' . $id_solicitacao);
        exit();
    }
    public function showAvaliacao()
    {
        // 1. Verificações de segurança
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
            header('Location: abc.php?action=logar');
            exit();
        }

        $id_solicitacao = $_GET['id'] ?? 0;
        $id_cliente = $_SESSION['usuario_id'];

        $solicitacao = $this->controle->buscarSolicitacaoPorId($id_solicitacao);

        // 2. Garante que a solicitação existe e pertence a este cliente
        if (!$solicitacao || $solicitacao['id_cliente'] != $id_cliente) {
            echo "<script>alert('Solicitação não encontrada ou não pertence a você.'); window.location.href='abc.php?action=minhaCaixaDeEntrada';</script>";
            exit();
        }

        // 3. Garante que só pode avaliar no status correto
        if ($solicitacao['status_solicitacao'] !== 'finalizado_concluido') {
            echo "<script>alert('Este trabalho não pode ser avaliado ou já foi avaliado.'); window.location.href='abc.php?action=minhaCaixaDeEntrada';</script>";
            exit();
        }

        // 4. Busca dados do profissional para exibir na página
        $id_profissional = $solicitacao['id_profissional'];
        $id_usuario_profissional = $this->controle->getUsuarioIdPorProfissionalId($id_profissional);
        $profissional_data = $this->controle->getProfissionalData($id_usuario_profissional);

        // 5. Inclui a view (que vamos criar agora)
        include '../view/avaliar.php';
        exit();
    }

    public function submitAvaliacao()
    {
        // 1. Verificações de segurança
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: abc.php?action=logar');
            exit();
        }

        // 2. Validar dados do POST
        $id_solicitacao = filter_input(INPUT_POST, 'id_solicitacao', FILTER_VALIDATE_INT);
        $id_cliente = filter_input(INPUT_POST, 'id_cliente', FILTER_VALIDATE_INT);
        $id_profissional = filter_input(INPUT_POST, 'id_profissional', FILTER_VALIDATE_INT);
        $nota_estrelas = filter_input(INPUT_POST, 'nota_estrelas', FILTER_VALIDATE_INT);
        $comentario = trim(filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS));

        // Verificação de segurança tripla
        if (
            !$id_solicitacao || !$id_cliente || !$id_profissional || !$nota_estrelas ||
            $id_cliente != $_SESSION['usuario_id']
        ) { // Garante que o cliente logado é quem está enviando
            header('Location: abc.php?action=minhaCaixaDeEntrada&status=avaliacao_erro');
            exit();
        }

        // 3. Re-verificar status da solicitação
        $solicitacao = $this->controle->buscarSolicitacaoPorId($id_solicitacao);
        if (!$solicitacao || $solicitacao['id_cliente'] != $id_cliente || $solicitacao['status_solicitacao'] !== 'finalizado_concluido') {
            header('Location: abc.php?action=minhaCaixaDeEntrada&status=avaliacao_ja_feita');
            exit();
        }

        // 4. Salvar no banco (Avaliação principal)
        $id_avaliacao = $this->controle->inserirAvaliacao($id_solicitacao, $id_cliente, $id_profissional, $nota_estrelas, $comentario);

        if (!$id_avaliacao) {
            // Falha ao inserir a avaliação principal
            header('Location: abc.php?action=minhaCaixaDeEntrada&status=avaliacao_db_erro');
            exit();
        }

        // 5. Fazer upload das fotos (se houver)
        if (isset($_FILES['fotos_avaliacao']) && !empty($_FILES['fotos_avaliacao']['name'][0])) {
            $upload_dir = __DIR__ . '/../../public/uploads/avaliacoes/';
            $tamanho_maximo = 5 * 1024 * 1024; // 5MB
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp'];

            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0775, true)) {
                    error_log("Falha ao criar diretório de uploads de avaliação: " . $upload_dir);
                }
            }

            $total_files = count($_FILES['fotos_avaliacao']['name']);

            // Limita a 3, mesmo que o usuário envie mais
            for ($i = 0; $i < $total_files && $i < 3; $i++) {

                if ($_FILES['fotos_avaliacao']['error'][$i] === UPLOAD_ERR_OK) {

                    $tmp_name = $_FILES['fotos_avaliacao']['tmp_name'][$i];
                    $size = $_FILES['fotos_avaliacao']['size'][$i];
                    $name = $_FILES['fotos_avaliacao']['name'][$i];
                    $type = mime_content_type($tmp_name);

                    // Validações
                    if ($size > $tamanho_maximo || !in_array($type, $tipos_permitidos)) {
                        continue; // Pula este arquivo (muito grande ou tipo errado)
                    }

                    // Move o arquivo
                    $extensao = pathinfo($name, PATHINFO_EXTENSION);
                    $nome_arquivo_novo = uniqid('avaliacao_', true) . '.' . $extensao;
                    $caminho_completo = $upload_dir . $nome_arquivo_novo;

                    if (move_uploaded_file($tmp_name, $caminho_completo)) {
                        $caminho_db = 'public/uploads/avaliacoes/' . $nome_arquivo_novo;
                        // Salva no banco (tabela 'fotos_avaliacao')
                        $this->controle->inserirFotoAvaliacao($id_avaliacao, $caminho_db);
                    }
                }
            }
        }

        // 6. Atualizar status da solicitação
        $this->controle->atualizarStatusSolicitacao($id_solicitacao, 'finalizado_avaliado');

        // 7. Redirecionar
        header('Location: abc.php?action=minhaCaixaDeEntrada&status=avaliacao_sucesso');
        exit();
    }

}
?>