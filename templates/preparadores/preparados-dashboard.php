<?php require __DIR__ . '/../components/_header.php'; ?>

<div class="form-container dashboard-filters">
    <form action="/admin/preparacao/dashboard" method="get">
        <p>Selecione um período para visualizar a produtividade.</p>
        <div class="form-row">
            <?php if ($permissao === 'administrador'): ?>
                <div class="form-group">
                    <label for="id_selecionado">Selecione um Preparador:</label>
                    <select id="id_selecionado" name="id_selecionado">
                        <option value="">-- Todos --</option>
                        <?php foreach ($listaDePreparadores as $preparador): ?>
                            <option 
                                value="<?= htmlspecialchars($preparador->getId()) ?>"
                                <?= (isset($id_usuario_selecionado) && $id_usuario_selecionado == $preparador->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($preparador->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="dia">Produtividade do Dia:</label>
                <input type="date" id="dia" name="dia" value="<?= $dia_selecionado->format('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label for="mes">Produtividade do Mês:</label>
                <input type="month" id="mes" name="mes" value="<?= $mes_selecionado->format('Y-m') ?>">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">visibility</span> Visualizar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="dashboard-container">
    <div class="dashboard-row">
        <div class="metric-card">
            <div class="label">Páginas Preparadas no Dia (<?= $dia_selecionado->format('d/m/Y') ?>)</div>
            <div class="value dia"><?= htmlspecialchars($paginasDia) ?></div>
        </div>
        <div class="metric-card">
            <div class="label">Páginas Preparadas no Mês (<?= $mes_selecionado->format('m/Y') ?>)</div>
            <div class="value mes"><?= htmlspecialchars($paginasMes) ?></div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <div class="metric-card">
            <div class="label">Protocolos Preparados no Dia (<?= $dia_selecionado->format('d/m/Y') ?>)</div>
            <div class="value dia"><?= htmlspecialchars($protocolosDia) ?></div>
        </div>

        <div class="metric-card">
            <div class="label">Protocolos Preparados no Mês (<?= $mes_selecionado->format('m/Y') ?>)</div>
            <div class="value mes"><?= htmlspecialchars($protocolosMes) ?></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>