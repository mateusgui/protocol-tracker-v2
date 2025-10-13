<?php require __DIR__ . '/../components/_header.php'; ?>

<div class="form-container busca-container">
    <form action="/admin/protocolos/buscar-protocolos" method="get" class="busca-form">
        <div class="form-row">
            <div class="form-group">
                <label for="numero_protocolo">Número do Protocolo</label>
                <input type="text" id="numero_protocolo" name="numero_protocolo" value="<?= htmlspecialchars($_GET['numero_protocolo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="numero_remessa">Número da Remessa</label>
                <input type="text" id="numero_remessa" name="numero_remessa" value="<?= htmlspecialchars($_GET['numero_remessa'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-buscar">
                <span class="material-icons-outlined">search</span> Buscar
            </button>
            <a href="/admin/protocolos/buscar-protocolos" class="btn-limpar">Limpar Filtros</a>
        </div>
    </form>
</div>

<div class="listagem-container busca-container">
    <h3>Resultados da Busca</h3>
    <table class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th>Nº Protocolo</th>
                <th>Nº Remessa</th>
                <th>Status</th>
                <th>Data Preparação</th>
                <th>Preparador</th>
                <th>Data Digitalização</th>
                <th>Digitalizador</th>
                <th>Qtd. Páginas</th>
                <th>Observacoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaProtocolos as $protocolo): ?>
                <tr>
                    <td><?= htmlspecialchars($protocolo['numero_protocolo']) ?></td>
                    <td><?= htmlspecialchars($protocolo['numero_remessa']) ?></td>
                    <td><?= htmlspecialchars($protocolo['status_protocolo']) ?></td>
                    <td><?= $protocolo['data_preparacao'] ? (new DateTimeImmutable($protocolo['data_preparacao']))->format('d/m/Y H:i') : 'N/A' ?></td>
                    <td><?= htmlspecialchars($protocolo['id_preparador'] ?? 'N/A') ?></td>
                    <td><?= $protocolo['data_digitalizacao'] ? (new DateTimeImmutable($protocolo['data_digitalizacao']))->format('d/m/Y H:i') : 'N/A' ?></td>
                    <td><?= htmlspecialchars($protocolo['id_digitalizador'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($protocolo['quantidade_paginas'] ?? 'N/A') ?></td>
                    <td class="coluna-observacoes" title="<?= htmlspecialchars($protocolo['observacoes'] ?? '') ?>"><?= htmlspecialchars($protocolo['observacoes']) ?? '' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>