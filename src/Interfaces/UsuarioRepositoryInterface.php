<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use Mateus\ProtocolTrackerV2\Model\Usuario;

interface UsuarioRepositoryInterface
{
    public function all(): array;
    public function findById(int $id): ?Usuario;
    public function findByCpf(string $cpf): ?Usuario;
    public function findByEmail(string $email): ?Usuario;
    public function add(Usuario $usuario): void;
    public function update(Usuario $usuario): void;
    public function alteraSenha(int $id, string $hash_senha): void;
}