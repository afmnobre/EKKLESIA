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
            <div class="col-12 mb-4"> <div class="card border-0 shadow-sm overflow-hidden rounded-3">
                    <div class="card-body p-0">
                        <div class="row g-0 align-items-center">

                            <div class="col-12 col-sm-3 col-md-2 bg-light d-flex align-items-center justify-content-center p-2 border-end">
                                <div class="w-100 shadow-sm rounded-2 overflow-hidden"
                                     style="aspect-ratio: 3 / 4; max-width: 140px; border: 2px solid #ddd;">
                                    <?php if ($m['membro_foto_arquivo']): ?>
                                        <img src="<?= url("public/assets/uploads/{$m['membro_igreja_id']}/membros/PENDENTE_{$m['membro_id']}/{$m['membro_foto_arquivo']}") ?>"
                                             class="w-100 h-100" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-flex flex-column h-100 align-items-center justify-content-center text-muted bg-white">
                                            <i class="bi bi-person-bounding-box fs-1 mb-1"></i>
                                            <span style="font-size: 0.65rem;">Sem Foto</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-7 p-3">
                                <h5 class="fw-bold mb-1 text-dark text-uppercase"><?= $m['membro_nome'] ?></h5>
                                <div class="mb-3">
                                    <span class="badge bg-light text-muted border">
                                        <i class="bi bi-calendar-check me-1"></i> Cadastrado: <?= date('d/m/Y', strtotime($m['membro_data_criacao'])) ?>
                                    </span>
                                </div>

                                <div class="row small mt-2 g-2">
                                    <div class="col-md-6 border-end">
                                        <p class="mb-1"><strong><i class="bi bi-balloon me-1 text-primary"></i>Nasc:</strong> <?= date('d/m/Y', strtotime($m['membro_data_nascimento'])) ?></p>
                                        <p class="mb-1"><strong><i class="bi bi-whatsapp me-1 text-success"></i>WhatsApp:</strong> <?= $m['membro_telefone'] ?></p>
                                    </div>
                                    <div class="col-md-6 p-l-md-3">
                                        <p class="mb-1 text-truncate"><strong><i class="bi bi-envelope me-1 text-danger"></i>E-mail:</strong> <?= $m['membro_email'] ?></p>
                                        <p class="mb-1 text-info"><strong><i class="bi bi-heart-fill me-1"></i>Status:</strong> <?= $m['membro_status'] ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3 col-md-3 p-3 text-center d-grid d-sm-block">
                                <button class="btn btn-ebd btn-lg fw-bold px-4 py-3 shadow"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAprovar<?= $m['membro_id'] ?>"
                                        style="background-color: #003366; color: white; border-radius: 12px; border: none; font-weight: bold; width: 100%;">
                                    <i class="bi bi-shield-lock-fill fs-5 d-block mb-1"></i>
                                    ANALISAR E APROVAR
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalAprovar<?= $m['membro_id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="<?= url('membros/aprovar') ?>" method="POST" class="modal-content border-0 shadow">
                        <div class="modal-header bg-dark text-white p-3">
                            <h5 class="modal-title fw-bold">Aprovação de Cadastro</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <input type="hidden" name="membro_id" value="<?= $m['membro_id'] ?>">

                            <div class="alert alert-light border small text-muted">
                                <i class="bi bi-info-circle me-1"></i> O sistema sugeriu um número de registro com base na data atual e ID da igreja.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-primary">Nº Registro Interno (ROL)</label>
                                <input type="text" name="membro_registro_interno"
                                       class="form-control form-control-lg fw-bold text-center border-primary shadow"
                                       style="letter-spacing: 2px; font-size: 1.5rem;"
                                       value="<?= $sugestao ?>" required>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="submit" name="status" value="Rejeitado" class="btn btn-outline-danger w-100 py-3 fw-bold">
                                        <i class="bi bi-trash3 me-1"></i> REJEITAR
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" name="status" value="Ativo" class="btn btn-success w-100 py-3 fw-bold shadow">
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

<style>
    /* Estilos Adicionais */
    .card:hover {
        transform: translateY(-3px);
        transition: all 0.2s ease-in-out;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .text-uppercase { text-transform: uppercase; }
    .btn-ebd:hover {
        background-color: #002244 !important;
        transition: background 0.3s;
    }
</style>
