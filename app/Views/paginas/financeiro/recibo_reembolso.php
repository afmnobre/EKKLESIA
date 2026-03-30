<?php
function valorPorExtenso($valor) {
    $f = new NumberFormatter("pt-BR", NumberFormatter::SPELLOUT);
    return mb_strtoupper($f->format($valor), 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo EKKLESIA #<?= $conta['financeiro_conta_id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        @page { size: A4; margin: 0; }
        body { background: #525659; padding: 30px; font-family: 'Arial', sans-serif; }

        .recibo-container {
            background: #fff;
            width: 210mm;
            min-height: 148mm; /* Meia folha A4 */
            margin: 0 auto;
            padding: 40px;
            position: relative;
            border: 1px solid #ddd;
        }

        .topo-verde {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: #006437; /* Verde IPB */
        }

        .valor-box {
            border: 2px solid #006437;
            padding: 10px 20px;
            font-size: 1.6rem;
            font-weight: 900;
            color: #006437;
            display: inline-block;
        }

        .titulo {
            font-weight: 900;
            color: #333;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .corpo { font-size: 1.2rem; line-height: 2; text-align: justify; }
        .extenso { font-weight: bold; text-decoration: underline; }

        .assinatura-area {
            margin-top: 60px;
            text-align: center;
        }

        .linha { border-top: 1px solid #000; width: 300px; margin: 10px auto; }

        @media print {
            body { background: #fff; padding: 0; }
            .recibo-container { border: none; margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container no-print mb-4 text-center">
    <button onclick="window.print()" class="btn btn-success btn-lg shadow">
        <i class="bi bi-printer-fill me-2"></i> IMPRIMIR RECIBO
    </button>
</div>

<div class="recibo-container shadow">
    <div class="topo-verde"></div>

    <div class="d-flex justify-content-between align-items-center mb-5">
        <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" style="max-height: 70px;">
        <div class="valor-box">R$ <?= number_format($conta['financeiro_conta_valor'], 2, ',', '.') ?></div>
    </div>

    <h2 class="titulo text-center">Recibo de Reembolso</h2>

    <div class="corpo mt-4">
        <p>Recebemos de <strong><?= $igreja['igreja_nome'] ?? 'IGREJA PRESBITERIANA' ?></strong>, a quantia supra de
        <span class="extenso">(<?= valorPorExtenso($conta['financeiro_conta_valor']) ?>)</span>,
        referente ao reembolso de despesa com <strong>"<?= $conta['financeiro_conta_descricao'] ?>"</strong>,
        pela classificação <strong><?= $conta['subcategoria_nome'] ?></strong>.</p>
    </div>

	<div class="text-end mt-5">
		<?php
			$meses = [
				1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
				5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
				9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
			];
			$dia = date('d');
			$mes = $meses[(int)date('m')];
			$ano = date('Y');
		?>
		<p><?= $dia ?> de <?= $mes ?> de <?= $ano ?></p>
	</div>

	<div class="row mt-5 pt-4 text-center">
		<div class="col-6">
			<div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
			<p class="mb-0 fw-bold small text-uppercase">Assinatura do Favorecido</p>
			<p class="text-muted" style="font-size: 0.8rem;">CPF: ___________________</p>
		</div>

		<div class="col-6">
			<div style="border-top: 1px solid #000; width: 80%; margin: 0 auto;"></div>
			<p class="mb-0 fw-bold small text-uppercase"><?= $tesoureiro ?></p>
			<p class="text-muted" style="font-size: 0.8rem;">Tesoureiro</p>
		</div>
	</div>

	<div class="mt-5 pt-3 text-center text-muted border-top" style="font-size: 0.7rem;">
		Este documento é parte integrante da prestação de contas da <strong><?= $igreja['nome'] ?? 'Igreja Presbiteriana' ?></strong>.
		<br>Gerado via EKKLESIA em <?= date('d/m/Y H:i') ?>
	</div>
</div>

</body>
</html>
