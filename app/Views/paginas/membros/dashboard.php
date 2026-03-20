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

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 h-100">
                <h6 class="fw-bold text-muted mb-3 text-center">Distribuição por Gênero</h6>
                <canvas id="chartGenero" style="max-height: 250px;"></canvas>
            </div>
        </div>

		<div class="col-md-8">
			<div class="card border-0 shadow-sm p-3 h-100">
				<div class="d-flex justify-content-between align-items-center mb-3">
					<h6 class="fw-bold text-muted mb-0">Demografia (Faixa Etária)</h6>
					<button class="btn btn-sm btn-link text-decoration-none p-0" type="button" data-bs-toggle="collapse" data-bs-target="#legendaEtaria">
						<i class="bi bi-info-circle"></i> Legenda
					</button>
				</div>

				<canvas id="chartEtaria" style="max-height: 250px;"></canvas>

				<div class="collapse show mt-3" id="legendaEtaria">
					<div class="row g-2 text-center">
						<div class="col-6 col-lg-3">
							<div class="p-2 border rounded bg-light">
								<small class="d-block fw-bold text-success">Crianças</small>
								<span class="text-muted small">0 a 12 anos</span>
							</div>
						</div>
						<div class="col-6 col-lg-3">
							<div class="p-2 border rounded bg-light">
								<small class="d-block fw-bold text-success">Jovens</small>
								<span class="text-muted small">13 a 18 anos</span>
							</div>
						</div>
						<div class="col-6 col-lg-3">
							<div class="p-2 border rounded bg-light">
								<small class="d-block fw-bold text-success">Adultos</small>
								<span class="text-muted small">19 a 59 anos</span>
							</div>
						</div>
						<div class="col-6 col-lg-3">
							<div class="p-2 border rounded bg-light">
								<small class="d-block fw-bold text-success">Idosos</small>
								<span class="text-muted small">60+ anos</span>
							</div>
						</div>
					</div>
					<p class="text-center mt-2 mb-0" style="font-size: 0.75rem; color: #adb5bd;">
						* Baseado na data de nascimento registrada no perfil do membro.
					</p>
				</div>
			</div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-cake2 me-2"></i>Aniversariantes do Mês</h6>
                </div>
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small">
                            <tr><th>Nome</th><th class="text-center">Dia</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($aniversariantes as $aniv): ?>
                                <tr>
                                    <td><?= $aniv['membro_nome'] ?></td>
                                    <td class="text-center"><span class="badge bg-light text-primary border"><?= $aniv['dia'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="mb-0 fw-bold text-info"><i class="bi bi-droplet-fill me-2"></i>Aniversário de Batismo</h6>
                </div>
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small">
                            <tr><th>Nome</th><th>Anos</th><th class="text-center">Dia</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($aniv_batismo as $bat): ?>
                                <tr>
                                    <td><?= $bat['membro_nome'] ?></td>
                                    <td><?= $bat['anos'] ?> anos</td>
                                    <td class="text-center"><span class="badge bg-info"><?= $bat['dia'] ?></span></td>
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
// Gênero
new Chart(document.getElementById('chartGenero'), {
    type: 'doughnut',
    data: {
        labels: [<?= "'" . implode("','", array_column($generos, 'genero')) . "'" ?>],
        datasets: [{
            data: [<?= implode(",", array_column($generos, 'total')) ?>],
            backgroundColor: ['#0d6efd', '#fd3550', '#6c757d']
        }]
    },
    options: { plugins: { legend: { position: 'bottom' } } }
});

// Faixa Etária
new Chart(document.getElementById('chartEtaria'), {
    type: 'bar',
    data: {
        labels: ['Crianças', 'Jovens', 'Adultos', 'Idosos'],
        datasets: [{
            label: 'Membros',
            data: [<?= $faixa_etaria['criancas'] ?>, <?= $faixa_etaria['jovens'] ?>, <?= $faixa_etaria['adultos'] ?>, <?= $faixa_etaria['idosos'] ?>],
            backgroundColor: '#198754'
        }]
    },
    options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
