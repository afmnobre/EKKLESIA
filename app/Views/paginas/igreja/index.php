<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">⛪ Informações da Instituição</h3>
        <a href="<?= url('igreja/editar') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-pencil"></i> Editar Dados
        </a>
    </div>

    <?php if (!empty($igreja)): ?>

    <div class="row mb-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary small fw-bold text-uppercase">📄 Ficha Cadastral</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase d-block">Nome da Igreja</label>
                        <p class="fs-6 mb-0 text-dark fw-bold"><?= htmlspecialchars($igreja['igreja_nome']) ?></p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block">CNPJ</label>
                            <p class="small mb-0 text-dark"><?= !empty($igreja['igreja_cnpj']) ? htmlspecialchars($igreja['igreja_cnpj']) : 'Não informado' ?></p>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block">Pastor Titular</label>
                            <div class="d-flex align-items-center">
                                <p class="small mb-0 text-primary fw-bold me-1"><?= htmlspecialchars($pastorNome ?? 'Não definido') ?></p>
                                <button type="button" class="btn btn-sm p-0 text-primary" data-bs-toggle="modal" data-bs-target="#modalPastor" title="Alterar">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small fw-bold text-uppercase d-block">Endereço Sede</label>
                        <p class="small mb-0 text-dark"><?= !empty($igreja['igreja_endereco']) ? htmlspecialchars($igreja['igreja_endereco']) : 'Não informado' ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary small fw-bold text-uppercase">📱 Redes Sociais</h5>
                    <button type="button" class="btn btn-sm btn-success py-0" data-bs-toggle="modal" data-bs-target="#modalRedeSocial">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Plataforma</th>
                                    <th>Usuário</th>
                                    <th class="text-end pe-3">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($redes)): foreach ($redes as $rede): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold"><?= htmlspecialchars($rede['rede_nome']) ?></td>
                                        <td class="text-truncate" style="max-width: 100px;"><?= htmlspecialchars($rede['rede_usuario']) ?></td>
                                        <td class="text-end pe-3">
                                            <a href="<?= url('igreja/excluirRedeSocial/' . $rede['rede_id']) ?>" class="text-danger" onclick="return confirm('Excluir?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3 small">Nenhuma cadastrada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center position-relative">
                    <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalLogo">
                        <i class="bi bi-camera"></i>
                    </button>

                    <?php
                        $logoPath = !empty($igreja['igreja_logo'])
                            ? url("assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}")
                            : url("assets/img/logo_placeholder.png");
                    ?>

                    <div class="bg-white rounded p-2 mb-2 shadow-sm">
                        <img src="<?= $logoPath ?>" alt="Logo" style="width: 80px; height: 80px; object-fit: contain;">
                    </div>
                    <p class="small fw-bold mb-0 text-uppercase" style="font-size: 0.7rem;">Logo Institucional</p>
                </div>
            </div>
        </div>
    </div>


	<div class="row mb-4">
			<div class="col-12">
				<div class="card border-0 shadow-sm">
					<div class="card-header bg-white py-3">
						<h5 class="mb-0 text-primary small fw-bold text-uppercase">👥 Corpo de Oficiais e Liderança</h5>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive">
							<table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
								<thead class="table-light">
									<tr>
										<th class="ps-4" style="width: 60px;">Foto</th>
										<th>Nome do Oficial</th>
										<th>Cargo / Ofício</th>
										<th class="text-end pe-4">Status</th>
									</tr>
								</thead>
								<tbody>
									<?php if (!empty($lideranca)):
										// Mapeamento das siglas por Código do Cargo
										$siglas = [
											1 => 'Rev.',   // Pastor
											2 => 'Pr. Aux.', // Pastor Auxiliar
											5 => 'Pb.',    // Presbítero Regente
											7 => 'Diác.',  // Diácono
											3 => 'Sem.'    // Seminarista
										];

										foreach ($lideranca as $lider):
											// Busca a sigla baseada no ID do cargo retornado pela query
											$prefixo = $siglas[$lider['vinculo_cargo_id']] ?? '';
									?>
										<tr>
											<td class="ps-4">
												<?php if (!empty($lider['membro_foto_arquivo'])): ?>
													<img src="<?= url("assets/uploads/{$igreja['igreja_id']}/membros/{$lider['membro_registro_interno']}/{$lider['membro_foto_arquivo']}") ?>"
														 alt="<?= htmlspecialchars($lider['membro_nome']) ?>"
														 class="rounded-circle shadow-sm border"
														 style="width: 40px; height: 40px; object-fit: cover;">
												<?php else: ?>
													<div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-secondary shadow-sm"
														 style="width: 40px; height: 40px;"
														 title="Sem foto">
														<i class="bi bi-person-fill"></i>
													</div>
												<?php endif; ?>
											</td>
											<td class="fw-bold text-dark text-uppercase">
												<?php if ($prefixo): ?>
													<span class="text-primary fw-bold me-1"><?= $prefixo ?></span>
												<?php endif; ?>
												<?= htmlspecialchars($lider['membro_nome']) ?>
											</td>
											<td>
												<span class="badge bg-secondary-soft text-secondary border px-2 py-1">
													<?= htmlspecialchars($lider['cargo_nome']) ?>
												</span>
											</td>
											<td class="text-end pe-4">
												<small class="text-success fw-bold" title="Vínculo Ativo">
													<i class="bi bi-check-circle-fill"></i>
												</small>
											</td>
										</tr>
									<?php endforeach; else: ?>
										<tr>
											<td colspan="4" class="text-center text-muted py-4 small">
												<i class="bi bi-info-circle me-1"></i> Nenhum oficial cadastrado nos cargos principais.
											</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	<div class="row">
		<div class="col-12">
			<div class="card border-0 shadow-sm mb-4">
				<div class="card-header bg-white py-3">
					<h5 class="mb-0 text-primary small fw-bold text-uppercase">📅 Programação e Cultos Recorrentes</h5>
				</div>
				<div class="card-body p-4">
					<form action="<?= url('igreja/salvarProgramacao') ?>" method="POST" class="mb-4">
						<div class="row g-3 align-items-end">
							<div class="col-md-3">
								<label class="text-muted small fw-bold text-uppercase mb-1">Atividade</label>
								<input type="text" name="prog_titulo" class="form-control" placeholder="Ex: Culto Público" required>
							</div>
							<div class="col-md-2">
								<label class="text-muted small fw-bold text-uppercase mb-1">Dia da Semana</label>
								<select name="prog_dia" class="form-select" required>
									<option value="Domingo">Domingo</option>
									<option value="Segunda">Segunda</option>
									<option value="Terça">Terça</option>
									<option value="Quarta">Quarta</option>
									<option value="Quinta">Quinta</option>
									<option value="Sexta">Sexta</option>
									<option value="Sábado">Sábado</option>
								</select>
							</div>
							<div class="col-md-2">
								<label class="text-muted small fw-bold text-uppercase mb-1">Horário</label>
								<input type="time" name="prog_hora" class="form-control" required>
							</div>
							<div class="col-md-2">
								<label class="text-muted small fw-bold text-uppercase mb-1">Recorrência</label>
								<select name="prog_recorrencia" class="form-select">
									<option value="0">Toda Semana</option>
									<option value="1">1º da Semana</option>
									<option value="2">2º da Semana</option>
									<option value="3">3º da Semana</option>
									<option value="4">4º da Semana</option>
								</select>
							</div>

							<div class="col-md-3">
								<div class="d-flex flex-wrap gap-3 mb-2">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="prog_is_externo" id="checkExterno" value="1">
										<label class="form-check-label small fw-bold text-primary" for="checkExterno">EXTERNO</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" name="prog_is_ceia" id="checkCeia">
										<label class="form-check-label small fw-bold text-danger" for="checkCeia">SANTA CEIA</label>
									</div>
								</div>
								<button type="submit" class="btn btn-primary w-100">
									<i class="bi bi-plus-lg"></i> Adicionar
								</button>
							</div>
						</div>
					</form>

					<div class="table-responsive">
						<table class="table table-hover align-middle">
							<thead class="table-light">
								<tr>
									<th>Dia / Horário</th>
									<th>Atividade</th>
									<th>Frequência</th>
									<th class="text-end">Ações</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($programacoes)): foreach ($programacoes as $p): ?>
								<tr>
									<td class="fw-bold">
										<span class="text-secondary"><?= $p['programacao_dia_semana'] ?></span><br>
										<small class="text-muted"><?= date('H:i', strtotime($p['programacao_hora'])) ?></small>
									</td>
									<td>
										<?php if ($p['programacao_is_externo']): ?>
											<i class="bi bi-house-door text-primary me-1" title="Evento Externo"></i>
										<?php endif; ?>

										<span class="fw-bold"><?= htmlspecialchars($p['programacao_titulo']) ?></span>

										<?php if ($p['programacao_is_ceia']): ?>
											<span class="badge bg-danger ms-1 small"><i class="bi bi-cup-hot"></i> CEIA</span>
										<?php endif; ?>

										<?php if ($p['programacao_local_nome']): ?>
											<div class="small text-muted" style="font-size: 0.75rem;">
												<i class="bi bi-geo-alt"></i> <?= $p['programacao_local_nome'] ?>
											</div>
										<?php endif; ?>
									</td>
									<td>
										<?php
											$semanas = [0 => 'Semanal', 1 => '1ª semana', 2 => '2ª semana', 3 => '3ª semana', 4 => '4ª semana'];
											echo '<span class="badge bg-light text-dark border">' . $semanas[$p['programacao_recorrencia_mensal']] . '</span>';
										?>
									</td>
									<td class="text-end">
										<div class="btn-group">
											<?php if ($p['programacao_is_externo']): ?>
												<button type="button" class="btn btn-sm btn-outline-primary btn-set-local"
														data-id="<?= $p['programacao_id'] ?>"
														data-titulo="<?= htmlspecialchars($p['programacao_titulo']) ?>"
														data-dia="<?= $p['programacao_dia_semana'] ?>"
														data-bs-toggle="modal" data-bs-target="#modalEndereco">
													<i class="bi bi-geo-alt-fill"></i>
												</button>
											<?php endif; ?>

											<a href="<?= url('igreja/excluirProgramacao/'.$p['programacao_id']) ?>"
											   class="btn btn-sm btn-outline-danger"
											   onclick="return confirm('Deseja realmente excluir esta programação?')"
											   title="Excluir">
												<i class="bi bi-trash"></i>
											</a>
										</div>
									</td>
								</tr>
								<?php endforeach; else: ?>
								<tr>
									<td colspan="4" class="text-center text-muted py-4">
										<i class="bi bi-calendar-x d-block mb-2 fs-4"></i>
										Nenhuma programação cadastrada.
									</td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

    <?php else: ?>
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Dados não encontrados.
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalRedeSocial" tabindex="-1" aria-labelledby="modalRedeSocialLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('igreja/salvarRedeSocial') ?>" method="POST" style="width: 100%;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="modalRedeSocialLabel">🚀 Nova Rede Social / Contato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Plataforma</label>
                        <select name="rede_nome" class="form-select" required>
                            <option value="Instagram">Instagram</option>
                            <option value="WhatsApp">WhatsApp (Telefone)</option>
                            <option value="Facebook">Facebook</option>
                            <option value="YouTube">YouTube</option>
                            <option value="E-mail">E-mail</option>
                            <option value="Site">Site Oficial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Usuário ou Identificador</label>
                        <input type="text" name="rede_usuario" class="form-control" placeholder="Ex: @igreja ou (11) 99999-9999" required>
                        <div class="form-text">Como você deseja que apareça no documento.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Exibir na Carteirinha?</label>
                        <select name="rede_status" class="form-select">
                            <option value="ativo">Sim, manter Ativo</option>
                            <option value="inativo">Não, manter Oculto</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Gravar Dados</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalPastor" tabindex="-1" aria-labelledby="modalPastorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('igreja/salvarPastor') ?>" method="POST" style="width: 100%;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalPastorLabel">👔 Definir Pastor Titular</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted">Selecione o membro que será o Pastor Titular da instituição.</p>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Pesquisar por nome</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text"
                                   id="inputBuscaPastor"
                                   class="form-control border-start-0 shadow-none"
                                   placeholder="Digite parte do nome..."
                                   onkeyup="window.filtrarMembrosPastor(this.value)">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-uppercase text-muted">Selecione o Membro</label>
                        <select name="pastor_id"
                                id="selectMembrosPastor"
                                class="form-select shadow-none"
                                size="6"
                                required>
                            <option value="" class="text-muted italic">-- Selecione um Membro --</option>
                            <?php foreach ($membros as $m): ?>
                                <option value="<?= $m['membro_id'] ?>"
                                        data-nome="<?= mb_strtolower($m['membro_nome']) ?>"
                                        <?= ($igreja['igreja_pastor_id'] == $m['membro_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['membro_nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text mt-2"><i class="bi bi-info-circle"></i> A lista acima mostra apenas membros desta igreja.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Atualizar Pastor</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalLogo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form action="<?= url('igreja/uploadLogo') ?>" method="POST" enctype="multipart/form-data" style="width: 100%;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">🖼️ Logo da Igreja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="small text-muted mb-3">Escolha uma imagem PNG ou JPG.</p>
                    <input type="file" name="igreja_logo" class="form-control mb-2" accept="image/*" required>
                    <div class="form-text">O arquivo antigo será removido.</div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="submit" class="btn btn-primary w-100">Substituir Logo</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEndereco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary"><i class="bi bi-geo-alt"></i> Escala de Locais: <span id="labelNomeProg"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 border-end">
                        <h6 class="fw-bold small text-uppercase mb-3">Agendar Novo Local</h6>
                        <form id="formSalvarEscala">
                            <input type="hidden" name="programacao_id" id="modal_prog_id">

                            <div class="mb-3">
                                <label class="small fw-bold">Data do Evento</label>
                                <input type="text" name="data_evento" id="modal_data_evento" class="form-control bg-white" placeholder="Selecione a data..." required>
                            </div>

							<div class="mb-3">
								<label class="small fw-bold">Selecionar Membro/Anfitrião</label>
								<select name="membro_id" id="selectMembro" class="form-control" required>
									<option value="">Pesquisar...</option>
								</select>
								<input type="hidden" name="local_nome_endereco" id="local_nome_endereco">
							</div>

                            <button type="submit" class="btn btn-primary w-100">Confirmar na Escala</button>
                        </form>
                    </div>

                    <div class="col-md-7">
                        <h6 class="fw-bold small text-uppercase mb-3">Locais já Agendados</h6>
                        <div id="listaLocaisCadastrados" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center py-3 text-muted">Carregando...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-secondary-soft { background-color: rgba(108, 117, 125, 0.1); }
    .card-header h5 { font-size: 0.85rem; letter-spacing: 0.5px; }
    /* Ajuste para o scroll da lista de escalas */
    #listaLocaisCadastrados { max-height: 300px; overflow-y: auto; overflow-x: hidden; }
</style>

<script>
// --- LÓGICA DO PASTOR ---
document.getElementById('modalPastor')?.addEventListener('hidden.bs.modal', function () {
    const input = document.getElementById('inputBuscaPastor');
    if(input) {
        input.value = "";
        window.filtrarMembrosPastor("");
    }
});

window.filtrarMembrosPastor = function(termo) {
    const busca = termo.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").trim();
    const select = document.getElementById('selectMembrosPastor');
    if(!select) return;
    const options = select.options;
    for (let i = 0; i < options.length; i++) {
        if (options[i].value === "") continue;
        const nomeMembro = (options[i].getAttribute('data-nome') || options[i].text).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
        options[i].style.display = nomeMembro.includes(busca) ? "" : "none";
    }
};

// --- LÓGICA DE ESCALA DE LOCAIS (CHOICES.JS + FLATPICKR) ---
let selectMembroChoices;
let calendarioFlatpickr;

document.addEventListener('DOMContentLoaded', function() {
    const mapaDias = {
        'Domingo': 0, 'Segunda': 1, 'Terça': 2, 'Quarta': 3,
        'Quinta': 4, 'Sexta': 5, 'Sábado': 6
    };

    const inputData = document.getElementById('modal_data_evento');

    const el = document.getElementById('selectMembro');
    if (el) {
        selectMembroChoices = new Choices(el, {
            searchPlaceholderValue: 'Nome do membro...',
            noResultsText: 'Nenhum membro encontrado',
            itemSelectText: 'Clique para selecionar',
            shouldSort: false,
            removeItemButton: false,
            searchEnabled: true,
            searchChoices: true
        });
    }

    document.querySelectorAll('.btn-set-local').forEach(btn => {
        btn.addEventListener('click', function() {
            const progId = this.dataset.id;
            const titulo = this.dataset.titulo;
            const diaSemanaTexto = this.dataset.dia;
            const diaAlvo = mapaDias[diaSemanaTexto];

            document.getElementById('modal_prog_id').value = progId;
            document.getElementById('labelNomeProg').innerText = titulo;

            if (calendarioFlatpickr) {
                calendarioFlatpickr.destroy();
            }

            calendarioFlatpickr = flatpickr(inputData, {
                locale: "pt",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                minDate: "today",
                disable: [
                    function(date) {
                        return (date.getDay() !== diaAlvo);
                    }
                ]
            });

            carregarMembros();
            carregarEscalas(progId);
        });
    });

	document.getElementById('formSalvarEscala')?.addEventListener('submit', function(e) {
		e.preventDefault();

		// 1. Pegamos a seleção atual diretamente da instância do Choices
		const selectedData = selectMembroChoices.getValue();

		if (!selectedData || selectedData.value === "") {
			alert("Por favor, selecione um membro ou a sede.");
			return;
		}

		// 2. Definimos o endereço textual para o campo hidden
		const enderecoTexto = (selectedData.customProperties && selectedData.customProperties.endereco)
			? selectedData.customProperties.endereco
			: "Sede da Igreja";
		document.getElementById('local_nome_endereco').value = enderecoTexto;

		// 3. Criamos o FormData
		const formData = new FormData(this);

		// 4. FORÇAMOS o valor do membro_id vindo do Choices dentro do FormData
		// Isso garante que o ID real seja enviado, ignorando o estado do select original
		formData.set('membro_id', selectedData.value);

		const progId = document.getElementById('modal_prog_id').value;

		fetch('<?= url("igreja/salvarEscalaLocal") ?>', {
			method: 'POST',
			body: formData
		}).then(res => res.json()).then(data => {
			if(data.success) {
				carregarEscalas(progId);
				this.reset();
				if(calendarioFlatpickr) calendarioFlatpickr.clear();
				selectMembroChoices.setChoiceByValue('0');
			} else {
				alert("Erro ao salvar: " + (data.message || "Verifique os dados."));
			}
		}).catch(err => {
			console.error("Erro ao salvar:", err);
		});
	});
});

function carregarMembros() {
    fetch('<?= url("igreja/buscarMembrosJson") ?>')
        .then(res => res.json())
        .then(data => {
            if(!selectMembroChoices) return;

            selectMembroChoices.clearChoices();

            let items = [{
                value: '0',
                label: '📍 Sede da Igreja',
                selected: false,
                customProperties: { endereco: 'Sede da Igreja' }
            }];

            data.forEach(m => {
                const enderecoCompleto = `Residencia: ${m.membro_nome} - ${m.endereco}`;

                // Se o membro_id não existir no JSON, usamos o nome (para não sumir a lista)
                // Mas atenção: se usar o nome, o banco salvará 0 se a coluna for INT.
                const idValor = (m.membro_id !== undefined && m.membro_id !== null)
                                ? m.membro_id.toString()
                                : m.membro_nome;

                items.push({
                    value: idValor,
                    label: enderecoCompleto,
                    customProperties: { endereco: enderecoCompleto }
                });
            });

            selectMembroChoices.setChoices(items, 'value', 'label', true);
        })
        .catch(err => console.error("Erro ao carregar membros:", err));
}

function carregarEscalas(progId) {
    const lista = document.getElementById('listaLocaisCadastrados');
    if(!lista) return;

    lista.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

    fetch('<?= url("igreja/listarEscalas/") ?>' + progId)
        .then(response => response.json())
        .then(data => {
            lista.innerHTML = '';
            if(!data || data.length === 0) {
                lista.innerHTML = '<div class="text-center p-4 text-muted small">Nenhuma data agendada para esta programação.</div>';
                return;
            }

            data.forEach(item => {
                let html = `
                    <div class="list-group-item d-flex justify-content-between align-items-center p-2 border-start-0 border-end-0">
                        <div style="line-height: 1.2;">
                            <span class="badge bg-secondary mb-1" style="font-size: 0.65rem;">${item.data_formatada}</span><br>
                            <small class="fw-bold text-dark">${item.local_nome_endereco}</small>
                        </div>
                        <button type="button" class="btn btn-sm text-danger p-1" onclick="excluirEscala(${item.id}, ${progId})">
                            <i class="bi bi-trash fs-6"></i>
                        </button>
                    </div>`;
                lista.insertAdjacentHTML('beforeend', html);
            });
        });
}

function excluirEscala(id, progId) {
    if(confirm('Deseja remover esta data da escala?')) {
        fetch('<?= url("igreja/excluirEscala/") ?>' + id)
            .then(res => {
                if(res.ok) carregarEscalas(progId);
            });
    }
}
</script>
