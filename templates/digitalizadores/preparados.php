<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Protocolos Preparados</h2>

<div class="container-principal-busca">
<div class="form-container busca-form-container">
        <form action="/digitalizadores/preparados" method="get" class="busca-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="numero_protocolo">Número do Protocolo</label>
                    <input type="text" id="numero_protocolo" name="numero_protocolo" value="<?= htmlspecialchars($_GET['numero_protocolo'] ?? '') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-buscar">
                    <span class="material-icons-outlined">search</span> Buscar
                </button>
                <a href="/digitalizadores/preparados" class="btn-limpar">Limpar Filtros</a>
            </div>
        </form>
</div>

<div class="listagem-container">
    <h3>Protocolos Aguardando Digitalização</h3>
    
    <table class="protocolos-table datatable-js tabela-protocolos" id="tabela-protocolos">
        <thead>
            <tr>
                <th>Nº do Protocolo</th>
                <th class="acoes-header">Ação</th>
                <th>Observações do Preparador</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($listaProtocolosPreparados)): ?>
                <tr>
                    <td colspan="5" class="nenhum-resultado">Nenhum protocolo preparado encontrado.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($listaProtocolosPreparados as $protocolo): ?>
                <tr>
                    <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                    <td class="acoes-cell">
                        <button 
                            type="button" 
                            class="btn-acao-digitalizar open-modal-digitalizar" 
                            data-protocolo-id="<?= htmlspecialchars($protocolo->getId()) ?>"
                            data-protocolo-numero="<?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?>"
                            data-protocolo-observacoes="<?= htmlspecialchars($protocolo->getObservacoes() ?? '') ?>"
                        >
                            <span class="material-icons-outlined">adf_scanner</span> Digitalizar
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
</div>

<div id="modal-digitalizar" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Digitalizar Protocolo</h4>
            <button class="close-modal-btn">&times;</button>
        </div>
        <div class="modal-body">
            <p>Você está digitalizando o Protocolo Nº <strong id="modal-protocolo-numero-dig"></strong>.</p>
            
            <form id="form-digitalizar-protocolo" action="/digitalizadores/movimentar-protocolo" method="post">
                
                <input type="hidden" name="id_protocolo" id="modal-protocolo-id-dig">

                <div class="form-group">
                    <label for="quantidade_paginas_modal">Quantidade de Páginas (Obrigatório)</label>
                    <input type="number" id="quantidade_paginas_modal" name="quantidade_paginas" required min="1">
                </div>

                <div class="form-group">
                    <label for="nome_digitalizador_modal">Responsável</label>
                    <input 
                        type="text" 
                        id="nome_digitalizador_modal" 
                        value="<?= htmlspecialchars($digitalizador->getNome()) ?>" 
                        readonly 
                        style="background-color: #e9ecef;"
                    >
                    <input 
                        type="hidden" 
                        id="id_digitalizador_modal" 
                        name="id_digitalizador" 
                        value="<?= htmlspecialchars($digitalizador->getId()) ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="observacoes_modal_dig">Observações (Adicionar/Editar)</label>
                    <textarea id="observacoes_modal_dig" name="observacoes" rows="3"></textarea>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancelar close-modal-btn">Cancelar</button>
                    <button type="submit" class="btn-salvar">Confirmar Digitalização</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>