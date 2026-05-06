<div class="container-fluid pt-3">

    <div class="row mb-3">
        <div class="col-12 text-center p-3 rounded bg-white shadow-sm border-bottom">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="Logo IPB Completo" class="img-fluid" style="max-height: 85px;">
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0 align-items-center">
                <div class="col-md-4 col-12 text-center bg-light p-3 border-end d-flex align-items-center justify-content-center" style="min-height: 200px;">
                    <img src="<?= url('assets/img/Igreja.jpg') ?>"
                         class="img-fluid rounded shadow-sm"
                         alt="Projeto da Igreja"
                         style="object-fit: contain; max-height: 180px; width: auto; display: block;">
                </div>

                <div class="col-md col-12 p-4">
                    <span class="badge bg-primary mb-2 shadow-sm"><i class="fas fa-laptop-code me-1"></i> Dashboard EKKLESIA</span>
                    <h2 class="card-title fw-bold text-dark mb-1">🏛️ <?= $igreja['igreja_nome'] ?></h2>
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-map-marker-alt me-2 text-danger"></i><?= $igreja['igreja_endereco'] ?>
                    </p>
                </div>

                <div class="col-md-auto col-12 text-center p-4 border-start bg-light-subtle">
                    <div class="px-3">
                        <small class="text-muted fw-bold d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">👥 Total de Membros</small>
                        <h1 class="fw-bold text-primary mb-0" style="font-size: 2.5rem; line-height: 1;"><?= $totalMembros ?></h1>
                        <span class="badge bg-success text-white rounded-pill mt-1" style="font-size: 0.7rem;">ATIVOS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
                <i class="fas fa-users-cog me-2 text-secondary"></i>Sociedades Internas <small class="text-muted fw-normal">(Membros / Potencial)</small>
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
        <div class="col-md-2 col-6 mb-3"> <div class="card shadow-sm border-0 <?= $coresSoc[$nome] ?> h-100 shadow-hover overflow-hidden">
                <div class="card-body p-0">
                    <div class="text-center p-2">
                        <div class="bg-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                            <?php if(!empty($dados['logo'])): ?>
                                <img src="<?= url('assets/uploads/' . $dados['logo']) ?>" alt="<?= $nome ?>" class="img-fluid p-1" style="max-height: 40px;">
                            <?php else: ?>
                                <i class="fas fa-shield-alt text-muted opacity-50"></i>
                            <?php endif; ?>
                        </div>
                        <small class="fw-bold d-block text-uppercase" style="font-size: 0.65rem;"><?= $nome ?></small>
                        <h5 class="mb-0 fw-bold">
                            <?= $dados['real'] ?> <small class="opacity-75" style="font-size: 0.7rem;">/<?= $dados['potencial'] ?></small>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h5 class="text-dark fw-bold mb-3 border-bottom pb-2">
                <i class="fas fa-bible me-2 text-primary"></i>Escola Bíblica Dominical <small class="text-muted fw-normal">(Matriculados / Potencial)</small>
            </h5>
        </div>
        <?php foreach($ebd as $c): ?>
        <div class="col-md-2 col-6 mb-3"> <div class="card shadow-sm border-0 border-top-primary h-100 bg-white shadow-hover">
                <div class="card-body p-2 text-center">
                    <small class="text-uppercase fw-bold text-muted d-block mb-1" style="font-size: 0.6rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= $c['classe_nome'] ?>
                    </small>
                    <h5 class="fw-bold mb-1">
                        <?= $c['matriculados'] ?> <span class="text-muted small" style="font-size: 0.7rem;">/ <?= $c['potencial'] ?></span>
                    </h5>

                    <?php
                        $porcentagem = ($c['potencial'] > 0) ? ($c['matriculados'] / $c['potencial']) * 100 : 0;
                        $corBarra = ($porcentagem >= 80) ? 'bg-success' : (($porcentagem >= 50) ? 'bg-primary' : 'bg-warning');
                    ?>

                    <div class="progress mx-auto" style="height: 4px; width: 80%;" title="<?= round($porcentagem) ?>%">
                        <div class="progress-bar <?= $corBarra ?>" style="width: <?= $porcentagem ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end border-bottom pb-2 mb-3">
                <h5 class="text-dark fw-bold mb-0">
                    <i class="fas fa-heartbeat me-2 text-danger"></i>Cuidado Pastoral: Membros Ausentes
                </h5>
                <span class="badge bg-danger-subtle text-danger">Ausência > 90 dias</span>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 40%;">Membro</th>
                                <th class="text-center">Idade</th>
                                <th class="text-center">Contato</th>
                                <th class="text-end pe-4">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($membrosAusentes)): ?>
                                <?php foreach($membrosAusentes as $m): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-danger-subtle text-danger me-3">
                                                <?= strtoupper(substr($m['membro_nome'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark d-block"><?= $m['membro_nome'] ?></span>
                                                <small class="text-muted"><i class="far fa-envelope me-1"></i><?= $m['membro_email'] ?? 'N/A' ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><span class="badge bg-light text-dark border"><?= $m['idade'] ?> anos</span></td>
                                    <td class="text-center">
                                        <?php if($m['membro_telefone']): ?>
                                            <a href="https://wa.me/55<?= preg_replace('/\D/', '', $m['membro_telefone']) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                                <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Sem telefone</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= url('membros/perfil/'.$m['membro_id']) ?>" class="btn btn-sm btn-outline-primary border-0">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="opacity-50">
                                            <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                                            <p class="mb-0">Glória a Deus! Não há membros ausentes no período.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .border-top-primary { border-top: 3px solid #4e73df !important; }
    .shadow-hover { transition: all 0.3s ease; }
    .shadow-hover:hover { transform: translateY(-4px); shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important; }

    .avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .bg-light-subtle { background-color: #f8f9fc !important; }

    /* Ajuste para telas pequenas */
    @media (max-width: 768px) {
        .col-md-2 { width: 50%; } /* Em mobile vira 2 por linha */
    }
</style>
