<?php

use Mateus\ProtocolTrackerV2\Infrastructure\Persistence\ConnectionCreator;

session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
    
    $connection = ConnectionCreator::createConnection();

    echo "Hello World!\nConectou!";

} catch (Exception $e) {
    http_response_code(500);
    echo "<p>Mensagem do Erro: " . $e->getMessage() . "</p>";
}