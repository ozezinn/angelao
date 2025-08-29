<?php
require_once '../model/conexao.php';

class UsuarioModel{
    private $pdo;

    public function __construct(){
        $conexao = new Conexao();
        $this->pdo = $conexao->conectar();
    }

    public function inserir($nome, $senha, $email, $tipo){

        $stmtUsuario = $this->pdo->prepare("INSERT INTO usuario (nome, senha, email)
        VALUES (:nome, :senha, :email)");
        $stmtUsuario->bindParam(':nome', $nome);
        $stmtUsuario->bindParam(':senha', $senha);
        $stmtUsuario->bindParam(':email', $email);

        if($stmtUsuario->execute()){
            $idUsuario = $this->pdo->lastInsertId();
            $stmtTipo = $this->pdo->prepare("INSERT INTO tipoUsuario (idUsuario, descricao)
            VALUES  (:idUsuario, :descricao)");
            $stmtTipo->bindParam(':idUsuario', $idUsuario);
            $stmtTipo->bindParam(':descricao', $tipo);
            $stmtTipo->execute();
        }
    }

        public function validar($nome, $senhaDigitada){
            $stmt = $this->pdo->prepare("SELECT u.idUsuario, u.nome, u.senha, t.descricao
                                         FROM usuario u
                                         INNER JOIN tipoUsuario t ON u.idUsuario = t.idUsuario
                                         WHERE u.nome = :nome");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if($usuario && password_verify($senhaDigitada, $usuario['senha'])){

            unset($usuario['senha']);
            return $usuario;
        }
        return false;
    }
}

?>