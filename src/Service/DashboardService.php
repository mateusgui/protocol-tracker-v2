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
        $entregues = $this->protocoloRepository->countByStatus($id_remessa, 'ENTREGUE');

        $totalProtocolos = $remessa->getQuantidadeProtocolos();

        $totalDigitalizadas = $this->protocoloRepository->sumPagesByRemessaAndStatus($id_remessa, 'DIGITALIZADO');
        $totalEntregues = $this->protocoloRepository->sumPagesByRemessaAndStatus($id_remessa, 'ENTREGUE');
        $totalPaginasDigitalizadas = $totalDigitalizadas + $totalEntregues;

        $percentualDigitalizado = ($totalProtocolos > 0) ? (($digitalizados + $entregues) / $totalProtocolos) * 100 : 0;

        return [
            'numero_remessa' => $remessa->getNumeroRemessa(),
            'status_remessa' => $remessa->getStatus(),
            'total_protocolos' => $totalProtocolos,
            'total_paginas_digitalizadas' => $totalPaginasDigitalizadas,
            'protocolos_aguardando_preparacao' => $aguardandoPreparacao,
            'protocolos_preparados' => $preparados,
            'protocolos_digitalizados' => $digitalizados,
            'protocolos_entregues' => $entregues,
            'percentual_digitalizado' => round($percentualDigitalizado, 2)
        ];
    }

    public function protocolosPreparadosPorDia(?int $id_preparador, DateTimeImmutable $dia): int
    {
        if ($id_preparador !== null) {
            return $this->protocoloRepository->countByDiaPreparador($id_preparador, $dia);
        } else {
            return $this->protocoloRepository->countTotalByDiaPreparacao($dia);
        }
    }

    public function protocolosPreparadosPorMes(?int $id_preparador, DateTimeImmutable $mes): int
    {
        if ($id_preparador !== null) {
            return $this->protocoloRepository->countByMesPreparador($id_preparador, $mes);
        } else {
            return $this->protocoloRepository->countTotalByMesPreparacao($mes);
        }
    }

    public function paginasDigitalizadasPorDia(?int $id_digitalizador, DateTimeImmutable $dia): int
    {
        if ($id_digitalizador !== null) {
            return $this->protocoloRepository->sumByDiaDigitalizador($id_digitalizador, $dia);
        } else {
            return $this->protocoloRepository->sumTotalByDiaDigitalizacao($dia);
        }
    }

    public function protocolosDigitalizadosPorDia(?int $id_digitalizador, DateTimeImmutable $dia): int
    {
        if ($id_digitalizador !== null) {
            return $this->protocoloRepository->countByDiaDigitalizador($id_digitalizador, $dia);
        } else {
            return $this->protocoloRepository->countTotalByDiaDigitalizacao($dia);
        }
    }

    public function paginasDigitalizadasPorMes(?int $id_digitalizador, DateTimeImmutable $mes): int
    {
        if ($id_digitalizador !== null) {
            return $this->protocoloRepository->sumByMesDigitalizador($id_digitalizador, $mes);
        } else {
            return $this->protocoloRepository->sumTotalByMesDigitalizacao($mes);
        }
    }

    public function protocolosDigitalizadosPorMes(?int $id_digitalizador, DateTimeImmutable $mes): int
    {
        if ($id_digitalizador !== null) {
            return $this->protocoloRepository->countByMesDigitalizador($id_digitalizador, $mes);
        } else {
            return $this->protocoloRepository->countTotalByMesDigitalizacao($mes);
        }
    }
}