<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Cadastrar novo Usuário</h2>

<div class="form-container home-container"> 
    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form action="/admin/usuarios/novo-usuario" method="post" class="protocolo-form">
        
        <div class="form-group">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="cpf_formatado">CPF</label>
                <input type="text" id="cpf_formatado" placeholder="000.000.000-00" required maxlength="14">
                <input type="hidden" id="cpf_puro" name="cpf">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="senha">Senha (mínimo 6 caracteres)</label>
                <input type="password" id="senha" name="senha" required>
            </div>
        </div>

        <div class="form-group">
            <label for="permissao">Nível de Permissão</label>
            <select id="permissao" name="permissao" required>
                <option value="">-- Selecione uma opção --</option>
                <option value="preparador">Preparador</option>
                <option value="digitalizador">Digitalizador</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>

        <div class="form-actions-edit">
            <button type="submit" class="btn-salvar">Salvar Usuário</button>
            <a href="/admin/usuarios/visualizar-usuarios" class="btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>