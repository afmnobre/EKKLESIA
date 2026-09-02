<!-- Nome do arquivo: app/Views/escoladominical/chamada_sala.php -->
<div class="container-fluid pt-3">
    <!-- Barra de Ações (Oculta na impressão) -->
    <div class="d-flex justify-content-end mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print me-1"></i> Imprimir Chamada
        </button>
    </div>

    <!-- Cabeçalho do Relatório -->
    <div class="border border-dark p-2 mb-3">
        <div class="row align-items-center">
            <div class="col-2">
                <img src="<?= url('assets/img/logo_ipb.png') ?>" style="max-height: 60px; width: auto;" alt="Logo IPB">
            </div>

            <div class="col-8 text-center">
                <h5 class="mb-1 text-uppercase fw-bold" style="font-size: 1.1rem;">Diário de Frequência - Escola Bíblica Dominical</h5>
                <h6 class="mb-0 fw-bold">Classe: <?= $classe['classe_nome'] ?></h6>
                <p class="mb-0 small text-muted" style="font-size: 0.8rem;">Trimestre: <strong><?= $trimestre ?>º Trimestre / <?= $ano ?></strong></p>
            </div>

            <div class="col-2 text-end">
                <?php
                    $igrejaId = $_SESSION['usuario_igreja_id'];
                    $logoLocal = !empty($classe['igreja_logo'])
                        ? url("assets/uploads/{$igrejaId}/logo/{$classe['igreja_logo']}")
                        : null;
                ?>
                <?php if ($logoLocal): ?>
                    <img src="<?= $logoLocal ?>" style="max-height: 60px; width: auto;" alt="Logo Local">
                <?php else: ?>
                    <div class="border border-secondary d-inline-block p-1 small text-muted" style="width: 60px; height: 60px; font-size: 9px;">
                        Selo da<br>Igreja
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabela de Frequência -->
    <div class="table-responsive">
        <table class="table table-bordered border-dark table-sm align-middle mb-0 text-center chamada-impressao">
            <thead>
                <!-- Linha 1 do Header: Nome dos Meses do Trimestre -->
                <tr class="table-light text-uppercase fw-bold" style="font-size: 0.75rem;">
                    <th rowspan="2" class="text-start ps-2 py-1 align-middle" style="width: 200px;">Aluno / Participante</th>
                    <?php foreach ($domingosPorMes as $mes): ?>
                        <th colspan="<?= count($mes['domingos']) ?>" class="border-start border-end py-1">
                            <?= $mes['nome'] ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
                <!-- Linha 2 do Header: Dias correspondentes aos Domingos -->
                <tr class="table-light" style="font-size: 0.7rem;">
                    <?php foreach ($domingosPorMes as $mes): ?>
                        <?php foreach ($mes['domingos'] as $idx => $dia): ?>
                            <th class="dia-col <?= $idx === 0 ? 'border-start' : '' ?>"><?= $dia ?></th>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <!-- Linha do Professor -->
                <tr class="table-secondary fw-bold" style="font-size: 0.75rem;">
                    <td class="text-start ps-2 py-1">
                        <div>PROF: <?= $classe['professor_nome'] ?? '____________________' ?></div>
                        <div class="small text-muted" style="font-weight: normal; font-size: 0.65rem;">
                            Substituto: _________________________
                        </div>
                    </td>
                    <?php foreach ($domingosPorMes as $mes): ?>
                        <?php foreach ($mes['domingos'] as $idx => $dia): ?>
                            <td class="celula-vazia <?= $idx === 0 ? 'border-start' : '' ?>"></td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>

                <!-- Alunos Matriculados -->
                <?php if (!empty($alunos)): ?>
                    <?php foreach ($alunos as $index => $aluno): ?>
                    <tr style="font-size: 0.75rem;">
                        <td class="text-start ps-2 py-1">
                            <span class="small" style="font-size: 0.7rem;"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>.</span>
                            <?= $aluno['membro_nome'] ?>
                        </td>
                        <?php foreach ($domingosPorMes as $mes): ?>
                            <?php foreach ($mes['domingos'] as $idx => $dia): ?>
                                <td class="celula-vazia <?= $idx === 0 ? 'border-start' : '' ?>"></td>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Linhas em branco para visitantes / novos alunos -->
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <tr>
                    <td class="text-start ps-2 py-1 text-muted italic" style="font-size: 0.7rem;">
                        ____________________________________
                    </td>
                    <?php foreach ($domingosPorMes as $mes): ?>
                        <?php foreach ($mes['domingos'] as $idx => $dia): ?>
                            <td class="celula-vazia <?= $idx === 0 ? 'border-start' : '' ?>"></td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <!-- Assinaturas -->
    <div class="mt-4">
        <div class="row" style="font-size: 0.8rem;">
            <div class="col-6 text-center">
                <div class="border-top border-dark mx-auto w-75 pt-1 mt-3">Assinatura do Professor</div>
            </div>
            <div class="col-6 text-center">
                <div class="border-top border-dark mx-auto w-75 pt-1 mt-3">Secretaria da EBD</div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-light { background-color: #f8f9fa !important; }
    .table-secondary { background-color: #e9ecef !important; }

    .dia-col {
        width: 22px !important;
        min-width: 22px !important;
        padding: 2px 0 !important;
    }

    .celula-vazia { height: 24px; }

    @media print {
        @page {
            size: portrait;
            margin: 0.8cm;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        nav, .sidebar, .btn, .footer, header, .no-print {
            display: none !important;
        }
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }
    }
</style>
