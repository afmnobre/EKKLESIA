<?php
/**
 * View: Banner Builder (Acesso Líder)
 * Local: Views/paginas/sociedade_portal/banner_builder.php
 */

// Define as variáveis para o header do portal
$tituloCard = 'Gerador de Banner & Flyers';
$subtitulo = 'Crie materiais personalizados para sua sociedade';

// Carrega o header padrão do portal do líder
$this->rawview('sociedade_portal/header', [
    'titulo'  => $tituloCard,
    'sociedade' => $sociedade,
    'ativo'   => 'banner' // Isso deixará o item "Banner" marcado no menu lateral
]);
?>

<style>
    /* Garante que o Fabric.js reconheça a fonte dos ícones */
    @font-face {
        font-family: 'bootstrap-icons';
        src: url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2') format('woff2');
    }

    #iconPicker, .bi-font { font-family: 'bootstrap-icons', 'Segoe UI', Arial; }
    .btn-xs { padding: 0.25rem 0.4rem; font-size: 0.75rem; }
    canvas { border: 1px solid #ddd; }
    .btn-outline-secondary.active { background-color: #6c757d !soft-important; color: white !important; }
    .x-small { font-size: 0.7rem; text-transform: uppercase; }

    /* Ajuste para o Canvas não quebrar em telas menores */
    #canvas-wrapper {
        max-width: 100%;
        height: auto;
        background-color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-dark bg-opacity-10 d-flex justify-content-center p-4" style="min-height: 850px; overflow: auto;">
                    <div id="canvas-wrapper" style="width: 800px; height: 800px; background-color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <canvas id="editorCanvas" width="800" height="800"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                <h5 class="fw-bold mb-0 text-truncate"><i class="bi bi-image-fill me-1"></i>Gerador</h5>
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-danger btn-xs" onclick="confirmarLimpeza()" title="Limpar Tudo">
                        <i class="bi bi-trash"></i> Limpar
                    </button>
                    <a href="<?= url('sociedadeLider/index') ?>" class="btn btn-outline-secondary btn-xs">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white p-1">
                    <ul class="nav nav-pills nav-justified small" id="bannerTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active text-white py-1 px-0" style="font-size: 0.75rem;" data-bs-toggle="pill" data-bs-target="#tab-texto">Texto</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-white py-1 px-0" style="font-size: 0.75rem;" data-bs-toggle="pill" data-bs-target="#tab-igreja">Igreja/Membros</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-white py-1 px-0" style="font-size: 0.75rem;" data-bs-toggle="pill" data-bs-target="#tab-imagens">Imagens/Layers</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-3 tab-content" id="tabContent">
                    <div class="tab-pane fade show active" id="tab-texto">
                        <div class="mb-2">
                            <label class="x-small fw-bold d-block mb-1">Conteúdo do Texto:</label>
                            <textarea id="customTextInput" class="form-control form-control-sm" rows="3" placeholder="Digite seu texto aqui..."></textarea>

                            <div class="btn-group btn-group-sm w-100 mt-2">
                                <button type="button" id="btn-bold" class="btn btn-outline-secondary" onclick="toggleStyle('bold')"><i class="bi bi-type-bold"></i></button>
                                <button type="button" id="btn-italic" class="btn btn-outline-secondary" onclick="toggleStyle('italic')"><i class="bi bi-type-italic"></i></button>
                                <button type="button" class="btn btn-outline-secondary" onclick="updateTextAlign('left')"><i class="bi bi-text-left"></i></button>
                                <button type="button" class="btn btn-outline-secondary" onclick="updateTextAlign('center')"><i class="bi bi-text-center"></i></button>
                                <button type="button" class="btn btn-outline-secondary" onclick="updateTextAlign('right')"><i class="bi bi-text-right"></i></button>
                                <button type="button" class="btn btn-outline-secondary" onclick="updateTextAlign('justify')"><i class="bi bi-text-paragraph"></i></button>
                            </div>

                            <button class="btn btn-primary btn-sm w-100 mt-2 fw-bold" onclick="addTextoLivre()">
                                <i class="bi bi-plus-circle me-1"></i> INSERIR NO BANNER
                            </button>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="x-small fw-bold d-block">Cor:</label>
                                <input type="color" id="textColorPicker" class="form-control form-control-sm border-0 p-0" value="#000000" onchange="updateTextStyle()">
                            </div>
                            <div class="col-4">
                                <label class="x-small fw-bold d-block">Tam:</label>
                                <input type="number" id="textSizePicker" class="form-control form-control-sm" value="30" onchange="updateTextStyle()">
                            </div>
                            <div class="col-4">
                                <label class="x-small fw-bold d-block">Fonte:</label>
                                <select id="fontFamilyPicker" class="form-select form-select-sm" onchange="updateTextStyle()">
                                    <option value="Arial">Arial</option>
                                    <option value="Impact">Impact</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Georgia">Georgia</option>
                                </select>
                            </div>
                        </div>
						<div class="row g-2 mt-2">
							<div class="col-6">
								<button type="button" id="emoji-trigger" class="btn btn-sm btn-outline-dark w-100 h-100 d-flex align-items-center justify-content-between px-2">
									<span>Emoji</span>
									<span style="font-size: 1.1rem;">😊</span>
								</button>
							</div>

							<div class="col-6">
								<button type="button" id="btnAbrirModalIcones" class="btn btn-sm btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-between px-2">
									<span class="text-truncate">Ícone</span>
									<i class="bi bi-star-fill"></i>
								</button>
							</div>
						</div>
                        </div>

					<div class="tab-pane fade" id="tab-igreja">
						<h6 class="fw-bold small mb-2 text-primary border-bottom pb-1">
							<i class="bi bi-houses me-1"></i> Dados da Igreja
						</h6>
						<div class="row g-2 mb-3">
							<div class="col-6">
								<button class="btn btn-outline-info btn-xs w-100 text-truncate" onclick="addTexto('<?= addslashes($sociedade['igreja_nome']) ?>', 35, true)">
									<i class="bi bi-building"></i> Nome Igreja
								</button>
							</div>

							<div class="col-6">
								<button class="btn btn-outline-dark btn-xs w-100 text-truncate" onclick="addTexto('Pr. <?= addslashes($sociedade['pastor_nome']) ?>', 28, true)">
									<i class="bi bi-person-check"></i> Nome Pastor
								</button>
							</div>

							<div class="col-6">
								<?php if (!empty($sociedade['pastor_foto'])):
									$urlFotoPastor = url("assets/uploads/{$sociedade['sociedade_igreja_id']}/membros/{$sociedade['pastor_registro']}/{$sociedade['pastor_foto']}");
								?>
									<button class="btn btn-primary btn-xs w-100 text-truncate" onclick="addFotoPastor('<?= $urlFotoPastor ?>')">
										<i class="bi bi-person-badge"></i> Foto Pastor
									</button>
								<?php else: ?>
									<button class="btn btn-outline-secondary btn-xs w-100 text-truncate" disabled title="Pastor sem foto">
										<i class="bi bi-person-slash"></i> S/ Foto Pastor
									</button>
								<?php endif; ?>
							</div>

							<div class="col-6">
								<select class="form-select form-select-xs py-1" style="font-size: 0.75rem;" onchange="addRedeSocial(this)">
									<option value="">Redes Sociais...</option>
									<?php if(!empty($redes)): foreach($redes as $r): ?>
										<option value="<?= $r['rede_usuario'] ?>" data-nome="<?= $r['rede_nome'] ?>">@<?= $r['rede_usuario'] ?></option>
									<?php endforeach; endif; ?>
								</select>
							</div>

							<div class="col-12">
								<button class="btn btn-outline-secondary btn-xs w-100 text-truncate" onclick="addTexto('<?= addslashes($sociedade['igreja_endereco']) ?>', 20)">
									<i class="bi bi-geo-alt"></i> Endereço da Igreja
								</button>
							</div>
						</div>

						<h6 class="fw-bold small mb-2 text-success border-bottom pb-1">
							<i class="bi bi-people-fill me-1"></i> Sociedade & Membros
						</h6>

						<div class="row g-2 mb-3">
							<div class="col-12">
								<button class="btn btn-dark btn-xs w-100 text-truncate" onclick="addTextoSociedade('<?= addslashes($sociedade['sociedade_nome']) ?>')" title="<?= $sociedade['sociedade_nome'] ?>">
									<i class="bi bi-tag-fill text-warning"></i> Nome da Sociedade: <?= $sociedade['sociedade_nome'] ?>
								</button>
							</div>
						</div>

						<div class="bg-light p-2 rounded border">
							<label class="x-small fw-bold mb-1 d-block text-muted text-uppercase">Adicionar Membro Específico:</label>
							<select id="selectMembro" class="form-select form-select-sm mb-2" style="font-size: 0.8rem;">
								<option value="">Selecione um membro...</option>
								<?php if(!empty($membros)): foreach($membros as $m):
									if (!empty($m['membro_endereco_rua'])) {
										$partes = [];
										if (!empty($m['membro_endereco_rua'])) $partes[] = $m['membro_endereco_rua'];
										if (!empty($m['membro_endereco_numero'])) $partes[] = $m['membro_endereco_numero'];
										$ruaNum = implode(", ", $partes);

										$bairroCidade = [];
										if (!empty($m['membro_endereco_bairro'])) $bairroCidade[] = $m['membro_endereco_bairro'];
										if (!empty($m['membro_endereco_cidade'])) $bairroCidade[] = $m['membro_endereco_cidade'];

										$enderecoCompleto = $ruaNum . " - " . implode(", ", $bairroCidade);
									} else {
										$enderecoCompleto = "Endereço não cadastrado";
									}
								?>
									<option value="<?= $m['membro_id'] ?>"
											data-nome="<?= addslashes($m['membro_nome']) ?>"
											data-foto="<?= $m['membro_foto_arquivo'] ?>"
											data-igreja="<?= $m['membro_igreja_id'] ?>"
											data-registro="<?= $m['membro_registro_interno'] ?>"
											data-endereco-full="<?= addslashes($enderecoCompleto) ?>">
										<?= $m['membro_nome'] ?> (<?= $m['sociedade_membro_funcao'] ?? 'Membro' ?>)
									</option>
								<?php endforeach; endif; ?>
							</select>

							<div class="row g-2">
								<div class="col-6">
									<button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('foto')">
										<i class="bi bi-image"></i> Add Foto
									</button>
								</div>
								<div class="col-6">
									<button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('endereco')">
										<i class="bi bi-geo"></i> Add Endereço
									</button>
								</div>
								<div class="col-12 mt-1">
									<button class="btn btn-xs btn-success w-100" onclick="addInfoMembro('nome')">
										<i class="bi bi-person-plus"></i> Inserir Nome do Membro
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab-imagens">
						<div class="row g-1 mb-2">
							<div class="col-3"><button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb')">IPB</button></div>
							<div class="col-3"><button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb_completo')">IPB Compl.</button></div>
                            <div class="col-3"><button class="btn btn-outline-primary btn-xs w-100" onclick="addSociedadeLogo()">Sociedade</button></div>

						<div class="col-3">
								<?php if (!empty($sociedade['igreja_logo'])):
									// O caminho segue o padrão: assets/uploads/{ID_IGREJA}/logo/{ARQUIVO}
									$urlLogoIgreja = url("assets/uploads/{$sociedade['sociedade_igreja_id']}/logo/{$sociedade['igreja_logo']}");
								?>
									<button class="btn btn-info btn-xs w-100 text-truncate text-white" onclick="addLogoIgreja('<?= $urlLogoIgreja ?>')">
										<i class="bi bi-shield-check"></i> Logo Igreja
									</button>
								<?php else: ?>
									<button class="btn btn-outline-secondary btn-xs w-100 text-truncate" disabled title="Igreja sem logo">
										<i class="bi bi-slash-circle"></i> S/ Logo Igreja
									</button>
								<?php endif; ?>
						</div>


						</div>

						<div class="bg-light p-2 rounded mb-2 border">
							<label class="x-small fw-bold d-block mb-1">Upload de Imagem:</label>
							<input type="file" id="imageLoader" class="form-control form-control-sm mb-2" accept="image/*">

							<label class="x-small fw-bold d-block mb-1">Fundo (Background):</label>
							<div class="input-group input-group-sm">
								<input type="file" id="bgInput" class="form-control" accept="image/*">
								<button class="btn btn-outline-danger" onclick="clearBackground()" title="Remover Fundo"><i class="bi bi-x"></i></button>
							</div>
						</div>

						<div id="imageToolsPanel" style="display: none;" class="bg-white border rounded p-2 mb-2 shadow-sm">
							<label class="x-small fw-bold d-block mb-2 text-primary border-bottom pb-1"><i class="bi bi-magic"></i> Edição da Imagem:</label>

							<div class="row g-1 mb-2">
								<div class="col-3">
									<button class="btn btn-xs btn-outline-dark w-100" onclick="girarObjeto(-90)" title="-90°"><i class="bi bi-arrow-counterclockwise"></i></button>
								</div>
								<div class="col-3">
									<button class="btn btn-xs btn-outline-dark w-100" onclick="girarObjeto(90)" title="+90°"><i class="bi bi-arrow-clockwise"></i></button>
								</div>
								<div class="col-3">
									<button class="btn btn-xs btn-outline-dark w-100" onclick="inverterObjeto('X')" title="Inverter H"><i class="bi bi-arrow-left-right"></i></button>
								</div>
								<div class="col-3">
									<button class="btn btn-xs btn-outline-info w-100 text-dark" onclick="aplicarMascaraCircular()" title="Foto Circular"><i class="bi bi-circle"></i></button>
								</div>
							</div>

							<div class="mb-2">
								<div class="d-flex justify-content-between align-items-center">
									<label class="x-small fw-bold text-muted">Opacidade:</label>
									<span id="opacityVal" class="x-small fw-bold text-primary">100%</span>
								</div>
								<input type="range" class="form-range" id="imgOpacity" min="0" max="100" value="100" oninput="ajustarOpacidade(this.value)">
							</div>

							<div class="row g-1">
								<div class="col-6">
									<button id="filter-grey" class="btn btn-xs btn-outline-secondary w-100" onclick="alternarFiltro('greyscale')">Preto e Branco</button>
								</div>
								<div class="col-6">
									<button id="filter-blur" class="btn btn-xs btn-outline-secondary w-100" onclick="alternarFiltro('blur')">Desfocar</button>
								</div>
								<div class="col-12">
									<button class="btn btn-xs btn-link text-danger w-100 py-0 mt-1" style="font-size: 0.65rem;" onclick="removerTodosFiltros()">Limpar Efeitos</button>
								</div>
							</div>
						</div>

						<div class="row g-1 border-top pt-2 mt-1">
							<div class="col-4"><button class="btn btn-xs btn-light border w-100" title="Para Frente" onclick="trazerParaFrente()"><i class="bi bi-layer-forward"></i></button></div>
							<div class="col-4"><button class="btn btn-xs btn-light border w-100" title="Para Trás" onclick="enviarParaTras()"><i class="bi bi-layer-backward"></i></button></div>
							<div class="col-4"><button class="btn btn-xs btn-outline-danger w-100" title="Deletar" onclick="deleteObject()"><i class="bi bi-trash"></i></button></div>

							<div class="col-12 mt-1">
								<button class="btn btn-xs btn-light border w-100" onclick="centralizarH()"><i class="bi bi-align-center"></i> Centralizar Horizontalmente</button>
							</div>
						</div>
					</div>
                </div>
            </div>

            <div class="row g-2 mt-3">
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-lg w-100 fw-bold shadow-sm" onclick="salvarLayout()">
                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>SALVAR
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-success btn-lg w-100 shadow-sm fw-bold" onclick="downloadBanner()">
                        <i class="bi bi-download me-2"></i>BAIXAR
                    </button>
                </div>
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
			<div class="modal-body">
				<div class="d-flex flex-wrap gap-1 mb-3 pb-2 border-bottom" id="categoryFilters">
					<button class="btn btn-xs btn-dark filter-btn" onclick="filtrarIcones('all')">Todos</button>
					<button class="btn btn-xs btn-outline-dark filter-btn" onclick="filtrarIcones('church')">Igreja/Fé</button>
					<button class="btn btn-xs btn-outline-dark filter-btn" onclick="filtrarIcones('social')">Redes Sociais</button>
					<button class="btn btn-xs btn-outline-dark filter-btn" onclick="filtrarIcones('ui')">Interface</button>
					<button class="btn btn-xs btn-outline-dark filter-btn" onclick="filtrarIcones('arrows')">Setas</button>
				</div>

				<div id="loadingIcones" class="text-center my-3">
					<div class="spinner-border spinner-border-sm text-primary"></div> Carregando ícones...
				</div>

				<div id="iconGrid" class="d-flex flex-wrap gap-2 justify-content-center" style="max-height: 400px; overflow-y: auto;">
					</div>
			</div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i id="confirmacaoIcone" class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                </div>
                <h5 id="confirmacaoTitulo" class="fw-bold">Limpar Canvas?</h5>
                <p id="confirmacaoMensagem" class="text-muted small mb-4">
                    Isso removerá todos os elementos da arte atual. Esta ação não pode ser desfeita.
                </p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100 fw-bold shadow-sm" data-bs-dismiss="modal">Não</button>
                    <button type="button" class="btn btn-danger w-100 fw-bold shadow-sm" id="btnConfirmarAcao">Sim, Limpar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/picmo@5.8.5/dist/umd/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@picmo/popup-picker@5.8.5/dist/umd/index.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
    // --- 1. CONFIGURAÇÃO INICIAL DO CANVAS ---
    var canvas = new fabric.Canvas('editorCanvas', {
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    // Feedback visual ao passar o mouse sobre objetos
    canvas.on('mouse:over', function(e) {
        if(e.target) {
            e.target.set('stroke', '#007bff');
            e.target.set('strokeWidth', 1);
            canvas.renderAll();
        }
    });

    canvas.on('mouse:out', function(e) {
        if(e.target) {
            e.target.set('strokeWidth', 0);
            canvas.renderAll();
        }
    });

    canvas.on('selection:created', updateControlPanel);
    canvas.on('selection:updated', updateControlPanel);

    // Garante carregamento da fonte de ícones
    if (document.fonts) {
        document.fonts.load('1em bootstrap-icons');
    }

    // --- 2. FUNÇÕES DE TEXTO E ESTILO ---
    let isBoldActive = false;
    let isItalicActive = false;

    function toggleStyle(style) {
        if (style === 'bold') {
            isBoldActive = !isBoldActive;
            document.getElementById('btn-bold').classList.toggle('active', isBoldActive);
        } else if (style === 'italic') {
            isItalicActive = !isItalicActive;
            document.getElementById('btn-italic').classList.toggle('active', isItalicActive);
        }

        let activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type.includes('text')) {
            if (style === 'bold') activeObject.set('fontWeight', isBoldActive ? 'bold' : 'normal');
            if (style === 'italic') activeObject.set('fontStyle', isItalicActive ? 'italic' : 'normal');
            canvas.renderAll();
        }
    }

    function addTextoLivre() {
        let val = document.getElementById('customTextInput').value;
        if(!val.trim()) return;
        addTexto(val);
        document.getElementById('customTextInput').value = "";
    }

    function addTexto(val, size = null, bold = false) {
        if(!val) return;
        let defaultSize = size || document.getElementById('textSizePicker').value;
        let color = document.getElementById('textColorPicker').value;
        let font = document.getElementById('fontFamilyPicker').value;

        var t = new fabric.Textbox(val, {
            left: canvas.width / 2,
            top: canvas.height / 2,
            width: 400,
            fontSize: parseInt(defaultSize),
            fontWeight: bold || isBoldActive ? 'bold' : 'normal',
            fontStyle: isItalicActive ? 'italic' : 'normal',
            fontFamily: font,
            fill: color,
            textAlign: 'center',
            originX: 'center',
            originY: 'center'
        });

        canvas.add(t);
        canvas.setActiveObject(t);
        canvas.renderAll();
    }

	function addRedeSocial(select) {
		if(!select.value) return;

		let usuario = select.value;
		let redeNome = select.options[select.selectedIndex].getAttribute('data-nome').toLowerCase();

		// Mapeamento de ícones do Bootstrap (Códigos Hex Unicode)
		let icon = "";
		if(redeNome.includes('instagram')) icon = "\uF437 "; // Ícone Instagram
		else if(redeNome.includes('facebook')) icon = "\uF344 "; // Ícone Facebook
		else if(redeNome.includes('youtube')) icon = "\uF62B ";  // Ícone Youtube
		else if(redeNome.includes('whatsapp')) icon = "\uF618 "; // Ícone WhatsApp
		else if(redeNome.includes('twitter') || redeNome.includes('x')) icon = "\uF5EF "; // Ícone Twitter/X
		else icon = "\uF3E5 "; // Ícone de Link genérico

		let textoFinal = icon + "@" + usuario;
		let color = document.getElementById('textColorPicker').value || '#000000';

		var t = new fabric.IText(textoFinal, {
			left: canvas.width / 2,
			top: canvas.height / 2 + 50, // Um pouco abaixo do centro para não encavalar
			fontSize: 26,
			fontFamily: 'bootstrap-icons', // Importante para o ícone aparecer
			fill: color,
			originX: 'center'
		});

		canvas.add(t);
		canvas.setActiveObject(t);
		canvas.renderAll();

		select.value = ""; // Reseta o combo para permitir selecionar a mesma rede de novo
	}

    function updateTextAlign(align) {
        let activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type.includes('text')) {
            activeObject.set('textAlign', align);
            canvas.renderAll();
        }
    }

    function updateTextStyle() {
        let activeObject = canvas.getActiveObject();
        if (activeObject && activeObject.type.includes('text')) {
            activeObject.set({
                fill: document.getElementById('textColorPicker').value,
                fontSize: parseInt(document.getElementById('textSizePicker').value),
                fontFamily: document.getElementById('fontFamilyPicker').value
            });
            canvas.renderAll();
        }
    }

    function updateControlPanel() {
        let obj = canvas.getActiveObject();
        if (obj && obj.type.includes('text')) {
            document.getElementById('textColorPicker').value = obj.fill;
            document.getElementById('textSizePicker').value = obj.fontSize;
            document.getElementById('fontFamilyPicker').value = obj.fontFamily;
            isBoldActive = (obj.fontWeight === 'bold');
            isItalicActive = (obj.fontStyle === 'italic');
            document.getElementById('btn-bold').classList.toggle('active', isBoldActive);
            document.getElementById('btn-italic').classList.toggle('active', isItalicActive);
        }
    }

    // --- 3. LOGOS E MEMBROS ---
    function addIgrejaLogo(tipo) {
        var arquivo = (tipo === 'ipb_completo') ? 'logo_ipb_completo.png' : 'logo_ipb.png';
        var urlFinal = "<?= url('assets/img/') ?>" + arquivo;
        fabric.Image.fromURL(urlFinal, function(img) {
            img.scaleToWidth(250);
            img.set({ left: canvas.width/2, top: 150, originX: 'center' });
            canvas.add(img).setActiveObject(img).renderAll();
        }, { crossOrigin: 'anonymous' });
    }

    function addSociedadeLogo() {
        var urlFinal = "<?= url('') ?>get_image.php?path=<?= $sociedade['sociedade_logo'] ?>";
        fabric.Image.fromURL(urlFinal, function(img) {
            img.scaleToWidth(180);
            img.set({ left: canvas.width/2, top: canvas.height/2, originX: 'center' });
            canvas.add(img).setActiveObject(img).renderAll();
        }, { crossOrigin: 'anonymous' });
    }

	function addInfoMembro(tipo) {
		let sel = document.getElementById('selectMembro');
		let opt = sel.options[sel.selectedIndex];

		// Substituindo o alert de seleção
		if(!opt || !opt.value) {
			return exibirAviso("Atenção", "Selecione um membro na lista antes de adicionar informações.", "bi-person-badge", "text-primary");
		}

		if(tipo === 'nome') {
			addTexto(opt.getAttribute('data-nome'), 30, true);
		} else if(tipo === 'endereco') {
			addTexto(opt.getAttribute('data-endereco-full'), 20);
		} else {
			let foto = opt.getAttribute('data-foto');

			// Substituindo o alert de falta de foto
			if(!foto || foto === "null" || foto === "") {
				return exibirAviso("Sem Foto", "Este membro não possui uma foto cadastrada no perfil.", "bi-camera-video-off", "text-danger");
			}

			let urlFinal = "<?= url('assets/uploads/') ?>" + opt.getAttribute('data-igreja') + "/membros/" + opt.getAttribute('data-registro') + "/" + foto;

			fabric.Image.fromURL(urlFinal, function(img) {
				img.scaleToWidth(250);
				canvas.add(img).setActiveObject(img).renderAll();
			}, { crossOrigin: 'anonymous' });
		}
    }

	function exibirAviso(titulo, mensagem, icone = 'bi-exclamation-circle', corIcone = 'text-warning') {
		const modalEl = document.getElementById('modalConfirmacao');
		const modal = new bootstrap.Modal(modalEl);

		// Ajusta o conteúdo para modo "Aviso"
		document.getElementById('confirmacaoTitulo').innerText = titulo;
		document.getElementById('confirmacaoMensagem').innerText = mensagem;

		const iconTag = document.getElementById('confirmacaoIcone');
		iconTag.className = `bi ${icone} ${corIcone}`;

		// Ajusta os botões: Esconde o "Não" e transforma o "Sim" em "OK"
		const btnSim = document.getElementById('btnConfirmarAcao');
		const btnNao = modalEl.querySelector('.btn-light');

		btnNao.style.display = 'none'; // Esconde o "Não"
		btnSim.innerText = 'Entendido';
		btnSim.className = 'btn btn-primary w-100 fw-bold';

		btnSim.onclick = function() {
			modal.hide();
		};

		// Ao fechar o modal, restauramos os botões para o padrão (para não quebrar a Limpeza)
		modalEl.addEventListener('hidden.bs.modal', function () {
			btnNao.style.display = 'block';
			btnSim.innerText = 'Sim';
			btnSim.className = 'btn btn-primary w-100 fw-bold';
		}, { once: true });

		modal.show();
	}

	function addLogoIgreja(url) {
		fabric.Image.fromURL(url, function(img) {
			// Redimensiona para um tamanho padrão de marca d'água/topo (ex: 150px)
			img.scaleToWidth(150);
			img.set({
				left: 50, // Posiciona um pouco afastado da borda esquerda
				top: 50,  // Posiciona um pouco afastado do topo
				cornerStyle: 'circle',
				transparentCorners: false
			});

			canvas.add(img);
			canvas.setActiveObject(img);
			canvas.renderAll();
		}, { crossOrigin: 'anonymous' });
	}

	// --- 3.1 SELETOR DE EMOJIS (PICMO) ---
	document.addEventListener('DOMContentLoaded', function() {
		const trigger = document.querySelector('#emoji-trigger');
		if (!trigger) return;

		// Inicializa o Popup do Picmo
		const picker = picmoPopup.createPopup({
			showSearch: true,
			placeholder: 'Buscar emoji...',
			referenceElement: trigger,
			triggerElement: trigger,
			position: 'bottom-start'
		}, {
			rootElement: document.body
		});

		// Abre/Fecha o seletor
		trigger.addEventListener('click', (e) => {
			e.preventDefault();
			picker.toggle();
		});

		// Evento ao selecionar o Emoji
		picker.addEventListener('emoji:select', selection => {
			let selectedSize = document.getElementById('textSizePicker').value || 60;

			// Criamos o emoji como um objeto de texto do Fabric
			var emojiObj = new fabric.IText(selection.emoji, {
				left: canvas.width / 2,
				top: canvas.height / 2,
				fontSize: parseInt(selectedSize),
				// Fontes que suportam emojis coloridos nativamente
				fontFamily: '"Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji", sans-serif',
				originX: 'center',
				originY: 'center'
			});

			canvas.add(emojiObj);
			canvas.setActiveObject(emojiObj);
			canvas.renderAll();
		});
	});

	// --- 4. GESTÃO DE ÍCONES BOOTSTRAP (SOLUÇÃO DEFINITIVA) ---
	// --- 4. GESTÃO DE ÍCONES BOOTSTRAP (CATEGORIAS + SOLUÇÃO MANUAL) ---
	let allIcons = [];
	const modalIconesEl = document.getElementById('modalIcones');
	let bootstrapModalInstance = null;

	// Mapeamento de palavras-chave para categorias
	const categoriasMap = {
		church: ['church', 'cross', 'book', 'heart', 'peace', 'sun', 'stars', 'infinity', 'hand', 'shield', 'fire'],
		social: ['facebook', 'instagram', 'youtube', 'whatsapp', 'twitter', 'telegram', 'github', 'linkedin', 'share', 'broadcast'],
		ui: ['house', 'person', 'gear', 'search', 'check', 'x-lg', 'plus', 'trash', 'pencil', 'info', 'telephone', 'envelope', 'calendar', 'clock'],
		arrows: ['arrow', 'chevron', 'caret', 'cursor', 'download', 'upload']
	};

	// 1. Função para Abrir o Modal Manualmente
	document.getElementById('btnAbrirModalIcones').addEventListener('click', function() {
		if (bootstrapModalInstance) {
			bootstrapModalInstance.dispose();
		}
		bootstrapModalInstance = new bootstrap.Modal(modalIconesEl);
		bootstrapModalInstance.show();
	});

	// 2. Carregamento e Renderização
	modalIconesEl.addEventListener('shown.bs.modal', async function () {
		if (allIcons.length > 0) return;
		const grid = document.getElementById('iconGrid');
		const loader = document.getElementById('loadingIcones');

		try {
			const response = await fetch('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
			const cssText = await response.text();
			const regex = /\.bi-([a-z0-9-]+)::before/g;
			let match;

			allIcons = [];
			while ((match = regex.exec(cssText)) !== null) {
				allIcons.push(match[1]);
			}

			grid.innerHTML = '';
			renderizarGrade(allIcons); // Inicialmente mostra todos
			loader.style.display = 'none';
		} catch (e) {
			console.error("Erro ao carregar ícones:", e);
		}
	}, { once: true });

	// 3. Função de Filtragem
	function filtrarIcones(cat, event) {
		const grid = document.getElementById('iconGrid');
		grid.innerHTML = '';

		// Atualiza visual dos botões de filtro
		document.querySelectorAll('.filter-btn').forEach(btn => {
			btn.classList.replace('btn-dark', 'btn-outline-dark');
		});

		// O evento pode ser nulo se chamado via código
		if(event) {
			event.currentTarget.classList.replace('btn-outline-dark', 'btn-dark');
		}

		const listaFiltrada = allIcons.filter(icon => {
			if (cat === 'all') return true;
			return categoriasMap[cat].some(key => icon.includes(key));
		});

		renderizarGrade(listaFiltrada);
	}

	// 4. Função que constrói os botões na grade
	function renderizarGrade(lista) {
		const grid = document.getElementById('iconGrid');
		grid.innerHTML = ''; // Limpa antes de renderizar

		lista.forEach(iconName => {
			const btn = document.createElement('button');
			btn.className = 'btn btn-outline-dark btn-sm p-2 icon-item';
			btn.style.width = '45px';
			btn.style.height = '45px';
			btn.title = iconName;
			btn.innerHTML = `<i class="bi bi-${iconName}" style="font-size: 1.2rem;"></i>`;

			btn.onclick = () => {
				const iElement = btn.querySelector('i');
				const style = window.getComputedStyle(iElement, '::before');
				const content = style.getPropertyValue('content').replace(/['"]/g, '');
				addIconeNoBanner(content);
			};
			grid.appendChild(btn);
		});
	}

	// 5. Inserção do Ícone e Limpeza de Estado
	function addIconeNoBanner(unicode) {
		if (bootstrapModalInstance) {
			bootstrapModalInstance.hide();
		}

		// Limpeza forçada para evitar travamento de tela
		setTimeout(() => {
			document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
			document.body.classList.remove('modal-open');
			document.body.style.overflow = '';

			let size = document.getElementById('textSizePicker').value || 60;
			let color = document.getElementById('textColorPicker').value || '#000000';

			var iconText = new fabric.IText(unicode, {
				left: canvas.width / 2,
				top: canvas.height / 2,
				fontSize: parseInt(size),
				fontFamily: 'bootstrap-icons',
				fill: color,
				originX: 'center',
				originY: 'center'
			});

			canvas.add(iconText);
			canvas.setActiveObject(iconText);
			canvas.renderAll();
		}, 150);
	}

	// 3. Inserção do Ícone e Limpeza Rigorosa
	function addIconeNoBanner(unicode) {
		// Primeiro: Fecha o modal imediatamente
		if (bootstrapModalInstance) {
			bootstrapModalInstance.hide();
		}

		// Segundo: Limpa o backdrop e classes do body manualmente (Garantia extra)
		setTimeout(() => {
			document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
			document.body.classList.remove('modal-open');
			document.body.style.overflow = '';
			document.body.style.paddingRight = '';

			// Terceiro: Inserção no Fabric.js
			let size = document.getElementById('textSizePicker').value || 60;
			let color = document.getElementById('textColorPicker').value || '#000000';

			var iconText = new fabric.IText(unicode, {
				left: canvas.width / 2,
				top: canvas.height / 2,
				fontSize: parseInt(size),
				fontFamily: 'bootstrap-icons',
				fill: color,
				originX: 'center',
				originY: 'center'
			});

			canvas.add(iconText);
			canvas.setActiveObject(iconText);
			canvas.renderAll();
		}, 100);
	}

    // --- 5. IMAGENS AVULSAS E FUNDO ---
    document.getElementById('imageLoader').onchange = function(e) {
        var reader = new FileReader();
        reader.onload = function(event) {
            fabric.Image.fromURL(event.target.result, function(img) {
                img.scaleToWidth(300);
                img.set({ left: canvas.width/2, top: canvas.height/2, originX: 'center', originY: 'center' });
                canvas.add(img).setActiveObject(img).renderAll();
            });
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
    };

    document.getElementById('bgInput').onchange = function(e) {
        var reader = new FileReader();
        reader.onload = function(f) {
            var imgObj = new Image();
            imgObj.src = f.target.result;
            imgObj.onload = function() {
                var image = new fabric.Image(imgObj);
                canvas.setBackgroundImage(image, canvas.renderAll.bind(canvas), {
                    scaleX: canvas.width / image.width,
                    scaleY: canvas.height / image.height
                });
            }
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
    };

    function clearBackground() { canvas.setBackgroundImage(null, canvas.renderAll.bind(canvas)); }

    // --- 6. UTILITÁRIOS E SALVAMENTO ---
    function deleteObject() {
        canvas.getActiveObjects().forEach(obj => canvas.remove(obj));
        canvas.discardActiveObject().renderAll();
    }

    // LIMPEZA GERAL COM MODAL
	// 1. Prepara e abre o modal
	function confirmarLimpeza() {
		const modal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));

		// Configura o botão de confirmação para executar a limpeza
		const btnSim = document.getElementById('btnConfirmarAcao');

		// Remove qualquer listener antigo para não duplicar a ação
		btnSim.onclick = function() {
			executarLimpezaCanvas();
			modal.hide();
		};

		modal.show();
	}

	// 2. Ação real de limpeza (executada apenas se clicar em Sim)
	function executarLimpezaCanvas() {
		// Limpa os objetos
		canvas.clear();

		// Restaura o fundo branco (ou a cor/imagem padrão que você usa)
		canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas));

		// Opcional: feedback visual rápido (Toast ou Log)
		console.log("Canvas resetado com sucesso.");
    }

    function trazerParaFrente() { canvas.getActiveObject()?.bringToFront(); canvas.renderAll(); }
    function enviarParaTras() { canvas.getActiveObject()?.sendBackwards(); canvas.renderAll(); }
    function centralizarH() { let obj = canvas.getActiveObject(); if(obj) { obj.set('left', canvas.width / 2); obj.setCoords(); canvas.renderAll(); } }

    function downloadBanner() {
        canvas.discardActiveObject().renderAll();
        var link = document.createElement('a');
        link.download = 'banner_ekklesia.png';
        link.href = canvas.toDataURL({ format: 'png', multiplier: 2 });
        link.click();
    }

    function salvarLayout() {
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        const layoutJson = JSON.stringify(canvas.toJSON());

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        const formData = new FormData();
        formData.append('id', "<?= $sociedade['sociedade_id'] ?>");
        formData.append('layout', layoutJson);

        fetch("<?= url('sociedadeLider/salvar_layout_banner') ?>", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.sucesso) alert("Layout salvo com sucesso!");
            else alert("Erro: " + data.mensagem);
        })
        .catch(err => alert("Erro ao conectar com o servidor."))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    // Atalhos de teclado
    document.addEventListener('keydown', function(e) {
        if ((e.key === "Delete" || e.key === "Backspace") && canvas.getActiveObject() && !canvas.getActiveObject().isEditing) {
            deleteObject();
        }
    });

	// --- GESTÃO DE FERRAMENTAS DE IMAGEM (FABRIC.JS) ---

	const imgToolsPanel = document.getElementById('imageToolsPanel');

	// 1. Ouvinte para mostrar/esconder o painel ao selecionar objetos
	canvas.on('selection:created', mostrarPainelImagem);
	canvas.on('selection:updated', mostrarPainelImagem);
	canvas.on('selection:cleared', fecharPainelImagem);

	function mostrarPainelImagem() {
		let activeObject = canvas.getActiveObject();
		// Mostra o painel apenas se for uma imagem ou um grupo de imagem (para máscara circular)
		if (activeObject && (activeObject.type === 'image' || (activeObject.type === 'group' && activeObject._objects[0].type === 'image'))) {
			imgToolsPanel.style.display = 'block';

			// Atualiza os sliders com os valores atuais do objeto
			document.getElementById('imgRotationFree').value = Math.round(activeObject.angle);
			document.getElementById('rotationVal').innerText = Math.round(activeObject.angle) + "°";
			document.getElementById('imgOpacity').value = activeObject.opacity * 100;
			document.getElementById('opacityVal').innerText = Math.round(activeObject.opacity * 100) + "%";

			// Atualiza o estado visual dos botões de filtro
			if (activeObject.type === 'image' && activeObject.filters) {
				document.getElementById('filter-grey').classList.toggle('active', activeObject.filters.some(f => f.type === 'Grayscale'));
				document.getElementById('filter-blur').classList.toggle('active', activeObject.filters.some(f => f.type === 'Blur'));
			}

		} else {
			fecharPainelImagem();
		}
	}

	function fecharPainelImagem() {
		imgToolsPanel.style.display = 'none';
	}

	// --- FUNÇÕES DE MANIPULAÇÃO DIRETA ---

	// Girar (Rotate): 90 graus fixos
	function girarObjeto(graus) {
		let activeObject = canvas.getActiveObject();
		if (activeObject) {
			// Pega o ângulo atual e soma/subtrai 90
			let currentAngle = activeObject.angle;
			let newAngle = (currentAngle + graus) % 360;

			activeObject.rotate(newAngle);

			// Atualiza o slider de rotação livre
			document.getElementById('imgRotationFree').value = Math.round(newAngle);
			document.getElementById('rotationVal').innerText = Math.round(newAngle) + "°";

			canvas.requestRenderAll();
		}
	}

	// Girar Livre (Slider)
	function girarObjetoLivre(valor) {
		let activeObject = canvas.getActiveObject();
		if (activeObject) {
			activeObject.rotate(parseInt(valor));
			document.getElementById('rotationVal').innerText = valor + "°";
			canvas.requestRenderAll();
		}
	}

	// Opacidade (Slider)
	function ajustarOpacidade(valor) {
		let activeObject = canvas.getActiveObject();
		if (activeObject) {
			// Opacidade no Fabric vai de 0 a 1
			activeObject.set('opacity', parseInt(valor) / 100);
			document.getElementById('opacityVal').innerText = valor + "%";
			canvas.requestRenderAll();
		}
	}

	// Inverter Horizontalmente (Flip X)
	function inverterObjeto(eixo) {
		let activeObject = canvas.getActiveObject();
		if (activeObject) {
			// Se for eixo X, inverte o flipX (booleano)
			if (eixo === 'X') {
				activeObject.set('flipX', !activeObject.flipX);
			}
			canvas.requestRenderAll();
		}
	}

	// --- FILTROS DE IMAGEM ---

	function alternarFiltro(filtroNome) {
		let activeObject = canvas.getActiveObject();
		// Filtros funcionam apenas em objetos do tipo 'image' (não em grupos de máscara)
		if (activeObject && activeObject.type === 'image') {
			let filtro;
			let btnId;

			// Cria o filtro ou define qual remover
			if (filtroNome === 'greyscale') {
				filtro = new fabric.Image.filters.Grayscale();
				btnId = 'filter-grey';
			} else if (filtroNome === 'blur') {
				filtro = new fabric.Image.filters.Blur({ blur: 0.5 }); // Valor de blur suave
				btnId = 'filter-blur';
			}

			// Verifica se o filtro já está aplicado
			let filterIndex = activeObject.filters.findIndex(f => f.type === filtro.type);

			if (filterIndex > -1) {
				// Se já tem, remove
				activeObject.filters.splice(filterIndex, 1);
				document.getElementById(btnId).classList.remove('active');
			} else {
				// Se não tem, adiciona
				activeObject.filters.push(filtro);
				document.getElementById(btnId).classList.add('active');
			}

			// Aplica e renderiza (Essencial para filtros)
			activeObject.applyFilters();
			canvas.requestRenderAll();
		} else if (activeObject && activeObject.type === 'group') {
			alert("Filtros rápidos funcionam apenas na imagem original, não na foto circular.");
		}
	}

	function removerTodosFiltros() {
		let activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'image') {
			activeObject.filters = [];
			activeObject.applyFilters();

			// Remove estado ativo dos botões
			document.querySelectorAll('.filter-btn-img').forEach(btn => btn.classList.remove('active'));

			canvas.requestRenderAll();
		}
	}

	// --- MÁSCARA CIRCULAR (FOTO DE PERFIL) ---
	// Nota: Esta é a mais complexa, pois envolve criar uma forma e usar o 'clipPath'
	function aplicarMascaraCircular() {
		let activeObject = canvas.getActiveObject();

		// Verificamos se é uma imagem simples
		if (activeObject && activeObject.type === 'image') {

			if (activeObject.clipPath) {
				alert("Esta imagem já possui um recorte.");
				return;
			}

			// 1. Deseleciona temporariamente para limpar o estado
			canvas.discardActiveObject();

			// 2. Armazena a posição e escala originais da imagem
			let imgOrig = activeObject;
			let originalLeft = imgOrig.left;
			let originalTop = imgOrig.top;
			let originalScaleX = imgOrig.scaleX;
			let originalScaleY = imgOrig.scaleY;
			let originalAngle = imgOrig.angle;

			// 3. Remove a imagem do canvas (ela vai ser reinserida no grupo)
			canvas.remove(imgOrig);

			// 4. Calcula o tamanho do recorte (raio) baseado nas dimensões nativas
			let minSideNative = Math.min(imgOrig.width, imgOrig.height);
			let radiusNative = minSideNative / 2;

			// 5. Cria o círculo de recorte (clipPath)
			// No Fabric v5+, (0,0) é o CENTRO da imagem para o clipPath.
			let circleMask = new fabric.Circle({
				radius: radiusNative,
				originX: 'center',
				originY: 'center',
				left: 0,
				top: 0,
				fill: 'black' // Cor não importa para o clipPath
			});

			// 6. Aplica a máscara à imagem nativa
			imgOrig.set({
				clipPath: circleMask,
				originX: 'center', // Centraliza a imagem em si mesma
				originY: 'center',
				left: 0,           // Posição (0,0) relativa ao centro do grupo
				top: 0,
				scaleX: originalScaleX, // Mantém a escala original
				scaleY: originalScaleY,
				dirty: true // Força o re-render
			});

			// 7. Cria a borda visual (stroke)
			// Ela deve ter o tamanho do recorte nativo, escalado pela escala da imagem
			let borderCircle = new fabric.Circle({
				radius: radiusNative * originalScaleX, // Raio escalado
				originX: 'center',
				originY: 'center',
				left: 0, // Posição (0,0) relativa ao centro do grupo
				top: 0,
				fill: 'transparent',
				stroke: '#000000', // Borda preta por padrão
				strokeWidth: 2,
				selectable: false, // Borda segue a imagem no grupo
				evented: false     // Não aceita cliques
			});

			// 8. Cria o grupo perfeitamente alinhado no centro
			// Ambos os objetos (imagem e borda) estão em (0,0) relativos ao centro do grupo
			let group = new fabric.Group([imgOrig, borderCircle], {
				left: originalLeft,   // Mantém a posição original da imagem no canvas
				top: originalTop,
				originX: 'center',
				originY: 'center',
				angle: originalAngle, // Mantém o ângulo original
				subTargetCheck: false // O grupo é tratado como um único objeto
			});

			// 9. Atualiza as coordenadas do grupo para garantir alinhamento
			group.setCoords();

			// 10. Adicionamos o grupo ao canvas e selecionamos
			canvas.add(group);
			canvas.setActiveObject(group);
			canvas.renderAll();

		} else {
			alert("Selecione uma imagem para aplicar o recorte circular.");
		}
	}

	// 1. Adiciona o Nome da Sociedade como texto estilizado
	function addTextoSociedade(nome) {
		const texto = new fabric.IText(nome, {
			left: canvas.width / 2,
			top: canvas.height / 2,
			fontSize: 40,
			fontFamily: 'Montserrat', // Ou a fonte que você estiver usando
			fontWeight: 'bold',
			fill: '#000000',
			originX: 'center',
			originY: 'center'
		});
		canvas.add(texto);
		canvas.setActiveObject(texto);
		canvas.renderAll();
	}

	// 2. Adiciona a foto do Pastor (já podemos aplicar a máscara circular direto se quiser!)
	function addFotoPastor(url) {
		fabric.Image.fromURL(url, function(img) {
			// Redimensiona para um tamanho padrão de "perfil"
			img.scaleToWidth(250);
			img.set({
				left: canvas.width / 2,
				top: canvas.height / 2,
				originX: 'center',
				originY: 'center'
			});

			canvas.add(img);
			canvas.setActiveObject(img);

			// Dica: Se quiser que já entre com o CROP circular, chame a função:
			// aplicarMascaraCircular();

			canvas.renderAll();
		}, { crossOrigin: 'anonymous' });
	}

    // SALVAR O LAYOUT DO BANNER
	function salvarBanner() {
		// Pega o estado atual do canvas como string JSON
		const dadosLayout = JSON.stringify(canvas.toJSON());

		const formData = new FormData();
		formData.append('layout', dadosLayout);

		// Usa a função url() do seu PHP para gerar o caminho correto
		fetch('<?= url("sociedadeLider/salvarBanner") ?>', {
			method: 'POST',
			body: formData
		})
		.then(response => response.json())
		.then(data => {
			if(data.status === 'success') {
				alert('Configuração do Banner salva com sucesso!');
			} else {
				alert('Erro ao salvar: ' + data.message);
			}
		})
		.catch(error => {
			console.error('Erro:', error);
			alert('Erro de conexão com o servidor.');
		});
	}


    // --- 7. CARREGAMENTO INICIAL ---
    <?php if (!empty($sociedade['sociedade_layout_config'])): ?>
        try {
            const layoutSaved = <?= json_encode($sociedade['sociedade_layout_config']) ?>;
            if (layoutSaved && layoutSaved !== "null") {
                canvas.loadFromJSON(layoutSaved, function() {
                    canvas.renderAll();
                });
            }
        } catch(err) { console.error("Erro ao carregar layout:", err); }
    <?php endif; ?>
</script>
