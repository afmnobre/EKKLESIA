<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Conferência - <?= date('d/m/Y', strtotime($data)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: white; font-size: 12px; }
        .folha { width: 210mm; margin: 0 auto; padding: 20px; }
        .header-rel { border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th { background: #f2f2f2 !important; border: 1px solid #ddd; padding: 8px; text-transform: uppercase; font-size: 10px; }
        table td { border: 1px solid #ddd; padding: 8px; }
        .assinatura { border-top: 1px solid #000; margin-top: 50px; text-align: center; padding-top: 5px; }
        .bg-avulso { background-color: #f8f9fa; border: 1px dashed #ddd; padding: 15px; border-radius: 5px; }
        @media print {
            .btn-print { display: none; }
            body { margin: 0; }
            .folha { padding: 10px; }
        }
		/* ... seus estilos anteriores ... */
		.header-top { border-bottom: 2px solid #000; padding-bottom: 15px; }
		.logo-box { width: 150px; } /* Define um tamanho fixo para as caixas de logo */
		.igreja-nome { font-size: 18px; margin-bottom: 2px; }
		.igreja-endereco { font-size: 10px; color: #666; display: block; }
		.titulo-relatorio {
			margin-top: 10px;
			font-size: 14px;
			letter-spacing: 1px;
			font-weight: bold;
		}

		@media print {
			.no-print { display: none; }
		}
	</style>
</head>
<body onload="window.print()">

<div class="container text-end py-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-print">Imprimir Agora</button>
</div>

<div class="folha">
    <div class="header-top d-flex justify-content-between align-items-center mb-4">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" style="height: 70px;">
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
            <div class="fw-bold">
                DATA: <?= date('d/m/Y', strtotime($data)) ?>
            </div>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                if(!empty($igreja['igreja_logo'])):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja" style="max-height: 70px;">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" style="height: 70px;">
            <?php endif; ?>
        </div>
    </div>

    <h6 class="fw-bold text-uppercase mt-4">1. Resumo de Entradas (Totais por Subcategoria)</h6>
    <table>
        <thead>
            <tr>
                <th>Descrição / Subcategoria</th>
                <th class="text-end">Valor Total Lançado</th>
            </tr>
        </thead>
		<tbody>
			<?php
				// Remova a linha $totalGeral = 0; daqui de dentro se ela existir,
				// pois o Controller já enviou essa variável pronta.
				foreach($resumo as $r):
			?>
				<tr>
					<td><?= htmlspecialchars($r['nome']) ?></td>
					<td class="text-end">R$ <?= number_format($r['total'], 2, ',', '.') ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
    </table>

    <div class="row g-4">
        <div class="col-8">
            <h6 class="fw-bold text-uppercase">2. Detalhamento de Contribuições (Envelopes Identificados)</h6>
            <?php if(!empty($rateio)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Membro</th>
                        <th>Tipo</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $somaRateio = 0;
                        foreach($rateio as $m):
                        $somaRateio += $m['valor'];
                    ?>
                        <tr>
                            <td><?= $m['membro_nome'] ?></td>
                            <td><?= $m['subcategoria_nome'] ?></td>
                            <td class="text-end">R$ <?= number_format($m['valor'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end text-uppercase">Total Identificado:</td>
                        <td class="text-end">R$ <?= number_format($somaRateio, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <?php else: ?>
                <div class="alert alert-light border text-center">Nenhuma contribuição identificada (rateio) nesta data.</div>
                <?php $somaRateio = 0; ?>
            <?php endif; ?>
        </div>

        <div class="col-4">
            <h6 class="fw-bold text-uppercase">3. Conciliação de Caixa</h6>
            <div class="bg-avulso">
                <div class="mb-3">
                    <small class="text-muted d-block text-uppercase" style="font-size: 9px;">Ofertas Não Rateadas:</small>
                    <?php $valorAvulso = $totalGeral - $somaRateio; ?>
                    <h5 class="fw-bold mb-0">R$ <?= number_format($valorAvulso, 2, ',', '.') ?></h5>
                    <small class="text-muted" style="font-size: 10px;">(Salvas / Envelopes sem nome)</small>
                </div>
                <hr>
                <div>
                    <small class="text-muted d-block text-uppercase" style="font-size: 9px;">Total Geral Conferido:</small>
                    <h4 class="fw-bold text-success mb-0">R$ <?= number_format($totalGeral, 2, ',', '.') ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-4">
            <div class="assinatura">
                <small><?= $oficiais['d1'] ?></small><br>
                <strong>Oficial de Conferência 1</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="assinatura">
                <small><?= $oficiais['d2'] ?></small><br>
                <strong>Oficial de Conferência 2</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="assinatura">
                <small><?= $tesoureiro ?></small><br>
                <strong>Tesoureiro (Visto/Recebido)</strong>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-4">
        <p class="small text-muted mb-0">Documento gerado em <?= date('d/m/Y H:i:s') ?> por Módulo de Conferência Ekklesia.</p>
        <p style="font-size: 9px;" class="text-uppercase text-muted">A conferência física deve bater com o Total Geral Conferido acima.</p>
    </div>
</div>

</body>
</html>
