<?php require __DIR__ . '/../components/_header.php'; ?>

    <?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
    <?php endif; ?>

    <form action="/admin/remessas/editar-protocolo" method="post" class="protocolo-form">
        
        <input type="hidden" name="id" value="<?= htmlspecialchars($protocolo->getId()) ?>">
        <input type="hidden" name="id_remessa" value="<?= htmlspecialchars($remessa->getId()) ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="numero_remessa">Número da Remessa</label>
                <input type="text" id="numero_remessa" name="numero_remessa" value="<?= htmlspecialchars($remessa->getNumeroRemessa()) ?>" readonly>
            </div>
            <div class="form-group">
                <label for="numero_protocolo">Número do Protocolo</label>
                <input type="text" id="numero_protocolo" name="numero_protocolo" required maxlength="6" pattern="\d{6}" value="<?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_preparador">Preparador</label>
                <select id="id_preparador" name="id_preparador">
                    <option value="">-- Nenhum --</option>
                    <?php foreach ($listaDePreparadores as $preparador): ?>
                        <option 
                            value="<?= htmlspecialchars($preparador->getId()) ?>"
                            <?= ($protocolo->getIdPreparador() === $preparador->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($preparador->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data_preparacao">Data da Preparação</label>
                <input type="datetime-local" id="data_preparacao" name="data_preparacao" value="<?= $protocolo->getDataPreparacao() ? $protocolo->getDataPreparacao()->format('Y-m-d\TH:i') : '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_digitalizador">Digitalizador</label>
                <select id="id_digitalizador" name="id_digitalizador">
                    <option value="">-- Nenhum --</option>
                    <?php foreach ($listaDeDigitalizadores as $digitalizador): ?>
                        <option 
                            value="<?= htmlspecialchars($digitalizador->getId()) ?>"
                            <?= ($protocolo->getIdDigitalizador() === $digitalizador->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($digitalizador->getNome()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data_digitalizacao">Data da Digitalização</label>
                <input type="datetime-local" id="data_digitalizacao" name="data_digitalizacao" value="<?= $protocolo->getDataDigitalizacao() ? $protocolo->getDataDigitalizacao()->format('Y-m-d\TH:i') : '' ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="RECEBIDO" <?= ($protocolo->getStatus() === 'RECEBIDO') ? 'selected' : '' ?>>Recebido</option>
                    <option value="PREPARADO" <?= ($protocolo->getStatus() === 'PREPARADO') ? 'selected' : '' ?>>Preparado</option>
                    <option value="DIGITALIZADO" <?= ($protocolo->getStatus() === 'DIGITALIZADO') ? 'selected' : '' ?>>Digitalizado</option>
                    <option value="ENTREGUE" <?= ($protocolo->getStatus() === 'ENTREGUE') ? 'selected' : '' ?>>Entregue</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade_paginas">Quantidade de Páginas</label>
                <input type="number" id="quantidade_paginas" name="quantidade_paginas" min="0" value="<?= htmlspecialchars($protocolo->getQuantidadePaginas() ?? '') ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea id="observacoes" name="observacoes" rows="3"><?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?></textarea>
        </div>

        <div class="form-actions-edit">
            <button type="submit" class="btn-salvar">Salvar Alterações</button>
            <a href="/admin/remessas/protocolos?id=<?= htmlspecialchars($protocolo->getIdRemessa()) ?>" class="btn-cancelar">Cancelar</a>
        </div>
    </form>

<?php require __DIR__ . '/../components/_footer.php'; ?>