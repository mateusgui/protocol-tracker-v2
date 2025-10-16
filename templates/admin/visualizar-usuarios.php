<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Usuários Cadastrados</h2>

<div class="listagem-container">

    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <table id="tabela-usuarios" class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>CPF</th>
                <th>Permissão</th>
                <th>Status</th>
                <th class="acoes-header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaUsuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario->getNome()) ?></td>
                    <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                    <td><?= htmlspecialchars($usuario->getCpf()) ?></td>
                    <td><?= htmlspecialchars(ucfirst($usuario->getPermissao())) ?></td>
                    <td>
                        <?php if ($usuario->getStatus()): ?>
                            Ativo
                        <?php else: ?>
                            Inativo
                        <?php endif; ?>
                    </td>
                    <td class="acoes-cell">
                        <a href="/admin/usuarios/editar-usuario?id=<?= htmlspecialchars($usuario->getId()) ?>" class="btn-acao btn-editar" title="Editar Usuário">
                            <span class="material-icons-outlined">manage_accounts</span>
                        </a>
                        <a href="/admin/usuarios/resetar-senha?id=<?= htmlspecialchars($usuario->getId()) ?>" class="btn-acao btn-reset-senha" title="Resetar Senha">
                            <span class="material-icons-outlined">password</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>