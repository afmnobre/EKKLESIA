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
</style>

<div class="header-institucional shadow-sm">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-2 text-start">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB" class="logo-header">
            </div>
            <div class="col-md-8 text-center">
                <h3 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px; color: #212529;">
                    <?= htmlspecialchars($igreja['igreja_nome']) ?>
                </h3>
                <p class="small text-muted mb-0"><?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?></p>
            </div>
            <div class="col-md-2 text-end">
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
            <div class="col-md-9">
                <label class="small fw-bold text-muted text-uppercase mb-1 d-block text-center text-md-start ps-3">Mês de Referência</label>
                <div class="nav nav-pills nav-fill bg-light p-1 rounded-pill mx-md-2">
                    <?php
                    $meses = [1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'];
                    foreach($meses as $num => $nome): ?>
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

<div class="container-fluid py-2 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-shield-check me-2 text-success"></i>Conferência de Dízimos</h3>

            <div class="d-flex gap-3 mt-2">
                <?php
                $getFoto = function($diacono, $igrejaId) {
                    // Seguindo sua estrutura: assets/uploads/{id}/membros/{registro}/{foto}
                    if (!empty($diacono['foto'])) {
                        return url("assets/uploads/{$igrejaId}/membros/{$diacono['registro']}/{$diacono['foto']}");
                    }
                    return url("assets/img/default-avatar.png");
                };
                ?>

                <div class="diacono-item shadow-sm">
                    <img src="<?= $getFoto($_SESSION['conf_diacono_1'], $igreja['igreja_id']) ?>" class="avatar-diacono">
                    <div>
                        <small class="text-muted d-block" style="font-size: 9px; line-height: 1;">CONFERENTE 1</small>
                        <span class="fw-bold small"><?= $_SESSION['conf_diacono_1']['nome'] ?></span>
                    </div>
                </div>

                <div class="diacono-item shadow-sm">
                    <img src="<?= $getFoto($_SESSION['conf_diacono_2'], $igreja['igreja_id']) ?>" class="avatar-diacono">
                    <div>
                        <small class="text-muted d-block" style="font-size: 9px; line-height: 1;">CONFERENTE 2</small>
                        <span class="fw-bold small"><?= $_SESSION['conf_diacono_2']['nome'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRelatorioConferencia">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <button class="btn btn-dark shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNovoLancamento">
                <i class="bi bi-plus-lg"></i> Novo Lançamento
            </button>
            <a href="<?= url('dizimoOferta/sair') ?>" class="btn btn-outline-danger shadow-sm">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>
    </div>

	<div class="card border-0 shadow-sm">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead class="bg-light">
						<tr>
							<th class="ps-4" style="width: 100px;">Data</th>
							<th>Descrição / Categoria</th>
							<th>Valor</th>
							<th class="text-center">Documentação</th>
							<th class="text-center">Status</th>
							<th class="text-end pe-4">Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php if(empty($lancamentos)): ?>
							<tr>
								<td colspan="6" class="text-center py-5 text-muted">
									<i class="bi bi-inbox fs-1 d-block mb-2"></i>
									Nenhum lançamento conferido por esta dupla neste mês.
								</td>
							</tr>
						<?php endif; ?>

						<?php foreach($lancamentos as $l):
							$temRateio = !empty($l['membros']);
							$temComprovante = !empty($l['financeiro_conta_comprovante']);
							$temNota = !empty($l['financeiro_conta_nota_fiscal']);
						?>
						<tr>
							<td class="ps-4">
								<span class="text-secondary small d-block" style="font-size: 0.65rem;">PAGO EM</span>
								<span class="fw-bold"><?= date('d/m', strtotime($l['financeiro_conta_data_pagamento'])) ?></span>
								<span class="text-muted small"><?= date('/Y', strtotime($l['financeiro_conta_data_pagamento'])) ?></span>
							</td>

							<td>
								<span class="d-block fw-bold text-dark mb-1"><?= $l['financeiro_conta_descricao'] ?></span>
								<span class="badge rounded-pill bg-light text-secondary border" style="font-size: 0.7rem;">
									<?= $l['financeiro_categoria_nome'] ?>
								</span>
							</td>

							<td class="fw-bold text-primary">
								R$ <?= number_format($l['financeiro_conta_valor'], 2, ',', '.') ?>
							</td>

							<td class="text-center">
								<div class="d-flex justify-content-center gap-3">
									<i class="bi bi-receipt <?= $temNota ? 'text-success' : 'text-light' ?> fs-5" title="Nota Fiscal"></i>
									<i class="bi <?= $temRateio ? 'bi-people-fill' : 'bi-file-earmark-check' ?> <?= ($temComprovante || $temRateio) ? 'text-success' : 'text-light' ?> fs-5" title="Comprovante/Rateio"></i>
								</div>
							</td>

							<td class="text-center">
								<?php if(!empty($l['conferido_por_1']) && !empty($l['conferido_por_2'])): ?>
									<span class="badge bg-success-subtle text-success px-3 rounded-pill" style="font-size: 0.7rem;">
										<i class="bi bi-check-all"></i> Conferido
									</span>
								<?php else: ?>
									<span class="badge bg-warning-subtle text-warning px-3 rounded-pill" style="font-size: 0.7rem;">
										<i class="bi bi-clock"></i> Pendente
									</span>
								<?php endif; ?>
							</td>

							<td class="text-end pe-4">
								<div class="d-flex align-items-center justify-content-end gap-2">
									<button class="btn btn-sm <?= $temRateio ? 'btn-primary' : 'btn-outline-primary' ?> shadow-sm border-0 position-relative"
											onclick='abrirGerenciadorAnexos(<?= json_encode($l) ?>)'
											title="<?= $temRateio ? 'Gerenciar Rateio' : 'Anexar Comprovante' ?>">

										<i class="bi <?= $temRateio ? 'bi-people-fill' : 'bi-file-earmark-arrow-up' ?> fs-6"></i>

										<?php if($temRateio): ?>
											<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.55rem;">
												<?= count($l['membros']) ?>
											</span>
										<?php endif; ?>
									</button>

									<span class="text-muted" style="font-size: 10px;">
										<i class="bi bi-fingerprint"></i> <?= $l['financeiro_conta_id'] ?>
									</span>
								</div>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
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
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Nova Receita</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= url('dizimoOferta/salvar') ?>" method="POST">
                <div class="modal-body p-4">

					<div class="mb-3">
						<label class="small fw-bold text-muted text-uppercase d-block mb-1">Classificação da Receita</label>
						<select name="categoria_sub_id" class="form-select choices-select" required>
							<option value="">Selecione a subcategoria...</option>
							<?php
							$lastCat = '';
							foreach($categorias as $cat):
								// Criar separadores visuais (Optgroup) para organizar por Categoria Pai
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
							<input type="text" name="valor" class="form-control bg-light border-0 fw-bold" placeholder="0,00" required>
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
						<div class="row g-2 mb-2 align-items-center linha-rateio">
							<div class="col-7">
								<select name="rateio_membro[]" class="form-select select-membro-rateio">
									<option value="">Pesquisar membro...</option>
									<?php foreach($membros as $m): ?>
										<option value="<?= $m['membro_id'] ?>"><?= htmlspecialchars($m['membro_nome']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-4">
								<div class="input-group input-group-sm">
									<span class="input-group-text">R$</span>
									<input type="text" name="rateio_valor[]" class="form-control valor-rateio" placeholder="0,00">
								</div>
							</div>
							<div class="col-1 text-end">
								<button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removerLinha(this)">
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
                    <button type="button" class="btn btn-light fw-bold" data-bs-toggle="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold shadow">
                        <i class="bi bi-check-lg me-1"></i> Salvar Lançamento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.choices-select');
        elements.forEach(el => {
            new Choices(el, {
                searchEnabled: true,
                itemSelectText: 'Selecionar',
                noResultsText: 'Nenhuma categoria encontrada',
            });
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
    // Se for string, limpa formatação brasileira (1.235,44 -> 1235.44)
    if (typeof valor === 'string') {
        let limpo = valor.replace(/\./g, '').replace(',', '.');
        return parseFloat(limpo) || 0;
    }
    return parseFloat(valor) || 0;
}

function calcularRateio() {
    // 1. Pega o valor total da receita usando o parser brasileiro
    const inputPrincipal = document.querySelector('input[name="valor"]');
    const totalReceita = parseBRFloat(inputPrincipal.value);

    // 2. Soma todos os inputs de rateio usando o parser brasileiro
    let somaRateio = 0;
    document.querySelectorAll('.valor-rateio').forEach(input => {
        somaRateio += parseBRFloat(input.value);
    });

    // 3. Calcula o saldo com precisão de 2 casas decimais para evitar erros de float do JS
    const saldo = parseFloat((totalReceita - somaRateio).toFixed(2));

    const badgeSaldo = document.getElementById('badge-saldo');
    const btnSalvar = document.querySelector('button[type="submit"]');

    // 4. Atualiza o visual do Badge formatado para Real
    badgeSaldo.innerHTML = `Saldo: R$ ${saldo.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

    // Lógica de cores e bloqueio do botão
    if (saldo < 0) {
        // Se negativo, erro (estourou o valor principal)
        badgeSaldo.className = 'badge bg-danger';
        btnSalvar.disabled = true;
    } else if (saldo === 0 && totalReceita > 0) {
        // Se zerado e valor preenchido, sucesso
        badgeSaldo.className = 'badge bg-success';
        btnSalvar.disabled = false;
    } else {
        // Se ainda tem saldo sobrando
        badgeSaldo.className = 'badge bg-secondary';
        btnSalvar.disabled = false;
    }
}

// Ouvintes de evento para disparar o cálculo
document.addEventListener('input', function(e) {
    // Se mudar o valor principal ou qualquer valor de rateio, recalcula
    if (e.target.name === 'valor' || e.target.classList.contains('valor-rateio')) {
        calcularRateio();
    }
});

// Ajuste na função de remover linha para recalcular após excluir
function removerLinha(btn) {
    btn.closest('.linha-rateio').remove();
    calcularRateio();
}

// Opcional: Validação básica se a soma do rateio bate com o total
document.querySelector('form').addEventListener('submit', function(e) {
    // Agora usamos parseBRFloat para ler corretamente o valor com vírgula
    const valorTotal = parseBRFloat(document.querySelector('input[name="valor"]').value);
    let somaRateio = 0;

    document.querySelectorAll('.valor-rateio').forEach(input => {
        somaRateio += parseBRFloat(input.value);
    });

    // Usamos toFixed para evitar dízimas infinitas do JS na comparação
    if (parseFloat(somaRateio.toFixed(2)) > parseFloat(valorTotal.toFixed(2))) {
        e.preventDefault();
        document.getElementById('aviso-valor').classList.remove('d-none');
        alert('Atenção: A soma dos rateios (R$ ' + somaRateio.toLocaleString('pt-BR') + ') é maior que o valor total!');
    }
});

// Adicione isso ao seu script para formatar enquanto digita
document.addEventListener('input', function(e) {
    if (e.target.name === 'valor' || e.target.classList.contains('valor-rateio')) {
        // 1. Pega apenas os números
        let v = e.target.value.replace(/\D/g, '');

        // 2. Se estiver vazio, não faz nada
        if (v === '') {
            calcularRateio();
            return;
        }

        // 3. Formata como moeda (centavos, vírgula e milhar)
        v = (v / 100).toFixed(2).replace('.', ',');
        v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

        // 4. Atribui o valor formatado de volta ao campo
        e.target.value = v;

        // 5. Atualiza o saldo do rateio
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

        // Formatação de data
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

/**
 * @param contaId ID da conta principal
 * @param tipo 'comprovante' ou 'nota_fiscal'
 * @param membroId ID do rateio (opcional)
 */
function anexarDocumento(contaId, tipo, membroId = null) {
    document.getElementById('anexo_conta_id').value = contaId;
    document.getElementById('anexo_tipo').value = tipo;

    // Campo que criamos no Modal para o ID do Membro
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

    // Feedback visual de carregamento
    botao.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Sucesso! Atualiza o visual da linha sem fechar o modal
            previewArea.classList.remove('d-none');
            previewArea.innerHTML = `
                <div class="d-flex align-items-center gap-2 mt-1 p-2 bg-success-subtle rounded border border-success-subtle animate__animated animate__fadeIn">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div class="flex-grow-1">
                        <small class="d-block fw-bold text-success" style="font-size: 10px;">ENVIADO COM SUCESSO!</small>
                        <span class="text-muted" style="font-size: 11px;">O arquivo foi salvo.</span>
                    </div>
                </div>`;

            // Limpa o campo de arquivo para um novo upload se necessário
            form.querySelector('input[type="file"]').value = '';

            // Opcional: Você pode adicionar um link para ver o arquivo se o seu PHP retornar o caminho
            // Mas para dízimos, o feedback visual de "Enviado" geralmente já basta para o fluxo.
        } else {
            alert("Erro ao enviar o arquivo. Verifique o tamanho ou formato.");
        }
    })
    .catch(error => {
        console.error("Erro:", error);
        alert("Erro de conexão ao tentar subir o arquivo.");
    })
    .finally(() => {
        // Restaura o botão
        botao.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    });
}

document.getElementById('modalGerenciarAnexos').addEventListener('hidden.bs.modal', function () {
    // Recarrega a página para que o PHP busque os dados atualizados do banco
    location.reload();
});

</script>
