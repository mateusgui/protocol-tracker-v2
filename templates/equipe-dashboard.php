<?php require __DIR__ . '/components/_header.php'; ?>

<h2>Dashboard de produtividade da equipe</h2>

<div class="metric-card">
    <form action="/equipe/dashboard" method="get" class="busca-form">
        <div class="form-row">
            <div class="form-group">
                <label for="id_remessa">Selecione uma Remessa:</label>
                <select id="id_remessa" name="id_remessa" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($listaDeRemessas as $remessaOpcao): ?>
                        <option 
                            value="<?= htmlspecialchars($remessaOpcao->getId()) ?>"
                            <?= (isset($remessaSelecionada) && $remessaSelecionada->getId() === $remessaOpcao->getId()) ? 'selected' : '' ?>
                        >
                            Nº <?= htmlspecialchars($remessaOpcao->getNumeroRemessa()) ?> (Recebida em: <?= $remessaOpcao->getDataRecebimento()->format('d/m/Y') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-buscar">
                <span class="material-icons-outlined">bar_chart</span> Visualizar Métricas
            </button>
        </div>
    </form>
</div>

<?php if (isset($metricas)): ?>
    <div class="dashboard-container dashboard-metricas">
        <div class="dashboard-row">
            <div class="metric-card">
                <div class="label">Total de Protocolos</div>
                <div class="value total"><?= htmlspecialchars($metricas['total_protocolos']) ?></div>
            </div>
            <div class="metric-card total">
                <div class="label">Total de Páginas Digitalizadas</div>
                <div class="value total"><?= htmlspecialchars($metricas['total_paginas_digitalizadas']) ?></div>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="metric-card">
                <div class="label">Aguardando Preparação</div>
                <div class="value dia"><?= htmlspecialchars($metricas['protocolos_aguardando_preparacao']) ?></div>
            </div>
            <div class="metric-card">
                <div class="label">Protocolos Preparados</div>
                <div class="value mes"><?= htmlspecialchars($metricas['protocolos_preparados']) ?></div>
            </div>
            <div class="metric-card">
                <div class="label">Protocolos Digitalizados</div>
                <div class="value total"><?= htmlspecialchars($metricas['protocolos_digitalizados']) ?></div>
            </div>
        </div>
        
        <div class="dashboard-row">
            <div class="metric-card">
                <div class="label">Digitalização Concluída</div>
                <div class="value"><?= htmlspecialchars($metricas['percentual_digitalizado']) ?>%</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/components/_footer.php'; ?>