<?php

namespace Mateus\ProtocolTrackerV2\Admin\Controller;

use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\UsuarioService;

class UsuarioController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private UsuarioService $usuarioService
    ) {
        $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($id_usuario) {
            $this->usuario_logado = $this->usuarioRepository->findById($id_usuario);
        }
        
        if ($this->usuario_logado) {
            $this->permissao = $this->usuario_logado->getPermissao();
        }
    }

    //GET
    public function exibirNovoUsuario()
    {

    }

    //POST
    public function novoUsuario()
    {

    }

    //GET
    public function exibirUsuarios()
    {

    }

    //GET
    public function exibirEditarUsuario()
    {

    }

    //POST
    public function editarUsuario()
    {

    }

    //GET
    public function exibirResetarSenhaUsuario()
    {

    }

    //POST
    public function resetarSenhaUsuario()
    {

    }
}
