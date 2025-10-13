<?php require __DIR__ . '/../components/_header.php'; ?>

<div class="form-container home-container"> 
    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/usuarios/resetar-senha" method="post" class="protocolo-form">
        
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->getId()) ?>">

        <div class="form-group">
            <label for="nome_usuario">Usuário</label>
            <input 
                type="text" 
                id="nome_usuario" 
                name="nome_usuario" 
                value="<?= htmlspecialchars($usuario->getNome()) ?>" 
                readonly 
                style="background-color: #e9ecef;"
            >
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="nova_senha">Nova Senha (mínimo 6 caracteres)</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>
        </div>

        <div class="form-actions-edit">
            <button type="submit" class="btn-salvar">Resetar Senha</button>
            <a href="/admin/usuarios/visualizar-usuarios" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>