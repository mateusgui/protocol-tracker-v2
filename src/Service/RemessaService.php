<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Protocolo;
use Mateus\ProtocolTrackerV2\Model\Remessa;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Ramsey\Uuid\Uuid;

class RemessaService
{
    public function __construct(
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository,
        private ProtocoloRepository $protocoloRepository
    ) {}

    public function novaRemessa(Usuario $usuarioLogado, string $data_recebimento, ?string $observacoes): Remessa
    {
        if($usuarioLogado->getPermissao() !== 'administrador') {
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
            null,
            $data_recebimento,
            null,
            'RECEBIDO',
            null,
            $usuarioLogado->getId(),
            $observacoes
        );

        $this->remessaRepository->add($remessa);

        return $remessa;
    }

    public function atualizarRemessa(Usuario $usuarioLogado, string $data_recebimento, ?string $data_entrega, string $status, ?string $observacoes, string $id): void
    {
        if($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem editar remessas.");
        }
        
        $remessaAntiga = $this->remessaRepository->findById($id);
        if($remessaAntiga === null){
            throw new Exception("Remessa informada não foi localizada");
        }

        $data_recebimento = empty($data_recebimento) ? null : new DateTimeImmutable($data_recebimento);
        $data_entrega = empty($data_entrega) ? null : new DateTimeImmutable($data_entrega);

        if($data_recebimento && $data_entrega){
            if($data_entrega < $data_recebimento){
                throw new Exception("A data de entrega não pode ser anterior à data de recebimento");
            }
        }

        if($status !== 'RECEBIDO' && $status !== 'ENTREGUE'){
            throw new Exception("O status informado é inválido");
        }
        
        $remessa = new Remessa(
            $id,
            $remessaAntiga->getNumeroRemessa(),
            $data_recebimento,
            $data_entrega,
            $status,
            $remessaAntiga->getQuantidadeProtocolos(),
            $usuarioLogado->getId(),
            $observacoes
        );

        $this->remessaRepository->update($remessa);
    }

    public function entregaRemessa(string $id_remessa): void
    {
        $remessa = $this->remessaRepository->findById($id_remessa);
        if($remessa === null){
            throw new Exception("Remessa informada não foi localizada");
        }

        $listaProtocolosRemessa = $this->protocoloRepository->findByRemessa($id_remessa);

        foreach ($listaProtocolosRemessa as $protocolo) {
            if($protocolo->getStatus() !== 'DIGITALIZADO'){
                throw new Exception("A remessa possui protocolos que ainda não foram digitalizados");
            }
        }

        $this->remessaRepository->entregaRemessa($id_remessa);
        $this->protocoloRepository->entregaProtocolos($id_remessa);
    }
}