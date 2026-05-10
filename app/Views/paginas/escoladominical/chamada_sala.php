<div class="container-fluid pt-4">
    <div class="border border-dark p-3 mb-4">
        <div class="row align-items-center">
            <div class="col-2">
                <img src="<?= url('assets/img/logo_ipb.png') ?>" style="max-height: 80px; width: auto;" alt="Logo IPB">
            </div>

            <div class="col-8 text-center">
                <h4 class="mb-1 text-uppercase fw-bold">Diário de Frequência - Escola Bíblica Dominical</h4>
                <h5 class="mb-0">Classe: <?= $classe['classe_nome'] ?></h5>
                <p class="mb-0 small text-muted">Mês / Ano: <strong><?= date('m / Y') ?></strong></p>
            </div>

            <div class="col-2 text-end">
                <?php
                    $igrejaId = $_SESSION['usuario_igreja_id'];
                    $logoLocal = !empty($classe['igreja_logo'])
                        ? url("assets/uploads/{$igrejaId}/logo/{$classe['igreja_logo']}")
                        : null;
                ?>
                <?php if ($logoLocal): ?>
                    <img src="<?= $logoLocal ?>" style="max-height: 80px; width: auto;" alt="Logo Local">
                <?php else: ?>
                    <div class="border border-secondary d-inline-block p-2 small text-muted" style="width: 80px; height: 80px; font-size: 10px;">
                        Selo da<br>Igreja
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered border-dark table-sm align-middle mb-0 text-center chamada-impressao">
            <thead>
                <tr class="table-light">
                    <th class="text-start ps-3 py-2" style="width: 300px;">Aluno / Participante</th>
                    <?php
                    $diasNoMes = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                    for($dia = 1; $dia <= $diasNoMes; $dia++):
                    ?>
                        <th class="dia-col"><?= str_pad($dia, 2, '0', STR_PAD_LEFT) ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <tr class="table-secondary fw-bold">
                    <td class="text-start ps-3 py-3">
                        <div>PROF: <?= $classe['professor_nome'] ?? '____________________' ?></div>
                        <div class="small mt-2 text-muted" style="font-weight: normal;">
                            Substituto: _________________________
                        </div>
                    </td>
                    <?php for($dia = 1; $dia <= $diasNoMes; $dia++): ?>
                        <td class="celula-vazia"></td>
                    <?php endfor; ?>
                </tr>

                <?php if(!empty($alunos)): ?>
                    <?php foreach($alunos as $index => $aluno): ?>
                    <tr>
                        <td class="text-start ps-3 py-2">
                            <span class="small"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>.</span>
                            <?= $aluno['membro_nome'] ?>
                        </td>
                        <?php for($dia = 1; $dia <= $diasNoMes; $dia++): ?>
                            <td class="celula-vazia border-start"></td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php for($i = 1; $i <= 8; $i++): ?>
                <tr>
                    <td class="text-start ps-3 py-3 text-muted italic">
                        ____________________________________
                    </td>
                    <?php for($dia = 1; $dia <= $diasNoMes; $dia++): ?>
                        <td class="celula-vazia border-start"></td>
                    <?php endfor; ?>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        <div class="row">
            <div class="col-6 text-center">
                <div class="border-top border-dark mx-auto w-75 pt-1 mt-4">Assinatura do Professor</div>
            </div>
            <div class="col-6 text-center">
                <div class="border-top border-dark mx-auto w-75 pt-1 mt-4">Secretaria da EBD</div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Força cores de fundo na impressão */
    .table-light { background-color: #f8f9fa !important; }
    .table-secondary { background-color: #e9ecef !important; }

    .dia-col {
        width: 28px !important;
        min-width: 28px !important;
        font-size: 0.75rem;
    }

    .celula-vazia { height: 35px; }

    @media print {
        @page { size: landscape; margin: 1cm; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        nav, .sidebar, .btn, .footer, header { display: none !important; }
        .container-fluid { width: 100% !important; padding: 0 !important; }
    }
</style>
