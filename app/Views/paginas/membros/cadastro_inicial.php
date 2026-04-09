<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="text-center mb-4">
        <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB Logo" style="max-width: 200px;" class="mb-3">
        <h2 class="fw-bold text-dark text-uppercase">Ficha de Membro</h2>
        <div class="badge bg-primary px-3 py-2 shadow-sm">
            <?= $igreja['igreja_nome'] ?>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
			<form action="<?= url('PortalMembro/salvar') ?>" id="formCadastro" method="POST" enctype="multipart/form-data" class="card shadow border-0 p-4" style="border-top: 5px solid #004a8d !important;">
				<input type="hidden" name="igreja_id" value="<?= $igreja['igreja_id'] ?>">

				<div class="d-flex align-items-center mb-3">
					<img src="<?= url('assets/img/logo_ipb.png') ?>" style="width: 25px;" class="me-2">
					<h6 class="mb-0 fw-bold text-primary text-uppercase">Onde você mora?</h6>
				</div>
				<hr class="mt-0 mb-3 opacity-10">

				<div class="row mb-3">
					<div class="col-6">
						<label class="form-label small fw-bold text-primary">CEP</label>
						<input type="text" name="cep" id="cep" class="form-control form-control-lg border-primary shadow-sm" placeholder="00000-000" required>
					</div>
					<div class="col-6 d-flex align-items-end">
						<small class="text-muted pb-2" id="status-cep"></small>
					</div>
				</div>

				<div class="mb-2">
					<label class="form-label small fw-bold">Rua / Logradouro</label>
					<input type="text" name="rua" id="rua" class="form-control shadow-sm" required>
				</div>

				<div class="row mb-2">
					<div class="col-4">
						<label class="form-label small fw-bold">Número</label>
						<input type="text" name="numero" class="form-control shadow-sm" required>
					</div>
					<div class="col-8">
						<label class="form-label small fw-bold">Complemento <span class="text-muted fw-normal">(Apto, Bloco, etc)</span></label>
						<input type="text" name="complemento" id="complemento" class="form-control shadow-sm" placeholder="Ex: Apto 12">
					</div>
				</div>

				<div class="mb-2">
					<label class="form-label small fw-bold">Bairro</label>
					<input type="text" name="bairro" id="bairro" class="form-control shadow-sm" required>
				</div>

				<div class="row mb-3">
					<div class="col-8">
						<label class="form-label small fw-bold">Cidade</label>
						<input type="text" name="cidade" id="cidade" class="form-control shadow-sm" required readonly>
					</div>
					<div class="col-4">
						<label class="form-label small fw-bold">UF</label>
						<input type="text" name="estado" id="estado" class="form-control shadow-sm" maxlength="2" readonly required>
					</div>
				</div>

				<div class="d-flex align-items-center mb-3 mt-4">
					<img src="<?= url('assets/img/logo_ipb.png') ?>" style="width: 25px;" class="me-2">
					<h6 class="mb-0 fw-bold text-primary text-uppercase">Seus Dados</h6>
				</div>
				<hr class="mt-0 mb-3 opacity-10">

				<div class="mb-3">
					<label class="form-label small fw-bold">Nome Completo</label>
					<input type="text" name="nome" id="nome_membro" class="form-control text-uppercase shadow-sm" placeholder="DIGITE SEU NOME COMPLETO" required>
				</div>

				<div class="row mb-3">
					<div class="col-6">
						<label class="form-label small fw-bold">Nascimento</label>
						<input type="date" name="data_nasc" class="form-control shadow-sm" required>
					</div>
					<div class="col-6">
						<label class="form-label small fw-bold">Batismo</label>
						<input type="date" name="data_batismo" class="form-control shadow-sm">
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-6">
						<label class="form-label small fw-bold">Sexo</label>
						<select name="sexo" class="form-select shadow-sm" required>
							<option value="" selected disabled>Selecione...</option>
							<option value="Masculino">Masculino</option>
							<option value="Feminino">Feminino</option>
						</select>
					</div>
					<div class="col-6">
						<label class="form-label small fw-bold">Estado Civil</label>
						<select name="estado_civil" id="estado_civil" class="form-select shadow-sm" required onchange="verificarCasamento()">
							<option value="" selected disabled>Selecione...</option>
							<option value="Solteiro(a)">Solteiro(a)</option>
							<option value="Casado(a)">Casado(a)</option>
							<option value="Viúvo(a)">Viúvo(a)</option>
							<option value="Divorciado(a)">Divorciado(a)</option>
							<option value="Separado(a)">Separado(a)</option>
						</select>
					</div>
				</div>

				<div class="mb-3" id="box_casamento" style="display: none;">
					<label class="form-label small fw-bold text-primary"><i class="fa fa-heart me-1"></i> Data de Casamento</label>
					<input type="date" name="data_casamento" class="form-control shadow-sm border-primary">
					<div class="form-text" style="font-size: 0.7rem;">Para celebrarmos esta data com você!</div>
				</div>

				<div class="row mb-3">
					<div class="col-6">
						<label class="form-label small fw-bold">RG</label>
						<input type="text" name="rg" id="rg" class="form-control shadow-sm" placeholder="00.000.000-0">
					</div>
					<div class="col-6">
						<label class="form-label small fw-bold">CPF</label>
						<input type="text" name="cpf" id="cpf" class="form-control shadow-sm" placeholder="000.000.000-00">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label small fw-bold">E-mail</label>
					<div class="input-group">
						<span class="input-group-text bg-light text-muted"><i class="fa fa-envelope"></i></span>
						<input type="email" name="email" class="form-control shadow-sm" placeholder="exemplo@email.com" required>
					</div>
					<div class="form-text text-muted" style="font-size: 0.7rem;">Este será seu login de acesso futuramente.</div>
				</div>

				<div class="mb-3">
					<label class="form-label small fw-bold">Telefone / WhatsApp</label>
					<div class="input-group">
						<span class="input-group-text bg-light text-success"><i class="fa fa-whatsapp"></i></span>
						<input type="text" name="telefone" id="telefone" class="form-control shadow-sm" placeholder="(00) 00000-0000" required>
					</div>
				</div>

				<div class="mb-3 bg-light p-3 rounded border border-danger shadow-sm">
					<label class="form-label small text-danger fw-bold"><i class="fa fa-lock me-1"></i> Criar Senha</label>
					<input type="password" name="senha" class="form-control shadow-sm" required>
				</div>

				<div class="mb-4 mt-4 text-center p-3 border rounded bg-light shadow-sm">
					<label class="form-label d-block fw-bold text-primary">FOTO PARA CARTEIRINHA</label>
					<input type="file" name="foto" class="form-control mb-2" accept="image/*" capture="user">
				</div>

				<button type="button" class="btn btn-primary btn-lg w-100 shadow fw-bold py-3 text-uppercase" data-bs-toggle="modal" data-bs-target="#modalLGPD">
					<i class="fa fa-check-circle me-2"></i> Enviar Cadastro
				</button>
			</form>
        </div>
    </div>

    <div class="modal fade" id="modalLGPD" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa fa-shield-alt me-2"></i>TERMO DE CONSENTIMENTO (LGPD)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body shadow-sm" style="font-size: 0.85rem; line-height: 1.5; color: #333;">
                    <div class="p-3 border rounded bg-white">
                        <p class="text-center fw-bold mb-1">TERMO DE CONSENTIMENTO PARA USO DE DADOS PESSOAIS E DE IMAGEM</p>
                        <p class="text-center small mb-3">
                            <strong>Igreja:</strong> <?= $igreja['igreja_nome'] ?><br>
                            <strong>Endereço:</strong> <?= $igreja['igreja_endereco'] ?>
                        </p>

                        <p><strong>1. Finalidade:</strong> A <?= $igreja['igreja_nome'] ?> trata seus dados para atividades eclesiásticas, administrativas e ministeriais (cadastro, comunicação de eventos e documentos internos).</p>

                        <p><strong>2. Dados:</strong> Nome, nascimento, estado civil, endereço, contatos, CPF/RG e imagem/voz registrada em cultos.</p>

                        <p><strong>3. Imagem:</strong> Você autoriza o uso de sua imagem em redes sociais e transmissões oficiais da igreja para fins institucionais.</p>

                        <p><strong>4. Direitos:</strong> Você pode solicitar a correção ou revogação deste consentimento a qualquer momento na secretaria da igreja.</p>

                        <p class="mt-3 text-muted small italic border-top pt-2">Ao clicar em "Aceito e Salvar", você autoriza a coleta e tratamento dos dados nos termos da Lei nº 13.709 (LGPD).</p>
                    </div>
                </div>
                <div class="modal-footer bg-light flex-column align-items-stretch">
                    <div class="form-check mb-3">
                        <input class="form-check-input border-primary" type="checkbox" id="checkLGPD">
                        <label class="form-check-label fw-bold text-primary" for="checkLGPD" style="cursor:pointer">
                            LI E ACEITO OS TERMOS DE USO DE DADOS
                        </label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary flex-grow-1" data-bs-dismiss="modal">VOLTAR</button>
                        <button type="button" id="btnFinalizarCadastro" class="btn btn-success flex-grow-1 fw-bold">
                            ACEITO E ENVIAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.2.0/vanilla-masker.min.js"></script>
<script>
    // Aplicando Máscaras
    VMasker(document.getElementById("cep")).maskPattern("99999-999");
    VMasker(document.getElementById("telefone")).maskPattern("(99) 99999-9999");
    VMasker(document.getElementById("cpf")).maskPattern("999.999.999-99");
    VMasker(document.getElementById("rg")).maskPattern("99.999.999-S"); // Exemplo de máscara com dígito verificador alfanumérico

    // Forçar nome em maiúsculo enquanto digita
    document.getElementById('nome_membro').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });

    // Lógica de Busca de CEP
    document.getElementById('cep').addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        const status = document.getElementById('status-cep');

        if (cep.length === 8) {
            status.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Buscando...';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
					if (!data.erro) {
						document.getElementById('rua').value = data.logradouro;
						document.getElementById('bairro').value = data.bairro;
						document.getElementById('cidade').value = data.localidade;
						document.getElementById('estado').value = data.uf;
						document.getElementById('complemento').value = data.complemento; // Linha adicionada
						status.innerHTML = '<span class="text-success"><i class="fa fa-check"></i></span>';
						document.getElementsByName('numero')[0].focus();
					}else {
                        status.innerHTML = '<span class="text-danger small">CEP não encontrado.</span>';
                    }
                })
                .catch(() => {
                    status.innerHTML = '<span class="text-danger small">Erro ao buscar CEP.</span>';
                });
        }
    });

    // Lógica do Modal LGPD
    document.getElementById('btnFinalizarCadastro').addEventListener('click', function() {
        const checkbox = document.getElementById('checkLGPD');

        if (checkbox.checked) {
            this.disabled = true;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ENVIANDO...';
            document.getElementById('formCadastro').submit();
        } else {
            alert("Para prosseguir, você deve aceitar o Termo de Consentimento (LGPD).");
        }
    });

    document.getElementById('modalLGPD').addEventListener('hidden.bs.modal', function () {
        const btn = document.getElementById('btnFinalizarCadastro');
        btn.disabled = false;
        btn.innerHTML = 'ACEITO E ENVIAR';
    });

	function verificarCasamento() {
		const estadoCivil = document.getElementById('estado_civil').value;
		const boxCasamento = document.getElementById('box_casamento');

		if (estadoCivil === 'Casado(a)') {
			boxCasamento.style.display = 'block';
		} else {
			boxCasamento.style.display = 'none';
			boxCasamento.querySelector('input').value = ''; // Limpa se mudar de ideia
		}
	}

</script>

<style>
    body { background-color: #f8f9fa; }
    .text-primary { color: #004a8d !important; }
    .btn-primary { background-color: #004a8d; border: none; }
    .form-control-lg { font-size: 1.1rem; }
    input[readonly] { background-color: #e9ecef !important; cursor: not-allowed; }
    .form-select { height: 45px; }
    .text-uppercase { text-transform: uppercase; }
</style>
