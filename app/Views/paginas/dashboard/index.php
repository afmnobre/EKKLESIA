<div class="container-fluid pt-3">

    <div class="row mb-3">
        <div class="col-12 text-center p-3 rounded bg-white shadow-sm border-bottom">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB Completo" class="img-fluid" style="max-height: 85px;">
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="row g-0 align-items-center">
            <div class="col-md-4 text-center bg-light p-2">
                <img src="<?= url('assets/img/Igreja.jpg') ?>"
                     class="img-fluid rounded"
                     alt="Projeto da Igreja"
                     style="object-fit: contain; max-height: 220px; width: auto;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <span class="badge bg-primary mb-2 shadow-sm">Dashboard Administrativo EKKLESIA</span>
                    <h2 class="card-title fw-bold text-dark mb-1"><?= $igreja['igreja_nome'] ?></h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i><?= $igreja['igreja_endereco'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
                <i class="fas fa-graduation-cap me-2 text-primary"></i>Escola Bíblica: Alunos por Classe (Matriculados / Potencial)
            </h5>
        </div>
        <?php foreach($ebd as $c): ?>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 border-left-primary h-100 bg-white">
                <div class="card-body p-3 text-center">
                    <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.75rem;"><?= $c['classe_nome'] ?></small>
                    <h4 class="fw-bold mb-0">
                        <?= $c['matriculados'] ?> <span class="text-muted small" style="font-size: 0.6em;">/ <?= $c['potencial'] ?></span>
                    </h4>

                    <?php
                        // Cálculo da barra de progresso
                        $porcentagem = ($c['potencial'] > 0) ? ($c['matriculados'] / $c['potencial']) * 100 : 0;
                        // Cor da barra baseada no aproveitamento
                        $corBarra = ($porcentagem >= 80) ? 'bg-success' : (($porcentagem >= 50) ? 'bg-primary' : 'bg-warning');
                    ?>

                    <div class="progress mt-2" style="height: 6px;" title="<?= round($porcentagem) ?>% da capacidade">
                        <div class="progress-bar <?= $corBarra ?>" style="width: <?= $porcentagem ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
                <i class="fas fa-users me-2 text-secondary"></i>Sociedades Internas (Membros / Potencial)
            </h5>
        </div>
        <?php
        $coresSoc = [
            'UCP' => 'bg-info text-white',
            'UPA' => 'bg-primary text-white',
            'UMP' => 'bg-warning text-dark',
            'SAF' => 'bg-danger text-white',
            'UPH' => 'bg-success text-white'
        ];
        ?>
        <?php foreach($sociedades as $nome => $dados): ?>
        <div class="col-md-2 col-6 mb-3">
            <div class="card shadow-sm border-0 <?= $coresSoc[$nome] ?> h-100 shadow-hover">
                <div class="card-body text-center d-flex flex-column justify-content-center p-2">
                    <small class="fw-bold d-block text-uppercase"><?= $nome ?></small>
                    <h4 class="mb-0 fw-bold">
                        <?= $dados['real'] ?> <small class="opacity-75" style="font-size: 0.6em;">/ <?= $dados['potencial'] ?></small>
                    </h4>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div> <style>
    /* Estilos extras para deixar o visual mais "limpo" */
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .shadow-hover:hover { transform: translateY(-3px); transition: 0.3s; }
</style>
