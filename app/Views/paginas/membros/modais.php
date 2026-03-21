<style>
/* CSS Focado em Preservar o Layout na Impressão A4 */
@media print {
    /* 1. Esconde tudo na página */
    body * {
        visibility: hidden !important;
        background-color: transparent !important; /* Ajuda a economizar tinta */
    }

    /* 2. Seleciona APENAS o modal aberto e seus filhos para ficarem visíveis */
    .modal.show,
    .modal.show .modal-dialog,
    .modal.show .modal-content,
    .modal.show .modal-body,
    .modal.show * {
        visibility: visible !important;
    }

    /* 3. Transforma o Modal em um bloco normal de fluxo (A4) */
    .modal.show {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        height: auto !important;
        overflow: visible !important;
        display: block !important;
        background-color: white !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .modal-dialog {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: none !important;
    }

    .modal-content {
        border: none !important;
        box-shadow: none !important;
        background-color: white !important;
    }

    /* 4. Esconde interface desnecessária */
    .btn-close, .modal-footer, .d-print-none, .modal-backdrop, .btn-sm {
        display: none !important;
    }

    /* 5. FORÇA O LAYOUT DE COLUNAS (BOOTSTRAP) NA IMPRESSÃO */
    /* Isso garante que a foto fique à esquerda e os dados à direita */
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
        margin-right: -15px !important;
        margin-left: -15px !important;
    }

    .col-md-3 {
        flex: 0 0 25% !important;
        max-width: 25% !important;
        width: 25% !important;
        padding-right: 15px !important;
        padding-left: 15px !important;
    }

    .col-md-9 {
        flex: 0 0 75% !important;
        max-width: 75% !important;
        width: 75% !important;
        padding-right: 15px !important;
        padding-left: 15px !important;
    }

    /* Ajuste para a foto não ficar gigante na impressão */
    .img-fluid {
        max-width: 100% !important;
        height: auto !important;
        object-fit: cover !important;
    }

    /* 6. Evita cortes de conteúdo entre páginas */
    .card, .row, .list-group-item {
        page-break-inside: avoid !important;
    }

    /* 7. Configuração da folha A4 */
    @page {
        size: A4;
        margin: 1.5cm; /* Margem de respiro */
    }

    /* Mantém o fundo branco para o corpo e cards */
    .modal-body, .card {
        background-color: white !important;
    }

    /* Ajuste de tipografia para ficar mais legível no papel */
    .text-muted {
        color: #6c757d !important;
    }
    .text-primary {
        color: #0d6efd !important;
    }
}
</style>


<div class="modal fade" id="modalEndereco<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('membros/updateEndereco') ?>" method="POST">
                <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">📍 Endereço de <?= htmlspecialchars($membro['membro_nome']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">CEP</label>
                            <input type="text" name="cep" id="cep_<?= $membro['membro_id'] ?>"
                                   class="form-control fw-bold" maxlength="9"
                                   placeholder="00000-000"
                                   value="<?= $membro['membro_endereco_cep'] ?? '' ?>"
                                   onblur="window.buscarCep(<?= $membro['membro_id'] ?>)">
                            <div id="msg_<?= $membro['membro_id'] ?>" class="mt-1" style="font-size: 11px;"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Rua / Logradouro</label>
                        <input type="text" name="rua" id="rua_<?= $membro['membro_id'] ?>"
                               class="form-control" value="<?= htmlspecialchars($membro['membro_endereco_rua'] ?? '') ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Cidade</label>
                            <input type="text" name="cidade" id="cidade_<?= $membro['membro_id'] ?>"
                                   class="form-control bg-light" readonly
                                   value="<?= htmlspecialchars($membro['membro_endereco_cidade'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">UF</label>
                            <input type="text" name="estado" id="uf_<?= $membro['membro_id'] ?>"
                                   class="form-control bg-light" readonly maxlength="2"
                                   value="<?= $membro['membro_endereco_estado'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Salvar Endereço</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalStatus<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('membros/updateStatus') ?>" method="POST">
                <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">🔄 Alterar Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label small fw-bold text-muted text-uppercase">Selecione o Status</label>
                    <select name="status" class="form-select">
                        <option value="Ativo" <?= $membro['membro_status'] == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="Inativo" <?= $membro['membro_status'] == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                        <option value="Transferido" <?= $membro['membro_status'] == 'Transferido' ? 'selected' : '' ?>>Transferido</option>
                        <option value="Falecido" <?= $membro['membro_status'] == 'Falecido' ? 'selected' : '' ?>>Falecido</option>
                    </select>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-warning w-100 fw-bold shadow-sm">Atualizar Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalHistorico<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('membros/addHistorico') ?>" method="POST">
                <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">📜 Novo Registro de Histórico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-uppercase text-muted">Descreva o Evento (Batismo, Transferência, etc.)</label>

                        <div class="w-100">
                            <textarea name="historico" class="form-control rich-text" placeholder="Digite aqui o histórico detalhado..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark px-5 fw-bold">
                        <i class="bi bi-save me-2"></i>Registrar no Histórico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFoto<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('membros/uploadFoto') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">
                <input type="hidden" name="membro_registro_interno" value="<?= $membro['membro_registro_interno'] ?>">

                <div class="modal-header bg-light">
                    <h5 class="modal-title w-100 text-center">📸 Foto do Membro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <div class="mb-3">
                        <?php
                            // Removido o prefixo "assets/" daqui, pois a função asset() já o inclui internamente
                            $caminhoRelativo = "uploads/" . $membro['membro_igreja_id'] . "/" . $membro['membro_registro_interno'] . "/";

                            if(!empty($membro['membro_foto_arquivo'])):
                                $urlFoto = asset($caminhoRelativo . $membro['membro_foto_arquivo']);
                        ?>
                            <img src="<?= $urlFoto ?>"
                                 class="img-thumbnail shadow-sm mb-2"
                                 style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%;">
                            <p class="small text-muted mb-3">Foto atual</p>
                        <?php else: ?>
                            <div class="bg-light mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle border" style="width: 100px; height: 100px;">
                                <i class="bi bi-person text-secondary fs-1"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="btn btn-outline-secondary w-100 py-3 shadow-sm border-dashed" style="border-style: dashed !important;">
                            <i class="bi bi-image me-2"></i>
                            <span class="small fw-bold" id="txt_foto_<?= $membro['membro_id'] ?>">Trocar Imagem</span>
                            <input type="file" name="foto" class="d-none" accept="image/*" required
                                   onchange="document.getElementById('txt_foto_<?= $membro['membro_id'] ?>').innerText = 'Selecionada!'; this.parentElement.classList.replace('btn-outline-secondary', 'btn-outline-success')">
                        </label>
                    </div>
                </div>

                <div class="modal-footer border-0 p-3">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Salvar Nova Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFicha<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">📄 Ficha Individual de Membro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                    <div>
                        <h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Instituição</h6>
                        <span class="fw-bold text-dark">Igreja Presbiteriana do Brasil</span>
                    </div>
                    <img src="<?= url('assets/img/logo_ipb_completo.png') ?>"
                         alt="Logo IPB"
                         style="max-height: 45px; width: auto;">
                </div>

                <div class="card border-0 mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center border-end">
                                <?php
                                    $subDiretorio = "uploads/" . $membro['membro_igreja_id'] . "/" . $membro['membro_registro_interno'] . "/";
                                    $fotoFinal = !empty($membro['membro_foto_arquivo'])
                                        ? asset($subDiretorio . $membro['membro_foto_arquivo'])
                                        : null;
                                ?>

                                <?php if($fotoFinal): ?>
                                    <img src="<?= $fotoFinal ?>" class="img-fluid rounded shadow-sm" style="height: 150px; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded border" style="height: 150px;">
                                        <i class="bi bi-person text-secondary" style="font-size: 4rem;"></i>
                                    </div>
                                <?php endif; ?>

                                <button class="btn btn-sm btn-outline-primary mt-2 w-100 d-print-none" data-bs-toggle="modal" data-bs-target="#modalFoto<?= $membro['membro_id'] ?>">
                                    <i class="bi bi-camera"></i> Alterar Foto
                                </button>
                            </div>

                            <div class="col-md-9 ps-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="mb-0 text-primary fw-bold"><?= htmlspecialchars($membro['membro_nome']) ?></h4>
                                        <span class="badge bg-secondary text-white mt-1 mb-2 shadow-sm" style="font-size: 0.85rem; letter-spacing: 1px;">
                                            <i class="bi bi-hash me-1"></i>MATRÍCULA:
                                            <?php
                                                $reg = $membro['membro_registro_interno'];
                                                if(strlen($reg) > 8) {
                                                    echo substr($reg, 0, -10) . " / " . substr($reg, -10, 4) . " / " . substr($reg, -6, 2) . " / " . substr($reg, -4);
                                                } else {
                                                    echo $reg;
                                                }
                                            ?>
                                        </span>
                                    </div>
                                    <span class="badge rounded-pill <?= $membro['membro_status'] == 'Ativo' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= strtoupper($membro['membro_status']) ?>
                                    </span>
                                </div>

                                <div class="row small mt-3">
                                    <div class="col-6 mb-2">
                                        <label class="fw-bold text-muted d-block small">E-MAIL</label>
                                        <span class="text-dark"><?= $membro['membro_email'] ?: '---' ?></span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="fw-bold text-muted d-block small">TELEFONE</label>
                                        <span class="text-dark"><?= $membro['membro_telefone'] ?: '---' ?></span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="fw-bold text-muted d-block small">NASCIMENTO</label>
                                        <span class="text-dark">
                                            <?= $membro['membro_data_nascimento'] ? date('d/m/Y', strtotime($membro['membro_data_nascimento'])) : '---' ?>
                                        </span>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="fw-bold text-muted d-block small">GÊNERO</label>
                                        <span class="text-dark"><?= $membro['membro_genero'] ?: '---' ?></span>
                                    </div>

									<div class="col-6 mb-2">
										<label class="fw-bold text-muted d-block small">CARGO / FUNÇÃO</label>
										<span class="text-primary fw-bold">
											<?= htmlspecialchars($membro['membro_cargo'] ?? 'Membro Comum') ?>
										</span>
									</div>

									<div class="col-6 mb-2">
										<label class="fw-bold text-muted d-block small">DATA DE BATISMO</label>
										<span class="text-dark">
											<?php
												$dataBatismo = $membro['membro_data_batismo'] ?? null;
												echo !empty($dataBatismo) ? date('d/m/Y', strtotime($dataBatismo)) : 'Não Informado';
											?>
										</span>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Endereço Registrado</div>
                            <div class="card-body py-3">
                                <?php if(!empty($membro['membro_endereco_rua'])): ?>
                                    <p class="mb-0 text-dark">
                                        <strong><?= htmlspecialchars($membro['membro_endereco_rua']) ?></strong><br>
                                        <?= htmlspecialchars($membro['membro_endereco_cidade']) ?> - <?= $membro['membro_endereco_estado'] ?>
                                        <br><small class="text-muted">CEP: <?= $membro['membro_endereco_cep'] ?></small>
                                    </p>
                                <?php else: ?>
                                    <p class="text-muted small mb-0 fst-italic text-center py-2">Nenhum endereço cadastrado para este membro.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="text-primary fw-bold border-bottom pb-2">📜 Histórico de Registros</h6>
                    <?php if (!empty($membro['historicos'])): ?>
                        <div class="list-group list-group-flush shadow-sm">
                            <?php foreach ($membro['historicos'] as $h): ?>
                                <div class="list-group-item bg-light mb-2 rounded border-0">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <small class="text-primary fw-bold">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($h['membro_historico_data'])) ?>
                                        </small>
                                    </div>
                                    <div class="mt-2 text-dark small">
                                        <?= $h['membro_historico_texto'] ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small p-3 bg-light rounded text-center">
                            <i class="bi bi-info-circle me-1"></i> Nenhum histórico para este membro.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal-footer bg-white border-top-0 p-3 d-print-none">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Fechar Ficha</button>
                <button type="button" class="btn btn-dark px-4 d-print-none" onclick="window.print();">
                    <i class="bi bi-printer me-2"></i>Imprimir Ficha
                </button>
            </div>
        </div>
    </div>
</div>

	<div class="modal fade" id="modalCargos<?= $membro['membro_id'] ?>" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-centered">
			<div class="modal-content border-0 shadow-lg">
				<form action="<?= url('membros/updateCargos') ?>" method="POST">
					<input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

					<div class="modal-header bg-dark text-white py-3">
						<h5 class="modal-title h6 text-uppercase fw-bold m-0">
							<i class="bi bi-tags me-2 text-warning"></i> Atribuir Cargos: <?= $membro['membro_nome'] ?>
						</h5>
						<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

					<div class="modal-body p-4 bg-light">
						<div class="row g-2">
							<?php foreach ($todosCargos as $cargo): ?>
								<div class="col-md-4">
									<div class="form-check h-100 p-2 border rounded bg-white shadow-sm d-flex align-items-center">
										<input class="form-check-input ms-1 mt-0"
											   type="checkbox"
											   name="cargos[]"
											   value="<?= $cargo['cargo_id'] ?>"
											   id="cargo_<?= $membro['membro_id'] ?>_<?= $cargo['cargo_id'] ?>"
											   <?= in_array($cargo['cargo_id'], $membro['cargos_selecionados']) ? 'checked' : '' ?>>
										<label class="form-check-label ms-3 small fw-bold text-secondary" for="cargo_<?= $membro['membro_id'] ?>_<?= $cargo['cargo_id'] ?>" style="cursor: pointer;">
											<?= $cargo['cargo_nome'] ?>
										</label>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="modal-footer bg-white border-0 shadow-sm">
						<button type="button" class="btn btn-outline-secondary px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary px-5 fw-bold shadow">
							<i class="bi bi-check-lg me-2"></i> Salvar Alterações
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalCarteirinha" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title fw-bold">Visualização da Carteirinha</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body bg-dark p-4 d-flex justify-content-center" id="conteudoModalCarteirinha">
					<div class="text-center text-white py-5">
						<div class="spinner-border text-primary" role="status"></div>
						<p class="mt-2">Carregando dados do membro...</p>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
					<button type="button" class="btn btn-success" id="btnImprimirAjax">
						<i class="bi bi-printer me-2"></i>Imprimir Carteirinha
					</button>
				</div>
			</div>
		</div>
	</div>


<script>
// Mantendo sua função de CEP
window.buscarCep = function(id) {
    var campoCep = document.getElementById('cep_' + id);
    var campoRua = document.getElementById('rua_' + id);
    var campoCid = document.getElementById('cidade_' + id);
    var campoUf  = document.getElementById('uf_' + id);
    var campoMsg = document.getElementById('msg_' + id);
    var cep = campoCep.value.replace(/\D/g, '');
    if (cep.length === 8) {
        campoMsg.innerHTML = '<span class="text-primary fw-bold">🔍 BUSCANDO...</span>';
        fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(function(response) { return response.json(); })
            .then(function(dados) {
                if (!("erro" in dados)) {
                    campoRua.value = dados.logradouro;
                    campoCid.value = dados.localidade;
                    campoUf.value  = dados.uf;
                    campoMsg.innerHTML = '<span class="text-success fw-bold">✅ LOCALIZADO</span>';
                    campoRua.focus();
                } else {
                    campoMsg.innerHTML = '<span class="text-danger fw-bold">❌ CEP NÃO ENCONTRADO</span>';
                }
            })
            .catch(function() {
                campoMsg.innerHTML = '<span class="text-danger fw-bold">⚠️ ERRO DE CONEXÃO</span>';
            });
    }
};

document.addEventListener('DOMContentLoaded', function() {
    if (typeof carteirinhaScriptLoaded === 'undefined') {
        var carteirinhaScriptLoaded = true;

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-gerar-carteirinha');
            if (btn) {
                e.preventDefault();
                e.stopImmediatePropagation();

                const membroId = btn.getAttribute('data-id');
                const container = document.getElementById('conteudoModalCarteirinha');

                container.innerHTML = '<div class="text-center text-white py-5"><div class="spinner-border text-primary"></div><p>Carregando...</p></div>';

                const modalEl = document.getElementById('modalCarteirinha');
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();

                fetch(`<?= url('membros/get_dados_carteirinha/') ?>${membroId}`)
                    .then(response => response.text())
                    .then(text => {
                        const jsonStart = text.indexOf('{');
                        const cleanJson = text.substring(jsonStart);

                        try {
                            const dados = JSON.parse(cleanJson);
                            if(dados.error) throw new Error(dados.error);

							// ... dentro do seu try { const dados = JSON.parse(cleanJson); ... }

							const membro = dados.membro;
							const igreja = dados.igreja;
							const urlFotoMembro = `<?= url('assets/uploads/') ?>${membro.igreja_id}/${membro.registro}/${membro.foto}`;

							container.innerHTML = `
							<style>
								.carteirinha-container {
									width: 21cm;
									height: 7.4cm;
									position: relative;
									background-image: url('<?= url('assets/img/Documento.png') ?>');
									background-size: 100% 100%;
									margin: 0 auto;
									color: #000;
									font-family: 'Arial', sans-serif;
								}
								.lado { width: 10.2cm; height: 6.8cm; position: absolute; top: 0.3cm; }
								.frente { left: 0.2cm; }
								.verso { right: 0.2cm; }

								/* FRENTE */
								.logo-ipb { position: absolute; top: 0.4cm; left: 0.4cm; width: 1.3cm; }
								.cabecalho-frente { position: absolute; top: 0.45cm; left: 1.8cm; width: 6.5cm; line-height: 1; }
								.cabecalho-frente h1 { font-size: 11pt; margin: 0; font-weight: bold; white-space: nowrap; }
								.cabecalho-frente p { font-size: 8.5pt; margin: 0; }

								/* ID/Registro no canto superior direito reduzido */
								.num-reg { position: absolute; top: 0.4cm; right: 0.4cm; font-size: 8.5pt; border: 1px solid #000; padding: 1px 4px; font-weight: bold; }

								.foto-box {
									position: absolute; top: 1.8cm; left: 0.4cm;
									width: 2.8cm; height: 3.5cm;
									border: 1px solid #000; overflow: hidden; background: #fff;
								}
								.foto-box img { width: 100%; height: 100%; object-fit: cover; }

								.dados-membro { position: absolute; top: 1.8cm; left: 3.4cm; width: 6.4cm; }
								.info-label { font-size: 6.5pt; text-transform: uppercase; color: #444; display: block; margin-top: 2px; }
								.info-valor { font-size: 9.5pt; font-weight: bold; display: block; border-bottom: 0.5px solid #eee; margin-bottom: 2px; }

								/* Datas Lado a Lado */
								.flex-row { display: flex; justify-content: space-between; gap: 5px; }
								.flex-col { flex: 1; }

								/* Texto centralizado no rodapé da frente */
								.footer-frente { position: absolute; bottom: 0.2cm; left: 3.4cm; right: 0.4cm; text-align: center; font-size: 8.5pt; font-weight: bold; font-style: italic; color: #004a2f; }

								/* VERSO */
								.cabecalho-verso { position: absolute; top: 0.4cm; left: 1.8cm; line-height: 1; }
								.cabecalho-verso h1 { font-size: 11pt; margin: 0; font-weight: bold; }
								.texto-certificacao { position: absolute; top: 1.8cm; left: 0.5cm; right: 0.5cm; font-size: 8.5pt; text-align: justify; line-height: 1.2; }
								.versiculo { position: absolute; top: 3.2cm; left: 0.5cm; right: 2.5cm; font-size: 8.5pt; font-style: italic; }
								.contatos { position: absolute; bottom: 0.4cm; left: 0.5cm; width: 6.5cm; font-size: 7pt; line-height: 1.2; }
								.qrcode-box { position: absolute; top: 3.8cm; right: 0.5cm; width: 1.6cm; height: 1.6cm; }
								.assinatura-area { position: absolute; bottom: 0.4cm; right: 0.5cm; width: 4.5cm; border-top: 1px solid #000; text-align: center; font-size: 7.5pt; padding-top: 2px; }
							</style>

							<div id="printArea" class="carteirinha-container">
								<div class="lado frente">
									<img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
									<div class="cabecalho-frente">
										<h1>IGREJA PRESBITERIANA</h1>
										<p>${igreja.nome}</p>
									</div>
									<div class="num-reg">Nº ${membro.registro}</div>

									<div class="foto-box">
										${membro.foto ? `<img src="${urlFotoMembro}">` : '<div style="padding-top:1.3cm; text-align:center; font-size:7pt;">SEM FOTO</div>'}
									</div>

									<div class="dados-membro">
										<span class="info-label">Nome do Membro:</span>
										<span class="info-valor" style="font-size: 10pt;">${membro.nome}</span>

										<div class="flex-row">
											<div class="flex-col">
												<span class="info-label">Data de Batismo:</span>
												<span class="info-valor">${membro.data_batismo || '--/--/----'}</span>
											</div>
											<div class="flex-col">
												<span class="info-label">Nascimento:</span>
												<span class="info-valor">${membro.data_nascimento || '--/--/----'}</span>
											</div>
										</div>

										<span class="info-label">Pastor:</span>
										<span class="info-valor">${igreja.pastor}</span>

										<span class="info-label">Sociedade / Cargo:</span>
										<span class="info-valor" style="font-size: 8.5pt; height: 0.8cm; overflow: hidden;">${membro.cargo}</span>
									</div>

									<div class="footer-frente">
										Igreja Presbiteriana do Jardim Girassol
									</div>
								</div>

								<div class="lado verso">
									<img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
									<div class="cabecalho-verso">
										<h1 style="font-size: 10pt;">IGREJA PRESBITERIANA</h1>
										<p style="margin:0; font-size: 8pt;">${igreja.nome}</p>
									</div>

									<div class="texto-certificacao">
										Este documento certifica que o portador é membro comungante da Igreja Presbiteriana do Jardim Girassol, jurisdicionada à Igreja Presbiteriana do Brasil.
									</div>

									<div class="versiculo">
										"Tudo faço por causa do evangelho, para ser também participante dele."<br>
										<strong>1 Coríntios 9:23</strong>
									</div>

									<div class="qrcode-box">
										 <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${membro.registro}" style="width:100%;">
									</div>

									<div class="contatos">
                                        📍 ${igreja.endereco}<br>
                                        📱 ${igreja.contatos}
                                    </div>

									<div class="assinatura-area">
										Assinatura do portador
									</div>
								</div>
							</div>
							`;
                        } catch (err) {
                            console.error("Erro no processamento:", err);
                            container.innerHTML = `<div class="alert alert-danger">Erro ao carregar dados.</div>`;
                        }
                    });
            }
        });
    }

    // Lógica de Impressão (Mantenha o resto do seu código de Iframe igual)
    document.getElementById('btnImprimirAjax').addEventListener('click', function() {
        const printArea = document.getElementById('printArea');
        if(!printArea) return;
        const content = printArea.innerHTML;
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        const pri = iframe.contentWindow;
        pri.document.open();
        pri.document.write(`<html><head><style>@page { size: landscape; margin: 0; } body { margin: 0; }</style></head><body>${content}</body></html>`);
        pri.document.close();
        setTimeout(() => { pri.focus(); pri.print(); iframe.remove(); }, 500);
    });
});
</script>
