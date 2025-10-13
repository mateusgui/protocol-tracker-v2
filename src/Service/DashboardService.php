<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;

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

        $percentualConclusao = ($totalProtocolos > 0) ? ($digitalizados / $totalProtocolos) * 100 : 0;

        return [
            'numero_remessa' => $remessa->getNumeroRemessa(),
            'status_remessa' => $remessa->getStatus(),
            'total_protocolos' => $totalProtocolos,
            'protocolos_aguardando_preparacao' => $aguardandoPreparacao,
            'protocolos_preparados' => $preparados,
            'protocolos_digitalizados' => $digitalizados,
            'percentual_conclusao' => round($percentualConclusao, 2)
        ];
    }
}