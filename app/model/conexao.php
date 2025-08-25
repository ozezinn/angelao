<?php

class Conexao
{
    private static $pdo;
    private static $host = "localhost";
    private static $dbname = "dbLuumina";
    private static $user = "root";
    private static $senha = "";

    public static function conectar()
    {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    "mysql:dbname=" . self::$dbname . ";host=" . self::$host,
                    self::$user,
                    self::$senha,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // resultados em arrays por padrão.
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                // Em vez de 'echo' e 'exit', você pode lançar a exceção
                // para que a camada superior (Controller) possa tratá-la.
                // Aqui, podemos adicionar mais contexto ao erro se quisermos.
                throw new PDOException("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
