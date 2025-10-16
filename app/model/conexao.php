<?php

class Conexao {

    private $pdo;

    public function conectar()
    {
         // AGORA USANDO OS NOMES DE VARIÁVEIS ORIGINAIS DO RAILWAY
        $host = get_env_var('MYSQL_HOST');
        $port = get_env_var('MYSQL_PORT');
        $dbname = get_env_var('MYSQL_DATABASE');
        $user = get_env_var('MYSQL_USER');
        $senha = get_env_var('MYSQL_PASSWORD');
        // --- INÍCIO DA ALTERAÇÃO ---

        // Credenciais do banco de dados local (padrão do XAMPP)
       // $host = 'localhost';       // Ou '127.0.0.1'
       // $port = '3306';            // Porta padrão do MySQL
        //$dbname = 'dbLuumina';     // O nome do seu banco de dados
        //$user = 'root';            // Usuário padrão do XAMPP
        //$senha = '';               // Senha padrão do XAMPP é vazia

        // --- FIM DA ALTERAÇÃO --- 

        try {         
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);
            
            // Define o modo de erro do PDO para exceção, o que é uma boa prática
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            // Se a conexão falhar, exibe uma mensagem de erro detalhada
            die("ERRO NA CONEXÃO COM O BANCO DE DADOS LOCAL: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>