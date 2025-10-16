<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Editar dados do Usuário</h2>

<div class="form-container home-container"> 
    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/usuarios/editar-usuario" method="post" class="protocolo-form">
        
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->getId()) ?>">

        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($usuario->getNome()) ?>">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($usuario->getEmail()) ?>">
            </div>
            <div class="form-group">
                <label for="cpf_formatado">CPF</label>
                <input type="text" id="cpf_formatado" placeholder="000.000.000-00" required maxlength="14" value="<?= htmlspecialchars($usuario->getCpf()) ?>">
                <input type="hidden" id="cpf_puro" name="cpf" value="<?= htmlspecialchars($usuario->getCpf()) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="permissao">Nível de Permissão</label>
                <select id="permissao" name="permissao" required>
                    <option value="preparador" <?= ($usuario->getPermissao() === 'preparador') ? 'selected' : '' ?>>Preparador</option>
                    <option value="digitalizador" <?= ($usuario->getPermissao() === 'digitalizador') ? 'selected' : '' ?>>Digitalizador</option>
                    <option value="administrador" <?= ($usuario->getPermissao() === 'administrador') ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="1" <?= ($usuario->getStatus()) ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= (!$usuario->getStatus()) ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>
        </div>

        <div class="form-actions-edit">
            <button type="submit" class="btn-salvar">Salvar Alterações</button>
            <a href="/admin/usuarios/visualizar-usuarios" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>