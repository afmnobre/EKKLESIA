<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-geo-alt me-2 text-primary"></i>Locais de Patrimônio</h3>
        <a href="<?= url('patrimonios') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar ao Inventário
        </a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
			<div class="card shadow-sm border-0">
				<div class="card-header bg-white py-3">
					<h6 class="m-0 font-weight-bold text-primary" id="titulo-form">Novo Local</h6>
				</div>
				<div class="card-body">
					<form action="<?= url('patrimonios/salvarLocal') ?>" method="POST" id="form-local">
						<input type="hidden" name="patrimonio_local_id" id="input-id">

						<div class="mb-3">
							<label class="form-label text-dark fw-bold">Nome do Local</label>
							<input type="text" name="patrimonio_local_nome" id="input-nome" class="form-control" placeholder="Ex: Nave, Cozinha..." required>
						</div>
						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary" id="btn-submit">
								<i class="bi bi-floppy me-1"></i> Cadastrar Local
							</button>
							<button type="button" class="btn btn-light btn-sm" id="btn-cancelar" style="display:none;" onclick="window.resetarFormLocal()">
								Cancelar Edição
							</button>
						</div>
					</form>
				</div>
			</div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Locais Cadastrados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">ID</th>
                                    <th>Nome do Local</th>
                                    <th width="20%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php foreach($locais as $l): ?>
								<tr>
									<td>#<?= $l['patrimonio_local_id'] ?></td>
									<td class="fw-bold text-dark"><?= $l['patrimonio_local_nome'] ?></td>
									<td class="text-end">
										<a href="<?= url('patrimonios/imprimirEtiquetas/'.$l['patrimonio_local_id']) ?>"
										   class="btn btn-sm btn-outline-info me-1"
										   target="_blank"
										   title="Imprimir Etiquetas (QR Code)">
											<i class="bi bi-qr-code"></i>
										</a>

										<a href="<?= url('patrimonios/exportarExcelLocal/'.$l['patrimonio_local_id']) ?>"
										   class="btn btn-sm btn-outline-success me-1"
										   title="Gerar Planilha de Conferência">
											<i class="bi bi-file-earmark-excel"></i>
										</a>

										<button class="btn btn-sm btn-outline-primary me-1"
												onclick="window.editarLocal(<?= $l['patrimonio_local_id'] ?>, '<?= $l['patrimonio_local_nome'] ?>')"
												title="Editar Nome do Local">
											<i class="bi bi-pencil"></i>
										</button>

										<a href="<?= url('patrimonios/excluirLocal/'.$l['patrimonio_local_id']) ?>"
										   class="btn btn-sm btn-outline-danger"
										   onclick="return confirm('Tem certeza? Isso pode afetar itens vinculados a este local.')"
										   title="Excluir Local">
											<i class="bi bi-trash"></i>
										</a>
									</td>
								</tr>
								<?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.editarLocal = function(id, nome) {
    document.getElementById('titulo-form').innerText = "Editar Local #" + id;
    document.getElementById('input-id').value = id;
    document.getElementById('input-nome').value = nome;
    document.getElementById('btn-submit').innerHTML = '<i class="bi bi-pencil-square me-1"></i> Atualizar Local';
    document.getElementById('btn-submit').classList.replace('btn-primary', 'btn-success');
    document.getElementById('btn-cancelar').style.display = 'block';

    // Foca no campo de nome para facilitar a digitação
    document.getElementById('input-nome').focus();
};

window.resetarFormLocal = function() {
    document.getElementById('titulo-form').innerText = "Novo Local";
    document.getElementById('input-id').value = "";
    document.getElementById('form-local').reset();
    document.getElementById('btn-submit').innerHTML = '<i class="bi bi-floppy me-1"></i> Cadastrar Local';
    document.getElementById('btn-submit').classList.replace('btn-success', 'btn-primary');
    document.getElementById('btn-cancelar').style.display = 'none';
};
</script>
