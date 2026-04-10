<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Ekklesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/picmo@5.8.5/dist/umd/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@picmo/popup-picker@5.8.5/dist/umd/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        /* Estrutura Principal */
        .sidebar-scroll { max-height: 90vh; overflow-y: auto; padding-right: 5px; }
        .canvas-wrapper {
            background: #2c2c2c; padding: 20px; border-radius: 12px;
            display: flex; justify-content: center; align-items: center;
            overflow: hidden; box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
            min-height: 700px; position: relative;
        }

        .canvas-container { max-width: 100% !important; height: auto !important; aspect-ratio: 1 / 1; }
        canvas { border: 1px solid #000; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 100% !important; height: auto !important; }

        /* Accordion e Botões */
        .accordion-button { font-size: 0.85rem; font-weight: 600; padding: 12px 15px; }
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: #0d6efd; }
        .tool-btn { margin-bottom: 6px; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .input-file-hidden { width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; }

        /* Listas e Itens */
        .sociedade-item { background: #fff; border: 1px solid #dee2e6; border-radius: 8px; padding: 8px; margin-bottom: 8px; }
        .sociedade-header { display: flex; align-items: center; gap: 8px; margin-bottom: 5px; }
        .sociedade-header img { height: 24px; width: 24px; object-fit: contain; border-radius: 3px; background: #f8f9fa; }
        .sociedade-header span { font-weight: 600; font-size: 0.75rem; color: #333; }

        /* Controles Dinâmicos (Texto e Imagem) */
        .dynamic-controls { margin-top: 15px; }
        .control-group { display: flex; align-items: center; gap: 5px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; flex-wrap: wrap; }
        .control-group:last-child { border-bottom: none; }
        .font-select { font-size: 0.75rem; }
        .size-input { width: 65px; font-size: 0.75rem; }

        /* Editor de Imagem */
        .control-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; gap: 8px; }
        .control-label { flex: 0 0 70px; font-size: 0.7rem; font-weight: 600; color: #666; text-align: right; }
        .control-slider { flex: 1; height: 4px; }
        .control-value { flex: 0 0 30px; font-size: 0.7rem; color: #999; }
        .filter-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px; }
        .filter-btn { font-size: 0.7rem !important; padding: 3px 5px !important; }

        /* Scrollbar customizada para o painel lateral */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    </style>
</head>
<body>

<div class="container-fluid py-3">
    <div class="row g-3">

        <div class="col-lg-3 sidebar-scroll">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-bold text-uppercase small text-muted">Ferramentas de Design</div>
                <div class="accordion accordion-flush" id="toolsAccordion">

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                <i class="bi bi-palette me-2 text-primary"></i> Canvas e Fundo
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <label class="small fw-bold mb-1">Cor de Fundo</label>
                                <input type="color" id="bgColor" class="form-control form-control-color w-100 mb-2" value="#ffffff">
                                <input type="file" id="bgImageInput" class="input-file-hidden" accept="image/*">
                                <label for="bgImageInput" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-image"></i> Trocar Imagem de Fundo</label>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                <i class="bi bi-patch-check me-2 text-success"></i> Marcas e Logos
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <?php if(isset($igreja['igreja_logo']) && !empty($igreja['igreja_logo'])): ?>
                                    <button class="btn btn-dark tool-btn w-100" onclick="addImageToCanvas('<?= url('assets/uploads/'.$_SESSION['usuario_igreja_id'].'/logo/'.$igreja['igreja_logo']) ?>', 300)">
                                        <i class="bi bi-house-door"></i> Logo Igreja Local
                                    </button>
                                <?php endif; ?>
                                <button class="btn btn-outline-dark tool-btn w-100" onclick="addImageToCanvas('<?= url('assets/img/logo_ipb_completo.png') ?>', 350)">IPB Completo</button>
                                <button class="btn btn-outline-dark tool-btn w-100" onclick="addImageToCanvas('<?= url('assets/img/logo_ipb.png') ?>', 150)">Símbolo Sarça (IPB)</button>
                                <hr class="my-2">
                                <input type="file" id="freeImageInput" class="input-file-hidden">
                                <label for="freeImageInput" class="btn btn-success tool-btn w-100 text-white"><i class="bi bi-upload"></i> Subir do Computador</label>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                <i class="bi bi-person-circle me-2 text-danger"></i> Base de Membros
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <select id="select-membros" class="form-control mb-2"></select>
                                <div id="membro-preview-actions" class="d-none p-2 border rounded bg-light">
                                    <p id="membro-nome-preview" class="small fw-bold text-center mb-2"></p>
                                    <div class="row g-1">
                                        <div class="col-6"><button class="btn btn-xs btn-dark w-100" id="btn-add-foto" style="font-size:0.7rem">Foto</button></div>
                                        <div class="col-6"><button class="btn btn-xs btn-dark w-100" id="btn-add-nome" style="font-size:0.7rem">Nome</button></div>
                                        <div class="col-6"><button class="btn btn-xs btn-dark w-100" id="btn-add-cargo" style="font-size:0.7rem">Cargo</button></div>
                                        <div class="col-6"><button class="btn btn-xs btn-dark w-100" id="btn-add-endereco" style="font-size:0.7rem">Endereço</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTextosProntos">
                                <i class="bi bi-megaphone me-2 text-info"></i> Dados do Evento
                            </button>
                        </h2>
                        <div id="collapseTextosProntos" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <label class="small fw-bold text-muted mb-1" style="font-size:0.65rem">EVENTO ATUAL</label>
                                <button class="btn btn-sm btn-outline-info w-100 mb-1 text-start" onclick="addTexto('<?= addslashes($evento['evento_titulo']) ?>', 70, true)">
                                    <i class="bi bi-type-h1"></i> <?= $evento['evento_titulo'] ?>
                                </button>
                                <button class="btn btn-sm btn-outline-info w-100 mb-1 text-start" onclick="addTexto('<?= date('d/m/Y H:i', strtotime($evento['evento_data_hora_inicio'])) ?>', 40, false)">
                                    <i class="bi bi-calendar-event"></i> <?= date('d/m/H:i', strtotime($evento['evento_data_hora_inicio'])) ?>
                                </button>
                                <button class="btn btn-sm btn-outline-info w-100 mb-2 text-start" onclick="addTexto('<?= addslashes($evento['evento_local']) ?>', 35, false)">
                                    <i class="bi bi-geo-alt"></i> <?= $evento['evento_local'] ?>
                                </button>

								<label class="small fw-bold text-muted mb-1" style="font-size:0.65rem">DADOS DA IGREJA</label>
								<button class="btn btn-sm btn-outline-secondary w-100 mb-1 text-start" onclick="addTexto('<?= addslashes($igreja['igreja_nome']) ?>', 45, true)">
									<i class="bi bi-building"></i> <?= $igreja['igreja_nome'] ?>
								</button>

								<?php if(!empty($igreja['igreja_endereco'])): ?>
									<button class="btn btn-sm btn-outline-secondary w-100 mb-3 text-start" onclick="addTexto('<?= addslashes($igreja['igreja_endereco']) ?>', 25, false)">
										<i class="bi bi-geo-alt-fill"></i> <?= $igreja['igreja_endereco'] ?>
									</button>
								<?php endif; ?>

								<?php if(!empty($redes)): ?>
									<label class="small fw-bold text-muted mb-1 mt-2 text-uppercase" style="font-size:0.65rem">Redes Sociais</label>
									<div class="row g-1 mb-3">
										<?php foreach($redes as $rede):
											// Lógica para definir o ícone e o caractere para o Canvas
											$nomeRedeLower = strtolower($rede['rede_nome']);
											$iconeClass = 'bi bi-share'; // Ícone padrão para o painel

											// Definição do caractere real usando json_decode para converter o hexadecimal
											$charIcone = json_decode('"\uF792"'); // Padrão (share)

											if (strpos($nomeRedeLower, 'instagram') !== false) {
												$iconeClass = 'bi bi-instagram text-danger';
												$charIcone = json_decode('"\uF437"'); // Caractere Instagram
											} elseif (strpos($nomeRedeLower, 'facebook') !== false) {
												$iconeClass = 'bi bi-facebook text-primary';
												$charIcone = json_decode('"\uF344"'); // Caractere Facebook
											} elseif (strpos($nomeRedeLower, 'youtube') !== false) {
												$iconeClass = 'bi bi-youtube text-danger';
												$charIcone = json_decode('"\uF62B"'); // Caractere YouTube
											} elseif (strpos($nomeRedeLower, 'whatsapp') !== false) {
												$iconeClass = 'bi bi-whatsapp text-success';
												$charIcone = json_decode('"\uF618"'); // Caractere WhatsApp
											}
										?>
											<div class="col-6">
												<button class="btn btn-xs btn-light border w-100 py-1 text-start text-truncate"
														style="font-size: 10px;"
														onclick="addRedeSocialNoCanvas('<?= addslashes($charIcone) ?>', '<?= addslashes($rede['rede_usuario']) ?>')"
														title="<?= $rede['rede_nome'] ?>: <?= $rede['rede_usuario'] ?>">
													<i class="<?= $iconeClass ?> me-1"></i>
													<?= $rede['rede_usuario'] ?>
												</button>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

                                <label class="small fw-bold text-muted mb-1 mt-2" style="font-size:0.65rem">SOCIEDADES INTERNAS</label>
                                <div class="sociedade-container">
                                    <?php if(!empty($sociedades)): foreach($sociedades as $soc): ?>
                                        <div class="sociedade-item">
                                            <div class="sociedade-header">
                                                <img src="<?= !empty($soc['sociedade_logo']) ? url('assets/uploads/'.$soc['sociedade_logo']) : url('assets/img/default_soc.png') ?>" alt="Logo">
                                                <span class="text-truncate"><?= $soc['sociedade_nome'] ?></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-xs btn-outline-secondary flex-grow-1" style="font-size: 0.65rem;" onclick="addTexto('<?= addslashes($soc['sociedade_nome']) ?>', 45, true)">Nome</button>
                                                <?php if(!empty($soc['sociedade_logo'])): ?>
                                                    <button class="btn btn-xs btn-outline-secondary flex-grow-1" style="font-size: 0.65rem;" onclick="addImageToCanvas('<?= url('assets/uploads/'.$soc['sociedade_logo']) ?>', 250)">Logo</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonalizado">
                                <i class="bi bi-pencil-square me-2 text-warning"></i> Texto Livre e Elementos
                            </button>
                        </h2>
                        <div id="collapsePersonalizado" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <textarea id="customTextArea" class="form-control form-control-sm mb-2" rows="2" placeholder="Digite seu texto..."></textarea>
                                <button class="btn btn-sm btn-primary w-100 mb-2" onclick="addTextoLivre()">Inserir Texto</button>
                                <div class="row g-1">
                                    <div class="col-6"><button id="emoji-trigger" class="btn btn-xs btn-outline-dark w-100">Emoji 😊</button></div>
                                    <div class="col-6"><button class="btn btn-xs btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#modalIcones">Ícones <i class="bi bi-star"></i></button></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <div class="p-2 border-top">
                    <button class="btn btn-primary w-100 fw-bold" onclick="exportarBanner()">
                        <i class="bi bi-download me-2"></i>BAIXAR IMAGEM
                    </button>
                </div>
            </div>

            <div class="dynamic-controls">

                <div id="text-controls" class="card shadow-sm p-3 d-none bg-white">
                    <h6 class="small fw-bold mb-3 border-bottom pb-2 text-primary text-uppercase">Formatação de Texto</h6>
                    <div class="control-group">
                        <input type="color" oninput="updateProp('fill', this.value)" id="textColor" class="form-control form-control-color p-0" style="width: 35px; height: 35px;">
                        <select id="fontFamily" class="form-select form-select-sm font-select flex-grow-1" onchange="updateProp('fontFamily', this.value)">
                            <option value="Arial">Arial</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Oswald">Oswald</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Impact">Impact</option>
                        </select>
                        <input type="number" id="fontSize" class="form-control form-control-sm size-input" oninput="updateProp('fontSize', parseInt(this.value))">
                    </div>
                    <div class="control-group justify-content-center">
                        <button class="btn btn-sm btn-light border" onclick="toggleStyle('bold')"><i class="bi bi-type-bold"></i></button>
                        <button class="btn btn-sm btn-light border" onclick="toggleStyle('italic')"><i class="bi bi-type-italic"></i></button>
                        <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'left')"><i class="bi bi-text-left"></i></button>
                        <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'center')"><i class="bi bi-text-center"></i></button>
                        <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'right')"><i class="bi bi-text-right"></i></button>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="camada('frente')"><i class="bi bi-layer-forward"></i></button>
                        <button class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="camada('atras')"><i class="bi bi-layer-backward"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSelected()"><i class="bi bi-trash"></i></button>
                    </div>
                </div>

                <div id="image-controls" class="card shadow-sm p-3 d-none bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h6 class="small fw-bold m-0 text-primary text-uppercase">Editor de Imagem</h6>
                        <button class="btn btn-xs btn-link text-danger p-0 text-decoration-none" onclick="resetFilters()" style="font-size:0.7rem">Resetar</button>
                    </div>
                    <div class="mb-3">
                        <div class="control-row">
                            <span class="control-label">Opacidade</span>
                            <input type="range" class="form-range control-slider" id="imgOpacity" min="0" max="1" step="0.05" oninput="updateImageProp('opacity', parseFloat(this.value))">
                            <span class="control-value" id="valOpacity">1.0</span>
                        </div>
                        <div class="control-row">
                            <span class="control-label">Brilho</span>
                            <input type="range" class="form-range control-slider" id="imgBrightness" min="-1" max="1" step="0.05" oninput="applyImgFilter('brightness', this.value)">
                            <span class="control-value" id="valBrightness">0.0</span>
                        </div>
                        <div class="control-row">
                            <span class="control-label">Desfoque</span>
                            <input type="range" class="form-range control-slider" id="imgBlur" min="0" max="1" step="0.05" oninput="applyImgFilter('blur', this.value)">
                            <span class="control-value" id="valBlur">0.0</span>
                        </div>
                    </div>
                    <div class="filter-grid mb-3">
                        <button class="btn btn-outline-secondary filter-btn" onclick="applyImgFilter('grayscale')">P&B</button>
                        <button class="btn btn-outline-secondary filter-btn" onclick="applyImgFilter('sepia')">Sépia</button>
                        <button class="btn btn-outline-secondary filter-btn" onclick="applyImgFilter('invert')">Inverter</button>
                        <button class="btn btn-outline-secondary filter-btn" onclick="applyImgFilter('vintage')">Vintage</button>
                    </div>
                    <button class="btn btn-sm btn-dark w-100" onclick="cropCircle()"><i class="bi bi-mask me-1"></i>Máscara Circular</button>
                </div>
            </div> </div>

        <div class="col-lg-9">
            <div class="canvas-wrapper">
                <canvas id="canvasBanner"></canvas>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="modalIcones" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2 bg-dark text-white">
                <h6 class="modal-title"><i class="bi bi-search me-2"></i>Biblioteca Completa de Ícones</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group mb-3 shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-filter"></i></span>
                    <input type="text" id="searchIcon" class="form-control" placeholder="Filtrar ícones..." onkeyup="filtrarIcones()">
                </div>
                <div id="loadingIcones" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="iconGrid" class="d-flex flex-wrap gap-2 justify-content-center"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Inicialização do Canvas (1080x1080)
    const canvas = new fabric.Canvas('canvasBanner', {
        width: 1080,
        height: 1080,
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    fabric.Object.prototype.transparentCorners = false;
    fabric.Object.prototype.cornerColor = '#2196F3';
    fabric.Object.prototype.cornerSize = 12;

    // 2. Ajuste de Zoom Visual
    function ajustarZoom() {
        const wrapper = document.querySelector('.canvas-wrapper');
        if (!wrapper) return;
        const larguraDisponivel = wrapper.offsetWidth - 40;
        const scale = larguraDisponivel / 1080;

        if (larguraDisponivel < 1080) {
            canvas.setZoom(scale);
            canvas.setWidth(1080 * scale);
            canvas.setHeight(1080 * scale);
        } else {
            canvas.setZoom(1);
            canvas.setWidth(1080);
            canvas.setHeight(1080);
        }
    }

    window.addEventListener('resize', ajustarZoom);
    setTimeout(ajustarZoom, 100);

    // 3. Atalhos de Teclado
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Delete' || (e.key === 'Backspace' && !canvas.getActiveObject()?.isEditing)) {
            deleteSelected();
        }
    });

    function deleteSelected() {
        const activeObject = canvas.getActiveObject();
        if (activeObject && !activeObject.isEditing) {
            canvas.remove(activeObject);
            canvas.discardActiveObject().renderAll();
        }
    }

    // 4. Funções de Inserção
    function addTexto(txt, size, bold) {
        if (!txt) return;
        const t = new fabric.IText(txt.toString(), {
            left: 540, top: 540, fontSize: size,
            fontWeight: bold ? 'bold' : 'normal',
            originX: 'center', originY: 'center',
            fontFamily: 'Arial', fill: '#000000', textAlign: 'center'
        });
        canvas.add(t).setActiveObject(t).renderAll();
    }

    function addTextoLivre() {
        const txt = document.getElementById('customTextArea').value;
        if (!txt.trim()) return;
        const t = new fabric.IText(txt, {
            left: 540, top: 540, fontSize: 50,
            originX: 'center', originY: 'center',
            fontFamily: 'Arial', fill: '#000000', textAlign: 'center'
        });
        canvas.add(t).setActiveObject(t).renderAll();
        document.getElementById('customTextArea').value = '';
    }

    function addImageToCanvas(url, width) {
        if (!url || url.includes('undefined')) return;
        fabric.Image.fromURL(url, img => {
            if(!img) return;
            img.scaleToWidth(width);
            img.set({ left: 540, top: 540, originX: 'center', originY: 'center' });
            canvas.add(img).setActiveObject(img).renderAll();
        }, { crossOrigin: 'anonymous' });
    }

    // 5. Controles de UI
    function updateProp(p, v) {
        const obj = canvas.getActiveObject();
        if (obj) { obj.set(p, v); canvas.renderAll(); }
    }

    function toggleStyle(style) {
        const obj = canvas.getActiveObject();
        if (!obj || !obj.type.includes('text')) return;
        if (style === 'bold') obj.set('fontWeight', obj.fontWeight === 'bold' ? 'normal' : 'bold');
        else if (style === 'italic') obj.set('fontStyle', obj.fontStyle === 'italic' ? 'normal' : 'italic');
        else if (style === 'underline') obj.set('underline', !obj.underline);
        canvas.renderAll();
    }

    function camada(dir) {
        const o = canvas.getActiveObject();
        if (!o) return;
        dir === 'frente' ? canvas.bringToFront(o) : canvas.sendToBack(o);
        canvas.renderAll();
    }

    canvas.on('selection:created', (e) => updateControlUI(e.selected[0]));
    canvas.on('selection:updated', (e) => updateControlUI(e.selected[0]));
    canvas.on('selection:cleared', () => document.getElementById('text-controls').classList.add('d-none'));

    function updateControlUI(obj) {
        const panel = document.getElementById('text-controls');
        if (!panel) return;
        panel.classList.remove('d-none');
        if (obj.type.includes('text')) {
            document.getElementById('textColor').value = obj.fill;
            document.getElementById('fontSize').value = obj.fontSize;
            document.getElementById('fontFamily').value = obj.fontFamily;
        }
    }

    // 6. GESTÃO DE ÍCONES BOOTSTRAP
    let allIcons = [];
    const modalIconesEl = document.getElementById('modalIcones');
    let bootstrapModalInstance = null;

    const categoriasMap = {
        church: ['church', 'cross', 'book', 'heart', 'peace', 'sun', 'stars', 'infinity', 'hand', 'shield', 'fire'],
        social: ['facebook', 'instagram', 'youtube', 'whatsapp', 'twitter', 'telegram', 'github', 'linkedin', 'share', 'broadcast'],
        ui: ['house', 'person', 'gear', 'search', 'check', 'x-lg', 'plus', 'trash', 'pencil', 'info', 'telephone', 'envelope', 'calendar', 'clock'],
        arrows: ['arrow', 'chevron', 'caret', 'cursor', 'download', 'upload']
    };

    function filtrarIcones(cat, event) {
        const grid = document.getElementById('iconGrid');
        if(!grid) return;
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.replace('btn-dark', 'btn-outline-dark'));
        if(event) event.currentTarget.classList.replace('btn-outline-dark', 'btn-dark');

        const listaFiltrada = allIcons.filter(icon => {
            if (cat === 'all') return true;
            return categoriasMap[cat] ? categoriasMap[cat].some(key => icon.includes(key)) : icon.includes(cat);
        });
        renderizarGrade(listaFiltrada);
    }

    function renderizarGrade(lista) {
        const grid = document.getElementById('iconGrid');
        if (!grid) return;
        grid.innerHTML = '';
        lista.forEach(iconName => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-outline-dark btn-sm p-2 icon-item';
            btn.style.width = '45px';
            btn.style.height = '45px';
            btn.title = iconName;

            // Adicionamos o atributo que o Bootstrap usa para fechar modais nativamente
            btn.setAttribute('data-bs-dismiss', 'modal');

            btn.innerHTML = `<i class="bi bi-${iconName}" style="font-size: 1.2rem;"></i>`;
            btn.onclick = () => {
                const iElement = btn.querySelector('i');
                const content = window.getComputedStyle(iElement, '::before').getPropertyValue('content').replace(/['"]/g, '');
                addIconeNoBanner(content);
            };
            grid.appendChild(btn);
        });
    }

// --- FUNÇÃO PARA INSERIR REDE SOCIAL (ÍCONE + TEXTO AGRUPADOS) NO CANVAS ---
function addRedeSocialNoCanvas(caractereIcone, usuario) {
    // 1. Definições de estilo (pegando do painel se existirem, ou padrão)
    let size = document.getElementById('fontSize')?.value || 40; // Tamanho padrão para redes sociais
    let color = document.getElementById('textColor')?.value || '#000000';

    // 2. Criar o objeto de Texto do Ícone (Bootstrap Icons)
    // Usamos fabric.Text (não editável) para garantir que o ícone não mude
    var iconObject = new fabric.Text(caractereIcone, {
        fontSize: parseInt(size) * 1.2, // Ícone ligeiramente maior que o texto
        fontFamily: 'bootstrap-icons',
        fill: color,
        originX: 'left',
        originY: 'center',
        selectable: false // Impede seleção individual dentro do grupo
    });

    // 3. Criar o objeto de Texto do Usuário
    var textObject = new fabric.IText(usuario, {
        fontSize: parseInt(size),
        fontFamily: 'Arial', // Fonte padrão editável
        fill: color,
        originX: 'left',
        originY: 'center',
        left: iconObject.width + 15, // Espaçamento de 15px após o ícone
        selectable: true // Permite editar o texto dentro do grupo
    });

    // 4. Agrupar os dois objetos para moverem e redimensionarem juntos
    var groupRede = new fabric.Group([ iconObject, textObject ], {
        left: 540, // Centro do canvas 1080
        top: 540,
        originX: 'center',
        originY: 'center',
        selectable: true,
        hasControls: true,
        lockScalingFlip: true // Impede inverter ao redimensionar
    });

    // 5. Adicionar ao canvas e renderizar
    canvas.add(groupRede);
    canvas.setActiveObject(groupRede);
    canvas.renderAll();
}

function addIconeNoBanner(unicode) {
        // 1. Adição ao Canvas imediata
        let size = document.getElementById('fontSize')?.value || 80;
        let color = document.getElementById('textColor')?.value || '#000000';

        const iconText = new fabric.IText(unicode, {
            left: 540,
            top: 540,
            fontSize: parseInt(size),
            fontFamily: 'bootstrap-icons',
            fill: color,
            originX: 'center',
            originY: 'center'
        });

        canvas.add(iconText).setActiveObject(iconText).renderAll();

        // 2. Limpeza de Segurança (Executada após o Bootstrap fechar pelo data-bs-dismiss)
        // O evento 'hidden.bs.modal' é o momento em que o modal sumiu TOTALMENTE da tela.
        const modalEl = document.getElementById('modalIcones');

        const limparEstadoModal = () => {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        };

        modalEl.addEventListener('hidden.bs.modal', limparEstadoModal, { once: true });

        // Backup para garantir que a tela não trave se a animação engasgar
        setTimeout(limparEstadoModal, 500);
    }

    // 7. Inicialização Centralizada
    document.addEventListener('DOMContentLoaded', () => {
        const sel = new Choices('#select-membros', { searchEnabled: true });
        let currentMember = null;

        document.getElementById('select-membros').addEventListener('search', (e) => {
            if (e.detail.value.length > 2) {
                fetch(`<?= url('igrejaEvento/buscarMembrosApi') ?>?q=${e.detail.value}`)
                .then(r => r.json()).then(data => {
                    const list = data.map(m => ({ value: m.membro_id, label: m.membro_nome, customProperties: m }));
                    sel.setChoices(list, 'value', 'label', true);
                });
            }
        });

        document.getElementById('select-membros').addEventListener('change', () => {
            const val = sel.getValue();
            if (val) {
                currentMember = val.customProperties;
                document.getElementById('membro-nome-preview').innerText = currentMember.membro_nome;
                document.getElementById('membro-preview-actions').classList.remove('d-none');
            }
        });

        // Botões Membros (RESTORED: Incluindo Endereço)
        document.getElementById('btn-add-nome').onclick = () => currentMember && addTexto(currentMember.membro_nome, 60, true);
        document.getElementById('btn-add-cargo').onclick = () => currentMember && addTexto(currentMember.cargos || 'Membro', 35, false);
        document.getElementById('btn-add-endereco').onclick = () => {
            if (currentMember) {
                // Usa o campo formatado pelo PHP
                const endereco = currentMember.endereco_completo_formatado;
                if (endereco && endereco.trim() !== "") {
                    addTexto(endereco, 25, false);
                } else {
                    alert("Endereço não preenchido no cadastro deste membro.");
                }
            }
        };
        document.getElementById('btn-add-foto').onclick = () => {
            if (currentMember?.membro_foto_arquivo) {
                const urlFoto = `<?= url('assets/uploads/') ?><?= $igreja['igreja_id'] ?>/membros/${currentMember.membro_registro_interno}/${currentMember.membro_foto_arquivo}`;
                addImageToCanvas(urlFoto, 400);
            }
        };

        // Modal Ícones (Criação e Categorias)
        if (modalIconesEl) {
            bootstrapModalInstance = new bootstrap.Modal(modalIconesEl);
            const modalBody = modalIconesEl.querySelector('.modal-body');
            if(!document.getElementById('cat-container')) {
                const catHTML = `
                <div id="cat-container" class="d-flex flex-wrap gap-1 mb-3 justify-content-center">
                    <button class="btn btn-sm btn-dark filter-btn" onclick="filtrarIcones('all', event)">Tudo</button>
                    <button class="btn btn-sm btn-outline-dark filter-btn" onclick="filtrarIcones('church', event)">Igreja</button>
                    <button class="btn btn-sm btn-outline-dark filter-btn" onclick="filtrarIcones('social', event)">Social</button>
                    <button class="btn btn-sm btn-outline-dark filter-btn" onclick="filtrarIcones('ui', event)">Interface</button>
                    <button class="btn btn-sm btn-outline-dark filter-btn" onclick="filtrarIcones('arrows', event)">Setas</button>
                </div>`;
                modalBody.insertAdjacentHTML('afterbegin', catHTML);
            }

            modalIconesEl.addEventListener('shown.bs.modal', async function () {
                if (allIcons.length > 0) return;
                try {
                    const response = await fetch('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
                    const cssText = await response.text();
                    const regex = /\.bi-([a-z0-9-]+)::before/g;
                    let match; allIcons = [];
                    while ((match = regex.exec(cssText)) !== null) { allIcons.push(match[1]); }
                    renderizarGrade(allIcons);
                    document.getElementById('loadingIcones').style.display = 'none';
                } catch (e) { console.error(e); }
            }, { once: true });
        }

		// --- EMOJIS (PICMO) ---
        setTimeout(() => {
            const emojiTrigger = document.querySelector('#emoji-trigger');
            if (emojiTrigger && typeof picmoPopup !== 'undefined') {
                const picker = picmoPopup.createPopup({
                    showSearch: true,
                    referenceElement: emojiTrigger,
                    triggerElement: emojiTrigger,
                    position: 'bottom-start'
                }, { rootElement: document.body });

                emojiTrigger.addEventListener('click', (e) => {
                    e.preventDefault();
                    picker.toggle();
                });

                picker.addEventListener('emoji:select', selection => {
                    let s = document.getElementById('fontSize')?.value || 80;
                    const eText = new fabric.IText(selection.emoji, {
                        left: 540, top: 540, fontSize: parseInt(s),
                        fontFamily: 'Segoe UI Emoji, Apple Color Emoji, sans-serif',
                        originX: 'center', originY: 'center'
                    });
                    canvas.add(eText).setActiveObject(eText).renderAll();
                });
            } else {
                console.warn("Emoji Picker: Botão não encontrado ou Picmo não carregado.");
            }
        }, 500); // Delay para garantir que o DOM e Picmo estejam prontos
    });

    // 8. Background e Uploads
    document.getElementById('bgColor').oninput = (e) => {
        canvas.setBackgroundImage(null, canvas.renderAll.bind(canvas));
        canvas.backgroundColor = e.target.value;
        canvas.renderAll();
    };

    document.getElementById('bgImageInput').onchange = (e) => {
        if (!e.target.files[0]) return;
        const reader = new FileReader();
        reader.onload = (f) => {
            fabric.Image.fromURL(f.target.result, img => {
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: 1080 / img.width, scaleY: 1080 / img.height,
                    originX: 'left', originY: 'top'
                });
            });
        };
        reader.readAsDataURL(e.target.files[0]);
    };

    document.getElementById('freeImageInput').onchange = (e) => {
        if (!e.target.files[0]) return;
        const reader = new FileReader();
        reader.onload = (f) => addImageToCanvas(f.target.result, 450);
        reader.readAsDataURL(e.target.files[0]);
    };

	// --- NOVO EDITOR DE IMAGEM COMPLETO COM FABRICJS FILTERS ---

	// 1. Gerenciador de Seleção e Visibilidade (handleSelection já existente, vamos atualizar)
	canvas.on('selection:created', (e) => handleSelection(e.selected[0]));
	canvas.on('selection:updated', (e) => handleSelection(e.selected[0]));
	canvas.on('selection:cleared', () => {
		// Esconde os painéis se nada estiver selecionado
		const panelText = document.getElementById('text-controls');
		const panelImg = document.getElementById('image-controls');
		if(panelText) panelText.classList.add('d-none');
		if(panelImg) panelImg.classList.add('d-none');
	});

	function handleSelection(obj) {
		const panelText = document.getElementById('text-controls');
		const panelImg = document.getElementById('image-controls');
		if(!panelText || !panelImg) return;

		// Reseta visibilidade
		panelText.classList.add('d-none');
		panelImg.classList.add('d-none');

		if (obj.type === 'i-text' || obj.type === 'text') {
			panelText.classList.remove('d-none');
			updateControlUI(obj);
		}
		else if (obj.type === 'image') {
			panelImg.classList.remove('d-none');
			syncImageSliders(obj); // Carrega os valores atuais da imagem nos sliders
		}
	}

	// Sincroniza os Sliders HTML com o estado real da imagem selecionada
	function syncImageSliders(obj) {
		document.getElementById('imgOpacity').value = obj.opacity;
		document.getElementById('valOpacity').innerText = obj.opacity.toFixed(2);

		// Busca os filtros na pilha
		const brightnessF = obj.filters.find(f => f instanceof fabric.Image.filters.Brightness);
		const contrastF = obj.filters.find(f => f instanceof fabric.Image.filters.Contrast);
		const saturationF = obj.filters.find(f => f instanceof fabric.Image.filters.Saturation);
		const hueF = obj.filters.find(f => f instanceof fabric.Image.filters.HueRotation);
		const blurF = obj.filters.find(f => f instanceof fabric.Image.filters.Blur);
		const gammaF = obj.filters.find(f => f instanceof fabric.Image.filters.Gamma);

		// Atualiza os sliders e os labels de texto
		updateSliderUI('Brightness', brightnessF ? brightnessF.brightness : 0);
		updateSliderUI('Contrast', contrastF ? contrastF.contrast : 0);
		updateSliderUI('Saturation', saturationF ? saturationF.saturation : 0);
		updateSliderUI('Hue', hueF ? hueF.rotation : 0);
		updateSliderUI('Blur', blurF ? blurF.blur : 0);
		updateSliderUI('Gamma', gammaF ? gammaF.gamma[0] : 1);
	}

	// Função auxiliar para atualizar o slider e o texto ao lado dele
	function updateSliderUI(name, value) {
		const slider = document.getElementById('img' + name);
		const valText = document.getElementById('val' + name);
		if(slider && valText) {
			slider.value = value;
			valText.innerText = value.toFixed(2);
		}
	}

	// 2. Atualiza Propriedades Diretas (Opacidade)
	function updateImageProp(prop, val) {
		const obj = canvas.getActiveObject();
		if (obj && obj.type === 'image') {
			obj.set(prop, val);
			document.getElementById('valOpacity').innerText = val.toFixed(2);
			canvas.renderAll();
		}
	}

	// 3. Aplica Filtros Complexos e Estilos
	function applyImgFilter(type, value) {
		const obj = canvas.getActiveObject();
		if (!obj || obj.type !== 'image') return;

		// Filtros que aceitam valores (Sliders)
		if (['brightness', 'contrast', 'saturation', 'hue', 'blur', 'gamma'].includes(type)) {
			// Atualiza o texto do valor na UI
			const valTextId = 'val' + type.charAt(0).toUpperCase() + type.slice(1);
			const valText = document.getElementById(valTextId);
			if(valText) valText.innerText = parseFloat(value).toFixed(2);

			// Aplica o filtro na pilha
			let filter;
			switch(type) {
				case 'brightness': filter = new fabric.Image.filters.Brightness({ brightness: parseFloat(value) }); break;
				case 'contrast': filter = new fabric.Image.filters.Contrast({ contrast: parseFloat(value) }); break;
				case 'saturation': filter = new fabric.Image.filters.Saturation({ saturation: parseFloat(value) }); break;
				case 'hue': filter = new fabric.Image.filters.HueRotation({ rotation: parseFloat(value) }); break;
				case 'blur': filter = new fabric.Image.filters.Blur({ blur: parseFloat(value) }); break;
				case 'gamma': filter = new fabric.Image.filters.Gamma({ gamma: [parseFloat(value), parseFloat(value), parseFloat(value)] }); break;
			}
			updateFilterStack(obj, filter);
		}
		// Filtros de Estilo (Toggle / Botões)
		else {
			let filter;
			switch(type) {
				case 'grayscale': filter = new fabric.Image.filters.Grayscale(); break;
				case 'sepia': filter = new fabric.Image.filters.Sepia(); break;
				case 'invert': filter = new fabric.Image.filters.Invert(); break;
				case 'pixelate': filter = new fabric.Image.filters.Pixelate({ blocksize: 8 }); break;
				case 'vintage': filter = new fabric.Image.filters.Vintage(); break;
				case 'brownie': filter = new fabric.Image.filters.Brownie(); break;
			}
			toggleFilterInStack(obj, filter);
		}

		// Processa os filtros e renderiza
		obj.applyFilters();
		canvas.renderAll();
	}

	// Função Auxiliar: Substitui o filtro se já existir (para sliders)
	function updateFilterStack(obj, newFilter) {
		// Remove qualquer instância anterior do mesmo tipo de filtro
		obj.filters = obj.filters.filter(f => !(f instanceof newFilter.constructor));
		// Adiciona o novo se o valor for diferente de 0 (ou 1 para gamma)
		const isGamma = newFilter instanceof fabric.Image.filters.Gamma;
		if((isGamma && newFilter.gamma[0] !== 1) || (!isGamma && (newFilter.brightness !== 0 || newFilter.contrast !== 0 || newFilter.saturation !== 0 || newFilter.rotation !== 0 || newFilter.blur !== 0))) {
			 obj.filters.push(newFilter);
		}
	}

	// Função Auxiliar: Adiciona ou remove o filtro (para botões)
	function toggleFilterInStack(obj, filterToToggle) {
		const existingIndex = obj.filters.findIndex(f => f instanceof filterToToggle.constructor);
		if (existingIndex > -1) {
			// Se já existe, remove (desliga)
			obj.filters.splice(existingIndex, 1);
		} else {
			// Se não existe, adiciona (liga)
			obj.filters.push(filterToToggle);
		}
	}

	// 4. Transformações (Crop Circular)
	function cropCircle() {
		const obj = canvas.getActiveObject();
		if (!obj || obj.type !== 'image') return;
		const radius = (Math.min(obj.width, obj.height) / 2);
		const circle = new fabric.Circle({
			radius: radius, originX: 'center', originY: 'center', left: 0, top: 0
		});
		obj.set('clipPath', circle);
		canvas.renderAll();
	}

	// 5. Resetar Tudo
	function resetFilters() {
		const obj = canvas.getActiveObject();
		if (!obj || obj.type !== 'image') return;
		obj.filters = []; // Limpa a pilha
		obj.set('opacity', 1); // Reseta opacidade
		obj.set('clipPath', null); // Reseta o crop
		obj.applyFilters();
		canvas.renderAll();
		syncImageSliders(obj); // Atualiza os sliders para o estado padrão
    }


    // 9. Exportar
    function exportarBanner() {
        canvas.setZoom(1); canvas.setWidth(1080); canvas.setHeight(1080);
        const link = document.createElement('a');
        link.download = `Banner_${Date.now()}.png`;
        link.href = canvas.toDataURL({ format: 'png', quality: 1.0 });
        link.click();
        ajustarZoom();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

