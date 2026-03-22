<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('patrimonios/uploadFoto') ?>" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Anexar Fotos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="patrimonio_id" id="foto_patrimonio_id">
                <div class="mb-3">
                    <label class="form-label text-dark fw-bold">Selecione as Imagens</label>
                    <input type="file" name="arquivo[]" class="form-control" accept="image/*" multiple required>
                    <div class="form-text">Você pode selecionar várias fotos de uma vez.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Fazer Upload das Fotos</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalDoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('patrimonios/uploadDocumento') ?>" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Anexar Documentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="patrimonio_id" id="doc_patrimonio_id">
                <div class="mb-3">
                    <label class="form-label text-dark fw-bold">Arquivos (PDF ou Imagem)</label>
                    <input type="file" name="arquivo[]" class="form-control" accept=".pdf,image/*" multiple required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-secondary w-100">Fazer Upload dos Documentos</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEtiqueta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title fw-bold">Placa de Patrimônio</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light p-4">

                <div id="areaEtiqueta" class="bg-white" style="width: 100%; max-width: 300px; margin: 0 auto; color: #000; border: 4px solid #000 !important; padding: 25px 15px;">

                    <div class="mb-3">
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" style="max-height: 90px; width: auto; display: block; margin: 0 auto;">
                    </div>

                    <div class="fw-bold mb-3" style="font-size: 1rem; border-bottom: 3px solid #000; padding-bottom: 8px; text-transform: uppercase; line-height: 1.2;">
                        <?= $nomeIgreja ?>
                    </div>

                    <div class="d-flex justify-content-center mb-2">
                        <img id="imgQrCode" src="" alt="QR Code" style="width: 170px; height: 170px; display: block;">
                    </div>

                    <div class="text-uppercase fw-bold" style="font-size: 0.8rem; letter-spacing: 3px; color: #000; margin-top: 5px;">
                        PATRIMÔNIO
                    </div>

                    <div class="fw-bold" id="txtCodigo" style="font-size: 1.5rem; line-height: 1.1; margin-bottom: 5px;"></div>

                    <div class="text-uppercase fw-bold text-muted" id="txtNome" style="font-size: 0.75rem; line-height: 1.3; border-top: 1px solid #ccc; padding-top: 5px; margin-top: 5px;"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" onclick="window.baixarEtiqueta()">
                    <i class="bi bi-download me-2"></i> BAIXAR PLACA (.PNG)
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMovimentacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= url('patrimonios/movimentar') ?>" method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Movimentar Patrimônio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="patrimonio_id" id="mov_patrimonio_id">

                <div class="mb-3">
                    <label class="form-label fw-bold">Tipo de Movimentação</label>
                    <select name="tipo" id="mov_tipo" class="form-select" onchange="window.ajustarCamposMovimentacao()" required>
                        <option value="transferencia">Transferência entre Locais</option>
                        <option value="manutencao">Enviar para Manutenção</option>
                        <option value="baixa">Dar Baixa (Retirar do Inventário)</option>
                        <option value="entrada">Entrada / Ajuste</option>
                        <option value="saida">Saída Temporária</option>
                    </select>
                </div>

				<div class="mb-3" id="div_origem">
					<label class="form-label fw-bold small text-muted">Local de Origem (Atual)</label>
					<select name="local_origem" id="mov_local_origem" class="form-select bg-light" readonly style="pointer-events: none;">
						<option value="">Não definido</option>
						<?php foreach($locais as $l): ?>
							<option value="<?= $l['patrimonio_local_id'] ?>"><?= $l['patrimonio_local_nome'] ?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="local_origem_hidden" id="mov_local_origem_hidden">
				</div>

                <div class="mb-3" id="div_destino">
                    <label class="form-label fw-bold">Local de Destino</label>
                    <select name="local_destino" class="form-select">
                        <option value="">Selecione o destino...</option>
                        <?php foreach($locais as $l): ?>
                            <option value="<?= $l['patrimonio_local_id'] ?>"><?= $l['patrimonio_local_nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Data da Ação</label>
                    <input type="date" name="data" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Observações</label>
                    <textarea name="observacao" class="form-control" rows="3" placeholder="Motivo da movimentação..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Registrar Movimentação</button>
            </div>
        </form>
    </div>
</div>


<script>
window.abrirModalUpload = function(id, tipo) {
    if(tipo === 'foto') {
        document.getElementById('foto_patrimonio_id').value = id;
        new bootstrap.Modal(document.getElementById('modalFoto')).show();
    } else {
        document.getElementById('doc_patrimonio_id').value = id;
        new bootstrap.Modal(document.getElementById('modalDoc')).show();
    }
};

window.gerarEtiqueta = function(codigo, nome) {
    const urlDetalhes = "<?= url('patrimonios/detalhes/') ?>" + codigo;
    const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(urlDetalhes);

    const imgQr = document.getElementById('imgQrCode');
    imgQr.src = "";
    imgQr.src = qrUrl;

    document.getElementById('txtCodigo').innerText = codigo;
    document.getElementById('txtNome').innerText = nome;

    const modalEtiqueta = new bootstrap.Modal(document.getElementById('modalEtiqueta'));
    modalEtiqueta.show();
};

window.baixarEtiqueta = function() {
    const areaEtiqueta = document.getElementById('areaEtiqueta');
    const codigo = document.getElementById('txtCodigo').innerText;
    const btn = event.currentTarget;

    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processando...';
    btn.disabled = true;

    html2canvas(areaEtiqueta, {
        scale: 3,
        useCORS: true,
        backgroundColor: '#FFFFFF',
        logging: false
    }).then(canvas => {
        const image = canvas.toDataURL("image/png");
        const link = document.createElement('a');
        link.href = image;
        link.download = `placa_${codigo}.png`;

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        btn.innerHTML = originalText;
        btn.disabled = false;
    }).catch(err => {
        console.error("Erro:", err);
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
};

window.abrirModalMovimentacao = function(id, localAtualId) {
    console.log("ID do Bem:", id);
    console.log("Local Atual recebido:", localAtualId);

    // 1. Preenche os campos PRIMEIRO
    document.getElementById('mov_patrimonio_id').value = id;

    const selectOrigem = document.getElementById('mov_local_origem');
    const inputHiddenOrigem = document.getElementById('mov_local_origem_hidden');

    // Forçamos a conversão para string para evitar falhas de comparação
    const idStr = String(localAtualId).trim();

    if (idStr && idStr !== 'null' && idStr !== 'undefined' && idStr !== '') {
        selectOrigem.value = idStr;
        inputHiddenOrigem.value = idStr;
    } else {
        selectOrigem.value = "";
        inputHiddenOrigem.value = "";
    }

    document.getElementById('mov_tipo').value = 'transferencia';
    window.ajustarCamposMovimentacao();

    // 2. Abre o modal garantindo que não criamos múltiplas instâncias
    const modalDiv = document.getElementById('modalMovimentacao');
    let modalMov = bootstrap.Modal.getInstance(modalDiv); // Tenta pegar instância existente

    if (!modalMov) {
        modalMov = new bootstrap.Modal(modalDiv); // Se não existir, cria
    }

    modalMov.show();
};

window.ajustarCamposMovimentacao = function() {
    const tipo = document.getElementById('mov_tipo').value;
    const divOrigem = document.getElementById('div_origem');
    const divDestino = document.getElementById('div_destino');

    // Se for Manutenção ou Baixa, não precisa selecionar Local de Destino
    if (tipo === 'manutencao' || tipo === 'baixa') {
        divDestino.style.display = 'none';
    } else {
        divDestino.style.display = 'block';
    }

    // Se for Entrada, talvez não tenha origem
    divOrigem.style.display = (tipo === 'entrada') ? 'none' : 'block';
    };


window.confirmarExclusao = function(id, nome) {
    if (confirm(`Deseja realmente excluir o patrimônio "${nome}"? \nEsta ação apagará permanentemente todas as fotos, documentos e histórico de movimentações.`)) {
        // Redireciona para a rota de exclusão
        window.location.href = "<?= url('patrimonios/excluir/') ?>" + id;
    }
};
</script>
