<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use PDO;

class ProtocoloRepository implements ProtocoloRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
}