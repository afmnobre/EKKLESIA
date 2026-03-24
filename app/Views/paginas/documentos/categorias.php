<div class="container-fluid py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('documentos') ?>">Documentos</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-tags-fill me-2 text-primary"></i>Categorias de Documentos</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </button>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="width: 80px;">ID</th>
                                    <th>Nome da Categoria</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($categorias)): foreach($categorias as $cat): ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?= $cat['documento_categoria_id'] ?></td>
                                    <td><span class="fw-bold"><?= $cat['documento_categoria_nome'] ?></span></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border"
                                                onclick="editarCategoria(<?= $cat['documento_categoria_id'] ?>, '<?= $cat['documento_categoria_nome'] ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= url('documentos/excluir_categoria/'.$cat['documento_categoria_id']) ?>"
                                           class="btn btn-sm btn-light border text-danger"
                                           onclick="return confirm('Tem certeza? Isso pode afetar documentos vinculados.')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Nenhuma categoria cadastrada.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Dica</h6>
                    <p class="small text-muted mb-0">
                        As categorias ajudam a organizar seus arquivos (Atas, Ofícios, Contratos, etc).
                        Ao criar uma categoria, o sistema gera automaticamente uma pasta no servidor para armazenar os uploads de forma organizada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovaCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('documentos/salvar_categoria') ?>" method="POST" class="modal-content">
            <input type="hidden" name="categoria_id" id="edit_categoria_id">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nome da Categoria</label>
                    <input type="text" name="nome" id="edit_categoria_nome" class="form-control" placeholder="Ex: Atas, Cartas..." required>
                </div>
            </div>
            <div class="modal-footer bg-light p-2">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-4">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function editarCategoria(id, nome) {
    document.getElementById('modalLabel').innerText = 'Editar Categoria';
    document.getElementById('edit_categoria_id').value = id;
    document.getElementById('edit_categoria_nome').value = nome;
    new bootstrap.Modal(document.getElementById('modalNovaCategoria')).show();
}

// Resetar modal ao fechar (para não ficar com dados de edição ao tentar criar nova)
document.getElementById('modalNovaCategoria').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalLabel').innerText = 'Nova Categoria';
    document.getElementById('edit_categoria_id').value = '';
    document.getElementById('edit_categoria_nome').value = '';
});
</script>
