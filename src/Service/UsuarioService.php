<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Usuario;

class UsuarioService
{
    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function add(): void{

    }

    public function update(): void{

    }

    public function alteraSenha(int $id, string $senha): void{

    }
}


