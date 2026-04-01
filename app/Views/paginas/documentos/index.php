<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>Gestão de Documentos</h3>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalCategoria">
                <i class="bi bi-tags"></i> Categorias
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoDocumento">
                <i class="bi bi-plus-lg"></i> Novo Documento
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded text-primary">
                        <i class="bi bi-folder2-open fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 text-muted small uppercase fw-bold">Total Documentos</h6>
                        <h4 class="mb-0 fw-bold"><?= $totalDocs ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<style>
		/* Força o layout da tabela a respeitar as larguras definidas */
		.table-fixed {
			table-layout: fixed;
			width: 100%;
		}

		/* Define larguras fixas para as colunas (ajuste os % se necessário) */
		.col-doc { width: 45%; }
		.col-data { width: 20%; }
		.col-status { width: 15%; }
		.col-acoes { width: 20%; }

		/* Garante que o texto longo na coluna documento não quebre o layout */
		.text-truncate-custom {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
	</style>

	<div class="container-fluid py-4">
		<?php if(!empty($documentosAgrupados)): ?>
			<?php foreach($documentosAgrupados as $categoria => $lista): ?>
				<div class="mb-4">
					<h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">
						<i class="bi bi-folder me-2"></i><?= htmlspecialchars($categoria) ?>
					</h5>
					<div class="card shadow-sm border-0 mb-4">
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-hover align-middle mb-0 table-fixed">
									<thead class="bg-light text-muted small">
										<tr>
											<th class="ps-4 col-doc">Documento</th>
											<th class="text-end col-data">Data Ref.</th>
											<th class="text-end col-status">Status</th>
											<th class="text-end pe-4 col-acoes">Ações</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($lista as $doc): ?>
										<tr>
											<td class="ps-4">
												<div class="fw-bold text-truncate-custom"><?= htmlspecialchars($doc['documento_nome']) ?></div>
												<small class="text-muted d-block text-truncate-custom">
													<?= mb_strimwidth($doc['documento_descricao'], 0, 80, "...") ?>
												</small>
											</td>
											<td class="text-end"><?= date('d/m/Y', strtotime($doc['documento_data_referencia'])) ?></td>
											<td class="text-end">
												<span class="badge rounded-pill bg-success opacity-75 small">Ativo</span>
											</td>
											<td class="text-end pe-4">
												<div class="btn-group">
													<button class="btn btn-sm btn-outline-primary border-0" onclick="verArquivos(<?= $doc['documento_id'] ?>)" title="Ver Anexos">
														<i class="bi bi-paperclip"></i>
													</button>
													<button class="btn btn-sm btn-outline-danger border-0" onclick="excluirDocumento(<?= $doc['documento_id'] ?>)" title="Excluir Documento">
														<i class="bi bi-trash"></i>
													</button>
												</div>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="alert alert-light text-center py-5 border shadow-sm">
				<i class="bi bi-file-earmark-x d-block fs-1 text-muted mb-2"></i>
				Nenhum documento encontrado para esta instituição.
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('documentos/salvar_categoria') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nome da Categoria</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Atas, Cartas..." required>
                </div>
            </div>
            <div class="modal-footer bg-light p-2">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-4">Salvar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalNovoDocumento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= url('documentos/store') ?>" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Cadastrar Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Título do Documento</label>
                        <input type="text" name="documento_nome" class="form-control" required placeholder="Ex: Ata da Reunião Extraordinária">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Categoria</label>
                        <select name="documento_categoria_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?= $cat['documento_categoria_id'] ?>"><?= $cat['documento_categoria_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Data de Referência</label>
                        <input type="date" name="documento_data_referencia" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Anexar Arquivos (PDF, Imagens, Word)</label>
                        <input type="file" name="arquivos[]" class="form-control" multiple>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Descrição / Observações</label>
                        <textarea name="documento_descricao" class="form-control" rows="3" placeholder="Breve resumo do conteúdo do documento..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-5">Salvar Documento e Arquivos</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalVerArquivos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-paperclip me-2 text-primary"></i>Arquivos Anexados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="lista-arquivos-content">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para tratar o feedback de sucesso via URL
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('sucesso')) {
        alert("Operação realizada com sucesso!");
    }

	function verArquivos(id) {
		const content = document.getElementById('lista-arquivos-content');
		const modal = new bootstrap.Modal(document.getElementById('modalVerArquivos'));

		// Limpa e mostra spinner
		content.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';
		modal.show();

		// Busca os arquivos via AJAX
		fetch(`<?= url('documentos/listar_arquivos/') ?>${id}`)
			.then(response => response.text())
			.then(html => {
				content.innerHTML = html;
			})
			.catch(err => {
				content.innerHTML = '<div class="alert alert-danger m-3">Erro ao carregar arquivos.</div>';
			});
    }

	function deletarArquivo(arquivoId, documentoId) {
		if(!confirm('Deseja realmente excluir permanentemente este anexo?')) return;

		fetch(`<?= url('documentos/excluir_arquivo/') ?>${arquivoId}`)
			.then(res => res.json())
			.then(data => {
				if(data.status === 'success') {
					// Atualiza a lista de arquivos no modal sem fechá-lo
					verArquivos(documentoId);
				} else {
					alert('Erro ao excluir arquivo.');
				}
			});
    }

	function excluirDocumento(id) {
		if (confirm("Atenção: Isso excluirá o documento e TODOS os arquivos anexados a ele permanentemente. Deseja continuar?")) {
			// Redireciona para a rota de exclusão no Controller
			window.location.href = `<?= url('documentos/excluir/') ?>${id}`;
		}
    }

</script>
