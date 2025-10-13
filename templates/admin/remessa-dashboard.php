<?php require __DIR__ . '/../components/_header.php'; ?>

<div class="form-container busca-container">
    <form action="/admin/remessas/dashboard" method="get" class="busca-form">
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
    <div class="dashboard-container">
        <div class="dashboard-row">
            <div class="metric-card">
                <div class="label">Status da Remessa</div>
                <div class="value status"><?= htmlspecialchars($metricas['status_remessa']) ?></div>
            </div>
            <div class="metric-card total">
                <div class="label">Total de Protocolos</div>
                <div class="value total"><?= htmlspecialchars($metricas['total_protocolos']) ?></div>
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
                <div class="label">Progresso da Remessa</div>
                <div class="value"><?= htmlspecialchars($metricas['percentual_conclusao']) ?>%</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../components/_footer.php'; ?>