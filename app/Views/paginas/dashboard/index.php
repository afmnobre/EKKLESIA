<div class="container-fluid pt-3">

	<div class="row mb-3">
		<div class="col-12 text-center p-3 rounded bg-white shadow-sm border-bottom">
			<img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB Completo" class="img-fluid" style="max-height: 85px;">
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4 overflow-hidden">
		<div class="card-body p-0">
			<div class="row g-0 align-items-center">

				<div class="col-md-4 col-12 text-center bg-light p-3 border-end d-flex align-items-center justify-content-center" style="min-height: 200px;">
					<img src="<?= url('assets/img/Igreja.jpg') ?>"
						 class="img-fluid rounded shadow-sm"
						 alt="Projeto da Igreja"
						 style="object-fit: contain; max-height: 180px; width: auto; display: block;">
				</div>

				<div class="col-md col-12 p-4">
					<span class="badge bg-primary mb-2 shadow-sm">Dashboard Administrativo EKKLESIA</span>
					<h2 class="card-title fw-bold text-dark mb-1"><?= $igreja['igreja_nome'] ?></h2>
					<p class="text-muted mb-0 small">
						<i class="fas fa-map-marker-alt me-2 text-danger"></i><?= $igreja['igreja_endereco'] ?>
					</p>
				</div>

				<div class="col-md-auto col-12 text-center p-4 border-start bg-light-subtle">
					<div class="px-3">
						<small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Total de Membros</small>
						<h1 class="fw-bold text-primary mb-0" style="font-size: 2.5rem; line-height: 1;"><?= $totalMembros ?></h1>
						<span class="badge bg-success-subtle text-success rounded-pill mt-1" style="font-size: 0.7rem;">ATIVOS</span>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="row mb-5">
		<div class="col-12">
			<h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
				<i class="fas fa-users me-2 text-secondary"></i>Sociedades Internas (Membros / Potencial)
			</h5>
		</div>
		<?php
		$coresSoc = [
			'UCP' => 'bg-info text-white',
			'UPA' => 'bg-primary text-white',
			'UMP' => 'bg-warning text-dark',
			'SAF' => 'bg-danger text-white',
			'UPH' => 'bg-success text-white'
		];
		?>
		<?php foreach($sociedades as $nome => $dados): ?>
		<div class="col-md-3 col-12 mb-3"> <div class="card shadow-sm border-0 <?= $coresSoc[$nome] ?> h-100 shadow-hover overflow-hidden">
				<div class="card-body p-0">
					<div class="row g-0 align-items-center">
						<div class="col-4 bg-white d-flex align-items-center justify-content-center p-2" style="min-height: 80px;">
							<?php if(!empty($dados['logo'])): ?>
								<img src="<?= url('assets/uploads/' . $dados['logo']) ?>"
									 alt="Logo <?= $nome ?>"
									 class="img-fluid"
									 style="max-height: 60px; object-fit: contain;">
							<?php else: ?>
								<i class="fas fa-shield-alt fa-2x text-muted opacity-25"></i>
							<?php endif; ?>
						</div>

						<div class="col-8 text-center p-2">
							<small class="fw-bold d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
								<?= $nome ?>
							</small>
							<h4 class="mb-0 fw-bold">
								<?= $dados['real'] ?>
								<small class="opacity-75" style="font-size: 0.6em;">/ <?= $dados['potencial'] ?></small>
							</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Escola Bíblica: Alunos por Classe (Matriculados / Potencial)
            </h5>
        </div>
        <?php foreach($ebd as $c): ?>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 border-left-primary h-100 bg-white">
                <div class="card-body p-3 text-center">
                    <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem;"><?= $c['classe_nome'] ?></small>
                    <h4 class="fw-bold mb-0">
                        <?= $c['matriculados'] ?> <span class="text-muted small" style="font-size: 0.6em;">/ <?= $c['potencial'] ?></span>
                    </h4>

                    <?php
                        // Cálculo da barra de progresso
                        $porcentagem = ($c['potencial'] > 0) ? ($c['matriculados'] / $c['potencial']) * 100 : 0;
                        // Cor da barra baseada no aproveitamento
                        $corBarra = ($porcentagem >= 80) ? 'bg-success' : (($porcentagem >= 50) ? 'bg-primary' : 'bg-warning');
                    ?>

                    <div class="progress mt-2" style="height: 6px;" title="<?= round($porcentagem) ?>% da capacidade">
                        <div class="progress-bar <?= $corBarra ?>" style="width: <?= $porcentagem ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

	<div class="row mb-5">
		<div class="col-12">
			<h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
				<i class="fas fa-user-clock me-2 text-danger"></i>Atenção Pastoral: Membros Ausentes (> 3 meses)
				<small class="text-muted fw-normal d-block" style="font-size: 0.7rem;">Membros sem presença registrada na EBD ou Sociedades nos últimos 90 dias.</small>
			</h5>
		</div>

		<div class="col-12">
			<div class="card border-0 shadow-sm">
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead class="bg-light">
							<tr>
								<th class="ps-4" style="width: 40%;">Membro</th>
								<th class="text-center">Idade</th>
								<th class="text-center">Contato</th>
								<th class="text-end pe-4">Ação</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($membrosAusentes)): ?>
								<?php foreach($membrosAusentes as $m): ?>
								<tr>
									<td class="ps-4">
										<div class="d-flex align-items-center">
											<div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
												<?= strtoupper(substr($m['membro_nome'], 0, 1)) ?>
											</div>
											<div>
												<span class="fw-bold text-dark d-block"><?= $m['membro_nome'] ?></span>
												<small class="text-muted"><?= $m['membro_email'] ?? 'Sem e-mail' ?></small>
											</div>
										</div>
									</td>
									<td class="text-center text-muted"><?= $m['idade'] ?> anos</td>
									<td class="text-center">
										<?php if($m['membro_telefone']): ?>
											<a href="https://wa.me/55<?= preg_replace('/\D/', '', $m['membro_telefone']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
												<i class="fab fa-whatsapp me-1"></i> Enviar Mensagem
											</a>
										<?php else: ?>
											<span class="badge bg-light text-muted">Sem telefone</span>
										<?php endif; ?>
									</td>
									<td class="text-end pe-4">
										<a href="<?= url('membros/perfil/'.$m['membro_id']) ?>" class="btn btn-sm btn-light border shadow-sm">
											<i class="fas fa-search me-1"></i> Prontuário
										</a>
									</td>
								</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="4" class="text-center py-4 text-muted">
										<i class="fas fa-check-circle text-success mb-2 d-block fa-2x"></i>
										Todos os membros ativos participaram de alguma atividade recentemente.
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

</div>
<style>
    /* Estilos extras para deixar o visual mais "limpo" */
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .shadow-hover:hover { transform: translateY(-3px); transition: 0.3s; }
</style>
