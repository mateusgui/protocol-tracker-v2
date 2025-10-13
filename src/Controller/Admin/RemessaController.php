<?php

namespace Mateus\ProtocolTrackerV2\Controller\Admin;

use Exception;
use Mateus\ProtocolTrackerV2\Controller\LoginController;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Mateus\ProtocolTrackerV2\Repository\RemessaRepository;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\DashboardService;
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
        private UsuarioRepository $usuarioRepository,
        private DashboardService $dashboardService
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
    public function exibirNovaRemessa(?string $erro = null)
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

            $novaRemessa = $this->remessaService->novaRemessa($usuario_logado, $data_recebimento, $observacoes);
            $idNovaRemessa = $novaRemessa->getId();
            $urlRedirecionamento = "/admin/remessas/protocolos?id=" . $idNovaRemessa;

            $_SESSION['mensagem_sucesso'] = "Remessa criada com sucesso!";

            header('Location: ' . $urlRedirecionamento);
            exit();

        } catch (Exception $e) {
            $this->exibirNovaRemessa($e->getMessage());
        }
    }

    //GET
    public function exibirRemessas(?string $erro = null)
    {
        try {
            $titulo_da_pagina = "Visualizar Remessas";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            $listaRemessas = $this->remessaRepository->all();

            require __DIR__ . '/../../../templates/admin/visualizar-remessas.php';
        } catch (Exception $e) {
            //retorno padrão de erro para todas as exibições primárias vindas do menu
            $this->home($e->getMessage());
        }
    }

    //GET
    public function exibirEditarRemessa(?string $erro = null)
    {
        try {
            $id = $_GET['id'] ?? null;
            $remessa = $this->remessaRepository->findById($id);

            $titulo_da_pagina = "Editar Remessa";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/editar-remessa.php';
        } catch (Exception $e) {
            $this->exibirRemessas($e->getMessage());
        }
    }

    //POST
    public function editarRemessa()
    {
        try {
            //public function atualizarRemessa(Usuario $usuarioLogado, string $data_recebimento, ?string $data_entrega, string $status, ?string $observacoes, string $id): void
            $usuario_logado = $this->usuario_logado;
            $data_recebimento = $_POST['data_recebimento'] ?? '';
            $data_entrega = $_POST['data_entrega'] ?? '';
            $status = $_POST['status'] ?? '';
            $observacoes = $_POST['observacoes'] ?? '';
            $id = $_POST['id'] ?? '';

            $this->remessaService->atualizarRemessa($usuario_logado, $data_recebimento, $data_entrega, $status, $observacoes, $id);

            $_SESSION['mensagem_sucesso'] = "Remessa editada com sucesso!";

            header('Location: /admin/remessas/visualizar-remessas');
            exit();
        } catch (Exception $e) {
            $this->exibirEditarRemessa($e->getMessage());
        }
    }

    //GET
    public function exibirProtocolos(?string $id_remessa_param, ?string $erro = null)
    {
        try {
            $id_remessa = $id_remessa_param;
            $remessa = $this->remessaRepository->findById($id_remessa);

            $listaProtocolos = $this->protocoloRepository->findByRemessa($id_remessa);

            $titulo_da_pagina = "Lista de Protocolos";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/visualizar-protocolos.php';
        } catch (Exception $e) {
            $this->exibirRemessas($e->getMessage());
        }
    }

    //POST
    public function novoProtocolo()
    {
        $id_remessa = $_POST['id_remessa'] ?? '';

        try {
            //public function novoProtocolo(Usuario $usuarioLogado, string $id_remessa, string $numero_protocolo): void
            $usuario_logado = $this->usuario_logado;
            $numero_protocolo = $_POST['numero_protocolo'] ?? '';

            $this->protocoloService->novoProtocolo($usuario_logado, $id_remessa, $numero_protocolo);
            $this->remessaRepository->adicionaProtocolo($id_remessa);

            $_SESSION['mensagem_sucesso'] = "Protocolo criado com sucesso!";

            $urlRedirecionamento = "/admin/remessas/protocolos?id=" . $id_remessa;

            header('Location: ' . $urlRedirecionamento);
            exit();
        } catch (Exception $e) {
            $this->exibirProtocolos($id_remessa, $e->getMessage());
        }
    }

    //GET
    public function exibirEditarProtocolo()
    {
        try {
            $id_protocolo = $_GET['id'] ?? null;
            $protocolo = $this->protocoloRepository->findById($id_protocolo);

            $listaDeRemessas = $this->remessaRepository->all();
            $listaDePreparadores = $this->usuarioRepository->allByPermissao('preparador');
            $listaDeDigitalizadores = $this->usuarioRepository->allByPermissao('digitalizador');

            $titulo_da_pagina = "Editar Protocolo";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/editar-protocolo.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function editarProtocolo()
    {
        try {
            //public function atualizarProtocolo(Usuario $usuarioLogado, string $id_remessa, string $numero_protocolo, ?string $data_preparacao, ?int $id_preparador, ?string $data_digitalizacao, ?int $id_digitalizador, string $status, ?int $quantidade_paginas, ?string $observacoes, string $id): void
            $usuario_logado = $this->usuario_logado;
            $id_remessa = $_POST['id_remessa'] ?? '';
            $numero_protocolo = $_POST['numero_protocolo'] ?? '';
            $data_preparacao = !empty($_POST['data_preparacao']) ? $_POST['data_preparacao'] : null;
            $id_preparador = !empty($_POST['id_preparador']) ? (int) $_POST['id_preparador'] : null;//nullable
            $data_digitalizacao = !empty($_POST['data_digitalizacao']) ? $_POST['data_digitalizacao'] : null;
            $id_digitalizador = !empty($_POST['id_digitalizador']) ? (int) $_POST['id_digitalizador'] : null;//nullable
            $status = $_POST['status'] ?? '';
            $quantidade_paginas = !empty($_POST['quantidade_paginas']) ? (int) $_POST['quantidade_paginas'] : null;//nullable
            $observacoes = $_POST['observacoes'] ?? '';//nullable
            $id = $_POST['id'] ?? '';

            $this->protocoloService->atualizarProtocolo($usuario_logado, $id_remessa, $numero_protocolo, $data_preparacao, $id_preparador, $data_digitalizacao, $id_digitalizador, $status, $quantidade_paginas, $observacoes, $id);

            $_SESSION['mensagem_sucesso'] = "Protocolo atualizado com sucesso!";

            $urlRedirecionamento = "/admin/remessas/protocolos?id=" . $id_remessa;

            header('Location: ' . $urlRedirecionamento);
            exit();
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function dashboardRemessa()
{
    try {
        $id_remessa = $_GET['id_remessa'] ?? null;
        
        $metricas = null;
        $remessaSelecionada = null;
        
        $listaDeRemessas = $this->remessaRepository->all();

        if ($id_remessa) {
            $metricas = $this->dashboardService->metricaPorRemessa($id_remessa);
            $remessaSelecionada = $this->remessaRepository->findById($id_remessa);
        }

        $titulo_da_pagina = "Dashboard por Remessa";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../../templates/admin/remessa-dashboard.php';
        
    } catch (Exception $e) {
        $this->home($e->getMessage());
    }
}

    private function home(?string $erro = null)
    {
        $titulo_da_pagina = "Home";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../../templates/home.php';
    }
}
