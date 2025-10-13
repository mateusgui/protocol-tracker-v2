<div class="content-header">
    <div class="header-title">
        <h2><?= isset($titulo_da_pagina) ? htmlspecialchars($titulo_da_pagina) : 'Página sem Título' ?></h2>
    </div>
    <div class="logout">
        <?php if (isset($usuario_logado) && $usuario_logado): ?>
            <div class="welcome-message">
                <span>Bem-vindo, <strong><?= htmlspecialchars($usuario_logado->getNome()) ?></strong>!</span>
            </div>
            <div class="dropdown">
                <a href="#" id="user-menu-toggle" class="action-icon" title="Opções do Usuário">
                    <span class="material-icons-outlined">account_circle</span>
                </a>
                <div id="user-menu" class="dropdown-menu">
                    <a href="/logout">
                        <span class="material-icons-outlined">logout</span> Logout
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>