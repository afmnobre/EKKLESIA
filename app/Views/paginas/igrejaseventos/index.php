<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        Ação realizada com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        Ocorreu um erro ao processar a solicitação.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="text-secondary mb-0">⛪ Eventos da Igreja</h3>
            <p class="text-muted small">Gestão de congressos, aniversários e convocações gerais</p>
        </div>
        <a href="<?= url('igrejaEvento/novo') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Novo Evento
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-3 border-end">
                    <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Filtrar por Ano</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-calendar-event text-primary"></i></span>
                        <select class="form-select border-0 bg-light fw-bold"
                                onchange="location.href='?mes=<?= $mesSelecionado ?>&ano='+this.value">
                            <?php
                            $anoAtual = date('Y');
                            // Se o controller não enviar anosDisponiveis, gera um range padrão
                            $anos = $anosDisponiveis ?? [['ano' => $anoAtual - 1], ['ano' => $anoAtual], ['ano' => $anoAtual + 1]];
                            foreach($anos as $a):
                            ?>
                                <option value="<?= $a['ano'] ?>" <?= $a['ano'] == $anoSelecionado ? 'selected' : '' ?>>
                                    <?= $a['ano'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-9">
                    <label class="small fw-bold text-muted text-uppercase mb-1 d-block text-center text-md-start ps-3">Mês do Calendário</label>
                    <div class="nav nav-pills nav-fill bg-light p-1 rounded-pill mx-md-2">
                        <?php
                        $meses = [
                            1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun',
                            7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'
                        ];
                        foreach($meses as $num => $nome):
                            $active = ($num == $mesSelecionado) ? 'active shadow-sm text-white bg-primary' : 'text-muted';
                        ?>
                            <div class="nav-item">
                                <a class="nav-link py-1 rounded-pill <?= $active ?>"
                                   href="?mes=<?= $num ?>&ano=<?= $anoSelecionado ?>">
                                    <?= $nome ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
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
                            <th>Evento</th>
                            <th>Local</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($eventos)): foreach ($eventos as $e): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold d-block"><?= date('d/m/Y', strtotime($e['evento_data_hora_inicio'])) ?></span>
                                    <small class="text-muted"><?= date('H:i', strtotime($e['evento_data_hora_inicio'])) ?>h</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div style="width: 12px; height: 12px; background: <?= $e['evento_cor'] ?>; border-radius: 50%; margin-right: 10px;" title="Cor no calendário"></div>
                                        <span class="text-primary fw-medium"><?= htmlspecialchars($e['evento_titulo']) ?></span>
                                    </div>
                                    <?php if(!empty($e['evento_descricao'])): ?>
                                        <small class="text-muted d-block text-truncate" style="max-width: 250px;"><?= htmlspecialchars($e['evento_descricao']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>
                                    <?= htmlspecialchars($e['evento_local'] ?? 'Templo') ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'bg-light text-dark border';
                                    if ($e['evento_status'] == 'Confirmado') $statusClass = 'bg-success text-white';
                                    if ($e['evento_status'] == 'Cancelado') $statusClass = 'bg-danger text-white';
                                    if ($e['evento_status'] == 'Concluído') $statusClass = 'bg-info text-dark';
                                    ?>
                                    <span class="badge <?= $statusClass ?> fw-normal">
                                        <?= $e['evento_status'] ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
									<div class="btn-group shadow-sm">
										<a href="<?= url("igrejaEvento/banner/{$e['evento_id']}") ?>"
										   class="btn btn-sm btn-info text-white"
										   title="Criar Banner">
											<i class="bi bi-palette"></i>
										</a>

										<a href="<?= url('igrejaEvento/editar/' . $e['evento_id']) ?>"
										   class="btn btn-sm btn-secondary"
										   title="Editar Evento">
											<i class="bi bi-pencil"></i>
										</a>

										<button type="button"
												class="btn btn-sm btn-danger"
												onclick="confirmarExclusao('<?= url('igrejaEvento/excluir/' . $e['evento_id']) ?>')"
												title="Excluir">
											<i class="bi bi-trash"></i>
										</button>
									</div>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                    <p class="mb-0">Nenhum evento registrado para este período.</p>
                                    <a href="<?= url('igrejaEvento/novo') ?>" class="btn btn-sm btn-primary mt-2">Criar Novo Evento</a>
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
    if (confirm('Deseja realmente excluir este evento? Ele será removido do calendário oficial.')) {
        window.location.href = url;
    }
}
</script>

<style>
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.03); }
    .badge { font-weight: 500; padding: 0.5em 0.8em; }
    .btn-group .btn { padding: 0.4rem 0.6rem; }
    .card { border-radius: 10px; }
    .nav-pills .nav-link { transition: all 0.2s; font-size: 0.85rem; }
    .nav-pills .nav-link.active { font-weight: bold; }
</style>
