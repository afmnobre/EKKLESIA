<script src="https://unpkg.com/html5-qrcode"></script>

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <a href="<?= url('escolaDominical') ?>" class="btn btn-sm btn-outline-secondary mb-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <h3 class="fw-bold text-primary mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Chamada: <?= $classe['classe_nome'] ?>
                    </h3>
                    <p class="text-muted mb-0 small">
                        <i class="bi bi-people me-1"></i> <?= $classe['classe_idade_min'] ?> a <?= $classe['classe_idade_max'] ?> anos
                        <span class="mx-2">•</span>
                        <i class="bi bi-clock me-1"></i> <?= date('d/m/Y') ?>
                    </p>
                </div>

                <div class="col-md-7 mt-3 mt-md-0 d-flex flex-column flex-md-row justify-content-md-end align-items-md-center gap-3">
                    <button type="button" class="btn btn-success shadow-sm px-4 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#modalScanner" id="btnIniciarScan">
                        <i class="bi bi-qr-code-scan me-2"></i>Scanner de Presença
                    </button>

                    <form method="GET" action="<?= url('escolaDominical/chamada/' . $classe['classe_id']) ?>" class="d-flex gap-2">
                        <div class="input-group" style="max-width: 220px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar3"></i></span>
                            <input type="date" name="data" class="form-control border-start-0" value="<?= $dataSelecionada ?>" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 60%;">Nome do Aluno</th>
                        <th class="text-center">Presença</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alunos)): foreach ($alunos as $aluno): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= $aluno['membro_nome'] ?></div>
                                <small class="text-muted">Registro: <?= $aluno['membro_registro_interno'] ?? '---' ?></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Presença">
                                    <input type="radio" class="btn-check btn-presenca"
                                           name="p_<?= $aluno['membro_id'] ?>"
                                           id="pres_<?= $aluno['membro_id'] ?>"
                                           value="1"
                                           data-membro="<?= $aluno['membro_id'] ?>"
                                           <?= ($aluno['presenca_status'] == 1) ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-success px-3" for="pres_<?= $aluno['membro_id'] ?>">
                                        <i class="bi bi-check-lg"></i> P
                                    </label>

                                    <input type="radio" class="btn-check btn-presenca"
                                           name="p_<?= $aluno['membro_id'] ?>"
                                           id="falt_<?= $aluno['membro_id'] ?>"
                                           value="0"
                                           data-membro="<?= $aluno['membro_id'] ?>"
                                           <?= ($aluno['presenca_status'] === '0' || (isset($aluno['presenca_status']) && $aluno['presenca_status'] == 0)) ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-danger px-3" for="falt_<?= $aluno['membro_id'] ?>">
                                        <i class="bi bi-x-lg"></i> F
                                    </label>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <i class="bi bi-person-exclamation fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Nenhum aluno matriculado nesta classe.</p>
                                <a href="<?= url('escolaDominical') ?>" class="btn btn-primary btn-sm">Gerenciar Alunos</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalScanner" tabindex="-1" aria-labelledby="modalScannerLabel" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-success text-white border-0 py-3">
					<h5 class="modal-title fw-bold" id="modalScannerLabel">
						<i class="bi bi-camera-fill me-2"></i>Aproxime o QR Code do Aluno
					</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btnFecharScanTop"></button>
				</div>

				<div class="modal-body p-0">
					<div id="scannerFeedback" class="text-center p-3 d-none fw-bold" style="position: absolute; top: 70px; left: 0; width: 100%; z-index: 1000;"></div>

					<div id="reader" style="width: 100%; background: #000; min-height: 400px; border-radius: 0 0 8px 8px; overflow: hidden;"></div>
				</div>

				<div class="modal-footer border-0 bg-light d-flex justify-content-center py-3">
					<p class="text-muted small mb-0">Para encerrar, clique em "Parar Câmera" ou feche o popup.</p>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="btnFecharScan">
						<i class="bi bi-camera-video-off me-2"></i>Parar Câmera
					</button>
				</div>
			</div>
		</div>
</div>


<script>
document.querySelectorAll('.btn-presenca').forEach(radio => {
    radio.addEventListener('change', function() {
        const membroId = this.getAttribute('data-membro');
        const status = this.value;
        const classeId = '<?= $classe['classe_id'] ?>';
        const data = '<?= $dataSelecionada ?>';

        const formData = new FormData();
        formData.append('classe_id', classeId);
        formData.append('membro_id', membroId);
        formData.append('data', data);
        formData.append('status', status);

        fetch('<?= url("escolaDominical/salvarPresenca") ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (!result.success) {
                alert('Erro ao salvar presença. Tente novamente.');
            }
        })
        .catch(error => console.error('Erro:', error));
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Seleção segura dos elementos
    const feedbackDiv = document.getElementById('scannerFeedback');
    const readerDiv = document.getElementById('reader');

    // Verifica se os elementos existem antes de continuar
    if (!feedbackDiv || !readerDiv) {
        console.error("Erro: Elementos do scanner não encontrados no HTML.");
        return;
    }

    const html5QrCode = new Html5Qrcode("reader");
    let isProcessing = false;

    const urlAjax = "<?= url('escolaDominical/registrarPresencaAjax') ?>";
    const classeIdActiva = <?= $classe['classe_id'] ?>;

    const startScanner = () => {
        isProcessing = false;
        feedbackDiv.classList.add('d-none');

        // Configuração otimizada
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess
        ).catch((err) => {
            console.error("Erro ao iniciar scanner:", err);

            // CORREÇÃO DO ERRO CLASSLIST:
            feedbackDiv.classList.remove('d-none', 'bg-success', 'bg-warning');
            feedbackDiv.classList.add('bg-danger', 'text-white');

            // Mensagem amigável para o usuário
            if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                feedbackDiv.textContent = "Erro: A câmera exige HTTPS para funcionar em domínios .local";
            } else {
                feedbackDiv.textContent = "Erro: Não foi possível acessar a câmera.";
            }
        });
    };

    // --- FUNÇÃO DE SUCESSO ---
	function onScanSuccess(decodedText) {
		if (isProcessing) return;
		isProcessing = true;

		const codigoLimpo = decodedText.trim();
		mostraFeedback("Comunicando com servidor...", 'aviso');

		const formData = new FormData();
		formData.append('classe_id', '<?= $classe["classe_id"] ?>');
		formData.append('membro_id', codigoLimpo);

		fetch("<?= url('escolaDominical/registrarPresencaAjax') ?>", {
			method: 'POST',
			body: formData
		})
		.then(response => {
			// Se o status não for 200 (sucesso), houve erro de PHP/Servidor
			if (!response.ok) {
				return response.text().then(text => { throw new Error(text) });
			}
			return response.json();
		})
		.then(data => {
			console.log("Sucesso:", data);
			mostraFeedback(data.mensagem, data.status);

			// Se salvou, podemos até recarregar a lista de nomes atrás do modal depois de um tempo
			setTimeout(() => {
				isProcessing = false;
				// Opcional: location.reload(); // Para atualizar a lista de presença na tela
			}, 3000);
		})
		.catch(error => {
			// ISSO VAI MOSTRAR O ERRO DO PHP NO SEU MODAL
			console.error("Erro no Servidor:", error);
			mostraFeedback("Erro crítico no servidor. Verifique o banco.", 'erro');
			isProcessing = false;
		});
	}

    function mostraFeedback(mensagem, tipo) {
        if (!feedbackDiv) return;
        feedbackDiv.textContent = mensagem;
        feedbackDiv.classList.remove('d-none', 'bg-success', 'bg-danger', 'bg-warning', 'text-white', 'text-dark');

        if (tipo === 'sucesso') feedbackDiv.classList.add('bg-success', 'text-white');
        else if (tipo === 'aviso') feedbackDiv.classList.add('bg-warning', 'text-dark');
        else feedbackDiv.classList.add('bg-danger', 'text-white');
    }

    // Gerenciamento do Modal
    const modalEl = document.getElementById('modalScanner');
    modalEl.addEventListener('shown.bs.modal', startScanner);
    modalEl.addEventListener('hidden.bs.modal', () => {
        if (html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error("Erro ao parar:", err));
        }
    });
});
</script>

<style>
    /* Estilos para o Modal de Scanner */
    #reader__scan_region video {
        object-fit: cover !important; /* Ajusta o vídeo da câmera para preencher o container */
    }
    #scannerFeedback {
        transition: opacity 0.3s;
        border-radius: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
</style>
