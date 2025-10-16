<?php
require_once '../model/conexao.php';

class UsuarioModel {
    private $pdo;

    public function __construct() {
        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();
    }

    public function inserir($nome, $senha, $email, $tipo, $cpf = null) {
        $idUsuario = $this->inserirUsuario($nome, $email, $senha, $tipo);
        if (!$idUsuario) return false;

        if ($tipo === 'profissional') {
            if (!$this->inserirProfissional($idUsuario, $cpf)) {
                return false;
            }
        }

        return true;
    }

    private function inserirUsuario($nome, $email, $senha, $tipo) {
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
    public function buscarPorEmail($email) {
        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function inserirProfissional($idUsuario, $cpf) {
        $stmt = $this->pdo->prepare("
            INSERT INTO profissionais (id_usuario, cpf)
            VALUES (:id_usuario, :cpf)
        ");
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':cpf', $cpf);
        return $stmt->execute();
    }
    public function searchProfissionais($term) {
    // Verifica se o termo de busca está vazio ou contém apenas espaços
    if (empty(trim($term))) {
        // SE ESTIVER VAZIO: Busca os 5 profissionais mais recentes
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
        // SE NÃO ESTIVER VAZIO: Faz a busca normal com LIKE
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

    public function validar($nome, $senhaDigitada) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, senha_hash, tipo_usuario
            FROM usuarios
            WHERE nome = :nome
        ");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senhaDigitada, $usuario['senha_hash'])) {
            return [
                'id_usuario' => $usuario['id_usuario'],
                'nome' => $usuario['nome'],
                'tipo_usuario' => $usuario['tipo_usuario']
            ];
        }
        return false;
    }

    public function verificarSenha($idUsuario, $senhaAtual) {
        $stmt = $this->pdo->prepare("
            SELECT senha_hash FROM usuarios WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado && password_verify($senhaAtual, $resultado['senha_hash']);
    }

    public function alterarSenha($idUsuario, $novaSenha) {
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            UPDATE usuarios SET senha_hash = :senha WHERE id_usuario = :id
        ");
        $stmt->bindParam(':senha', $hash);
        $stmt->bindParam(':id', $idUsuario);
        return $stmt->execute();
    }

    public function buscarPorId($idUsuario) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, email, tipo_usuario
            FROM usuarios
            WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarProfissionalPorUsuario($idUsuario) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM profissionais WHERE id_usuario = :id
        ");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function buscarProfissionalPorUsuarioId($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT id_profissional FROM profissionais WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarTodosProfissionais() {
        $stmt = $this->pdo->prepare("
            SELECT u.id_usuario, u.nome, u.email, p.cpf
            FROM usuarios u
            JOIN profissionais p ON u.id_usuario = p.id_usuario
            WHERE u.tipo_usuario = 'profissional'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProfissionalData($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT u.nome, p.* FROM profissionais p
            JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE p.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Busca as especialidades de um profissional específico.
     */
    public function getProfissionalEspecialidades($id_profissional) {
        $stmt = $this->pdo->prepare("
            SELECT e.nome_especialidade 
            FROM profissional_especialidades pe
            JOIN especialidades e ON pe.id_especialidade = e.id_especialidade
            WHERE pe.id_profissional = ?
        ");
        $stmt->execute([$id_profissional]);
        // fetchAll com PDO::FETCH_COLUMN retorna um array simples: ['Casamentos', 'Ensaios']
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Busca todos os itens do portfólio de um profissional.
     */
    public function getPortfolioItems($id_profissional) {
        $stmt = $this->pdo->prepare("SELECT id_item, titulo, caminho_arquivo FROM portifolio WHERE id_profissional = ? ORDER BY id_item DESC");
        $stmt->execute([$id_profissional]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     public function addPortfolioItem($id_profissional, $titulo, $descricao, $id_servico, $caminho_arquivo, $tipo_midia = 'foto') {
    // ======================================================
    // MUDANÇA AQUI: Adicionamos a coluna 'tipo_midia' à query
    // ======================================================
    $sql = "INSERT INTO portifolio (id_profissional, titulo, descricao, id_servico, caminho_arquivo, tipo_midia) 
            VALUES (?, ?, ?, ?, ?, ?)";
    try {
        $stmt = $this->pdo->prepare($sql);
        // Se o usuário não selecionar um serviço, o valor será null
        // ======================================================
        // MUDANÇA AQUI: Adicionamos $tipo_midia ao array de execução
        // ======================================================
        $stmt->execute([$id_profissional, $titulo, $descricao, $id_servico, $caminho_arquivo, $tipo_midia]);
        return true;
    } catch (PDOException $e) {
        // ESSA LINHA É SUA MELHOR AMIGA PARA DEBUG:
        error_log("Erro ao adicionar item ao portfólio: " . $e->getMessage());
        return false;
    }
}

    /**
     * Busca o catálogo completo de todas as especialidades disponíveis.
     */
    public function getAllEspecialidades() {
        $stmt = $this->pdo->prepare("SELECT nome_especialidade FROM especialidades ORDER BY nome_especialidade ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Busca o catálogo completo de todos os serviços disponíveis.
     */
    public function getAllServicos() {
        $stmt = $this->pdo->prepare("SELECT id_servico, nome_servico FROM servicos ORDER BY nome_servico ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarTodosClientes() {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nome, email
            FROM usuarios
            WHERE tipo_usuario = 'cliente'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function buscarProfissionalPorEmail($email) {
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

public function buscarClientePorEmail($email) {
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

public function excluirUsuario($idUsuario) {
    $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
    $stmt->bindParam(':id', $idUsuario);
    return $stmt->execute();
}

public function atualizarUsuario($idUsuario, $nome, $email) {
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
        // A consulta SQL usa LIKE para buscar por partes do nome
        // O uso de prepared statements é CRUCIAL para evitar SQL Injection
        $sql = "SELECT id, nome, email FROM usuarios WHERE nome LIKE :nome AND tipo = 'profissional' LIMIT 10";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            
            // Adiciona os caracteres '%' para a busca LIKE
            $searchTerm = '%' . $nome . '%';
            $stmt->bindParam(':nome', $searchTerm);
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Em um projeto real, você deveria logar este erro
            return []; // Retorna um array vazio em caso de falha
        }
    }public function updateProfissional($id_usuario, $id_profissional, $nome, $localizacao, $biografia, $caminho_foto, $especialidades) {
        $this->pdo->beginTransaction();
        try {
            // 1. Atualiza a tabela 'usuarios' (apenas o nome)
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ? WHERE id_usuario = ?");
            $stmt->execute([$nome, $id_usuario]);

            // 2. Atualiza a tabela 'profissionais'
            // Apenas atualiza a foto se um novo caminho foi fornecido
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

            // 3. Atualiza as especialidades (apaga as antigas e insere as novas)
            $stmt = $this->pdo->prepare("DELETE FROM profissional_especialidades WHERE id_profissional = ?");
            $stmt->execute([$id_profissional]);
            
            if (!empty($especialidades)) {
                $stmt = $this->pdo->prepare("INSERT INTO profissional_especialidades (id_profissional, id_especialidade) VALUES (?, (SELECT id_especialidade FROM especialidades WHERE nome_especialidade = ?))");
                foreach ($especialidades as $esp_nome) {
                    $stmt->execute([$id_profissional, $esp_nome]);
                }
            }
            
            // Se tudo deu certo, confirma a transação
            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            // Se algo deu errado, desfaz tudo
            $this->pdo->rollBack();
            // Opcional: registrar o erro em um log
            // error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um item de portfólio, garantindo que ele pertença ao profissional logado.
     * Retorna o caminho do arquivo para exclusão física, ou false se não encontrou.
     */
    public function deletePortfolioItem($id_item, $id_profissional) {
        // Primeiro, busca o caminho do arquivo para poder excluí-lo depois
        $stmt = $this->pdo->prepare("SELECT caminho_arquivo FROM portifolio WHERE id_item = ? AND id_profissional = ?");
        $stmt->execute([$id_item, $id_profissional]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $caminho_arquivo = $resultado['caminho_arquivo'];

            // Se encontrou, agora exclui o registro do banco
            $stmt = $this->pdo->prepare("DELETE FROM portifolio WHERE id_item = ? AND id_profissional = ?");
            $stmt->execute([$id_item, $id_profissional]);
            
            return $caminho_arquivo;
        }

        // Retorna false se o item não foi encontrado ou não pertence ao profissional (medida de segurança)
        return false;
    }
    

}
?>