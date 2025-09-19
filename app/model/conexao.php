<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
        // Esta função auxiliar tenta buscar a variável de 3 lugares diferentes
        function get_env_var($key) {
            if (getenv($key)) return getenv($key);
            if (isset($_ENV[$key])) return $_ENV[$key];
            if (isset($_SERVER[$key])) return $_SERVER[$key];
            return null;
        }

        // Usamos a nova função para pegar as credenciais
        $host = get_env_var('maglev.proxy.rlwy.net');
        $port = get_env_var('33172');
        $dbname = get_env_var('railway');
        $user = get_env_var('root');
        $senha = get_env_var('XZZjMrtNewUrVfaHAOstUIygwHKUGbUo');

        // Adicionamos uma verificação para dar um erro mais claro se a variável não for encontrada
        if (!$host) {
            die("ERRO CRÍTICO: A variável de ambiente MYSQL_HOST não foi encontrada. Verifique as configurações de variáveis no Railway.");
        }

        try {        
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            // Se ainda falhar, agora teremos certeza que é um problema de credencial/rede
            die("ERRO DETALHADO DO PDO: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>
