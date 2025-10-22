<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// A definição de BASE_URL pode ser ajustada ou removida se não for usada nas views
define('BASE_URL', 'http://localhost/angelao/app/view/'); 

require_once '../controller/usuarioController.php';

$controle = new UsuarioController();

// --- INÍCIO DA LÓGICA DE ROTEAMENTO (URLs AMIGÁVEIS) ---

// 1. Pega a rota "amigável" enviada pelo .htaccess
$route = $_GET['route'] ?? '';

// 2. Limpa a rota (remove barras extras no final) e divide-a em partes
// Ex: "perfil/4" vira ['perfil', '4']
// Ex: "especialidade/Casamentos" vira ['especialidade', 'Casamentos']
$parts = explode('/', rtrim($route, '/'));

// 3. A primeira parte é a sua "action"
// Se a rota estiver vazia (homepage), a ação é 'index'
$action = $parts[0] ?? 'index';

// 4. A segunda parte (se existir) é o ID ou parâmetro
$id = $parts[1] ?? null;

// 5. A terceira parte (se existir) pode ser usada para admin ou ações mais complexas
$param2 = $parts[2] ?? null;

// --- FIM DA LÓGICA DE ROTEAMENTO ---


// O switch agora usa a variável $action derivada da URL amigável
switch ($action) {

    case 'index':
        $controle->index();
        break;

    case 'cadastrar':
        $controle->cadastrar();
        break;

    case 'logar':
        $controle->logar();
        break;

    case 'autenticar':
        $controle->autenticar();
        break;

    case 'logout':
        $controle->logout();
        break;

    case 'areaProfissional':
        $controle->showAreaProfissional();
        break;

    // Rota: /perfil/4
    // $action será 'perfil' e $id será '4'
    case 'perfil': 
        if ($id) {
            $_GET['id'] = $id; // Define o $_GET['id'] para o controller
        }
        $controle->showPublicProfile();
        break;

    case 'areaCliente':
        $controle->showAreaCliente();
        break;

    // Rota: /especialidade/Casamentos
    // $action será 'especialidade' e $id será 'Casamentos'
    case 'especialidade': 
        if ($id) {
            // Decodifica o ID caso ele tenha espaços (ex: "Chas%20de%20Bebe")
            $_GET['especialidade'] = urldecode($id); 
        }
        $controle->showProfissionaisPorEspecialidade();
        break;

    case 'alterarSenha':
        $controle->alterarSenha();
        break;

    case 'admin':
        include '../view/admin.php'; 
        break;

    case 'gerenciarProfissionais':
        include '../view/admin.php';
        break;

    case 'gerenciarClientes':
        include '../view/admin.php';
        break;

    // Rota: /search (para o AJAX)
    // Os parâmetros (?term=...) são passados automaticamente
    case 'searchProfissionais':
        $controle->searchProfissionais();
        break;

    case 'updateProfile':
        $controle->updateProfile();
        break;

    // Rota: /deleteFoto/123
    case 'deleteFoto':
        if ($id) {
            $_GET['id'] = $id; // Define o $_GET['id'] para o controller
        }
        $controle->deletePortfolioItem();
        break;

    case 'uploadFotoPortfolio':
        $controle->uploadFotoPortfolio();
        break;

    case 'updatePortfolioItem':
        $controle->updatePortfolioItem();
        break;
        
    case 'excluirConta':
        $controle->excluirMinhaConta();
        break;

    case 'solicitarOrcamento':
        $controle->solicitarOrcamento();
        break;

    // Rota: /excluirProfissional/123
    case 'excluirProfissional':
        if ($id) {
            $model = new UsuarioModel();
            $model->excluirUsuario($id);
        }
        header('Location: ../view/abc.php?route=gerenciarProfissionais'); // Redireciona para a rota amigável
        exit();
        break;

    // Rota: /excluirCliente/123
    case 'excluirCliente':
        if ($id) {
            require_once '../model/usuarioModel.php';
            $model = new UsuarioModel();
            $model->excluirUsuario($id);
        }
        header('Location: ../view/abc.php?route=gerenciarClientes'); // Redireciona para a rota amigável
        exit();
        break;

    // Rota: /editarUsuario/123
    case 'editarUsuario':
        if ($id) {
            $_SESSION['editar_id'] = $id;
            header('Location: editarUsuario.php'); // editarUsuario.php não faz parte do roteador
            exit();
        }
        break;

    // Adiciona casos para as páginas estáticas que você pode querer mascarar
    case 'comoFunciona':
        include '../view/comoFunciona.php';
        break;
        
    case 'termos':
        include '../view/termos.php';
        break;
        
    case 'politica':
        include '../view/politica.php';
        break;

    default:
        // Se nenhuma rota corresponder, pode enviar para a home ou para uma pág 404
        // Aqui, estou enviando para a home (case 'index')
        $controle->index();
        break;
}
?>