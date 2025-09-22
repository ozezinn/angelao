<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
        function get_env_var($key) {
            if (getenv($key) !== false) return getenv($key);
            if (isset($_ENV[$key])) return $_ENV[$key];
            if (isset($_SERVER[$key])) return $_SERVER[$key];
            return null;
        }

        // AGORA USANDO OS NOMES DE VARIÁVEIS ORIGINAIS DO RAILWAY
        $host = get_env_var('MYSQL_HOST');
        $port = get_env_var('MYSQL_PORT');
        $dbname = get_env_var('MYSQL_DATABASE');
        $user = get_env_var('MYSQL_USER');
        $senha = get_env_var('MYSQL_PASSWORD');

        if (!$host || !$port || !$dbname || !$user || !$senha) {
            die("ERRO CRÍTICO: Não foi possível encontrar uma ou mais variáveis de ambiente do Railway (MYSQL_HOST, MYSQL_PORT, etc.). Verifique o painel do seu serviço MySQL.");
        }

        try {        
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            die("ERRO NA CONEXÃO COM O BANCO DE DADOS: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>