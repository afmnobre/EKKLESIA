<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-arrow-left-right me-2"></i>Controle de Empréstimos</h3>
        <a href="<?= url('biblioteca') ?>" class="btn btn-outline-dark btn-sm">Voltar para Estante</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 40px;"><input type="checkbox" id="checkAll"></th>
                            <th>Membro</th>
                            <th>Livro</th>
                            <th>Data Saída</th>
                            <th>Previsão Devolução</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($emprestimos as $e):
                            $atrasado = (strtotime($e['emprestimo_data_prevista']) < time());
                            $dataSaida = date('d/m/Y', strtotime($e['emprestimo_data_saida']));
                            $dataDevolucaoSugestao = date('d/m/Y', strtotime($e['emprestimo_data_saida'] . ' + 15 days'));

                            // CORREÇÃO: Usando 'membro_telefone' que vem do seu Model
                            $telefoneRaw = $e['membro_telefone'] ?? '';
                            $celularLimpo = preg_replace('/[^0-9]/', '', $telefoneRaw);

                            $textoWhats = "Olá " . $e['membro_nome'] . ", tudo bem? Passando para lembrar da devolução do livro \"" . $e['livro_titulo'] . "\" na biblioteca. Deus abençoe!";
                            // Link universal: abre no App se estiver no celular ou no Web se estiver no PC
                            $linkWhats = "https://api.whatsapp.com/send?phone=55" . $celularLimpo . "&text=" . urlencode($textoWhats);
                        ?>
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox" class="check-recibo"
                                       data-membro="<?= htmlspecialchars($e['membro_nome']) ?>"
                                       data-livro="<?= htmlspecialchars($e['livro_titulo']) ?>"
                                       data-data="<?= $dataSaida ?>"
                                       data-devolucao="<?= $dataDevolucaoSugestao ?>">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-2"><?= $e['membro_nome'] ?></span>
                                    <?php if(!empty($celularLimpo)): ?>
                                        <a href="<?= $linkWhats ?>" target="_blank" class="text-success" title="Cobrar via WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?= $e['livro_titulo'] ?></td>
                            <td class="small"><?= $dataSaida ?></td>
                            <td class="small <?= $atrasado ? 'text-danger fw-bold' : '' ?>">
                                <?= date('d/m/Y', strtotime($e['emprestimo_data_prevista'])) ?>
                            </td>
                            <td>
                                <span class="badge <?= $atrasado ? 'bg-danger' : 'bg-primary' ?> shadow-sm">
                                    <?= $atrasado ? 'Atrasado' : 'Ativo' ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button"
                                        class="btn btn-sm btn-success btn-abrir-devolucao"
                                        data-id="<?= $e['emprestimo_id'] ?>"
                                        data-titulo="<?= htmlspecialchars($e['livro_titulo']) ?>">
                                    <i class="bi bi-check2-circle me-1"></i> Receber Livro
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($emprestimos)): ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">Nenhum empréstimo ativo no momento.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button id="btnGerarRecibo" class="btn btn-dark btn-lg shadow position-fixed bottom-0 end-0 m-4 d-none">
        <i class="bi bi-printer me-2"></i> Gerar Recibo (<span id="countSelected">0</span>)
    </button>
</div>

<div class="modal fade" id="modalVisualizarRecibo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title small fw-bold"><i class="bi bi-eye me-2"></i>PRÉVIA DO RECIBO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div id="papelRecibo" class="bg-white shadow-sm p-4 mx-auto" style="width: 100%; font-family: 'Courier New', Courier, monospace; color: #000; border-top: 5px solid #198754;">
                    <div class="text-center mb-3">
                        <?php if (!empty($igreja['igreja_logo'])): ?>
                            <img src="<?= url("assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}") ?>"
                                 style="max-width: 80px; margin-bottom: 10px;" alt="Logo">
                        <?php endif; ?>
                        <h5 class="fw-bold mb-0" style="text-transform: uppercase;"><?= $igreja['igreja_nome'] ?? 'EKKLESIA' ?></h5>
                        <small>📖 Biblioteca Comunitária</small>
                        <div class="my-2">--------------------------------</div>
                        <h6 class="fw-bold">📄 RECIBO DE EMPRÉSTIMO</h6>
                    </div>
                    <div class="mb-3 small">
                        <strong>👤 MEMBRO:</strong> <span id="reciboMembro"></span><br>
                        <strong>📅 DATA SAÍDA:</strong> <span id="reciboData"></span>
                    </div>
                    <div class="mb-3">
                        <small class="fw-bold d-block border-bottom mb-1">📚 LIVROS RETIRADOS:</small>
                        <div id="reciboItens" class="small"></div>
                    </div>
                    <div class="alert alert-warning p-2 text-center" style="font-size: 11px; border: 1px dashed #ffc107; background-color: #fffcf0; color: #856404;">
                        <strong>⚠️ ATENÇÃO À DEVOLUÇÃO:</strong><br>
                        Os livros devem ser devolvidos até:<br>
                        <span id="reciboDevolucao" class="fw-bold fs-6"></span>
                    </div>
                    <div class="text-center mt-4">
                        <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
                        <small style="font-size: 10px;">Assinatura do Membro</small>
                    </div>
                    <div class="text-center mt-3" style="font-size: 9px;">🙏 Deus abençoe sua leitura!</div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-dark w-100" onclick="imprimirPapelRecibo()">
                    <i class="bi bi-printer me-2"></i> Imprimir Recibo
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmarDevolucao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title small fw-bold"><i class="bi bi-arrow-return-left me-2"></i>CONFIRMAR DEVOLUÇÃO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="display-6 text-success mb-3"><i class="bi bi-journal-check"></i></div>
                <p class="mb-1 text-muted">Você está recebendo o livro:</p>
                <h5 class="fw-bold mb-3" id="nomeLivroDevolucao"></h5>
                <p class="small text-muted">Ao confirmar, o exemplar retornará automaticamente para o estoque disponível.</p>
            </div>
            <div class="modal-footer border-0 p-3">
                <button type="button" class="btn btn-light w-100 mb-2" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="linkConfirmarDevolucao" class="btn btn-success w-100">
                    <i class="bi bi-check2-circle me-1"></i> Confirmar Recebimento
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checks = document.querySelectorAll('.check-recibo');
    const btnGerar = document.getElementById('btnGerarRecibo');
    const countSpan = document.getElementById('countSelected');
    const modalRecibo = new bootstrap.Modal(document.getElementById('modalVisualizarRecibo'));
    const modalDevolucao = new bootstrap.Modal(document.getElementById('modalConfirmarDevolucao'));

    function updateUI() {
        const selected = document.querySelectorAll('.check-recibo:checked');
        countSpan.innerText = selected.length;
        btnGerar.classList.toggle('d-none', selected.length === 0);
    }

    checks.forEach(c => c.addEventListener('change', updateUI));

    document.getElementById('checkAll').addEventListener('change', function() {
        checks.forEach(c => c.checked = this.checked);
        updateUI();
    });

    btnGerar.addEventListener('click', function() {
        const selected = document.querySelectorAll('.check-recibo:checked');
        if (selected.length === 0) return;

        document.getElementById('reciboMembro').innerText = selected[0].getAttribute('data-membro');
        document.getElementById('reciboData').innerText = selected[0].getAttribute('data-data');
        document.getElementById('reciboDevolucao').innerText = selected[0].getAttribute('data-devolucao');

        let itensHtml = '';
        selected.forEach(s => {
            itensHtml += `<div class="mb-1">√ ${s.getAttribute('data-livro')}</div>`;
        });
        document.getElementById('reciboItens').innerHTML = itensHtml;

        modalRecibo.show();
    });

    const btnConfirmarLink = document.getElementById('linkConfirmarDevolucao');
    const labelNomeLivro = document.getElementById('nomeLivroDevolucao');

    document.querySelectorAll('.btn-abrir-devolucao').forEach(btn => {
        btn.addEventListener('click', function() {
            labelNomeLivro.innerText = this.getAttribute('data-titulo');
            btnConfirmarLink.href = `<?= url('biblioteca/processarDevolucao/') ?>${this.getAttribute('data-id')}`;
            modalDevolucao.show();
        });
    });
});

function imprimirPapelRecibo() {
    const conteudo = document.getElementById('papelRecibo').innerHTML;
    const largura = 800;
    const altura = 700;
    const esquerda = (screen.width - largura) / 2;
    const topo = (screen.height - altura) / 2;

    const telaImpressao = window.open('', '', `width=${largura},height=${altura},top=${topo},left=${esquerda},resizable=yes,scrollbars=yes`);

    telaImpressao.document.write(`
        <html>
            <head>
                <title>Imprimir Recibo</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { background: #f4f4f4; padding: 20px; font-family: 'Courier New', Courier, monospace; display: flex; justify-content: center; }
                    .folha { background: white; width: 320px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                    @media print {
                        body { background: white; padding: 0; display: block; }
                        .folha { box-shadow: none; width: 100%; padding: 10px; }
                        @page { margin: 0.5cm; }
                    }
                </style>
            </head>
            <body>
                <div class="folha">${conteudo}</div>
                <script>
                    window.onload = function() {
                        setTimeout(() => {
                            window.print();
                            window.onafterprint = function() { window.close(); };
                        }, 500);
                    };
                <\/script>
            </body>
        </html>
    `);
    telaImpressao.document.close();
}
</script>
