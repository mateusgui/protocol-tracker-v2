<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;

class LoginService
{
    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    /**
     * Realiza validações para que um usuário possa fazer login
     * @throws Exception Se os dados forem inválidos.
     * @param string $cpf CPF informado pelo Usuario
     * @param string $senha senha informada pelo Usuario
     * @return void Array associativo
     */
    public function login(string $cpf, string $senha): void
    {
        $usuario = $this->usuarioRepository->findByCpf($cpf);

        if(!$usuario){
            throw new Exception("Usuário não localizado");
        }

        if(!$usuario->getStatus()){
            throw new Exception("Usuário inativo");
        }

        $senhaCorreta = password_verify($senha, $usuario?->getHashSenha() ?? '');

        if ($usuario === null || !$senhaCorreta) {
            throw new Exception("CPF ou senha inválidos.");
        }

        session_regenerate_id(true);

        $_SESSION['usuario_logado_id'] = $usuario->getId();
    }
}