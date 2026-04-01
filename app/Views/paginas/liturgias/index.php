<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-secondary mb-0">⛪ Liturgias e Ordens de Culto</h3>
            <p class="text-muted small">Histórico detalhado das celebrações e ordens litúrgicas</p>
        </div>
        <a href="<?= url('liturgia/novo') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Nova Liturgia
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <form class="row g-2 align-items-center" method="GET" action="<?= url('liturgia/index') ?>">
                        <div class="col-auto">
                            <label class="small fw-bold text-muted text-uppercase">Filtrar por período:</label>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control form-control-sm" name="inicio" value="<?= $_GET['inicio'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control form-control-sm" name="fim" value="<?= $_GET['fim'] ?? '' ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Aplicar</button>
                            <?php if(isset($_GET['inicio'])): ?>
                                <a href="<?= url('liturgia/index') ?>" class="btn btn-sm btn-link text-decoration-none text-muted">Limpar</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Data e Hora</th>
                            <th>Tema do Culto</th>
                            <th>Dirigente</th>
                            <th>Pregador</th>
                            <th class="text-center">Itens</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($liturgias)): foreach ($liturgias as $l): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold d-block"><?= date('d/m/Y', strtotime($l['igreja_liturgia_data'])) ?></span>
                                    <small class="text-muted"><?= date('H:i', strtotime($l['igreja_liturgia_data'])) ?>h</small>
                                </td>
                                <td>
                                    <span class="text-primary fw-medium">
                                        <?= !empty($l['igreja_liturgia_tema']) ? htmlspecialchars($l['igreja_liturgia_tema']) : 'Culto Regular' ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="bi bi-person-badge me-1 text-muted"></i>
                                    <?php
                                        echo !empty($l['igreja_liturgia_dirigente_id'])
                                            ? htmlspecialchars($l['nome_membro_dirigente'])
                                            : htmlspecialchars($l['igreja_liturgia_dirigente_nome']);
                                    ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal">
                                        <i class="bi bi-mic me-1 text-primary"></i>
                                        <?php
                                            echo !empty($l['igreja_liturgia_pregador_id'])
                                                ? htmlspecialchars($l['nome_membro_pregador'])
                                                : htmlspecialchars($l['igreja_liturgia_pregador_nome']);
                                        ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-info text-dark" title="Total de itens na liturgia">
                                        <?= $l['total_itens'] ?? '0' ?> partes
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?= url('liturgia/editar/' . $l['igreja_liturgia_id']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="Editar Liturgia">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a href="<?= url('liturgia/imprimir/' . $l['igreja_liturgia_id']) ?>"
                                           target="_blank" class="btn btn-sm btn-outline-primary" title="Imprimir/Visualizar">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="confirmarExclusao('<?= url('liturgia/excluir/' . $l['igreja_liturgia_id']) ?>')"
                                                title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                    <p class="mb-0">Nenhuma liturgia registrada para esta igreja.</p>
                                    <a href="<?= url('liturgia/novo') ?>" class="btn btn-sm btn-primary mt-2">Criar Primeira Ordem</a>
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
function confirmarExclusao(url) {
    if (confirm('Atenção: Ao excluir esta liturgia, toda a ordem do culto e os textos bíblicos associados serão apagados. Deseja continuar?')) {
        window.location.href = url;
    }
}
</script>

<style>
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.03); }
    .badge { font-weight: 500; padding: 0.5em 0.8em; }
    .btn-group .btn { padding: 0.4rem 0.6rem; }
</style>
