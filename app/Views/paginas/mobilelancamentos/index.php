<style>
    /* Cabeçalho Institucional */
    .header-institucional { background: #fff; border-bottom: 2px solid #212529; padding: 1rem 2rem; margin-bottom: 1.5rem; }
    .logo-header { height: 60px; object-fit: contain; }

    /* Avatares dos Diáconos */
    .avatar-diacono {
        width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
        border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #eee;
    }
    .diacono-item { display: flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 4px 12px; border-radius: 50px; border: 1px solid #e9ecef; }

    /* Estilos dos Filtros e Componentes */
    .choices__inner { background-color: #f8f9fa; border-radius: 0.375rem; border: 1px solid #dee2e6; min-height: 45px; padding: 5px 10px; }
    .nav-pills .nav-link.active { background-color: #212529; color: white !important; }
    .nav-link { transition: all 0.2s; }


/* Ajustes Mobile */
    .scroll-horizontal-mobile { display: flex; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 5px; -webkit-overflow-scrolling: touch; }
    .scroll-horizontal-mobile::-webkit-scrollbar { height: 4px; }
    .scroll-horizontal-mobile::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    @media (max-width: 768px) {
        .btn-mobile-full { width: 100%; margin-bottom: 0.5rem; justify-content: center; }
        .header-institucional { padding: 1rem !important; }
        .logo-header { height: 45px; }
        .table-responsive { border: 0; }
        .linha-rateio > div { margin-bottom: 10px; }
    }

</style>

<div class="header-institucional shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center text-center text-md-start">
            <div class="col-6 col-md-2 mb-2 mb-md-0 d-flex justify-content-center justify-content-md-start">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" class="logo-header">
            </div>
            <div class="col-12 col-md-8 text-center order-3 order-md-2 mt-2 mt-md-0">
                <h3 class="fw-bold mb-0 text-uppercase fs-5 fs-md-3" style="letter-spacing: 1px; color: #212529;">
                    <?= htmlspecialchars($igreja['igreja_nome']) ?>
                </h3>
                <p class="small text-muted mb-0"><?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?></p>
            </div>
            <div class="col-6 col-md-2 mb-2 mb-md-0 d-flex justify-content-center justify-content-md-end order-2 order-md-3">
                <?php
                    $caminhoLogo = "assets/uploads/{$igreja['igreja_id']}/logo/{$igreja['igreja_logo']}";
                    if(!empty($igreja['igreja_logo'])):
                ?>
                    <img src="<?= url($caminhoLogo) ?>" alt="Logo Local" class="logo-header">
                <?php else: ?>
                    <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB" class="logo-header">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4 mx-3">
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-3 border-end">
                <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Filtrar por Ano</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-calendar-event text-primary"></i></span>
                    <select class="form-select border-0 bg-light fw-bold" onchange="location.href='?mes=<?= $mesSelecionado ?>&ano='+this.value">
                        <?php foreach($anosDisponiveis as $a): ?>
                            <option value="<?= $a['ano'] ?>" <?= $a['ano'] == $anoSelecionado ? 'selected' : '' ?>><?= $a['ano'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-9 mt-3 mt-md-0">
                <label class="small fw-bold text-muted text-uppercase mb-1 d-block text-center text-md-start ps-0 ps-md-3">Mês de Referência</label>
                <div class="nav nav-pills bg-light p-1 rounded-pill mx-0 mx-md-2 scroll-horizontal-mobile">
                    <?php
                    $meses = [1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'];                    foreach($meses as $num => $nome): ?>
                        <div class="nav-item">
                            <a class="nav-link py-1 rounded-pill <?= $num == $mesSelecionado ? 'active shadow-sm' : 'text-dark fw-bold' ?>"
                               style="font-size: 0.85rem;" href="?ano=<?= $anoSelecionado ?>&mes=<?= $num ?>">
                                <?= $nome ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-2 px-2 px-md-4">
    <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 fs-4"><i class="bi bi-shield-check me-2 text-success"></i>Conferência de Dízimos</h3>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <?php
                $getFoto = function($diacono, $igrejaId) {
                    if (!empty($diacono['foto'])) {
                        return url("assets/uploads/{$igrejaId}/membros/{$diacono['registro']}/{$diacono['foto']}");
                    }
                    return url("assets/img/default-avatar.png");
                };
                ?>

                <div class="diacono-item shadow-sm flex-grow-1 flex-md-grow-0">
                    <img src="<?= $getFoto($_SESSION['conf_diacono_1'], $igreja['igreja_id']) ?>" class="avatar-diacono">
                    <div>
                        <small class="text-muted d-block" style="font-size: 9px; line-height: 1;">CONFERENTE 1</small>
                        <span class="fw-bold small"><?= $_SESSION['conf_diacono_1']['nome'] ?></span>
                    </div>
                </div>

                <div class="diacono-item shadow-sm flex-grow-1 flex-md-grow-0">
                    <img src="<?= $getFoto($_SESSION['conf_diacono_2'], $igreja['igreja_id']) ?>" class="avatar-diacono">
                    <div>
                        <small class="text-muted d-block" style="font-size: 9px; line-height: 1;">CONFERENTE 2</small>
                        <span class="fw-bold small"><?= $_SESSION['conf_diacono_2']['nome'] ?></span>
                    </div>
                </div>
            </div>
        </div>

		<div class="d-flex flex-wrap gap-2 w-100 w-xl-auto">
            <button class="btn btn-outline-dark shadow-sm btn-mobile-full flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalRelatorioConferencia">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <button class="btn btn-outline-secondary shadow-sm btn-mobile-full flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalRelatorioContabil">
                <i class="bi bi-file-earmark-spreadsheet"></i> Contábil
            </button>
            <button class="btn btn-success shadow-sm btn-mobile-full flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalLancamentoIndividual">
                <i class="bi bi-person-heart"></i> Individual
            </button>
            <button class="btn btn-dark shadow-sm btn-mobile-full flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalNovoLancamento">
                <i class="bi bi-layers"></i> Em Lote
            </button>
            <a href="<?= url('dizimoOferta/sair') ?>" class="btn btn-outline-danger shadow-sm btn-mobile-full flex-grow-1">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
		<!-- CABEÇALHO DO CARD COM O FILTRO -->
		<div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
			<h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-list-ul me-2"></i>Relação de Lançamentos</h6>
			<div class="form-check form-switch mb-0">
				<input class="form-check-input" type="checkbox" role="switch" id="switchFiltroHoje" onchange="filtrarLancamentosHoje(this)" style="cursor: pointer;">
				<label class="form-check-label small fw-bold text-muted mt-1 ms-1" for="switchFiltroHoje" style="cursor: pointer;">
					Mostrar só de hoje
				</label>
			</div>
		</div>

		<div class="card-body p-0" id="container-lista-lancamentos">
			<?php if(empty($lancamentos)): ?>
				<div class="text-center py-5 text-muted">
					<i class="bi bi-inbox fs-1 d-block mb-2"></i>
					Nenhum lançamento conferido por esta dupla neste mês.
				</div>
			<?php else: ?>
				<div class="list-group list-group-flush rounded">
					<?php foreach($lancamentos as $l):
						$temRateio = !empty($l['membros']);
						$temComprovante = !empty($l['financeiro_conta_comprovante']);

						// Pegamos a data para usar no filtro JS (Prioriza a data de criação, se não, usa a do pagamento)
						$dataFiltro = date('Y-m-d', strtotime($l['financeiro_conta_created_at'] ?? $l['financeiro_conta_data_pagamento']));
					?>
					<!-- ADICIONADA A CLASSE 'item-lancamento' E O ATRIBUTO 'data-data' -->
					<div class="list-group-item py-3 px-3 hover-bg-light transition-all border-bottom item-lancamento" data-data="<?= $dataFiltro ?>">

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">

							<!-- 1. Foto e Nome (Membro ou Anônimo) -->
							<div class="d-flex flex-wrap gap-2 align-items-center" style="min-width: 250px;">
								<?php if($temRateio): ?>
									<div class="d-flex flex-column gap-2 w-100">
										<?php foreach($l['membros'] as $m):
											$urlFoto = !empty($m['membro_foto_arquivo'])
												? url("assets/uploads/{$igreja['igreja_id']}/membros/{$m['membro_registro_interno']}/{$m['membro_foto_arquivo']}")
												: url("assets/img/default-avatar.png");
										?>
											<div class="d-flex align-items-center gap-2">
												<img src="<?= $urlFoto ?>"
													 alt="Foto"
													 class="rounded-circle border shadow-sm flex-shrink-0"
													 style="width: 48px; height: 48px; object-fit: cover;">
												<div class="lh-sm">
													<span class="d-block fw-semibold text-dark" style="font-size: 0.95rem;">
														<?= htmlspecialchars($m['membro_nome'] ?? '') ?>
													</span>
													<span class="text-muted" style="font-size: 0.75rem;">
														ID: <?= $l['financeiro_conta_id'] ?>
													</span>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
								<?php else: ?>
									<div class="d-flex align-items-center gap-2">
										<div class="bg-light rounded-circle border shadow-sm d-flex justify-content-center align-items-center flex-shrink-0" style="width: 48px; height: 48px;">
											<i class="bi bi-person-x text-muted fs-4"></i>
										</div>
										<div class="lh-sm">
											<span class="d-block fw-semibold text-muted" style="font-size: 0.95rem;">Anônimo</span>
											<span class="text-muted" style="font-size: 0.75rem;">ID: <?= $l['financeiro_conta_id'] ?></span>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<!-- 2. Categoria, Valor e Data -->
							<div class="flex-grow-1 text-start text-md-center w-100 mt-1 mt-md-0">
								<span class="badge rounded-pill bg-light text-secondary border mb-1">
									<?= htmlspecialchars($l['subcategoria_nome'] ?? $l['financeiro_subcategoria_nome'] ?? $l['financeiro_categoria_nome'] ?? '') ?>
								</span>
								<div class="fw-bold text-primary mb-1" style="font-size: 1.25rem;">
									R$ <?= number_format($l['financeiro_conta_valor'], 2, ',', '.') ?>
								</div>
								<div class="text-muted small">
									<i class="bi bi-calendar2-check"></i> Pago em <?= date('d/m/Y', strtotime($l['financeiro_conta_data_pagamento'])) ?>
								</div>
							</div>

							<!-- 3. Botões de Ação -->
							<div class="d-flex align-items-center gap-2 w-100 w-md-auto mt-2 mt-md-0">
								<!-- Botão de Upload de Foto/Comprovante -->
								<button class="btn <?= $temComprovante ? 'btn-success' : 'btn-primary' ?> btn-sm flex-grow-1 flex-md-grow-0 d-flex justify-content-center align-items-center gap-2 shadow-sm"
										onclick='abrirGerenciadorAnexos(<?= json_encode($l) ?>)'
										title="Anexar Foto/Comprovante">
									<i class="bi <?= $temComprovante ? 'bi-image-fill' : 'bi-camera' ?> fs-6"></i>
									<span class="d-md-none fw-semibold">Comprovante</span>
								</button>

								<?php
									// Regra de exclusão: Limite de 1 HORA
									$dataReferenciaStr = $l['financeiro_conta_created_at'] ?? $l['created_at'] ?? $l['financeiro_conta_data_pagamento'] ?? date('Y-m-d H:i:s');
									$dataCriacao = new DateTime($dataReferenciaStr);
									$agora = new DateTime();
									$diferencaHoras = ($agora->getTimestamp() - $dataCriacao->getTimestamp()) / 3600;

									// Limite de 1 hora
									if ($diferencaHoras <= 1):
								?>
									<!-- Botão de Excluir -->
									<button class="btn btn-outline-danger btn-sm flex-grow-1 flex-md-grow-0 d-flex justify-content-center align-items-center gap-2 shadow-sm"
											onclick="excluirLancamento(<?= $l['financeiro_conta_id'] ?>)"
											title="Excluir (Permitido até 1 hora)">
										<i class="bi bi-trash fs-6"></i>
										<span class="d-md-none fw-semibold">Excluir</span>
									</button>
								<?php endif; ?>
							</div>

						</div>
					</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

<script>
function filtrarLancamentosHoje(checkbox) {
    const itens = document.querySelectorAll('.item-lancamento');
    const dataHoje = '<?= date('Y-m-d') ?>'; // Pega a data atual gerada pelo servidor PHP
    let visiveis = 0;

    itens.forEach(item => {
        if (checkbox.checked) {
            // Se o botão estiver ativo, mostra só os que batem com a data de hoje
            if (item.dataset.data === dataHoje) {
                item.classList.remove('d-none');
                item.classList.add('d-flex'); // Mantém o layout flexível ativo
                visiveis++;
            } else {
                item.classList.remove('d-flex');
                item.classList.add('d-none');
            }
        } else {
            // Se o botão for desativado, volta a mostrar todos
            item.classList.remove('d-none');
            item.classList.add('d-flex');
            visiveis++;
        }
    });

    // Controle para exibir mensagem caso não haja nenhum lançamento hoje
    const container = document.getElementById('container-lista-lancamentos');
    let msgVazia = document.getElementById('msg-vazia-hoje');

    if (visiveis === 0) {
        if (!msgVazia) {
            msgVazia = document.createElement('div');
            msgVazia.id = 'msg-vazia-hoje';
            msgVazia.className = 'text-center py-5 text-muted';
            msgVazia.innerHTML = '<i class="bi bi-calendar-x fs-1 d-block mb-2"></i>Nenhum lançamento salvo na data de hoje.';
            container.appendChild(msgVazia);
        }
        msgVazia.style.display = 'block';
    } else {
        if (msgVazia) msgVazia.style.display = 'none';
    }
}
</script>


<!-- MODAL LANÇAMENTO INDIVIDUAL (DUPLO DÍZIMO/OFERTA) -->
<div class="modal fade" id="modalLancamentoIndividual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-check me-2"></i>Lançamento Individual Por Membro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLancamentoIndividual" action="<?= url('dizimoOferta/salvarIndividual') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">Membro Doador/Dizimista</label>
                        <select name="membro_id" id="select-membro-individual" class="form-select" required>
                            <option value="">Digite para pesquisar o membro...</option>
                            <?php foreach($membros as $m): ?>
                                <option value="<?= $m['membro_id'] ?>"><?= htmlspecialchars($m['membro_nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">Data do Recebimento</label>
                        <input type="date" name="data_pagamento" class="form-control bg-light border-0 fw-bold" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <!-- BLOCO DÍZIMO -->
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-success mb-3"><i class="bi bi-wallet2 me-1"></i> Dízimo</h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Valor (R$)</label>
                                    <input type="text" name="dizimo_valor" class="form-control campo-moeda bg-white fw-bold" placeholder="0,00">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Conta Destino Dízimo</label>
                                    <select name="dizimo_conta_id" class="form-select bg-white fw-bold">
                                        <option value="">Selecione a conta...</option>
                                        <?php foreach($contas_bancarias as $conta): ?>
                                            <option value="<?= $conta['id'] ?>">
                                                <?= htmlspecialchars($conta['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BLOCO OFERTA -->
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-gift me-1"></i> Oferta</h6>
                            <div class="row g-2">
                                <div class="col-md-12 mb-2">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Tipo de Oferta</label>
									<select name="oferta_categoria_sub_id" class="form-select bg-white fw-bold">
										<?php
										$lastCat = '';
										foreach($categorias as $cat):
											if($lastCat != $cat['financeiro_categoria_nome']):
												if($lastCat != '') echo '</optgroup>';
												echo '<optgroup label="'. htmlspecialchars($cat['financeiro_categoria_nome']) .'">';
												$lastCat = $cat['financeiro_categoria_nome'];
											endif;

											// Seleciona por padrão a Oferta de Culto (Subcategoria 2 ou nome exato "Oferta" na categoria "Culto - Dizimo e Ofertas")
                                                $isPadrao = ($cat['subcategoria_id'] == 2 ||
														(mb_stripos($cat['financeiro_categoria_nome'], 'Culto') !== false && trim($cat['subcategoria_nome']) === 'Oferta'));
										?>
											<option value="<?= $cat['financeiro_categoria_id'] ?>-<?= $cat['subcategoria_id'] ?>" <?= $isPadrao ? 'selected' : '' ?>>
												<?= htmlspecialchars($cat['subcategoria_nome']) ?>
											</option>
										<?php endforeach; ?>
										<?php if($lastCat != '') echo '</optgroup>'; ?>
									</select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Valor Oferta (R$)</label>
                                    <input type="text" name="oferta_valor" class="form-control campo-moeda bg-white fw-bold" placeholder="0,00">
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted text-uppercase mb-1">Conta Destino Oferta</label>
                                    <select name="oferta_conta_id" class="form-select bg-white fw-bold">
                                        <option value="">Selecione a conta...</option>
                                        <?php foreach($contas_bancarias as $conta): ?>
                                            <option value="<?= $conta['id'] ?>">
                                                <?= htmlspecialchars($conta['nome']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-secondary border-0 small mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Insira pelo menos um dos valores (Dízimo e/ou Oferta). Ambos serão vinculados ao membro selecionado.
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold shadow">
                        <i class="bi bi-check-lg me-1"></i> Salvar Lançamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGerenciarAnexos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-files me-2"></i>Comprovantes de Receita</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div id="info-receita-anexo" class="mb-4 p-3 bg-light rounded border-start border-4 border-primary">
                    </div>

                <div id="lista-uploads-anexos">
                    </div>
            </div>

            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<template id="template-upload-membro">
    <form action="<?= url('dizimoOferta/uploadAnexo') ?>" method="POST" enctype="multipart/form-data" class="card mb-3 border shadow-sm">
        <input type="hidden" name="conta_id" class="up-conta-id">
        <input type="hidden" name="receita_membro_id" class="up-membro-id">
        <input type="hidden" name="tipo_arquivo" value="comprovante">
        <input type="hidden" name="ano_referencia" value="<?= $anoSelecionado ?>">
        <input type="hidden" name="mes_referencia" value="<?= $mesSelecionado ?>">

        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <span class="fw-bold text-dark nome-membro-anexo"></span>
                    <small class="d-block text-muted valor-membro-anexo"></small>
                </div>
                <div class="col-md-5">
                    <input type="file" name="arquivo" class="form-control form-control-sm" required>
                </div>
                <div class="col-md-2 text-end">
					<button type="button" class="btn btn-sm btn-primary w-100 btn-upload-async" onclick="executarUploadAsync(this)">
						<span class="spinner-border spinner-border-sm d-none" role="status"></span>
						<span class="btn-text"><i class="bi bi-upload"></i> Salvar</span>
					</button>
                </div>
            </div>
            <div class="preview-anexo-existente mt-2 d-none">
                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Comprovante já enviado</span>
            </div>
        </div>
    </form>
</template>

<div class="modal fade" id="modalRelatorioConferencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title small fw-bold">GERAR RELATÓRIO</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= url('dizimoOferta/imprimir') ?>" method="GET" target="_blank">
                <div class="modal-body p-4">
                    <label class="small fw-bold text-muted text-uppercase d-block mb-2">Data da Conferência</label>
                    <input type="date" name="data" class="form-control fw-bold" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">
                        <i class="bi bi-file-earmark-pdf"></i> Visualizar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovoLancamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Nova Receita (Lote)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formLancamentoLote" action="<?= url('dizimoOferta/salvar') ?>" method="POST">
                <div class="modal-body p-4">

					<div class="mb-3">
						<label class="small fw-bold text-muted text-uppercase d-block mb-1">Classificação da Receita</label>
						<select name="categoria_sub_id" class="form-select choices-select" required>
							<option value="">Selecione a subcategoria...</option>
							<?php
							$lastCat = '';
							foreach($categorias as $cat):
								if($lastCat != $cat['financeiro_categoria_nome']):
									if($lastCat != '') echo '</optgroup>';
									echo '<optgroup label="'. htmlspecialchars($cat['financeiro_categoria_nome']) .'">';
									$lastCat = $cat['financeiro_categoria_nome'];
								endif;
							?>
								<option value="<?= $cat['financeiro_categoria_id'] ?>-<?= $cat['subcategoria_id'] ?>">
									<?= htmlspecialchars($cat['subcategoria_nome']) ?>
								</option>
							<?php endforeach; ?>
							<?php if($lastCat != '') echo '</optgroup>'; ?>
						</select>
					</div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">Descrição</label>
                        <input type="text" name="descricao" class="form-control bg-light border-0 fw-bold" placeholder="Ex: Dízimo - Culto de Domingo" required>
                    </div>

					<div class="mb-3">
						<label class="small fw-bold text-muted text-uppercase d-block mb-1">Creditar na Conta</label>
						<select name="conta_financeira_id" class="form-select bg-light border-0 fw-bold" required>
							<option value="">Selecione a conta destino...</option>
							<?php foreach($contas_bancarias as $conta): ?>
								<option value="<?= $conta['id'] ?>">
									<?= htmlspecialchars($conta['nome']) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

                    <div class="row">
						<div class="col-md-6 mb-3">
							<label class="small fw-bold text-muted text-uppercase d-block mb-1">Valor (R$)</label>
							<input type="text" name="valor" class="form-control campo-moeda bg-light border-0 fw-bold" placeholder="0,00" required>
						</div>
                        <div class="col-md-6 mb-3">
                            <label class="small fw-bold text-muted text-uppercase d-block mb-1">Data do Recebimento</label>
                            <input type="date" name="data_pagamento" class="form-control bg-light border-0 fw-bold" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

					<div class="mt-4 pt-3 border-top">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div>
								<label class="small fw-bold text-muted text-uppercase d-block">Rateio por Membro</label>
								<span id="badge-saldo" class="badge bg-secondary" style="font-size: 0.75rem;">Saldo: R$ 0,00</span>
							</div>
							<button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="adicionarLinhaRateio()">
								<i class="bi bi-person-plus me-1"></i> Adicionar
							</button>
						</div>

						<div id="container-rateio" class="mb-2">
							</div>

						<div id="aviso-valor" class="alert alert-danger small py-2 d-none">
							<i class="bi bi-exclamation-triangle-fill me-1"></i>
							<strong>Erro:</strong> A soma do rateio ultrapassou o valor total!
						</div>
					</div>

                    <template id="template-rateio">
						<div class="row g-2 mb-2 align-items-center linha-rateio border-bottom pb-2 pb-md-0 border-md-0">
							<div class="col-12 col-md-7">
								<select name="rateio_membro[]" class="form-select select-membro-rateio">
									<option value="">Pesquisar membro...</option>
									<?php foreach($membros as $m): ?>
										<option value="<?= $m['membro_id'] ?>"><?= htmlspecialchars($m['membro_nome']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-10 col-md-4">
								<div class="input-group input-group-sm">
									<span class="input-group-text">R$</span>
									<input type="text" name="rateio_valor[]" class="form-control valor-rateio campo-moeda" placeholder="0,00">
								</div>
							</div>
							<div class="col-2 col-md-1 text-end text-md-center">
								<button type="button" class="btn btn-sm btn-outline-danger p-1 p-md-0 border-0" onclick="removerLinha(this)">
									<i class="bi bi-trash fs-5"></i>
								</button>
							</div>
						</div>
					</template>

                    <div class="alert alert-secondary border-0 small mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Este lançamento será registrado com a assinatura digital dos dois oficiais logados.
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold shadow">
                        <i class="bi bi-check-lg me-1"></i> Salvar Lançamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL RELATÓRIO CONTÁBIL (PARÂMETRO DE DATA) -->
<div class="modal fade" id="modalRelatorioContabil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title small fw-bold"><i class="bi bi-file-earmark-text me-1"></i> RELATÓRIO CONTÁBIL</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('dizimoOferta/relatorioContabil') ?>" method="GET" target="_blank">
                <div class="modal-body p-4">
					<div class="col-md-6 mb-3">
						<label for="data_inicio" class="form-label fw-bold">Data Início</label>
						<input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= date('Y-m-d') ?>" required>
					</div>

					<div class="col-md-6 mb-3">
						<label for="data_fim" class="form-label fw-bold">Data Fim</label>
						<input type="date" name="data_fim" id="data_fim" class="form-control" value="<?= date('Y-m-d') ?>" required>
					</div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="submit" class="btn btn-secondary w-100 fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    let choicesMembroIndividual = null;

    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.choices-select');
        elements.forEach(el => {
            new Choices(el, {
                searchEnabled: true,
                itemSelectText: 'Selecionar',
                noResultsText: 'Nenhuma categoria encontrada',
            });
        });

        // Inicializa o Choices.js no Select do Membro no Modal Individual quando o modal for aberto
        const modalIndividualEl = document.getElementById('modalLancamentoIndividual');
        modalIndividualEl.addEventListener('shown.bs.modal', function () {
            if (!choicesMembroIndividual) {
                const elem = document.getElementById('select-membro-individual');
                choicesMembroIndividual = new Choices(elem, {
                    searchEnabled: true,
                    itemSelectText: '',
                    noResultsText: 'Nenhum membro encontrado',
                    placeholder: true,
                    placeholderValue: 'Digite o nome do membro...'
                });
            }
        });
    });

function adicionarLinhaRateio() {
    const container = document.getElementById('container-rateio');
    const template = document.getElementById('template-rateio');
    const clone = template.content.cloneNode(true);

    const novoSelect = clone.querySelector('.select-membro-rateio');
    container.appendChild(clone);

    // Inicializa o Choices no novo select para permitir pesquisa livre
    new Choices(novoSelect, {
        searchEnabled: true,
        itemSelectText: '',
        noResultsText: 'Membro não encontrado',
        placeholder: true,
        placeholderValue: 'Digite o nome do membro...'
    });
}

function parseBRFloat(valor) {
    if (!valor) return 0;
    if (typeof valor === 'string') {
        let limpo = valor.replace(/\./g, '').replace(',', '.');
        return parseFloat(limpo) || 0;
    }
    return parseFloat(valor) || 0;
}

function calcularRateio() {
    const inputPrincipal = document.querySelector('#modalNovoLancamento input[name="valor"]');
    if (!inputPrincipal) return;

    const totalReceita = parseBRFloat(inputPrincipal.value);

    let somaRateio = 0;
    document.querySelectorAll('.valor-rateio').forEach(input => {
        somaRateio += parseBRFloat(input.value);
    });

    const saldo = parseFloat((totalReceita - somaRateio).toFixed(2));

    const badgeSaldo = document.getElementById('badge-saldo');
    const btnSalvar = document.querySelector('#modalNovoLancamento button[type="submit"]');

    badgeSaldo.innerHTML = `Saldo: R$ ${saldo.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

    if (saldo < 0) {
        badgeSaldo.className = 'badge bg-danger';
        btnSalvar.disabled = true;
    } else if (saldo === 0 && totalReceita > 0) {
        badgeSaldo.className = 'badge bg-success';
        btnSalvar.disabled = false;
    } else {
        badgeSaldo.className = 'badge bg-secondary';
        btnSalvar.disabled = false;
    }
}

document.addEventListener('input', function(e) {
    if (e.target.name === 'valor' || e.target.classList.contains('valor-rateio')) {
        calcularRateio();
    }
});

function removerLinha(btn) {
    btn.closest('.linha-rateio').remove();
    calcularRateio();
}

document.querySelector('#modalNovoLancamento form')?.addEventListener('submit', function(e) {
    const valorTotal = parseBRFloat(document.querySelector('input[name="valor"]').value);
    let somaRateio = 0;

    document.querySelectorAll('.valor-rateio').forEach(input => {
        somaRateio += parseBRFloat(input.value);
    });

    if (parseFloat(somaRateio.toFixed(2)) > parseFloat(valorTotal.toFixed(2))) {
        e.preventDefault();
        document.getElementById('aviso-valor').classList.remove('d-none');
        alert('Atenção: A soma dos rateios (R$ ' + somaRateio.toLocaleString('pt-BR') + ') é maior que o valor total!');
    }
});

// Validação simples para o formulário individual (Garante que ao menos Dízimo ou Oferta foi preenchido)
document.querySelector('#modalLancamentoIndividual form')?.addEventListener('submit', function(e) {
    const dizimoValor = parseBRFloat(this.querySelector('input[name="dizimo_valor"]').value);
    const ofertaValor = parseBRFloat(this.querySelector('input[name="oferta_valor"]').value);

    if (dizimoValor <= 0 && ofertaValor <= 0) {
        e.preventDefault();
        alert('Por favor, informe ao menos o valor do Dízimo ou da Oferta.');
    }
});

// Mascara de moeda para campos com a classe .campo-moeda
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('campo-moeda') || e.target.name === 'valor' || e.target.classList.contains('valor-rateio')) {
        let v = e.target.value.replace(/\D/g, '');

        if (v === '') {
            e.target.value = '';
            calcularRateio();
            return;
        }

        v = (v / 100).toFixed(2).replace('.', ',');
        v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

        e.target.value = v;

        calcularRateio();
    }
});

function abrirGerenciadorAnexos(receita) {
    try {
        const container = document.getElementById('lista-uploads-anexos');
        const infoHeader = document.getElementById('info-receita-anexo');
        const template = document.getElementById('template-upload-membro');

        if (!container || !template) return;

        container.innerHTML = '';

        let dataFormatada = "";
        if(receita.financeiro_conta_data_pagamento) {
            const partes = receita.financeiro_conta_data_pagamento.split('-');
            dataFormatada = partes.length === 3 ? `${partes[2]}/${partes[1]}/${partes[0]}` : receita.financeiro_conta_data_pagamento;
        }

        const temMembros = (receita.membros && receita.membros.length > 0);
        const tipoTexto = temMembros ?
            '<span class="badge bg-primary">COM RATEIO</span>' :
            '<span class="badge bg-secondary">AVULSO</span>';

        infoHeader.innerHTML = `
            <div class="row align-items-center">
                <div class="col-8">
                    <div class="mb-1">${tipoTexto}</div>
                    <strong class="fs-6 d-block">${receita.financeiro_conta_descricao}</strong>
                    <small class="text-muted">Data: ${dataFormatada}</small>
                </div>
                <div class="col-4 text-end">
                    <small class="text-muted d-block">VALOR</small>
                    <strong class="text-success fs-5">R$ ${parseFloat(receita.financeiro_conta_valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</strong>
                </div>
            </div>
        `;

        if (temMembros) {
            receita.membros.forEach(m => {
                const clone = template.content.cloneNode(true);
                clone.querySelector('.up-conta-id').value = receita.financeiro_conta_id;
                clone.querySelector('.up-membro-id').value = m.receita_membro_id;
                clone.querySelector('.nome-membro-anexo').innerText = m.membro_nome;

                const valorMembro = parseFloat(m.receita_membro_valor || 0).toLocaleString('pt-BR', {minimumFractionDigits: 2});
                clone.querySelector('.valor-membro-anexo').innerText = `Dízimo/Oferta: R$ ${valorMembro}`;

                const preview = clone.querySelector('.preview-anexo-existente');
                if(m.receita_membro_comprovante) {
                    preview.classList.remove('d-none');
                    preview.innerHTML = `
                        <div class="d-flex align-items-center gap-2 mt-1 p-2 bg-success-subtle rounded border border-success-subtle">
                            <i class="bi bi-file-earmark-check-fill text-success fs-5"></i>
                            <div class="flex-grow-1">
                                <small class="d-block fw-bold text-success" style="font-size: 10px;">ARQUIVO DISPONÍVEL</small>
                                <a href="<?= url('public/assets/uploads/') ?>${m.receita_membro_comprovante}" target="_blank" class="btn btn-sm btn-success py-0 px-2 fw-bold" style="font-size: 11px;">
                                    <i class="bi bi-eye"></i> Abrir para Conferir
                                </a>
                            </div>
                        </div>`;
                }
                container.appendChild(clone);
            });
        } else {
            const clone = template.content.cloneNode(true);
            clone.querySelector('.up-conta-id').value = receita.financeiro_conta_id;
            clone.querySelector('.up-membro-id').value = '';
            clone.querySelector('.nome-membro-anexo').innerText = "Comprovante Geral";
            clone.querySelector('.valor-membro-anexo').innerText = "Esta receita não possui rateio entre membros.";

            const preview = clone.querySelector('.preview-anexo-existente');
            if(receita.financeiro_conta_comprovante) {
                preview.classList.remove('d-none');
                preview.innerHTML = `
                    <div class="d-flex align-items-center gap-2 mt-1 p-2 bg-primary-subtle rounded border border-primary-subtle">
                        <i class="bi bi-file-earmark-arrow-up-fill text-primary fs-5"></i>
                        <div class="flex-grow-1">
                            <small class="d-block fw-bold text-primary" style="font-size: 10px;">COMPROVANTE GERAL SALVO</small>
                            <a href="<?= url('public/assets/uploads/') ?>${receita.financeiro_conta_comprovante}" target="_blank" class="btn btn-sm btn-primary py-0 px-2 fw-bold" style="font-size: 11px;">
                                <i class="bi bi-eye"></i> Visualizar Arquivo
                            </a>
                        </div>
                    </div>`;
            }
            container.appendChild(clone);
        }

        const modalEl = document.getElementById('modalGerenciarAnexos');
        const modalInstance = new bootstrap.Modal(modalEl);
        modalInstance.show();

    } catch (err) {
        console.error("Erro na função abrirGerenciadorAnexos:", err);
    }
}

function anexarDocumento(contaId, tipo, membroId = null) {
    document.getElementById('anexo_conta_id').value = contaId;
    document.getElementById('anexo_tipo').value = tipo;

    const inputMembro = document.getElementById('anexo_receita_membro_id');
    if(inputMembro) {
        inputMembro.value = membroId || '';
    }

    document.getElementById('titulo_anexo').innerText = membroId
        ? "Recibo de Membro Individual"
        : "Comprovante Geral da Oferta";

    var modal = new bootstrap.Modal(document.getElementById('modalAnexo'));
    modal.show();
}

function executarUploadAsync(botao) {
    const form = botao.closest('form');
    const formData = new FormData(form);
    const btnText = botao.querySelector('.btn-text');
    const spinner = botao.querySelector('.spinner-border');
    const previewArea = form.querySelector('.preview-anexo-existente');
    const inputFileInput = form.querySelector('input[type="file"]');

    if (!inputFileInput || !inputFileInput.files.length) {
        alert('Selecione um arquivo para enviar.');
        return;
    }

    botao.disabled = true;
    if (spinner) spinner.classList.remove('d-none');

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Arquivo enviado com sucesso!');
            if (previewArea) previewArea.classList.remove('d-none');
        } else {
            alert(data.message || 'Erro ao enviar o arquivo.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro de comunicação com o servidor.');
    })
    .finally(() => {
        botao.disabled = false;
        if (spinner) spinner.classList.add('d-none');
    });
}




// Arquivo: dizimosofertas/index.php (Script ao final da página)

// 1. SUBMISSÃO AJAX - LANÇAMENTO EM LOTE
// Arquivo: App/Views/dizimooferta/index.php
// Linha: 689

document.getElementById('formLancamentoLote')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const btnSubmit = form.querySelector('button[type="submit"]');
    const valorTotal = parseBRFloat(form.querySelector('input[name="valor"]').value);

    let somaRateio = 0;
    form.querySelectorAll('.valor-rateio').forEach(input => {
        somaRateio += parseBRFloat(input.value);
    });

    if (parseFloat(somaRateio.toFixed(2)) > parseFloat(valorTotal.toFixed(2))) {
        document.getElementById('aviso-valor')?.classList.remove('d-none');
        alert('Atenção: A soma dos rateios é maior que o valor total!');
        return; // Interrompe a execução antes de chamar o fetch
    }

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Salvando...';

    const formData = new FormData(form);

    fetch('<?= url("dizimoOferta/salvar") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Erro ao processar requisição.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check-lg me-1"></i> Salvar Lançamento';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Ocorreu um erro no servidor.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="bi bi-check-lg me-1"></i> Salvar Lançamento';
    });
});

// 2. SUBMISSÃO AJAX - LANÇAMENTO INDIVIDUAL
// Arquivo: App/Views/dizimooferta/index.php
// Linha: 727

document.getElementById('formLancamentoIndividual')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const btnSubmit = form.querySelector('button[type="submit"]');
    const dizimoValor = parseBRFloat(form.querySelector('input[name="dizimo_valor"]').value);
    const ofertaValor = parseBRFloat(form.querySelector('input[name="oferta_valor"]').value);

    if (dizimoValor <= 0 && ofertaValor <= 0) {
        alert('Por favor, informe ao menos o valor do Dízimo ou da Oferta.');
        return; // Interrompe a execução antes de chamar o fetch
    }

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Salvando...';

    const formData = new FormData(form);

    fetch('<?= url("dizimoOferta/salvarIndividual") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Erro ao processar requisição.');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-check-lg me-1"></i> Salvar Lançamento';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Ocorreu um erro no servidor.');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="bi bi-check-lg me-1"></i> Salvar Lançamento';
    });
});



// Arquivo: App/Views/dizimooferta/index.php
// Linha: Adicione ao final do arquivo dentro da tag <script>

function excluirLancamento(id) {
    if (!confirm('Deseja realmente excluir este lançamento? Esta ação irá desfazer todas as movimentações e estornar o saldo!')) {
        return;
    }

    const formData = new FormData();
    formData.append('id', id);

    fetch('<?= url("dizimoOferta/excluirLancamento") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao processar a requisição.');
    });
}


// FORMA CORRETA: Apenas registra o ouvinte de evento sem forçar o modal.show() ao carregar a página
const modalElement = document.getElementById('modalRelatorioContabil');

if (modalElement) {
    modalElement.addEventListener('show.bs.modal', function () {
        const hoje = new Date().toISOString().split('T')[0];

        const inputInicio = document.getElementById('data_inicio');
        const inputFim = document.getElementById('data_fim');

        if (inputInicio) inputInicio.value = hoje;
        if (inputFim) inputFim.value = hoje;
    });
}

</script>
