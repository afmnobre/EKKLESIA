<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-calendar-check me-2 text-primary"></i>Contas a Pagar/Receber</h3>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovaConta">
            <i class="bi bi-plus-lg"></i> Novo Lançamento
        </button>
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
                                    <span class="badge bg-success-subtle text-success px-3">Pago</span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning px-3">Pendente</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <?php if(!$c['financeiro_conta_pago']): ?>
                                        <button class="btn btn-sm btn-success px-3" title="Dar Baixa"
                                                onclick="pagarConta(<?= $c['financeiro_conta_id'] ?>, <?= $c['financeiro_conta_valor'] ?>)">
                                            <i class="bi bi-check2-circle"></i>
                                        </button>

										<?php if($c['financeiro_conta_tipo'] == 'entrada'):
											// Tenta encontrar o ID da subcategoria em diferentes nomes possíveis
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
                            <option value="">Selecione...</option>
                            <?php foreach($categorias_agrupadas as $cat): ?>
                                <optgroup label="<?= strtoupper($cat['nome']) ?>">
                                    <?php foreach($cat['subs'] as $sub): ?>
                                        <option value="<?= $sub['id'] ?>" data-tipo="<?= $cat['tipo'] ?>"><?= $sub['nome'] ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
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
        <form action="<?= url('financeiro/baixar_conta') ?>" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            <input type="hidden" name="conta_id" id="baixa_id">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Confirmar Baixa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Valor</label>
                    <input type="text" id="baixa_valor_display" class="form-control bg-light fw-bold" readonly>
                    <input type="hidden" name="valor" id="baixa_valor_real">
                </div>
				<div class="mb-3">
					<label class="small fw-bold">Conta Financeira (Origem/Destino)</label>
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
                <div class="mb-3">
                    <label class="small fw-bold">Data Pagamento</label>
                    <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="mb-0">
                    <label class="small fw-bold">Comprovante</label>
                    <input type="file" name="comprovante" class="form-control form-control-sm">
                </div>
            </div>
            <div class="modal-footer bg-light p-2">
                <button type="submit" class="btn btn-success w-100 fw-bold">Confirmar e Baixar</button>
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
</script>
