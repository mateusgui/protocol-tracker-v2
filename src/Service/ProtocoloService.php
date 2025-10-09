<?php

namespace Mateus\ProtocolTrackerV2\Service;

use DateTimeImmutable;
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
            throw new Exception("Apenas administradores podem cadastrar novos protocolos.");
        }

        $remessa = $this->remessaRepository->findById($id_remessa);
        if(empty($remessa)){
            throw new Exception("Remessa informada não foi localizada.");
        }

        if(strlen($numero_protocolo) !== 6 || !ctype_digit($numero_protocolo)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números.");
        }

        if(!is_null($this->protocoloRepository->findByNumber($numero_protocolo))){
            throw new Exception("O número do protocolo informado já foi registrado.");
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

    public function atualizarProtocolo(Usuario $usuarioLogado, string $id_remessa, string $numero_protocolo, ?string $data_preparacao, ?int $id_preparador, ?string $data_digitalizacao, ?int $id_digitalizador, string $status, ?int $quantidade_paginas, ?string $observacoes, string $id): void
    {
        if($usuarioLogado->getPermissao() !== 'administrador') {
            throw new Exception("Apenas administradores podem cadastrar novos protocolos.");
        }

        $remessa = $this->remessaRepository->findById($id_remessa);
        if($remessa === null){
            throw new Exception("Remessa informada não foi localizada.");
        }

        $protocoloAntigo = $this->protocoloRepository->findById($id);
        if($protocoloAntigo === null){
            throw new Exception("Protocolo informado não foi localizado");
        }

        $protocoloExistente = $this->protocoloRepository->findByNumber($numero_protocolo);
        if ($protocoloExistente !== null && $protocoloExistente->getId() !== $id) {
            throw new Exception("Não é possível informar o número de um protocolo que já existe.");
        }

        if(strlen($numero_protocolo) !== 6 || !ctype_digit($numero_protocolo)){
            throw new Exception("O número do protocolo precisa ter exatamente 6 dígitos e possuir somente números.");
        }

        if($id_preparador !== null){
            $preparador = $this->usuarioRepository->findById($id_preparador);
            if(!$preparador){
                throw new Exception("O preparador informado não foi localizado.");
            }
        }

        if($id_digitalizador !== null){
            $digitalizador = $this->usuarioRepository->findById($id_digitalizador);
            if(!$digitalizador){
                throw new Exception("O digitalizador informado não foi localizado.");
            }
        }
        
        $data_preparacao = is_null($data_preparacao) ? null : new DateTimeImmutable($data_preparacao);
        $data_digitalizacao = is_null($data_digitalizacao) ? null : new DateTimeImmutable($data_digitalizacao);

        if($status !== 'RECEBIDO' && $status !== 'PREPARADO' && $status !== 'DIGITALIZADO' && $status !== 'ENTREGUE'){
            throw new Exception("O status informado é inválido");
        }

        if($quantidade_paginas < 1 && $quantidade_paginas !== null){
            throw new Exception("A quantidade de páginas precisa ser maior que zero.");
        }

        $protocolo = new Protocolo(
            $id,
            $id_remessa,
            $numero_protocolo,
            $data_preparacao,
            $id_preparador,
            $data_digitalizacao,
            $id_digitalizador,
            $status,
            $quantidade_paginas,
            $observacoes
        );

        $this->protocoloRepository->update($protocolo);
    }

    public function prepararProtocolo(string $id, Usuario $preparador): void
    {
        if ($preparador->getPermissao() !== 'preparador') {
            throw new Exception("Apenas preparadores podem executar esta ação.");
        }

        $protocolo = $this->protocoloRepository->findById($id);
        if ($protocolo === null) {
            throw new Exception("Protocolo informado não foi localizado.");
        }
        if ($protocolo->getStatus() !== 'RECEBIDO') {
            throw new Exception("Apenas protocolos com status 'RECEBIDO' podem ser preparados.");
        }

        $data_preparacao = new DateTimeImmutable('now')->format('Y-m-d H:i:s');

        $this->protocoloRepository->preparaProtocolo($id, $data_preparacao, $preparador->getId());
    }

    public function digitalizarProtocolo(string $id, int $quantidade_paginas, Usuario $digitalizador): void
    {
        if ($digitalizador->getPermissao() !== 'digitalizador') {
            throw new Exception("Apenas digitalizadores podem executar esta ação.");
        }

        $protocolo = $this->protocoloRepository->findById($id);
        if ($protocolo === null) {
            throw new Exception("Protocolo informado não foi localizado.");
        }
        if ($protocolo->getStatus() !== 'PREPARADO') {
            throw new Exception("Apenas protocolos com status 'PREPARADO' podem ser digitalizados.");
        }
        if ($quantidade_paginas < 1) {
            throw new Exception("A quantidade de páginas deve ser maior que zero.");
        }

        $data_digitalizacao = new DateTimeImmutable('now')->format('Y-m-d H:i:s');

        $this->protocoloRepository->digitalizaProtocolo($id, $data_digitalizacao, $digitalizador->getId(), $quantidade_paginas);
    }
}