<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Boletim #<?= $boletim['igreja_boletim_num_historico'] ?> - <?= htmlspecialchars($igreja['igreja_nome']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #fff; font-family: 'Times New Roman', Times, serif; color: #000; }

        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .container { width: 100%; max-width: 100%; }
        }

        /* Estrutura do Cabeçalho com Logos */
        .boletim-header {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .logo-local {
            max-height: 80px;
            max-width: 150px;
            object-fit: contain;
        }

        /* Logo Completo da IPB no Topo Direito */
        .logo-ipb-completo-topo {
            max-height: 45px;
            object-fit: contain;
        }

        .boletim-titulo {
            font-size: 22pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            line-height: 1.1;
        }

        .boletim-meta {
            font-style: italic;
            font-size: 11pt;
            color: #333;
            margin-bottom: 25px;
        }

        .conteudo-boletim {
            font-size: 12pt;
            line-height: 1.5;
            text-align: justify;
            min-height: 600px;
        }

        /* Estilização para tabelas do CKEditor no papel */
        .conteudo-boletim table {
            width: 100% !important;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .conteudo-boletim table td, .conteudo-boletim table th {
            border: 1px solid #000;
            padding: 6px;
        }

        .assinatura-area {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }

        /* Ícone da Sarça no Rodapé */
        .logo-ipb-sarca-rodape {
            max-height: 25px;
            margin-right: 8px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="no-print bg-dark text-white p-3 mb-4 shadow">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-file-earmark-pdf me-2"></i>
            <strong>Boletim Dominical</strong>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary btn-sm px-4">
                Imprimir
            </button>
            <button onclick="window.close()" class="btn btn-outline-light btn-sm ms-2">Fechar</button>
        </div>
    </div>
</div>

<div class="container my-4">
    <?php
        $logoPath = !empty($igreja['igreja_logo'])
            ? url("assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}")
            : url("assets/img/logo_placeholder.png");
    ?>

    <div class="boletim-header">
        <div class="row align-items-center">
            <div class="col-3 text-start">
                <img src="<?= $logoPath ?>" class="logo-local" alt="Logo Igreja">
            </div>

            <div class="col-6 text-center">
                <h3 class="mb-1 fw-bold"><?= htmlspecialchars($igreja['igreja_nome']) ?></h3>
                <p class="mb-0 small">
                    <?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?><br>
                    <?= !empty($igreja['igreja_telefone']) ? 'Telefone: ' . $igreja['igreja_telefone'] : '' ?>
                </p>
            </div>

            <div class="col-3 text-end">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" class="logo-ipb-completo-topo" alt="IPB">
            </div>
        </div>
    </div>

    <div class="text-center mb-4">
        <div class="boletim-titulo"><?= htmlspecialchars($boletim['igreja_boletim_titulo']) ?></div>
        <div class="boletim-meta text-uppercase">
            Boletim Dominical nº <?= $boletim['igreja_boletim_num_historico'] ?> —
            <?= date('d/m/Y', strtotime($boletim['igreja_boletim_data'])) ?>
        </div>
    </div>

    <div class="conteudo-boletim">
        <?= $boletim['igreja_boletim_mensagem'] ?>
    </div>

    <div class="assinatura-area text-end">
        <p class="mb-2"><strong>Escrito por:</strong> <?= htmlspecialchars($autor) ?></p>

        <div class="d-flex align-items-center justify-content-end text-muted italic small">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb-sarca-rodape" alt="Sarça">
            <span>"Pela fé, a sarça ainda arde."</span>
        </div>
    </div>
</div>

</body>
</html>
