<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use DateTimeImmutable;
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

    public function search(?string $numero_protocolo = null, ?string $numero_remessa = null): array
    {
        $sqlConditions = [];
        $parametros = [];

        if (!empty($numero_protocolo)) {
            $sqlConditions[] = 'numero_protocolo = :numero_protocolo';
            $parametros[':numero_protocolo'] = $numero_protocolo;
        }
        if (!empty($numero_remessa)) {
            $sqlConditions[] = 'numero_remessa = :numero_remessa';
            $parametros[':numero_remessa'] = $numero_remessa;
        }

        $sqlQuery = "SELECT
        p.numero_protocolo AS numero_protocolo,
        r.numero_remessa AS numero_remessa,
        p.status AS status_protocolo,
        p.data_preparacao AS data_preparacao,
        p.id_preparador AS id_preparador,
        p.data_digitalizacao AS data_digitalizacao,
        p.id_digitalizador AS id_digitalizador,
        p.quantidade_paginas AS quantidade_paginas,
        p.observacoes AS observacoes
        FROM protocolos as p
        JOIN remessas as r
        ON p.id_remessa = r.id";

        if (!empty($sqlConditions)) {
            $sqlQuery .= ' WHERE ' . implode(' AND ', $sqlConditions);
        }

        $sqlQuery .= ' ORDER BY r.numero_remessa, p.numero_protocolo;';

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->execute($parametros);

        return $stmt->fetchAll();
    }

    public function findByRemessa(string $id_remessa): array
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE id_remessa = :id_remessa;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_remessa', $id_remessa);

        $stmt->execute();

        return $this->hidrataLista($stmt);
    }

    public function findById(string $id): ?Protocolo
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $dadosProtocolo = $stmt->fetch();
        if ($dadosProtocolo === false) {
            return null;
        }

        return Protocolo::fromArray($dadosProtocolo);
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

    public function findByStatus(string $status): array
    {
        $sqlQuery = "SELECT * FROM protocolos WHERE status = :status;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':status', $status);

        $stmt->execute();

        return $this->hidrataLista($stmt);
    }

    public function countByStatus(string $id_remessa, string $status): int
    {
        $sqlQuery = "SELECT COUNT(*) FROM protocolos WHERE id_remessa = :id_remessa AND status = :status;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_remessa', $id_remessa);
        $stmt->bindValue(':status', $status);

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countByDiaPreparador(int $id_preparador, DateTimeImmutable $data_preparacao): int
    {
        $sqlQuery = "SELECT COUNT(*) FROM protocolos WHERE id_preparador = :id_preparador AND DATE_FORMAT(data_preparacao, '%Y-%m-%d') = :data_preparacao";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_preparador', $id_preparador);
        $stmt->bindValue(':data_preparacao', $data_preparacao->format('Y-m-d'));
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countByMesPreparador(int $id_preparador, DateTimeImmutable $data_preparacao): int
    {
        $sqlQuery = "SELECT COUNT(*) FROM protocolos WHERE id_preparador = :id_preparador AND DATE_FORMAT(data_preparacao, '%Y-%m') = :mes_ano;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_preparador', $id_preparador);
        $stmt->bindValue(':mes_ano', $data_preparacao->format('Y-m'));
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function sumByDiaDigitalizador(int $id_digitalizador, DateTimeImmutable $dia): int
    {
        $sqlQuery = "SELECT SUM(quantidade_paginas) FROM protocolos WHERE id_digitalizador = :id_digitalizador AND DATE_FORMAT(data_digitalizacao, '%Y-%m-%d') = :data_digitalizacao;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_digitalizador', $id_digitalizador);
        $stmt->bindValue(':data_digitalizacao', $dia->format('Y-m-d') );
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countByDiaDigitalizador(int $id_digitalizador, DateTimeImmutable $data_digitalizacao): int
    {
        $sqlQuery = "SELECT COUNT(*) FROM protocolos WHERE id_digitalizador = :id_digitalizador AND DATE_FORMAT(data_digitalizacao, '%Y-%m-%d') = :data_digitalizacao;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_digitalizador', $id_digitalizador);
        $stmt->bindValue(':data_digitalizacao', $data_digitalizacao->format('Y-m-d'));
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function sumByMesDigitalizador(int $id_digitalizador, DateTimeImmutable $mes): int
    {
        $sqlQuery = "SELECT SUM(quantidade_paginas) FROM protocolos WHERE id_digitalizador = :id_digitalizador AND DATE_FORMAT(data_digitalizacao, '%Y-%m') = :data_mes;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_digitalizador', $id_digitalizador);
        $stmt->bindValue(':data_mes', $mes->format('Y-m'));
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function countByMesDigitalizador(int $id_digitalizador, DateTimeImmutable $mes): int
    {
        $sqlQuery = "SELECT COUNT(*) FROM protocolos WHERE id_digitalizador = :id_digitalizador AND DATE_FORMAT(data_digitalizacao, '%Y-%m') = :data_mes;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_digitalizador', $id_digitalizador);
        $stmt->bindValue(':data_mes', $mes->format('Y-m'));
        
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function sumPagesByRemessaAndStatus(string $id_remessa, string $status): int
    {
        $sqlQuery = "SELECT SUM(quantidade_paginas) FROM protocolos WHERE id_remessa = :id_remessa AND status = :status;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_remessa', $id_remessa);
        $stmt->bindValue(':status', $status);
        
        $stmt->execute();

        $total = $stmt->fetchColumn();

        return (int)$total;
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
    public function preparaProtocolo(string $id, string $data_preparacao, int $id_preparador, ?string $observacoes): void
    {
        $sqlQuery = "UPDATE protocolos SET status = 'PREPARADO', data_preparacao = :data_preparacao, id_preparador = :id_preparador, observacoes = :observacoes WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':data_preparacao', $data_preparacao);
        $stmt->bindValue(':id_preparador', $id_preparador);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':observacoes', $observacoes);

        $stmt->execute();
    }

    public function digitalizaProtocolo(string $id, string $data_digitalizacao, int $id_digitalizador, int $quantidade_paginas, ?string $observacoes): void
    {
        $sqlQuery = "UPDATE protocolos SET status = 'DIGITALIZADO', data_digitalizacao = :data_digitalizacao, id_digitalizador = :id_digitalizador, quantidade_paginas = :quantidade_paginas, observacoes = :observacoes WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':data_digitalizacao', $data_digitalizacao);
        $stmt->bindValue(':id_digitalizador', $id_digitalizador);
        $stmt->bindValue(':quantidade_paginas', $quantidade_paginas);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':observacoes', $observacoes);

        $stmt->execute();
    }

    public function entregaProtocolos(string $id_remessa): void
    {
        $sqlQuery = "UPDATE protocolos SET status = 'ENTREGUE' WHERE id_remessa = :id_remessa;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id_remessa', $id_remessa);

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