<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Ekklesia</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

	<style>
		body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

		.canvas-wrapper {
			background: #2c2c2c; padding: 20px; border-radius: 12px;
			display: flex; justify-content: center; align-items: center;
			overflow: hidden; box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
			min-height: 600px;
			position: relative;
		}

		/* CORREÇÃO PARA O PAINEL: O container do Fabric deve ser responsivo */
		.canvas-container {
			max-width: 100% !important;
			height: auto !important;
			aspect-ratio: 1 / 1; /* Garante que o painel seja sempre um quadrado */
		}

		canvas {
			border: 1px solid #000;
			box-shadow: 0 10px 30px rgba(0,0,0,0.5);
			max-width: 100% !important; /* Força o canvas a respeitar o wrapper */
			height: auto !important;
		}

		.tool-btn { margin-bottom: 8px; text-align: left; font-size: 0.85rem; }
		.accordion-button { font-size: 0.9rem; font-weight: 600; }
		.input-file-hidden { width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1; }

		.sociedade-item {
			background: #fff; border: 1px solid #dee2e6; border-radius: 8px;
			padding: 10px; margin-bottom: 10px;
		}
		.sociedade-header { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
		.sociedade-header img { height: 30px; width: 30px; object-fit: contain; border-radius: 4px; background: #f8f9fa; }
		.sociedade-header span { font-weight: 600; font-size: 0.85rem; color: #333; }

		#text-controls { flex-wrap: wrap; padding: 10px; }
		.control-group { display: flex; align-items: center; gap: 5px; border-right: 1px solid #ddd; padding-right: 10px; margin-right: 5px; }
		.control-group:last-child { border-right: none; }
		.font-select { width: 130px; font-size: 0.8rem; }
		.size-input { width: 60px; font-size: 0.8rem; }
	</style>
</head>
<body>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-3">
            <div class="card shadow-lg border-0 p-2">
                <div class="accordion" id="toolsAccordion">

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                <i class="bi bi-palette me-2 text-primary"></i> Canvas e Fundo
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body">
                                <label class="small fw-bold">Cor de Fundo</label>
                                <input type="color" id="bgColor" class="form-control form-control-color w-100 mb-3" value="#ffffff">
                                <input type="file" id="bgImageInput" class="input-file-hidden" accept="image/*">
                                <label for="bgImageInput" class="btn btn-outline-primary tool-btn w-100"><i class="bi bi-image"></i> Definir Imagem de Fundo</label>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                <i class="bi bi-patch-check me-2 text-success"></i> Marcas Oficiais
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body">
								<?php if(isset($igreja['igreja_logo']) && !empty($igreja['igreja_logo'])): ?>
									<button class="btn btn-dark tool-btn w-100 mb-2"
											onclick="addImageToCanvas('<?= url('assets/uploads/'.$_SESSION['usuario_igreja_id'].'/logo/'.$igreja['igreja_logo']) ?>', 300)">
										<i class="bi bi-house-door me-1"></i> Logo Igreja Local
									</button>
                                <?php else: ?>
                                    <?php var_dump($igreja); ?>
									<p class="text-muted small"><i class="bi bi-info-circle"></i> Logo local não encontrado no banco.</p>
								<?php endif; ?>


                                <button class="btn btn-outline-dark tool-btn w-100 mb-2" onclick="addImageToCanvas('<?= url('assets/img/logo_ipb_completo.png') ?>', 350)">
                                    IPB Completo
                                </button>
                                <button class="btn btn-outline-dark tool-btn w-100 mb-2" onclick="addImageToCanvas('<?= url('assets/img/logo_ipb.png') ?>', 150)">
                                    Logo Sarça (IPB)
                                </button>

                                <hr>
                                <input type="file" id="freeImageInput" class="input-file-hidden">
                                <label for="freeImageInput" class="btn btn-success tool-btn w-100 text-white"><i class="bi bi-upload"></i> Carregar do PC</label>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSociedades">
                                <i class="bi bi-people me-2 text-warning"></i> Sociedades Internas
                            </button>
                        </h2>
                        <div id="collapseSociedades" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body p-2">
                                <?php if(!empty($sociedades)): ?>
                                    <?php foreach($sociedades as $soc): ?>
                                        <div class="sociedade-item">
                                            <div class="sociedade-header">
                                                <?php if(!empty($soc['sociedade_logo'])): ?>
                                                    <img src="<?= url('assets/uploads/'.$soc['sociedade_logo']) ?>" alt="Logo">
                                                <?php else: ?>
                                                    <i class="bi bi-image text-muted"></i>
                                                <?php endif; ?>
                                                <span class="text-truncate"><?= $soc['sociedade_nome'] ?></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-outline-secondary flex-grow-1" style="font-size: 0.7rem;" onclick="addTexto('<?= addslashes($soc['sociedade_nome']) ?>', 45, true)">
                                                    <i class="bi bi-type"></i> Nome
                                                </button>
                                                <?php if(!empty($soc['sociedade_logo'])): ?>
                                                    <button class="btn btn-sm btn-outline-secondary flex-grow-1" style="font-size: 0.7rem;" onclick="addImageToCanvas('<?= url('assets/uploads/'.$soc['sociedade_logo']) ?>', 250)">
                                                        <i class="bi bi-image"></i> Logo
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

					<div class="accordion-item border-0">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTextosProntos">
								<i class="bi bi- megaphone me-2 text-info"></i> Conteúdo Dinâmico
							</button>
						</h2>
						<div id="collapseTextosProntos" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
							<div class="accordion-body p-2">

								<label class="small fw-bold text-muted mb-1">DADOS DO EVENTO</label>
								<button class="btn btn-sm btn-outline-info w-100 mb-1 text-start" onclick="addTexto('<?= addslashes($evento['evento_titulo']) ?>', 70, true)">
									<i class="bi bi-type-h1"></i> <?= $evento['evento_titulo'] ?>
								</button>
								<button class="btn btn-sm btn-outline-info w-100 mb-1 text-start" onclick="addTexto('<?= date('d/m/Y H:i', strtotime($evento['evento_data_hora_inicio'])) ?>', 40, false)">
									<i class="bi bi-calendar-event"></i> <?= date('d/m/H:i', strtotime($evento['evento_data_hora_inicio'])) ?>
								</button>
								<button class="btn btn-sm btn-outline-info w-100 mb-3 text-start" onclick="addTexto('<?= addslashes($evento['evento_local']) ?>', 35, false)">
									<i class="bi bi-geo-alt"></i> <?= $evento['evento_local'] ?>
								</button>

								<label class="small fw-bold text-muted mb-1">DADOS DA IGREJA</label>
								<button class="btn btn-sm btn-outline-secondary w-100 mb-1 text-start" onclick="addTexto('<?= addslashes($igreja['igreja_nome']) ?>', 45, true)">
									<i class="bi bi-building"></i> <?= $igreja['igreja_nome'] ?>
                                </button>

                                <?php if(!empty($igreja['igreja_endereco'])): ?>
                                    <button class="btn btn-sm btn-outline-secondary w-100 mb-3 text-start" onclick="addTexto('<?= addslashes($igreja['igreja_endereco']) ?>', 25, false)">
                                        <i class="bi bi-geo-alt-fill"></i> <?= $igreja['igreja_endereco'] ?>
                                    </button>
                                <?php endif; ?>

								<?php if($pastor): ?>
									<hr>
									<label class="small fw-bold text-muted mb-1">PASTOR</label>
									<div class="d-flex gap-1 mb-2">
										<button class="btn btn-sm btn-dark flex-grow-1" onclick="addTexto('Rev. <?= addslashes($pastor['membro_nome']) ?>', 35, true)">Nome</button>
										<?php if(!empty($pastor['membro_foto_arquivo'])): ?>
											<?php
												$urlFotoPastor = url("assets/uploads/{$igreja['igreja_id']}/membros/{$pastor['membro_registro_interno']}/{$pastor['membro_foto_arquivo']}");
											?>
											<button class="btn btn-sm btn-primary" onclick="addImageToCanvas('<?= $urlFotoPastor ?>', 400)">
												<i class="bi bi-image"></i> Foto
											</button>
										<?php endif; ?>
									</div>
								<?php endif; ?>

								<label class="small fw-bold text-muted mb-1">REDES SOCIAIS</label>
								<div class="row g-1">
									<?php if(!empty($igreja['igreja_instagram'])): ?>
										<div class="col-6"><button class="btn btn-xs btn-light border w-100 py-1" style="font-size: 11px;" onclick="addTexto('<?= $igreja['igreja_instagram'] ?>', 25, false)">Instagram</button></div>
									<?php endif; ?>
									<?php if(!empty($igreja['igreja_youtube'])): ?>
										<div class="col-6"><button class="btn btn-xs btn-light border w-100 py-1" style="font-size: 11px;" onclick="addTexto('<?= $igreja['igreja_youtube'] ?>', 25, false)">YouTube</button></div>
									<?php endif; ?>
								</div>

							</div>
						</div>
					</div>


                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                <i class="bi bi-person-circle me-2 text-danger"></i> Membros
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#toolsAccordion">
                            <div class="accordion-body">
                                <select id="select-membros" class="form-control"></select>
                                <div id="membro-preview-actions" class="d-none mt-3 p-2 border rounded bg-light">
                                    <p id="membro-nome-preview" class="small fw-bold text-center"></p>
                                    <div class="row g-1">
                                        <div class="col-6"><button class="btn btn-sm btn-dark w-100" id="btn-add-foto">Foto</button></div>
                                        <div class="col-6"><button class="btn btn-sm btn-dark w-100" id="btn-add-nome">Nome</button></div>
                                        <div class="col-6"><button class="btn btn-sm btn-dark w-100" id="btn-add-cargo">Cargo</button></div>
                                        <div class="col-6"><button class="btn btn-sm btn-dark w-100" id="btn-add-endereco">Endereço</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <button class="btn btn-primary w-100 mt-3 fw-bold" onclick="exportarBanner()">BAIXAR IMAGEM</button>
            </div>
        </div>

        <div class="col-lg-9">
            <div id="text-controls" class="card mb-2 d-none shadow-sm flex-row align-items-center bg-white">

                <div class="control-group">
                    <input type="color" oninput="updateProp('fill', this.value)" id="textColor" class="form-control form-control-color p-0" title="Cor do Texto">
                    <select id="fontFamily" class="form-select font-select" onchange="updateProp('fontFamily', this.value)">
                        <option value="Arial">Arial</option>
                        <option value="Verdana">Verdana</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Courier New">Courier New</option>
                        <option value="Impact">Impact</option>
                        <option value="Comic Sans MS">Comic Sans MS</option>
                        <option value="Oswald">Oswald</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                    <input type="number" id="fontSize" class="form-control size-input" value="40" oninput="updateProp('fontSize', parseInt(this.value))">
                </div>

                <div class="control-group">
                    <button class="btn btn-sm btn-light border" onclick="toggleStyle('bold')" title="Negrito"><i class="bi bi-type-bold"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="toggleStyle('italic')" title="Itálico"><i class="bi bi-type-italic"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="toggleStyle('underline')" title="Sublinhado"><i class="bi bi-type-underline"></i></button>
                </div>

                <div class="control-group">
                    <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'left')" title="Esquerda"><i class="bi bi-text-left"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'center')" title="Centralizar"><i class="bi bi-text-center"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'right')" title="Direita"><i class="bi bi-text-right"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="updateProp('textAlign', 'justify')" title="Justificado"><i class="bi bi-justify"></i></button>
                </div>

                <div class="control-group">
                    <button class="btn btn-sm btn-light border" onclick="camada('frente')" title="Trazer para Frente"><i class="bi bi-layer-forward"></i></button>
                    <button class="btn btn-sm btn-light border" onclick="camada('atras')" title="Enviar para Trás"><i class="bi bi-layer-backward"></i></button>
                    <button class="btn btn-sm btn-danger border" onclick="deleteSelected()"><i class="bi bi-trash"></i></button>
                </div>
            </div>

            <div class="canvas-wrapper">
                <canvas id="canvasBanner"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Inicialização do Canvas (Sempre 1080x1080 interno para alta qualidade)
    const canvas = new fabric.Canvas('canvasBanner', {
        width: 1080,
        height: 1080,
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    fabric.Object.prototype.transparentCorners = false;
    fabric.Object.prototype.cornerColor = '#2196F3';
    fabric.Object.prototype.cornerSize = 12;

    // 2. Ajuste de Zoom Visual (Responsividade sem alterar a resolução real)
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

    // 4. Funções de Inserção de Conteúdo
    function addTexto(txt, size, bold) {
        if (!txt) return;
        const t = new fabric.IText(txt.toString(), {
            left: 540,
            top: 540,
            fontSize: size,
            fontWeight: bold ? 'bold' : 'normal',
            originX: 'center',
            originY: 'center',
            fontFamily: 'Arial',
            fill: '#000000',
            textAlign: 'center'
        });
        canvas.add(t).setActiveObject(t).renderAll();
    }

    function addImageToCanvas(url, width) {
        if (!url || url.includes('undefined')) {
            console.error("URL Inválida detectada:", url);
            return;
        }
        fabric.Image.fromURL(url, img => {
            if(!img) return;
            img.scaleToWidth(width);
            img.set({ left: 540, top: 540, originX: 'center', originY: 'center' });
            canvas.add(img).setActiveObject(img).renderAll();
        }, { crossOrigin: 'anonymous' });
    }

    // 5. Controles de Propriedades e Camadas
    function updateProp(p, v) {
        const obj = canvas.getActiveObject();
        if (obj) {
            obj.set(p, v);
            canvas.renderAll();
        }
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

    // 6. Manipulação de Eventos do Canvas
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

    // 7. Seção de Membros e API
    document.addEventListener('DOMContentLoaded', () => {
        const sel = new Choices('#select-membros', { searchEnabled: true, placeholderValue: 'Buscar membro...' });
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

        document.getElementById('btn-add-nome').onclick = () => currentMember && addTexto(currentMember.membro_nome, 60, true);
        document.getElementById('btn-add-cargo').onclick = () => currentMember && addTexto(currentMember.cargos || 'Membro', 35, false);

        document.getElementById('btn-add-endereco').onclick = () => {
            if (currentMember) {
                const rua = currentMember.membro_endereco_rua || '';
                const num = currentMember.membro_endereco_numero ? `, ${currentMember.membro_endereco_numero}` : '';
                const bairro = currentMember.membro_endereco_bairro ? ` - ${currentMember.membro_endereco_bairro}` : '';
                const cidade = currentMember.membro_endereco_cidade ? ` (${currentMember.membro_endereco_cidade})` : '';
                const enderecoCompleto = `${rua}${num}${bairro}${cidade}`;

                if (enderecoCompleto.trim() !== "") {
                    addTexto(enderecoCompleto, 25, false);
                } else {
                    alert("Endereço não preenchido no cadastro deste membro.");
                }
            }
        };

        document.getElementById('btn-add-foto').onclick = () => {
            if (currentMember && currentMember.membro_foto_arquivo) {
                const igrejaId = '<?= $igreja['igreja_id'] ?? $_SESSION['usuario_igreja_id'] ?>';
                const registroId = currentMember.membro_registro_interno;
                const arquivo = currentMember.membro_foto_arquivo;
                const urlFoto = `<?= url('assets/uploads/') ?>${igrejaId}/membros/${registroId}/${arquivo}`;
                addImageToCanvas(urlFoto, 400);
            }
        };
    });

    // 8. Upload e Background (CORREÇÃO DE ESTICAR IMAGEM)
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
                // Força a imagem a ESTICAR para ocupar exatamente 1080x1080
                const scaleX = 1080 / img.width;
                const scaleY = 1080 / img.height;

                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                    scaleX: scaleX,
                    scaleY: scaleY,
                    originX: 'left',
                    originY: 'top'
                });
                e.target.value = '';
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

    // 9. Exportação
    function exportarBanner() {
        // Reseta zoom para garantir qualidade 1:1 na exportação
        canvas.setZoom(1);
        canvas.setWidth(1080);
        canvas.setHeight(1080);

        const dataURL = canvas.toDataURL({ format: 'png', quality: 1.0 });
        const link = document.createElement('a');
        link.download = `Banner_Ekklesia_${Date.now()}.png`;
        link.href = dataURL;
        link.click();

        // Retorna o zoom visual após exportar
        ajustarZoom();
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

