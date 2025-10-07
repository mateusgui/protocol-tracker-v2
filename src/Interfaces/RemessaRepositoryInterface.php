<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use DateTimeImmutable;
use Mateus\ProtocolTrackerV2\Model\Remessa;

interface RemessaRepositoryInterface
{
    public function all(): array;
    public function findById(string $id): ?Remessa;
    public function add(Remessa $remessa): void;
    public function update(Remessa $remessa): void;
    public function adicionaProtocolo(string $id): void;
}