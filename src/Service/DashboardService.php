<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
use DateTimeZone;
use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;

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

    //PREPARADOR
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

    //IMPLEMENTANDO
    public function paginasPreparadasPorDia(?int $id_preparador, DateTimeImmutable $dia): int
    {
        if ($id_preparador !== null) {
            return $this->protocoloRepository->sumByDiaPreparador($id_preparador, $dia); //fzr
        } else {
            return $this->protocoloRepository->sumTotalByDiaDigitalizacao($dia);
        }
    }

    //IMPLEMENTANDO
    public function paginasPreparadasPorMes(?int $id_preparador, DateTimeImmutable $mes): int
    {
        if ($id_preparador !== null) {
            return $this->protocoloRepository->sumByMesPreparador($id_preparador, $mes); //fzr
        } else {
            return $this->protocoloRepository->sumTotalByMesDigitalizacao($mes);
        }
    }

    //DIGITALIZADOR
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

    public function mediaPreparacaoDia(): int
    {
        $dataFim = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        $dataInicio = $dataFim->modify('-30 days');

        $contagemPorDia = $this->protocoloRepository->getContagemPreparadosPorDia($dataInicio, $dataFim);

        if (empty($contagemPorDia)) {
            return 0;
        }

        $totalProtocolosNoPeriodo = array_sum($contagemPorDia);
        $diasTrabalhados = count($contagemPorDia);

        $media = $totalProtocolosNoPeriodo / $diasTrabalhados;
        
        return (int) round($media);
    }

    public function mediaDigitalizacaoDia(): int
    {
        $dataFim = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        $dataInicio = $dataFim->modify('-30 days');

        $contagemPorDia = $this->protocoloRepository->getContagemDigitalizadosPorDia($dataInicio, $dataFim);

        if (empty($contagemPorDia)) {
            return 0;
        }

        $totalProtocolosNoPeriodo = array_sum($contagemPorDia);
        $diasTrabalhados = count($contagemPorDia);

        $media = $totalProtocolosNoPeriodo / $diasTrabalhados;
        
        return (int) round($media);
    }

    public function calcularPrevisaoEntrega(?string $id_remessa): ?DateTimeImmutable
    {
        if($id_remessa === null){
            return null;
        }

        $remessa = $this->remessaRepository->findById($id_remessa);

        if ($remessa === null || $remessa->getStatus() === 'ENTREGUE') {
            return null; 
        }

        $totalProtocolos = $remessa->getQuantidadeProtocolos();

        $digitalizados = $this->protocoloRepository->countByStatus($id_remessa, 'DIGITALIZADO');
        $entregues = $this->protocoloRepository->countByStatus($id_remessa, 'ENTREGUE');
        $protocolosFinalizados = $digitalizados + $entregues;

        $trabalhoRestante = $totalProtocolos - $protocolosFinalizados;

        if ($trabalhoRestante <= 0) {
            return new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        }

        $velocidadePreparacao = $this->mediaPreparacaoDia();
        $velocidadeDigitalizacao = $this->mediaDigitalizacaoDia();

        $velocidadeDoSistema = min($velocidadePreparacao, $velocidadeDigitalizacao);

        if ($velocidadeDoSistema <= 0) {
            return null;
        }

        $diasRestantes = ceil($trabalhoRestante / $velocidadeDoSistema); 

        $hoje = new DateTimeImmutable('now', new DateTimeZone('America/Campo_Grande'));
        
        $dataPrevista = $hoje->modify("+$diasRestantes days");

        return $dataPrevista;
    }
}