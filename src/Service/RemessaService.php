<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Remessa;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Ramsey\Uuid\Uuid;

class RemessaService
{
    public function __construct(
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function novaRemessa(Usuario $usuarioLogado, string $data_recebimento): void
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
            $data_recebimento,
            null,
            'RECEBIDO',
            null,
            $usuarioLogado->getId(),
            null
        );

        $this->remessaRepository->add($remessa);
    }

    public function atualizarRemessa(Usuario $usuarioLogado, string $data_recebimento, ?string $data_entrega, string $status, ?int $quantidade_protocolos, ?string $observacoes, string $id): void
    {
        if($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem editar remessas.");
        }
        
        $remessaAntiga = $this->remessaRepository->findById($id);
        if(empty($remessaAntiga)){
            throw new Exception("Remessa informada não foi localizada");
        }

        $data_recebimento = is_null($data_recebimento) ? null : new DateTimeImmutable($data_recebimento);
        $data_entrega = is_null($data_entrega) ? null : new DateTimeImmutable($data_entrega);

        if($data_recebimento && $data_entrega){
            if($data_entrega < $data_recebimento){
                throw new Exception("A data de entrega não pode ser anterior à data de recebimento");
            }
        }

        if($status !== 'RECEBIDO' && $status !== 'ENTREGUE'){
            throw new Exception("O status informado é inválido");
        }

        if($quantidade_protocolos < 0){
            throw new Exception("A quantidade de protocolos não pode ser menor do que zero");
        }
        
        $remessa = new Remessa(
            $remessaAntiga->getId(),
            $data_recebimento,
            $data_entrega,
            $status,
            $quantidade_protocolos,
            $usuarioLogado->getId(),
            $observacoes
        );

        $this->remessaRepository->update($remessa);
    }
}