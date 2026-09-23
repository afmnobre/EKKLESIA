<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Conferência - <?= date('d/m/Y', strtotime($data)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-size: 11px; }
        .folha { width: 210mm; margin: 0 auto; padding: 15px; }
        .header-top { border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo-box { width: 130px; }
        .igreja-nome { font-size: 16px; margin-bottom: 2px; }
        .igreja-endereco { font-size: 10px; color: #666; display: block; }
        .titulo-relatorio { margin-top: 5px; font-size: 13px; letter-spacing: 1px; font-weight: bold; }

        table.tabela-relatorio { width: 100%; border-collapse: collapse; margin-bottom: 15px; border: 2px solid #000; }
        table.tabela-relatorio th, table.tabela-relatorio td { border: 1px solid #000; padding: 4px 6px; }

        .header-red { color: #d32f2f; font-weight: bold; }
        .header-blue { color: #1976d2; font-weight: bold; }
        .bg-avulsa { background-color: #ffff00 !important; font-weight: bold; color: #d32f2f; }
        .assinatura { border-top: 1px solid #000; margin-top: 40px; text-align: center; padding-top: 5px; }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            .folha { padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container text-end py-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-print">Imprimir Agora</button>
</div>

<div class="folha">
    <div class="header-top d-flex justify-content-between align-items-center mb-3">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" style="height: 60px;">
        </div>

        <div class="text-center flex-grow-1 px-3">
            <h4 class="igreja-nome text-uppercase fw-bold mb-0">
                <?= htmlspecialchars($igreja['igreja_nome']) ?>
            </h4>
            <span class="igreja-endereco">
                <?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>
            </span>
            <div class="titulo-relatorio text-uppercase">
                Relatório de Conferência de Valores
            </div>
            <div class="fw-bold header-red">
                DATA: <?= date('d/m/Y', strtotime($data)) ?>
            </div>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                if(!empty($igreja['igreja_logo'])):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja" style="max-height: 60px;">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" style="height: 60px;">
            <?php endif; ?>
        </div>
    </div>

    <!-- TABELA EM FORMATO MATRIZ DA FOLHA DE CONFERÊNCIA -->
    <table class="tabela-relatorio">
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle header-red fs-6" style="width: 45%;">NOME</th>
                <th colspan="2" class="text-center header-red fs-6">DÍZIMO</th>
                <th colspan="2" class="text-center header-red fs-6">OFERTA</th>
            </tr>
            <tr>
                <th class="text-center header-blue" style="width: 13.75%;">$ (Espécie)</th>
                <th class="text-center header-blue" style="width: 13.75%;">Conta</th>
                <th class="text-center header-blue" style="width: 13.75%;">$ (Espécie)</th>
                <th class="text-center header-blue" style="width: 13.75%;">Conta</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($membrosMatriz)): ?>
                <?php foreach ($membrosMatriz as $nome => $valores): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($nome) ?></td>
                        <td class="text-end"><?= $valores['dizimo_especie'] > 0 ? number_format($valores['dizimo_especie'], 2, ',', '.') : '' ?></td>
                        <td class="text-end"><?= $valores['dizimo_conta'] > 0 ? number_format($valores['dizimo_conta'], 2, ',', '.') : '' ?></td>
                        <td class="text-end"><?= $valores['oferta_especie'] > 0 ? number_format($valores['oferta_especie'], 2, ',', '.') : '' ?></td>
                        <td class="text-end"><?= $valores['oferta_conta'] > 0 ? number_format($valores['oferta_conta'], 2, ',', '.') : '' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-3">Nenhum lançamento registrado nesta data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <!-- TOTAL PARCIAL -->
            <tr>
                <td class="fw-bold text-center header-red fs-6">TOTAL PARCIAL</td>
                <td class="text-end fw-bold"><?= number_format($totalDizimoEspecie, 2, ',', '.') ?></td>
                <td class="text-end fw-bold"><?= number_format($totalDizimoConta, 2, ',', '.') ?></td>
                <td class="text-end fw-bold"><?= number_format($totalOfertaEspecie, 2, ',', '.') ?></td>
                <td class="text-end fw-bold"><?= number_format($totalOfertaConta, 2, ',', '.') ?></td>
            </tr>

            <!-- DÍZIMO (A) / OFERTA (B) -->
            <tr>
                <td colspan="1" class="border-0"></td>
                <td colspan="2" class="text-center fw-bold fs-6">
                    <span class="header-red">DÍZIMO</span>:
                    R$ <?= number_format($totalDizimoA, 2, ',', '.') ?>
                </td>
                <td colspan="2" class="text-center fw-bold fs-6">
                    <span class="header-red">OFERTA</span>:
                    R$ <?= number_format($totalOfertaB, 2, ',', '.') ?>
                </td>
            </tr>

            <!-- OFERTAS AVULSAS (C) -->
            <tr>
                <td class="bg-avulsa text-uppercase text-center">
                    OFERTAS AVULSAS &rarr;
                </td>
                <td colspan="4" class="text-center fw-bold fs-6">
                    R$ <?= number_format($ofertasAvulsas, 2, ',', '.') ?>
                </td>
            </tr>

            <!-- TOTAL GERAL (A+B+C) -->
            <tr>
                <td class="text-center header-red fw-bold fs-4">
                    TOTAL GERAL
                </td>
                <td colspan="4" class="text-center header-red fw-bold fs-4">
                    R$ <?= number_format($totalGeral, 2, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- ASSINATURAS -->
    <div class="row mt-5">
        <div class="col-4">
            <div class="assinatura">
                <small><?= htmlspecialchars($oficiais['d1']) ?></small><br>
                <strong>Oficial de Conferência 1</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="assinatura">
                <small><?= htmlspecialchars($oficiais['d2']) ?></small><br>
                <strong>Oficial de Conferência 2</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="assinatura">
                <small><?= htmlspecialchars($tesoureiro) ?></small><br>
                <strong>Tesoureiro (Visto/Recebido)</strong>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 pt-2">
        <p class="small text-muted mb-0">Documento gerado em <?= date('d/m/Y H:i:s') ?> pelo Módulo de Conferência Ekklesia.</p>
    </div>
</div>

</body>
</html>
