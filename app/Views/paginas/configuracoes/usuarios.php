<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark"><i class="fas fa-user-shield me-2 text-primary"></i>Gestão de Usuários</h4>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="fas fa-plus me-1"></i> Novo Usuário
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Nome</th>
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= $u['usuario_nome'] ?></td>
                        <td><?= $u['usuario_email'] ?></td>
                        <td><span class="badge bg-info-subtle text-info border border-info-subtle"><?= $u['perfil_nome'] ?></span></td>
                        <td>
                            <span class="badge <?= $u['usuario_status'] == 'Ativo' ? 'bg-success' : 'bg-secondary' ?> rounded-pill">
                                <?= $u['usuario_status'] ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-light border shadow-sm"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal simplificado para exemplo -->
<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('configuracao/usuario_salvar') ?>" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Cadastrar Usuário</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">E-mail (Login)</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Senha Inicial</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Usuário</button>
                </div>
            </form>
        </div>
    </div>
</div>
