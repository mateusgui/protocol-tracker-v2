<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;

class DashboardService
{
    public function __construct(
        private ProtocoloRepositoryInterface $protocoloRepository
    ) {}
}