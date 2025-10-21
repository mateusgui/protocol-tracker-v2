<?php

namespace Mateus\ProtocolTrackerV2\Infrastructure\Persistence;

use PDO;
use PDOException;

class ConnectionCreator
{
    public static function createConnection() : PDO
    {
        //$dsn = 'mysql:host=127.0.0.1;port=3306;dbname=estudoMySql;charset=utf8mb4';
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=meu-mysql;charset=utf8mb4';
        
        try {
            //$connection = new PDO($dsn, 'root', 'sua_senha_super_secreta');
            $connection = new PDO($dsn, 'root', 'sua_senha_secreta');

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }

        return $connection;
    }
}