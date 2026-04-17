<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold">
            <i class="bi bi-tags me-2 text-primary"></i>Categorias de Patrimônio
        </h3>
        <div class="d-flex gap-2">
            <a href="<?= url('patrimonios') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Voltar aos Bens
            </a>
            <button class="btn btn-primary" onclick="novaCategoria()">
                <i class="bi bi-plus-lg me-1"></i> Nova Categoria
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nome da Categoria</th>
                            <th class="text-center">Identificador</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Nenhuma categoria cadastrada.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($categorias as $cat): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded border d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-tag-fill"></i>
                                    </div>
                                    <div class="fw-bold text-dark"><?= $cat['patrimonio_categoria_nome'] ?></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">ID: <?= $cat['patrimonio_categoria_id'] ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-sm btn-white border"
                                            title="Editar"
                                            onclick="editarCategoria(<?= $cat['patrimonio_categoria_id'] ?>, '<?= $cat['patrimonio_categoria_nome'] ?>')">
                                        <i class="bi bi-pencil text-warning"></i>
                                    </button>
                                    <button class="btn btn-sm btn-white border text-danger"
                                            title="Excluir"
                                            onclick="confirmarExclusaoCategoria(<?= $cat['patrimonio_categoria_id'] ?>, '<?= $cat['patrimonio_categoria_nome'] ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= url('patrimonios/salvarCategoria') ?>" method="POST" class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitulo">
                    <i class="bi bi-tag-plus"></i> Nova Categoria
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id" id="cat_id">
                <div class="mb-3">
                    <label class="form-label fw-bold">Descrição da Categoria</label>
                    <input type="text" name="patrimonio_categoria_nome" id="cat_nome" class="form-control" required
                           placeholder="Ex: Instrumentos Musicais, Móveis, Som...">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="bi bi-check-lg"></i> SALVAR CATEGORIA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Limpa o formulário e abre o modal para nova categoria
 */
function novaCategoria() {
    document.getElementById('cat_id').value = '';
    document.getElementById('cat_nome').value = '';
    document.getElementById('modalTitulo').innerHTML = '<i class="bi bi-tag-plus"></i> Nova Categoria';
    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
}

/**
 * Preenche os dados e abre o modal para edição
 */
function editarCategoria(id, nome) {
    document.getElementById('cat_id').value = id;
    document.getElementById('cat_nome').value = nome;
    document.getElementById('modalTitulo').innerHTML = '<i class="bi bi-pencil-square"></i> Editar Categoria';
    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
}

/**
 * Confirmação de exclusão enviando para a rota do PatrimoniosController
 */
function confirmarExclusaoCategoria(id, nome) {
    if(confirm('Deseja realmente excluir a categoria "' + nome + '"?')) {
        window.location.href = "<?= url('patrimonios/excluirCategoria/') ?>" + id;
    }
}
</script>
