<?php

use Mateus\ProtocolTrackerV2\Infrastructure\Persistence\ConnectionCreator;
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
    
    $connection = ConnectionCreator::createConnection();

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
            echo "CHEGOU";
            //CONTROLLER
            break;

        case '/logout':
            //CONTROLLER
            break;

        case '/home':
            //CONTROLLER
            break;

        /*----- 
        rota views EQUIPE 
        ----- */
        case '/equipe/dashboard':
            if($method === 'GET'){
                //CARREGAR DASHBOARD DE PRODUÇÃO DA EQUIPE
            }
            break;

        /*----- 
        rota views preparadores 
        ----- */
        case '/preparadores/recebidos':
            if($method === 'GET'){
                //CARREGAR LISTA DE PROTOCOLOS COM STATUS RECEBIDO
            }
            break;

        case '/preparadores/movimentar-protocolo':
            if($method === 'GET'){
                //CARREGAR TELA PARA PREENCHER DADOS ANTES DE MOVIMENTAR
            } else if($method === 'POST'){
                //CONFIRMA A MOVIMENTAÇÃO
            }
            break;

        case '/preparadores/preparados':
            if($method === 'GET'){
                //CARREGAR LISTA DE PROTOCOLOS COM STATUS PREPARADO
            }
            break;

        /*----- 
        rota views digitalizadores 
        ----- */

        case '/digitalizadores/preparados':
            if($method === 'GET'){
                //CARREGAR LISTA DE PROTOCOLOS COM STATUS PREPARADO
            }
            break;

        case '/digitalizadores/movimentar-protocolo':
            if($method === 'GET'){
                //CARREGAR TELA PARA PREENCHER DADOS ANTES DE MOVIMENTAR
            } else if($method === 'POST'){
                //CONFIRMA A MOVIMENTAÇÃO
            }
            break;

        case '/digitalizadores/digitalizados':
            if($method === 'GET'){
                //CARREGAR LISTA DE PROTOCOLOS COM STATUS DIGITALIADO
            }
            break;

        /*----- 
        /ADMIN/REMESSAS
        ----- */
        case '/admin/remessas/nova-remessa':
            if($method === 'GET'){
                //CARREGAR TELA DE NOVA REMESSA
            } else if($method === 'POST'){
                //ENVIA DADOS PARA CRIAR REMESSA - Service = novaRemessa(Usuario $usuarioLogado, string $data_recebimento): void
            }
            break;

        case '/admin/remessas/visualizar-remessas':
            if($method === 'GET'){
                //CARREGAR TELA DE BUSCA DE REMESSAS
            }
            break;

        case '/admin/remessas/editar-remessa':
            if($method === 'GET'){
                //CARREGAR TELA PARA EDITAR UMA REMESSA
            } else if($method === 'POST'){
                //ENVIA DADOS PARA EDIÇÃO DA REMESSA
            }
            break;

        case '/admin/remessas/protocolos':
            if($method === 'GET'){
                //CARREGAR TELA PARA ADICIONAR UM PROTOCOLO NA REMESSA E LISTAGEM DOS PROTOCOLOS DAQUELA REMESSA
            } else if($method === 'POST'){
                //ENVIA DADOS PARA CRIAR PROTOCOLO E VINCULAR À REMESSA ATUAL
            }
            break;

        case '/admin/remessas/editar-protocolo':
            if($method === 'GET'){
                //CARREGAR TELA PARA EDITAR DADOS DO PROTOCOLO
            } else if($method === 'POST'){
                //ENVIA DADOS PARA EDITAR O PROTOCOLO
            }
            break;

        case '/admin/remessas/dashboard':
            if($method === 'GET'){
                //CARREGAR TELA DASHBOARD POR REMESSA
            }
            break;

        /*----- 
        /ADMIN/PROTOCOLOS
        ----- */
        case '/admin/protocolos/buscar-protocolos':
            if($method === 'GET'){
                //CARREGAR TELA BUSCA DE TODOS OS PROTOCOLOS
            }
            break;

        /*----- 
        /ADMIN/PREPARAÇÃO
        ----- */
        case '/admin/preparacao/dashboard':
            if($method === 'GET'){
                //CARREGAR TELA DASHBOARD DE PREPARAÇÃO
            }
            break;

        /*----- 
        /ADMIN/DIGITALIZAÇÃO
        ----- */
        case '/admin/digitalizacao/dashboard':
            if($method === 'GET'){
                //CARREGAR TELA DASHBOARD DE DIGITALIZAÇÃO
            }
            break;

        /*----- 
        /ADMIN/USUARIOS
        ----- */
        case '/admin/usuarios/novo-usuario':
            if($method === 'GET'){
                //CARREGAR TELA PARA CADASTRAR NOVO USUÁRIO
            } else if($method === 'POST'){
                //ENVIA DADOS PARA CRIAÇÃO DO USUÁRIO
            }
            break;

        case '/admin/usuarios/visualizar-usuarios':
            if($method === 'GET'){
                //CARREGAR TELA DE LISTAGEM DE USUARIOS
            }
            break;

        case '/admin/usuarios/editar-usuario':
            if($method === 'GET'){
                //CARREGAR TELA PARA EDIÇÃO DO USUÁRIO
            } else if($method === 'POST'){
                //ENVIA DADOS PARA EDIÇÃO DO USUÁRIO
            }
            break;

        case '/admin/usuarios/resetar-senha':
            if($method === 'GET'){
                //CARREGAR TELA PARA RESET DE SENHA
            } else if($method === 'POST'){
                //ENVIA DADOS PARA RESET DA SENHA
            }
            break;

        /*----- 
        /ADMIN/EQUIPE
        ----- */
        case '/admin/equipe/dashboard':
            if($method === 'GET'){
                //CARREGAR TELA DASHBOARD GERAL DA EQUIPE
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