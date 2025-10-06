<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use DateTimeImmutable;
use Mateus\ProtocolTrackerV2\Model\Remessa;

interface RemessaRepositoryInterface
{
    public function all(): array;
    public function search(?string $numero = null, ?DateTimeImmutable $dataInicio = null, ?DateTimeImmutable $dataFim = null): array;
    public function add(Remessa $remessa): void;
    public function update(Remessa $remessa): void;
    public function delete($id): void;
}