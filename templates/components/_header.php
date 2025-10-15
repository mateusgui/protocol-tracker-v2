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
                <a href="/home"><img src="/assets/imgs/ProtocolTrackerLogo.png" alt="Logo Protocol Tracker"></a>
            </div>
            
            <nav>
                <ul>
                    <?php if ($permissao === 'preparador'): ?>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">description</span> Recebidos</a>
                            <ul class="submenu">
                                <li><a href="/preparadores/recebidos">Protocolos Recebidos</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">show_chart</span> Preparados</a>
                            <ul class="submenu">
                                <li><a href="/preparadores/preparados">Protocolos Preparados</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">group</span> Equipe</a>
                            <ul class="submenu">
                                <li><a href="/equipe/dashboard">Produtividade Equipe</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($permissao === 'digitalizador'): ?>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">description</span> Preparados</a>
                            <ul class="submenu">
                                <li><a href="/digitalizadores/preparados">Protocolos Preparados</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">show_chart</span> Digitalizados</a>
                            <ul class="submenu">
                                <li><a href="/digitalizadores/digitalizados">Protocolos Digitalizados</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">group</span> Equipe</a>
                            <ul class="submenu">
                                <li><a href="/equipe/dashboard">Produtividade Equipe</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if ($permissao === 'administrador'): ?>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">folder</span> Remessas</a>
                            <ul class="submenu">
                                <li><a href="/admin/remessas/nova-remessa">Nova Remessa</a></li>
                                <li><a href="/admin/remessas/visualizar-remessas">Visualizar Remessas</a></li>
                                <li><a href="/admin/remessas/dashboard">Dashboard Remessas</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">description</span> Protocolos</a>
                            <ul class="submenu">
                                <li><a href="/admin/protocolos/buscar-protocolos">Buscar Protocolos</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">content_cut</span> Preparação</a>
                            <ul class="submenu">
                                <li><a href="/admin/preparacao/dashboard">Dashboard Preparados</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">adf_scanner</span> Digitalização</a>
                            <ul class="submenu">
                                <li><a href="/admin/digitalizacao/dashboard">Dashboard Digitalizados</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu">
                            <a><span class="material-icons-outlined">person</span> Usuários</a>
                            <ul class="submenu">
                                <li><a href="/admin/usuarios/novo-usuario">Novo Usuário</a></li>
                                <li><a href="/admin/usuarios/visualizar-usuarios">Visualizar Usuários</a></li>
                            </ul>
                        </li>
                        <!-- <li class="has-submenu">
                            <a><span class="material-icons-outlined">group</span> Equipe</a>
                            <ul class="submenu">
                                <li><a href="/admin/equipe/dashboard">Produtividade Equipe</a></li>
                            </ul>
                        </li> -->
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>

        <div class="div-conteudo-principal">
            <?php require __DIR__ . '/_content-header.php'; ?>
            <main class="conteudo-principal">