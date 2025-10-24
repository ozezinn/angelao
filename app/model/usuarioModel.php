<?php
require_once '../model/conexao.php';

class UsuarioModel
{
    private $pdo;
    public function __construct($databaseConnection)
    {
        $this->pdo = $databaseConnection;
    }

    public function inserir($nome, $senha, $email, $tipo, $cpf = null)
    {
        $idUsuario = $this->inserirUsuario($nome, $email, $senha, $tipo);
        if (!$idUsuario)
            return false;

        if ($tipo === 'profissional') {
            if (!$this->inserirProfissional($idUsuario, $cpf)) {
                return false;
            }
        }

        return true;
    }

    public function createPasswordResetToken($email, $token_hash, $expires_at)
    {
        try {
            $this->pdo->beginTransaction();
            // 1. Deleta qualquer token antigo para este email
            $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);

            // 2. Insere o novo token
            $stmt = $this->pdo->prepare("
                INSERT INTO password_resets (email, token_hash, expires_at)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$email, $token_hash, $expires_at]);
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao criar token de reset: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca os dados de um token se ele for válido e não tiver expirado.
     */
    public function getResetTokenData($token_hash)
    {
        $stmt = $this->pdo->prepare("
            SELECT email, expires_at FROM password_resets
            WHERE token_hash = ? AND expires_at > NOW()
        ");
        $stmt->execute([$token_hash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Deleta um token de reset (usado após a senha ser redefinida).
     */
    public function deleteResetToken($email)
    {
        $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        return $stmt->execute([$email]);
    }

    /**
     * Atualiza a senha do usuário com base no email.
     */
    public function updatePasswordByEmail($email, $novaSenhaHash)
    {
        $stmt = $this->pdo->prepare("
            UPDATE usuarios SET senha_hash = ? WHERE email = ?
        ");
        return $stmt->execute([$novaSenhaHash, $email]);
    }
    private function inserirUsuario($nome, $email, $senha, $tipo)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nome, email, senha_hash, tipo_usuario)
            VALUES (:nome, :email, :senha, :tipo)
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':tipo', $tipo);
        if ($stmt->execute())
            return $this->pdo->lastInsertId();
        return false;
    }
    public function buscarPorEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function inserirProfissional($idUsuario, $cpf)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO profissionais (id_usuario, cpf)
            VALUES (:id_usuario, :cpf)
        ");
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':cpf', $cpf);
        return $stmt->execute();
    }
    public function searchProfissionais($term)
    {
        if (empty(trim($term))) {
            $sql = "SELECT u.id_usuario, u.nome, p.foto_perfil, p.localizacao 
                FROM usuarios u
                JOIN profissionais p ON u.id_usuario = p.id_usuario
                WHERE u.tipo_usuario = 'profissional'
                ORDER BY u.data_cadastro DESC 
                LIMIT 5";
            try {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return [];
            }
        } else {
            $sql = "SELECT u.id_usuario, u.nome, p.foto_perfil, p.localizacao 
                FROM usuarios u
                JOIN profissionais p ON u.id_usuario = p.id_usuario
                WHERE u.tipo_usuario = 'profissional' AND u.nome LIKE ?
                LIMIT 5";
            try {
                $stmt = $this->pdo->prepare($sql);
                $searchTerm = '%' . $term . '%';
                $stmt->execute([$searchTerm]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return [];
            }
        }
    }

    // NOVA FUNÇÃO ADICIONADA
    public function buscarPorEspecialidade($especialidade)
    {
        $sql = "
        SELECT u.id_usuario, u.nome, p.localizacao, p.foto_perfil, GROUP_CONCAT(e.nome_especialidade SEPARATOR ', ') as especialidades
        FROM usuarios u
        JOIN profissionais p ON u.id_usuario = p.id_usuario
        JOIN profissional_especialidades pe ON p.id_profissional = pe.id_profissional
        JOIN especialidades e ON pe.id_especialidade = e.id_especialidade
        WHERE p.id_profissional IN (
            SELECT pe2.id_profissional
            FROM profissional_especialidades pe2
            JOIN especialidades e2 ON pe2.id_especialidade = e2.id_especialidade
            WHERE e2.nome_especialidade = ?
        )
        GROUP BY u.id_usuario, u.nome, p.localizacao, p.foto_perfil
        ORDER BY u.nome;
    ";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$especialidade]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro na busca por especialidade: " . $e->getMessage());
            return [];
        }
    }

    public function updatePortfolioItem($id_item, $id_profissional, $titulo, $descricao, $id_servico)
    {
        $sql = "UPDATE portifolio 
                SET titulo = ?, descricao = ?, id_servico = ? 
                WHERE id_item = ? AND id_profissional = ?"; // Garante que só edite itens do próprio profissional
        try {
            $stmt = $this->pdo->prepare($sql);
            // Executa a query com os parâmetros na ordem correta
            $stmt->execute([$titulo, $descricao, $id_servico, $id_item, $id_profissional]);
            // Verifica se alguma linha foi afetada (indica sucesso)
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar item do portfólio: " . $e->getMessage());
            return false;
        }
    }

    public function validar($email, $senhaDigitada)
    {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, senha_hash, tipo_usuario, email
            FROM usuarios
            WHERE email = :email
        ");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senhaDigitada, $usuario['senha_hash'])) {
            return [
                'id_usuario' => $usuario['id_usuario'],
                'nome' => $usuario['nome'],
                'tipo_usuario' => $usuario['tipo_usuario'],
                'email' => $usuario['email'] // <-- Adicione esta linha
            ];
        }
        return false;
    }

    public function verificarSenha($idUsuario, $senhaAtual)
    {
        $stmt = $this->pdo->prepare("
            SELECT senha_hash FROM usuarios WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado && password_verify($senhaAtual, $resultado['senha_hash']);
    }

    public function alterarSenha($idUsuario, $novaSenha)
    {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            UPDATE usuarios SET senha_hash = :senha WHERE id_usuario = :id
        ");
        $stmt->bindParam(':senha', $hash);
        $stmt->bindParam(':id', $idUsuario);
        return $stmt->execute();
    }

    public function buscarPorId($idUsuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, email, tipo_usuario
            FROM usuarios
            WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarProfissionalPorUsuario($idUsuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM profissionais WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function buscarProfissionalPorUsuarioId($id_usuario)
    {
        $stmt = $this->pdo->prepare("SELECT id_profissional FROM profissionais WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarTodosProfissionais()
    {
        $stmt = $this->pdo->prepare("
            SELECT u.id_usuario, u.nome, u.email, p.cpf
            FROM usuarios u
            JOIN profissionais p ON u.id_usuario = p.id_usuario
            WHERE u.tipo_usuario = 'profissional'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProfissionalData($id_usuario)
    {
        $stmt = $this->pdo->prepare("
            SELECT u.nome, p.* FROM profissionais p
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE p.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProfissionalEspecialidades($id_profissional)
    {
        $stmt = $this->pdo->prepare("
            SELECT e.nome_especialidade 
            FROM profissional_especialidades pe
            JOIN especialidades e ON pe.id_especialidade = e.id_especialidade
            WHERE pe.id_profissional = ?
        ");
        $stmt->execute([$id_profissional]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getPortfolioItems($id_profissional)
    {
        $stmt = $this->pdo->prepare("SELECT id_item, titulo, caminho_arquivo FROM portifolio WHERE id_profissional = ? ORDER BY id_item DESC");
        $stmt->execute([$id_profissional]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_arquivo, $tipo_midia = 'foto')
    {
        $sql = "INSERT INTO portifolio (id_profissional, titulo, descricao, id_servico, caminho_arquivo, tipo_midia) 
            VALUES (?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_profissional, $titulo, $descricao, $id_servico, $caminho_arquivo, $tipo_midia]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao adicionar item ao portfólio: " . $e->getMessage());
            return false;
        }
    }

    public function getAllEspecialidades()
    {
        $stmt = $this->pdo->prepare("SELECT nome_especialidade FROM especialidades ORDER BY nome_especialidade ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllServicos()
    {
        $stmt = $this->pdo->prepare("SELECT id_servico, nome_servico FROM servicos ORDER BY nome_servico ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodosClientes()
    {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, email
            FROM usuarios
            WHERE tipo_usuario = 'cliente'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarProfissionalPorEmail($email)
    {
        $stmt = $this->pdo->prepare("
        SELECT u.id_usuario, u.nome, u.email, p.cpf
        FROM usuarios u
        JOIN profissionais p ON u.id_usuario = p.id_usuario
        WHERE u.tipo_usuario = 'profissional' AND u.email LIKE :email
    ");
        $like = "%$email%";
        $stmt->bindParam(':email', $like);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarClientePorEmail($email)
    {
        $stmt = $this->pdo->prepare("
        SELECT id_usuario, nome, email
        FROM usuarios
        WHERE tipo_usuario = 'cliente' AND email LIKE :email
    ");
        $like = "%$email%";
        $stmt->bindParam(':email', $like);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirUsuario($idUsuario)
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
        $stmt->bindParam(':id', $idUsuario);
        return $stmt->execute();
    }

    public function atualizarUsuario($idUsuario, $nome, $email)
    {
        $stmt = $this->pdo->prepare("
        UPDATE usuarios
        SET nome = :nome, email = :email
        WHERE id_usuario = :id
    ");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $idUsuario);
        return $stmt->execute();
    }
    public function buscarProfissionaisPorNome($nome)
    {
        $sql = "SELECT id, nome, email FROM usuarios WHERE nome LIKE :nome AND tipo = 'profissional' LIMIT 10";

        try {
            $stmt = $this->pdo->prepare($sql);

            $searchTerm = '%' . $nome . '%';
            $stmt->bindParam(':nome', $searchTerm);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function updateProfissional($id_usuario, $id_profissional, $nome, $localizacao, $biografia, $caminho_foto, $especialidades)
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ? WHERE id_usuario = ?");
            $stmt->execute([$nome, $id_usuario]);

            $sql_profissionais = "UPDATE profissionais SET localizacao = ?, biografia = ?";
            $params_profissionais = [$localizacao, $biografia];
            if ($caminho_foto) {
                $sql_profissionais .= ", foto_perfil = ?";
                $params_profissionais[] = $caminho_foto;
            }
            $sql_profissionais .= " WHERE id_profissional = ?";
            $params_profissionais[] = $id_profissional;
            $stmt = $this->pdo->prepare($sql_profissionais);
            $stmt->execute($params_profissionais);

            $stmt = $this->pdo->prepare("DELETE FROM profissional_especialidades WHERE id_profissional = ?");
            $stmt->execute([$id_profissional]);

            if (!empty($especialidades)) {
                $stmt = $this->pdo->prepare("INSERT INTO profissional_especialidades (id_profissional, id_especialidade) VALUES (?, (SELECT id_especialidade FROM especialidades WHERE nome_especialidade = ?))");
                foreach ($especialidades as $esp_nome) {
                    $stmt->execute([$id_profissional, $esp_nome]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deletePortfolioItem($id_item, $id_profissional)
    {
        $stmt = $this->pdo->prepare("SELECT caminho_arquivo FROM portifolio WHERE id_item = ? AND id_profissional = ?");
        $stmt->execute([$id_item, $id_profissional]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $caminho_arquivo = $resultado['caminho_arquivo'];

            $stmt = $this->pdo->prepare("DELETE FROM portifolio WHERE id_item = ? AND id_profissional = ?");
            $stmt->execute([$id_item, $id_profissional]);

            return $caminho_arquivo;
        }

        return false;
    }

    public function getSolicitacoesPorProfissional($id_profissional)
    {
        $sql = "SELECT * FROM solicitacoes_orcamento WHERE id_profissional = ? ORDER BY data_evento DESC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_profissional]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar solicitações: " . $e->getMessage());
            return [];
        }
    }

    public function inserirSolicitacaoOrcamento($id_profissional, $id_cliente, $nome_solicitante, $email_solicitante, $telefone_solicitante, $tipo_evento, $data_evento, $mensagem)
    {
        $sql = "INSERT INTO solicitacoes_orcamento (id_profissional, id_cliente, nome_solicitante, email_solicitante, telefone_solicitante, tipo_evento, data_evento, mensagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_profissional, $id_cliente, $nome_solicitante, $email_solicitante, $telefone_solicitante, $tipo_evento, $data_evento, $mensagem]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir solicitação de orçamento: " . $e->getMessage());
            return false;
        }
    }

    public function buscarSolicitacaoPorId($id_solicitacao)
    {
        try {
            $sql = "SELECT * FROM solicitacoes_orcamento WHERE id_solicitacao = :id";
            // Use a variável correta: $this->pdo
            $stmt = $this->pdo->prepare($sql); // <--- CORREÇÃO
            $stmt->bindParam(':id', $id_solicitacao, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar solicitação: " . $e->getMessage());
            return false;
        }
    }

    // Insere uma nova mensagem na conversa
    public function inserirMensagem($id_solicitacao, $id_remetente, $id_destinatario, $mensagem)
    {
        $sql = "INSERT INTO mensagens_conversa (id_solicitacao, id_remetente, id_destinatario, mensagem) 
            VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_solicitacao, $id_remetente, $id_destinatario, $mensagem]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao inserir mensagem: " . $e->getMessage());
            return false;
        }
    }

    // Busca todas as mensagens de uma solicitação específica
    public function buscarMensagensPorSolicitacao($id_solicitacao)
    {
        // Usamos JOIN para pegar o nome do remetente
        $sql = "SELECT m.*, u.nome as nome_remetente 
            FROM mensagens_conversa m
            JOIN usuarios u ON m.id_remetente = u.id_usuario
            WHERE m.id_solicitacao = ? 
            ORDER BY m.data_envio ASC";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_solicitacao]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar mensagens: " . $e->getMessage());
            return [];
        }
    }

    // Atualiza o status da solicitação (Ex: para 'respondido')
    public function atualizarStatusSolicitacao($id_solicitacao, $novo_status)
    {
        $sql = "UPDATE solicitacoes_orcamento SET status_solicitacao = ? WHERE id_solicitacao = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$novo_status, $id_solicitacao]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }

    // (Você também precisará de uma função para o CLIENTE ver as solicitações DELE)
    public function getSolicitacoesPorCliente($id_cliente)
    {
        $sql = "SELECT s.*, p.id_usuario as id_usuario_profissional, u.nome as nome_profissional
            FROM solicitacoes_orcamento s
            JOIN profissionais p ON s.id_profissional = p.id_profissional
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE s.id_cliente = ? 
            ORDER BY s.id_solicitacao DESC";
        try { // <--- INÍCIO DA CORREÇÃO
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_cliente]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar solicitações do cliente: " . $e->getMessage());
            return [];
        }
    }

}
?>