<!-- Nome do arquivo: app/Views/dizimosofertas/relatorio_extrato.php -->

<!DOCTYPE html>
<?php
$dataDocumentoFormatada = date('d/m/Y');
$dataInicioFormatada = !empty($dataInicio) ? date('d/m/Y', strtotime($dataInicio)) : date('01/m/Y');
$dataFimFormatada    = !empty($dataFim) ? date('d/m/Y', strtotime($dataFim)) : date('t/m/Y');
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro Extrato - <?= $dataInicioFormatada ?> até <?= $dataFimFormatada ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-size: 11px; font-family: Arial, sans-serif; }
        .folha { width: 210mm; margin: 0 auto; padding: 15px; }
        .header-top { border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo-box { width: 130px; }
        .igreja-nome { font-size: 16px; margin-bottom: 2px; }
        .igreja-endereco { font-size: 10px; color: #666; display: block; }
        .titulo-relatorio { margin-top: 5px; font-size: 13px; letter-spacing: 1px; font-weight: bold; }

        .header-red { color: #d32f2f; font-weight: bold; }
        .header-blue { color: #1976d2; font-weight: bold; }

        /* Tabela Principal */
        table.tabela-extrato {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 15px;
        }
        table.tabela-extrato th, table.tabela-extrato td {
            border-bottom: 1px solid #ccc;
            border-left: 1px solid #ddd;
            padding: 6px 10px;
        }
        table.tabela-extrato th {
            border-bottom: 2px solid #000;
            text-transform: uppercase;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .linha-total {
            font-weight: bold;
            font-size: 12px;
            background-color: #f2f2f2;
        }

        .assinatura { border-top: 1px solid #000; margin-top: 50px; text-align: center; padding-top: 5px; }

        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            .folha { padding: 5px; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container text-end py-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-print">
        <i class="bi bi-printer"></i> Imprimir / Salvar PDF
    </button>
</div>

<div class="folha">
    <!-- CABEÇALHO PADRONIZADO -->
    <div class="header-top d-flex justify-content-between align-items-center mb-3">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" style="height: 60px;">
        </div>

        <div class="text-center flex-grow-1 px-3">
            <h4 class="igreja-nome text-uppercase fw-bold mb-0">
                <?= htmlspecialchars($igreja['igreja_nome'] ?? 'Igreja Presbiteriana do Brasil') ?>
            </h4>
            <span class="igreja-endereco">
                <?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>
            </span>
            <div class="titulo-relatorio text-uppercase">
                EXTRATO MENSAL DE RECEITAS E DESPESAS
            </div>
            <div class="fw-bold header-red mb-1">
                DATA DE GERAÇÃO: <?= $dataDocumentoFormatada ?>
            </div>
            <div class="small text-muted fw-bold">
                Período de referência: <?= $dataInicioFormatada ?> até <?= $dataFimFormatada ?>
            </div>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/" . ($igreja['igreja_id'] ?? '') . "/logo/" . ($igreja['igreja_logo'] ?? '');
                if(!empty($igreja['igreja_logo'])):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja" style="max-height: 60px;">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" style="height: 60px;">
            <?php endif; ?>
        </div>
    </div>

    <!-- TABELA DE EXTRATO AGLUTINADO -->
    <table class="tabela-extrato">
        <thead>
            <tr>
                <th style="width: 12%;">DATA</th>
                <th style="width: 10%; text-align: center;">TIPO</th>
                <th style="width: 30%;">CATEGORIA</th>
                <th>DESCRIÇÃO / SUBCATEGORIA</th>
                <th class="text-end" style="width: 18%;">VALOR (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($lancamentos)): ?>
                <?php foreach ($lancamentos as $item): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($item['data_movimentacao'])) ?></td>
                        <td class="text-center">
                            <?php if ($item['tipo'] === 'entrada'): ?>
                                <span class="badge bg-success" style="font-size: 9px;">RECEITA</span>
                            <?php else: ?>
                                <span class="badge bg-danger" style="font-size: 9px;">DESPESA</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($item['categoria']) ?></strong></td>
                        <td><?= htmlspecialchars($item['descricao']) ?></td>
                        <td class="text-end fw-bold <?= $item['tipo'] === 'entrada' ? 'text-success' : 'text-danger' ?>">
                            <?= $item['tipo'] === 'saida' ? '-' : '' ?><?= number_format($item['valor'], 2, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Nenhuma movimentação encontrada no período.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="linha-total">
                <td colspan="4" class="text-end text-uppercase header-blue">TOTAL RECEITAS:</td>
                <td class="text-end text-success">R$ <?= number_format($totalReceitas, 2, ',', '.') ?></td>
            </tr>
            <tr class="linha-total">
                <td colspan="4" class="text-end text-uppercase header-red">TOTAL DESPESAS:</td>
                <td class="text-end text-danger">R$ <?= number_format($totalDespesas, 2, ',', '.') ?></td>
            </tr>
            <tr class="linha-total" style="border-top: 2px solid #000;">
                <td colspan="4" class="text-end text-uppercase">SALDO DO PERÍODO:</td>
                <td class="text-end <?= $saldoPeriodo >= 0 ? 'text-success' : 'text-danger' ?>">
                    R$ <?= number_format($saldoPeriodo, 2, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- ASSINATURAS PADRONIZADAS -->
    <div class="row mt-5">
        <div class="col-6">
            <div class="assinatura">
                <strong>Tesoureiro / Responsável Financeiro</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="assinatura">
                <strong>Conselho Fiscal / Visto Pastor</strong>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 pt-2">
        <p class="small text-muted mb-0">Documento gerado em <?= date('d/m/Y H:i:s') ?> pelo Módulo Financeiro Ekklesia.</p>
    </div>
</div>

</body>
</html>
