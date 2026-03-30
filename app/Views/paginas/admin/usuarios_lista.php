<div class="container-fluid p-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-shield-lock me-2"></i><?= $titulo ?></h5>
            <button class="btn btn-success btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovoUser">
                <i class="bi bi-plus-lg"></i> Novo Operador
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil / Regra</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $u): ?>
                        <tr>
                            <td class="fw-bold"><?= $u['usuario_nome'] ?></td>
                            <td><?= $u['usuario_email'] ?></td>
                            <td>
                                <?php
                                    $listaPerfis = isset($u['perfil_nome']) ? explode(',', $u['perfil_nome']) : ['Sem Perfil'];
                                    foreach($listaPerfis as $nomePerfil):
                                ?>
                                    <span class="badge bg-soft-primary text-primary border border-primary small mb-1">
                                        <?= trim($nomePerfil) ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php $statusCor = ($u['usuario_status'] == 'ativo') ? 'success' : 'danger'; ?>
                                <span class="badge bg-<?= $statusCor ?>-subtle text-<?= $statusCor ?>">
                                    <?= ucfirst($u['usuario_status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-light btn-sm btn-editar-usuario"
                                        data-id="<?= $u['usuario_id'] ?>"
                                        data-nome="<?= $u['usuario_nome'] ?>"
                                        data-email="<?= $u['usuario_email'] ?>"
                                        data-status="<?= $u['usuario_status'] ?>"
                                        data-perfis-ids="<?= $u['perfis_ids'] ?? '' ?>">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <a href="<?= url('admin/excluir_usuario/'.$u['usuario_id']) ?>"
                                   class="btn btn-light btn-sm"
                                   onclick="return confirm('Excluir este operador?')">
                                    <i class="bi bi-trash text-danger"></i>
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

<div class="modal fade" id="modalNovoUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('admin/salvar_usuario') ?>" method="POST" class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Novo Usuário do Sistema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">E-mail (Login)</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Senha Inicial</label>
                    <input type="password" name="senha" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase d-block mb-2">Perfis de Acesso</label>
                    <div class="p-3 border rounded bg-light" style="max-height: 150px; overflow-y: auto;">
                        <div class="row">
                            <?php foreach($perfis as $p): ?>
                                <div class="col-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="perfis[]" value="<?= $p['perfil_id'] ?>" id="perfil_<?= $p['perfil_id'] ?>">
                                        <label class="form-check-label small fw-bold" for="perfil_<?= $p['perfil_id'] ?>"><?= $p['perfil_nome'] ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Gravar Acesso</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('admin/editar_usuario') ?>" method="POST" class="modal-content">
            <input type="hidden" name="usuario_id" id="edit_usuario_id">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">Nome Completo</label>
                    <input type="text" name="nome" id="edit_nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase">E-mail (Login)</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Nova Senha (opcional)</label>
                        <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-muted text-uppercase">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted text-uppercase d-block mb-2">Perfis de Acesso</label>
                    <div class="p-3 border rounded bg-light" style="max-height: 150px; overflow-y: auto;">
                        <div class="row">
                            <?php foreach($perfis as $p): ?>
                                <div class="col-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input check-perfil-edit" type="checkbox" name="perfis[]" value="<?= $p['perfil_id'] ?>" id="edit_perfil_<?= $p['perfil_id'] ?>">
                                        <label class="form-check-label small fw-bold" for="edit_perfil_<?= $p['perfil_id'] ?>"><?= $p['perfil_nome'] ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarUser'));

    document.querySelectorAll('.btn-editar-usuario').forEach(btn => {
        btn.addEventListener('click', function() {
            // Preenche campos de texto
            document.getElementById('edit_usuario_id').value = this.dataset.id;
            document.getElementById('edit_nome').value = this.dataset.nome;
            document.getElementById('edit_email').value = this.dataset.email;
            document.getElementById('edit_status').value = this.dataset.status;

            // Lógica para os Checkboxes de Perfil
            const perfisIds = this.dataset.perfisIds.split(',');
            document.querySelectorAll('.check-perfil-edit').forEach(checkbox => {
                checkbox.checked = perfisIds.includes(checkbox.value);
            });

            modalEditar.show();
        });
    });
});
</script>
