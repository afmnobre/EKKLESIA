<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Diário - <?= date('d/m/Y', strtotime($data)) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: white; font-size: 13px; color: #000; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .header-container { border-bottom: 3px solid #212529; padding-bottom: 15px; margin-bottom: 25px; }

        /* Ajuste de Logos */
        .logo-local { height: 70px; width: auto; object-fit: contain; }
        .logo-ipb { height: 50px; width: auto; opacity: 0.9; }

        .nome-igreja { font-size: 18px; font-weight: 800; text-transform: uppercase; color: #1a1a1a; margin-bottom: 0; }
        .subtitulo-relatorio { font-size: 11px; text-uppercase; letter-spacing: 2px; color: #666; font-weight: 600; }

        .table-conferencia thead th {
            background: #f8f9fa !important;
            border-bottom: 2px solid #212529;
            color: #000;
            text-transform: uppercase;
            font-size: 10px;
            padding: 10px;
        }

        .signature-box {
            border-top: 1px solid #000;
            margin-top: 50px;
            text-align: center;
            padding-top: 8px;
            font-weight: bold;
            min-height: 90px;
        }

        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 15px; }
            .container { max-width: 100% !important; width: 100% !important; }
            .table-secondary { background-color: #efefef !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container mt-4">
    <div class="row align-items-center header-container">
		<div class="col-3 text-start">
			<?php
				$igrejaId = $_SESSION['usuario_igreja_id'];
				$logoIgreja = $movimentos[0]['igreja_logo'] ?? '';

				if (!empty($logoIgreja)):
					$caminhoLogo = "assets/uploads/{$igrejaId}/logo/" . $logoIgreja;
			?>
				<img src="<?= url($caminhoLogo) ?>" class="logo-local" alt="Logo Local">
			<?php else: ?>
				<img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-local" alt="IPB">
			<?php endif; ?>
		</div>

		<div class="col-6 text-center">
			<h2 class="nome-igreja">
				<?= htmlspecialchars($movimentos[0]['igreja_nome'] ?? 'Igreja Presbiteriana') ?>
			</h2>
			<p class="subtitulo-relatorio mb-2">Conferência Diária de Receitas</p>
			<span class="badge bg-dark px-3 py-2" style="font-size: 12px;">
				MOVIMENTO DE: <?= date('d/m/Y', strtotime($data)) ?>
			</span>
		</div>

        <div class="col-3 text-end">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" class="logo-ipb" alt="IPB Logo">
        </div>
    </div>

    <table class="table table-bordered table-conferencia">
        <thead>
            <tr>
                <th width="25%">Classificação</th>
                <th>Descrição / Ofertante</th>
                <th width="18%" class="text-end">Valor (R$)</th>
            </tr>
        </thead>
		<tbody>
			<?php
			$totalGeral = 0;
			if(!empty($movimentos)):
				foreach($movimentos as $m):
					$valorLinha = $m['valor_exibir'] ?? 0;
					$totalGeral += $valorLinha;
			?>
				<tr>
					<td class="align-middle">
						<div class="fw-bold text-uppercase" style="font-size: 10px; color: #333;">
							<?= htmlspecialchars($m['categoria_pai'] ?? 'GERAL') ?>
						</div>
						<div class="text-muted" style="font-size: 11px;">
							<?= htmlspecialchars($m['subcategoria'] ?? '-') ?>
						</div>
					</td>

					<td class="align-middle">
						<?php if (!empty($m['ofertante'])): ?>
							<div class="fw-bold text-dark"><?= htmlspecialchars($m['ofertante']) ?></div>
							<div class="text-muted" style="font-size: 10px;">
								<i class="bi bi-tag me-1"></i><?= htmlspecialchars($m['financeiro_conta_descricao']) ?>
							</div>
						<?php else: ?>
							<div class="text-dark fw-bold"><?= htmlspecialchars($m['financeiro_conta_descricao']) ?></div>
						<?php endif; ?>

						<div class="mt-1">
							<span style="font-size: 9px; padding: 2px 5px; background: #f0f0f0; border-radius: 3px; border: 1px solid #ddd; color: #555;">
								<i class="bi bi-wallet2 me-1"></i> <strong>DESTINO:</strong> <?= htmlspecialchars($m['destino'] ?? 'CAIXA GERAL') ?>
							</span>
						</div>
					</td>

					<td class="text-end align-middle fw-bold" style="font-size: 14px;">
						<small class="fw-normal">R$</small> <?= number_format($valorLinha, 2, ',', '.') ?>
					</td>
				</tr>
			<?php
				endforeach;
			else:
			?>
				<tr>
					<td colspan="3" class="text-center py-5 text-muted">
						<i class="bi bi-exclamation-circle d-block mb-2" style="font-size: 2rem;"></i>
						Nenhum lançamento encontrado para esta data.
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
        <tfoot>
            <tr class="table-secondary">
                <td colspan="2" class="text-end fw-bold py-2">TOTAL GERAL DE ENTRADAS:</td>
                <td class="text-end fw-bold py-2" style="font-size: 16px;">
                    R$ <?= number_format($totalGeral, 2, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 pt-4">
        <div class="col-4 text-center">
            <div class="signature-box">
                <small class="d-block text-muted text-uppercase mb-2" style="font-size: 9px;">Tesouraria</small>
                <div class="border-bottom mx-3 mb-1"></div>
                <span style="font-size: 11px;"><?= htmlspecialchars($tesoureiro ?? 'Responsável') ?></span>
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="signature-box">
                <small class="d-block text-muted text-uppercase mb-2" style="font-size: 9px;">1º Diácono de Dia</small>
                <div class="border-bottom mx-3 mb-1"></div>
                <span style="font-size: 11px;"><?= htmlspecialchars($conferente1 ?: 'Assinatura') ?></span>
            </div>
        </div>
        <div class="col-4 text-center">
            <div class="signature-box">
                <small class="d-block text-muted text-uppercase mb-2" style="font-size: 9px;">2º Diácono de Dia</small>
                <div class="border-bottom mx-3 mb-1"></div>
                <span style="font-size: 11px;"><?= htmlspecialchars($conferente2 ?: 'Assinatura') ?></span>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 no-print">
        <hr>
        <button onclick="window.print()" class="btn btn-dark px-5 py-2 fw-bold">
            <i class="bi bi-printer me-2"></i>IMPRIMIR AGORA
        </button>
        <button onclick="window.close()" class="btn btn-link text-muted ms-3">Fechar</button>
    </div>
</div>

</body>
</html>
