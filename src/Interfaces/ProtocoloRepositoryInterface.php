<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use DateTimeImmutable;
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
    public function countByDiaPreparador(int $id_preparador, DateTimeImmutable $dia): int;
    public function countByMesPreparador(int $id_preparador, DateTimeImmutable $data_preparacao): int;
    public function sumByDiaDigitalizador(int $id_digitalizador, DateTimeImmutable $dia): int;
    public function countByDiaDigitalizador(int $id_digitalizador, DateTimeImmutable $data_digitalizacao): int;
    public function sumByMesDigitalizador(int $id_digitalizador, DateTimeImmutable $mes): int;
    public function countByMesDigitalizador(int $id_digitalizador, DateTimeImmutable $mes): int;
    public function sumPagesByRemessaAndStatus(string $id_remessa, string $status): int;
    public function add(Protocolo $protocolo): void;
    public function update(Protocolo $protocolo): void;
    public function preparaProtocolo(string $id, string $data_preparacao, int $id_preparador, ?string $observacoes): void;
    public function digitalizaProtocolo(string $id, string $data_digitalizacao, int $id_digitalizador, int $quantidade_paginas, ?string $observacoes): void;
    public function entregaProtocolos(string $id_remessa): void;
}