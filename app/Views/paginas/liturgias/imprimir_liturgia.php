<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liturgia - <?= date('d/m/Y', strtotime($liturgia['igreja_liturgia_data'])) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Arial, sans-serif; }
        .print-container { max-width: 850px; margin: 20px auto; background: white; padding: 50px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* Cabeçalho com Logos */
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .logo-box img { max-height: 70px; object-fit: contain; }

        /* Estilo do Endereço no Cabeçalho */
        .igreja-endereco { font-size: 0.75rem; color: #666; display: block; margin-top: 2px; line-height: 1.2; }

        .header-info { text-align: center; margin-bottom: 40px; }
        .header-info h2 { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #1a1a1a; margin-bottom: 5px; }
        .date-badge { background: #eee; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; color: #444; }

        /* Blocos de Dirigente e Pregador */
        .staff-box { background: #f9f9f9; border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 30px; display: flex; justify-content: space-around; }
        .staff-item { text-align: center; }
        .staff-label { display: block; font-size: 0.75rem; text-transform: uppercase; color: #777; font-weight: bold; margin-bottom: 2px; }
        .staff-name { font-size: 1.1rem; font-weight: 600; color: #222; }

        /* Lista de Liturgia */
        .liturgia-list { border-top: 2px solid #333; margin-top: 10px; }

        .item-row {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 18px 0;
            align-items: flex-start;
        }

        .item-type {
            width: 130px;
            min-width: 130px;
            font-size: 0.75rem;
            font-weight: 900;
            color: #0d6efd;
            text-transform: uppercase;
            padding-top: 4px;
        }

        .item-main { flex-grow: 1; }
        .item-title { font-size: 1.15rem; color: #1a1a1a; font-weight: 600; }
        .item-ref { font-style: italic; color: #0d6efd; font-weight: 700; font-size: 1rem; margin-left: 8px; }

        .biblia-content {
            margin-top: 12px;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
            border-radius: 0 8px 8px 0;
            white-space: pre-wrap;
        }

        /* Controles */
        .btn-float { position: fixed; bottom: 20px; right: 20px; display: flex; gap: 10px; z-index: 1000; }

        @media print {
            body { background: white; padding: 0; }
            .print-container { margin: 0; padding: 0; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
            .staff-box { border: 1px solid #ccc; background: white !important; }
            .biblia-content { background: white !important; border-color: #333 !important; }
        }

        .staff-photo, .staff-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }

        .staff-placeholder {
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 2rem;
        }
    </style>
</head>
<body>

<div class="btn-float no-print">
    <button onclick="window.history.back();" class="btn btn-dark shadow"><i class="bi bi-arrow-left"></i></button>
    <button onclick="window.print();" class="btn btn-primary shadow"><i class="bi bi-printer"></i> Imprimir Liturgia</button>
</div>

<div class="print-container">
    <div class="header-top d-flex justify-content-between align-items-center mb-4">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" style="height: 70px;">
        </div>

        <div class="text-center flex-grow-1 px-3">
            <h4 class="mb-0 text-uppercase fw-bold"><?= htmlspecialchars($liturgia['igreja_nome']) ?></h4>
            <span class="igreja-endereco">
                <?= htmlspecialchars($liturgia['igreja_endereco'] ?? '') ?>
            </span>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/{$liturgia['igreja_id']}/logo/{$liturgia['igreja_logo']}";
                if(!empty($liturgia['igreja_logo']) && file_exists($caminhoLogo)):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja" style="max-height: 70px;">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" style="height: 70px;">
            <?php endif; ?>
        </div>
    </div>

    <div class="header-info text-center mb-4">
        <h2 class="display-6 fw-bold"><?= htmlspecialchars($liturgia['igreja_liturgia_tema'] ?: 'Ordem de Culto') ?></h2>
        <span class="badge bg-dark px-3 py-2">
            <i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y', strtotime($liturgia['igreja_liturgia_data'])) ?>
            às <?= date('H:i', strtotime($liturgia['igreja_liturgia_data'])) ?>h
        </span>
    </div>

    <div class="staff-box d-flex justify-content-around p-4 border rounded bg-light mb-4">

        <div class="staff-item text-center">
            <?php
                $caminhoDir = "assets/uploads/{$liturgia['igreja_id']}/membros/{$liturgia['registro_dirigente']}/{$liturgia['foto_dirigente']}";

                if(!empty($liturgia['foto_dirigente']) && file_exists($caminhoDir)): ?>
                    <img src="<?= url($caminhoDir) ?>" class="staff-photo" alt="Dirigente">
                <?php else: ?>
                    <div class="staff-placeholder"><i class="bi bi-person-fill"></i></div>
                <?php endif; ?>

            <small class="staff-label">Dirigente</small>
            <span class="staff-name">
                <?= htmlspecialchars($liturgia['nome_membro_dirigente'] ?? $liturgia['igreja_liturgia_dirigente_nome'] ?? 'A definir'); ?>
            </span>
        </div>

        <div class="staff-item text-center">
            <?php
                $caminhoPreg = "assets/uploads/{$liturgia['igreja_id']}/membros/{$liturgia['registro_pregador']}/{$liturgia['foto_pregador']}";

                if(!empty($liturgia['foto_pregador']) && file_exists($caminhoPreg)): ?>
                    <img src="<?= url($caminhoPreg) ?>" class="staff-photo" alt="Pregador">
                <?php else: ?>
                    <div class="staff-placeholder"><i class="bi bi-person-fill"></i></div>
                <?php endif; ?>

            <small class="staff-label">Pregador</small>
            <span class="staff-name">
                <?= htmlspecialchars($liturgia['nome_membro_pregador'] ?? $liturgia['igreja_liturgia_pregador_nome'] ?? 'A definir'); ?>
            </span>
        </div>

    </div>

	<div class="liturgia-list">
		<?php
		// Garante que usaremos os itens processados que vieram do controller
		$listaItens = $itens ?? $liturgia['itens'] ?? [];
		foreach ($listaItens as $item):
			$tipoLower = strtolower($item['tipo'] ?? '');
		?>
			<div class="item-row">
				<div class="item-main">
					<span class="item-title"><?= htmlspecialchars($item['desc']) ?></span>

					<?php if(!empty($item['ref'])): ?>
						<span class="item-ref">(<?= htmlspecialchars($item['ref']) ?>)</span>
					<?php endif; ?>

					<?php if(!empty($item['conteudo'])): ?>
						<div class="biblia-content"><?= nl2br(htmlspecialchars($item['conteudo'])) ?></div>
					<?php endif; ?>

					<?php if($tipoLower == 'hino' && !empty($item['hino_letra'])): ?>
                    <div class="biblia-content" style="border-left-color: #198754; font-family: 'Verdana', sans-serif; padding: 20px;">
                        <div class="fw-bold mb-2 text-uppercase small" style="color: #198754; border-bottom: 1px solid #eee; padding-bottom: 5px;"><?= htmlspecialchars($item['hino_titulo']) ?></div>
                        <div style="white-space: pre-wrap; margin-top: 10px; line-height: 1.6; color: #333;"><?= htmlspecialchars($item['hino_letra']) ?></div>
                    </div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

</body>
</html>
