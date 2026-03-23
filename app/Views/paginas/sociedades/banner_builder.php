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

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-dark text-white fw-bold py-2 small">1. Texto e Customização</div>
                <div class="card-body p-3">
                    <div class="input-group mb-3">
                        <input type="text" id="customTextInput" class="form-control form-control-sm" placeholder="Escreva algo...">
                        <button class="btn btn-primary btn-sm" onclick="addTextoLivre()">Inserir</button>
                    </div>

                    <div class="row g-2">
                        <div class="col-4">
                            <label class="x-small fw-bold d-block">Cor:</label>
                            <input type="color" id="textColorPicker" class="form-control form-control-sm border-0 p-0" value="#000000" onchange="updateTextStyle()">
                        </div>
                        <div class="col-4">
                            <label class="x-small fw-bold d-block">Tamanho:</label>
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
                        <div class="col-8">
                            <select id="iconPicker" class="form-select form-select-sm bi-font">
                                <option value="" style="font-family: Arial;">Escolha um ícone...</option>
                                <option value="&#xF4E1;">📍 Localização</option>
                                <option value="&#xF22D;">📅 Calendário</option>
                                <option value="&#xF101;">⏰ Horário</option>
                                <option value="&#xF5C1;">📞 Telefone</option>
                                <option value="&#xF437;">📸 Instagram</option>
                                <option value="&#xF27B;">⛪ Igreja</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-sm btn-outline-dark w-100" onclick="addIcone()">Add Ícone</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white fw-bold py-2 small d-flex justify-content-between">
                    2. Dados da Igreja
                    <button class="btn btn-xs btn-light py-0" onclick="deleteObject()"><i class="bi bi-trash text-danger"></i></button>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 mb-2">
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
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-success text-white fw-bold py-2 small">3. Membros</div>
                <div class="card-body p-3">
                    <select id="selectMembro" class="form-select form-select-sm mb-2">
                        <option value="">Selecione um membro...</option>
                        <?php if(!empty($membros)): foreach($membros as $m): ?>
                            <option value="<?= $m['membro_id'] ?>"
                                    data-nome="<?= addslashes($m['membro_nome']) ?>"
                                    data-foto="<?= $m['membro_foto_arquivo'] ?>"
                                    data-igreja="<?= $m['membro_igreja_id'] ?>"
                                    data-registro="<?= $m['membro_registro_interno'] ?>"
                                    data-rua="<?= addslashes($m['membro_endereco_rua'] ?? 'Sem endereço') ?>">
                                <?= $m['membro_nome'] ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <div class="row g-2">
                        <div class="col-6"><button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('foto')">Add Foto</button></div>
                        <div class="col-6"><button class="btn btn-xs btn-outline-success w-100" onclick="addInfoMembro('endereco')">Add Endereço</button></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-secondary text-white fw-bold py-2 small">4. Logos e Camadas</div>
                <div class="card-body p-3">
                    <div class="row g-1 mb-3 text-center">
                        <div class="col-4">
                            <button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb')">IPB Normal</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-outline-dark btn-xs w-100" onclick="addIgrejaLogo('ipb_completo')">IPB Compl.</button>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-outline-primary btn-xs w-100" onclick="addSociedadeLogo()">Sociedade</button>
                        </div>
                    </div>

                    <div class="row g-1 mb-2">
                        <div class="col-6">
                            <button class="btn btn-xs btn-light border w-100" onclick="trazerParaFrente()"><i class="bi bi-layer-forward"></i> Frente</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-xs btn-light border w-100" onclick="enviarParaTras()"><i class="bi bi-layer-backward"></i> Trás</button>
                        </div>
                        <div class="col-12 mt-1">
                            <button class="btn btn-xs btn-light border w-100" onclick="centralizarH()"><i class="bi bi-align-center"></i> Centralizar Horizontal</button>
                        </div>
                    </div>

                    <hr class="my-2">
                    <label class="x-small fw-bold">Fundo (Background):</label>
                    <div class="input-group">
                        <input type="file" id="bgInput" class="form-control form-control-sm" accept="image/*">
                        <button class="btn btn-outline-danger btn-sm" onclick="clearBackground()"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>
			<div class="row g-2 mt-3">
				<div class="col-6">
					<button class="btn btn-outline-primary btn-lg w-100 fw-bold" onclick="salvarLayout()">
						<i class="bi bi-cloud-arrow-up-fill me-2"></i>SALVAR LAYOUT
					</button>
				</div>
				<div class="col-6">
					<button class="btn btn-success btn-lg w-100 shadow fw-bold" onclick="downloadBanner()">
						<i class="bi bi-download me-2"></i>BAIXAR (PNG)
					</button>
				</div>
			</div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
    // Configuração Inicial
    var canvas = new fabric.Canvas('editorCanvas', {
        backgroundColor: '#ffffff',
        preserveObjectStacking: true
    });

    // Garante carregamento da fonte antes da primeira inserção
    if (document.fonts) {
        document.fonts.load('1em bootstrap-icons');
    }

    // --- FUNÇÕES DE TEXTO E ESTILO ---
    function addTextoLivre() {
        let val = document.getElementById('customTextInput').value;
        if(!val) return;
        addTexto(val);
        document.getElementById('customTextInput').value = "";
    }

    function addTexto(val, size = null, bold = false) {
        if(!val) return;
        let defaultSize = size || document.getElementById('textSizePicker').value;
        let color = document.getElementById('textColorPicker').value;
        let font = document.getElementById('fontFamilyPicker').value;

        var t = new fabric.IText(val, {
            left: 400, top: 400,
            fontSize: parseInt(defaultSize),
            fontWeight: bold ? 'bold' : 'normal',
            fontFamily: font,
            fill: color,
            originX: 'center', originY: 'center'
        });
        canvas.add(t);
        canvas.setActiveObject(t);
        canvas.renderAll();
    }

    function updateTextStyle() {
        let activeObject = canvas.getActiveObject();
        if (activeObject && (activeObject.type === 'i-text' || activeObject.type === 'text')) {
            activeObject.set({
                fill: document.getElementById('textColorPicker').value,
                fontSize: parseInt(document.getElementById('textSizePicker').value),
                fontFamily: document.getElementById('fontFamilyPicker').value
            });
            canvas.renderAll();
        }
    }

    // --- ÍCONES ---
    function addIcone() {
        let select = document.getElementById('iconPicker');
        if(!select.value) return;

        var icon = new fabric.IText(select.value, {
            left: 400, top: 400,
            fontSize: 50,
            fontFamily: 'bootstrap-icons',
            fill: document.getElementById('textColorPicker').value,
            originX: 'center', originY: 'center'
        });
        canvas.add(icon);
        canvas.setActiveObject(icon);
        canvas.renderAll();
        // Pequeno fix para renderização assíncrona da fonte
        setTimeout(() => canvas.renderAll(), 150);
    }

    // --- MEMBROS E LOGOS ---
    function addInfoMembro(tipo) {
        let sel = document.getElementById('selectMembro');
        let opt = sel.options[sel.selectedIndex];
        if(!opt.value) return;

        if(tipo === 'endereco') {
            addTexto(opt.getAttribute('data-rua'), 20);
        } else {
            let foto = opt.getAttribute('data-foto');
            let idIgreja = opt.getAttribute('data-igreja');
            let registro = opt.getAttribute('data-registro');
            if(!foto || foto === "null") return alert("Sem foto cadastrada.");

            let urlFinal = "<?= url('assets/uploads/') ?>" + idIgreja + "/" + registro + "/" + foto;
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


