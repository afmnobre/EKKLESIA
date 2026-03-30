<div class="container-fluid p-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-tags-fill me-2"></i><?= $titulo ?>
            </h5>
            <button class="btn btn-dark btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovoPerfil">
                <i class="bi bi-plus-circle"></i> Criar Perfil
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 250px;">Nome do Perfil</th>
                            <th>Descrição / Atribuições</th>
                            <th style="width: 120px;">Status</th>
                            <th class="text-center" style="width: 150px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($perfis as $p): ?>
                        <tr>
                            <td class="fw-bold text-uppercase small text-secondary"><?= $p['perfil_nome'] ?></td>
                            <td class="small text-muted"><?= $p['perfil_descricao'] ?></td>
                            <td>
                                <?php $classeStatus = ($p['perfil_status'] == 'ativo') ? 'success' : 'danger'; ?>
                                <span class="badge rounded-pill bg-<?= $classeStatus ?>-subtle text-<?= $classeStatus ?> border border-<?= $classeStatus ?>">
                                    <?= ucfirst($p['perfil_status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-light btn-sm btn-editar-perfil"
                                        title="Editar Perfil"
                                        data-id="<?= $p['perfil_id'] ?>"
                                        data-nome="<?= $p['perfil_nome'] ?>"
                                        data-descricao="<?= $p['perfil_descricao'] ?>"
                                        data-status="<?= $p['perfil_status'] ?>">
                                    <i class="bi bi-gear-fill text-primary"></i>
                                </button>

                                <a href="<?= url('admin/excluir_perfil/'.$p['perfil_id']) ?>"
                                   class="btn btn-light btn-sm text-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir este perfil? Usuários vinculados podem perder o acesso.')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <?php if(empty($perfis)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Nenhum perfil cadastrado.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovoPerfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('admin/salvar_perfil') ?>" method="POST" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Novo Perfil de Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nome do Perfil</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Tesoureiro, Secretaria..." required>
                    <div class="form-text">Este nome será usado para validar o acesso no Sidebar.</div>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Descrição das Permissões</label>
                    <textarea name="descricao" class="form-control" rows="3" placeholder="O que este perfil pode fazer no sistema?"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Salvar Perfil</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarPerfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('admin/editar_perfil') ?>" method="POST" class="modal-content">
            <input type="hidden" name="perfil_id" id="edit_perfil_id">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Editar Perfil de Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nome do Perfil</label>
                    <input type="text" name="nome" id="edit_nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Descrição</label>
                    <textarea name="descricao" id="edit_descricao" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Status</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Atualizar Perfil</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const botoesEditar = document.querySelectorAll('.btn-editar-perfil');
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarPerfil'));

    botoesEditar.forEach(botao => {
        botao.addEventListener('click', function() {
            // Preenche os campos do modal com os dados do botão
            document.getElementById('edit_perfil_id').value = this.getAttribute('data-id');
            document.getElementById('edit_nome').value = this.getAttribute('data-nome');
            document.getElementById('edit_descricao').value = this.getAttribute('data-descricao');
            document.getElementById('edit_status').value = this.getAttribute('data-status');

            // Abre o modal
            modalEditar.show();
        });
    });
});
</script>
