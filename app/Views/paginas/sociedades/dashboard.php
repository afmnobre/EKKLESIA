<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="fw-bold text-dark mb-1">📊 Painel Estratégico de Sociedades</h2>
        <p class="text-muted mb-0">Análise de engajamento, conformidade etária e agenda de eventos.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button class="btn btn-outline-primary btn-sm me-2" onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar Dados
        </button>
        <span class="badge bg-white text-dark border p-2 shadow-sm">
            <i class="bi bi-calendar3 me-2 text-primary"></i><?= date('d/m/Y') ?>
        </span>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-pie-chart-fill text-primary me-2"></i>Taxa de Cobertura (Sócios vs Aptos)
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 320px;">
                    <canvas id="chartCoberturaSociedades"></canvas>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 fw-bold">Estatísticas por Sociedade</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Sociedade</th>
                            <th>Líder</th>
                            <th class="text-center">Gestão</th>
                            <th class="text-end pe-4">Aproveitamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sociedades as $soc):
                            $total = (int)$soc['total_socios'];
                            $aptos = (int)$soc['membros_aptos'];
                            $percentual = ($aptos > 0) ? round(($total / $aptos) * 100) : 0;
                            $corBarra = $percentual >= 80 ? 'bg-success' : ($percentual >= 50 ? 'bg-info' : 'bg-warning');

                            $txtTempo = "---";
                            if (!empty($soc['vinculo_data_atribuicao'])) {
                                $dataIni = new DateTime($soc['vinculo_data_atribuicao']);
                                $diff = $dataIni->diff(new DateTime());
                                $txtTempo = $diff->y > 0 ? $diff->y . "a " . $diff->m . "m" : ($diff->m > 0 ? $diff->m . "m" : $diff->d . "d");
                            }
                        ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark d-block"><?= $soc['sociedade_nome'] ?></span>
                                <small class="text-muted"><?= $soc['sociedade_idade_min'] ?>-<?= $soc['sociedade_idade_max'] ?> anos</small>
                            </td>
                            <td>
                                <span class="small"><?= $soc['nome_lider'] ?? '<em class="text-muted">Não definido</em>' ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-light text-dark border"><?= $txtTempo ?></span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px; max-width: 80px;">
                                        <div class="progress-bar <?= $corBarra ?>" style="width: <?= $percentual ?>%"></div>
                                    </div>
                                    <span class="small fw-bold"><?= $percentual ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4 bg-danger-soft border-start border-danger border-4">
            <div class="card-body">
                <h6 class="fw-bold text-danger mb-3">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>Membros Fora da Faixa
                </h6>
                <div class="list-group list-group-flush">
                    <?php if (empty($alertas)): ?>
                        <div class="text-center p-3 text-muted small bg-white rounded border">
                            ✅ Nenhuma inconformidade.
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($alertas, 0, 3) as $alerta): ?>
                        <div class="list-group-item bg-white border mb-2 rounded p-2 shadow-sm">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold small text-dark"><?= $alerta['membro_nome'] ?></span>
                                <span class="badge bg-danger"><?= $alerta['idade_atual'] ?> anos</span>
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                <?= $alerta['sociedade_nome'] ?> (Máx: <?= $alerta['sociedade_idade_max'] ?>)
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-calendar-check text-success me-2"></i>Agenda Próxima</h5>
            </div>
            <div class="card-body p-0"> <?php if (empty($eventos)): ?>
                    <p class="text-center py-4 text-muted small">Sem eventos agendados.</p>
                <?php else: ?>
                    <div class="px-3">
                        <?php foreach ($eventos as $ev): ?>
                        <div class="d-flex mb-3 pb-3 border-bottom-dashed">
                            <div class="bg-primary text-white rounded text-center p-1 me-3" style="min-width: 45px; height: 50px;">
                                <div class="fw-bold lh-1 mt-1"><?= date('d', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></div>
                                <small style="font-size: 0.65rem; text-transform: uppercase;"><?= date('M', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></small>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="mb-0 fw-bold small text-truncate"><?= $ev['sociedade_evento_titulo'] ?></h6>
                                <p class="mb-0 text-muted small"><?= $ev['sociedade_nome'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 mt-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-calendar-event-fill text-primary me-2"></i>Frequência de Eventos por Mês (<?= date('Y') ?>)
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start ps-4" style="min-width: 180px;">Sociedade</th>
                            <?php foreach($mesesNomes as $nome): ?>
                                <th><?= $nome ?></th>
                            <?php endforeach; ?>
                            <th class="bg-light fw-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($eventosMensais as $socId => $dados):
                            $totalAnual = array_sum($dados['meses']);
                        ?>
                        <tr>
                            <td class="text-start ps-4 fw-bold text-dark"><?= $dados['nome'] ?></td>
                            <?php for($i=1; $i<=12; $i++): $qtd = $dados['meses'][$i]; ?>
                                <td>
                                    <?php if($qtd > 0): ?>
                                        <span class="badge rounded-pill bg-primary-subtle text-primary"><?= $qtd ?></span>
                                    <?php else: ?>
                                        <span class="text-muted opacity-25">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                            <td class="bg-light fw-bold text-primary"><?= $totalAnual ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.03); }
    .border-bottom-dashed { border-bottom: 1px dashed #eee; }
    .border-bottom-dashed:last-child { border-bottom: none; }
    .progress-bar { transition: width 0.8s ease; }
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartCoberturaSociedades').getContext('2d');

    const labels = [<?= "'" . implode("','", array_column($sociedades, 'sociedade_nome')) . "'" ?>];
    const dataSocios = [<?= implode(",", array_column($sociedades, 'total_socios')) ?>];
    const dataAptos = [<?= implode(",", array_column($sociedades, 'membros_aptos')) ?>];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Sócios Atuais',
                    data: dataSocios,
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Membros Aptos (Potencial)',
                    data: dataAptos,
                    backgroundColor: 'rgba(233, 236, 239, 0.8)',
                    borderColor: '#dee2e6',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            indexAxis: 'y', // Inverte para barras horizontais, fica melhor para nomes longos
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end' }
            },
            scales: {
                x: { grid: { display: false }, beginAtZero: true },
                y: { grid: { display: false } }
            }
        }
    });
});
</script>
