<?php

namespace Mateus\ProtocolTrackerV2\Infrastructure\Persistence;

use PDO;
use PDOException;

class ConnectionCreatorSqlite
{
    public static function createConnectionSqlite(): PDO
    {
        $databasePath = __DIR__ . '/banco_teste.sqlite';

        $dsn = 'sqlite:' . $databasePath;

        try {
            $connection = new PDO($dsn);

            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }

        return $connection;
    }
}