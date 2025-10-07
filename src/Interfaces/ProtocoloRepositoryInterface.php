<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use Mateus\ProtocolTrackerV2\Model\Protocolo;

interface ProtocoloRepositoryInterface
{
    public function all(): array;
    public function findByNumber(string $numero_protocolo): ?Protocolo;
    public function add(Protocolo $protocolo): void;
    public function update(Protocolo $protocolo): void;
    public function movimentarProtocolo(string $id, string $status): void;
}