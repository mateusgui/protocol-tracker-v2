<?php

namespace Mateus\ProtocolTrackerV2\Controller;

use Exception;
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
        try {
            $listaProtocolosRecebidos = $this->protocoloRepository->findByStatus('RECEBIDO');
            $listaDePreparadores = $this->usuarioRepository->allByPermissao('preparador');

            $titulo_da_pagina = "Lista de Protocolos Recebidos";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../templates/preparadores/recebidos.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function prepararProtocolo()
    {
        try {
            $id_preparador = $_POST['id_preparador'] ?? null;
            $preparador = $this->usuarioRepository->findById($id_preparador);
            $id_protocolo = $_POST['id_protocolo'] ?? null;
            $observacoes = $_POST['observacoes'] ?? '';
            $protocolo = $this->protocoloRepository->findById($id_protocolo);

            $this->protocoloService->prepararProtocolo($preparador, $id_protocolo, $observacoes);

            $_SESSION['mensagem_sucesso'] = "Protocolo Nº " . $protocolo->getNumeroProtocolo() . " Preparado";

            header('Location: /preparadores/recebidos');
            exit();
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
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
        try {
            $titulo_da_pagina = "Buscar Protocolos";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            $numero_protocolo = $_GET['numero_protocolo'] ?? null;
            $numero_remessa = $_GET['numero_remessa'] ?? null;

            $listaProtocolos = $this->protocoloRepository->search($numero_protocolo, $numero_remessa);

            require __DIR__ . '/../../templates/admin/buscar-protocolos.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function dashboardPreparados()
    {

    }

    //GET
    public function dashboardDigitalizados()
    {

    }

    //GET
    public function adminDashboardEquipe()
    {
        
    }

    private function home(?string $erro = null)
    {
        $titulo_da_pagina = "Home";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../templates/home.php';
    }
}