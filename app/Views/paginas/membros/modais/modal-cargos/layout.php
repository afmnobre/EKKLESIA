<?php
// Garante que as variáveis existam mesmo que o Controller falhe em enviá-las
$todosCargos = $todosCargos ?? [];
$cargosSelecionados = $cargosSelecionados ?? [];
?>

<div class="modal-body p-4 bg-light">
    <form action="<?= url('membros/updateCargos') ?>" method="POST" id="formCargos">
        <input type="hidden" name="membro_id" value="<?= $membro['membro_id'] ?>">

        <div class="text-center mb-4">
            <div class="display-6 mb-2">🏷️</div>
            <h5 class="fw-bold">Atribuir Cargos e Funções</h5>
            <p class="text-muted small">Membro: <span class="text-dark fw-bold"><?= htmlspecialchars($membro['membro_nome']) ?></span></p>
        </div>

        <div class="row g-2 overflow-auto" style="max-height: 400px; padding: 5px;">
            <?php if (!empty($todosCargos)): ?>
                <?php foreach ($todosCargos as $cargo): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="form-check h-100 p-3 border rounded bg-white shadow-sm d-flex align-items-center hover-shadow transition">
                            <input class="form-check-input ms-1 mt-0"
                                   type="checkbox"
                                   name="cargos[]"
                                   value="<?= $cargo['cargo_id'] ?>"
                                   id="cargo_<?= $membro['membro_id'] ?>_<?= $cargo['cargo_id'] ?>"
                                   <?= in_array($cargo['cargo_id'], $cargosSelecionados) ? 'checked' : '' ?>>

                            <label class="form-check-label ms-3 small fw-bold text-secondary text-uppercase"
                                   for="cargo_<?= $membro['membro_id'] ?>_<?= $cargo['cargo_id'] ?>"
                                   style="cursor: pointer; font-size: 0.75rem; line-height: 1.2;">
                                <?= $cargo['cargo_nome'] ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Nenhum cargo encontrado no sistema.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="button" class="btn btn-outline-secondary px-4 fw-bold" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                <i class="bi bi-check-lg me-2"></i>SALVAR ALTERAÇÕES
            </button>
        </div>
    </form>
</div>

<style>
    .hover-shadow:hover {
        border-color: #0d6efd !important;
        background-color: #f8f9ff !important;
        transform: translateY(-2px);
    }
    .transition {
        transition: all 0.2s ease-in-out;
    }
    /* Ajuste para telas menores */
    @media (max-width: 576px) {
        .col-md-6 { width: 100%; }
    }
</style>
