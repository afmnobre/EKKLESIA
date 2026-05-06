<!-- Arquivo: app/Views/admin/igrejas_lista.php -->
<div class="container-fluid pt-3">

    <!-- Cabeçalho e Botão de Cadastro -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-0">
                <i class="fas fa-church me-2 text-primary"></i><?= $titulo ?>
            </h4>
            <p class="text-muted small mb-0">Gerencie as congregações e sedes cadastradas no sistema.</p>
        </div>
        <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalNovaIgreja">
            <i class="fas fa-plus-circle me-2"></i>Nova Igreja
        </button>
    </div>

    <!-- Alertas de Feedback -->
    <?php if(isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabela de Igrejas -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 50px;">ID</th>
                        <th>Igreja</th>
                        <th>CNPJ</th>
                        <th>Pastor Responsável</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($igrejas)): ?>
                        <?php foreach($igrejas as $i): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $i['igreja_id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-place-of-worship"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block"><?= $i['igreja_nome'] ?></span>
                                        <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?= $i['igreja_endereco'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $i['igreja_cnpj'] ?? 'Não informado' ?></span></td>
                            <td>
                                <i class="fas fa-user-tie me-1 text-secondary"></i>
                                <?= $i['pastor_nome'] ?? '<span class="text-muted italic">Não definido</span>' ?>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border shadow-sm btn-editar"
                                        data-id="<?= $i['igreja_id'] ?>"
                                        data-nome="<?= $i['igreja_nome'] ?>"
                                        data-cnpj="<?= $i['igreja_cnpj'] ?>"
                                        data-endereco="<?= $i['igreja_endereco'] ?>"
                                        data-pastor="<?= $i['igreja_pastor_id'] ?>"
                                        title="Editar">
                                    <i class="fas fa-edit text-primary"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                                Nenhuma igreja cadastrada até o momento.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Cadastro / Edição -->
<div class="modal fade" id="modalNovaIgreja" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="<?= url('admin/salvar_igreja') ?>" method="POST" id="formIgreja">
                <input type="hidden" name="igreja_id" id="field_id">

                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold" id="modalLabel">
                        <i class="fas fa-plus me-2"></i><span>Nova Igreja</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small">Nome da Igreja/Congregação</label>
                            <input type="text" name="igreja_nome" id="field_nome" class="form-control shadow-sm" placeholder="Ex: IPB de Bairro Exemplo" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">CNPJ</label>
                            <input type="text" name="igreja_cnpj" id="field_cnpj" class="form-control shadow-sm" placeholder="00.000.000/0000-00">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Endereço Completo</label>
                            <input type="text" name="igreja_endereco" id="field_endereco" class="form-control shadow-sm" placeholder="Rua, Número, Bairro, Cidade - UF">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Pastor Responsável</label>
                            <select name="igreja_pastor_id" id="field_pastor" class="form-select shadow-sm">
                                <option value="">Selecione um pastor (opcional)</option>
                                <!-- Aqui você pode carregar dinamicamente os pastores/membros -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-save me-2"></i>Salvar Igreja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('modalNovaIgreja'));
    const form = document.getElementById('formIgreja');
    const label = document.querySelector('#modalLabel span');

    // Lógica para preparar o modal para Edição
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {
            form.action = '<?= url('admin/editar_igreja') ?>';
            label.textContent = 'Editar Igreja';

            document.getElementById('field_id').value = this.dataset.id;
            document.getElementById('field_nome').value = this.dataset.nome;
            document.getElementById('field_cnpj').value = this.dataset.cnpj;
            document.getElementById('field_endereco').value = this.dataset.endereco;
            document.getElementById('field_pastor').value = this.dataset.pastor;

            modal.show();
        });
    });

    // Resetar modal ao fechar
    document.getElementById('modalNovaIgreja').addEventListener('hidden.bs.modal', function () {
        form.action = '<?= url('admin/salvar_igreja') ?>';
        label.textContent = 'Nova Igreja';
        form.reset();
        document.getElementById('field_id').value = '';
    });
});
</script>

<style>
    .bg-primary-subtle { background-color: #e7f1ff !important; }
    .table thead th { font-size: 0.75rem; text-uppercase: true; letter-spacing: 0.5px; }
    .btn-editar:hover { background-color: #f8f9fa; transform: translateY(-1px); }
</style>
