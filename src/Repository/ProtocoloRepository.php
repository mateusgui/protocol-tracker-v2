<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Protocolo;
use PDO;
use PDOStatement;

class ProtocoloRepository implements ProtocoloRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $sqlQuery = "SELECT * FROM protocolos;";
        $stmt = $this->connection->query($sqlQuery);

        return $this->hidrataLista($stmt);
    }

    public function findByNumber(string $numero_protocolo): ?Protocolo
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE numero_protocolo = :numero_protocolo;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':numero_protocolo', $numero_protocolo);

        $stmt->execute();

        $dadosProtocolo = $stmt->fetch();
        if ($dadosProtocolo === false) {
            return null;
        }

        return Protocolo::fromArray($dadosProtocolo);
    }

    public function add(Protocolo $protocolo): void
    {
        $sqlQuery = "INSERT INTO protocolos (id, id_remessa, numero_protocolo, status) VALUES (:id, :id_remessa, :numero_protocolo, :status);";

        $protocoloArray = $protocolo->toArray();

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $protocoloArray['id']);
        $stmt->bindValue(':id_remessa', $protocoloArray['id_remessa']);
        $stmt->bindValue(':numero_protocolo', $protocoloArray['numero_protocolo']);
        $stmt->bindValue(':status', $protocoloArray['status']);

        $stmt->execute();
    }

    public function update(Protocolo $protocolo): void
    {
        $sqlQuery = "UPDATE protocolos SET id_remessa = :id_remessa, numero_protocolo = :numero_protocolo, data_preparacao = :data_preparacao, id_preparador = :id_preparador, data_digitalizacao = :data_digitalizacao, id_digitalizador = :id_digitalizador, status = :status, quantidade_paginas = :quantidade_paginas, observacoes = :observacoes WHERE id = :id;";

        $protocoloArray = $protocolo->toArray();

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_remessa', $protocoloArray['id_remessa']);
        $stmt->bindValue(':numero_protocolo', $protocoloArray['numero_protocolo']);
        $stmt->bindValue(':data_preparacao', $protocoloArray['data_preparacao']);
        $stmt->bindValue(':id_preparador', $protocoloArray['id_preparador']);
        $stmt->bindValue(':data_digitalizacao', $protocoloArray['data_digitalizacao']);
        $stmt->bindValue(':id_digitalizador', $protocoloArray['id_digitalizador']);
        $stmt->bindValue(':status', $protocoloArray['status']);
        $stmt->bindValue(':quantidade_paginas', $protocoloArray['quantidade_paginas']);
        $stmt->bindValue(':observacoes', $protocoloArray['observacoes']);
        $stmt->bindValue(':id', $protocoloArray['id']);

        $stmt->execute();
    }

    //Método para preparadores e digitalizadores utilizarem
    public function movimentarProtocolo(string $id, string $status): void
    {
        $sqlQuery = "UPDATE protocolos SET status = :status WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    private function hidrataLista(PDOStatement $stmt): array
    {
        $listaDeProtocolos = [];

        while($dadosProtocolo = $stmt->fetch()){
            $listaDeProtocolos[] = Protocolo::fromArray($dadosProtocolo);
        }

        return $listaDeProtocolos;
    }
}