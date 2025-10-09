<?php

namespace Mateus\ProtocolTrackerV2\Controller;

use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Service\LoginService;
use Mateus\ProtocolTrackerV2\Service\UsuarioService;

class LoginController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepository,
        private UsuarioService $usuarioService,
        private LoginService $loginService,
    ) {
        $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($id_usuario) {
            $this->usuario_logado = $this->usuarioRepository->findById($id_usuario);
        }
        
        if ($this->usuario_logado) {
            $this->permissao = $this->usuario_logado->getPermissao();
        }
    }

    public function exibirLogin(?string $erro = null)
    {
        $titulo_da_pagina = "Login Protocol Tracker";

        require __DIR__ . '/../../templates/login.php';
    }

    public function login()
    {
        try {
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $this->loginService->login($cpf, $senha);

            header('Location: /home');
            exit();

        } catch (Exception $e) {
            $this->exibirLogin($e->getMessage());
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        header('Location: /login');
        exit();
    }

    public function home(?string $erro = null)
    {
        $titulo_da_pagina = "Home";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../templates/home.php';
    }
}