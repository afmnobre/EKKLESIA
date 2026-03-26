<div class="modal-body p-4">
    <form action="<?= url('membros/addHistorico') ?>" method="POST" id="formHistorico">
        <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

        <div class="mb-3 text-center">
            <div class="display-6 mb-2">📜</div>
            <h5 class="fw-bold">Novo Registro de Histórico</h5>
            <p class="text-muted small">Membro: <span class="text-dark fw-bold"><?= htmlspecialchars($membro['membro_nome']) ?></span></p>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase text-muted">Descreva o Evento</label>
            <textarea name="historico" id="editor-historico" class="form-control" rows="5"></textarea>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-dark px-5 fw-bold shadow-sm">
                <i class="bi bi-save me-2"></i>SALVAR REGISTRO
            </button>
        </div>
    </form>
</div>

