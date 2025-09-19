<?php

class Conexao {

    private $pdo;

    private $host;
    private $port;
    private $dbname;
    private $user;
    private $senha;
          
    public function conectar()
    {
        $this->host = getenv('mysql.railway.internal');
        $this->port = getenv('3306');
        $this->dbname = getenv('railway');
        $this->user = getenv('root');
        $this->senha = getenv('XZZjMrtNewUrVfaHAOstUIygwHKUGbUo');

        try {        
            
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname;
            $this->pdo = new PDO($dsn, $this->user, $this->senha);
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "ERRO DE CONEXÃO NO PDO: " . $e->getMessage();
            exit();
        } catch (Exception $e) {
            echo "ERRO: " . $e->getMessage();
            exit();
        }
        
        return $this->pdo;
    }
}
?>
