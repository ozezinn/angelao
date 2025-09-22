<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
        // Esta função auxiliar busca a variável de ambiente corretamente
        function get_env_var($key) {
            // A ordem de preferência é getenv > $_ENV > $_SERVER
            if (getenv($key) !== false) return getenv($key);
            if (isset($_ENV[$key])) return $_ENV[$key];
            if (isset($_SERVER[$key])) return $_SERVER[$key];
            return null;
        }

        // Usamos a função para pegar as credenciais a partir dos NOMES das variáveis
        $host = get_env_var('DB_HOST');
        $port = get_env_var('DB_PORT');
        $dbname = get_env_var('DB_DATABASE');
        $user = get_env_var('DB_USERNAME');
        $senha = get_env_var('DB_PASSWORD');

        // Verificamos se TODAS as variáveis essenciais foram encontradas
        if (!$host || !$port || !$dbname || !$user || !$senha) {
            die("ERRO CRÍTICO: Uma ou mais variáveis de ambiente para conexão com o banco de dados não foram encontradas. Verifique as configurações no Railway (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).");
        }

        try {        
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            // Se a conexão falhar, o erro será exibido
            die("ERRO NA CONEXÃO COM O BANCO DE DADOS: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>