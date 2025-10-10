<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Remessas Cadastradas</h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<section class="listagem-container">
    <table id="tabela-remessas" class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nº da Remessa</th>
                <th>Status</th>
                <th>Qtd. Protocolos</th>
                <th>Data de Recebimento</th>
                <th>Data de Entrega</th>
                <th>Observações</th>
                <th class="acoes-header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaRemessas as $remessa): ?>
                <tr>
                    <td><?= htmlspecialchars($remessa->getNumeroRemessa()) ?></td>
                    <td><?= htmlspecialchars($remessa->getStatus()) ?></td>
                    <td><?= htmlspecialchars($remessa->getQuantidadeProtocolos() ?? 0) ?></td>
                    <td><?= $remessa->getDataRecebimento() ? $remessa->getDataRecebimento()->format('d/m/Y') : 'N/A' ?></td>
                    <td><?= $remessa->getDataEntrega() ? $remessa->getDataEntrega()->format('d/m/Y') : 'Pendente' ?></td>
                    <td><?= htmlspecialchars($remessa->getObservacoes() ?? '') ?></td>
                    <td class="acoes-cell">
                        <a href="/admin/remessas/editar-remessa?id=<?= htmlspecialchars($remessa->getId()) ?>" class="btn-acao btn-editar" title="Editar Remessa">
                            <span class="material-icons-outlined">edit</span>
                        </a>
                        <a href="/admin/remessas/protocolos?id=<?= htmlspecialchars($remessa->getId()) ?>" class="btn-acao btn-detalhes" title="Ver Detalhes/Protocolos">
                            <span class="material-icons-outlined">visibility</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require __DIR__ . '/../components/_footer.php'; ?>