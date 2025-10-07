<?php

namespace Mateus\ProtocolTrackerV2\Interfaces;

use Mateus\ProtocolTrackerV2\Model\Usuario;

interface UsuarioRepositoryInterface
{
    public function all(): array;
    public function add(Usuario $usuario): void;
    public function update(Usuario $usuario): void;
    public function alteraSenha(int $id, string $hash_senha): void;
}