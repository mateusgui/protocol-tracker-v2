<?php

namespace Mateus\ProtocolTrackerV2\Controller;

use DateTimeImmutable;
use Exception;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Mateus\ProtocolTrackerV2\Repository\RemessaRepository;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\DashboardService;
use Mateus\ProtocolTrackerV2\Service\ProtocoloService;

class ProtocoloController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private ProtocoloRepository $protocoloRepository,
        private ProtocoloService $protocoloService,
        private UsuarioRepository $usuarioRepository,
        private RemessaRepository $remessaRepository,
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
    public function dashboardEquipe()
    {
        try {
            $id_remessa = $_GET['id_remessa'] ?? null;
            
            $metricas = null;
            $remessaSelecionada = null;
            
            $listaDeRemessas = $this->remessaRepository->all();

            foreach ($listaDeRemessas as $remessa) {
                if($remessa->getStatus() === 'RECEBIDO'){
                    $metricas = $this->dashboardService->metricaPorRemessa($remessa->getId());
                    $remessaSelecionada = $this->remessaRepository->findById($remessa->getId());
                }
            }

            if ($id_remessa) {
                $metricas = $this->dashboardService->metricaPorRemessa($id_remessa);
                $remessaSelecionada = $this->remessaRepository->findById($id_remessa);
            }

            $titulo_da_pagina = "Dashboard por Remessa";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../templates/equipe-dashboard.php';
            
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function preparadores_listaRecebidos()
    {
        try {
            $numeroFiltro = $_GET['numero_protocolo'] ?? null;
        
            $listaProtocolosRecebidos = $this->protocoloRepository->searchByNumeroEStatus($numeroFiltro, 'RECEBIDO');
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
        try {
            $numeroFiltro = $_GET['numero_protocolo'] ?? null;
        
            $listaProtocolosPreparados = $this->protocoloRepository->searchByNumeroEStatus($numeroFiltro, 'PREPARADO');

            $titulo_da_pagina = "Lista de Protocolos Recebidos";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../templates/preparadores/preparados.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function digitalizadores_listaPreparados()
    {
        try {
            $numeroFiltro = $_GET['numero_protocolo'] ?? null;
        
            $listaProtocolosPreparados = $this->protocoloRepository->searchByNumeroEStatus($numeroFiltro, 'PREPARADO');
            
            $digitalizador = $this->usuario_logado;

            $titulo_da_pagina = "Lista de Protocolos Preparados";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../templates/digitalizadores/preparados.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function DigitalizarProtocolo()
    {
        try {
            $digitalizador = $this->usuario_logado;
            $id_protocolo = $_POST['id_protocolo'] ?? null;
            $quantidade_paginas = $_POST['quantidade_paginas'];
            $observacoes = $_POST['observacoes'] ?? '';
            $protocolo = $this->protocoloRepository->findById($id_protocolo);

            $this->protocoloService->digitalizarProtocolo($digitalizador, $id_protocolo, $quantidade_paginas, $observacoes);

            $_SESSION['mensagem_sucesso'] = "Protocolo Nº " . $protocolo->getNumeroProtocolo() . " Digitalizado";

            header('Location: /digitalizadores/preparados');
            exit();
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function digitalizadores_listaDigitalizados()
    {
        try {
            $numeroFiltro = $_GET['numero_protocolo'] ?? null;
        
            $listaProtocolosDigitalizados = $this->protocoloRepository->searchByNumeroEStatus($numeroFiltro, 'DIGITALIZADO');

            $titulo_da_pagina = "Lista de Protocolos Digitalizados";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../templates/digitalizadores/digitalizados.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
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

            $listaDePreparadores = $this->usuarioRepository->allByPermissao('preparador');
            $listaDeDigitalizadores = $this->usuarioRepository->allByPermissao('digitalizador');
            $listaProtocolos = $this->protocoloRepository->search($numero_protocolo, $numero_remessa);

            require __DIR__ . '/../../templates/admin/buscar-protocolos.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function dashboardPreparados()
    {
        try {
            $dia_selecionado = !empty($_GET['dia']) ? new DateTimeImmutable($_GET['dia']) : new DateTimeImmutable('now');
            $mes_selecionado = !empty($_GET['mes']) ? new DateTimeImmutable($_GET['mes']) : new DateTimeImmutable('now');
            
            if ($this->permissao === 'administrador') {
                $id_usuario_selecionado = !empty($_GET['id_selecionado']) ? (int) $_GET['id_selecionado'] : null;
            } else {
                $id_usuario_selecionado = $this->usuario_logado->getId();
            }
            
            $listaDePreparadores = $this->usuarioRepository->allByPermissao('preparador');

            $protocolosDia = $this->dashboardService->protocolosPreparadosPorDia($id_usuario_selecionado, $dia_selecionado);
            $paginasDia = $this->dashboardService->paginasPreparadasPorDia($id_usuario_selecionado, $dia_selecionado);

            $protocolosMes = $this->dashboardService->protocolosPreparadosPorMes($id_usuario_selecionado, $mes_selecionado);
            $paginasMes = $this->dashboardService->paginasPreparadasPorMes($id_usuario_selecionado, $mes_selecionado);

            $titulo_da_pagina = "Dashboard de Preparadores";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;
            
            require __DIR__ . '/../../templates/preparadores/preparados-dashboard.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function dashboardDigitalizados()
    {
        try {
            $dia_selecionado = !empty($_GET['dia']) ? new DateTimeImmutable($_GET['dia']) : new DateTimeImmutable('now');
            $mes_selecionado = !empty($_GET['mes']) ? new DateTimeImmutable($_GET['mes']) : new DateTimeImmutable('now');
            
            if ($this->permissao === 'administrador') {
                $id_usuario_selecionado = !empty($_GET['id_selecionado']) ? (int) $_GET['id_selecionado'] : null;
            } else {
                $id_usuario_selecionado = $this->usuario_logado->getId();
            }
            
            $listaDeDigitalizadores = $this->usuarioRepository->allByPermissao('digitalizador');

            $protocolosDia = $this->dashboardService->protocolosDigitalizadosPorDia($id_usuario_selecionado, $dia_selecionado);
            $paginasDia = $this->dashboardService->paginasDigitalizadasPorDia($id_usuario_selecionado, $dia_selecionado);

            $protocolosMes = $this->dashboardService->protocolosDigitalizadosPorMes($id_usuario_selecionado, $mes_selecionado);
            $paginasMes = $this->dashboardService->paginasDigitalizadasPorMes($id_usuario_selecionado, $mes_selecionado);

            $titulo_da_pagina = "Dashboard de Digitalizadores";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;
            
            require __DIR__ . '/../../templates/digitalizadores/digitalizados-dashboard.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
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