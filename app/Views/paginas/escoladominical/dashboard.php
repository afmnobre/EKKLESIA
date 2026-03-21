<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary mb-0">Dashboard EBD</h2>
            <p class="text-muted">Indicadores de assiduidade e engajamento</p>
        </div>
        <a href="<?= url('escolaDominical') ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Voltar às Classes
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold text-uppercase">Engajamento</h6>
                    <h2 class="fw-bold mb-1"><?= $ocupacao['percentual'] ?>%</h2>
                    <div class="progress mb-2" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: <?= $ocupacao['percentual'] ?>%"></div>
                    </div>
                    <small class="text-muted small"><?= $ocupacao['total_ebd'] ?> alunos</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold text-uppercase">Sem Classe</h6>
                    <h2 class="fw-bold mb-0 text-warning"><?= $resumoMatriculas['disponiveis'] ?></h2>
                    <p class="small text-muted mb-0">Disponíveis</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold text-uppercase">Total Alunos</h6>
                    <h2 class="fw-bold mb-0 text-info"><?= $resumoMatriculas['matriculados'] ?></h2>
                    <p class="small text-muted mb-0">Matriculados</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold text-uppercase">Presença: <?= $comparativo['data_ref'] ?></h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2"><?= $comparativo['atual'] ?></h2>
                        <?php if($comparativo['tendencia'] == 'subida'): ?>
                            <small class="text-success fw-bold"><i class="bi bi-graph-up"></i> +<?= $comparativo['atual'] - $comparativo['anterior'] ?></small>
                        <?php elseif($comparativo['tendencia'] == 'descida'): ?>
                            <small class="text-danger fw-bold"><i class="bi bi-graph-down"></i> <?= $comparativo['atual'] - $comparativo['anterior'] ?></small>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted small mb-0">Anterior: <?= $comparativo['anterior'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-danger bg-opacity-10 border border-danger-subtle">
                <div class="card-body">
                    <h6 class="text-danger small fw-bold text-uppercase">Alerta de Evasão</h6>
                    <h2 class="fw-bold text-danger mb-0"><?= count($sumidos) ?> Alunos</h2>
                    <p class="text-danger small mb-0">+30 dias ausentes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                    <i class="bi bi-whatsapp text-success fs-4 me-2"></i>
                    <h5 class="fw-bold mb-0">Recuperação de Alunos</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th class="ps-4">Aluno / Classe</th>
                                <th>Última Presença</th>
                                <th>Tempo de Ausência</th>
                                <th class="text-end pe-4">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($sumidos)): foreach($sumidos as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?= $s['membro_nome'] ?></div>
                                    <span class="badge bg-light text-secondary border fw-normal"><?= $s['classe_nome'] ?></span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($s['ultima_presenca'])) ?></td>
                                <td><span class="text-danger fw-bold"><?= $s['dias_ausente'] ?> dias</span></td>
                                <td class="text-end pe-4">
                                    <?php
                                        $msg = urlencode("Olá " . explode(' ', $s['membro_nome'])[0] . ", tudo bem? Sentimos sua falta na Escola Dominical!");
                                        $celular = preg_replace('/[^0-9]/', '', $s['membro_telefone']);
                                    ?>
                                    <a href="https://wa.me/55<?= $celular ?>?text=<?= $msg ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="bi bi-whatsapp me-1"></i> Contatar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Nenhum aluno em evasão crítica. Parabéns!</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                    <i class="bi bi-award-fill text-warning fs-4"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Top 5 Assiduidade por Classe</h4>
                    <p class="text-muted small mb-0">Alunos com maior frequência acumulada</p>
                </div>
            </div>
        </div>

        <?php foreach($classes as $classe): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-mortarboard-fill me-2 text-secondary"></i><?= $classe['classe_nome'] ?>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="bg-light small">
                                <tr>
                                    <th class="ps-3 border-0">Rank</th>
                                    <th class="border-0">Aluno</th>
                                    <th class="text-center border-0">Presenças</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($classe['top_alunos'])): foreach($classe['top_alunos'] as $idx => $ta): ?>
                                <tr>
                                    <td class="ps-3 text-muted small">#<?= $idx + 1 ?></td>
                                    <td class="fw-bold small">
                                        <?php
                                            $partesNome = explode(' ', trim($ta['membro_nome']));
                                            $primeiroNome = $partesNome[0];
                                            $ultimoNome = (count($partesNome) > 1) ? end($partesNome) : '';
                                            echo $primeiroNome . ' ' . $ultimoNome;
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                            <?= $ta['total_presencas'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">Sem registros.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="row mt-5 mb-5">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-0 text-center">
                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-check-circle-fill me-2"></i>Alunos Matriculados</h5>
                <p class="text-muted small mb-0">Perfil de quem já frequenta a EBD</p>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="chartEtario"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100 border-top border-warning border-3">
            <div class="card-header bg-white py-3 border-0 text-center">
                <h5 class="fw-bold mb-0 text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Membros Fora da EBD</h5>
                <p class="text-muted small mb-0">Membros ativos sem matrícula em classe</p>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="chartEtarioFora"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .bg-opacity-10 { --bs-bg-opacity: 0.1; }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
    .border-success-subtle { border-color: rgba(25, 135, 84, 0.2) !important; }
    .card { transition: all 0.2s ease-in-out; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }
    .table th { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cores = ['#0d6efd', '#198754', '#ffc107', '#0dcaf0'];

    // Configuração Comum
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
        },
        cutout: '70%'
    };

    // Gráfico 1: Matriculados
    const dados1 = <?= json_encode($faixasEtarias) ?>;
    new Chart(document.getElementById('chartEtario'), {
        type: 'doughnut',
        data: {
            labels: dados1.map(i => i.faixa),
            datasets: [{ data: dados1.map(i => i.total), backgroundColor: cores }]
        },
        options: commonOptions
    });

    // Gráfico 2: Fora da EBD
    const dados2 = <?= json_encode($faixasEtariasFora) ?>;
    new Chart(document.getElementById('chartEtarioFora'), {
        type: 'doughnut',
        data: {
            labels: dados2.map(i => i.faixa),
            datasets: [{ data: dados2.map(i => i.total), backgroundColor: cores }]
        },
        options: commonOptions
    });
});
</script>
