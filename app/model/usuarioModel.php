<?php
require_once 'Conexao.php';

class Usuario
{
    private $id_usuario;
    private $nome;
    private $email;
    private $senha_hash;
    private $tipo_usuario;

    public function setNome($nome)
    {
        $this->nome = trim($nome);
    }

    public function setEmail($email)
    {
        $this->email = trim($email);
    }

    public function setSenha($senha)
    {
        // A senha nunca é armazenada diretamente.
        // Geramos um hash seguro para guardar no banco.
        $this->senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function setTipoUsuario($tipo)
    {
        $this->tipo_usuario = $tipo;
    }

    // --- Métodos de Interação com o Banco de Dados ---

    /**
     * Insere o usuário atual no banco de dados.
     * @return bool Retorna true se o cadastro foi bem-sucedido, false caso contrário.
     */
    public function cadastrar()
    {
        try {
            // Verifica se já existe um usuário com o mesmo email
            if ($this->emailJaExiste()) {
                return false;
            }

            $pdo = Conexao::conectar();

            // Prepara a query SQL
            $sql = "INSERT INTO usuarios (nome, email, senha_hash, tipo_usuario) VALUES (:nome, :email, :senha_hash, :tipo_usuario)";

            $stmt = $pdo->prepare($sql);

            // Associa os valores das propriedades do objeto aos parâmetros da query
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':senha_hash', $this->senha_hash);
            $stmt->bindValue(':tipo_usuario', $this->tipo_usuario);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Em um sistema real, você deveria registrar este erro em um log.
            // error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se um email já está cadastrado no banco.
     * @return bool Retorna true se o email já existe, false caso contrário.
     */
    private function emailJaExiste()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT id_usuario FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();

        // Se a contagem de linhas for maior que 0, o email já existe
        return $stmt->rowCount() > 0;
    }
}
