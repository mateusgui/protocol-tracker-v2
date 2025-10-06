<?php

namespace Mateus\ProtocolTrackerV2\Model;

use DateTimeImmutable;
use Exception;

class Protocolo {

    public function __construct(
        private readonly string $id,
        private readonly string $id_remessa,
        private readonly string $numero_protocolo,
        private readonly ?DateTimeImmutable $data_preparacao,
        private readonly ?int $id_preparador,
        private readonly ?DateTimeImmutable $data_digitalizacao,
        private readonly ?int $id_digitalizador,
        private readonly string $status,
        private readonly ?int $quantidade_paginas,
        private readonly ?string $observacoes
    )
    {}

    //GETTERS
    /**
     * @return string|null id
     */
    public function getId(): ?string {
        return $this->id;
    }

    /**
     * @return string id_remessa
     */
    public function getIdRemessa(): string {
        return $this->id_remessa;
    }

    /**
     * @return string numero_protocolo
     */
    public function getNumeroProtocolo(): string {
        return $this->numero_protocolo;
    }

    /**
     * @return DateTimeImmutable|null data_preparacao
     */
    public function getDataPreparacao(): ?DateTimeImmutable {
        return $this->data_preparacao;
    }

    /**
     * @return int|null id_preparador
     */
    public function getIdPreparador(): ?int {
        return $this->id_preparador;
    }

    /**
     * @return DateTimeImmutable|null data_digitalizacao
     */
    public function getDataDigitalizacao(): ?DateTimeImmutable {
        return $this->data_digitalizacao;
    }

    /**
     * @return int|null id_digitalizador
     */
    public function getIdDigitalizador(): ?int {
        return $this->id_digitalizador;
    }

    /**
     * @return string status
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return int|null quantidade_paginas
     */
    public function getQuantidadePaginas(): ?int {
        return $this->quantidade_paginas;
    }

    /**
     * @return string|null observacoes
     */
    public function getObservacoes(): ?string {
        return $this->observacoes;
    }

    /**
     * Converte um array associativo em Protocolo
     * @param array $array Array associativo
     * @return Protocolo
     */
    public static function fromArray(array $array): self
    {
        try {
            $data_preparacao = isset($array['data_preparacao']) ? new DateTimeImmutable($array['data_preparacao']) :  null;
            $data_digitalizacao = isset($array['data_digitalizacao']) ? new DateTimeImmutable($array['data_digitalizacao']) :  null;
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data");
        }

        return new self(
            $array['id'],
            $array['id_remessa'],
            $array['numero_protocolo'],
            $data_preparacao,
            isset($array['id_preparador']) ? (int) $array['id_preparador'] : null,
            $data_digitalizacao,
            isset($array['id_digitalizador']) ? (int) $array['id_digitalizador'] : null,
            $array['status'],
            isset($array['quantidade_paginas']) ? (int) $array['quantidade_paginas'] : null,
            $array['observacoes'] ?? null,
        );
    }


    /**
     * Converte um Protocolo em array associativo
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'id_remessa' => $this->id_remessa,
            'numero_protocolo' => $this->numero_protocolo,
            'data_preparacao' => $this->data_preparacao?->format('Y-m-d H:i:s'),
            'id_preparador' => $this->id_preparador ?? null,
            'data_digitalizacao' => $this->data_digitalizacao?->format('Y-m-d H:i:s'),
            'id_digitalizador' => $this->id_digitalizador ?? null,
            'status' => $this->status,
            'quantidade_paginas' => $this->quantidade_paginas ?? 0,
            'observacoes' => $this->observacoes ?? ''
        ];
    }
}