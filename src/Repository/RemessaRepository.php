<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use DateTimeImmutable;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Remessa;
use PDO;

class RemessaRepository implements RemessaRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        return [];
    }

    public function search(?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        return [];
    }

    public function add(Remessa $remessa): void
    {

    }

    public function update(Remessa $remessa): void
    {

    }
    public function delete($id): void
    {

    }
}