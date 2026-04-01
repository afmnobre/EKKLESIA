<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensagem #<?= $mensagem['igreja_mensagem_dominical_num_historico'] ?> - <?= htmlspecialchars($igreja['igreja_nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Padronização de Fundo e Fonte conforme Modelo Liturgia */
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a; }
        .print-container { max-width: 850px; margin: 20px auto; background: white; padding: 50px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* Cabeçalho com Logos */
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .logo-box img { max-height: 70px; object-fit: contain; }

        /* Títulos e Metas */
        .mensagem-titulo {
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -1px;
            color: #1a1a1a;
            margin-top: 10px;
            line-height: 1.1;
        }
        .date-badge { background: #eee; padding: 5px 15px; border-radius: 20px; font-weight: bold; font-size: 0.9rem; color: #444; text-transform: uppercase; }

        /* Conteúdo do Texto (CKEditor) */
        .conteudo-mensagem {
            margin-top: 30px;
            font-size: 1.15rem;
            line-height: 1.7;
            color: #333;
            text-align: justify;
            min-height: 500px;
        }

        /* Ajuste de Tabelas vindas do Editor */
        .conteudo-mensagem table { width: 100% !important; border-collapse: collapse; margin: 20px 0; }
        .conteudo-mensagem table td, .conteudo-mensagem table th { border: 1px solid #dee2e6; padding: 12px; }

        /* Área de Assinatura */
        .assinatura-area { margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; }
        .logo-sarca { height: 30px; opacity: 0.7; margin-right: 10px; }

        /* Controles Flutuantes Padronizados */
        .btn-float { position: fixed; bottom: 20px; right: 20px; display: flex; gap: 10px; z-index: 1000; }

        @media print {
            body { background: white; padding: 0; }
            .print-container { margin: 0; padding: 0; box-shadow: none; max-width: 100%; }
            .no-print { display: none !important; }
            .header-top { border-bottom-color: #000; }
            .conteudo-mensagem { font-size: 12pt; }
        }
    </style>
</head>
<body>

<div class="btn-float no-print">
    <button onclick="window.history.back();" class="btn btn-dark shadow"><i class="bi bi-arrow-left"></i></button>
    <button onclick="window.print();" class="btn btn-primary shadow"><i class="bi bi-printer"></i> Imprimir Mensagem</button>
</div>

<div class="print-container">
    <div class="header-top">
        <div class="logo-box">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB">
        </div>

        <div class="text-center flex-grow-1 px-3">
            <h5 class="mb-0 fw-bold text-uppercase"><?= htmlspecialchars($igreja['igreja_nome']) ?></h5>
            <small class="text-muted d-block" style="font-size: 0.75rem;">
                <?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>
            </small>
        </div>

        <div class="logo-box text-end">
            <?php
                $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                if(!empty($igreja['igreja_logo']) && file_exists($caminhoLogo)):
            ?>
                <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja">
            <?php else: ?>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB">
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mb-5">
        <div class="mensagem-titulo mb-2"><?= htmlspecialchars($mensagem['igreja_mensagem_dominical_titulo']) ?></div>
        <span class="date-badge">
            <i class="bi bi-hash me-1"></i><?= $mensagem['igreja_mensagem_dominical_num_historico'] ?> —
            <i class="bi bi-calendar3 ms-2 me-1"></i><?= date('d/m/Y', strtotime($mensagem['igreja_mensagem_dominical_data'])) ?>
        </span>
    </div>

    <div class="conteudo-mensagem">
        <?= $mensagem['igreja_mensagem_dominical_mensagem'] ?>
    </div>

    <div class="assinatura-area text-end">
        <p class="mb-3"><strong>Autor:</strong> <?= htmlspecialchars($autor) ?></p>

        <div class="d-flex align-items-center justify-content-end text-muted fst-italic small">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-sarca" alt="Sarça">
            <span>"Pela fé, a sarça ainda arde."</span>
        </div>
    </div>
</div>

</body>
</html>
