<?php

namespace Mateus\ProtocolTrackerV2\Controller\Admin;

use Exception;
use Mateus\ProtocolTrackerV2\Controller\LoginController;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Mateus\ProtocolTrackerV2\Repository\RemessaRepository;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\ProtocoloService;
use Mateus\ProtocolTrackerV2\Service\RemessaService;

class RemessaController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private RemessaRepository $remessaRepository,
        private RemessaService $remessaService,
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
    public function exibirNovaRemessa()
    {
        try {
            $titulo_da_pagina = "Nova Remessa";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/nova-remessa.php';
        } catch (Exception $e) {
            //retorno padrão de erro para todas as exibições primárias vindas do menu
            $this->home($e->getMessage());
        }
    }

    //POST
    public function novaRemessa()
    {
        try {
            //public function novaRemessa(Usuario $usuarioLogado, string $data_recebimento, ?string $observacoes): void
            $usuario_logado = $this->usuario_logado;
            $data_recebimento = $_POST['data_recebimento'] ?? '';
            $observacoes = $_POST['observacoes'] ?? null;

            $this->remessaService->novaRemessa($usuario_logado, $data_recebimento, $observacoes);

            header('Location: /admin/remessas/protocolos');
            exit();

        } catch (Exception $e) {
            //retorno padrão de erro para todas as exibições primárias vindas do menu
            $this->home($e->getMessage());
        }
    }

    //GET
    public function exibirRemessas()
    {
        
    }

    //GET
    public function exibirEditarRemessa()
    {
        
    }

    //POST
    public function editarRemessa()
    {
        
    }

    //GET
    public function exibirProtocolos()
    {
        
    }

    //POST
    public function novoProtocolo()
    {
        
    }

    //GET
    public function exibirEditarProtocolo()
    {
        
    }

    //POST
    public function editarProtocolo()
    {
        
    }

    //GET
    public function dashboardRemessa()
    {
        
    }

    private function home(?string $erro = null)
    {
        $titulo_da_pagina = "Home";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../../templates/home.php';
    }
}
