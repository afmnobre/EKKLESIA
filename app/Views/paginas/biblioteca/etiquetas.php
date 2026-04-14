<div class="container-fluid py-4 no-print">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-filter-right me-2"></i>Filtros para Impressão</h5>
            <form method="GET" action="<?= url('biblioteca/imprimirEtiquetas') ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Título do Livro</label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ex: Cristianismo Puro e Simples" value="<?= $_GET['titulo'] ?? '' ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-bold">Autor</label>
                    <select name="autor" class="form-select">
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
                    <select name="editora" class="form-select">
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
					<select name="categoria" class="form-select">
						<option value="">Todas</option>
						<?php foreach($filtros['categorias'] as $c): ?>
							<option value="<?= $c['categoria_nome'] ?>" <?= ($_GET['categoria'] ?? '') == $c['categoria_nome'] ? 'selected' : '' ?>>
								<?= $c['categoria_nome'] ?>
							</option>
						<?php endforeach; ?>
					</select>
                </div>

                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-search"></i>
                    </button>
                    <button type="button" onclick="window.print()" class="btn btn-primary w-100">
                        <i class="bi bi-printer"></i>
                    </button>
                </div>
            </form>
            <?php if(count($livros) > 0): ?>
                <div class="mt-2 text-muted small">
                    <i class="bi bi-info-circle me-1"></i> Foram encontrados <b><?= count($livros) ?></b> livros para este filtro.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="print-area">
    <div class="grid-etiquetas">
        <?php foreach ($livros as $livro): ?>
            <div class="etiqueta-wrapper">
                <div class="etiqueta-card">
                    <div class="header-etiqueta">
                        <div class="d-flex align-items-center w-100">
                            <?php if(!empty($logoIgreja)): ?>
                                <img src="<?= url("assets/uploads/{$igreja_id}/logo/{$logoIgreja}") ?>" class="logo-igreja-etiqueta">
                            <?php endif; ?>
                            <div class="flex-grow-1 ms-2">
                                <div class="nome-igreja"><?= mb_strtoupper($nomeIgreja) ?></div>
                                <div class="projeto-tag">BIBLIOTECA COMUNITÁRIA</div>
                            </div>
                            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-sarca-etiqueta">
                        </div>
                    </div>

                    <div class="dados-livro mt-2">
                        <div class="id-badge">REGISTRO: #<?= str_pad($livro['livro_id'], 4, '0', STR_PAD_LEFT) ?></div>
                        <div class="titulo-etiqueta"><?= mb_strimwidth($livro['livro_titulo'], 0, 50, "...") ?></div>
                        <div class="info-detalhe"><strong>AUTOR:</strong> <?= $livro['livro_autor'] ?></div>
                        <div class="info-detalhe"><strong>EDITORA:</strong> <?= $livro['livro_editora'] ?? '---' ?></div>
                    </div>

                    <div class="controle-manual">
                        <div class="row-header">
                            <div class="col-nome">LEITOR / CONTATO</div>
                            <div class="col-data">DATA</div>
                            <div class="col-data">DEV.</div>
                        </div>
                        <?php for($i=0; $i<6; $i++): ?>
                            <div class="row-vazia">
                                <div class="col-nome"></div>
                                <div class="col-data"></div>
                                <div class="col-data"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div class="footer-obs">EKKLESIA - Gestão de Acervo Pastoral</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
/* --- ESTILOS BASE (PROPORÇÃO NORMAL) --- */
.etiqueta-wrapper {
    width: 100mm;
    height: 143mm;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border: 1px dashed #ccc; /* Guia de corte no browser */
}

.etiqueta-card {
    width: 82mm; /* Base menor para o scale não estourar */
    height: 118mm;
    padding: 5mm;
    border: 1px solid #000;
    display: flex;
    flex-direction: column;
    background: white;
    box-sizing: border-box;
    /* AMPLIAÇÃO PROPORCIONAL DE 20% EM TUDO */
    transform: scale(1.2);
    transform-origin: center center;
}

/* Elementos Internos com tamanhos base */
.logo-igreja-etiqueta { height: 30px; }
.logo-sarca-etiqueta { height: 28px; }
.nome-igreja { font-weight: 800; font-size: 0.75rem; line-height: 1.1; color: #000; }
.projeto-tag { font-size: 0.5rem; font-weight: bold; color: #666; }
.id-badge { font-size: 0.6rem; font-weight: bold; background: #eee; padding: 1px 4px; border: 1px solid #ccc; display: inline-block; margin-bottom: 2mm; }
.titulo-etiqueta { font-weight: bold; font-size: 0.85rem; line-height: 1.2; height: 2.4em; overflow: hidden; color: #000; }
.info-detalhe { font-size: 0.65rem; }
.controle-manual { border: 1.5px solid #000; flex-grow: 1; display: flex; flex-direction: column; margin-top: 2mm; }
.row-header { display: flex; background: #f0f0f0; font-size: 0.55rem; font-weight: bold; border-bottom: 1.5px solid #000; }
.row-vazia { display: flex; flex-grow: 1; border-bottom: 1px solid #000; }
.col-nome { width: 60%; border-right: 1px solid #000; }
.col-data { width: 20%; border-right: 1px solid #000; }
.col-data:last-child { border-right: none; }
.header-etiqueta { border-bottom: 2px solid #000; padding-bottom: 2mm; margin-bottom: 2mm; }
.footer-obs { text-align: center; font-size: 0.55rem; font-weight: bold; margin-top: 1.5mm; }

/* --- CONFIGURAÇÃO DA GRADE A4 --- */
#print-area {
    width: 210mm;
    margin: 0 auto;
    background: white;
}

.grid-etiquetas {
    display: grid;
    grid-template-columns: 105mm 105mm; /* 2 Colunas */
    grid-auto-rows: 148.5mm; /* 2 Linhas */
}

/* --- REGRAS DE IMPRESSÃO --- */
@media print {
    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 210mm !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .etiqueta-wrapper {
        border: 1px solid #000 !important; /* Linha de corte final */
        page-break-inside: avoid;
    }
}
</style>
