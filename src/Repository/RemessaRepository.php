<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use DateTimeImmutable;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Remessa;
use PDO;
use PDOStatement;

class RemessaRepository implements RemessaRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $sqlQuery = "SELECT * FROM remessas ORDER BY data_recebimento DESC;";

        $stmt = $this->connection->query($sqlQuery);

        return $this->hidrataLista($stmt);
    }

    public function search(?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array
    {
        return [];
    }

    public function add(Remessa $remessa): void
    {
        $sqlQuery = "INSERT INTO remessas (id, data_recebimento, data_entrega, status, quantidade_protocolos, id_administrador, observacoes) VALUES (:id, :data_recebimento, :data_entrega, :status, :quantidade_protocolos, :id_administrador, :observacoes);";
        
        $stmt = $this->connection->prepare($sqlQuery);

        $remessaArray = $remessa->toArray();

        $stmt->execute($remessaArray); //Indexação automática no prepared statement
    }

    public function update(Remessa $remessa): void
    {
        $sqlQuery = "UPDATE remessas;";
        //MEXENDO AQUI
    }

    private function hidrataLista(PDOStatement $stmt): array
    {
        $listaDeRemessas = [];

        while($dadosRemessa = $stmt->fetch()){
            $listaDeRemessas[] = Remessa::fromArray($dadosRemessa);
        }

        return $listaDeRemessas;
    }
}