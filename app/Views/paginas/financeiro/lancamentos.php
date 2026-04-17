<style>
	.choices__inner {
		background-color: #f8f9fa; /* Cor bg-light do seu projeto */
		border-radius: 0.375rem;
		border: 1px solid #dee2e6;
		min-height: 45px;
		padding: 5px 10px;
	}

	.choices__list--single .choices__item {
		font-weight: bold;
	}

</style>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row align-items-center">

            <div class="col-md-3 border-end">
                <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Filtrar por Ano</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-calendar-event text-primary"></i></span>
                    <select class="form-select border-0 bg-light fw-bold"
                            onchange="location.href='?mes=<?= $mesSelecionado ?>&ano='+this.value">
                        <?php foreach($anosDisponiveis as $a): ?>
                            <option value="<?= $a['ano'] ?>" <?= $a['ano'] == $anoSelecionado ? 'selected' : '' ?>>
                                <?= $a['ano'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-9">
                <label class="small fw-bold text-muted text-uppercase mb-1 d-block text-center text-md-start ps-3">Mês de Referência</label>
                <div class="nav nav-pills nav-fill bg-light p-1 rounded-pill mx-md-2">
                    <?php
                    $meses = [
                        1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun',
                        7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'
                    ];
                    foreach($meses as $num => $nome): ?>
                        <div class="nav-item">
                            <a class="nav-link py-1 rounded-pill <?= $num == $mesSelecionado ? 'active shadow-sm' : 'text-dark fw-bold' ?>"
                               style="font-size: 0.85rem;"
                               href="?ano=<?= $anoSelecionado ?>&mes=<?= $num ?>">
                                <?= $nome ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container-fluid py-4">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h3 class="fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>Contas a Pagar/Receber</h3>
		<div>
            <a href="<?= url('financeiro/baixar_anexos_zip?mes='.$mesSelecionado.'&ano='.$anoSelecionado) ?>"
                class="btn btn-outline-primary shadow-sm me-2">
                <i class="bi bi-file-earmark-zip"></i> Baixar Anexos (ZIP)
            </a>

            <a href="<?= url('financeiro/exportar_excel?mes='.$mesSelecionado.'&ano='.$anoSelecionado) ?>"
			   class="btn btn-outline-success shadow-sm me-2">
				<i class="bi bi-file-earmark-excel"></i> Exportar Excel
			</a>

            <button class="btn btn-outline-dark shadow-sm me-2" onclick="abrirModalRelatorioConferencia()">
                <i class="bi bi-printer"></i> Relatório de Conferência
            </button>

			<button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovaConta">
				<i class="bi bi-plus-lg"></i> Novo Lançamento
			</button>
		</div>
	</div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead class="bg-light">
						<tr>
							<th class="ps-4">Vencimento</th>
							<th>Descrição</th>
							<th>Classificação</th>
							<th>Valor</th>
							<th>Status</th>
							<th class="text-end pe-4">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($contas_agendadas as $c):
							$hoje = date('Y-m-d');
							$atrasado = ($c['financeiro_conta_data_vencimento'] < $hoje && !$c['financeiro_conta_pago']);

							// Verifica se existe data de pagamento válida
							$dataPagamento = (!empty($c['financeiro_conta_data_pagamento']) && $c['financeiro_conta_data_pagamento'] != '0000-00-00')
											 ? date('d/m/Y', strtotime($c['financeiro_conta_data_pagamento']))
											 : null;
						?>
						<tr>
							<td class="ps-4 <?= $atrasado ? 'text-danger fw-bold' : '' ?>">
								<?= date('d/m/Y', strtotime($c['financeiro_conta_data_vencimento'])) ?>
							</td>
							<td>
								<span class="d-block fw-bold text-dark"><?= $c['financeiro_conta_descricao'] ?></span>
							</td>
							<td>
								<small class="text-muted d-block" style="font-size: 0.7rem;"><?= $c['financeiro_categoria_nome'] ?></small>
								<span class="badge bg-light text-dark border"><?= $c['subcategoria_nome'] ?></span>
							</td>
							<td class="fw-bold text-<?= $c['financeiro_conta_tipo'] == 'saida' ? 'danger' : 'success' ?>">
								R$ <?= number_format($c['financeiro_conta_valor'], 2, ',', '.') ?>
							</td>
							<td>
								<?php if($c['financeiro_conta_pago']): ?>
									<span class="badge bg-success-subtle text-success px-3 d-block mb-1">Pago</span>
									<?php if($dataPagamento): ?>
										<small class="text-muted d-block" style="font-size: 0.75rem;" title="Data do Pagamento">
											<i class="bi bi-calendar-check me-1"></i><?= $dataPagamento ?>
										</small>
									<?php endif; ?>
								<?php else: ?>
									<span class="badge bg-warning-subtle text-warning px-3">Pendente</span>
								<?php endif; ?>
							</td>

							<td class="text-end pe-4">

							<?php if($c['financeiro_conta_pago']): ?>
								<div class="btn-group">
									<?php
										// Usamos json_encode para que o JS receba a string limpa e segura
										$arqComp = json_encode($c['financeiro_conta_comprovante'] ?? '');
										$arqNF = json_encode($c['financeiro_conta_nota_fiscal'] ?? '');
									?>
									<button type="button"
											class="btn btn-sm <?= (!empty($c['financeiro_conta_comprovante']) || !empty($c['membros'])) ? 'btn-success' : 'btn-outline-secondary' ?>"
											onclick='abrirModalAnexo(<?= json_encode($c) ?>, "comprovante")'
											title="Comprovante">
										<i class="bi bi-receipt"></i>
									</button>

									<button type="button"
											class="btn btn-sm <?= !empty($c['financeiro_conta_nota_fiscal']) ? 'btn-info' : 'btn-outline-secondary' ?>"
											onclick='abrirModalAnexo(<?= json_encode($c) ?>, "notafiscal")'
											title="Nota Fiscal">
										<i class="bi bi-file-earmark-text"></i>
									</button>

									<button type="button" class="btn btn-sm btn-outline-primary"
										onclick="abrirModalQR('<?= $c['financeiro_conta_id'] ?>', 'comprovante')"
										title="Upload via Celular">
										<i class="bi bi-qr-code-scan"></i>
                                    </button>

									<button type="button"
											class="btn btn-sm btn-outline-primary"
											onclick="editarLancamento(<?= $c['financeiro_conta_id'] ?>)"
											title="Editar Lançamento">
										<i class="bi bi-pencil"></i>
									</button>
                                </div>
                            <?php endif; ?>


                                <div class="btn-group">
									<?php if($c['financeiro_conta_tipo'] == 'saida' && isset($c['financeiro_conta_reembolso']) && $c['financeiro_conta_reembolso'] == 1): ?>
										<a href="<?= url('financeiro/gerar_recibo_reembolso/'.$c['financeiro_conta_id']) ?>"
										   target="_blank"
										   class="btn btn-sm btn-outline-success"
										   title="Gerar Recibo de Reembolso">
											<i class="bi bi-file-earmark-pdf"></i>
										</a>
									<?php endif; ?>
									<?php if(!$c['financeiro_conta_pago']): ?>
										<button class="btn btn-sm btn-success px-3" title="Dar Baixa"
												onclick="pagarConta(<?= $c['financeiro_conta_id'] ?>, <?= $c['financeiro_conta_valor'] ?>)">
											<i class="bi bi-check2-circle"></i>
										</button>

										<?php if($c['financeiro_conta_tipo'] == 'entrada'):
											$subId = $c['financeiro_conta_financeiro_subcategoria_id']
													 ?? $c['financeiro_subcategoria_id']
													 ?? $c['financeiro_conta_financeiro_categoria_id']
													 ?? 0;
										?>
											<button type="button" class="btn btn-sm btn-outline-info"
												onclick="abrirModalMembros(<?= $c['financeiro_conta_id'] ?>, <?= $c['financeiro_conta_valor'] ?>, <?= $subId ?>)">
												<i class="bi bi-people"></i> Ratear
											</button>
										<?php endif; ?>

                                        <button class="btn btn-sm btn-outline-primary" title="Editar Lançamento"
												onclick="editarLancamento(<?= htmlspecialchars(json_encode($c)) ?>)">
											<i class="bi bi-pencil"></i>
										</button>

										<a href="<?= url('financeiro/excluir_lancamento/'.$c['financeiro_conta_id']) ?>"
										   class="btn btn-sm btn-outline-danger" title="Excluir"
										   onclick="return confirm('Tem certeza que deseja excluir este agendamento?')">
											<i class="bi bi-trash"></i>
										</a>
									<?php else: ?>
										<span class="badge bg-light text-muted border py-2 px-3">
											<i class="bi bi-lock-fill me-1"></i> Conciliado
										</span>
									<?php endif; ?>
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

<div class="modal fade" id="modalEditarLancamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Lançamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarLancamento" action="<?= url('financeiro/atualizar') ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Descrição</label>
                            <input type="text" name="descricao" id="edit_descricao" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Valor (R$)</label>
                            <input type="number" step="0.01" name="valor" id="edit_valor"
                                   class="form-control" required
                                   oninput="recalcularRateioEdicao()">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Data Pagamento/Recebimento</label>
                            <input type="date" name="data_pagamento" id="edit_data_pagamento" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Conta Bancária / Caixa</label>
                            <select name="financeiro_conta_financeira_id" id="edit_conta_financeira_id" class="form-select" required>
                                <?php foreach($contas_bancarias as $cb): ?>
                                    <?php
                                        // Formata o saldo para exibição (ex: 1.250,50)
                                        $saldoFormatado = number_format($cb['financeiro_conta_financeira_saldo'], 2, ',', '.');
                                    ?>
                                    <option value="<?= $cb['financeiro_conta_financeira_id'] ?>">
                                        <?= $cb['financeiro_conta_financeira_nome'] ?> (Saldo: R$ <?= $saldoFormatado ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold">Classificação</label>
                            <select name="subcategoria_id" id="edit_categoria_id" class="form-select choice-select-color">
                                <option value="">Digite para pesquisar...</option>
                                <?php if(!empty($categorias_formatadas)): ?>
                                    <?php foreach($categorias_formatadas as $cat): ?>
                                        <option value="<?= $cat['subcategoria_id'] ?>"
                                                data-tipo="<?= $cat['financeiro_categoria_tipo'] ?>">
                                            <?= $cat['nome_formatado'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div id="areaRateioEdicao" class="col-12 d-none">
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-people"></i> Rateio por Membros</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="adicionarMembroEdicao()">
                                    <i class="bi bi-plus"></i> Adicionar Membro
                                </button>
                            </div>
                            <div id="listaMembrosEdicao" class="border rounded p-3 bg-light">
                                </div>
                            <div class="text-end mt-2">
                                <span class="small fw-bold">Restante: </span>
                                <span id="labelRestanteEdicao" class="badge bg-success">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnSalvarEdicao" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQRCode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-primary text-white border-0 py-2">
                <h6 class="modal-title fw-bold small text-uppercase">Anexar pelo Celular</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">

                <div id="qrcode_financeiro" class="d-flex justify-content-center p-3 border rounded-4 bg-white shadow-sm mb-3" style="min-height: 200px;">
                </div>

                <div class="card bg-light border-0 rounded-3 mb-3">
                    <div class="card-body p-2">
                        <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.6rem;">Link de Acesso:</small>
                        <div class="input-group input-group-sm">
                            <input type="text" id="inputLinkUpload" class="form-control form-control-sm border-0 bg-transparent text-center fw-bold" readonly style="font-size: 0.75rem;">
                            <button class="btn btn-sm btn-outline-primary border-0" type="button" onclick="copiarLinkUpload()">
                                <i class="bi bi-box-arrow-up-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <p class="small text-muted mb-0" style="font-size: 0.75rem;">Aponte a câmera ou use o link acima para testar.</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnexo" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered"> <form action="<?= url('financeiro/uploadAnexo') ?>" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="conta_id" id="anexo_conta_id">
            <input type="hidden" name="tipo_arquivo" id="anexo_tipo">
            <input type="hidden" name="ano_referencia" value="<?= $anoSelecionado ?>">
            <input type="hidden" name="mes_referencia" value="<?= $mesSelecionado ?>">

            <div class="modal-header bg-light border-bottom-0">
                <h6 class="modal-title fw-bold" id="titulo_anexo">Anexar Documento</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

				<div id="area_rateio_anexos" class="d-none mb-3">
					<label class="small fw-bold text-primary mb-2"><i class="bi bi-people-fill"></i> Comprovantes por Membro:</label>
					<div id="lista_membros_upload" class="list-group shadow-sm">
						</div>
					<hr>
				</div>

                <div id="area_visualizacao" class="d-none mb-3">
                    <label class="small fw-bold text-primary mb-2"><i class="bi bi-eye"></i> Arquivo Atual:</label>
                    <div id="wrapper_preview" style="width: 100%; height: 350px; overflow: auto; border: 2px solid #e9ecef; border-radius: 8px; background: #f8f9fa; display: flex; justify-content: center; align-items: flex-start;">
                        <div id="content_preview" style="min-width: 100%;"></div>
                    </div>
                    <div class="text-center mt-2">
                        <a id="link_abrir_externo" href="#" target="_blank" class="btn btn-sm btn-link text-decoration-none">
                            <i class="bi bi-box-arrow-up-right"></i> Abrir em tela cheia
                        </a>
                    </div>
                    <hr>
                </div>

                <div class="upload-section">
                    <label class="small fw-bold text-muted mb-2" id="label_upload">Substituir arquivo:</label>
                    <div class="input-group">
                        <input type="file" name="arquivo" class="form-control" required>
                    </div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Formatos aceitos: JPG, PNG, PDF.</small>
                </div>
            </div>

            <div class="modal-footer bg-light border-top-0 p-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-cloud-arrow-up"></i> SALVAR ARQUIVO
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalRelatorioConferencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-shield-check me-2"></i>Conferência de Receitas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Data do Movimento</label>
                    <input type="date" id="rel_data_movimento" class="form-control form-control-lg fw-bold" value="<?= date('Y-m-d') ?>">
                </div>

                <hr class="text-muted opacity-25">

				<div class="mb-4">
					<label class="small fw-bold text-muted text-uppercase mb-1 d-block">1º Conferente</label>
					<select id="rel_diacono_1" class="form-select choice-select">
						<option value="">Pesquisar Diácono ou Presbítero...</option>
						<?php if (!empty($oficiais)): foreach($oficiais as $o): ?>
							<?php
								// Evita o erro de Undefined Index e Deprecated htmlspecialchars
								$nome = $o['membro_nome'] ?? '';
								$cargo = $o['cargo_nome'] ?? '';
							?>
							<?php if ($nome): ?>
								<option value="<?= htmlspecialchars($nome) ?>">
									<?= htmlspecialchars($nome) ?> (<?= htmlspecialchars($cargo) ?>)
								</option>
							<?php endif; ?>
						<?php endforeach; endif; ?>
					</select>
				</div>

				<div class="mb-2">
					<label class="small fw-bold text-muted text-uppercase mb-1 d-block">2º Conferente</label>
					<select id="rel_diacono_2" class="form-select choice-select">
						<option value="">Pesquisar Diácono ou Presbítero...</option>
						<?php if (!empty($oficiais)): foreach($oficiais as $o): ?>
							<?php
								$nome = $o['membro_nome'] ?? '';
								$cargo = $o['cargo_nome'] ?? '';
							?>
							<?php if ($nome): ?>
								<option value="<?= htmlspecialchars($nome) ?>">
									<?= htmlspecialchars($nome) ?> (<?= htmlspecialchars($cargo) ?>)
								</option>
							<?php endif; ?>
						<?php endforeach; endif; ?>
					</select>
				</div>
            </div>

            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark px-4 fw-bold" onclick="executarGerarRelatorio()">
                    <i class="bi bi-printer me-2"></i> GERAR PARA IMPRESSÃO
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalNovaConta" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('financeiro/salvar_conta_agendada') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="id" id="input_id">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modal_titulo">Agendar Novo Lançamento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="small fw-bold">Descrição do Lançamento</label>
                        <input type="text" name="descricao" id="input_descricao" class="form-control" placeholder="Ex: Conta de Luz" required>
                    </div>
					<div class="col-md-12">
						<label class="small fw-bold text-secondary">Classificação</label>
						<select name="subcategoria_id" id="select_subcategoria_nova" class="form-select choice-select-color" onchange="atualizarTipoLancamento()">
							<option value="">Digite para pesquisar...</option>
							<?php if(!empty($categorias_formatadas)): ?>
								<?php foreach($categorias_formatadas as $cat): ?>
									<option value="<?= $cat['subcategoria_id'] ?>"
											data-tipo="<?= $cat['financeiro_categoria_tipo'] ?>">
										<?= $cat['nome_formatado'] ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<div class="form-check form-switch mt-2">
							<input class="form-check-input" type="checkbox" name="reembolso" id="check_reembolso" value="1">
							<label class="form-check-label small fw-bold text-primary" for="check_reembolso">
								<i class="bi bi-cash-stack"></i> Este lançamento é um Reembolso?
							</label>
						</div>
					</div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Tipo</label>
                        <select name="tipo" id="select_tipo" class="form-select">
                            <option value="saida">Saída</option>
                            <option value="entrada">Entrada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Valor (R$)</label>
                        <input type="number" step="0.01" name="valor" id="input_valor" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold">Vencimento</label>
                        <input type="date" name="vencimento" id="input_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Confirmar Agendamento</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalBaixa" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('financeiro/baixar_conta') ?>" method="POST" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="conta_id" id="baixa_id">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold">Confirmar Baixa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted">VALOR</label>
                    <input type="text" id="baixa_valor_display" class="form-control bg-light fw-bold border-0" readonly>
                    <input type="hidden" name="valor" id="baixa_valor_real">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted">CONTA FINANCEIRA</label>
                    <select name="conta_financeira_id" class="form-select shadow-sm" required>
                        <option value="">Selecione a conta...</option>
                        <?php foreach($contas_bancarias as $cb): ?>
                            <option value="<?= $cb['financeiro_conta_financeira_id'] ?>">
                                <?= $cb['financeiro_conta_financeira_nome'] ?>
                                (R$ <?= number_format($cb['financeiro_conta_financeira_saldo'], 2, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold text-muted">DATA DO PAGAMENTO</label>
                    <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            <div class="modal-footer bg-light p-2 border-0">
                <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                    <i class="bi bi-check-circle me-1"></i> Confirmar e Baixar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalRateioMembros" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-people me-2"></i>Rateio por Membros</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rateio_conta_id">
                <input type="hidden" id="rateio_subcategoria_id">

                <div class="alert alert-secondary py-2 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-uppercase fw-bold text-muted">Total:</small>
                        <span class="h5 fw-bold mb-0" id="rateio_total_display">R$ 0,00</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-uppercase fw-bold text-danger">Falta:</small>
                        <span class="h5 fw-bold text-danger mb-0" id="rateio_restante_display">R$ 0,00</span>
                    </div>
                </div>

                <div class="row g-2 mb-3 bg-light p-2 rounded border">
                    <div class="col-md-7">
                        <label class="small fw-bold">Membro</label>
                        <select id="rateio_membro_id">
                            <option value="">Buscar membro...</option>
                            <?php if(!empty($membros)): ?>
                                <?php foreach($membros as $m):
                                    $nomeExibir = $m['membro_nome'] ?? $m['nome'] ?? 'Membro';
                                    $idExibir = $m['membro_id'] ?? $m['id'] ?? '';
                                ?>
                                    <option value="<?= $idExibir ?>"><?= htmlspecialchars((string)$nomeExibir) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold">Valor (R$)</label>
                        <input type="number" step="0.01" id="rateio_valor" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" onclick="adicionarMembroLista()" class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 200px;">
                    <table class="table table-sm table-hover table-bordered mb-0">
                        <thead class="bg-light sticky-top">
                            <tr class="small text-muted">
                                <th>Membro</th>
                                <th class="text-end">Valor</th>
                                <th class="text-center">#</th>
                            </tr>
                        </thead>
                        <tbody id="lista_rateio_membros">
                            <tr><td colspan="3" class="text-center py-3 text-muted small">Nenhum membro adicionado.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light p-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" onclick="salvarRateioFinal()" id="btnSalvarRateio" class="btn btn-info btn-sm fw-bold disabled">Gravar Rateio</button>
            </div>
        </div>
    </div>
</div>

<script>
let membrosRateio = [];
let valorTotalReceita = 0;
let choicesMembro;

function pagarConta(id, valor) {
    document.getElementById('baixa_id').value = id;
    document.getElementById('baixa_valor_real').value = valor;
    document.getElementById('baixa_valor_display').value = valor.toLocaleString('pt-BR', {minimumFractionDigits: 2});
    new bootstrap.Modal(document.getElementById('modalBaixa')).show();
}

function atualizarTipoLancamento() {
    const selectSub = document.getElementById('select_subcategoria_nova');
    const selectTipo = document.getElementById('select_tipo');
    if (!selectSub || !selectTipo) return;

    const selectedOption = selectSub.options[selectSub.selectedIndex];
    if (selectedOption) {
        const tipo = selectedOption.getAttribute('data-tipo');
        if (tipo) selectTipo.value = tipo;
    }
}

// 1. Crie um objeto global para as instâncias logo no início do script
window.instanciasChoices = {};

document.addEventListener('DOMContentLoaded', function() {
    const colorSelects = document.querySelectorAll('.choice-select-color');

    colorSelects.forEach(el => {
        // 2. Guarde a instância no objeto global usando o ID do elemento
        window.instanciasChoices[el.id] = new Choices(el, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false,
            allowHTML: true,
            callbackOnCreateTemplates: function(strToEl) {
				return {
					// Cor na lista de opções (dropdown)
					choice: (conf, data) => {
						const isReceita = data.label.includes('[ RECEITA ]');
						const isDespesa = data.label.includes('[ DESPESA ]');
						const cor = isReceita ? '#006437' : (isDespesa ? '#d9534f' : 'inherit');

						return strToEl(`
							<div class="choices__item choices__item--choice"
								 data-choice data-id="${data.id}" data-value="${data.value}"
								 style="color: ${cor}; font-weight: bold;">
								${data.label}
							</div>
						`);
					},
					// Cor no item selecionado (dentro do campo)
					item: (conf, data) => {
						const isReceita = data.label.includes('[ RECEITA ]');
						const isDespesa = data.label.includes('[ DESPESA ]');
						const cor = isReceita ? '#006437' : (isDespesa ? '#d9534f' : 'inherit');

						return strToEl(`
							<div class="choices__item choices__item--selectable"
								 data-item data-id="${data.id}" data-value="${data.value}"
								 style="color: ${cor}; font-weight: bold;">
								${data.label}
							</div>
						`);
					},
				};
			},
		});

        // Evento para atualizar o tipo (Entrada/Saída) no modal de nova conta
        el.addEventListener('change', function() {
            if (el.id === 'select_subcategoria_nova') {
                atualizarTipoLancamento();
            }
        });
    });
});

function abrirModalMembros(id, valor, subcategoriaId) {
    document.getElementById('rateio_conta_id').value = id;
    document.getElementById('rateio_subcategoria_id').value = subcategoriaId || '';
    document.getElementById('rateio_total_display').innerText = valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    valorTotalReceita = valor;
    membrosRateio = [];
    atualizarTabelaRateio();

    const element = document.getElementById('rateio_membro_id');
    if (element) {
        if (choicesMembro) choicesMembro.destroy();
        choicesMembro = new Choices(element, { searchEnabled: true, itemSelectText: '', noResultsText: 'Não encontrado' });
    }

    new bootstrap.Modal(document.getElementById('modalRateioMembros')).show();
}

function adicionarMembroLista() {
    const selecionado = choicesMembro.getValue(true);
    const itemData = choicesMembro.getValue();
    const texto = itemData ? itemData.label : '';
    const valorInput = document.getElementById('rateio_valor');
    const valorMembro = parseFloat(valorInput.value);

    if(!selecionado || isNaN(valorMembro) || valorMembro <= 0) {
        alert("Dados inválidos.");
        return;
    }

    membrosRateio.push({ membro_id: selecionado, nome: texto, valor: valorMembro });
    valorInput.value = '';
    choicesMembro.setChoiceByValue('');
    atualizarTabelaRateio();
}

function atualizarTabelaRateio() {
    const tbody = document.getElementById('lista_rateio_membros');
    const btnSalvar = document.getElementById('btnSalvarRateio');
    const displayRestante = document.getElementById('rateio_restante_display');
    tbody.innerHTML = '';
    let totalRateado = 0;

    membrosRateio.forEach((m, index) => {
        totalRateado += m.valor;
        tbody.innerHTML += `<tr><td>${m.nome}</td><td class="text-end">R$ ${m.valor.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td><td class="text-center"><button class="btn btn-sm text-danger" onclick="removerMembroRateio(${index})"><i class="bi bi-trash"></i></button></td></tr>`;
    });

    const restante = Math.round((valorTotalReceita - totalRateado) * 100) / 100;
    displayRestante.innerText = restante.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    if (restante === 0) {
        btnSalvar.classList.remove('disabled');
        displayRestante.classList.replace('text-danger', 'text-success');
    } else {
        btnSalvar.classList.add('disabled');
        displayRestante.classList.replace('text-success', 'text-danger');
    }
}

function removerMembroRateio(index) {
    membrosRateio.splice(index, 1);
    atualizarTabelaRateio();
}

async function salvarRateioFinal() {
    const btn = document.getElementById('btnSalvarRateio');
    btn.innerHTML = 'Salvando...';
    btn.classList.add('disabled');

    const data = {
        conta_id: document.getElementById('rateio_conta_id').value,
        subcategoria_id: document.getElementById('rateio_subcategoria_id').value,
        membros: membrosRateio
    };

    try {
        const response = await fetch('<?= url("financeiro/salvar_rateio_membros") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.sucesso) location.reload();
        else { alert("Erro: " + result.erro); btn.innerHTML = 'Gravar Rateio'; btn.classList.remove('disabled'); }
    } catch (error) { alert("Erro de conexão."); btn.innerHTML = 'Gravar Rateio'; btn.classList.remove('disabled'); }
}

function abrirModalAnexo(dados, tipo) {
    let id, membros, arquivoAtual;

    if (typeof dados === 'object' && dados !== null) {
        id = dados.financeiro_conta_id;
        membros = dados.membros || [];
        arquivoAtual = (tipo === 'comprovante') ? dados.financeiro_conta_comprovante : dados.financeiro_conta_nota_fiscal;
    } else {
        id = dados;
        membros = [];
        arquivoAtual = "";
    }

    document.getElementById('anexo_conta_id').value = id;
    document.getElementById('anexo_tipo').value = tipo;

    const areaVisu = document.getElementById('area_visualizacao');
    const areaRateio = document.getElementById('area_rateio_anexos');
    const uploadSec = document.querySelector('.upload-section');
    const titulo = document.getElementById('titulo_anexo');
    const listaMembros = document.getElementById('lista_membros_upload');
    const labelUpload = document.getElementById('label_upload'); // Referência ao label

    // Reset padrão
    areaVisu.classList.add('d-none');
    areaRateio.classList.add('d-none');
    uploadSec.classList.add('d-none'); // Começa escondido para decidir depois
    listaMembros.innerHTML = '';

    // Caso 1: Nota Fiscal ou Comprovante sem Rateio
    if (tipo === 'notafiscal' || (tipo === 'comprovante' && membros.length === 0)) {
        titulo.innerText = (tipo === 'notafiscal') ? "Anexar Nota Fiscal" : "Anexar Comprovante";

        // Sempre mostramos a seção de upload nestes casos
        uploadSec.classList.remove('d-none');

        if (arquivoAtual) {
            renderizarPreview(arquivoAtual);
            areaVisu.classList.remove('d-none');
            if (labelUpload) labelUpload.innerText = "Substituir arquivo:";
        } else {
            if (labelUpload) labelUpload.innerText = "Selecionar arquivo:";
        }
    }
    // Caso 2: Comprovante com Rateio
    else if (tipo === 'comprovante' && membros.length > 0) {
        titulo.innerText = "Comprovantes do Rateio";
        areaRateio.classList.remove('d-none');
        // Aqui a seção de upload geral fica escondida pois o upload é individual por membro
        uploadSec.classList.add('d-none');

        const htmlMembros = membros.map(m => {
            const temDoc = m.receita_membro_comprovante;
            const valorFmt = parseFloat(m.receita_membro_valor || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2});

            return `
                <div class="list-group-item bg-light mb-3 border rounded p-3 bloco-upload-membro">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="small d-block text-primary">${m.membro_nome}</strong>
                            <span class="badge bg-white text-dark border shadow-sm">R$ ${valorFmt}</span>
                        </div>
                        <div class="status-anexo-membro" id="status_container_${m.receita_membro_id}">
                            ${temDoc
                                ? `<a href="<?= url('public/assets/uploads/') ?>${temDoc}" target="_blank" class="btn btn-sm btn-success shadow-sm">
                                     <i class="bi bi-eye"></i> Ver Anexo
                                   </a>`
                                : `<span class="badge bg-warning-subtle text-warning border border-warning" style="font-size:11px">PENDENTE</span>`
                            }
                        </div>
                    </div>

                    <div class="row g-1 border-top pt-2">
                        <input type="hidden" class="membro-id-input" value="${m.receita_membro_id}">
                        <div class="col-9">
                            <input type="file" class="form-control form-control-sm arquivo-membro-input">
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="executarUploadManual(this)">
                                <i class="bi bi-upload"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        }).join('');

        listaMembros.innerHTML = htmlMembros;
    }

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAnexo')).show();
}

// Função auxiliar para o Preview (Reaproveitando seu código de visualização)
function renderizarPreview(arquivoLimpo) {
    const areaVisu = document.getElementById('area_visualizacao');
    const contentPreview = document.getElementById('content_preview');
    const linkExterno = document.getElementById('link_abrir_externo');

    areaVisu.classList.remove('d-none');
    const urlArquivo = '<?= url("public/assets/uploads/") ?>' + arquivoLimpo.replace(/\\/g, '/').replace(/^\//, '');
    linkExterno.href = urlArquivo;

    const extensao = arquivoLimpo.split('.').pop().toLowerCase();
    if (extensao === 'pdf') {
        contentPreview.innerHTML = `<div class="text-center p-4"><i class="bi bi-file-earmark-pdf text-danger fs-1"></i><br><a href="${urlArquivo}" target="_blank" class="btn btn-link">Abrir PDF</a></div>`;
    } else {
        contentPreview.innerHTML = `<img src="${urlArquivo}" class="img-fluid rounded">`;
    }
}

function executarUploadManual(botao) {
    const bloco = botao.closest('.bloco-upload-membro');
    const inputArquivo = bloco.querySelector('.arquivo-membro-input');
    const membroId = bloco.querySelector('.membro-id-input').value;
    const contaId = document.getElementById('anexo_conta_id').value;
    const statusContainer = document.getElementById(`status_container_${membroId}`);

    if (!inputArquivo.files.length) {
        Swal.fire('Atenção', 'Selecione um arquivo.', 'warning');
        return;
    }

    const formData = new FormData();
    formData.append('arquivo', inputArquivo.files[0]);
    formData.append('conta_id', contaId);
    formData.append('membro_id', membroId);
    formData.append('is_ajax', '1');
    formData.append('tipo_arquivo', 'comprovante');
    formData.append('ano_referencia', '<?= $anoSelecionado ?>');
    formData.append('mes_referencia', '<?= $mesSelecionado ?>');

    // Feedback visual no botão
    const iconOriginal = botao.innerHTML;
    botao.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    botao.disabled = true;

    fetch('<?= url("financeiro/uploadAnexo") ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 1. Limpa o input de arquivo
            inputArquivo.value = '';

            // 2. Atualiza o status visual no modal sem fechar nada
            if (statusContainer && data.arquivo) {
                const urlBase = '<?= url("public/assets/uploads/") ?>';
                statusContainer.innerHTML = `
                    <a href="${urlBase}${data.arquivo}" target="_blank" class="btn btn-sm btn-success shadow-sm animate__animated animate__fadeIn">
                        <i class="bi bi-eye"></i> Ver Anexo
                    </a>
                `;
            }

            // 3. Exibe uma mensagem de sucesso discreta no topo do modal ou via Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'success',
                title: 'Upload realizado com sucesso!'
            });

        } else {
            Swal.fire('Erro', data.message, 'error');
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
    })
    .finally(() => {
        // Restaura o botão
        botao.innerHTML = iconOriginal;
        botao.disabled = false;
    });
}


	function executarUploadAsync(botao) {
		// Busca o formulário pai deste botão específico
		const form = botao.closest('.form-upload-membro');
		if (!form) return;

		// Busca o input de arquivo dentro DESTE formulário
		const inputArquivo = form.querySelector('.input-arquivo-membro');
		const membroId = form.querySelector('input[name="membro_id"]').value;

		if (!inputArquivo || inputArquivo.files.length === 0) {
			Swal.fire('Atenção', 'Selecione um arquivo para este membro.', 'warning');
			return;
		}

		// Cria o FormData APENAS com os dados deste formulário
		const formData = new FormData(form);

		// Feedback visual no botão
		const iconOriginal = botao.innerHTML;
		botao.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
		botao.disabled = true;

		fetch(form.action, {
			method: 'POST',
			body: formData,
			headers: { 'X-Requested-With': 'XMLHttpRequest' }
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				Swal.fire({
					icon: 'success',
					title: 'Sucesso!',
					text: 'Upload concluído para este membro.',
					timer: 1500,
					showConfirmButton: false
				}).then(() => {
					// Em vez de atualizar o HTML manualmente e arriscar erro de JS,
					// vamos recarregar para garantir que o banco e a tela estejam iguais.
					location.reload();
				});
			} else {
				Swal.fire('Erro', data.message, 'error');
				botao.innerHTML = iconOriginal;
				botao.disabled = false;
			}
		})
		.catch(error => {
			console.error('Erro:', error);
			Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
			botao.innerHTML = iconOriginal;
			botao.disabled = false;
		});
	}

// Recarregar se houve mudanças
document.getElementById('modalAnexo').addEventListener('hidden.bs.modal', function () {
    if (this.getAttribute('data-refresh') === 'true') {
        location.reload();
    }
});

function abrirModalRelatorioConferencia() {
    new bootstrap.Modal(document.getElementById('modalRelatorioConferencia')).show();
}

function executarGerarRelatorio() {
    const data = document.getElementById('rel_data_movimento').value;
    const d1 = document.getElementById('rel_diacono_1').value;
    const d2 = document.getElementById('rel_diacono_2').value;

    // Validações básicas
    if (!data) {
        alert("Selecione a data do movimento.");
        return;
    }
    if (!d1 || !d2) {
        alert("Por favor, selecione os dois diáconos para a assinatura.");
        return;
    }
    if (d1 === d2) {
        alert("Os conferentes devem ser pessoas diferentes.");
        return;
    }

    // Monta a URL para o controller que criamos no passo anterior
    const urlBase = "<?= url('financeiro/relatorio_raw') ?>";
    const params = new URLSearchParams({
        data: data,
        d1: d1,
        d2: d2
    });

    // Abre em nova janela (Raw View)
    window.open(`${urlBase}?${params.toString()}`, '_blank');

    // Fecha o modal
    const modalEl = document.getElementById('modalRelatorioConferencia');
    const modalBus = bootstrap.Modal.getInstance(modalEl);
    modalBus.hide();
}

document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.choice-select');
    selects.forEach(select => {
        new Choices(select, {
            searchEnabled: true,
            searchPlaceholderValue: 'Digite o nome...',
            noResultsText: 'Nenhum oficial encontrado',
            itemSelectText: 'Pressione Enter',
            allowHTML: true,
            shouldSort: false
        });
    });
});

function abrirModalQR(id, tipo) {
    const container = document.getElementById('qrcode_financeiro');
    const inputLink = document.getElementById('inputLinkUpload');
    container.innerHTML = "";

    // Dados da igreja para o acesso externo
    const idIgreja = "<?= $_SESSION['usuario_igreja_id'] ?>";
    const urlFinal = "<?= full_url('financeiro/uploadExterno/') ?>" + id + "?i=" + idIgreja;
    // Atualiza o input de texto com o link gerado
    inputLink.value = urlFinal;

    // Gera o QR Code
    new QRCode(container, {
        text: urlFinal,
        width: 180,
        height: 180,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    const modalEl = document.getElementById('modalQRCode');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

// Função para abrir o link em uma nova aba para teste no PC
// 1. LISTA DE MEMBROS (Gerada de forma segura para o JS)
const listaMembrosJS = [
    <?php if(!empty($membros) && is_array($membros)): foreach($membros as $m): ?>
    {
        // Verifica as chaves prováveis: membro_id, id ou o ID do usuário da tabela de receita
        id: '<?= $m['membro_id'] ?? $m['id'] ?? $m['receita_membro_usuario_id'] ?? '' ?>',
        nome: "<?= addslashes($m['membro_nome'] ?? $m['nome'] ?? 'Membro não identificado') ?>"
    },
    <?php endforeach; endif; ?>
];


// 2. FUNÇÃO ADICIONAR LINHA (Versão Corrigida e Segura)
function adicionarMembroEdicao(membroId, valor) {
    membroId = membroId || '';
    valor = valor || '';

    var lista = document.getElementById('listaMembrosEdicao');
    if (!lista) return;

    var div = document.createElement('div');
    div.className = 'row g-2 mb-2 align-items-center membro-item-edicao';

    // Construímos as opções fora do loop para não gerar erros de sintaxe por valores nulos
    var optionsHtml = '<option value="">Selecione o Membro...</option>';

    // Usamos a lista que você já definiu no JS (listaMembrosJS) em vez de abrir PHP dentro da função
    if (typeof listaMembrosJS !== 'undefined' && listaMembrosJS.length > 0) {
        listaMembrosJS.forEach(function(m) {
            var selected = (String(m.id) === String(membroId)) ? 'selected' : '';
            optionsHtml += '<option value="' + m.id + '" ' + selected + '>' + m.nome + '</option>';
        });
    }

    var selectHtml = '<div class="col-md-7">';
    selectHtml += '<select name="membros[]" class="form-select select-membro-edit" required>';
    selectHtml += optionsHtml;
    selectHtml += '</select></div>';

    var inputHtml = '<div class="col-md-4">';
    inputHtml += '<input type="number" step="0.01" name="membros_valores[]" class="form-control valor-membro-edit" value="' + valor + '" placeholder="0,00" required oninput="recalcularRateioEdicao()">';
    inputHtml += '</div>';

    var buttonHtml = '<div class="col-md-1 text-end">';
    buttonHtml += '<button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest(\'.row\').remove(); recalcularRateioEdicao();">';
    buttonHtml += '<i class="bi bi-trash"></i></button></div>';

    div.innerHTML = selectHtml + inputHtml + buttonHtml;
    lista.appendChild(div);
}

async function editarLancamento(id) {
    try {
        const response = await fetch("<?= url('financeiro/getContaJson/') ?>" + id);
        const dados = await response.json();

        const modalEl = document.getElementById('modalEditarLancamento');
        if (!modalEl || !dados) return;

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        // 2. Preenchimento de campos básicos
        document.getElementById('edit_id').value = dados.financeiro_conta_id;
        document.getElementById('edit_descricao').value = dados.financeiro_conta_descricao;
        document.getElementById('edit_valor').value = dados.financeiro_conta_valor;

        const dataRef = dados.financeiro_conta_data_pagamento || dados.financeiro_conta_data_vencimento;
        if (dataRef) {
            document.getElementById('edit_data_pagamento').value = dataRef.substring(0, 10);
        }

        // 3. COMBO CONTA FINANCEIRA
        const idBanco = dados.financeiro_conta_financeira_id;
        const comboBanco = document.getElementById('edit_conta_financeira_id');

        if (comboBanco) {
            if (idBanco) {
                comboBanco.value = String(idBanco);
            } else {
                comboBanco.selectedIndex = 0;
            }

            modalEl.addEventListener('shown.bs.modal', () => {
                if (idBanco) comboBanco.value = String(idBanco);
            }, { once: true });
        }

        // 4. Combo Categoria (Choices.js)
        const idSub = dados.financeiro_conta_financeiro_categoria_id;
        const instCat = (window.instanciasChoices && window.instanciasChoices['edit_categoria_id']) ? window.instanciasChoices['edit_categoria_id'] : null;
        if (instCat && idSub) instCat.setChoiceByValue(String(idSub));

        // 5. Lógica de Rateio
        const areaRateio = document.getElementById('areaRateioEdicao');
        const lista = document.getElementById('listaMembrosEdicao');
        if (lista) lista.innerHTML = "";

        if (dados.financeiro_conta_tipo === 'entrada') {
            areaRateio.classList.remove('d-none');
            const resRateio = await fetch("<?= url('financeiro/getRateio/') ?>" + id);
            const membros = await resRateio.json();

            // Se houver membros no banco, carrega eles.
            // Se não houver, deixa a lista vazia (Opcional).
            if (membros && membros.length > 0) {
                membros.forEach(m => adicionarMembroEdicao(m.receita_membro_usuario_id, m.receita_membro_valor));
            }

            // Chama o recalcular para validar se o botão deve estar ativo ou não
            recalcularRateioEdicao();
        } else {
            areaRateio.classList.add('d-none');
            if(document.getElementById('btnSalvarEdicao')) document.getElementById('btnSalvarEdicao').disabled = false;
        }

        modal.show();

    } catch (error) {
        console.error("Erro ao carregar dados:", error);
        alert("Erro ao buscar dados do lançamento.");
    }
}

// 4. Recalcular Soma
function recalcularRateioEdicao() {
    const valorTotal = parseFloat(document.getElementById('edit_valor').value) || 0;
    const areaRateio = document.getElementById('areaRateioEdicao');
    const btnSalvar = document.getElementById('btnSalvarEdicao');
    const label = document.getElementById('labelRestanteEdicao');

    // Se for Despesa (área escondida), libera sempre.
    if (!areaRateio || areaRateio.classList.contains('d-none')) {
        if (btnSalvar) btnSalvar.disabled = false;
        return;
    }

    const inputsMembros = document.querySelectorAll('.valor-membro-edit');
    const temMembros = inputsMembros.length > 0;

    let somaRateio = 0;
    inputsMembros.forEach(input => {
        somaRateio += parseFloat(input.value) || 0;
    });

    const restante = (valorTotal - somaRateio).toFixed(2);

    if (label) {
        // Se não tem membros adicionados, o rateio é opcional
        if (!temMembros) {
            label.innerText = "Rateio Opcional";
            label.className = "badge bg-secondary";
            if (btnSalvar) btnSalvar.disabled = false;
        } else {
            label.innerText = 'Restante: R$ ' + parseFloat(restante).toLocaleString('pt-br', {minimumFractionDigits: 2});

            // Se tem membros, o valor total DEVE ser distribuído (restante 0)
            if (Math.abs(restante) < 0.01) {
                label.className = "badge bg-success";
                if (btnSalvar) btnSalvar.disabled = false;
            } else {
                label.className = "badge bg-danger";
                if (btnSalvar) btnSalvar.disabled = true;
            }
        }
    }
}
</script>
