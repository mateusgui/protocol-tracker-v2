<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Protocolos Digitalizados</h2>

<div class="listagem-container">
    <h3>Protocolos Digitalizados</h3>

    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <table id="tabela-digitalizados" class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nº da Remessa</th>
                <th>Nº do Protocolo</th>
                <th>Data da Digitalização</th>
                <th>Qtd. Páginas</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $fusoHorarioLocal = new DateTimeZone('America/Campo_Grande');
            ?>

            <?php if (empty($listaProtocolosDigitalizados)): ?>
                <tr>
                    <td colspan="5" class="nenhum-resultado">Nenhum protocolo digitalizado encontrado.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($listaProtocolosDigitalizados as $protocolo): ?>
                <tr>
                    <td>
                        <?php
                            // Lógica para buscar o número da remessa (via JOIN no Repository)
                            echo htmlspecialchars($protocolo->getIdRemessa()); 
                        ?>
                    </td>
                    <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                    <td><?= $protocolo->getDataDigitalizacao() ? $protocolo->getDataDigitalizacao()->setTimezone($fusoHorarioLocal)->format('d/m/Y H:i') : 'N/A' ?></td>
                    <td><?= htmlspecialchars($protocolo->getQuantidadePaginas() ?? 'N/A') ?></td>
                    <td class="coluna-observacoes" title="<?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?>">
                        <?= htmlspecialchars($protocolo->getObservacoes() ?? 'N/A') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>