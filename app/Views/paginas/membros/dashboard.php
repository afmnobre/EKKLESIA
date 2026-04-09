<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">📊 Dashboard de Membros</h3>
        <span class="badge bg-white text-muted border p-2 shadow-sm">
            <i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y') ?>
        </span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-primary border-5">
                <small class="text-uppercase fw-bold text-muted small">Ativos</small>
                <h3 class="mb-0"><?= $ativos ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-success border-5">
                <small class="text-uppercase fw-bold text-muted small">Novos (Mês)</small>
                <h3 class="mb-0">+<?= $novos_mes ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-warning border-5">
                <small class="text-uppercase fw-bold text-muted small">Sem Cargo</small>
                <h3 class="mb-0"><?= $sem_cargo ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-info border-5">
                <small class="text-uppercase fw-bold text-muted small">Aniversariantes</small>
                <h3 class="mb-0"><?= count($aniversariantes) ?></h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-bold text-muted mb-3 text-center">Distribuição por Gênero</h6>
                <canvas id="chartGenero" style="max-height: 250px;"></canvas>
            </div>
        </div>

		<div class="col-md-4">
			<div class="card border-0 shadow-sm p-3 h-100">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h6 class="fw-bold text-muted mb-0">Estado Civil</h6>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="filtroMaioresIdade">
						<label class="form-check-label small text-muted" for="filtroMaioresIdade">+18</label>
					</div>
				</div>
				<canvas id="chartEstadoCivil" style="max-height: 250px;"></canvas>
			</div>
		</div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-muted mb-0">Faixa Etária</h6>
                    <button class="btn btn-sm btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#legendaEtaria">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
                <canvas id="chartEtaria" style="max-height: 200px;"></canvas>
                <div class="collapse mt-2" id="legendaEtaria">
                    <div class="p-2 bg-light rounded" style="font-size: 0.7rem;">
                        <strong>C:</strong> 0-12 | <strong>J:</strong> 13-18 | <strong>A:</strong> 19-59 | <strong>I:</strong> 60+
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-3">
                <h6 class="fw-bold text-muted mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Membros por Bairro (Top 10)</h6>
                <canvas id="chartBairros" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

	<div class="row g-4">
		<div class="col-md-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white py-3 border-0">
					<h6 class="mb-0 fw-bold text-primary"><i class="bi bi-cake2 me-2"></i>Aniversariantes do Mês</h6>
				</div>
				<div class="table-responsive" style="max-height: 300px;">
					<table class="table table-hover align-middle mb-0">
						<thead class="bg-light small">
							<tr>
								<th>Nome</th>
								<th class="text-center">Idade</th>
								<th class="text-center">Dia</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($aniversariantes as $aniv): ?>
								<tr>
									<td class="small fw-bold"><?= $aniv['membro_nome'] ?></td>
									<td class="text-center small"><?= $aniv['idade'] ?> anos</td>
									<td class="text-center"><span class="badge bg-light text-primary border"><?= $aniv['dia'] ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white py-3 border-0">
					<h6 class="mb-0 fw-bold text-info"><i class="bi bi-droplet-fill me-2"></i>Aniversário de Batismo</h6>
				</div>
				<div class="table-responsive" style="max-height: 300px;">
					<table class="table table-hover align-middle mb-0">
						<thead class="bg-light small">
							<tr>
								<th>Nome</th>
								<th class="text-center">Tempo</th>
								<th class="text-center">Dia</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($aniv_batismo as $bat): ?>
								<tr>
									<td class="small fw-bold"><?= $bat['membro_nome'] ?></td>
									<td class="text-center small"><?= $bat['anos'] ?> anos</td>
									<td class="text-center"><span class="badge bg-info"><?= $bat['dia'] ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card border-0 shadow-sm h-100">
				<div class="card-header bg-white py-3 border-0">
					<h6 class="mb-0 fw-bold text-danger"><i class="bi bi-heart-fill me-2"></i>Bodas / Casamento</h6>
				</div>
				<div class="table-responsive" style="max-height: 300px;">
					<table class="table table-hover align-middle mb-0">
						<thead class="bg-light small">
							<tr>
								<th>Nome</th>
								<th class="text-center">Anos</th>
								<th class="text-center">Dia</th>
							</tr>
						</thead>
						<tbody>
							<?php if(empty($aniv_casamento)): ?>
								<tr><td colspan="3" class="text-center text-muted small py-3">Nenhum este mês</td></tr>
							<?php endif; ?>
							<?php foreach($aniv_casamento as $cas): ?>
								<tr>
									<td class="small fw-bold"><?= $cas['membro_nome'] ?></td>
									<td class="text-center small"><?= $cas['anos'] ?> anos</td>
									<td class="text-center"><span class="badge bg-danger"><?= $cas['dia'] ?></span></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 1. Gráfico de Gênero
const ctxGenero = document.getElementById('chartGenero');
if (ctxGenero) {
    new Chart(ctxGenero, {
        type: 'doughnut',
        data: {
            labels: [<?= "'" . implode("','", array_column($generos, 'genero')) . "'" ?>],
            datasets: [{
                data: [<?= implode(",", array_column($generos, 'total')) ?>],
                backgroundColor: ['#0d6efd', '#fd3550', '#6c757d']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '65%',
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

// 2. Gráfico de Estado Civil com Filtro Dinâmico (+18)
const dadosGeral = <?= json_encode($estado_civil_geral ?? ['labels' => [], 'valores' => []]) ?>;
const dadosMaiores = <?= json_encode($estado_civil_maiores ?? ['labels' => [], 'valores' => []]) ?>;
const ctxEstadoCivil = document.getElementById('chartEstadoCivil');

if (ctxEstadoCivil) {
    const chartEstadoCivil = new Chart(ctxEstadoCivil, {
        type: 'doughnut',
        data: {
            labels: dadosGeral.labels,
            datasets: [{
                data: dadosGeral.valores,
                backgroundColor: ['#0d6efd', '#198754', '#6f42c1', '#ffc107', '#dc3545', '#adb5bd']
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '65%',
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Lógica do Filtro (Checkbox +18)
    const filtroMaiores = document.getElementById('filtroMaioresIdade');
    if (filtroMaiores) {
        filtroMaiores.addEventListener('change', function() {
            const novosDados = this.checked ? dadosMaiores : dadosGeral;
            chartEstadoCivil.data.labels = novosDados.labels;
            chartEstadoCivil.data.datasets[0].data = novosDados.valores;
            chartEstadoCivil.update();
        });
    }
}

// 3. Faixa Etária (Demografia)
const ctxEtaria = document.getElementById('chartEtaria');
if (ctxEtaria) {
    new Chart(ctxEtaria, {
        type: 'bar',
        data: {
            labels: ['Crianças', 'Jovens', 'Adultos', 'Idosos'],
            datasets: [{
                label: 'Membros',
                // Usando as chaves retornadas pelo seu Model (getDashboardStats)
                data: [
                    <?= $faixa_etaria['criancas'] ?? 0 ?>,
                    <?= $faixa_etaria['jovens'] ?? 0 ?>,
                    <?= $faixa_etaria['adultos'] ?? 0 ?>,
                    <?= $faixa_etaria['idosos'] ?? 0 ?>
                ],
                backgroundColor: '#198754',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 }
                }
            },
            plugins: { legend: { display: false } }
        }
    });
}

// 4. Gráfico de Bairros (Ranking)
const dadosBairros = <?= json_encode($bairros ?? []) ?>;
const ctxBairros = document.getElementById('chartBairros');

if (ctxBairros && dadosBairros.length > 0) {
    new Chart(ctxBairros, {
        type: 'bar',
        data: {
            labels: dadosBairros.map(item => item.bairro),
            datasets: [{
                label: 'Membros',
                data: dadosBairros.map(item => item.total),
                backgroundColor: '#6610f2',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } },
                y: { grid: { display: false } }
            }
        }
    });
}
</script>
