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
    $dashboardService = new DashboardService($protocoloRepository);
    $loginService = new LoginService($usuarioRepository);
    $protocoloService = new ProtocoloService($protocoloRepository, $remessaRepository, $usuarioRepository);
    $remessaService = new RemessaService($remessaRepository, $usuarioRepository);
    $usuarioService = new UsuarioService($usuarioRepository);

    //Controllers
    $remessaController = new RemessaController($remessaRepository, $remessaService, $protocoloRepository, $protocoloService, $usuarioRepository);
    $usuarioController = new UsuarioController($usuarioRepository, $usuarioService);
    $loginController = new LoginController($usuarioRepository, $usuarioService, $loginService);
    $protocoloController = new ProtocoloController($protocoloRepository, $protocoloService, $usuarioRepository);

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
                //ProtocoloController->dashboardEquipe();
            }
            break;

        /*----- 
        rota views preparadores 
        ----- */
        case '/preparadores/recebidos':
            if($method === 'GET'){
                //ProtocoloController->preparadores_listaRecebidos();
            }
            break;

        case '/preparadores/movimentar-protocolo':
            if($method === 'GET'){
                //ProtocoloController->exibirPrepararProtocolo();
            } else if($method === 'POST'){
                //ProtocoloController->prepararProtocolo();
            }
            break;

        case '/preparadores/preparados':
            if($method === 'GET'){
                //ProtocoloController->preparadores_listaPreparados();
            }
            break;

        /*----- 
        rota views digitalizadores 
        ----- */
        case '/digitalizadores/preparados':
            if($method === 'GET'){
                //ProtocoloController->digitalizadores_listaPreparados();
            }
            break;

        case '/digitalizadores/movimentar-protocolo':
            if($method === 'GET'){
                //ProtocoloController->exibirDigitalizarProtocolo();
            } else if($method === 'POST'){
                //ProtocoloController->DigitalizarProtocolo();
            }
            break;

        case '/digitalizadores/digitalizados':
            if($method === 'GET'){
                //ProtocoloController->digitalizadores_listaDigitalizados();
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
            if($method === 'GET'){
                //RemessaController->exibirProtocolos();
            } else if($method === 'POST'){
                //RemessaController->novoProtocolo();
            }
            break;

        case '/admin/remessas/editar-protocolo':
            if($method === 'GET'){
                //RemessaController->exibirEditarProtocolo();
            } else if($method === 'POST'){
                //RemessaController->editarProtocolo();
            }
            break;

        case '/admin/remessas/dashboard':
            if($method === 'GET'){
                //RemessaController->dashboardRemessa();
            }
            break;

        /*----- 
        /ADMIN/PROTOCOLOS
        ----- */
        case '/admin/protocolos/buscar-protocolos':
            if($method === 'GET'){
                //ProtocoloController->buscarProtocolos();
            }
            break;

        /*----- 
        /ADMIN/PREPARAÇÃO
        ----- */
        case '/admin/preparacao/dashboard':
            if($method === 'GET'){
                //ProtocoloController->dashboardPreparados();
            }
            break;

        /*----- 
        /ADMIN/DIGITALIZAÇÃO
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
                //UsuarioController->exibirNovoUsuario();
            } else if($method === 'POST'){
                //UsuarioController->novoUsuario();
            }
            break;

        case '/admin/usuarios/visualizar-usuarios':
            if($method === 'GET'){
                //UsuarioController->exibirUsuarios();
            }
            break;

        case '/admin/usuarios/editar-usuario':
            if($method === 'GET'){
                //UsuarioController->exibirEditarUsuario();
            } else if($method === 'POST'){
                //UsuarioController->editarUsuario();
            }
            break;

        case '/admin/usuarios/resetar-senha':
            if($method === 'GET'){
                //UsuarioController->exibirResetarSenhaUsuario();
            } else if($method === 'POST'){
                //UsuarioController->resetarSenhaUsuario();
            }
            break;

        /*----- 
        /ADMIN/EQUIPE
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