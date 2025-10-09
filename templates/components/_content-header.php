<div class="content-header">
    <div class="header-title">
        <h2><?= isset($titulo_da_pagina) ? htmlspecialchars($titulo_da_pagina) : 'Página sem Título' ?></h2>
    </div>
    <div>
        <?php if (isset($usuario_logado) && $usuario_logado): ?>
            <div class="welcome-message">
                <span>Bem-vindo, <strong><?= htmlspecialchars($usuario_logado->getNome()) ?></strong>!</span>
            </div>
        <?php endif; ?>
    </div>
</div>