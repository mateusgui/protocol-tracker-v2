<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Editar Remessa Nº <?= htmlspecialchars($remessa->getNumeroRemessa()) ?></h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

    <form action="/admin/remessas/editar-remessa" method="post" class="protocolo-form">

    <input type="hidden" name="id" value="<?= htmlspecialchars($remessa->getId()) ?>">

    <div class="form-row">
        <div class="form-group">
            <label for="numero_remessa">Nº da Remessa</label>
            <input 
                type="text" 
                id="numero_remessa" 
                name="numero_remessa" 
                value="<?= htmlspecialchars($remessa->getNumeroRemessa()) ?>" 
                readonly 
                style="background-color: #e9ecef;"
            >
        </div>
        <div class="form-group">
            <label for="quantidade_protocolos">Qtd. de Protocolos</label>
            <input 
                type="text" 
                id="quantidade_protocolos" 
                name="quantidade_protocolos" 
                value="<?= htmlspecialchars($remessa->getQuantidadeProtocolos() ?? 0) ?>" 
                readonly
                style="background-color: #e9ecef;"
            >
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="data_recebimento">Data do Recebimento:</label>
            <input 
                type="date" 
                id="data_recebimento" 
                name="data_recebimento" 
                value="<?= $remessa->getDataRecebimento() ? $remessa->getDataRecebimento()->format('Y-m-d') : '' ?>"
                required
            >
        </div>
        <div class="form-group">
            <label for="data_entrega">Data da Entrega (Opcional):</label>
            <input 
                type="date" 
                id="data_entrega" 
                name="data_entrega"
                value="<?= $remessa->getDataEntrega() ? $remessa->getDataEntrega()->format('Y-m-d') : '' ?>"
            >
        </div>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="RECEBIDO" <?= ($remessa->getStatus() === 'RECEBIDO') ? 'selected' : '' ?>>Recebido</option>
            <option value="ENTREGUE" <?= ($remessa->getStatus() === 'ENTREGUE') ? 'selected' : '' ?>>Entregue</option>
        </select>
    </div>

    <div class="form-group">
        <label for="observacoes">Observações (Opcional):</label>
        <textarea id="observacoes" name="observacoes" rows="3"><?= htmlspecialchars($remessa->getObservacoes() ?? '') ?></textarea>
    </div>

    <div class="form-actions-edit">
        <a href="/admin/remessas/visualizar-remessas" class="btn-cancelar">Cancelar</a>
        <button type="submit" class="btn-salvar">Salvar Alterações</button>
    </div>
</form>

<?php require __DIR__ . '/../components/_footer.php'; ?>