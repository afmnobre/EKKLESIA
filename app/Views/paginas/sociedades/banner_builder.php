<style>
    /* Garante que o Fabric.js reconheça a fonte dos ícones */
    @font-face {
        font-family: 'bootstrap-icons';
        src: url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/fonts/bootstrap-icons.woff2') format('woff2');
    }

    #iconPicker, .bi-font {
        font-family: 'bootstrap-icons', 'Segoe UI', Arial;
    }

    .btn-xs { padding: 0.25rem 0.4rem; font-size: 0.75rem; }
    canvas { border: 1px solid #ddd; }

    .btn-outline-secondary.active {
        background-color: #6c757d !important;
        color: white !important;
    }

</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-image-fill me-2"></i>Gerador de Banner</h3>
        <div>
            <button class="btn btn-outline-danger btn-sm me-2" onclick="limparCanvas()"><i class="bi bi-trash"></i> Limpar Tudo</button>
            <a href="<?= url('sociedades') ?>" class="btn btn-outline-secondary btn-sm">Voltar</a>
        </div>
    </div>

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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white p-2">
                    <ul class="nav nav-pills nav-justified small" id="bannerTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active text-white py-1" data-bs-toggle="pill" data-bs-target="#tab-texto">Texto</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-white py-1" data-bs-toggle="pill" data-bs-target="#tab-igreja">Igreja/Membros</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-white py-1" data-bs-toggle="pill" data-bs-target="#tab-imagens">Imagens/Layers</button>
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
    <button type="button" id="emoji-trigger" class="btn btn-sm btn-outline-dark w-100 d-flex align-items-center justify-content-between">
        <span>Inserir Emoji</span> <span style="font-size: 1.1rem;">😊</span>
    </button>
	<button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2 d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#modalIcones">
		<span>Inserir Ícone Bootstrap</span> <i class="bi bi-star-fill"></i>
	</button>
</div>

	<div class="tab-pane fade" id="tab-igreja">
		<h6 class="fw-bold small mb-2 text-primary border-bottom pb-1">Dados da Igreja</h6>
		<div class="row g-2 mb-3">
			<div class="col-6">
				<button class="btn btn-outline-info btn-xs w-100 text-truncate" onclick="addTexto('<?= addslashes($igreja['igreja_nome']) ?>', 35, true)">Nome Igreja</button>
			</div>
			<div class="col-6">
				<button class="btn btn-outline-dark btn-xs w-100 text-truncate" onclick="addTexto('Pr. <?= addslashes($igreja['pastor_nome']) ?>', 28, true)">Nome Pastor</button>
			</div>
			<div class="col-6">
				<button class="btn btn-outline-secondary btn-xs w-100 text-truncate" onclick="addTexto('<?= addslashes($igreja['igreja_endereco']) ?>', 20)">Endereço</button>
			</div>
			<div class="col-6">
				<select class="form-select form-select-xs py-1" style="font-size: 0.75rem;" onchange="addRedeSocial(this)">
					<option value="">Redes Sociais...</option>
					<?php if(!empty($redes)): foreach($redes as $r): ?>
						<option value="<?= $r['rede_usuario'] ?>" data-nome="<?= $r['rede_nome'] ?>">@<?= $r['rede_usuario'] ?></option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>

		<h6 class="fw-bold small mb-2 text-success border-bottom pb-1">Membros</h6>
		<select id="selectMembro" class="form-select form-select-sm mb-2">
			<option value="">Selecione um membro...</option>
			<?php if(!empty($membros)): foreach($membros as $m):
				$enderecoCompleto = trim((addslashes($m['membro_endereco_rua'] ?? '')) . ", " . (addslashes($m['membro_endereco_numero'] ?? '')) . " - " . (addslashes($m['membro_endereco_bairro'] ?? '')) . ", " . (addslashes($m['membro_endereco_cidade'] ?? '')), " ,-");
			?>
				<option value="<?= $m['membro_id'] ?>"
						data-nome="<?= addslashes($m['membro_nome']) ?>"
						data-foto="<?= $m['membro_foto_arquivo'] ?>"
						data-igreja="<?= $m['membro_igreja_id'] ?>"
						data-registro="<?= $m['membro_registro_interno'] ?>"
						data-endereco-full="<?= $enderecoCompleto ?: 'Sem endereço' ?>">
					<?= $m['membro_nome'] ?>
				</option>
			<?php endforeach; endif; ?>
		</select>

		<div class="row g-2">
			<div class="col-6"><button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('foto')">Add Foto</button></div>
			<div class="col-6"><button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('endereco')">Add Endereço</button></div>
			<div class="col-12 mt-1"><button class="btn btn-xs btn-success w-100" onclick="addInfoMembro('nome')">Add Nome do Membro</button></div>
		</div>
	</div>

                    <div class="tab-pane fade" id="tab-imagens">
                        <div class="row g-1 mb-2">
                            <div class="col-4"><button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb')">IPB</button></div>
                            <div class="col-4"><button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb_completo')">IPB Compl.</button></div>
                            <div class="col-4"><button class="btn btn-outline-primary btn-xs w-100" onclick="addSociedadeLogo()">Sociedade</button></div>
                        </div>

                        <div class="bg-light p-2 rounded mb-2">
                            <label class="x-small fw-bold d-block mb-1">Upload de Imagem:</label>
                            <input type="file" id="imageLoader" class="form-control form-control-sm mb-1" accept="image/*">
                            <label class="x-small fw-bold d-block mb-1">Fundo (Background):</label>
                            <div class="input-group input-group-sm">
                                <input type="file" id="bgInput" class="form-control" accept="image/*">
                                <button class="btn btn-outline-danger" onclick="clearBackground()"><i class="bi bi-x"></i></button>
                            </div>
                        </div>

                        <div class="row g-1">
                            <div class="col-4"><button class="btn btn-xs btn-light border w-100" onclick="trazerParaFrente()"><i class="bi bi-layer-forward"></i></button></div>
                            <div class="col-4"><button class="btn btn-xs btn-light border w-100" onclick="enviarParaTras()"><i class="bi bi-layer-backward"></i></button></div>
                            <div class="col-4"><button class="btn btn-xs btn-outline-danger w-100" onclick="deleteObject()"><i class="bi bi-trash"></i></button></div>
                            <div class="col-12 mt-1">
                                <button class="btn btn-xs btn-light border w-100" onclick="centralizarH()"><i class="bi bi-align-center"></i> Centralizar H</button>
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
            <div class="modal-body p-3">
                <div class="input-group mb-3 shadow-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-filter"></i></span>
                    <input type="text" id="searchIcon" class="form-control" placeholder="Digite para filtrar (ex: church, music, social)..." onkeyup="filtrarIcones()">
                </div>

                <div id="loadingIcones" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="small text-muted mt-2">Carregando biblioteca...</p>
                </div>

                <div id="iconGrid" class="d-flex flex-wrap gap-2 justify-content-center"></div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/picmo@5.8.5/dist/umd/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@picmo/popup-picker@5.8.5/dist/umd/index.js"></script>

<script>
    // Configuração Inicial
    var canvas = new fabric.Canvas('editorCanvas', {
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    canvas.on('selection:created', updateControlPanel);
    canvas.on('selection:updated', updateControlPanel);

	canvas.on('mouse:over', function(e) {
		if(e.target) {
			e.target.set('stroke', '#007bff'); // Borda azul ao passar o mouse
			e.target.set('strokeWidth', 1);
			canvas.renderAll();
		}
	});

	canvas.on('mouse:out', function(e) {
		if(e.target) {
			e.target.set('strokeWidth', 0); // Remove a borda ao sair
			canvas.renderAll();
		}
	});

    // Garante carregamento da fonte antes da primeira inserção
    if (document.fonts) {
        document.fonts.load('1em bootstrap-icons');
    }

	// --- FUNÇÕES DE TEXTO E ESTILO (REVISADAS) ---
	// Variáveis de estado globais para o estilo
	let isBoldActive = false;
	let isItalicActive = false;

	// 1. Liga/Desliga Negrito e Itálico (Interface <-> Canvas)
	function toggleStyle(style) {
		if (style === 'bold') {
			isBoldActive = !isBoldActive;
			document.getElementById('btn-bold').classList.toggle('active', isBoldActive);
		} else if (style === 'italic') {
			isItalicActive = !isItalicActive;
			document.getElementById('btn-italic').classList.toggle('active', isItalicActive);
		}

		// Se houver um texto selecionado, aplica a mudança imediatamente
		let activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type.includes('text')) {
			if (style === 'bold') activeObject.set('fontWeight', isBoldActive ? 'bold' : 'normal');
			if (style === 'italic') activeObject.set('fontStyle', isItalicActive ? 'italic' : 'normal');
			canvas.renderAll();
		}
	}

	// 2. Insere o Texto no Banner (Sempre como Textbox para permitir Justificar)
	function addTextoLivre() {
		let val = document.getElementById('customTextInput').value;
		if(!val.trim()) return;

		let size = document.getElementById('textSizePicker').value;
		let color = document.getElementById('textColorPicker').value;
		let font = document.getElementById('fontFamilyPicker').value;

		var t = new fabric.Textbox(val, {
			left: 400,
			top: 400,
			width: 350, // Largura para o Justify funcionar
			fontSize: parseInt(size),
			fontWeight: isBoldActive ? 'bold' : 'normal',
			fontStyle: isItalicActive ? 'italic' : 'normal',
			fontFamily: font,
			fill: color,
			textAlign: 'center',
			originX: 'center',
			originY: 'center',
			cornerColor: '#007bff',
			transparentCorners: false,
			borderColor: '#007bff'
		});

		canvas.add(t);
		canvas.setActiveObject(t);
		canvas.renderAll();

		// Limpa o campo após inserir
		document.getElementById('customTextInput').value = "";
	}

	// 3. Alinhamento (Esquerda, Centro, Direita, Justificado)
	function updateTextAlign(align) {
		let activeObject = canvas.getActiveObject();
		// Verificamos se é um tipo de texto que suporta alinhamento
		if (activeObject && activeObject.type.includes('text')) {
			activeObject.set('textAlign', align);
			canvas.renderAll();
		}
	}

	// 4. Atualiza Cor, Tamanho e Fonte do texto selecionado
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

	// 5. Sincroniza a Interface quando você clica em um texto existente
	function updateControlPanel() {
		let obj = canvas.getActiveObject();
		if (obj && obj.type.includes('text')) {
			// Atualiza os inputs de valor
			document.getElementById('textColorPicker').value = obj.fill;
			document.getElementById('textSizePicker').value = obj.fontSize;
			document.getElementById('fontFamilyPicker').value = obj.fontFamily;

			// Atualiza o estado das variáveis e o visual dos botões
			isBoldActive = (obj.fontWeight === 'bold');
			isItalicActive = (obj.fontStyle === 'italic');

			document.getElementById('btn-bold').classList.toggle('active', isBoldActive);
			document.getElementById('btn-italic').classList.toggle('active', isItalicActive);
		}
	}

	// Função "Ponte" para os botões do PHP (Igreja, Pastor, Membro)
	function addTexto(val, size = null, bold = false) {
		if(!val) return;

		// Pega os valores atuais dos seletores ou os valores vindos do botão
		let defaultSize = size || document.getElementById('textSizePicker').value;
		let color = document.getElementById('textColorPicker').value;
		let font = document.getElementById('fontFamilyPicker').value;

		var t = new fabric.Textbox(val, {
			left: 400,
			top: 400,
			width: 400, // Largura padrão para nomes longos
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


    // --- ÍCONES ---
	// Aguarda o documento carregar para evitar erros de elemento não encontrado
	document.addEventListener('DOMContentLoaded', function() {
		const trigger = document.querySelector('#emoji-trigger');

		// Inicializa o Picker da Picmo
		const picker = picmoPopup.createPopup({
			// Configurações visuais
			showSearch: true,
			placeholder: 'Buscar emoji...',
			// Posicionamento em relação ao botão
			referenceElement: trigger,
			triggerElement: trigger,
			position: 'bottom-start'
		}, {
			// Elemento onde o popup será renderizado (body evita problemas de z-index)
			rootElement: document.body
		});

		// Abre o seletor ao clicar no botão
		trigger.addEventListener('click', () => {
			picker.toggle();
		});

		// Evento disparado ao escolher um emoji
		picker.addEventListener('emoji:select', selection => {
			// Pega o tamanho selecionado no seu input de texto para manter a consistência
			let selectedSize = document.getElementById('textSizePicker').value || 60;

			var emojiText = new fabric.IText(selection.emoji, {
				left: 400,
				top: 400,
				fontSize: parseInt(selectedSize),
				fontFamily: 'Segoe UI Emoji, Apple Color Emoji, sans-serif',
				originX: 'center',
				originY: 'center'
			});

			// Adiciona ao canvas global
			canvas.add(emojiText);
			canvas.setActiveObject(emojiText);
			canvas.renderAll();
		});
	});

	// --- FUNÇÃO PARA ADICIONAR IMAGEM AVULSA ---
	document.getElementById('imageLoader').onchange = function(e) {
		var reader = new FileReader();

		reader.onload = function(event) {
			var imgObj = new Image();
			imgObj.src = event.target.result;

			imgObj.onload = function() {
				// Cria o objeto de imagem do Fabric.js
				var image = new fabric.Image(imgObj);

				// Configurações iniciais da imagem inserida
				image.set({
					left: canvas.width / 2, // Centraliza horizontalmente
					top: canvas.height / 2, // Centraliza verticalmente
					originX: 'center',
					originY: 'center',
					cornerColor: '#007bff', // Cor dos cantinhos de redimensionar (azul IPB)
					cornerSize: 10,
					transparentCorners: false,
					borderColor: '#007bff',
					borderDashArray: [3, 3] // Borda pontilhada ao selecionar
				});

				// Redimensiona automaticamente se a imagem for muito grande para o canvas
				if (image.width > canvas.width * 0.8) {
					image.scaleToWidth(canvas.width * 0.5); // Limita a 50% da largura do canvas
				} else if (image.height > canvas.height * 0.8) {
					image.scaleToHeight(canvas.height * 0.5);
				}

				// Adiciona ao canvas, define como ativo e renderiza
				canvas.add(image);
				canvas.setActiveObject(image);
				canvas.renderAll();

				// Reseta o input file para permitir adicionar a mesma imagem novamente se necessário
				document.getElementById('imageLoader').value = '';
			}
		}

		// Lê o arquivo selecionado como URL de dados (base64)
		if (e.target.files[0]) {
			reader.readAsDataURL(e.target.files[0]);
		}
	};

    // --- MEMBROS E LOGOS ---
	function addInfoMembro(tipo) {
		let sel = document.getElementById('selectMembro');
		let opt = sel.options[sel.selectedIndex];
		if(!opt || !opt.value) return alert("Selecione um membro primeiro.");

		if(tipo === 'endereco') {
			let endereco = opt.getAttribute('data-endereco-full');
			addTexto(endereco, 20);
		} else if(tipo === 'nome') {
			let nome = opt.getAttribute('data-nome');
			addTexto(nome, 30, true);
		} else {
			// Lógica da Foto
			let foto = opt.getAttribute('data-foto');
			let idIgreja = opt.getAttribute('data-igreja');
			let registro = opt.getAttribute('data-registro');
			if(!foto || foto === "null") return alert("Sem foto cadastrada.");

			let urlFinal = "<?= url('assets/uploads/') ?>" + idIgreja + "/membros/" + registro + "/" + foto;
			fabric.Image.fromURL(urlFinal, function(img) {
				img.scaleToWidth(250);
				canvas.add(img);
				canvas.setActiveObject(img);
				canvas.renderAll();
			}, { crossOrigin: 'anonymous' });
		}
	}

    function addIgrejaLogo(tipo) {
        var arquivo = (tipo === 'ipb_completo') ? 'logo_ipb_completo.png' : 'logo_ipb.png';
        var urlFinal = "<?= url('assets/img/') ?>" + arquivo;
        fabric.Image.fromURL(urlFinal, function(img) {
            img.scaleToWidth(250);
            img.set({ left: 400, top: 150, originX: 'center' });
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
        }, { crossOrigin: 'anonymous' });
    }

    function addSociedadeLogo() {
        var urlFinal = "<?= url('') ?>get_image.php?path=<?= $sociedade['sociedade_logo'] ?>";
        fabric.Image.fromURL(urlFinal, function(img) {
            img.scaleToWidth(180);
            canvas.add(img);
            canvas.setActiveObject(img);
            canvas.renderAll();
        }, { crossOrigin: 'anonymous' });
    }

    // --- UTILITÁRIOS ---
    function trazerParaFrente() { canvas.getActiveObject()?.bringToFront(); canvas.renderAll(); }
    function enviarParaTras() { canvas.getActiveObject()?.sendBackwards(); canvas.renderAll(); }
    function centralizarH() {
        let obj = canvas.getActiveObject();
        if(obj) { obj.set('left', canvas.width / 2); obj.setCoords(); canvas.renderAll(); }
    }
    function deleteObject() { canvas.getActiveObjects().forEach(obj => canvas.remove(obj)); canvas.discardActiveObject().renderAll(); }
    function limparCanvas() { if(confirm("Limpar tudo?")) { canvas.clear(); canvas.setBackgroundColor('#ffffff', canvas.renderAll.bind(canvas)); } }

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
        reader.readAsDataURL(e.target.files[0]);
    };

    function clearBackground() { canvas.setBackgroundImage(null, canvas.renderAll.bind(canvas)); }

    function downloadBanner() {
        canvas.discardActiveObject().renderAll();
        var link = document.createElement('a');
        link.download = 'banner_ekklesia.png';
        link.href = canvas.toDataURL({ format: 'png', multiplier: 2 });
        link.click();
    }

	// 1. Restaurar Função de Redes Sociais com Ícone Automático
	function addRedeSocial(select) {
		if(!select.value) return;

		let usuario = select.value;
		let redeNome = select.options[select.selectedIndex].getAttribute('data-nome').toLowerCase();

		// Mapeamento de ícones do Bootstrap (Códigos Hex)
		let icon = "";
		if(redeNome.includes('instagram')) icon = "\uF437 "; // Ícone Instagram
		else if(redeNome.includes('facebook')) icon = "\uF344 "; // Ícone Facebook
		else if(redeNome.includes('youtube')) icon = "\uF62B "; // Ícone Youtube
		else if(redeNome.includes('whatsapp')) icon = "\uF618 "; // Ícone WhatsApp
		else icon = "\uF3E5 "; // Ícone de Link genérico

		let textoFinal = icon + "@" + usuario;

		var t = new fabric.IText(textoFinal, {
			left: 400, top: 460,
			fontSize: 26,
			fontFamily: 'bootstrap-icons', // Usa a fonte de ícones para renderizar o primeiro caractere
			fill: document.getElementById('textColorPicker').value,
			originX: 'center'
		});

		canvas.add(t);
		canvas.setActiveObject(t);
		canvas.renderAll();

		select.value = ""; // Reseta o combo
	}

	// 2. Restaurar Atalho do Teclado (Delete/Backspace)
	document.addEventListener('keydown', function(e) {
		// Só deleta se não estiver editando um texto dentro do canvas
		let activeObject = canvas.getActiveObject();
		if ((e.key === "Delete" || e.key === "Backspace") && activeObject) {
			if(!activeObject.isEditing) {
				deleteObject();
			}
		}
	});

	// 3. Função Auxiliar para Centralizar Vertical (Opcional, mas ajuda muito)
	function centralizarV() {
		let obj = canvas.getActiveObject();
		if(obj) {
			obj.set('top', canvas.height / 2);
			obj.setCoords();
			canvas.renderAll();
		}
	}

function salvarLayout() {
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;

    // Captura o JSON do canvas
    const layoutJson = JSON.stringify(canvas.toJSON());
    const idSociedade = "<?= $sociedade['sociedade_id'] ?>";

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

    const formData = new FormData();
    formData.append('id', idSociedade);
    formData.append('layout', layoutJson);

    fetch("<?= url('sociedades/salvar_layout') ?>", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) // Lemos como texto primeiro para capturar erros do PHP
    .then(text => {
        // DEPURAÇÃO: Mostra no console exatamente o que o PHP respondeu
        console.log("--- RESPOSTA BRUTA DO SERVIDOR ---");
        console.log(text);
        console.log("----------------------------------");

        try {
            const data = JSON.parse(text); // Tenta converter o texto para objeto JSON

            if(data.sucesso) {
                alert("Layout salvo com sucesso!");
            } else {
                alert("Erro no Servidor: " + (data.mensagem || "Erro desconhecido"));
            }
        } catch (e) {
            // Se cair aqui, o PHP mandou um erro formatado em HTML (o que quebra o JSON)
            console.error("Falha ao processar JSON. O servidor enviou HTML em vez de JSON.");
            alert("Erro crítico no servidor. Verifique o console (F12) para ler o erro do PHP.");
        }
    })
    .catch(err => {
        console.error("Erro na requisição Fetch:", err);
        alert("Não foi possível conectar ao servidor.");
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

//BOOTSTRAP
// Variável global para armazenar os ícones carregados
let allIcons = [];

async function carregarTodosIcones() {
    const grid = document.getElementById('iconGrid');
    const loader = document.getElementById('loadingIcones');

    try {
        // 1. Pega a folha de estilo do Bootstrap Icons que você já carregou no cabeçalho
        const response = await fetch('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css');
        const cssText = await response.text();

        // 2. Regex para encontrar todas as classes que começam com .bi- e terminam com ::before
        // Isso extrai o nome exato de cada ícone disponível na versão da CDN
        const regex = /\.bi-([a-z0-9-]+)::before/g;
        let match;
        allIcons = [];

        while ((match = regex.exec(cssText)) !== null) {
            allIcons.push(match[1]);
        }

        // 3. Renderiza os ícones na Grid
        grid.innerHTML = '';
        allIcons.forEach(iconName => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-outline-dark btn-sm p-2 icon-item';
            btn.style.width = '45px';
            btn.style.height = '45px';
            btn.title = iconName;
            btn.innerHTML = `<i class="bi bi-${iconName}" style="font-size: 1.2rem;"></i>`;

            btn.onclick = () => {
                // Pegamos o caractere unicode gerado pelo CSS
                const iconElement = btn.querySelector('i');
                const content = window.getComputedStyle(iconElement, '::before').getPropertyValue('content');
                // Removemos as aspas que o navegador coloca no content
                const cleanContent = content.replace(/['"]/g, '');

                addIconeNoBanner(cleanContent);
            };
            grid.appendChild(btn);
        });

        loader.style.display = 'none';
    } catch (error) {
        console.error("Erro ao carregar ícones:", error);
        grid.innerHTML = '<p class="text-danger">Erro ao carregar a biblioteca de ícones.</p>';
    }
}

function filtrarIcones() {
    let term = document.getElementById('searchIcon').value.toLowerCase();
    let icons = document.querySelectorAll('.icon-item');
    icons.forEach(btn => {
        // Oculta/Mostra baseado no título (nome do ícone)
        btn.style.display = btn.title.includes(term) ? 'block' : 'none';
    });
}

function addIconeNoBanner(unicode) {
    let size = document.getElementById('textSizePicker').value || 60;
    let color = document.getElementById('textColorPicker').value || '#000000';

    // Insere como IText usando a fonte do Bootstrap
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

    // Fecha o modal
    const modalEl = document.getElementById('modalIcones');
    bootstrap.Modal.getInstance(modalEl).hide();
}

// Dispara o carregamento quando o modal for aberto pela primeira vez (para economizar memória)
document.getElementById('modalIcones').addEventListener('shown.bs.modal', function () {
    if (allIcons.length === 0) carregarTodosIcones();
}, { once: true });




// No final do seu script, logo após 'var canvas = ...'
<?php if (!empty($sociedade['sociedade_layout_config'])): ?>
    try {
        // O json_decode aqui no PHP limpa as aspas para o JS ler como string pura
        const layoutSaved = <?= json_encode($sociedade['sociedade_layout_config']) ?>;

        if (layoutSaved && layoutSaved !== "null") {
            // O Fabric.js precisa que o JSON seja um objeto ou string válida
            canvas.loadFromJSON(layoutSaved, function() {
                canvas.renderAll();
                console.log("Layout carregado com sucesso.");
            });
        }
    } catch(err) {
        console.error("Erro ao carregar layout:", err);
    }
<?php endif; ?>

</script>

