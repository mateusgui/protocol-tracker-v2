<?php require __DIR__ . '/../components/_header.php'; ?>

<h2>Cadastrar novos protocolos</h2>

<?php if (isset($erro) && $erro): ?>
    <div class="error-message">
        <strong>Erro:</strong> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>

<div class="container-principal-busca">

    <section class="busca-form-container">
        <form action="/admin/remessas/protocolos" method="post" class="busca-form">
            
            <input type="hidden" name="id_remessa" value="<?= htmlspecialchars($id_remessa) ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="numero_protocolo">Número do Protocolo (6 dígitos)</label>
                    <input type="text" id="numero_protocolo" name="numero_protocolo" required maxlength="6" pattern="\d{6}"placeholder="Ex: 123456">
                </div>
                <div class="form-group">
                    <label for="numero_remessa">Número da Remessa</label>
                    <input type="text" id="numero_remessa" readonly  name="numero_remessa" value="<?= htmlspecialchars($remessa->getNumeroRemessa()) ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit">
                    <span class="material-icons-outlined">add</span> Adicionar Protocolo
                </button>
            </div>
        </form>
    </section>

    <section class="listagem-container">
        <h3>Protocolos da Remessa</h3>

        <table id="tabela-protocolos-remessa" class="protocolos-table datatable-js">
            <thead>
                <tr>
                    <th>Nº do Protocolo</th>
                    <th>Status</th>
                    <th>Preparador</th>
                    <th>Data da Preparação</th>
                    <th>Digitalizador</th>
                    <th>Data da Digitalização</th>
                    <th>Qtd. Páginas</th>
                    <th class="acoes-header">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $fusoHorarioLocal = new DateTimeZone('America/Campo_Grande');
                ?>
                <?php foreach ($listaProtocolos as $protocolo): ?>
                    <tr>
                        <td><?= htmlspecialchars($protocolo->getNumeroProtocolo()) ?></td>
                        <td><?= htmlspecialchars($protocolo->getStatus()) ?></td>
                        <td>
                            <?php
                                foreach ($listaDePreparadores as $preparador) {
                                    if($preparador->getId() === $protocolo->getIdPreparador()){
                                        $nomePreparador = $preparador->getNome();
                                        break;
                                    }
                                }
                                echo htmlspecialchars($nomePreparador ?? 'N/A');
                            ?>
                        </td>
                        <td><?= $protocolo->getDataPreparacao() ? $protocolo->getDataPreparacao()->setTimezone($fusoHorarioLocal)->format('d/m/Y H:i') : 'Pendente' ?></td>
                        <td>
                            <?php
                                foreach ($listaDeDigitalizadores as $digitalizador) {
                                    if($digitalizador->getId() === $protocolo->getIdDigitalizador()){
                                        $nomeDigitalizador = $digitalizador->getNome();
                                        break;
                                    }
                                }
                                echo htmlspecialchars($nomeDigitalizador ?? 'N/A');
                            ?>
                        </td>
                        <td><?= $protocolo->getDataDigitalizacao() ? $protocolo->getDataDigitalizacao()->setTimezone($fusoHorarioLocal)->format('d/m/Y H:i') : 'Pendente' ?></td>
                        <td><?= htmlspecialchars($protocolo->getQuantidadePaginas() ?? 'N/A') ?></td>
                        <td class="acoes-cell">
                            <a href="/admin/remessas/editar-protocolo?id=<?= htmlspecialchars($protocolo->getId()) ?>" class="btn-acao btn-editar" title="Editar Protocolo">
                                <span class="material-icons-outlined">edit</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</div>

<?php require __DIR__ . '/../components/_footer.php'; ?>