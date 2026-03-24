<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-person-check me-2 text-primary"></i>Contribuições por Membro</h3>
        <form method="GET" class="d-flex gap-2">
            <select name="ano" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php for($i=date('Y'); $i>=date('Y')-5; $i--): ?>
                    <option value="<?= $i ?>" <?= $ano == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <?php if(empty($relatorio)): ?>
        <div class="alert alert-info border-0 shadow-sm">Nenhum rateio de membros encontrado para o ano de <?= $ano ?>.</div>
    <?php else: ?>
        <?php foreach($relatorio as $tipoReceita => $membros): ?>
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-uppercase text-primary" style="letter-spacing: 1px;">
                        <i class="bi bi-tag-fill me-2"></i><?= $tipoReceita ?>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle" style="font-size: 0.85rem;">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="ps-3" style="width: 250px;">Nome do Membro</th>
                                    <?php for($m=1; $m<=12; $m++): ?>
                                        <th class="text-center"><?= date('M', mktime(0,0,0,$m,1)) ?></th>
                                    <?php endfor; ?>
                                    <th class="text-end pe-3 bg-light fw-bold">Total Ano</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($membros as $nome => $meses): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-dark"><?= $nome ?></td>
                                    <?php
                                    $totalMembroAno = 0;
                                    for($m=1; $m<=12; $m++):
                                        $valor = $meses[$m] ?? 0;
                                        $totalMembroAno += $valor;
                                    ?>
                                        <td class="text-center <?= $valor > 0 ? 'fw-bold text-dark' : 'text-muted opacity-25' ?>">
                                            <?= $valor > 0 ? number_format($valor, 0, ',', '.') : '-' ?>
                                        </td>
                                    <?php endfor; ?>
                                    <td class="text-end pe-3 fw-bold bg-light text-primary">
                                        R$ <?= number_format($totalMembroAno, 2, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
