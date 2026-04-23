<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Dashboard da Biblioteca</h3>
        <a href="<?= url('biblioteca') ?>" class="btn btn-outline-dark btn-sm">Voltar para Estante</a>
    </div>

	<div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted small text-uppercase fw-bold mb-0">Total de Acervo</h6>
                        <i class="bi bi-bookshelf fs-4 text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-primary"><?= $stats['total_livros'] ?? 0 ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted small text-uppercase fw-bold mb-0">Empréstimos Ativos</h6>
                        <i class="bi bi-journal-check fs-4 text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-success"><?= $stats['ativos'] ?? 0 ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted small text-uppercase fw-bold mb-0">Livros Atrasados</h6>
                        <i class="bi bi-exclamation-octagon fs-4 text-danger"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-danger"><?= $stats['atrasados'] ?? 0 ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted small text-uppercase fw-bold mb-0">Membros Leitores</h6>
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <h2 class="fw-bold mb-0 text-info"><?= $stats['membros_leitores'] ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2"></i>Categorias</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartCategorias" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-person-badge me-2"></i>Principais Autores</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartAutores" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-building me-2"></i>Top Editoras</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartEditoras" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-graph-up me-2"></i>Histórico de Empréstimos (Mensal)</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartEvolucao" height="300"></canvas>
                </div>
            </div>
        </div>

		<div class="row g-4 mb-4">
			<div class="col-md-6">
				<div class="card border-0 shadow-sm h-100">
					<div class="card-header bg-white border-0 py-3">
						<h6 class="fw-bold mb-0 text-primary">
							<i class="bi bi-calendar-check me-2"></i>Top 5 Leitores de <?= date('M/Y') ?>
						</h6>
					</div>
					<div class="card-body">
						<div class="row text-center">
							<?php if(!empty($topLeitoresMes)): foreach($topLeitoresMes as $leitor): ?>
								<div class="col">
									<?php
										$foto = (!empty($leitor['membro_foto_arquivo'])) ? url("assets/uploads/{$igrejaId}/membros/{$leitor['membro_registro_interno']}/{$leitor['membro_foto_arquivo']}") : url('assets/img/user-default.png');
									?>
									<img src="<?= $foto ?>" class="rounded-circle mb-2 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
									<p class="small fw-bold mb-0 text-truncate"><?= mb_strtoupper(explode(' ', $leitor['membro_nome'])[0]) ?></p>
									<span class="badge bg-primary-soft text-primary small" style="font-size: 10px;"><?= $leitor['total_leituras'] ?> livros</span>
								</div>
							<?php endforeach; else: ?>
								<p class="text-muted small">Nenhuma leitura este mês.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="card border-0 shadow-sm h-100">
					<div class="card-header bg-white border-0 py-3">
						<h6 class="fw-bold mb-0 text-warning">
							<i class="bi bi-trophy me-2"></i>Top 5 Melhores do Ano (<?= date('Y') ?>)
						</h6>
					</div>
					<div class="card-body">
						<div class="row text-center">
							<?php if(!empty($topLeitoresAno)): foreach($topLeitoresAno as $leitor): ?>
								<div class="col">
									<?php
										$foto = (!empty($leitor['membro_foto_arquivo'])) ? url("assets/uploads/{$igrejaId}/membros/{$leitor['membro_registro_interno']}/{$leitor['membro_foto_arquivo']}") : url('assets/img/user-default.png');
									?>
									<img src="<?= $foto ?>" class="rounded-circle mb-2 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
									<p class="small fw-bold mb-0 text-truncate"><?= mb_strtoupper(explode(' ', $leitor['membro_nome'])[0]) ?></p>
									<span class="badge bg-warning-soft text-warning small" style="font-size: 10px;"><?= $leitor['total_leituras'] ?> livros</span>
								</div>
							<?php endforeach; else: ?>
								<p class="text-muted small">Nenhuma leitura este ano.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-star me-2"></i>Livros Mais Procurados</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Título do Livro</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Qtd. Empréstimos</th>
                                    <th class="text-end pe-4">Estoque</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($maisPopulares)): foreach($maisPopulares as $lp): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= $lp['livro_titulo'] ?></td>
                                    <td><?= $lp['categoria_nome'] ?></td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $lp['total_emprestimos'] ?> saídas</span></td>
                                    <td class="text-end pe-4">
                                        <?= ($lp['livro_quantidade'] > 0) ? '<span class="badge bg-success-soft text-success">Disponível</span>' : '<span class="badge bg-danger-soft text-danger">Esgotado</span>' ?>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="4" class="text-center py-3">Nenhum dado disponível.</td></tr>
                                <?php endif; ?>
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
document.addEventListener('DOMContentLoaded', function() {

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
        }
    };

    // 1. Categorias
    new Chart(document.getElementById('chartCategorias'), {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($dadosCategorias, 'label')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($dadosCategorias, 'total')) ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
            }]
        },
        options: chartOptions
    });

    // 2. Autores (Barra Horizontal)
    new Chart(document.getElementById('chartAutores'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($dadosAutores, 'label')) ?>,
            datasets: [{
                label: 'Livros',
                data: <?= json_encode(array_column($dadosAutores, 'total')) ?>,
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            indexAxis: 'y',
            ...chartOptions,
            plugins: { legend: { display: false } }
        }
    });

    // 3. Editoras (Doughnut)
    // Criamos variáveis seguras para evitar o erro Fatal
    const labelsEditoras = <?= json_encode(array_column($editoras ?? [], 'label')) ?>;
    const dataEditoras = <?= json_encode(array_column($editoras ?? [], 'total')) ?>;

    new Chart(document.getElementById('chartEditoras'), {
        type: 'doughnut',
        data: {
            labels: labelsEditoras.length > 0 ? labelsEditoras : ['Nenhuma Editora'],
            datasets: [{
                data: dataEditoras.length > 0 ? dataEditoras : [1], // Mostra um gráfico vazio se não houver dados
                backgroundColor: ['#6610f2', '#fd7e14', '#20c997', '#0dcaf0', '#f6c23e'],
                borderWidth: 0
            }]
        },
        options: chartOptions
    });

    // 4. Evolução
    new Chart(document.getElementById('chartEvolucao'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($historico, 'mes_ano')) ?>,
            datasets: [{
                label: 'Empréstimos',
                data: <?= json_encode(array_column($historico, 'total')) ?>,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            ...chartOptions,
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>

<style>
    .bg-success-soft { background-color: #e1f6eb; color: #198754; }
    .bg-danger-soft { background-color: #fce8e8; color: #dc3545; }
    .card { border-radius: 10px; }
</style>
