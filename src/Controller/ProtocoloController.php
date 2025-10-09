<?php

namespace Mateus\ProtocolTrackerV2\Controller;

use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\ProtocoloService;

class ProtocoloController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private ProtocoloRepository $protocoloRepository,
        private ProtocoloService $protocoloService,
        private UsuarioRepository $usuarioRepository
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
    public function dashboardEquipe()
    {

    }

    //GET
    public function preparadores_listaRecebidos()
    {

    }

    //GET
    public function exibirPrepararProtocolo()
    {

    }

    //POST
    public function prepararProtocolo()
    {

    }

    //GET
    public function preparadores_listaPreparados()
    {

    }

    //GET
    public function digitalizadores_listaPreparados()
    {

    }

    //GET
    public function exibirDigitalizarProtocolo()
    {

    }

    //POST
    public function DigitalizarProtocolo()
    {

    }

    //GET
    public function digitalizadores_listaDigitalizados()
    {

    }

    //GET
    public function buscarProtocolos()
    {

    }

    //GET
    public function dashboardPreparados()
    {

    }

    //GET
    public function dashboardDigitalizados()
    {

    }
}