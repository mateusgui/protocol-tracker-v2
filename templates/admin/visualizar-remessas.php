<?php require __DIR__ . '/../components/_header.php'; ?>

<div class="container-principal-busca">

    <?php if (isset($erro) && $erro): ?>
        <div class="error-message">
            <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <div class="listagem-container">
        <h3>Remessas Cadastradas</h3>
        <table id="tabela-protocolos" class="protocolos-table datatable-js">
            <thead>
                <tr>
                    <th>Nº da Remessa</th>
                    <th>Status</th>
                    <th>Qtd. Protocolos</th>
                    <th>Data de Recebimento</th>
                    <th>Data de Entrega</th>
                    <th class="acoes-header">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaRemessas as $remessa): ?>
                    <tr>
                        <td><?= htmlspecialchars($remessa->getNumeroRemessa()) ?></td>
                        <td><?= htmlspecialchars($remessa->getStatus()) ?></td>
                        <td><?= htmlspecialchars($remessa->getQuantidadeProtocolos() ?? 0) ?></td>
                        <td><?= $remessa->getDataRecebimento() ? $remessa->getDataRecebimento()->format('d/m/Y') : 'N/A' ?></td>
                        <td><?= $remessa->getDataEntrega() ? $remessa->getDataEntrega()->format('d/m/Y') : 'Pendente' ?></td>
                        <td class="acoes-cell">
                            <a href="/admin/remessas/protocolos?id=<?= htmlspecialchars($remessa->getId()) ?>" class="btn-acao btn-detalhes" title="Ver Protocolos">
                                <span class="material-icons-outlined">visibility</span>
                            </a>
                            <a href="/admin/remessas/editar-remessa?id=<?= htmlspecialchars($remessa->getId()) ?>" class="btn-acao btn-editar" title="Editar Remessa">
                                <span class="material-icons-outlined">edit</span>
                            </a>
                            <?php if ($remessa->getStatus() !== 'ENTREGUE'): ?>
                                <button 
                                    type="button" 
                                    class="btn-acao btn-entregar open-modal-entregar"
                                    data-remessa-id="<?= htmlspecialchars($remessa->getId()) ?>"
                                    data-remessa-numero="<?= htmlspecialchars($remessa->getNumeroRemessa()) ?>"
                                    title="Entregar Remessa">
                                    <span class="material-icons-outlined">task_alt</span>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modal-entregar" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Confirmar Entrega de Remessa</h4>
            <button class="close-modal-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Você tem certeza que deseja marcar a Remessa Nº <strong id="modal-remessa-numero"></strong> como "ENTREGUE"?</p>
            <p class="alerta-irreversivel">Atenção: Esta ação também marcará todos os protocolos associados como "ENTREGUE".</p>
            
            <form id="form-entregar-remessa" action="/admin/remessas/entregar-remessa" method="post">
                
                <input type="hidden" name="id_remessa" id="modal-remessa-id">

                <div class="form-group">
                    <label for="senha_confirmacao">Digite sua senha para confirmar:</label>
                    <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancelar close-modal-btn">Cancelar</button>
                    <button type="submit" class="btn-salvar">Confirmar Entrega</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>