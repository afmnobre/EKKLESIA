<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Etiquetas com QR Code - Ekklesia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body { background: #f4f4f4; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

		.page {
			width: 210mm;
			margin: 0 auto;
			background: white;
			display: flex;
			flex-wrap: wrap;
			gap: 10px; /* Espaçamento entre etiquetas */
			justify-content: flex-start;
			padding: 10mm 5mm; /* Margem interna da folha */
		}

		.etiqueta {
			width: 95mm; /* Ajustado para caber 2 por linha com segurança */
			height: 45mm;
			border: 2px solid #000;
			padding: 10px;
			display: flex;
			align-items: center;
			position: relative;
			background: #fff;
			box-sizing: border-box;

			/* IMPEDIR CORTE NO MEIO DA ETIQUETA */
			page-break-inside: avoid;
			break-inside: avoid;
			margin-bottom: 5px; /* Pequena margem para não encostar na de baixo */
		}

		.header-etiqueta {
			display: flex;
			align-items: center;
			gap: 8px;
			border-bottom: 1px solid #eee;
			margin-bottom: 5px;
			padding-bottom: 3px;
		}

		.qr-section {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-right: 15px;
			flex-shrink: 0;
        }

        .qr-container {
            width: 85px;
            height: 85px;
        }

		.logo-igreja {
            max-width: 40px;
            max-height: 25px;
			object-fit: contain;
		}

        .info {
            flex: 1;
            font-size: 11px;
            line-height: 1.3;
            overflow: hidden;
            border-left: 1px solid #eee;
            padding-left: 12px;
        }

		.igreja-nome {
			font-weight: 800;
			text-transform: uppercase;
			font-size: 9px;
			color: #444;
			line-height: 1;
        }

        .livro-titulo {
            font-weight: bold;
            font-size: 13px;
            color: #000;
            margin-bottom: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .detalhe {
            color: #555;
            font-size: 10px;
        }

        .livro-id {
            position: absolute;
            bottom: 5px;
            right: 8px;
            font-size: 9px;
            font-weight: bold;
            color: #000;
            background: #eee;
            padding: 2px 5px;
            border-radius: 3px;
        }

		@media print {
			/* 1. ESCONDE O SIDEBAR E QUALQUER ELEMENTO DE INTERFACE */
			.sidebar,
			.no-print,
			nav,
			header,
			footer,
			.navbar,
			button {
				display: none !important;
				width: 0 !important;
				height: 0 !important;
				overflow: hidden !important;
			}

			/* 2. FAZ O CONTEÚDO OCUPAR A TELA TODA (Remove o recuo do sidebar) */
			body,
			.main-content,
			.content-wrapper,
			.wrapper,
			main {
				margin: 0 !important;
				padding: 0 !important;
				left: 0 !important;
				width: 100% !important;
				display: block !important;
				position: static !important;
			}

			/* 3. POSICIONA AS ETIQUETAS NO TOPO DA PÁGINA */
			.page {
				position: absolute;
				top: 0;
				left: 0;
				width: 210mm !important; /* Força largura de A4 */
				margin: 0 !important;
				padding: 0 !important;
				visibility: visible !important;
			}
		}
    </style>
</head>
<body>

<div class="container-fluid py-4 no-print">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="bi bi-filter-right me-2"></i>Filtros para Impressão de Etiquetas</h5>
                <a href="<?= url('biblioteca') ?>" class="btn btn-sm btn-outline-secondary">Voltar ao Acervo</a>
            </div>

            <form method="GET" action="<?= url('biblioteca/imprimirEtiquetasQr') ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Título do Livro</label>
                    <input type="text" name="titulo" class="form-control form-control-sm" placeholder="Ex: C.S. Lewis" value="<?= $_GET['titulo'] ?? '' ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Autor</label>
                    <select name="autor" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <?php foreach($filtros['autores'] as $a): ?>
                            <option value="<?= $a['livro_autor'] ?>" <?= ($_GET['autor'] ?? '') == $a['livro_autor'] ? 'selected' : '' ?>>
                                <?= $a['livro_autor'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Editora</label>
                    <select name="editora" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <?php foreach($filtros['editoras'] as $e): ?>
                            <?php if(!empty($e['livro_editora'])): ?>
                                <option value="<?= $e['livro_editora'] ?>" <?= ($_GET['editora'] ?? '') == $e['livro_editora'] ? 'selected' : '' ?>>
                                    <?= $e['livro_editora'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Categoria</label>
                    <select name="categoria" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <?php foreach($filtros['categorias'] as $c): ?>
                            <?php
                                // Ajuste conforme o nome da coluna no seu banco (categoria_nome ou livro_categoria)
                                $catNome = $c['categoria_nome'] ?? $c['livro_categoria'];
                            ?>
                            <option value="<?= $catNome ?>" <?= ($_GET['categoria'] ?? '') == $catNome ? 'selected' : '' ?>>
                                <?= $catNome ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark w-100 btn-sm">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                    <button type="button" onclick="window.print()" class="btn btn-primary w-100 btn-sm">
                        <i class="bi bi-printer me-1"></i> Imprimir
                    </button>
                </div>
            </form>

            <?php if(count($livros) > 0): ?>
                <div class="mt-3 text-muted small border-top pt-2">
                    <i class="bi bi-info-circle me-1"></i> <b><?= count($livros) ?></b> etiquetas prontas para impressão.
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-3 mb-0 py-2 small">
                    Nenhum livro encontrado com os filtros selecionados.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="page">
    <?php foreach($livros as $livro): ?>
        <div class="etiqueta">
            <div class="qr-section">
                <div class="qr-container" data-id="<?= $livro['livro_id'] ?>"></div>
            </div>

            <div class="info">
                <div class="header-etiqueta">
                    <?php if(!empty($igreja['igreja_logo'])): ?>
                        <img src="<?= url('assets/uploads/' . $igreja['igreja_id'] . '/logo/' . $igreja['igreja_logo']) ?>" class="logo-igreja">
                    <?php endif; ?>
                    <span class="igreja-nome"><?= mb_strimwidth($igreja['igreja_nome'], 0, 40, "...") ?></span>
                </div>

                <div class="livro-titulo"><?= $livro['livro_titulo'] ?></div>
                <div class="detalhe"> <strong>Autor:</strong> <?= mb_strimwidth($livro['livro_autor'] ?? '', 0, 30, "...") ?></div>
                <div class="detalhe"> <strong>Cat:</strong> <?= $livro['livro_categoria'] ?? '' ?></div>
                <div class="detalhe"> <strong>Editora:</strong> <?= mb_strimwidth($livro['livro_editora'] ?? '', 0, 25, "...") ?></div>
            </div>

            <div class="livro-id">ID: <?= str_pad($livro['livro_id'], 5, '0', STR_PAD_LEFT) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const qrs = document.querySelectorAll('.qr-container');

        qrs.forEach(container => {
            const idLivro = container.getAttribute('data-id');

            new QRCode(container, {
                text: idLivro,
                width: 85,
                height: 85,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    });
</script>

</body>
</html>
