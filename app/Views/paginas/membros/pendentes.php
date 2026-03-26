<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary"><i class="bi bi-person-check me-2"></i>Aprovações Pendentes</h4>
        <a href="<?= url('membros') ?>" class="btn btn-secondary btn-sm">Voltar</a>
    </div>

    <?php if (empty($pendentes)): ?>
        <div class="alert alert-info border-0 shadow-sm text-center py-5">
            <i class="bi bi-emoji-smile fs-1 d-block mb-2"></i>
            Nenhum cadastro aguardando aprovação no momento.
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($pendentes as $m): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <?php if ($m['membro_foto_arquivo']): ?>
                                    <img src="<?= url("public/assets/uploads/{$m['membro_igreja_id']}/membros/PENDENTE_{$m['membro_id']}/{$m['membro_foto_arquivo']}") ?>"
                                         class="rounded-circle shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                                        <i class="bi bi-person fs-2 text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0"><?= $m['membro_nome'] ?></h6>
                                <small class="text-muted">Cadastro em: <?= date('d/m/Y H:i', strtotime($m['membro_data_criacao'])) ?></small>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush small mb-3">
                            <li class="list-group-item px-0"><strong>Nascimento:</strong> <?= date('d/m/Y', strtotime($m['membro_data_nascimento'])) ?></li>
                            <li class="list-group-item px-0"><strong>WhatsApp:</strong> <?= $m['membro_telefone'] ?></li>
                            <li class="list-group-item px-0 text-truncate"><strong>E-mail:</strong> <?= $m['membro_email'] ?></li>
                        </ul>

                        <div class="d-grid gap-2">
                            <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAprovar<?= $m['membro_id'] ?>">
                                <i class="bi bi-check-lg me-1"></i> ANALISAR / APROVAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>

			<div class="modal fade" id="modalAprovar<?= $m['membro_id'] ?>" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog">
					<form action="<?= url('membros/aprovar') ?>" method="POST" class="modal-content border-0 shadow">
						<div class="modal-header bg-dark text-white">
							<h5 class="modal-title">Finalizar Cadastro</h5>
							<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
						</div>
						<div class="modal-body">
							<input type="hidden" name="membro_id" value="<?= $m['membro_id'] ?>">

							<div class="alert alert-light border small text-muted">
								<i class="bi bi-info-circle me-1"></i> O sistema sugeriu um número de registro com base na data atual e ID da igreja.
							</div>

							<div class="mb-3">
								<label class="form-label fw-bold small text-primary">Nº Registro Interno (ROL)</label>
								<input type="text" name="membro_registro_interno"
									   class="form-control form-control-lg fw-bold text-center"
									   style="letter-spacing: 2px;"
									   value="<?= $sugestao ?>" required>
							</div>

							<div class="row g-2">
								<div class="col-6">
									<button type="submit" name="status" value="Rejeitado" class="btn btn-outline-danger w-100 py-2">
										<i class="bi bi-x-circle me-1"></i> Rejeitar
									</button>
								</div>
								<div class="col-6">
									<button type="submit" name="status" value="Ativo" class="btn btn-success w-100 py-2 fw-bold">
										<i class="bi bi-check-circle me-1"></i> APROVAR
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
        <?php endforeach; ?>
    </div>
</div>
