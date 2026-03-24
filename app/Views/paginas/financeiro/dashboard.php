<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Dashboard Financeiro</h3>
        <div class="badge bg-white text-dark shadow-sm border p-2">
            <i class="bi bi-calendar3 me-2 text-primary"></i><?= date('F / Y') ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted">Receitas (Mês)</small>
                    <h3 class="fw-bold mb-0">R$ <?= number_format($resumo['receitas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted">Despesas (Mês)</small>
                    <h3 class="fw-bold mb-0 text-danger">R$ <?= number_format($resumo['despesas'], 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start <?= $resumo['saldo_mes'] >= 0 ? 'border-success' : 'border-warning' ?> border-4">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted">Balanço Mensal</small>
                    <h3 class="fw-bold mb-0 <?= $resumo['saldo_mes'] >= 0 ? 'text-success' : 'text-warning' ?>">
                        R$ <?= number_format($resumo['saldo_mes'], 2, ',', '.') ?>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-dark border-4 bg-light">
                <div class="card-body">
                    <small class="text-uppercase fw-bold text-muted">Total em Caixa</small>
                    <h3 class="fw-bold mb-0">R$ <?= number_format(array_sum(array_column($contas, 'financeiro_conta_financeira_saldo')), 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Fluxo de Caixa (12 meses)</span>
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
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h6 class="mb-0 fw-bold"><?= $c['financeiro_conta_financeira_nome'] ?></h6>
                                <small class="text-muted text-uppercase" style="font-size: 0.65rem;"><?= $c['financeiro_conta_financeira_tipo'] ?></small>
                            </div>
                            <span class="badge bg-light text-dark fs-6 border fw-bold">R$ <?= number_format($c['financeiro_conta_financeira_saldo'], 2, ',', '.') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$mesesNomes = ['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];
$totalGeralAno = 0;
?>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-dark">
            <tr>
                <th>DESPESAS</th>
                <?php foreach($mesesNomes as $m) echo "<th>$m</th>"; ?>
                <th>TOTAL GERAL</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($relatorio['saida'] as $catId => $cat):
                $totalCatLinha = array_sum($cat['meses']);
                $totalGeralAno += $totalCatLinha;
            ?>
                <tr class="table-secondary fw-bold">
                    <td><?= $cat['nome'] ?></td>
                    <?php foreach($cat['meses'] as $valor): ?>
                        <td><?= number_format($valor, 2, ',', '.') ?></td>
                    <?php endforeach; ?>
                    <td><?= number_format($totalCatLinha, 2, ',', '.') ?></td>
                </tr>

                <?php foreach($cat['subcategorias'] as $sub): ?>
                    <tr>
                        <td class="ps-4 italic small"><?= $sub['nome'] ?></td>
                        <?php foreach($sub['meses'] as $valor): ?>
                            <td class="small text-muted"><?= number_format($valor, 2, ',', '.') ?></td>
                        <?php endforeach; ?>
                        <td class="fw-bold"><?= number_format(array_sum($sub['meses']), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="13">TOTAL GERAL ANUAL DE DESPESAS</td>
                <td>R$ <?= number_format($totalGeralAno, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php
$totalGeralReceitas = 0;
?>

<div class="table-responsive mt-5">
    <table class="table table-sm table-bordered">
        <thead class="table-success text-dark">
            <tr>
                <th>RECEITAS</th>
                <?php foreach($mesesNomes as $m) echo "<th class='text-center'>$m</th>"; ?>
                <th>TOTAL GERAL</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($relatorio['entrada'])): ?>
                <?php foreach($relatorio['entrada'] as $catId => $cat):
                    $totalCatLinha = array_sum($cat['meses']);
                    $totalGeralReceitas += $totalCatLinha;
                ?>
                    <tr class="table-light fw-bold">
                        <td><?= $cat['nome'] ?></td>
                        <?php foreach($cat['meses'] as $valor): ?>
                            <td class="text-center"><?= number_format($valor, 2, ',', '.') ?></td>
                        <?php endforeach; ?>
                        <td class="table-success"><?= number_format($totalCatLinha, 2, ',', '.') ?></td>
                    </tr>

                    <?php foreach($cat['subcategorias'] as $sub): ?>
                        <tr>
                            <td class="ps-4 text-muted" style="font-size: 0.85rem;">
                                <i><?= $sub['nome'] ?></i>
                            </td>
                            <?php foreach($sub['meses'] as $valor): ?>
                                <td class="text-center text-muted small"><?= number_format($valor, 2, ',', '.') ?></td>
                            <?php endforeach; ?>
                            <td class="text-muted small"><?= number_format(array_sum($sub['meses']), 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot class="table-dark fw-bold">
            <tr>
                <td colspan="13">TOTAL GERAL ANUAL DE RECEITAS</td>
                <td class="text-success">R$ <?= number_format($totalGeralReceitas, 2, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="alert <?= ($totalGeralReceitas - $totalGeralAno >= 0) ? 'alert-success' : 'alert-danger' ?> mt-3">
    <strong>SALDO ANUAL ACUMULADO: </strong>
    R$ <?= number_format(($totalGeralReceitas - $totalGeralAno), 2, ',', '.') ?>
</div>


<script>
// Como o Chart.js já foi carregado no footer, iniciamos direto no DOMContentLoaded
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('chartFluxo').getContext('2d');

    const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    // PHP gera os dados para os 12 meses
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
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }, {
                label: 'Saídas (R$)',
                data: saidas,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (value) => 'R$ ' + value.toLocaleString('pt-BR') }
                }
            }
        }
    });
});
</script>
