<?php

namespace Mateus\ProtocolTrackerV2\Model;

use DateTimeImmutable;
use Exception;

class Remessa {

    public function __construct(
        private readonly string $id,
        private readonly ?DateTimeImmutable $data_recebimento,
        private readonly ?DateTimeImmutable $data_entrega,
        private readonly string $status,
        private readonly ?int $quantidade_protocolos,
        private readonly int $id_administrador,
        private readonly ?string $observacoes
    )
    {}

    //GETTERS
    /**
     * @return string id
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable|null data_recebimento
     */
    public function getDataRecebimento(): ?DateTimeImmutable {
        return $this->data_recebimento;
    }

    /**
     * @return DateTimeImmutable|null data_entrega
     */
    public function getDataEntrega(): ?DateTimeImmutable {
        return $this->data_entrega;
    }

    /**
     * @return string status
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return int|null quantidade_protocolos
     */
    public function getQuantidadeProtocolos(): ?int {
        return $this->quantidade_protocolos;
    }

    /**
     * @return int id_administrador
     */
    public function getIdAdministrador(): int {
        return $this->id_administrador;
    }

    /**
     * @return string|null observacoes
     */
    public function getObservacoes(): ?string {
        return $this->observacoes;
    }

    /**
     * Converte um array associativo em Remessa
     * @param array $array Array associativo
     * @return Remessa
     */
    public static function fromArray(array $array): self
    {
        try {
            $data_recebimento = isset($array['data_recebimento']) ? new DateTimeImmutable($array['data_recebimento']) :  null;
            $data_entrega = isset($array['data_entrega']) ? new DateTimeImmutable($array['data_entrega']) : null;
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data");
        }

        return new self(
            $array['id'],
            $data_recebimento,
            $data_entrega,
            $array['status'],
            isset($array['quantidade_protocolos']) ? (int)$array['quantidade_protocolos'] : null,
            (int)$array['id_administrador'],
            $array['observacoes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'data_recebimento' => $this->data_recebimento?->format('Y-m-d H:i:s'),
            'data_entrega' => $this->data_entrega?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'quantidade_protocolos' => $this->quantidade_protocolos ?? 0,
            'id_administrador' => $this->id_administrador,
            'observacoes' => $this->observacoes ?? ''
        ];
    }
}