<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Remessa;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Ramsey\Uuid\Uuid;

class RemessaService
{
    public function __construct(
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function add(Usuario $usuarioLogado, string $data_recebimento): void
    {
        if ($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem criar novas remessas.");
        }

        try {
            $data_recebimento = new DateTimeImmutable($data_recebimento);
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data");
        }

        $id = Uuid::uuid4()->toString();

        $remessa = new Remessa(
            $id,
            $data_recebimento,
            null,
            'RECEBIDO',
            0,
            $usuarioLogado->getId(),
            null
        );

        $this->remessaRepository->add($remessa);
    }

    public function update(Usuario $usuarioLogado, string $data_recebimento, string $data_entrega, string $status, string $observacoes, string $id): void
    {
        //$sqlQuery = "UPDATE remessas SET data_recebimento = :data_recebimento, data_entrega = :data_entrega, status = :status, observacoes = :observacoes WHERE id = :id;";
        if ($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem editar remessas.");
        }
        //Validar se remessa existe pelo ID
        //Validar se data recebimento < data entrega
        //Validar status
        //Implementar função que adiciona +1 quantidade_protocolos



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