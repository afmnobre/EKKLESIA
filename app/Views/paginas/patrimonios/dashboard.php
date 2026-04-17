<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Indicadores de Patrimônio</h3>
        <div class="btn-group">
            <a href="<?= url('patrimonios') ?>" class="btn btn-outline-primary btn-sm">Inventário</a>
            <a href="<?= url('patrimonios/novo') ?>" class="btn btn-primary btn-sm">Novo Item</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-bold">Total Ativos</small>
                <h3 class="mb-0 text-primary fw-bold"><?= $metrics['geral']['total_itens'] ?></h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-bold">Valor Total Investido</small>
                <h3 class="mb-0 text-success fw-bold">R$ <?= number_format($metrics['geral']['valor_total'] ?? 0, 2, ',', '.') ?></h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-bold text-warning">Em Manutenção</small>
                <h3 class="mb-0 fw-bold"><?= number_format($metrics['taxa_manutencao'], 1) ?>%</h3>
            </div>
        </div>
        <div class="col-md-6 col-lg-2">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-bold">Novos (30 dias)</small>
                <h3 class="mb-0 text-info fw-bold">+<?= $metrics['entradas_30_dias'] ?></h3>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <small class="text-muted text-uppercase fw-bold">Movimentações (Mês)</small>
                <h3 class="mb-0 text-secondary fw-bold"><?= $metrics['movimentacoes_30_dias'] ?></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Distribuição por Status</div>
                <div class="card-body p-4">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>

		<div class="col-md-8 mb-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white fw-bold">Patrimônio por Categoria (Quantidade)</div>
				<div class="card-body">
					<canvas id="chartCategoria" style="max-height: 300px;"></canvas>
				</div>
			</div>
		</div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">Top 5 Locais (Ocupação e Valor)</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="small text-muted">
                                <tr>
                                    <th>Localização</th>
                                    <th>Qtd Bens</th>
                                    <th>Valor Estimado</th>
                                    <th width="40%">Capacidade Ocupada</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($metrics['por_local'] as $local):
                                    $percent = ($metrics['geral']['total_itens'] > 0) ? ($local['qtd'] / $metrics['geral']['total_itens'] * 100) : 0;
                                ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= $local['patrimonio_local_nome'] ?></td>
                                    <td><span class="badge bg-light text-dark"><?= $local['qtd'] ?></span></td>
                                    <td>R$ <?= number_format($local['valor'] ?? 0, 2, ',', '.') ?></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: <?= $percent ?>%"></div>
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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // --- GRÁFICO DE STATUS ---
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($metrics['por_status'], 'status')) ?>.map(l => l.toUpperCase()),
            datasets: [{
                data: <?= json_encode(array_column($metrics['por_status'], 'qtd')) ?>,
                backgroundColor: ['#198754', '#ffc107', '#dc3545', '#0dcaf0'],
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
    });

    // --- NOVO: GRÁFICO DE CATEGORIAS ---
    const ctxCat = document.getElementById('chartCategoria').getContext('2d');
    new Chart(ctxCat, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($metrics['por_categoria'], 'categoria')) ?>,
            datasets: [{
                label: 'Quantidade de Itens',
                data: <?= json_encode(array_column($metrics['por_categoria'], 'qtd')) ?>,
                backgroundColor: '#0d6efd',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y', // Barras horizontais ficam melhores para nomes longos de categorias
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, beginAtZero: true },
                y: { grid: { display: false } }
            }
        }
    });
});
</script>
