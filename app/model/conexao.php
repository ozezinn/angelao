<?php

class Conexao
{

    private $pdo;

    public function conectar()
    {
        // --- INÍCIO DA ALTERAÇÃO ---

        $host = '127.0.0.1'; // <-- AQUI ESTÁ A MUDANÇA
        $port = '3306';
        $dbname = 'dbLuumina';
        $user = 'adm';
        $senha = 'Luumina2010@';

        // --- FIM DA ALTERAÇÃO ---

        try {
            $dsn = "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $dbname;
            $this->pdo = new PDO($dsn, $user, $senha);

            // Define o modo de erro do PDO para exceção, o que é uma boa prática
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Se a conexão falhar, exibe uma mensagem de erro detalhada
            die("ERRO NA CONEXÃO COM O BANCO DE DADOS LOCAL: " . $e->getMessage());
        }

        return $this->pdo;
    }
}
?>