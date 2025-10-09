<?php

namespace Mateus\ProtocolTrackerV2\Infrastructure\Persistence;

use PDO;
use PDOException;

class ConnectionCreatorSqlite
{
    public static function createConnectionSqlite(): PDO
    {
        // 1. Define o caminho para o arquivo do banco de dados SQLite.
        //    __DIR__ pega o diretório do arquivo ATUAL (Persistence), então está correto.
        $databasePath = __DIR__ . '/banco_teste.sqlite';

        // 2. A string de conexão (DSN) para SQLite é simples: 'sqlite:' + caminho do arquivo.
        $dsn = 'sqlite:' . $databasePath;

        try {
            // 3. A conexão com SQLite não precisa de usuário e senha.
            $connection = new PDO($dsn);

            // Suas configurações de atributos continuam perfeitas.
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }

        return $connection;
    }
}