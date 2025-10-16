<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Protocolos Preparados</h2>

<div class="listagem-container">
    <h3>Protocolos Preparados</h3>

    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <table id="tabela-preparados" class="protocolos-table datatable-js tabela-protocolos">
        <thead>
            <tr>
                <th>Nº do Protocolo</th>
                <th>Data da Preparação</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $fusoHorarioLocal = new DateTimeZone('America/Campo_Grande');
            ?>

            <?php if (empty($listaProtocolosPreparados)): ?>
                <tr>
                    <td colspan="4" class="nenhum-resultado">Nenhum protocolo preparado encontrado.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($listaProtocolosPreparados as $protocolo): ?>
                <tr>
                    <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                    <td><?= $protocolo->getDataPreparacao() ? $protocolo->getDataPreparacao()->setTimezone($fusoHorarioLocal)->format('d/m/Y H:i') : 'N/A' ?></td>
                    <td class="coluna-observacoes" title="<?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?>">
                        <?= htmlspecialchars($protocolo->getObservacoes() ?? 'N/A') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>