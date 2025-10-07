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

    public function add(Remessa $remessa): void
    {
        $sqlQuery = "INSERT INTO remessas (id, data_recebimento, data_entrega, status, quantidade_protocolos, id_administrador, observacoes) VALUES (:id, :data_recebimento, :data_entrega, :status, :quantidade_protocolos, :id_administrador, :observacoes);";
        
        $stmt = $this->connection->prepare($sqlQuery);
        $remessaArray = $remessa->toArray();
        $stmt->execute($remessaArray); //Indexação automática no prepared statement
    }

    public function update(Remessa $remessa): void
    {
        $sqlQuery = "UPDATE remessas SET data_recebimento = :data_recebimento, data_entrega = :data_entrega, status = :status, quantidade_protocolos = :quantidade_protocolos, observacoes = :observacoes WHERE id = :id;";

        $remessaArray = $remessa->toArray();
        
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':data_recebimento', $remessaArray['data_recebimento']);
        $stmt->bindValue(':data_entrega', $remessaArray['data_entrega']);
        $stmt->bindValue(':status', $remessaArray['status']);
        $stmt->bindValue(':quantidade_protocolos', $remessaArray['quantidade_protocolos']);
        $stmt->bindValue(':observacoes', $remessaArray['observacoes']);
        $stmt->bindValue(':id', $remessaArray['id']);
        $stmt->execute();
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