<?php

use Mateus\ProtocolTrackerV2\Controller\Admin\RemessaController;
use Mateus\ProtocolTrackerV2\Controller\Admin\UsuarioController;
use Mateus\ProtocolTrackerV2\Controller\LoginController;
use Mateus\ProtocolTrackerV2\Controller\ProtocoloController;
use Mateus\ProtocolTrackerV2\Infrastructure\Persistence\ConnectionCreator;
use Mateus\ProtocolTrackerV2\Infrastructure\Persistence\ConnectionCreatorSqlite;
use Mateus\ProtocolTrackerV2\Repository\ProtocoloRepository;
use Mateus\ProtocolTrackerV2\Repository\RemessaRepository;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\DashboardService;
use Mateus\ProtocolTrackerV2\Service\LoginService;
use Mateus\ProtocolTrackerV2\Service\ProtocoloService;
use Mateus\ProtocolTrackerV2\Service\RemessaService;
use Mateus\ProtocolTrackerV2\Service\UsuarioService;

session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
    
    //$connection = ConnectionCreator::createConnection();
    $connection = ConnectionCreatorSqlite::createConnectionSqlite(); 

    //Repositórios
    $protocoloRepository = new ProtocoloRepository($connection);
    $remessaRepository = new RemessaRepository($connection);
    $usuarioRepository = new UsuarioRepository($connection);

    //Services
    $dashboardService = new DashboardService($protocoloRepository, $remessaRepository);
    $loginService = new LoginService($usuarioRepository);
    $protocoloService = new ProtocoloService($protocoloRepository, $remessaRepository, $usuarioRepository);
    $remessaService = new RemessaService($remessaRepository, $usuarioRepository, $protocoloRepository);
    $usuarioService = new UsuarioService($usuarioRepository);

    //Controllers
    $remessaController = new RemessaController($remessaRepository, $remessaService, $protocoloRepository, $protocoloService, $usuarioRepository, $dashboardService, $usuarioService);
    $usuarioController = new UsuarioController($usuarioRepository, $usuarioService);
    $loginController = new LoginController($usuarioRepository, $usuarioService, $loginService);
    $protocoloController = new ProtocoloController($protocoloRepository, $protocoloService, $usuarioRepository, $remessaRepository, $dashboardService);

    //Verificando autenticação
    $usuario_esta_logado = isset($_SESSION['usuario_logado_id']);
    $usuario_logado_id = $_SESSION['usuario_logado_id'] ?? null;
    
    $usuario_logado = null;
    $permissao = null;

    if ($usuario_logado_id) {
        $usuario_logado = $usuarioRepository->findById($usuario_logado_id);
        if ($usuario_logado) {
            $permissao = $usuario_logado->getPermissao();
        }
    }

    $erro = null;

    // LÓGICA DE ROTEAMENTO 
    // ---------------------
    $url = $_SERVER['REQUEST_URI'];
    $url = parse_url($url, PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($url) {
        case '/':
            if ($usuario_esta_logado) {
                header('Location: /home');
            } else {
                header('Location: /login');
            }
            exit();
        
        case '/login':
            if($method === 'GET'){
                $loginController->exibirLogin();
            } else if($method === 'POST'){
                $loginController->login();
            }
            break;

        case '/logout':
            if($method === 'GET'){
                $loginController->logout();
            }
            break;

        case '/home':
            if($method === 'GET'){
                $loginController->home();
            }
            break;

        /*----- 
        rota views EQUIPE
        ----- */
        case '/equipe/dashboard':
            if($method === 'GET'){
                $protocoloController->dashboardEquipe();
            }
            break;

        /*----- 
        rota views preparadores
        ----- */
        case '/preparadores/recebidos':
            if($method === 'GET'){
                $protocoloController->preparadores_listaRecebidos();
            }
            break;

        case '/preparadores/movimentar-protocolo':
            if($method === 'POST'){
                $protocoloController->prepararProtocolo();
            }
            break;

        case '/preparadores/preparados':
            if($method === 'GET'){
                $protocoloController->preparadores_listaPreparados();
            }
            break;

        /*----- 
        rota views digitalizadores
        ----- */
        case '/digitalizadores/preparados':
            if($method === 'GET'){
                $protocoloController->digitalizadores_listaPreparados();
            }
            break;

        case '/digitalizadores/movimentar-protocolo':
            if($method === 'POST'){
                $protocoloController->DigitalizarProtocolo();
            }
            break;

        case '/digitalizadores/digitalizados':
            if($method === 'GET'){
                $protocoloController->digitalizadores_listaDigitalizados();
            }
            break;

        /*----- 
        /ADMIN/REMESSAS
        ----- */
        case '/admin/remessas/nova-remessa':
            if($method === 'GET'){
                $remessaController->exibirNovaRemessa();
            } else if($method === 'POST'){
                $remessaController->novaRemessa();
            }
            break;

        case '/admin/remessas/visualizar-remessas':
            if($method === 'GET'){
                $remessaController->exibirRemessas();
            }
            break;

        case '/admin/remessas/editar-remessa':
            if($method === 'GET'){
                $remessaController->exibirEditarRemessa();
            } else if($method === 'POST'){
                $remessaController->editarRemessa();
            }
            break;

        case '/admin/remessas/protocolos':
            if ($method === 'POST') {
                $remessaController->novoProtocolo();
            } else if($method === 'GET') {
                $id_remessa = $_GET['id'] ?? null;

                if ($id_remessa === null) {
                    header('Location: /admin/remessas/visualizar-remessas');
                    exit();
                }

                $remessaController->exibirProtocolos($id_remessa);
            }
            break;

        case '/admin/remessas/entregar-remessa':
            if ($method === 'POST') {
                $remessaController->entregaRemessa(); //--------------------- FALTANDO IMPLEMENTAR ------------------------------
            }
            break;

        case '/admin/remessas/editar-protocolo':
            if($method === 'GET'){
                $remessaController->exibirEditarProtocolo();
            } else if($method === 'POST'){
                $remessaController->editarProtocolo();
            }
            break;

        case '/admin/remessas/dashboard':
            if($method === 'GET'){
                $remessaController->dashboardRemessa();
            }
            break;

        /*----- 
        /ADMIN/PROTOCOLOS
        ----- */
        case '/admin/protocolos/buscar-protocolos':
            if($method === 'GET'){
                $protocoloController->buscarProtocolos();
            }
            break;

        /*----- 
        /ADMIN/PREPARAÇÃO --------------------- FALTANDO IMPLEMENTAR ------------------------------
        ----- */
        case '/admin/preparacao/dashboard':
            if($method === 'GET'){
                //ProtocoloController->dashboardPreparados();
            }
            break;

        /*----- 
        /ADMIN/DIGITALIZAÇÃO --------------------- FALTANDO IMPLEMENTAR ------------------------------
        ----- */
        case '/admin/digitalizacao/dashboard':
            if($method === 'GET'){
                //ProtocoloController->dashboardDigitalizados();
            }
            break;

        /*----- 
        /ADMIN/USUARIOS
        ----- */
        case '/admin/usuarios/novo-usuario':
            if($method === 'GET'){
                $usuarioController->exibirNovoUsuario();
            } else if($method === 'POST'){
                $usuarioController->novoUsuario();
            }
            break;

        case '/admin/usuarios/visualizar-usuarios':
            if($method === 'GET'){
                $usuarioController->exibirUsuarios();
            }
            break;

        case '/admin/usuarios/editar-usuario':
            if($method === 'GET'){
                $usuarioController->exibirEditarUsuario();
            } else if($method === 'POST'){
                $usuarioController->editarUsuario();
            }
            break;

        case '/admin/usuarios/resetar-senha':
            if($method === 'GET'){
                $usuarioController->exibirResetarSenhaUsuario();
            } else if($method === 'POST'){
                $usuarioController->resetarSenhaUsuario();
            }
            break;

        /*----- 
        /ADMIN/EQUIPE --------------------- FALTANDO IMPLEMENTAR ------------------------------
        ----- */
        case '/admin/equipe/dashboard':
            if($method === 'GET'){
                //ProtocoloController->adminDashboardEquipe();
            }
            break;
        
        //NOT FOUND
        default:
            http_response_code(500);
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "<p>Mensagem do Erro: " . $e->getMessage() . "</p>";
}