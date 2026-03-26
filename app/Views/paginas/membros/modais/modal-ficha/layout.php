<div class="modal-body p-4 bg-light">
	<div class="d-flex justify-content-between align-items-center mb-4 px-2">
		<div>
			<h6 class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Instituição</h6>
			<span class="fw-bold text-dark text-uppercase"><?= htmlspecialchars($membro['igreja_nome']) ?></span>
		</div>
		<img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" style="max-height: 45px; width: auto;">
	</div>

    <div class="card border-0 mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
				<div class="col-md-3 text-center border-end">
					<?php if(!empty($membro['foto_url'])): ?>
						<img src="<?= $membro['foto_url'] ?>"
							 class="img-fluid rounded shadow-sm border p-1 bg-white"
							 style="width: 130px; height: 160px; object-fit: cover;"
							 alt="Foto do Membro">
					<?php else: ?>
						<div class="bg-light d-flex flex-column align-items-center justify-content-center rounded border mx-auto"
							 style="width: 130px; height: 160px;">
							<i class="bi bi-person-bounding-box text-secondary" style="font-size: 3.5rem;"></i>
							<span class="text-muted mt-2" style="font-size: 0.7rem; font-weight: bold;">SEM FOTO</span>
						</div>
					<?php endif; ?>

					<button class="btn btn-sm btn-outline-primary mt-3 w-100 d-print-none btn-acao-dinamica"
							data-id="<?= $membro['membro_id'] ?>"
							data-acao="foto">
						<i class="bi bi-camera me-1"></i> Alterar Foto
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
                                    echo (strlen($reg) > 8) ? substr($reg, 0, -10) . " / " . substr($reg, -10, 4) . " / " . substr($reg, -6, 2) . " / " . substr($reg, -4) : $reg;
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
                            <span class="text-dark"><?= $membro['membro_data_nascimento'] ? date('d/m/Y', strtotime($membro['membro_data_nascimento'])) : '---' ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-bold text-muted d-block small">GÊNERO</label>
                            <span class="text-dark"><?= $membro['membro_genero'] ?: '---' ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-bold text-muted d-block small">CARGO / FUNÇÃO</label>
                            <span class="text-primary fw-bold"><?= htmlspecialchars($membro['membro_cargo'] ?? 'Membro Comum') ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-bold text-muted d-block small">DATA DE BATISMO</label>
                            <span class="text-dark"><?= !empty($membro['membro_data_batismo']) ? date('d/m/Y', strtotime($membro['membro_data_batismo'])) : 'Não Informado' ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold border-0 pt-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Endereço Registrado</div>
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

    <div class="mt-4">
        <h6 class="text-primary fw-bold border-bottom pb-2">📜 Histórico de Registros</h6>
        <?php if (!empty($membro['historicos'])): ?>
            <div class="list-group list-group-flush shadow-sm">
                <?php foreach ($membro['historicos'] as $h): ?>
                    <div class="list-group-item bg-white mb-2 rounded border shadow-sm">
                        <small class="text-primary fw-bold d-block">
                            <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y H:i', strtotime($h['membro_historico_data'])) ?>
                        </small>
                        <div class="mt-2 text-dark small">
                            <?= $h['membro_historico_texto'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted small p-3 bg-white rounded text-center border">
                <i class="bi bi-info-circle me-1"></i> Nenhum histórico para este membro.
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer bg-white border-top p-3 d-print-none">
    <button type="button" class="btn btn-outline-secondary px-4 fw-bold" data-bs-dismiss="modal">Fechar Ficha</button>
    <button type="button" class="btn btn-dark px-4 fw-bold" onclick="window.print();">
        <i class="bi bi-printer me-2"></i>Imprimir Ficha
    </button>
</div>
