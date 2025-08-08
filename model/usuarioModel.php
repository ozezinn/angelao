<?php

// Inclui a classe de conexão que você criou
require_once 'conexao.php';

/**
 * A classe UsuarioModel é responsável por interagir com a tabela 'usuarios'.
 * Ela lida com a lógica de negócio e as operações de banco de dados.
 */
class UsuarioModel {
    private $conn;

    // Construtor que recebe a conexão PDO
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    /**
     * Cadastra um novo usuário no banco de dados.
     *
     * @param string $nome O nome do usuário.
     * @param string $email O email do usuário (deve ser único).
     * @param string $senha A senha do usuário (será criptografada).
     * @param string $tipo_usuario O tipo de usuário (e.g., 'cliente', 'profissional').
     * @return bool Retorna true se o cadastro for bem-sucedido, false caso contrário.
     */
    public function cadastrar($nome, $email, $senha, $tipo_usuario = 'cliente') {
        try {
            // 1. Criptografa a senha antes de salvar no banco de dados
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // 2. Prepara a query SQL com placeholders para evitar SQL Injection
            $sql = "INSERT INTO usuarios (nome, email, senha_hash, tipo_usuario) VALUES (:nome, :email, :senha_hash, :tipo_usuario)";
            $stmt = $this->conn->prepare($sql);

            // 3. Atribui os valores aos placeholders
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha_hash', $senha_hash);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);

            // 4. Executa a query e retorna o resultado
            return $stmt->execute();

        } catch (PDOException $e) {
            // Em caso de erro, exibe a mensagem (em produção, você pode logar o erro)
            echo "Erro ao cadastrar usuário: " . $e->getMessage();
            return false;
        }
    }
}