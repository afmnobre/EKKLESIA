<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>EKKLESIA - Etiquetas <?= $local_nome ?></title>
    <style>
        /* Estilo Global para Visualização em Tela */
        body { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f0f0; }

        @page {
            size: A4;
            margin: 10mm;
        }

        @media print {
            /* ISOLAMENTO TOTAL: Esconde Sidebar, Headers e Footers do Sistema */
            header, footer, nav, .sidebar, .navbar, .breadcrumb, .no-print, #sidebar-wrapper {
                display: none !important;
                height: 0 !important;
                width: 0 !important;
                visibility: hidden !important;
            }

            body {
                background-color: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .folha-a4 {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                border: none !important;
            }

            .etiqueta {
                border: 2px solid #000 !important; /* Borda levemente mais grossa para o corte */
                page-break-inside: avoid;
            }
        }

        /* Container da Folha */
        .folha-a4 {
            width: 190mm;
            margin: 20px auto;
            background: #fff;
            padding: 5mm;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            display: flex;
            flex-wrap: wrap;
            gap: 4mm;
            justify-content: flex-start;
        }

        /* Layout da Etiqueta */
        .etiqueta {
            width: 60mm;
            height: 30mm;
            border: 1px solid #333;
            border-radius: 4px;
            display: flex;
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
        }

        /* LADO ESQUERDO: Informações */
        .etiqueta-info {
            width: 65%;
            padding: 2mm;
            display: flex;
            flex-direction: column;
            border-right: 1px dashed #ccc;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 2mm;
            margin-bottom: 1.5mm;
        }

        .etiqueta-logo {
            height: 5mm;
            filter: grayscale(1);
        }

        .etiqueta-id {
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .etiqueta-nome {
            font-size: 9.5px;
            font-weight: bold;
            color: #111;
            line-height: 1.2;
            margin-bottom: 1mm;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Quebra linha 1 e 2 */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .etiqueta-desc {
            font-size: 8px;
            color: #444;
            line-height: 1.1;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Quebra linha 1, 2 e 3 */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* LADO DIREITO: QR Code */
        .etiqueta-qrcode {
            width: 35%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fafafa;
            padding: 1mm;
        }

        .etiqueta-qrcode img {
            width: 18mm;
            height: 18mm;
            background: #fff;
        }

        /* Botão Flutuante */
        .btn-print {
            position: fixed; bottom: 30px; right: 30px;
            background-color: #1c452e; color: #fff; border: none;
            padding: 15px 30px; border-radius: 50px; cursor: pointer;
            box-shadow: 0 5px 10px rgba(0,0,0,0.3); font-weight: bold;
            z-index: 1000; display: flex; align-items: center; gap: 10px;
            transition: 0.3s;
        }
        .btn-print:hover { background-color: #2a6343; }
    </style>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print();">
        <span>🖨️</span> Imprimir Etiquetas (A4)
    </button>

    <div class="folha-a4">
        <?php foreach ($bens as $b):
            $urlDestino = url("patrimonios/detalhes/{$b['patrimonio_bem_id']}");
            $qrCodeUrl = "https://quickchart.io/qr?text=" . urlencode($urlDestino) . "&size=150&margin=0";
        ?>
            <div class="etiqueta">
                <div class="etiqueta-info">
                    <div class="header-info">
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" class="etiqueta-logo" alt="Logo">
                        <span class="etiqueta-id"><?= !empty($b['patrimonio_bem_codigo']) ? $b['patrimonio_bem_codigo'] : '#'.$b['patrimonio_bem_id'] ?></span>
                    </div>

                    <div class="etiqueta-nome">
                        <?= strtoupper($b['patrimonio_bem_nome']) ?>
                    </div>

                    <div class="etiqueta-desc">
                        <?= $b['patrimonio_bem_description'] ?? $b['patrimonio_bem_descricao'] ?>
                    </div>
                </div>

                <div class="etiqueta-qrcode">
                    <img src="<?= $qrCodeUrl ?>" alt="QR Code">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
