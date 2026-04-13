<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canais de Acesso - <?= htmlspecialchars($igreja['igreja_nome'] ?? 'EKKLESIA') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .qr-card { border: none; border-top: 3px solid #0d6efd; }
        .qr-code { width: 180px; height: 180px; background: #fff; padding: 5px; }
        .header-container { border-bottom: 2px solid #dee2e6; margin-bottom: 30px; padding-bottom: 15px; }

        .logo-igreja { max-height: 70px; width: auto; }
        .logo-sarca { max-height: 50px; width: auto; }
        .logo-sarca-completa { max-height: 40px; width: auto; }

        @media print {
            @page { size: landscape; margin: 1cm; }
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .container { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
            .row { display: flex !important; flex-wrap: wrap !important; }
            .col-print-6 { width: 50% !important; flex: 0 0 50% !important; max-width: 50% !important; }
            .card { border: 1px solid #eee !important; box-shadow: none !important; margin-bottom: 10px !important; break-inside: avoid; }
            .qr-code { width: 160px !important; height: 160px !important; }
            .header-container { margin-bottom: 20px !important; }
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="header-container">
        <div class="row align-items-center">
            <div class="col-3">
                <?php if (!empty($igreja['igreja_logo'])): ?>
                    <img src="<?= url('assets/uploads/' . $igreja['igreja_id'] . '/logo/' . $igreja['igreja_logo']) ?>" class="logo-igreja">
                <?php endif; ?>
            </div>
            <div class="col-6 text-center">
                <h3 class="fw-bold mb-0">Canais de Acesso</h3>
                <h6 class="text-secondary mb-1"><?= htmlspecialchars($igreja['igreja_nome']) ?></h6>
                <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-sarca">
            </div>
            <div class="col-3 text-end">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" class="logo-sarca-completa">
            </div>
        </div>
    </div>

    <div class="row g-3">
        <?php
        $listaCanais = $canais ?? [];

        // ADICIONANDO O PORTAL DE CONFERÊNCIA MANUALMENTE NA LISTA
        $listaCanais[] = [
            'titulo' => 'Conferência de Dízimos',
            'url'    => full_url('dizimoOferta/login'), // A URL do seu novo MVC
            'icon'   => 'bi-shield-check',
            'bg'     => 'bg-dark'
        ];

        foreach ($listaCanais as $item):
            $qrCodeUrl = "https://quickchart.io/qr?text=" . urlencode($item['url']) . "&size=300&margin=1";
        ?>
        <div class="col-md-6 col-lg-3 col-print-6">
            <div class="card h-100 shadow-sm qr-card text-center" style="border-top-color: <?= $item['bg'] == 'bg-dark' ? '#212529' : '' ?>;">
                <div class="card-body p-3">
                    <div class="mb-2">
                        <i class="bi <?= $item['icon'] ?> fs-4 <?= str_replace('bg-', 'text-', $item['bg']) ?>"></i>
                        <span class="fw-bold d-block small mt-1 text-uppercase"><?= htmlspecialchars($item['titulo']) ?></span>
                    </div>

                    <div class="mb-2">
                        <a href="<?= $item['url'] ?>" target="_blank">
                            <img src="<?= $qrCodeUrl ?>" alt="QR Code" class="qr-code border rounded shadow-sm">
                        </a>
                    </div>

                    <div class="small text-muted font-monospace" style="font-size: 0.65rem; word-break: break-all;">
                        <?= str_replace(['https://', 'http://'], '', $item['url']) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-5 text-center no-print">
        <button onclick="window.print()" class="btn btn-dark btn-lg px-5 shadow">
            <i class="bi bi-printer me-2"></i> Imprimir em Paisagem (2x2)
        </button>
        <p class="mt-3 text-muted small">
            Dica: Na tela de impressão, verifique se a <strong>Orientação</strong> está como <strong>Paisagem</strong>.
        </p>
    </div>
</div>

</body>
</html>
