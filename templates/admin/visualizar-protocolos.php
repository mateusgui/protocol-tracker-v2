<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Visualizar Protocolos</h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<div class="container-principal-busca">

    <form action="/admin/remessas/protocolos" method="post" class="busca-form-container">
        
        <input type="hidden" name="id_remessa" value="<?= htmlspecialchars($id_remessa) ?>">

        <div class="form-group">
            <label for="numero_protocolo">Número do Protocolo (6 dígitos)</label>
            <input type="text" id="numero_protocolo" name="numero_protocolo" required maxlength="6" pattern="\d{6}"placeholder="Ex: 123456">

            <label for="numero_remessa">Número da Remessa</label>
            <input type="text" id="numero_remessa" readonly  name="numero_remessa" value="<?= htmlspecialchars($remessa->getNumeroRemessa()) ?>">
        </div>

        <div class="form-actions">
            <button type="submit">
                <span class="material-icons-outlined">add</span> Adicionar
            </button>
        </div>
    </form>

<section class="listagem-container">
    <h3>Protocolos da Remessa</h3>

    <table id="tabela-protocolos-remessa" class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nº do Protocolo</th>
                <th>Status</th>
                <th>Preparador</th>
                <th>Data da Preparação</th>
                <th>Digitalizador</th>
                <th>Data da Digitalização</th>
                <th>Qtd. Páginas</th>
                <th class="acoes-header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaProtocolos as $protocolo): ?>
                <tr>
                    <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                    <td><?= htmlspecialchars($protocolo->getStatus()) ?></td>
                    <td>
                        <?php
                            // Lógica para buscar o nome do preparador
                            // (Esta parte exigiria que o Controller buscasse e passasse a lista de usuários)
                            // Por enquanto, exibiremos o ID
                            echo htmlspecialchars($protocolo->getIdPreparador() ?? 'N/A');
                        ?>
                    </td>
                    <td><?= $protocolo->getDataPreparacao() ? $protocolo->getDataPreparacao()->format('d/m/Y H:i') : 'Pendente' ?></td>
                    <td>
                        <?php
                            // Lógica para buscar o nome do digitalizador
                            echo htmlspecialchars($protocolo->getIdDigitalizador() ?? 'N/A');
                        ?>
                    </td>
                    <td><?= $protocolo->getDataDigitalizacao() ? $protocolo->getDataDigitalizacao()->format('d/m/Y H:i') : 'Pendente' ?></td>
                    <td><?= htmlspecialchars($protocolo->getQuantidadePaginas() ?? 'N/A') ?></td>
                    <td class="acoes-cell">
                        <a href="/admin/remessas/editar-protocolo?id=<?= htmlspecialchars($protocolo->getId()) ?>" class="btn-acao btn-editar" title="Editar Protocolo">
                            <span class="material-icons-outlined">edit</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>