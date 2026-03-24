<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-bank me-2 text-primary"></i>Contas e Caixas</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalConta">
            <i class="bi bi-plus-lg"></i> Nova Conta/Caixa
        </button>
    </div>

    <div class="row g-3">
        <?php foreach($contas as $c): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-light text-dark border"><?= ucfirst($c['financeiro_conta_financeira_tipo']) ?></span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editConta(<?= htmlspecialchars(json_encode($c)) ?>)">Editar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= url('financeiro/excluir_conta/'.$c['financeiro_conta_financeira_id']) ?>" onclick="return confirm('Deseja excluir? Só é possível excluir contas sem movimentações.')">Excluir</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1"><?= $c['financeiro_conta_financeira_nome'] ?></h5>
                    <h3 class="text-primary fw-bold">R$ <?= number_format($c['financeiro_conta_financeira_saldo'], 2, ',', '.') ?></h3>
                    <small class="text-muted">Status: <span class="text-success"><?= $c['financeiro_conta_financeira_status'] ?></span></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="modal fade" id="modalConta" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="<?= url('financeiro/salvar_conta') ?>" method="POST" class="modal-content">
            <input type="hidden" name="id" id="conta_id">
            <div class="modal-header">
                <h5 class="modal-title" id="modalContaTitle">Nova Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Nome da Conta</label>
                    <input type="text" name="nome" id="conta_nome" class="form-control" required placeholder="Ex: Caixa Geral, Banco do Brasil">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Tipo</label>
                    <select name="tipo" id="conta_tipo" class="form-select">
                        <option value="caixa">Caixa (Espécie)</option>
                        <option value="banco">Banco (Conta Corrente/Poupança)</option>
                        <option value="carteira">Carteira Digital (Pix, etc)</option>
                    </select>
                </div>
                <div id="div_saldo" class="mb-3">
                    <label class="small fw-bold">Saldo Inicial (R$)</label>
                    <input type="number" step="0.01" name="saldo" class="form-control" value="0.00">
                    <small class="text-muted" style="font-size: 0.7rem;">O saldo só pode ser definido na criação.</small>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Status</label>
                    <select name="status" id="conta_status" class="form-select">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Salvar Conta</button>
            </div>
        </form>
    </div>
</div>

<script>
function editConta(dados) {
    document.getElementById('modalContaTitle').innerText = 'Editar Conta';
    document.getElementById('conta_id').value = dados.financeiro_conta_financeira_id;
    document.getElementById('conta_nome').value = dados.financeiro_conta_financeira_nome;
    document.getElementById('conta_tipo').value = dados.financeiro_conta_financeira_tipo;
    document.getElementById('conta_status').value = dados.financeiro_conta_financeira_status;

    // Esconde saldo inicial na edição para não dar erro de balanço
    document.getElementById('div_saldo').style.display = 'none';

    new bootstrap.Modal(document.getElementById('modalConta')).show();
}

// Reset ao fechar
document.getElementById('modalConta').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalContaTitle').innerText = 'Nova Conta';
    document.getElementById('conta_id').value = '';
    document.getElementById('div_saldo').style.display = 'block';
});
</script>
