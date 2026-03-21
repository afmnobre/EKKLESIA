<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-0"><i class="bi bi-gear-fill me-2"></i>Configurar Salas</h2>
            <p class="text-muted">Defina os nomes e as faixas etárias permitidas</p>
        </div>
        <a href="<?= url('escolaDominical') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Voltar às Classes
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Cadastrar Novo Modelo</h6>
                </div>
                <div class="card-body">
                    <form action="<?= url('escolaDominical/salvarConfig') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nome da Sala</label>
                            <input type="text" name="config_nome" class="form-control" placeholder="Ex: Jovens, Primários..." required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Idade Mín.</label>
                                <input type="number" name="config_idade_min" class="form-control" value="0" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold">Idade Máx.</label>
                                <input type="number" name="config_idade_max" class="form-control" value="99" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Adicionar Sala
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nome da Sala</th>
                                <th>Faixa Etária</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($configuracoes)): foreach($configuracoes as $c): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark"><?= $c['config_nome'] ?></td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3">
                                        <?= $c['config_idade_min'] ?> a <?= $c['config_idade_max'] ?> anos
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= url('escolaDominical/excluirConfig/'.$c['config_id']) ?>"
                                       class="btn btn-sm btn-outline-danger border-0"
                                       onclick="return confirm('Excluir este modelo de sala?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Nenhuma sala configurada.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
