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
    $url = $_SERVER['PATH_INFO'];
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($url) {
        case '/':
            if ($usuario_esta_logado) {
                header('Location: /home');
            } else {
                header('Location: /login');
            }
            exit();
        
        default:
            # code...
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo "<p>Mensagem do Erro: " . $e->getMessage() . "</p>";
}