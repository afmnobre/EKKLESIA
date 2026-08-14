<!DOCTYPE html>
<?php
// Define a data de geração (hoje) e do período
$dataDoc = !empty($dataGeracao) ? $dataGeracao : (!empty($data) && is_string($data) ? $data : date('Y-m-d'));
$dataDocumentoFormatada = date('d/m/Y', strtotime($dataDoc));

$dataInicioFormatada = !empty($dataInicio) ? date('d/m/Y', strtotime($dataInicio)) : date('01/m/Y');
$dataFimFormatada    = !empty($dataFim) ? date('d/m/Y', strtotime($dataFim)) : date('t/m/Y');
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Contábil - <?= $dataDocumentoFormatada ?></title>
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

        /* Grade Principal */
        .grid-relatorio {
            display: flex;
            border: 2px solid #000;
            margin-bottom: 15px;
        }

        .coluna-conferentes {
            width: 40%;
            border-right: 2px solid #000;
            display: flex;
            flex-direction: column;
        }

        .box-conferente {
            flex: 1;
            padding: 10px;
        }
        .box-conferente:first-child {
            border-bottom: 2px solid #000;
        }

        .coluna-tabela {
            width: 60%;
        }

        table.tabela-modalidades {
            width: 100%;
            border-collapse: collapse;
        }
        table.tabela-modalidades th, table.tabela-modalidades td {
            border-bottom: 1px solid #ccc;
            border-left: 1px solid #000;
            padding: 6px 10px;
        }
        table.tabela-modalidades th {
            border-bottom: 2px solid #000;
            text-transform: uppercase;
            font-weight: bold;
            background-color: #f9f9f9;
        }
        table.tabela-modalidades tr:last-child td {
            border-bottom: none;
        }

        .linha-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 2px solid #000 !important;
            background-color: #f2f2f2;
        }

        /* Quadro Vermelho - Ajuste Bancário */
        .box-ajuste-red {
            border: 2px solid #ff0000;
            padding: 8px;
            margin-top: 15px;
        }
        .texto-ajuste {
            color: #ff0000;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .sub-ajuste {
            font-style: italic;
            font-size: 10px;
            color: #555;
        }
        .tabela-ajuste-red {
            width: 100%;
            border-collapse: collapse;
        }
        .tabela-ajuste-red td {
            border: 1px solid #ff0000;
            padding: 4px 8px;
            font-weight: bold;
            color: #ff0000;
        }

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
    <!-- CABEÇALHO PADRONIZADO -->
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
                COLETA SEMANAL DE DÍZIMOS E OFERTAS
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
                $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                if(!empty($igreja['igreja_logo'])):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja" style="max-height: 60px;">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" style="height: 60px;">
            <?php endif; ?>
        </div>
    </div>

    <!-- GRADE CONFERENTES E MODALIDADES -->
    <div class="grid-relatorio">
        <!-- Lado Esquerdo: Conferentes -->
        <div class="coluna-conferentes">
            <div class="box-conferente">
                <span class="fw-bold fst-italic header-red">Conferente 1 - Diácono</span>
                <div class="mt-4 pt-2 text-muted small fw-bold"><?= htmlspecialchars($oficiais['d1']) ?></div>
            </div>
            <div class="box-conferente">
                <span class="fw-bold fst-italic header-red">Conferente 2 - Diácono</span>
                <div class="mt-4 pt-2 text-muted small fw-bold"><?= htmlspecialchars($oficiais['d2']) ?></div>
            </div>
        </div>

        <!-- Lado Direito: Tabela de Modalidades -->
        <div class="coluna-tabela">
            <table class="tabela-modalidades">
                <thead>
                    <tr>
                        <th class="text-start header-red">CATEGORIA / SUBCATEGORIA</th>
                        <th class="text-end header-red" style="width: 40%;">VALOR R$</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($modalidades)): ?>
                        <?php foreach ($modalidades as $mod): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($mod['financeiro_categoria_nome']) && !empty($mod['subcategoria_nome'])): ?>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($mod['financeiro_categoria_nome']) ?></div>
                                        <div class="small text-muted" style="font-size: 10px; text-transform: uppercase;">
                                            &rsaquo; <?= htmlspecialchars($mod['subcategoria_nome']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="fw-bold"><?= htmlspecialchars($mod['nome']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end fw-bold align-middle">
                                    <?= $mod['valor'] > 0 ? number_format($mod['valor'], 2, ',', '.') : '0,00' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center py-3 text-muted">Nenhuma receita encontrada no período.</td>
                        </tr>
                    <?php endif; ?>

                    <!-- TOTAL GERAL -->
                    <tr class="linha-total">
                        <td class="text-uppercase header-red">TOTAL GERAL</td>
                        <td class="text-end header-red"><?= number_format($totalGeral, 2, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- BLOCO VERMELHO DE AJUSTE BANCÁRIO DO MÊS CORRENTE -->
    <div class="box-ajuste-red">
        <div class="row align-items-center">
            <div class="col-8">
                <div class="texto-ajuste">
                    AJUSTE DE CÁLCULO SEGUNDO MOVIMENTAÇÃO BANCÁRIA DO MÊS CORRENTE:
                </div>
                <div class="sub-ajuste">
                    ( preenchido pela tesouraria, se necessário )
                </div>
            </div>
            <div class="col-4">
                <table class="tabela-ajuste-red">
                    <tr>
                        <td>DÍZIMO:</td>
                        <td style="width: 50%;"></td>
                    </tr>
                    <tr>
                        <td>OFERTA:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>TOTAL:</td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- ASSINATURAS PADRONIZADAS -->
    <div class="row mt-4">
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

    <div class="text-center mt-3 pt-2">
        <p class="small text-muted mb-0">Documento gerado em <?= date('d/m/Y H:i:s') ?> pelo Módulo de Conferência Ekklesia.</p>
    </div>
</div>

</body>
</html>
