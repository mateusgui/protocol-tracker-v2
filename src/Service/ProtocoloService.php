<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Protocolo;

class ProtocoloService
{
    public function __construct(
        private ProtocoloRepositoryInterface $protocoloRepository,
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function add(Protocolo $protocolo): void
    {

    }

    public function update(Protocolo $protocolo): void
    {

    }

    public function movimentarProtocolo(string $id, string $status): void
    {

    }
}