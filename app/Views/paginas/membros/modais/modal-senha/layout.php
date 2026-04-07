<div class="p-4">
    <div class="text-center mb-4">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
            <span style="font-size: 25px;">🔑</span>
        </div>
        <h6 class="fw-bold mb-0"><?= $membro['membro_nome'] ?></h6>
        <p class="text-muted small">Alterar senha de acesso</p>
    </div>

    <form action="<?= url('membros/salvarNovaSenha') ?>" method="POST">
        <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

        <div class="mb-3">
            <label class="form-label small fw-bold">Defina a Nova Senha</label>
            <input type="password" name="nova_senha" class="form-control"
                   placeholder="Mínimo 6 caracteres" required minlength="6" autofocus>
        </div>

        <div class="alert alert-warning border-0 small">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            O membro passará a usar esta nova senha imediatamente.
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary fw-bold">
                CONFIRMAR E ATUALIZAR
            </button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        </div>
    </form>
</div>
