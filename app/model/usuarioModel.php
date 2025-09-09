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

    private function inserirProfissional($idUsuario, $cpf) {
        $stmt = $this->pdo->prepare("
            INSERT INTO profissionais (id_usuario, cpf)
            VALUES (:id_usuario, :cpf)
        ");
        $stmt->bindParam(':id_usuario', $idUsuario);
        $stmt->bindParam(':cpf', $cpf);
        return $stmt->execute();
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
    }

}
?>