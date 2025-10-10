<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Adicionar Nova Remessa</h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<form action="/home" method="post" class="protocolo-form">
    
    <div class="form-row">
        <div class="form-group">
            <label for="data_recebimento">Data do Recebimento:</label>
            <input type="date" id="data_recebimento" name="data_recebimento">
        </div>

        <div class="form-group">
            <label for="id_administrador">Responsável</label>
            <input type="text" id="id_administrador" name="id_administrador" required placeholder="Ex: 2000">
        </div>
    </div>

    <div class="form-group">
        <label for="observacoes">Observações:</label>
        <textarea id="observacoes" name="observacoes" rows="3"></textarea>
    </div>

    <div class="form-actions">
        <button type="submit"><span class="material-icons-outlined">add</span> Registrar Nova Remessa</button>
    </div>
</form>

<?php require __DIR__ . '/../components/_footer.php'; ?>