<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Usuario;

class DashboardService
{
    public function __construct(
        private ProtocoloRepositoryInterface $protocoloRepository,
        private RemessaRepositoryInterface $remessaRepository
    ) {}

    public function metricaPorRemessa(string $id_remessa): array
    {
        $remessa = $this->remessaRepository->findById($id_remessa);
        if ($remessa === null) {
            throw new \Exception("Remessa não encontrada.");
        }

        $aguardandoPreparacao = $this->protocoloRepository->countByStatus($id_remessa, 'RECEBIDO');
        $preparados = $this->protocoloRepository->countByStatus($id_remessa, 'PREPARADO');
        $digitalizados = $this->protocoloRepository->countByStatus($id_remessa, 'DIGITALIZADO');

        $totalProtocolos = $remessa->getQuantidadeProtocolos();
        $totalPaginasDigitalizadas = $this->protocoloRepository->sumPagesByRemessaAndStatus($id_remessa, 'DIGITALIZADO');

        $percentualDigitalizado = ($totalProtocolos > 0) ? ($digitalizados / $totalProtocolos) * 100 : 0;

        return [
            'numero_remessa' => $remessa->getNumeroRemessa(),
            'status_remessa' => $remessa->getStatus(),
            'total_protocolos' => $totalProtocolos,
            'total_paginas_digitalizadas' => $totalPaginasDigitalizadas,
            'protocolos_aguardando_preparacao' => $aguardandoPreparacao,
            'protocolos_preparados' => $preparados,
            'protocolos_digitalizados' => $digitalizados,
            'percentual_digitalizado' => round($percentualDigitalizado, 2)
        ];
    }

    // ---------- MÉTRICAS PREPARAÇÃO ----------
    public function metricaPorPreparadorDia(int $id_preparador, DateTimeImmutable $dia): int
    {
        return $this->protocoloRepository->countByDiaPreparador($id_preparador, $dia);
    }
}