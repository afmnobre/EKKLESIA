<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-dark">Dashboard Financeiro</h3>
        <div class="badge bg-white text-dark shadow-sm border p-2 px-3">
            <i class="bi bi-calendar3 me-2 text-primary"></i>
            <span class="text-uppercase"><?= date('M / Y') ?></span>
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
    const ctx = document.getElementById('chartFluxo').getContext('2d');
    const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    const entradas = [<?= implode(',', array_column($fluxoAnual, 'entradas')) ?>];
    const saidas = [<?= implode(',', array_column($fluxoAnual, 'saidas')) ?>];

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Entradas (R$)',
                data: entradas,
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.05)',
                borderWidth: 4,
                pointRadius: 4,
                pointBackgroundColor: '#198754',
                tension: 0.4,
                fill: true
            }, {
                label: 'Saídas (R$)',
                data: saidas,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.05)',
                borderWidth: 4,
                pointRadius: 4,
                pointBackgroundColor: '#dc3545',
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
                        label: function(context) {
                            return context.dataset.label + ': R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { callback: (value) => 'R$ ' + value.toLocaleString('pt-BR') }
                },
                x: { grid: { display: false } }
            }
        }
    });
});


//GRAFICO DE BARRAS POR CONTA CORRENTE
// --- GRÁFICO DE PIZZA (DESPESAS POR CATEGORIA) ---
document.addEventListener("DOMContentLoaded", function() {
    // Verifica se a biblioteca Chart.js foi carregada corretamente
    if (typeof Chart === 'undefined') {
        console.error("Erro: A biblioteca Chart.js não foi carregada. Verifique o link do script.");
        return;
    }

    // --- 1. GRÁFICO DE PIZZA (DESPESAS POR CATEGORIA) ---
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

// --- GRÁFICO 2: PIZZA (SALDOS POR CONTA) ---
try {
    const ctxB = document.getElementById('chartSaldosContas');
    if (ctxB) {
        const dadosContas = <?= json_encode($contas ?? []); ?>;

        // Filtra apenas contas que possuem saldo maior que zero para a pizza não ficar poluída
        const contasComSaldo = dadosContas.filter(c => parseFloat(c.financeiro_conta_financeira_saldo) > 0);

        const labelsB = contasComSaldo.map(c => c.financeiro_conta_financeira_nome);
        const valoresB = contasComSaldo.map(c => parseFloat(c.financeiro_conta_financeira_saldo));

        new Chart(ctxB.getContext('2d'), {
            type: 'pie', // Alterado para Pizza
            data: {
                labels: labelsB,
                datasets: [{
                    data: valoresB,
                    backgroundColor: [
                        '#0d6efd', // Azul (BB/Principal)
                        '#fd7e14', // Laranja (Caixa)
                        '#198754', // Verde
                        '#0dcaf0', // Ciano
                        '#6f42c1'  // Roxo
                    ],
                    hoverOffset: 15,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                }
            }
        });
    }
} catch (e) { console.error("Erro na Pizza de Saldos:", e); }
});

// --- GRÁFICO 3: PIZZA (RECEITAS POR CATEGORIA ANUAL) ---
(function() {
    const renderReceitas = function() {
        const ctxPizzaR = document.getElementById('chartPizzaReceitas');
        if (!ctxPizzaR) return;

        try {
            // Pegamos os dados do relatório de entrada (Receitas)
            const dadosReceita = <?= json_encode($relatorio['entrada'] ?? []); ?>;

            const labelsPizzaR = [];
            const valoresPizzaR = [];

            // Extraindo totais anuais por categoria
            Object.values(dadosReceita).forEach(cat => {
                const totalCat = Object.values(cat.meses).reduce((a, b) => a + b, 0);
                if (totalCat > 0) {
                    labelsPizzaR.push(cat.nome);
                    valoresPizzaR.push(totalCat);
                }
            });

            new Chart(ctxPizzaR.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labelsPizzaR,
                    datasets: [{
                        data: valoresPizzaR,
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
        } catch (e) {
            console.error("Erro interno no gráfico de Receitas:", e);
        }
    };

    // Tenta rodar agora ou espera o DOM estar pronto
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        renderReceitas();
    } else {
        document.addEventListener("DOMContentLoaded", renderReceitas);
    }
})();

</script>

