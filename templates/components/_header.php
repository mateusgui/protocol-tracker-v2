<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_da_pagina) ? htmlspecialchars($titulo_da_pagina) : 'Controle de Protocolos' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="icon" href="/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
</head>
<body>

    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
        <div class="flash-message">
            <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
        </div>
        <?php unset($_SESSION['mensagem_sucesso']); ?>
    <?php endif; ?>

    <div class="container-principal">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="/assets/imgs/ProtocolTrackerLogo.png" alt="Logo Protocol Tracker">
            </div>
            
            <nav>
                <ul>
                    <?php $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
                    <li><a href="/home" class="<?= ($uri === '/home') ? 'active' : '' ?>"><span class="material-icons-outlined">add</span> Novo Protocolo</a></li>
                    <li><a href="/busca" class="<?= ($uri === '/busca') ? 'active' : '' ?>"><span class="material-icons-outlined">search</span> Buscar Protocolos</a></li>
                    <li><a href="/dashboard" class="<?= ($uri === '/dashboard') ? 'active' : '' ?>"><span class="material-icons-outlined">speed</span> Dashboard</a></li>

                    <?php if (isset($isAdmin) && $isAdmin === true): ?>
                        <li class="menu-admin-separator"><hr></li>
    
                        <li class="menu-admin-title">
                            <span>ADMINISTRAÇÃO</span>
                        </li>

                        <li>
                            <a href="/admin/protocolos" class="<?= (str_starts_with($uri, '/admin/protocolos')) ? 'active' : '' ?>">
                                <span class="material-icons-outlined">description</span> Protocolos
                            </a>
                        </li>
                        <li>
                            <a href="/admin/dashboard" class="<?= (str_starts_with($uri, '/admin/dashboard')) ? 'active' : '' ?>">
                                <span class="material-icons-outlined">show_chart</span> Produtividade Geral
                            </a>
                        </li>
                        <li>
                            <a href="/admin/usuarios" class="<?= (str_starts_with($uri, '/admin/usuarios')) ? 'active' : '' ?>">
                                <span class="material-icons-outlined">group</span> Usuarios
                            </a>
                        </li>
                        <li>
                            <a href="/admin/auditoria" class="<?= (str_starts_with($uri, '/admin/auditoria')) ? 'active' : '' ?>">
                                <span class="material-icons-outlined">inventory</span> Auditoria
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <div class="div-conteudo-principal">
            <?php require __DIR__ . '/_content-header.php'; ?>
            <main class="conteudo-principal">