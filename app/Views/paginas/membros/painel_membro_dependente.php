<style>
    /* Cores e Estilos Base */
    .bg-ipb { background-color: #005a32 !important; }
    .text-ipb { color: #005a32 !important; }
    .btn-ipb { background-color: #005a32; color: white; border: none; }
    .btn-ipb:hover { background-color: #004426; color: white; }
    .btn-outline-ipb { border-color: #005a32; color: #005a32; }
    .btn-outline-ipb:hover { background-color: #005a32; color: white; }

    .uppercase-input { text-transform: uppercase; }
    .img-preview { width: 120px; height: 120px; object-fit: cover; border: 3px solid #eee; transition: all 0.3s; }
    .img-preview:hover { border-color: #005a32; transform: scale(1.05); }

    /* Estilo do Cabeçalho Unificado */
    .header-perfil { padding: 40px 0 80px 0; margin-bottom: -60px; }
    .logo-igreja-local { max-height: 105px; width: auto; filter: brightness(0) invert(1); }
</style>

<div class="container-fluid p-0" style="background-color: #f8f9fa; min-height: 100vh;">

	<div class="bg-ipb text-white header-perfil shadow-sm">
		<div class="container">
			<div class="row align-items-center g-3">

				<div class="col-auto d-none d-md-block pe-4 border-end border-white border-opacity-25">
					<?php
						$idIgreja = $igreja_dados['igreja_id'] ?? $_SESSION['membro_igreja_id'];
						$nomeLogo = $igreja_dados['igreja_logo'] ?? '';
						$urlLogoLocal = !empty($nomeLogo)
							? url("assets/uploads/{$idIgreja}/logo/{$nomeLogo}")
							: url("assets/img/logo_ipb.png");
					?>
					<img src="<?= $urlLogoLocal ?>" class="logo-igreja-local" style="max-height: 60px;">
				</div>

				<div class="col d-flex align-items-center ps-md-4">
					<?php
						$diretorio = ($perfil['membro_status'] === 'Ativo') ? $perfil['membro_registro_interno'] : "PENDENTE_{$perfil['membro_id']}";
						$fotoUrl = !empty($perfil['membro_foto_arquivo'])
							? url("assets/uploads/{$perfil['membro_igreja_id']}/membros/{$diretorio}/{$perfil['membro_foto_arquivo']}")
							: url("assets/img/avatar-default.png");
					?>
					<img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border border-3 border-white me-3" style="width: 65px; height: 65px; object-fit: cover;">

					<div>
						<h5 class="fw-bold mb-0 text-white">Olá, <?= explode(' ', $perfil['membro_nome'])[0] ?>!</h5>
						<p class="small mb-0 opacity-75">
							<a href="<?= url('PortalMembro/painel') ?>" class="text-white text-decoration-none hover-opacity">
								<i class="bi bi-arrow-left-circle me-1"></i> Voltar ao Painel
							</a>
						</p>
					</div>
				</div>

				<div class="col-auto d-flex align-items-center gap-2 ms-auto">

					<a href="<?= url('PortalMembro/logout') ?>" class="btn btn-outline-light btn-sm px-4 fw-bold border-2 rounded-pill shadow-sm">
						<i class="bi bi-box-arrow-right me-1"></i> SAIR
					</a>

					<div class="d-none d-lg-block ms-2 ps-3 border-start border-white border-opacity-25">
						<img src="<?= url('assets/img/logo_ipb_completo.png') ?>" style="height: 40px; filter: brightness(0) invert(1);">
					</div>
				</div>

			</div>
		</div>
	</div>

    <div class="container pb-5">
        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-white p-3 border-0 rounded-top-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-ipb"><i class="bi bi-person-plus me-2"></i>NOVO DEPENDENTE</h6>
                <small class="text-muted fw-bold"><?= $igreja_dados['igreja_nome'] ?? $perfil['igreja_nome'] ?? 'EKKLESIA' ?></small>
            </div>

            <div class="card-body p-4">
                <form action="<?= url('PortalMembro/salvarDependente') ?>" method="POST" enctype="multipart/form-data">

                    <div class="text-center mb-4">
                        <div class="mb-2">
                            <img id="preview" src="<?= url('assets/img/avatar_padrao.png') ?>" class="rounded-circle img-preview shadow-sm">
                        </div>
                        <label for="foto" class="btn btn-outline-ipb btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-camera me-1"></i> TIRAR OU ESCOLHER FOTO
                        </label>
                        <input type="file" name="foto" id="foto" class="d-none" accept="image/*" onchange="previewImage(this)">
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <h6 class="fw-bold text-ipb mb-0 small text-uppercase">Dados da Criança</h6>
                        <hr class="flex-grow-1 ms-3 opacity-25">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Nome Completo</label>
                            <input type="text" name="membro_nome" class="form-control uppercase-input p-2"
                                   placeholder="NOME COMPLETO" required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Data de Nascimento</label>
                            <input type="date" name="membro_data_nascimento" class="form-control p-2" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Data de Batismo (Opcional)</label>
                            <input type="date" name="membro_data_batismo" class="form-control p-2">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Gênero</label>
                            <select name="membro_genero" class="form-select p-2" required>
                                <option value="" selected disabled>Selecione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Estado Civil</label>
                            <select name="membro_estado_civil" class="form-select p-2" required>
                                <option value="Solteiro(a)" selected>Solteiro(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Viúvo(a)">Viúvo(a)</option>
                                <option value="Divorciado(a)">Divorciado(a)</option>
                                <option value="Separado(a)">Separado(a)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Grau de Parentesco</label>
                            <select name="parentesco_grau" class="form-select p-2" required>
                                <option value="Pai/Mãe">Filho(a)</option>
                                <option value="Avô/Avó">Neto(a)</option>
                                <option value="Tio/Tia">Sobrinho(a)</option>
                                <option value="Tutor Legal">Tutorado(a)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <h6 class="fw-bold text-ipb mb-0 small text-uppercase">Endereço e Contato</h6>
                        <div class="ms-auto form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="checkHerdar" checked onchange="toggleEndereco()">
                            <label class="form-check-label small fw-bold" for="checkHerdar">Mesmos que os meus</label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php
                            $p = $perfil ?? [
                                'membro_email' => '',
                                'membro_telefone' => '',
                                'membro_endereco_rua' => '',
                                'membro_endereco_numero' => '',
                                'membro_endereco_bairro' => '',
                                'membro_endereco_cidade' => '',
                                'membro_endereco_estado' => '',
                                'membro_endereco_cep' => ''
                            ];
                        ?>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">E-mail (Opcional)</label>
                            <input type="email" name="membro_email" id="email" class="form-control bg-light"
                                   value="<?= $p['membro_email'] ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Celular (Opcional)</label>
                            <input type="text" name="membro_telefone" id="telefone" class="form-control bg-light"
                                   value="<?= $p['membro_telefone'] ?>" readonly>
                        </div>

                        <div class="col-9">
                            <label class="form-label small fw-bold text-muted">Rua</label>
                            <input type="text" name="membro_endereco_rua" id="rua" class="form-control bg-light"
                                   value="<?= $p['membro_endereco_rua'] ?>" readonly>
                        </div>
                        <div class="col-3">
                            <label class="form-label small fw-bold text-muted">Nº</label>
                            <input type="text" name="membro_endereco_numero" id="numero" class="form-control bg-light"
                                   value="<?= $p['membro_endereco_numero'] ?>" readonly>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Cidade/UF</label>
                            <input type="text" id="cidade_display" class="form-control bg-light"
                                   value="<?= $p['membro_endereco_cidade'] ?>/<?= $p['membro_endereco_estado'] ?>" readonly>

                            <input type="hidden" name="membro_endereco_cidade" id="cidade" value="<?= $p['membro_endereco_cidade'] ?>">
                            <input type="hidden" name="membro_endereco_estado" id="estado" value="<?= $p['membro_endereco_estado'] ?>">
                            <input type="hidden" name="membro_endereco_bairro" id="bairro" value="<?= $p['membro_endereco_bairro'] ?>">
                            <input type="hidden" name="membro_endereco_cep" id="cep" value="<?= $p['membro_endereco_cep'] ?>">
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-ipb w-100 p-3 fw-bold rounded-pill shadow">
                                <i class="bi bi-check2-circle me-2"></i> FINALIZAR CADASTRO
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-5 pb-5 text-center">
            <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                <img src="<?= url('assets/img/logo_ipb.png') ?>" style="height: 40px; opacity: 0.6;">
                <div class="text-start border-start ps-3">
                    <h6 class="fw-bold text-muted mb-0 small text-uppercase"><?= $nomeIgreja ?></h6>
                    <p class="text-muted mb-0" style="font-size: 0.65rem;">Igreja Presbiteriana do Brasil</p>
                </div>
            </div>
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" style="height: 35px; opacity: 0.4;">
            <p class="mt-3 text-muted" style="font-size: 0.65rem;">&copy; <?= date('Y') ?> - Sistema Ekklesia | Gestão Eclesiástica</p>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { document.getElementById('preview').src = e.target.result; }
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleEndereco() {
    const isChecked = document.getElementById('checkHerdar').checked;
    const campos = ['rua', 'numero', 'email', 'telefone', 'cidade_display'];

    campos.forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;
        input.readOnly = isChecked;
        if (isChecked) {
            input.value = input.defaultValue;
            input.classList.remove('bg-white');
            input.classList.add('bg-light');
        } else {
            input.classList.add('bg-white');
            input.classList.remove('bg-light');
        }
    });
}
</script>
