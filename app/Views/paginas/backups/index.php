<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-secondary mb-0"><i class="bi bi-shield-lock-fill me-2"></i> Segurança e Backup</h3>
            <p class="text-muted small text-uppercase fw-bold mb-0">Gestão de cópias de segurança - EKKLESIA</p>
        </div>
        <a href="<?= url('backup/gerar') ?>" class="btn btn-primary shadow-sm px-4">
            <i class="bi bi-database-add me-2"></i> Gerar Novo Backup
        </a>
    </div>

    <?php if(isset($_GET['status'])): ?>
        <div class="alert alert-<?= ($_GET['status'] == 'sucesso' || $_GET['status'] == 'excluido') ? 'success' : 'danger' ?> border-0 shadow-sm animate__animated animate__fadeIn">
            <?php
                if($_GET['status'] == 'sucesso') echo "Backup realizado com sucesso!";
                elseif($_GET['status'] == 'excluido') echo "Arquivo removido permanentemente.";
                else echo "Ocorreu um erro na operação. Verifique os logs do servidor.";
            ?>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <span class="fw-bold text-dark"><i class="bi bi-folder2-open me-2 text-primary"></i> Arquivos em `/public/assets/dbbkp/`</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4">NOME DO ARQUIVO</th>
                            <th>DATA / HORA</th>
                            <th>TAMANHO</th>
                            <th class="text-end pe-4">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($backups)): foreach($backups as $bkp): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-filetype-sql fs-4 text-primary me-3"></i>
                                        <span class="text-dark fw-bold"><?= $bkp['nome'] ?></span>
                                    </div>
                                </td>
                                <td><?= $bkp['data'] ?></td>
                                <td><span class="badge bg-light text-secondary border"><?= $bkp['tamanho'] ?></span></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?= url('assets/dbbkp/' . $bkp['nome']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="Baixar SQL" download>
                                            <i class="bi bi-cloud-download"></i>
                                        </a>
                                        <button onclick="confirmarExcluir('<?= $bkp['nome'] ?>')"
                                                class="btn btn-sm btn-outline-danger" title="Excluir Backup">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted small">Nenhum backup disponível.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExcluir(nome) {
    if(confirm('Tem certeza que deseja apagar este arquivo?')) {
        window.location.href = '<?= url("backup/excluir/") ?>' + nome;
    }
}
</script>
