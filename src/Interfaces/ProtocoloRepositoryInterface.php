<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use Mateus\ProtocolTrackerV2\Model\Protocolo;

interface ProtocoloRepositoryInterface
{
    public function all(): array;
    public function search(?string $numero_protocolo = null, ?string $numero_remessa = null): array;
    public function findByRemessa(string $id_remessa): array;
    public function findById(string $id): ?Protocolo;
    public function findByNumber(string $numero_protocolo): ?Protocolo;
    public function findByStatus(string $status): array;
    public function countByStatus(string $id_remessa, string $status): int;
    public function add(Protocolo $protocolo): void;
    public function update(Protocolo $protocolo): void;
    public function preparaProtocolo(string $id, string $data_preparacao, int $id_preparador, ?string $observacoes): void;
    public function digitalizaProtocolo(string $id, string $data_digitalizacao, int $id_digitalizador, int $quantidade_paginas, ?string $observacoes): void;
}