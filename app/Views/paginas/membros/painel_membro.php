<style>
    /* Cores e Estilos Base */
    .bg-ipb { background-color: #005a32 !important; }
    .text-ipb { color: #005a32 !important; }
    .btn-ipb { background-color: #005a32; color: white; border: none; }
    .btn-ipb:hover { background-color: #004426; color: white; }
    .btn-outline-ipb { border-color: #005a32; color: #005a32; }
    .btn-outline-ipb:hover { background-color: #005a32; color: white; }

    .uppercase-input { text-transform: uppercase; }

    /* Estilo do Cabeçalho Unificado */
    .header-perfil { padding: 30px 0 70px 0; margin-bottom: -50px; }

    /* Logo da Igreja Local no Header */
    .logo-igreja-local {
        height: 115px;
        width: auto;
        max-width: 150px;
        object-fit: contain;
        filter: brightness(0) invert(1); /* Deixa o logo branco para o header */
    }

    /* Badge Customizado */
    .badge-status { font-size: 0.65rem; padding: 5px 10px; }
    .bg-success-subtle { background-color: #d1e7dd; color: #0f5132; }
    .bg-warning-subtle { background-color: #fff3cd; color: #664d03; }

    /* Ajustes da Tabela */
    .table-ebd th { font-size: 0.65rem; letter-spacing: 0.5px; }

    /* Botão Boletim no Header */
    .btn-header-boletim {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        backdrop-filter: blur(5px);
        transition: all 0.3s ease;
    }
    .btn-header-boletim:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid p-0" style="background-color: #f8f9fa; min-height: 100vh;">

	<div class="bg-ipb text-white header-perfil shadow-sm">
		<div class="container">
			<div class="row align-items-center g-3">

				<div class="col-auto d-none d-md-block pe-4 border-end border-white border-opacity-25">
					<?php
						$logoIgreja = !empty($igreja_dados['igreja_logo'])
							? url("assets/uploads/{$_SESSION['membro_igreja_id']}/logo/{$igreja_dados['igreja_logo']}")
							: url("assets/img/logo_ipb.png");
					?>
					<img src="<?= $logoIgreja ?>" class="logo-igreja-local" style="max-height: 60px;">
				</div>

				<div class="col d-flex align-items-center ps-md-4">
					<?php
						$diretorio = ($perfil['membro_status'] === 'Ativo') ? $perfil['membro_registro_interno'] : "PENDENTE_{$perfil['membro_id']}";
						$fotoUrl = !empty($perfil['membro_foto_arquivo'])
							? url("assets/uploads/{$_SESSION['membro_igreja_id']}/membros/{$diretorio}/{$perfil['membro_foto_arquivo']}")
							: url("assets/img/avatar-default.png");
					?>
					<img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border border-3 border-white me-3" style="width: 65px; height: 65px; object-fit: cover;">

					<div>
						<h5 class="fw-bold mb-0 text-white">Olá, <?= explode(' ', $perfil['membro_nome'])[0] ?>!</h5>
						<p class="small mb-0 opacity-75">
							<i class="bi bi-card-text me-1"></i> ROL: <?= $perfil['membro_registro_interno'] ?? 'Aguardando...' ?>
						</p>
					</div>
				</div>

				<div class="col-auto d-flex align-items-center gap-2 ms-auto">

					<button type="button" class="btn btn-header-boletim btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalQRCode">
						<i class="bi bi-qr-code me-1"></i> MEU ACESSO
					</button>

					<a href="<?= url('PortalMembro/calendario') ?>" target="_blank" class="btn btn-header-boletim btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm">
						<i class="bi bi-calendar3 me-1 fs-6"></i> AGENDA
					</a>

					<?php if ($tem_boletim): ?>
						<a href="<?= url('BoletimSemanal/index') ?>" target="_blank" class="btn btn-header-boletim btn-sm fw-bold px-3 py-2 rounded-pill shadow-sm">
							<i class="bi bi-journal-text me-1 fs-6"></i> BOLETIM
						</a>
					<?php else: ?>
						<button class="btn btn-header-boletim btn-sm fw-bold px-3 py-2 rounded-pill disabled opacity-50">
							<i class="bi bi-journal-x me-1 fs-6"></i> BOLETIM
						</button>
					<?php endif; ?>

					<a href="<?= url('PortalMembro/logout') ?>" class="btn btn-outline-light btn-sm px-3 fw-bold border-2 rounded-pill">
						<i class="bi bi-box-arrow-right"></i>
					</a>
				</div>

			</div>
		</div>
	</div>

    <div class="container pb-5">

        <div class="card border-0 shadow rounded-4">
            <div class="card-header bg-white p-3 border-0 rounded-top-4 d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-bold text-ipb"><i class="bi bi-person-badge me-2"></i>MEU PERFIL</h6>
                <small class="text-muted fw-bold"><?= $nomeIgreja ?></small>
            </div>

            <div class="card-body p-4">

                <div class="d-flex align-items-center mb-3">
                    <h6 class="fw-bold text-ipb mb-0 small text-uppercase">Informações Pessoais</h6>
                    <hr class="flex-grow-1 ms-3 opacity-25">
                </div>

				<div class="row g-3 mb-4">
					<div class="col-12 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">Nome Completo</label>
						<p class="mb-0 fw-bold text-dark text-uppercase"><?= $perfil['membro_nome'] ?></p>
					</div>

					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">RG</label>
						<p class="mb-0 text-dark"><?= $perfil['membro_rg'] ?: '---' ?></p>
					</div>
					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">CPF</label>
						<p class="mb-0 text-dark"><?= $perfil['membro_cpf'] ?: '---' ?></p>
					</div>
					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">Gênero / Estado Civil</label>
						<p class="mb-0 text-dark"><?= $perfil['membro_genero'] ?: '---' ?> / <?= $perfil['membro_estado_civil'] ?: '---' ?></p>
					</div>

					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">Nascimento</label>
						<p class="mb-0 text-dark">
							<?= date('d/m/Y', strtotime($perfil['membro_data_nascimento'])) ?>
							<?php
								$nasc = new DateTime($perfil['membro_data_nascimento']);
								$hoje = new DateTime();
								$idade = $hoje->diff($nasc)->y;
								echo " <span class='badge bg-light text-dark border-0 fw-bold small'>({$idade} anos)</span>";
							?>
						</p>
					</div>
					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">Data de Batismo</label>
						<p class="mb-0 text-dark">
							<?php if(!empty($perfil['membro_data_batismo']) && $perfil['membro_data_batismo'] != '0000-00-00'): ?>
								<?= date('d/m/Y', strtotime($perfil['membro_data_batismo'])) ?>
								<?php
									$bat = new DateTime($perfil['membro_data_batismo']);
									$tempoBat = $hoje->diff($bat)->y;
									echo " <span class='badge bg-info-subtle text-info fw-bold small' style='font-size:0.7rem;'>{$tempoBat}º ANO</span>";
								?>
							<?php else: ?>
								---
							<?php endif; ?>
						</p>
					</div>
					<div class="col-md-4 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">Data de Casamento</label>
						<p class="mb-0 text-dark">
							<?php if(!empty($perfil['membro_data_casamento']) && $perfil['membro_data_casamento'] != '0000-00-00'): ?>
								<?= date('d/m/Y', strtotime($perfil['membro_data_casamento'])) ?>
								<?php
									$casam = new DateTime($perfil['membro_data_casamento']);
									$tempoCasam = $hoje->diff($casam)->y;
									echo " <span class='badge bg-danger-subtle text-danger fw-bold small' style='font-size:0.7rem;'>{$tempoCasam}º ANO</span>";
								?>
							<?php else: ?>
								---
							<?php endif; ?>
						</p>
					</div>

					<div class="col-md-6 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">WhatsApp / Celular</label>
						<p class="mb-0 text-dark"><?= $perfil['membro_telefone'] ?: 'Não informado' ?></p>
					</div>
					<div class="col-md-6 border-bottom pb-2">
						<label class="form-label small fw-bold text-muted mb-0">E-mail</label>
						<p class="mb-0 text-dark"><?= $perfil['membro_email'] ?: 'Não informado' ?></p>
					</div>

					<div class="col-12">
						<label class="form-label small fw-bold text-muted mb-0">Endereço</label>
						<p class="mb-0 text-dark small">
							<?= $perfil['membro_endereco_rua'] ?>, <?= $perfil['membro_endereco_numero'] ?><br>
							<?= $perfil['membro_endereco_bairro'] ?> — <?= $perfil['membro_endereco_cidade'] ?>/<?= $perfil['membro_endereco_estado'] ?>
						</p>
					</div>
				</div>

                <div class="d-flex align-items-center mb-3">
                    <h6 class="fw-bold text-ipb mb-0 small text-uppercase">Dados Eclesiásticos</h6>
                    <hr class="flex-grow-1 ms-3 opacity-25">
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-4 border bg-light">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.6rem;">Cargo Atual</small>
                            <span class="fw-bold text-ipb fs-5"><?= $perfil['cargo_nome'] ?: 'Membro Comungante' ?></span>
                        </div>
                    </div>
					<?php if (!empty($perfil['sociedade_nome'])): ?>
					<div class="col-12 col-md-6">
						<div class="p-3 rounded-4 border bg-light h-100 d-flex align-items-center">

							<div class="me-3">
								<?php
								// Monta o caminho completo. Se não houver logo, usamos um ícone padrão.
								$urlLogoSociedade = !empty($perfil['sociedade_logo'])
									? url("public/assets/uploads/" . $perfil['sociedade_logo'])
									: null;
								?>

								<?php if ($urlLogoSociedade): ?>
									<img src="<?= $urlLogoSociedade ?>"
										 alt="Logo <?= $perfil['sociedade_nome'] ?>"
										 class="rounded-circle border bg-white"
										 style="width: 45px; height: 45px; object-fit: cover;">
								<?php else: ?>
									<div class="rounded-circle border bg-white d-flex align-items-center justify-content-center text-muted"
										 style="width: 45px; height: 45px;">
										<i class="bi bi-people-fill"></i>
									</div>
								<?php if (isset($perfil['sociedade_logo'])): ?> <?php endif; ?>
								<?php endif; ?>
							</div>

							<div>
								<small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.6rem; letter-spacing: 0.5px;">
									Sociedade Interna
								</small>
								<span class="fw-bold text-dark d-block" style="line-height: 1.2;">
									<?= $perfil['sociedade_nome'] ?>
								</span>
							</div>

						</div>
					</div>
					<?php endif; ?>
                </div>

				<div class="d-flex align-items-center mb-3 mt-4">
					<h6 class="fw-bold text-ipb mb-0 small text-uppercase">Presença EBD - <?= $anoAtual ?></h6>
					<hr class="flex-grow-1 ms-3 opacity-25">
				</div>

				<div class="row g-2 mb-4">
					<div class="col-12">
						<div class="d-flex overflow-auto pb-2 mb-3" style="white-space: nowrap; -webkit-overflow-scrolling: touch;">
							<?php
							$mesesNome = ['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'];
							$mesAtual = date('m');

							foreach ($mesesNome as $num => $nome):
								$active = ($num == $mesAtual) ? 'active bg-ipb text-white' : 'bg-white text-muted border';
							?>
								<button type="button" class="btn btn-sm rounded-pill me-2 fw-bold btn-mes <?= $active ?>"
										onclick="filtrarMes('<?= $num ?>', this)" style="min-width: 60px;">
									<?= substr($nome, 0, 3) ?>
								</button>
							<?php endforeach; ?>
						</div>

						<div class="table-responsive rounded-4 border bg-white shadow-sm">
							<table class="table table-sm table-borderless mb-0">
								<thead class="bg-light">
									<tr class="text-center" style="font-size: 0.7rem;">
										<th class="p-3 text-muted text-uppercase">Data</th>
										<th class="p-3 text-muted text-uppercase text-start">Classe / Aula</th>
										<th class="p-3 text-muted text-uppercase">Status</th>
									</tr>
								</thead>
								<tbody id="corpo-tabela-ebd" style="font-size: 0.85rem;">
									</tbody>
							</table>
						</div>
					</div>
				</div>

                <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
                    <h6 class="fw-bold text-ipb mb-0 small text-uppercase">Filhos e Dependentes -18</h6>
                    <a href="<?= url('PortalMembro/novoDependente') ?>" class="btn btn-outline-ipb btn-sm rounded-pill px-3 fw-bold">
                        <i class="bi bi-plus-lg me-1"></i> ADICIONAR
                    </a>
                </div>

                <?php if (empty($dependentes)): ?>
                    <div class="text-center py-4 border rounded-4 bg-light">
                        <p class="text-muted small mb-0">Nenhum dependente cadastrado.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($dependentes as $dep): ?>
                            <div class="col-12 col-md-6">
                                <div class="card border shadow-none rounded-4">
                                    <div class="card-body p-2 px-3">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <?php
                                                    $dirDep = ($dep['membro_status'] === 'Ativo') ? $dep['membro_registro_interno'] : "PENDENTE_{$dep['membro_id']}";
                                                    $fotoDep = !empty($dep['membro_foto_arquivo'])
                                                        ? url("assets/uploads/{$_SESSION['membro_igreja_id']}/membros/{$dirDep}/{$dep['membro_foto_arquivo']}")
                                                        : url("assets/img/avatar-default.png");
                                                ?>
                                                <img src="<?= $fotoDep ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-0 fw-bold small text-uppercase"><?= $dep['membro_nome'] ?></h6>
                                                <span class="badge badge-status rounded-pill <?= $dep['membro_status'] === 'Ativo' ? 'bg-success-subtle' : 'bg-warning-subtle' ?>">
                                                    <?= $dep['membro_status'] ?>
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-chevron-right text-muted opacity-50"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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

<div class="modal fade" id="modalQRCode" tabindex="-1" aria-labelledby="modalQRCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <i class="bi bi-person-badge text-ipb mb-2" style="font-size: 2rem;"></i>
                <h5 class="fw-bold text-ipb mb-1" id="modalQRCodeLabel">Cartão de Acesso</h5>
                <p class="small text-muted mb-4">Apresente este código para marcar sua presença</p>

                <div class="bg-light p-4 rounded-4 d-inline-block shadow-sm mb-4">
                    <div id="qrcode-container"></div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold mb-0 text-uppercase"><?= $perfil['membro_nome'] ?></h6>
                    <code class="text-muted"><?= $perfil['membro_registro_interno'] ?></code>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-4" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> IMPRIMIR
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
// Captura os dados vindos do PHP de forma segura
const todasPresencas = <?= json_encode($presencas_mensais ?? []) ?>;
const mesesNomesCompletos = <?= json_encode($mesesNome) ?>;

function filtrarMes(mes, btn) {
    // 1. Atualizar visual dos botões
    document.querySelectorAll('.btn-mes').forEach(b => {
        b.classList.remove('active', 'bg-ipb', 'text-white');
        b.classList.add('bg-white', 'text-muted', 'border');
    });
    btn.classList.add('active', 'bg-ipb', 'text-white');
    btn.classList.remove('bg-white', 'text-muted', 'border');

    const corpo = document.getElementById('corpo-tabela-ebd');
    corpo.innerHTML = '';

    // 2. Formatar a chave do mês (ex: "4" vira "04")
    const mesKey = mes.toString().padStart(2, '0');

    // 3. Verificar se existem dados para o mês selecionado
    if (!todasPresencas[mesKey] || todasPresencas[mesKey].length === 0) {
        corpo.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x d-block fs-2 opacity-25 mb-2"></i>
            <small>Nenhum registro de presença em ${mesesNomesCompletos[mesKey]}.</small>
        </td></tr>`;
        return;
    }

    // 4. Renderizar as linhas
    todasPresencas[mesKey].forEach(p => {
        // Formata a data de YYYY-MM-DD para DD/MM
        const dataOriginal = new Date(p.presenca_data + 'T00:00:00');
        const dataFormatada = dataOriginal.toLocaleDateString('pt-BR', {day: '2-digit', month: '2-digit'});

        // Define o badge de status
        const statusBadge = p.presenca_status == 1
            ? '<span class="badge bg-success-subtle text-success rounded-pill px-3">Presente</span>'
            : '<span class="badge bg-danger-subtle text-danger rounded-pill px-3">Falta</span>';

        const tr = document.createElement('tr');
        tr.className = 'text-center border-bottom align-middle';
        tr.innerHTML = `
            <td class="p-3 fw-bold text-muted">${dataFormatada}</td>
            <td class="p-3 text-start">
                <div class="fw-bold text-dark">${p.classe_nome}</div>
                <div class="text-muted" style="font-size: 0.7rem;">Escola Bíblica Dominical</div>
            </td>
            <td class="p-3">${statusBadge}</td>
        `;
        corpo.appendChild(tr);
    });
}

// Inicializa a tabela com o mês atual assim que a página carrega
document.addEventListener('DOMContentLoaded', function() {
    const mesAtual = '<?= date('m') ?>';
    const btnAtivo = document.querySelector(`.btn-mes.active`);
    if(btnAtivo) {
        filtrarMes(mesAtual, btnAtivo);
    }
});

document.addEventListener("DOMContentLoaded", function() {
    // Pegamos o registro interno do PHP
    const registroInterno = "<?= $perfil['membro_registro_interno'] ?>";

    if (registroInterno) {
        new QRCode(document.getElementById("qrcode-container"), {
            text: registroInterno,
            width: 200,
            height: 200,
            colorDark : "#005a32", // Cor Verde IPB
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    }
});
</script>
