<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="<?= url('sociedades') ?>" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left"></i> Voltar</a>
            <h3 class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>Eventos: <?= $sociedade['sociedade_nome'] ?></h3>
        </div>
        <button class="btn btn-primary shadow-sm" onclick="window.novoEvento()">
            <i class="bi bi-plus-lg me-2"></i>Novo Evento
        </button>
    </div>

    <div class="row">
        <?php if (!empty($eventos)): foreach ($eventos as $ev): ?>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-info-subtle text-info"><?= $ev['sociedade_evento_status'] ?></span>
                            <button class="btn btn-sm btn-light border" onclick='window.editarEvento(<?= json_encode($ev) ?>)'>
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </div>
                        <h5 class="fw-bold"><?= $ev['sociedade_evento_titulo'] ?></h5>
                        <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i> <?= $ev['sociedade_evento_local'] ?></p>
                        <p class="text-muted small mb-3"><i class="bi bi-clock me-1"></i> <?= date('d/m/Y H:i', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Nenhum evento agendado para esta sociedade.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEvento" action="<?= url('sociedades/eventos/salvar') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="evento_id" id="ev_id">
            <input type="hidden" name="sociedade_id" value="<?= $sociedade['sociedade_id'] ?>">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEventoTitulo">Novo Evento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Título do Evento</label>
                    <input type="text" name="titulo" id="ev_titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Local</label>
                    <input type="text" name="local" id="ev_local" class="form-control">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small">Início</label>
                        <input type="datetime-local" name="data_inicio" id="ev_inicio" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label fw-bold small">Fim (Opcional)</label>
                        <input type="datetime-local" name="data_fim" id="ev_fim" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Descrição</label>
                    <textarea name="descricao" id="ev_desc" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar Evento</button>
            </div>
        </form>
    </div>
</div>

<script>
window.novoEvento = function() {
    document.getElementById('formEvento').reset();
    document.getElementById('ev_id').value = '';
    document.getElementById('modalEventoTitulo').innerText = 'Novo Evento';
    new bootstrap.Modal(document.getElementById('modalEvento')).show();
};

window.editarEvento = function(dados) {
    document.getElementById('ev_id').value = dados.sociedade_evento_id;
    document.getElementById('ev_titulo').value = dados.sociedade_evento_titulo;
    document.getElementById('ev_local').value = dados.sociedade_evento_local;
    document.getElementById('ev_inicio').value = dados.sociedade_evento_data_hora_inicio.replace(" ", "T");
    document.getElementById('ev_fim').value = dados.sociedade_evento_data_hora_fim ? dados.sociedade_evento_data_hora_fim.replace(" ", "T") : '';
    document.getElementById('ev_desc').value = dados.sociedade_evento_descricao;
    document.getElementById('modalEventoTitulo').innerText = 'Editar Evento';
    new bootstrap.Modal(document.getElementById('modalEvento')).show();
};
</script>
