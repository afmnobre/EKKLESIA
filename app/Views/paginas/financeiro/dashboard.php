<div class="container-fluid py-4">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h3 class="fw-bold mb-0 text-dark">Dashboard Financeiro</h3>
		<div>
			<a href="<?= url('financeiro/exportar_excel_dashboard') ?>" class="btn btn-success shadow-sm me-2">
				<i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel
			</a>

			<div class="badge bg-white text-dark shadow-sm border p-2 px-3 d-inline-block">
				<i class="bi bi-calendar3 me-2 text-primary"></i>
				<span class="text-uppercase"><?= date('M / Y') ?></span>
			</div>
		</div>
	</div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Receitas (Mês)</small>
                    <h3 class="fw-bold mb-0 text-primary">R$ <?= number_format($resumo['receitas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Despesas (Mês)</small>
                    <h3 class="fw-bold mb-0 text-danger">R$ <?= number_format($resumo['despesas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start <?= $resumo['saldo_mes'] >= 0 ? 'border-success' : 'border-warning' ?> border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Balanço Mensal</small>
                    <h3 class="fw-bold mb-0 <?= $resumo['saldo_mes'] >= 0 ? 'text-success' : 'text-warning' ?>">
                        R$ <?= number_format($resumo['saldo_mes'], 2, ',', '.') ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-dark border-4 bg-light">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Total em Caixa</small>
                    <h3 class="fw-bold mb-0 text-dark">R$ <?= number_format(array_sum(array_column($contas, 'financeiro_conta_financeira_saldo')), 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>


<div class="row g-4 mb-5">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="bi bi-arrow-left-right me-2 text-primary"></i>Comparativo de Receitas: <?= $anoAnterior ?> vs <?= $anoAtual ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-3">Subcategoria</th>
                            <th class="text-center"><?= $anoAnterior ?></th>
                            <th class="text-center"><?= $anoAtual ?></th>
                            <th class="text-end pe-3">Evolução</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($compReceitas)): foreach($compReceitas as $r):
                            $dif = $r['total_anterior'] > 0 ? (($r['total_atual'] - $r['total_anterior']) / $r['total_anterior']) * 100 : 0;
                        ?>
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold d-block text-dark"><?= $r['subcategoria_nome'] ?></span>
                                <small class="text-muted"><?= $r['financeiro_categoria_nome'] ?></small>
                            </td>
                            <td class="text-center">R$ <?= number_format($r['total_anterior'], 2, ',', '.') ?></td>
                            <td class="text-center fw-bold text-primary">R$ <?= number_format($r['total_atual'], 2, ',', '.') ?></td>
                            <td class="text-end pe-3">
                                <span class="badge <?= $dif >= 0 ? 'bg-success' : 'bg-danger' ?> rounded-pill">
                                    <?= ($dif > 0 ? '+' : '') . number_format($dif, 1) ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center py-3 text-muted">Nenhum dado de receita encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3">Distribuição de Crescimento</div>
            <div class="card-body">
                <div style="height: 320px;">
                    <canvas id="chartComparativoBarras"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="bi bi-arrow-down-right me-2 text-danger"></i>Comparativo de Despesas: <?= $anoAnterior ?> vs <?= $anoAtual ?>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-3">Subcategoria</th>
                            <th class="text-center"><?= $anoAnterior ?></th>
                            <th class="text-center"><?= $anoAtual ?></th>
                            <th class="text-end pe-3">Diferença</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($compDespesas)): foreach($compDespesas as $d):
                            $dif = $d['total_anterior'] > 0 ? (($d['total_atual'] - $d['total_anterior']) / $d['total_anterior']) * 100 : 0;
                        ?>
                        <tr>
                            <td class="ps-3">
                                <span class="fw-bold d-block text-dark"><?= $d['subcategoria_nome'] ?></span>
                                <small class="text-muted"><?= $d['financeiro_categoria_nome'] ?></small>
                            </td>
                            <td class="text-center">R$ <?= number_format($d['total_anterior'], 2, ',', '.') ?></td>
                            <td class="text-center fw-bold text-danger">R$ <?= number_format($d['total_atual'], 2, ',', '.') ?></td>
                            <td class="text-end pe-3">
                                <span class="badge <?= $dif <= 0 ? 'bg-success' : 'bg-warning text-dark' ?> rounded-pill">
                                    <?= ($dif > 0 ? '+' : '') . number_format($dif, 1) ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="4" class="text-center py-3 text-muted">Nenhum dado de despesa encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3">Maiores Gastos do Ano</div>
            <div class="card-body">
                <div style="height: 320px;">
                    <canvas id="chartCompDespesasBarras"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>





    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Fluxo de Caixa Anual</span>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="chartFluxo"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">Disponibilidade por Conta</div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach($contas as $c): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark"><?= $c['financeiro_conta_financeira_nome'] ?></h6>
                                <small class="text-muted text-uppercase" style="font-size: 0.6rem; letter-spacing: 1px;"><?= $c['financeiro_conta_financeira_tipo'] ?></small>
                            </div>
                            <span class="badge bg-white text-dark fs-6 border border-2 fw-bold p-2 px-3 rounded-pill shadow-sm">
                                R$ <?= number_format($c['financeiro_conta_financeira_saldo'], 2, ',', '.') ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$mesesNomes = ['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];

// Inicializadores de Totais Horizontais (por mês)
$totaisMensaisSaida = array_fill(1, 12, 0);
$totaisMensaisEntrada = array_fill(1, 12, 0);

$totalGeralAno = 0;
$totalGeralReceitas = 0;
?>

<div class="card border-0 shadow-sm mb-5">
    <div class="card-header bg-success text-white fw-bold py-3">
        <i class="bi bi-arrow-up-circle me-2"></i>DETALHAMENTO DE RECEITAS ANUAL
    </div>
    <div class="card-body p-0 text-nowrap table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="bg-light">
                <tr class="text-muted" style="font-size: 0.75rem;">
                    <th class="ps-3 py-3">RECEITAS</th>
                    <?php foreach($mesesNomes as $m) echo "<th class='text-center py-3'>$m</th>"; ?>
                    <th class="text-end pe-3 py-3">TOTAL GERAL</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($relatorio['entrada'])): ?>
                    <?php foreach($relatorio['entrada'] as $catId => $cat):
                        $totalCatLinha = array_sum($cat['meses']);
                        $totalGeralReceitas += $totalCatLinha;
                        // Acumula para o total por mês
                        foreach($cat['meses'] as $mesAlvo => $vMes) { $totaisMensaisEntrada[$mesAlvo] += $vMes; }
                    ?>
                        <tr class="fw-bold align-middle" style="background-color: #f8fff9;">
                            <td class="ps-3 py-2"><?= $cat['nome'] ?></td>
                            <?php foreach($cat['meses'] as $valor): ?>
                                <td class="text-center">R$ <?= number_format($valor, 2, ',', '.') ?></td>
                            <?php endforeach; ?>
                            <td class="text-end pe-3 text-success">R$ <?= number_format($totalCatLinha, 2, ',', '.') ?></td>
                        </tr>
                        <?php foreach($cat['subcategorias'] as $sub): ?>
                            <tr class="align-middle">
                                <td class="ps-5 text-muted small"><i><?= $sub['nome'] ?></i></td>
                                <?php foreach($sub['meses'] as $valor): ?>
                                    <td class="text-center text-muted small">R$ <?= number_format($valor, 2, ',', '.') ?></td>
                                <?php endforeach; ?>
                                <td class="text-end pe-3 text-muted small">R$ <?= number_format(array_sum($sub['meses']), 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <tr class="table-success fw-bold border-top border-dark">
                    <td class="ps-3">TOTAIS POR MÊS</td>
                    <?php foreach($totaisMensaisEntrada as $totalMes): ?>
                        <td class="text-center">R$ <?= number_format($totalMes, 2, ',', '.') ?></td>
                    <?php endforeach; ?>
                    <td class="text-end pe-3">R$ <?= number_format($totalGeralReceitas, 2, ',', '.') ?></td>
                </tr>
            </tbody>
            <tfoot class="table-dark">
                <tr class="fw-bold">
                    <td class="ps-3 py-3">TOTAL GERAL ANUAL DE RECEITAS</td>
                    <td colspan="12"></td>
                    <td class="text-end pe-3 py-3 fs-5">R$ <?= number_format($totalGeralReceitas, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-danger text-white fw-bold py-3">
        <i class="bi bi-arrow-down-circle me-2"></i>DETALHAMENTO DE DESPESAS ANUAL
    </div>
    <div class="card-body p-0 text-nowrap table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="bg-light text-muted">
                <tr style="font-size: 0.75rem;">
                    <th class="ps-3 py-3">DESPESAS</th>
                    <?php foreach($mesesNomes as $m) echo "<th class='text-center py-3'>$m</th>"; ?>
                    <th class="text-end pe-3 py-3">TOTAL GERAL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($relatorio['saida'] as $catId => $cat):
                    $totalCatLinha = array_sum($cat['meses']);
                    $totalGeralAno += $totalCatLinha;
                    // Acumula para o total por mês
                    foreach($cat['meses'] as $mesAlvo => $vMes) { $totaisMensaisSaida[$mesAlvo] += $vMes; }
                ?>
                    <tr class="fw-bold align-middle" style="background-color: #fff9f9;">
                        <td class="ps-3 py-2"><?= $cat['nome'] ?></td>
                        <?php foreach($cat['meses'] as $valor): ?>
                            <td class="text-center">R$ <?= number_format($valor, 2, ',', '.') ?></td>
                        <?php endforeach; ?>
                        <td class="text-end pe-3 text-danger">R$ <?= number_format($totalCatLinha, 2, ',', '.') ?></td>
                    </tr>
                    <?php foreach($cat['subcategorias'] as $sub): ?>
                        <tr class="align-middle">
                            <td class="ps-5 text-muted small"><i><?= $sub['nome'] ?></i></td>
                            <?php foreach($sub['meses'] as $valor): ?>
                                <td class="text-center text-muted small">R$ <?= number_format($valor, 2, ',', '.') ?></td>
                            <?php endforeach; ?>
                            <td class="text-end pe-3 text-muted small">R$ <?= number_format(array_sum($sub['meses']), 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <tr class="table-warning fw-bold border-top border-dark text-dark">
                    <td class="ps-3">TOTAIS POR MÊS</td>
                    <?php foreach($totaisMensaisSaida as $totalMes): ?>
                        <td class="text-center">R$ <?= number_format($totalMes, 2, ',', '.') ?></td>
                    <?php endforeach; ?>
                    <td class="text-end pe-3">R$ <?= number_format($totalGeralAno, 2, ',', '.') ?></td>
                </tr>
            </tbody>
            <tfoot class="table-dark">
                <tr class="fw-bold">
                    <td class="ps-3 py-3 text-uppercase">Total Geral Anual de Despesas</td>
                    <td colspan="12"></td>
                    <td class="text-end pe-3 py-3 fs-5">R$ <?= number_format($totalGeralAno, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

    <div class="alert <?= ($totalGeralReceitas - $totalGeralAno >= 0) ? 'alert-success border-success' : 'alert-danger border-danger' ?> shadow-sm d-flex justify-content-between align-items-center p-4">
        <div class="fs-5">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>SALDO ANUAL ACUMULADO (<?= date('Y') ?>):</strong>
        </div>
        <div class="fs-2 fw-bold">
            R$ <?= number_format(($totalGeralReceitas - $totalGeralAno), 2, ',', '.') ?>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success text-white fw-bold py-3">
                <i class="bi bi-pie-chart me-2"></i>RECEITAS (ANUAL)
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="chartPizzaReceitas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-danger text-white fw-bold py-3">
                <i class="bi bi-pie-chart me-2"></i>DESPESAS (ANUAL)
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="chartPizzaDespesas"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-bold py-3">
                <i class="bi bi-bank me-2"></i>SALDO POR CONTA
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="chartSaldosContas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Verificação global da biblioteca
    if (typeof Chart === 'undefined') {
        console.error("Erro: A biblioteca Chart.js não foi carregada.");
        return;
    }

    // --- 1. GRÁFICO DE LINHA (FLUXO MENSAL) ---
    const ctxFluxo = document.getElementById('chartFluxo');
    if (ctxFluxo) {
        const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
        const entradas = [<?= implode(',', array_column($fluxoAnual, 'entradas')) ?>];
        const saidas = [<?= implode(',', array_column($fluxoAnual, 'saidas')) ?>];

        new Chart(ctxFluxo.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Entradas (R$)',
                    data: entradas,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.05)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Saídas (R$)',
                    data: saidas,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.05)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { boxWidth: 10, usePointStyle: true } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.dataset.label + ': R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2})
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: (v) => 'R$ ' + v.toLocaleString('pt-BR') } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // --- 2. GRÁFICO DE PIZZA (DESPESAS POR CATEGORIA) ---
    const ctxPizza = document.getElementById('chartPizzaDespesas');
    if (ctxPizza) {
        const dadosSaida = <?= json_encode($relatorio['saida'] ?? []); ?>;
        const labelsP = [];
        const valoresP = [];

        Object.values(dadosSaida).forEach(cat => {
            const total = Object.values(cat.meses).reduce((a, b) => a + b, 0);
            if (total > 0) {
                labelsP.push(cat.nome);
                valoresP.push(total);
            }
        });

        new Chart(ctxPizza.getContext('2d'), {
            type: 'pie',
            data: {
                labels: labelsP,
                datasets: [{
                    data: valoresP,
                    backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#0d6efd', '#6610f2'],
                    borderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.label + ': R$ ' + ctx.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2})
                        }
                    }
                }
            }
        });
    }

    // --- 3. GRÁFICO DE PIZZA (SALDOS POR CONTA) ---
    const ctxSaldos = document.getElementById('chartSaldosContas');
    if (ctxSaldos) {
        const dadosContas = <?= json_encode($contas ?? []); ?>;
        const contasComSaldo = dadosContas.filter(c => parseFloat(c.financeiro_conta_financeira_saldo) > 0);

        new Chart(ctxSaldos.getContext('2d'), {
            type: 'pie',
            data: {
                labels: contasComSaldo.map(c => c.financeiro_conta_financeira_nome),
                datasets: [{
                    data: contasComSaldo.map(c => parseFloat(c.financeiro_conta_financeira_saldo)),
                    backgroundColor: ['#0d6efd', '#fd7e14', '#198754', '#0dcaf0', '#6f42c1'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.label + ': R$ ' + ctx.parsed.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                }
            }
        });
    }

    // --- 4. GRÁFICO DE PIZZA (RECEITAS POR CATEGORIA ANUAL) ---
    const ctxPizzaR = document.getElementById('chartPizzaReceitas');
    if (ctxPizzaR) {
        const dadosReceita = <?= json_encode($relatorio['entrada'] ?? []); ?>;
        const labelsR = [];
        const valoresR = [];

        Object.values(dadosReceita).forEach(cat => {
            const totalCat = Object.values(cat.meses).reduce((a, b) => a + b, 0);
            if (totalCat > 0) {
                labelsR.push(cat.nome);
                valoresR.push(totalCat);
            }
        });

        new Chart(ctxPizzaR.getContext('2d'), {
            type: 'pie',
            data: {
                labels: labelsR,
                datasets: [{
                    data: valoresR,
                    backgroundColor: ['#198754', '#20c997', '#0dcaf0', '#0d6efd', '#6f42c1'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.label + ': R$ ' + ctx.parsed.toLocaleString('pt-BR', {minimumFractionDigits: 2})
                        }
                    }
                }
            }
        });
    }

    // --- 5. NOVO GRÁFICO: BARRAS COMPARATIVO RECEITAS ---
    const ctxCompRec = document.getElementById('chartComparativoBarras');
    if (ctxCompRec) {
        const dadosCompRec = <?= json_encode($compReceitas ?? []); ?>;
        const topRec = dadosCompRec.slice(0, 8);

        new Chart(ctxCompRec.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topRec.map(d => d.subcategoria_nome),
                datasets: [
                    {
                        label: '<?= $anoAnterior ?>',
                        data: topRec.map(d => d.total_anterior),
                        backgroundColor: '#e9ecef',
                        borderRadius: 4
                    },
                    {
                        label: '<?= $anoAtual ?>',
                        data: topRec.map(d => d.total_atual),
                        backgroundColor: '#0d6efd',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: { label: (ctx) => ctx.dataset.label + ': R$ ' + ctx.parsed.x.toLocaleString('pt-BR', {minimumFractionDigits: 2}) }
                    }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { callback: (v) => 'R$ ' + v.toLocaleString('pt-BR') } }
                }
            }
        });
    }

    // --- 6. NOVO GRÁFICO: BARRAS COMPARATIVO DESPESAS ---
    const ctxCompDesp = document.getElementById('chartCompDespesasBarras');
    if (ctxCompDesp) {
        const dadosCompDesp = <?= json_encode($compDespesas ?? []); ?>;
        const topDesp = dadosCompDesp.slice(0, 8);

        new Chart(ctxCompDesp.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topDesp.map(d => d.subcategoria_nome),
                datasets: [
                    {
                        label: '<?= $anoAnterior ?>',
                        data: topDesp.map(d => d.total_anterior),
                        backgroundColor: '#e9ecef',
                        borderRadius: 4
                    },
                    {
                        label: '<?= $anoAtual ?>',
                        data: topDesp.map(d => d.total_atual),
                        backgroundColor: '#dc3545',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: { label: (ctx) => ctx.dataset.label + ': R$ ' + ctx.parsed.x.toLocaleString('pt-BR', {minimumFractionDigits: 2}) }
                    }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { callback: (v) => 'R$ ' + v.toLocaleString('pt-BR') } }
                }
            }
        });
    }
});
</script>
