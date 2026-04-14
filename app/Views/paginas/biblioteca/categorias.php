<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold"><i class="bi bi-tags me-2"></i>Categorias da Biblioteca</h3>
            <p class="text-muted small">Organize seu acervo por temas, gêneros ou departamentos.</p>
        </div>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </button>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nome da Categoria</th>
                                <th>Descrição</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($categorias as $cat): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= htmlspecialchars($cat['categoria_nome']) ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($cat['categoria_descricao'] ?? '---') ?></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-secondary me-1 btn-editar-categoria"
                                            data-id="<?= $cat['categoria_id'] ?>"
                                            data-nome="<?= htmlspecialchars($cat['categoria_nome']) ?>"
                                            data-descricao="<?= htmlspecialchars($cat['categoria_descricao']) ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCategoria">
                                        <i class="bi bi-pencil"></i>
                                    </button>

									<button type="button"
											class="btn btn-sm btn-outline-danger btn-excluir-categoria"
											data-id="<?= $cat['categoria_id'] ?>"
											data-nome="<?= htmlspecialchars($cat['categoria_nome']) ?>"
											data-bs-toggle="modal"
											data-bs-target="#modalExcluirCategoria">
										<i class="bi bi-trash"></i>
									</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($categorias)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">Nenhuma categoria cadastrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h6 class="fw-bold">Dica de Organização</h6>
                    <p class="small text-muted">Use categorias como: <i>Teologia Reformada, Vida Cristã, Comentários Bíblicos, Infantil, Liderança.</i></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluirCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="mb-1">Você está prestes a excluir a categoria:</p>
                <h5 id="excluir-cat-nome" class="fw-bold text-danger mb-3">---</h5>
                <p class="text-muted small mb-0">Esta ação não pode ser desfeita. Livros vinculados a esta categoria podem precisar ser reclassificados.</p>
            </div>
            <div class="modal-footer bg-light border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <a id="btn-confirmar-exclusao-cat" href="#" class="btn btn-danger px-4">Excluir Agora</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Cadastrar Categoria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('biblioteca/categoriaSalvar') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold">Nome</label>
                        <input type="text" name="categoria_nome" class="form-control" required placeholder="Ex: Teologia">
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Descrição (Opcional)</label>
                        <textarea name="categoria_descricao" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Salvar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Editar Categoria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('biblioteca/categoriaAtualizar') ?>" method="POST">
                <input type="hidden" name="categoria_id" id="edit_cat_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold">Nome</label>
                        <input type="text" name="categoria_nome" id="edit_cat_nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold">Descrição</label>
                        <textarea name="categoria_descricao" id="edit_cat_descricao" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lógica para preencher o modal de edição
    const botoesEditar = document.querySelectorAll('.btn-editar-categoria');

    botoesEditar.forEach(btn => {
        btn.addEventListener('click', function() {
            // Captura os dados dos atributos data-
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const descricao = this.getAttribute('data-descricao');

            // Preenche os campos do modal de edição
            document.getElementById('edit_cat_id').value = id;
            document.getElementById('edit_cat_nome').value = nome;
            document.getElementById('edit_cat_descricao').value = descricao;
        });
    });

    // Lógica para o Modal de Exclusão
    const botoesExcluir = document.querySelectorAll('.btn-excluir-categoria');
    const nomeExibir = document.getElementById('excluir-cat-nome');
    const btnConfirmar = document.getElementById('btn-confirmar-exclusao-cat');

    botoesExcluir.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');

            // Preenche o nome da categoria no corpo do modal
            nomeExibir.textContent = nome;

            // Monta a URL de exclusão com o ID
            btnConfirmar.setAttribute('href', '<?= url("biblioteca/categoriaExcluir/") ?>' + id);
        });
    });
});
</script>
