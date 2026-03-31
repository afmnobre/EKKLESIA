<?php
$this->rawview('sociedade_portal/header', [
    'titulo' => 'Dashboard Operacional',
    'ativo'  => 'dashboard',
    'sociedade' => $sociedade
]);
?>

<div class="container pb-5">
	<div class="row g-3 mb-4">
		<div class="col-6 col-md-3">
			<div class="card border-0 shadow-sm p-3 text-center border-start border-primary border-4 h-100">
				<small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem;">Sócios Ativos</small>
				<h3 class="fw-bold mb-0 text-dark"><?= count($membros ?? []) ?></h3>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card border-0 shadow-sm p-3 text-center border-start border-success border-4 h-100">
				<small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem;">Eventos Realizados</small>
				<h3 class="fw-bold mb-0 text-dark"><?= count($eventos ?? []) ?></h3>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card border-0 shadow-sm p-3 text-center border-start border-warning border-4 h-100">
				<small class="text-muted d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem;">Novas Sugestões</small>
				<h3 class="fw-bold mb-0 text-dark"><?= count($sugestoes ?? []) ?></h3>
			</div>
		</div>

		<div class="col-6 col-md-3">
			<div class="card border-0 shadow-sm p-3 text-center border-start border-info border-4 h-100">
				<div class="d-flex justify-content-between align-items-start mb-1">
					<small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Aproveitamento</small>
					<i class="bi bi-person-check text-info"></i>
				</div>

				<div class="d-flex align-items-baseline justify-content-center">
					<h3 class="fw-bold mb-0 text-dark"><?= $aproveitamento ?? 0 ?>%</h3>
				</div>

				<div class="mt-2">
					<div class="progress" style="height: 6px; border-radius: 10px;">
						<div class="progress-bar bg-info progress-bar-striped progress-bar-animated"
							 role="progressbar"
							 style="width: <?= $aproveitamento ?? 0 ?>%"
							 aria-valuenow="<?= $aproveitamento ?? 0 ?>"
							 aria-valuemin="0"
							 aria-valuemax="100">
						</div>
					</div>
					<div class="d-flex justify-content-between mt-1">
						<small class="text-muted" style="font-size: 0.6rem;">Atuais: <strong><?= $membros_ativos ?? 0 ?></strong></small>
						<small class="text-muted" style="font-size: 0.6rem;">Total: <strong><?= $membros_possiveis ?? 0 ?></strong></small>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="row g-4">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0 text-dark small text-uppercase">
                        <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Frequência nos Últimos Eventos
                    </h6>
                </div>
                <div style="height: 300px;">
                    <canvas id="chartPresenca"></canvas>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h6 class="fw-bold mb-4 text-dark small text-uppercase">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>Faixa Etária
                        </h6>
                        <div style="height: 250px;">
                            <canvas id="chartIdades"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 h-100">
                        <h6 class="fw-bold mb-4 text-dark small text-uppercase">
                            <i class="bi bi-heart me-2 text-danger"></i>Estado Civil
                        </h6>
                        <div style="height: 250px;">
                            <canvas id="chartCivil"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">
                        <i class="bi bi-people me-2 text-primary"></i>SÓCIOS RECENTES
                    </h5>
                    <a href="<?= url('sociedadeLider/index') ?>" class="btn btn-sm btn-light text-primary fw-bold px-3">Ver Todos</a>
                </div>

                <div class="list-group list-group-flush">
                    <?php if(empty($membros)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-person-slash fs-1 text-muted opacity-25"></i>
                            <p class="text-muted mt-2 small">Nenhum sócio cadastrado.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach(array_slice($membros, 0, 5) as $m):
                            $fotoNome = $m['membro_foto_arquivo'] ?? null;
                            $temFoto = (!empty($fotoNome) && $fotoNome !== "null");
                            $urlFoto = $temFoto ? url("assets/uploads/{$sociedade['membro_igreja_id']}/membros/{$m['membro_registro_interno']}/{$fotoNome}") : null;
                        ?>
                            <div class="list-group-item border-0 d-flex align-items-center px-0 bg-transparent mb-1">
                                <?php if($temFoto): ?>
                                    <img src="<?= $urlFoto ?>" class="rounded-circle me-3 border shadow-sm" width="40" height="40" style="object-fit: cover;"
                                         onerror="this.parentElement.innerHTML='<div class=\'rounded-circle me-3 border shadow-sm bg-light d-flex align-items-center justify-content-center\' style=\'width:40px; height:40px;\'><i class=\'bi bi-person-fill text-secondary\'></i></div>';">
                                <?php else: ?>
                                    <div class="rounded-circle me-3 border shadow-sm bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                        <i class="bi bi-person-fill text-secondary"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-bold small text-dark text-truncate"><?= $m['membro_nome'] ?></h6>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;"><?= $m['sociedade_membro_funcao'] ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 mb-4 bg-dark text-white">
                <h6 class="fw-bold text-center mb-3 small text-uppercase">Distribuição de Gênero</h6>
                <div style="height: 200px;">
                    <canvas id="chartGenero"></canvas>
                </div>
            </div>

            <div class="card border-0 shadow-sm p-3 mb-4">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted border-bottom pb-2">Sugestões de Novos Sócios</h6>
                <?php if(empty($sugestoes)): ?>
                    <p class="small text-muted text-center py-2">Sem novas sugestões no momento.</p>
                <?php else: ?>
                    <?php foreach(array_slice($sugestoes, 0, 5) as $s): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded p-2 me-2">
                                <i class="bi bi-person-plus text-primary"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="mb-0 small fw-bold text-dark text-truncate"><?= $s['membro_nome'] ?></h6>
                                <small class="text-muted" style="font-size: 0.7rem;"><?= $s['idade'] ?> anos</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="card border-0 bg-primary text-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle fs-4 me-3"></i>
                    <div>
                        <small class="d-block opacity-75" style="font-size: 0.7rem;">Dica do Sistema</small>
                        <p class="mb-0 small fw-bold">Mantenha a lista de presença atualizada para gerar métricas precisas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // 1. Gráfico de Gênero
    new Chart(document.getElementById('chartGenero'), {
        type: 'doughnut',
        data: {
            labels: [<?php foreach($metricas['generos'] as $g) echo "'".$g['membro_genero']."',"; ?>],
            datasets: [{
                data: [<?php foreach($metricas['generos'] as $g) echo $g['total'].","; ?>],
                backgroundColor: ['#0d6efd', '#f8d7da', '#d1e7dd'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#fff', font: { size: 10 } } }
            }
        }
    });

    // 2. Gráfico de Presença
    new Chart(document.getElementById('chartPresenca'), {
        type: 'line',
        data: {
            labels: [<?php foreach($metricas['presencas'] as $p) echo "'".mb_strimwidth($p['titulo'], 0, 12, "...")."',"; ?>],
            datasets: [{
                label: 'Presentes',
                data: [<?php foreach($metricas['presencas'] as $p) echo $p['total_presente'].","; ?>],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: '#0d6efd'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { display: false } }
        }
    });

    // 3. Gráfico de Faixa Etária
    new Chart(document.getElementById('chartIdades'), {
        type: 'bar',
        data: {
            labels: [<?php foreach($metricas['idades'] as $i) echo "'".$i['faixa']."',"; ?>],
            datasets: [{
                label: 'Sócios',
                data: [<?php foreach($metricas['idades'] as $i) echo $i['total'].","; ?>],
                backgroundColor: '#0d6efd',
                borderRadius: 5
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 4. Gráfico de Estado Civil
    new Chart(document.getElementById('chartCivil'), {
        type: 'pie',
        data: {
            labels: [<?php foreach($metricas['civil'] as $c) echo "'".$c['membro_estado_civil']."',"; ?>],
            datasets: [{
                data: [<?php foreach($metricas['civil'] as $c) echo $c['total'].","; ?>],
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
