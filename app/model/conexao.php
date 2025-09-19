<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
        // Pega as credenciais que o Railway fornece
        $host = getenv('MYSQL_HOST');
        $port = getenv('MYSQL_PORT');
        $dbname = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $senha = getenv('MYSQL_PASSWORD');

        try {        
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            // MUDANÇA IMPORTANTE: AGORA VAMOS MOSTRAR O ERRO REAL
            die("ERRO DETALHADO DO PDO: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>
