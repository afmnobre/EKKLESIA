<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-piggy-bank-fill me-2 text-success"></i>Tesouraria</h3>
        <div>
            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTransferencia">
                <i class="bi bi-arrow-left-right"></i> Transferir entre Contas
            </button>
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nova Entrada/Saída
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach($contas as $conta): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted small fw-bold text-uppercase"><?= $conta['financeiro_conta_financeira_nome'] ?></h6>
                            <h3 class="fw-bold mb-0">R$ <?= number_format($conta['financeiro_conta_financeira_saldo'], 2, ',', '.') ?></h3>
                                <span class="badge bg-light text-dark border mt-2 text-uppercase">
                                    <?= $conta['financeiro_conta_financeira_tipo'] ?? 'Conta' ?>
                                </span>
                        </div>
                        <div class="bg-success bg-opacity-10 p-2 rounded text-success">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body">
			<form method="GET" action="<?= url('financeiro/index') ?>" class="row g-2 align-items-end">
				<div class="col-md-3">
					<label class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem;">Data Inicial</label>
					<input type="date" name="data_inicio" class="form-control form-control-sm" value="<?= $dataInicio ?>">
				</div>

				<div class="col-md-3">
					<label class="small fw-bold text-muted text-uppercase" style="font-size: 0.65rem;">Data Final</label>
					<input type="date" name="data_fim" class="form-control form-control-sm" value="<?= $dataFim ?>">
				</div>

				<div class="col-md-2">
					<button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">
						<i class="bi bi-filter"></i> Filtrar
					</button>
				</div>

				<div class="col-md-2">
					<a href="<?= url('financeiro/index') ?>" class="btn btn-outline-secondary btn-sm w-100">
						Limpar
					</a>
				</div>

				<div class="col-md-2">
					<button type="button" onclick="exportarExcel()" class="btn btn-success btn-sm w-100 text-nowrap">
						<i class="bi bi-file-earmark-excel"></i> Excel
					</button>
				</div>
			</form>
		</div>
	</div>

    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Movimentações do Período</span>
                <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25">
                    <?= date('d/m/Y', strtotime($dataInicio)) ?> - <?= date('d/m/Y', strtotime($dataFim)) ?>
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small text-muted">
                            <tr>
                                <th class="ps-4">Data</th>
                                <th>Conta/Caixa</th>
                                <th>Descrição</th>
                                <th class="text-center">Origem</th>
                                <th class="text-end" style="width: 150px;">Entrada (R$)</th>
                                <th class="text-end pe-4" style="width: 150px;">Saída (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($movimentacoes)): foreach($movimentacoes as $mov): ?>
                            <tr>
                                <td class="ps-4 small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($mov['financeiro_movimentacao_data'])) ?>
                                </td>
                                <td>
                                    <span class="badge border text-dark fw-normal">
                                        <?= $mov['financeiro_conta_financeira_nome'] ?>
                                    </span>
                                </td>
                                <td><?= $mov['financeiro_movimentacao_descricao'] ?></td>
                                <td class="text-center">
                                    <?php
                                        $origem = $mov['financeiro_movimentacao_origem'];
                                        $badgeClass = ($origem == 'pagamento') ? 'bg-info text-white' : (($origem == 'transferencia') ? 'bg-warning text-dark' : 'bg-light text-dark');
                                    ?>
                                    <small class="badge <?= $badgeClass ?> text-uppercase" style="font-size: 0.65rem;">
                                        <?= $origem ?>
                                    </small>
                                </td>

                                <td class="text-end fw-bold text-success">
                                    <?= $mov['financeiro_movimentacao_tipo'] == 'entrada' ? 'R$ ' . number_format($mov['financeiro_movimentacao_valor'], 2, ',', '.') : '-' ?>
                                </td>

                                <td class="text-end pe-4 fw-bold text-danger">
                                    <?= $mov['financeiro_movimentacao_tipo'] == 'saida' ? 'R$ ' . number_format($mov['financeiro_movimentacao_valor'], 2, ',', '.') : '-' ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Nenhuma movimentação registrada no período selecionado.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTransferencia" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="<?= url('financeiro/transferir') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transferir Valores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Origem (Sairá daqui)</label>
                    <select name="origem" class="form-select" required>
                        <?php foreach($contas as $c): ?>
                            <option value="<?= $c['financeiro_conta_financeira_id'] ?>"><?= $c['financeiro_conta_financeira_nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Destino (Entrará aqui)</label>
                    <select name="destino" class="form-select" required>
                        <?php foreach($contas as $c): ?>
                            <option value="<?= $c['financeiro_conta_financeira_id'] ?>"><?= $c['financeiro_conta_financeira_nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Observação</label>
                    <input type="text" name="descricao" class="form-control" placeholder="Ex: Depósito Bancário">
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="submit" class="btn btn-primary w-100">Confirmar Transferência</button>
            </div>
        </form>
    </div>
</div>

<script>
function exportarExcel() {
    // Pega os valores das datas que estão nos inputs de filtro
    const dataInicio = document.querySelector('input[name="data_inicio"]').value;
    const dataFim = document.querySelector('input[name="data_fim"]').value;

    // Constrói a URL com os parâmetros
    const baseUrl = "<?= url('financeiro/exportar_extrato') ?>";
    const finalUrl = `${baseUrl}?data_inicio=${dataInicio}&data_fim=${dataFim}`;

    // Redireciona para baixar o arquivo
    window.location.href = finalUrl;
}
</script>
