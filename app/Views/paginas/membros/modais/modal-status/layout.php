<div class="modal-body p-4">
    <form action="<?= url('membros/updateStatus') ?>" method="POST">
        <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

        <div class="text-center mb-4">
            <div class="display-6 mb-2">🔄</div>
            <h5 class="fw-bold">Alterar Status</h5>
            <p class="text-muted small">Membro: <span class="text-dark fw-bold"><?= htmlspecialchars($membro['membro_nome']) ?></span></p>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted text-uppercase">Selecione o Novo Status</label>
            <select name="status" class="form-select form-select-lg border-2">
                <?php
                $statusOpcoes = ['Ativo', 'Inativo', 'Transferido', 'Falecido'];
                foreach ($statusOpcoes as $opcao):
                ?>
                    <option value="<?= $opcao ?>" <?= ($membro['membro_status'] == $opcao) ? 'selected' : '' ?>>
                        <?= $opcao ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-warning btn-lg fw-bold shadow-sm">
                ATUALIZAR STATUS
            </button>
            <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">
                Cancelar
            </button>
        </div>
    </form>
</div>
