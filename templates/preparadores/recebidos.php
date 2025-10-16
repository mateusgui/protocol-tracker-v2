<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Protocolos Recebidos</h2>

<div class="listagem-container">
    <h3>Protocolos Aguardando Preparação</h3>
    <table class="protocolos-table datatable-js tabela-protocolos">
        <thead>
            <tr>
                <th>Nº do Protocolo</th>
                <th class="acoes-header">Ação</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaProtocolosRecebidos as $protocolo): ?>
                <tr>
                    <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                    <td class="acoes-cell">
                        <button 
                            type="button" 
                            class="btn-acao-preparar open-modal-preparar" 
                            data-protocolo-id="<?= htmlspecialchars($protocolo->getId()) ?>"
                            data-protocolo-numero="<?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?>"
                        >
                            <span class="material-icons-outlined">content_cut</span> Preparar
                        </button>
                    </td>
                    <td class="coluna-observacoes" title="<?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?>">
                        <?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<div id="modal-preparar" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Preparar Protocolo</h4>
            <button class="close-modal-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Você está preparando o Protocolo Nº <strong id="modal-protocolo-numero"></strong>.</p>
            
            <form id="form-preparar-protocolo" action="/preparadores/movimentar-protocolo" method="post">
                
                <input type="hidden" name="id_protocolo" id="modal-protocolo-id">

                <div class="form-group">
                    <label for="id_preparador_modal">Seu Nome (Responsável)</label>
                    <select id="id_preparador_modal" name="id_preparador" required>
                        <option value="">-- Selecione seu nome --</option>
                        <?php foreach ($listaDePreparadores as $preparador): ?>
                            <option value="<?= htmlspecialchars($preparador->getId()) ?>">
                                <?= htmlspecialchars($preparador->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="observacoes_modal">Observações (Opcional)</label>
                    <textarea id="observacoes_modal" name="observacoes" rows="3"></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancelar close-modal-btn">Cancelar</button>
                    <button type="submit" class="btn-salvar">Confirmar Preparação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>