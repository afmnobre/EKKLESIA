<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-tags me-2 text-primary"></i>Categorias Financeiras</h3>
        <button class="btn btn-primary btn-sm" onclick="novaCat()">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </button>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nome</th>
                                <th>Tipo</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($categorias as $c): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= $c['financeiro_categoria_nome'] ?></td>
                                <td>
                                    <?php if($c['financeiro_categoria_tipo'] == 'entrada'): ?>
                                        <span class="badge bg-success-subtle text-success px-3">Entrada</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger px-3">Saída</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
									<a href="<?= url('financeiro/subcategorias?cat_id='.$c['financeiro_categoria_id']) ?>"
									   class="btn btn-sm btn-outline-primary border"
									   title="Adicionar Subcategoria nesta categoria">
										<i class="bi bi-node-plus"></i>
									</a>

                                    <button class="btn btn-sm btn-light border" onclick="editCat(<?= $c['financeiro_categoria_id'] ?>, '<?= $c['financeiro_categoria_nome'] ?>', '<?= $c['financeiro_categoria_tipo'] ?>')" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <a href="<?= url('financeiro/excluir_categoria/'.$c['financeiro_categoria_id']) ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Excluir esta categoria?')" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCat" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('financeiro/salvar_categoria') ?>" method="POST" class="modal-content">
            <input type="hidden" name="id" id="cat_id">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold">Nome</label>
                    <input type="text" name="nome" id="cat_nome" class="form-control" required placeholder="Ex: Dízimos, Luz...">
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Tipo</label>
                    <select name="tipo" id="cat_tipo" class="form-select" required>
                        <option value="entrada">Entrada (Receita)</option>
                        <option value="saida">Saída (Despesa)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function novaCat() {
    document.getElementById('modalTitle').innerText = 'Nova Categoria';
    document.getElementById('cat_id').value = '';
    document.getElementById('cat_nome').value = '';
    new bootstrap.Modal(document.getElementById('modalCat')).show();
}

function editCat(id, nome, tipo) {
    document.getElementById('modalTitle').innerText = 'Editar Categoria';
    document.getElementById('cat_id').value = id;
    document.getElementById('cat_nome').value = nome;
    document.getElementById('cat_tipo').value = tipo;
    new bootstrap.Modal(document.getElementById('modalCat')).show();
}
</script>
