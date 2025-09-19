<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
        $host = getenv('MYSQL_HOST');
        $port = getenv('MYSQL_PORT');
        $dbname = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $senha = getenv('MYSQL_PASSWORD');

        try {        
            // A string de conexão foi atualizada para usar a PORTA e todas as outras variáveis
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);

            // Configura o PDO para lançar exceções em caso de erro (boa prática)
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            // Em um site de produção, é melhor não mostrar o erro detalhado
            die("ERRO DE CONEXÃO: Não foi possível conectar ao banco de dados.");
        }

        return $this->pdo;
    }
}
?>
