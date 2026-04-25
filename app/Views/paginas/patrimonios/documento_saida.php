<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimônio - <?= $dados['patrimonio_bem_codigo'] ?></title>
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

        /* Listagem de Itens */
        .movimentacao-list { border-top: 2px solid #333; margin-top: 10px; }
        .item-row { display: flex; border-bottom: 1px solid #eee; padding: 18px 0; align-items: flex-start; }
        .item-label { width: 150px; min-width: 150px; font-size: 0.75rem; font-weight: 900; color: #0d6efd; text-transform: uppercase; padding-top: 4px; }
        .item-main { flex-grow: 1; }
        .item-title { font-size: 1.15rem; color: #1a1a1a; font-weight: 600; }

        .observacao-content {
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

        /* Assinaturas */
        .signature-section { margin-top: 60px; display: flex; justify-content: space-around; text-align: center; }
        .signature-box { width: 40%; border-top: 1px solid #333; padding-top: 10px; font-size: 0.9rem; font-weight: bold; }

        /* Controles */
        .btn-float { position: fixed; bottom: 20px; right: 20px; display: flex; gap: 10px; z-index: 1000; }

        @media print {
            body { background: white; padding: 0; }
            .print-container { margin: 0; padding: 0; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
            .observacao-content { background: white !important; border-color: #333 !important; }
        }
    </style>
</head>
<body>

<div class="btn-float no-print">
    <button onclick="window.history.back();" class="btn btn-dark shadow"><i class="bi bi-arrow-left"></i> Voltar</button>
    <button onclick="window.print();" class="btn btn-primary shadow"><i class="bi bi-printer"></i> Imprimir Documento</button>
</div>

<div class="print-container">
    <div class="header-top d-flex justify-content-between align-items-center mb-4">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB">
        </div>

        <div class="text-center flex-grow-1 px-3">
            <h4 class="mb-0 text-uppercase fw-bold"><?= htmlspecialchars($igreja['igreja_nome']) ?></h4>
            <span class="igreja-endereco">
                <?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>
            </span>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/{$_SESSION['usuario_igreja_id']}/logo/{$igreja['igreja_logo']}";
                if(!empty($igreja['igreja_logo']) && file_exists($caminhoLogo)):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB">
            <?php endif; ?>
        </div>
    </div>

    <div class="header-info text-center mb-4">
        <h2 class="display-6 fw-bold">Termo de <?= ucfirst($dados['patrimonio_bem_status']) ?></h2>
        <span class="badge bg-dark px-3 py-2 text-uppercase">
            <i class="bi bi-box-seam me-2"></i>Controle de Patrimônio
        </span>
    </div>

    <div class="movimentacao-list">
        <div class="item-row">
            <div class="item-label">Identificação</div>
            <div class="item-main">
                <span class="item-title"><?= htmlspecialchars($dados['patrimonio_bem_nome']) ?></span>
                <div class="text-muted small">Código: <strong><?= $dados['patrimonio_bem_codigo'] ?></strong></div>
            </div>
        </div>

        <div class="item-row">
            <div class="item-label">Status & Data</div>
            <div class="item-main">
                <span class="item-title text-uppercase"><?= $dados['patrimonio_bem_status'] ?></span>
                <div class="text-muted small">Registrado em: <?= date('d/m/Y H:i', strtotime($dados['patrimonio_movimentacao_data'] ?? 'now')) ?></div>
            </div>
        </div>

        <div class="item-row">
            <div class="item-label">Localização</div>
            <div class="item-main">
                <div class="item-title" style="font-size: 1rem;">
                    Origem: <?= $dados['local_origem'] ?? 'Não informado' ?>
                    <i class="bi bi-arrow-right mx-2"></i>
                    Destino: <?= $dados['local_destino'] ?? 'Saída Externa' ?>
                </div>
            </div>
        </div>

        <div class="item-row" style="border-bottom: none;">
            <div class="item-label">Detalhes / Motivo</div>
            <div class="item-main">
                <div class="observacao-content">
                    <?= nl2br(htmlspecialchars($dados['patrimonio_movimentacao_observacao'] ?: 'Nenhuma observação detalhada foi registrada para esta movimentação.')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            Responsável pelo Patrimônio<br>
            <small class="text-muted fw-normal"><?= htmlspecialchars($igreja['igreja_nome']) ?></small>
        </div>
        <div class="signature-box">
            Responsável pela Retirada/Recebimento<br>
            <small class="text-muted fw-normal">Documento: ____________________</small>
        </div>
    </div>

    <div class="text-center mt-5 text-muted small no-print">
        <hr>
        <p>Documento gerado em <?= date('d/m/Y às H:i:s') ?> pelo Sistema Ekklesia.</p>
    </div>
</div>

</body>
</html>
