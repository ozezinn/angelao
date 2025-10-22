<?php
require_once '../model/conexao.php';

class UsuarioModel
{
    private $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();
    }

    public function inserir($nome, $senha, $email, $tipo, $cpf = null)
    {
        $idUsuario = $this->inserirUsuario($nome, $email, $senha, $tipo);
        if (!$idUsuario) return false;

        if ($tipo === 'profissional') {
            if (!$this->inserirProfissional($idUsuario, $cpf)) {
                return false;
            }
        }

        return true;
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
        if ($stmt->execute()) return $this->pdo->lastInsertId();
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
}
?>