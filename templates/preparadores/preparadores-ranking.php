<?php require __DIR__ . '/../components/_header.php'; ?>

<!-- Formulário de Filtro por Data -->
<div class="form-container dashboard-filters">
    <form action="/admin/preparacao/ranking" method="get"> <!-- Verifique se a action está correta -->
        <p>Selecione um período para visualizar o ranking.</p>
        <div class="form-row">
            <div class="form-group">
                <label for="inicio">Data Inicial:</label>
                <!-- CORREÇÃO: O formato do value deve ser Y-m-d para input type="date" -->
                <input type="date" id="inicio" name="inicio" value="<?= htmlspecialchars($inicio->format('Y-m-d')) ?>">
            </div>
            <div class="form-group">
                <label for="fim">Data Final:</label>
                <!-- CORREÇÃO: O formato do value deve ser Y-m-d para input type="date" -->
                <input type="date" id="fim" name="fim" value="<?= htmlspecialchars($fim->format('Y-m-d')) ?>">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">bar_chart</span> Gerar Ranking
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Container para a Tabela de Ranking -->
<div class="listagem-container">
    <h3>Ranking de Preparadores (Período: <?= $inicio->format('d/m/Y') ?> até <?= $fim->format('d/m/Y') ?>)</h3>
    
    <table class="protocolos-table datatable-js">
        <thead>
            <tr>
                <th style="width: 80px;">Posição</th>
                <th>Nome do Preparador</th>
                <th>Protocolos Preparados</th>
                <th>Total de Páginas</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ranking)): ?>
                <tr>
                    <td colspan="4" class="nenhum-resultado">Nenhum dado encontrado para este período.</td>
                </tr>
            <?php endif; ?>

            <?php $posicao = 1; ?>
            <?php foreach ($ranking as $preparador): ?>
                <tr>
                    <td style="text-align: center; font-weight: bold;"><?= $posicao++ ?>º</td>
                    <td><?= htmlspecialchars($preparador['nome']) ?></td>
                    <td><?= htmlspecialchars($preparador['total_protocolos']) ?></td>
                    <td><?= htmlspecialchars($preparador['total_paginas'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>