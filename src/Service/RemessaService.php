<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;

class RemessaService
{
    public function __construct(
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function add($data_recebimento, $id_administrador): void
    {
        //$sqlQuery = "INSERT INTO remessas (id, data_recebimento, id_administrador) VALUES (:id, :data_recebimento, :id_administrador);";



    }

    public function update(): void
    {
        //$sqlQuery = "UPDATE remessas SET data_recebimento = :data_recebimento, data_entrega = :data_entrega, status = :status, quantidade_protocolos = :quantidade_protocolos, observacoes = :observacoes WHERE id = :id;";



    }
}

/**public function __construct(
        private readonly string $id,
        private readonly ?DateTimeImmutable $data_recebimento,
        private readonly ?DateTimeImmutable $data_entrega,
        private readonly string $status,
        private readonly ?int $quantidade_protocolos,
        private readonly int $id_administrador,
        private readonly ?string $observacoes
        )
        {} */