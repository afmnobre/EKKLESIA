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
									<button type="button" class="btn btn-sm <?= !empty($c['financeiro_conta_comprovante']) ? 'btn-success' : 'btn-outline-secondary' ?>"
											onclick="abrirModalAnexo(<?= $c['financeiro_conta_id'] ?>, 'comprovante', '<?= $c['financeiro_conta_comprovante'] ?? '' ?>')" title="Comprovante">
										<i class="bi bi-receipt"></i>
									</button>

									<button type="button" class="btn btn-sm <?= !empty($c['financeiro_conta_nota_fiscal']) ? 'btn-info' : 'btn-outline-secondary' ?>"
											onclick="abrirModalAnexo(<?= $c['financeiro_conta_id'] ?>, 'notafiscal', '<?= $c['financeiro_conta_nota_fiscal'] ?? '' ?>')" title="Nota Fiscal">
										<i class="bi bi-file-earmark-text"></i>
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
						<select name="subcategoria_id" id="select_subcategoria" class="form-select" required onchange="atualizarTipoLancamento()">
							<option value="">Selecione a Classificação...</option>

							<?php foreach($categorias_agrupadas as $cat):
								$ehEntrada = ($cat['tipo'] == 'entrada');
								$cor = $ehEntrada ? '#006437' : '#d9534f'; // Verde IPB ou Vermelho Despesa
								$prefixo = $ehEntrada ? '[ RECEITA ] ' : '[ DESPESA ] ';
							?>
								<optgroup label="<?= $prefixo . strtoupper($cat['nome']) ?>" style="color: <?= $cor ?>; background: #f8f9fa;">
									<?php foreach($cat['subs'] as $sub): ?>
										<option value="<?= $sub['id'] ?>" data-tipo="<?= $cat['tipo'] ?>" style="color: #333;">
											<?= $sub['nome'] ?>
										</option>
									<?php endforeach; ?>
								</optgroup>
							<?php endforeach; ?>
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
    const selectSub = document.getElementById('select_subcategoria');
    const selectTipo = document.getElementById('select_tipo');
    const option = selectSub.options[selectSub.selectedIndex];
    const tipo = option.getAttribute('data-tipo');
    if (tipo) selectTipo.value = tipo;
}

function editarLancamento(dados) {
    document.getElementById('modal_titulo').innerText = 'Editar Lançamento';
    document.getElementById('input_id').value = dados.financeiro_conta_id;
    document.getElementById('input_descricao').value = dados.financeiro_conta_descricao;
    document.getElementById('select_subcategoria').value = dados.financeiro_conta_financeiro_subcategoria_id;
    document.getElementById('input_valor').value = dados.financeiro_conta_valor;
    document.getElementById('input_vencimento').value = dados.financeiro_conta_data_vencimento;
    document.getElementById('select_tipo').value = dados.financeiro_conta_tipo;
    new bootstrap.Modal(document.getElementById('modalNovaConta')).show();
}

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

function abrirModalAnexo(id, tipo, arquivoAtual) {
    // Configura os campos ocultos
    document.getElementById('anexo_conta_id').value = id;
    document.getElementById('anexo_tipo').value = tipo;

    // Título dinâmico
    const titulo = (tipo === 'comprovante') ? 'Comprovante de Pagamento' : 'Nota Fiscal';
    document.getElementById('titulo_anexo').innerText = titulo;

    const areaVisu = document.getElementById('area_visualizacao');
    const contentPreview = document.getElementById('content_preview');
    const linkExterno = document.getElementById('link_abrir_externo');
    const labelUpload = document.getElementById('label_upload');

    if (arquivoAtual && arquivoAtual !== "") {
        areaVisu.classList.remove('d-none');
        labelUpload.innerText = "Substituir arquivo atual:";

        const urlArquivo = '<?= url("public/assets/uploads/") ?>' + arquivoAtual;
        linkExterno.href = urlArquivo;

        // Verifica a extensão para decidir como exibir
        const extensao = arquivoAtual.split('.').pop().toLowerCase();

        if (extensao === 'pdf') {
            // Se for PDF, mostra um botão amigável (PDFs não renderizam bem em divs pequenas)
            contentPreview.innerHTML = `
                <div class="text-center p-5">
                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 4rem;"></i>
                    <p class="fw-bold mt-2">Documento PDF</p>
                    <a href="${urlArquivo}" target="_blank" class="btn btn-outline-danger btn-sm">Clique para Abrir PDF</a>
                </div>`;
        } else {
            // Se for Imagem (JPG, PNG, WEBP), renderiza no "Iframe" com scroll
            contentPreview.innerHTML = `
                <img src="${urlArquivo}" style="max-width: none; height: auto; cursor: zoom-in;"
                     title="Clique no link abaixo para ver original"
                     onclick="window.open('${urlArquivo}', '_blank')">`;
        }
    } else {
        // Se não houver arquivo, esconde o preview e ajusta o label
        areaVisu.classList.add('d-none');
        labelUpload.innerText = "Selecione o arquivo para upload:";
        contentPreview.innerHTML = '';
    }

    // Abre o modal
    new bootstrap.Modal(document.getElementById('modalAnexo')).show();
}


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

</script>
