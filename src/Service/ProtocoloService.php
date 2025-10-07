<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\ProtocoloRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\RemessaRepositoryInterface;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Protocolo;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Ramsey\Uuid\Uuid;

class ProtocoloService
{
    public function __construct(
        private ProtocoloRepositoryInterface $protocoloRepository,
        private RemessaRepositoryInterface $remessaRepository,
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function novoProtocolo(Usuario $usuarioLogado, string $id_remessa, string $numero_protocolo): void
    {
        if($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem cadastrar novos protocolos");
        }

        $remessa = $this->remessaRepository->findById($id_remessa);
        if(empty($remessa)){
            throw new Exception("Remessa informada não foi localizada");
        }

        if(strlen($numero_protocolo) !== 6 || !ctype_digit($numero_protocolo)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números");
        }

        if(!is_null($this->protocoloRepository->findByNumber($numero_protocolo))){
            throw new Exception("O número do protocolo informado já foi registrado");
        }

        $id = Uuid::uuid4()->toString();

        $protocolo = new Protocolo(
            $id,
            $id_remessa,
            $numero_protocolo,
            null,
            null,
            null,
            null,
            'RECEBIDO',
            null,
            null
        );

        $this->protocoloRepository->add($protocolo);

    }

    public function atualizarProtocolo(Usuario $usuarioLogado): void
    {

    }

    public function movimentarProtocolo(string $id, string $status): void
    {

    }
}