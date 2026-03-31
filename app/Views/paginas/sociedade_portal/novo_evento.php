<?php
$editando = isset($evento) && !empty($evento);
$tituloCard = $editando ? 'Editar Evento' : 'Cadastrar Novo Evento';
$textoBotao = $editando ? 'Salvar Alterações' : 'Publicar Evento';

// Ajuste aqui para evitar erro de string se $evento['sociedade_evento_id'] não existir
$idEvento = $editando ? ($evento['sociedade_evento_id'] ?? '') : '';

$actionForm = $editando
    ? url('sociedadeLider/processarEditarEvento/'.$idEvento)
    : url('sociedadeLider/processarNovoEvento');

$this->rawview('sociedade_portal/header', ['titulo' => $tituloCard, 'sociedade' => $sociedade, 'ativo' => 'eventos']);
?>

<script>
// Capturamos os IDs reais da sua sessão para o JS
const ID_IGREJA_USUARIO = <?= $_SESSION['usuario_igreja_id'] ?? 0 ?>;
const ID_SOCIEDADE_ATIVA = <?= $_SESSION['sociedade_ativa_id'] ?? 0 ?>;

window.togglePresenca = function(membroId, status) {
    if (!eventoIdAtual || ID_IGREJA_USUARIO === 0) {
        alert("Erro de autenticação. Por favor, faça login novamente.");
        return;
    }

    // Criar ou capturar o iframe worker
    let ifrm = document.getElementById('iframe_worker');
    if (!ifrm) {
        ifrm = document.createElement('iframe');
        ifrm.id = 'iframe_worker';
        ifrm.name = 'iframe_worker';
        ifrm.style.display = 'none';
        document.body.appendChild(ifrm);
    }

    // Criar o formulário para envio via Iframe
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= url("sociedadeLider/registrarPresenca") ?>';
    form.target = 'iframe_worker';

    // Dados baseados na sua estrutura de banco e sessão
    const campos = {
        'evento_id': eventoIdAtual,
        'membro_id': membroId,
        'status': status,
        'igreja_id': ID_IGREJA_USUARIO,
        'sociedade_id': ID_SOCIEDADE_ATIVA
    };

    for (let key in campos) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = campos[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);

    // Feedback visual imediato (Cores do Bootstrap)
    const btnP = document.getElementById('btn-presenca-' + membroId);
    const btnF = document.getElementById('btn-falta-' + membroId);

    if (btnP && btnF) {
        if (status === 'Presente') {
            btnP.className = 'btn btn-success';
            btnF.className = 'btn btn-outline-danger';
        } else {
            btnP.className = 'btn btn-outline-success';
            btnF.className = 'btn btn-danger';
        }
    }
};
</script>

<style>
/* Força o container do Choices a ocupar toda a largura disponível */
.choices {
    width: 100% !important;
    margin-bottom: 0;
}

/* Ajusta o arredondamento para alinhar com o input-group-text do Bootstrap */
.choices__inner {
    border: none !important;
    background-color: transparent !important;
    min-height: 38px !important;
    display: flex !important;
    align-items: center !important;
    width: 100% !important;
    border-radius: 0 5px 5px 0 !important; /* Arredonda apenas o lado direito */
}

/* Garante que a lista de resultados (dropdown) também acompanhe a largura total */
.choices__list--dropdown {
    width: 100% !important;
    word-break: break-all;
}

/* Ajuste para o campo de busca dentro do dropdown ocupar tudo */
.choices__input {
    width: 100% !important;
    background-color: #f8f9fa !important;
    margin: 5px 0 !important;
}

/* Se estiver dentro de um input-group, precisamos ajustar o flex do Bootstrap */
.input-group > .choices {
    flex: 1 1 auto;
    width: 1% !important; /* Truque do Bootstrap para inputs em grupo */
    min-width: 0;
}
</style>

<div class="container pb-5 mt-4">
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card card-operacional p-4 bg-white border-top border-4 <?= $editando ? 'border-warning' : 'border-primary' ?> shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi <?= $editando ? 'bi-pencil-square' : 'bi-calendar-plus' ?> me-2 <?= $editando ? 'text-warning' : 'text-primary' ?>"></i><?= $tituloCard ?>
                    </h5>
                    <?php if($editando): ?>
                        <a href="<?= url('sociedadeLider/novoEvento') ?>" class="btn btn-sm btn-outline-secondary">Novo Cadastro</a>
                    <?php endif; ?>
                </div>

                <form action="<?= $actionForm ?>" method="POST">
                    <div class="mb-3">
                        <label class="secao-titulo mb-2 fw-bold small text-muted">TÍTULO DO EVENTO</label>
                        <input type="text" name="titulo" class="form-control form-control-lg border-light bg-light"
                               placeholder="Ex: Noite do Pastel, Vigília, Congresso..."
                               value="<?= $editando ? htmlspecialchars($evento['sociedade_evento_titulo']) : '' ?>" required>
                    </div>

					<div class="mb-3">
						<label class="secao-titulo mb-2 fw-bold small text-muted">LOCAL DO EVENTO</label>

						<div class="input-group shadow-sm">
							<span class="input-group-text bg-light border-light text-muted"><i class="bi bi-geo-alt"></i></span>
							<select name="local" id="selectLocal" class="form-select border-light bg-light" required>
								<?php if($editando): ?>
									<option value="<?= htmlspecialchars($evento['sociedade_evento_local']) ?>" selected>📍 Atual: <?= htmlspecialchars($evento['sociedade_evento_local']) ?></option>
								<?php else: ?>
									<option value="">Selecione ou digite o nome do membro...</option>
								<?php endif; ?>

								<optgroup label="Sedes">
									<?php if(!empty($igreja)): ?>
										<option value="Na Igreja: <?= htmlspecialchars($igreja) ?>">📍 Na Igreja (Sede)</option>
									<?php endif; ?>
								</optgroup>

								<optgroup label="Residência de Sócios">
									<?php if(!empty($membros)): ?>
										<?php foreach($membros as $m):
											$partes = array_filter([$m['membro_endereco_rua'], $m['membro_endereco_numero'], $m['membro_endereco_bairro'], $m['membro_endereco_cidade']]);
											$enderecoCompleto = implode(', ', $partes);
										?>
											<option value="Residência de <?= htmlspecialchars($m['membro_nome']) ?>: <?= htmlspecialchars($enderecoCompleto) ?>">
												🏠 Residência de <?= htmlspecialchars($m['membro_nome']) ?> - <?= htmlspecialchars($enderecoCompleto) ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</optgroup>

								<optgroup label="Diversos">
									<option value="OUTRO" <?= ($editando && $evento['sociedade_evento_local'] == 'OUTRO') ? 'selected' : '' ?>>✨ OUTRO (Informar na descrição abaixo)</option>
								</optgroup>
							</select>
						</div>
					</div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="secao-titulo mb-2 fw-bold small text-muted">INÍCIO</label>
                            <input type="datetime-local" name="data_inicio" class="form-control border-light bg-light"
                                   value="<?= $editando ? date('Y-m-d\TH:i', strtotime($evento['sociedade_evento_data_hora_inicio'])) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="secao-titulo mb-2 fw-bold small text-muted">TÉRMINO (OPCIONAL)</label>
                            <input type="datetime-local" name="data_fim" class="form-control border-light bg-light"
                                   value="<?= ($editando && $evento['sociedade_evento_data_hora_fim']) ? date('Y-m-d\TH:i', strtotime($evento['sociedade_evento_data_hora_fim'])) : '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="secao-titulo mb-2 fw-bold small text-muted">VALOR/INVESTIMENTO</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-muted">R$</span>
                                <input type="number" step="0.01" name="valor" class="form-control border-light bg-light"
                                       placeholder="0,00" value="<?= $editando ? $evento['sociedade_evento_valor'] : '' ?>">
                            </div>
                        </div>
                        <?php if($editando): ?>
                        <div class="col-md-6">
                            <label class="secao-titulo mb-2 fw-bold small text-muted">STATUS</label>
                            <select name="status" class="form-select border-light bg-light">
                                <?php $statusArr = ['Agendado', 'Confirmado', 'Cancelado', 'Concluído'];
                                foreach($statusArr as $st): ?>
                                    <option value="<?= $st ?>" <?= $evento['sociedade_evento_status'] == $st ? 'selected' : '' ?>><?= $st ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="secao-titulo mb-2 fw-bold small text-muted">DESCRIÇÃO / INFORMAÇÕES</label>
                        <textarea name="descricao" class="form-control border-light bg-light" rows="4"
                                  placeholder="Detalhes sobre o evento, o que levar..."><?= $editando ? htmlspecialchars($evento['sociedade_evento_descricao']) : '' ?></textarea>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= url('sociedadeLider/novoEvento') ?>" class="btn btn-light fw-bold px-4">Cancelar</a>
                        <button type="submit" class="btn <?= $editando ? 'btn-warning' : 'btn-primary' ?> fw-bold px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i><?= $textoBotao ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <div class="col-lg-5">
            <div class="card border-0 shadow-sm bg-white rounded-3">
                <div class="card-header bg-dark text-white fw-bold py-3">
                    <i class="bi bi-list-stars me-2"></i>Próximos Eventos da Sociedade
                </div>
                <div class="card-body p-0">
					<div class="list-group list-group-flush">
						<?php if(empty($eventos)): ?>
							<div class="p-4 text-center text-muted">
								<i class="bi bi-calendar-x d-block fs-2 mb-2"></i>
								<small>Nenhum evento agendado.</small>
							</div>
						<?php else: ?>
							<?php foreach($eventos as $ev): ?>
								<div class="list-group-item p-3">
									<div class="d-flex justify-content-between align-items-start">
										<div>
											<h6 class="fw-bold mb-1 text-primary text-uppercase" style="font-size: 0.85rem;">
												<?= htmlspecialchars($ev['sociedade_evento_titulo'] ?? '') ?>
											</h6>

											<div class="small mb-2 text-dark fw-medium">
												<i class="bi bi-calendar3 me-1 text-muted"></i>
												<?php
													$dataInicio = $ev['sociedade_evento_data_hora_inicio'] ?? null;
													echo ($dataInicio) ? date('d/m/Y H:i', strtotime($dataInicio)) : '--/--/----';
												?>
											</div>

											<div class="text-muted small">
												<i class="bi bi-geo-alt-fill text-danger me-1"></i>
												<?= htmlspecialchars($ev['sociedade_evento_local'] ?? 'Não informado') ?>
											</div>
										</div>

										<?php
											$valor = (float) ($ev['sociedade_evento_valor'] ?? 0);
											if($valor > 0):
										?>
											<span class="badge bg-success shadow-sm">R$ <?= number_format($valor, 2, ',', '.') ?></span>
										<?php else: ?>
											<span class="badge bg-light text-dark border">Grátis</span>
										<?php endif; ?>
									</div>

									<div class="mt-2 d-flex justify-content-between align-items-center">
										<span class="badge rounded-pill bg-info text-dark" style="font-size: 0.65rem;">
											<?= $ev['sociedade_evento_status'] ?? 'Agendado' ?>
                                        </span>

                                        <div class="btn-group">
											<button class="btn btn-sm btn-outline-primary border-0 p-1"
													onclick="abrirModalPresenca(<?= $ev['sociedade_evento_id'] ?>, '<?= htmlspecialchars($ev['sociedade_evento_titulo']) ?>')">
												<i class="bi bi-person check-fs-5"></i>
											</button>

											<a href="<?= url('sociedadeLider/editarEvento/'.$ev['sociedade_evento_id']) ?>"
											   class="btn btn-sm btn-outline-secondary border-0 p-1">
												<i class="bi bi-pencil"></i>
											</a>

											<button type="button"
													onclick="prepararExclusao(<?= $ev['sociedade_evento_id'] ?>, '<?= htmlspecialchars($ev['sociedade_evento_titulo']) ?>')"
													class="btn btn-sm btn-outline-danger border-0 p-1">
												<i class="bi bi-trash"></i>
											</button>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-octagon text-danger" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold">Excluir Evento?</h5>
                <p id="msgConfirmarExclusao" class="text-muted small mb-4"></p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal">Não</button>
                    <a href="#" id="btnConfirmarDeletar" class="btn btn-danger w-100 fw-bold">Sim, Excluir</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPresenca" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="tituloEventoPresenca">Lista de Presença</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="listaMembrosPresenca" class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    <div class="p-4 text-center text-muted small">Carregando membros...</div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
// Variável para controle do Modal
let modalExclusao;

/**
 * Prepara o modal com as informações do evento
 */
function prepararExclusao(id, titulo) {
    const msg = document.getElementById('msgConfirmarExclusao');
    const btnDeletar = document.getElementById('btnConfirmarDeletar');

    msg.innerText = `Tem certeza que deseja excluir o evento "${titulo}"?`;

    // Define o link real de exclusão no botão "Sim" do modal
    btnDeletar.href = "<?= url('sociedadeLider/deletarEvento/') ?>" + id;

    // Abre o modal
    if(!modalExclusao) {
        modalExclusao = new bootstrap.Modal(document.getElementById('modalConfirmarExclusao'));
    }
    modalExclusao.show();
}

/**
 * Filtro de Locais (Mantendo sua lógica existente)
 */
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('selectLocal');

    if (element) {
        const choices = new Choices(element, {
            searchEnabled: true,          // Habilita a caixa de texto de busca
            searchChoices: true,          // Filtra conforme digita
            searchFloor: 1,               // Começa a filtrar com 1 caractere
            searchResultLimit: 10,        // Limite de resultados visíveis
            itemSelectText: '',           // Remove o texto "Press Enter to select"
            noResultsText: 'Nenhum local encontrado',
            shouldSort: false,            // Mantém a ordem dos seus optgroups
            placeholder: true,
            placeholderValue: 'Selecione ou digite um nome...',
            searchPlaceholderValue: 'Digite o nome ou endereço...', // Texto dentro da busca
        });

        // Corrigindo o bug do "OUTRO" com Choices.js
        element.addEventListener('change', function(event) {
            if (event.detail.value === 'OUTRO') {
                const descricao = document.querySelector('textarea[name="descricao"]');
                if(descricao) {
                    descricao.focus();
                    descricao.placeholder = "Informe o endereço completo aqui...";
                    descricao.classList.add('border-warning');
                }
            }
        });
    }
});

// IMPORTANTE: Remova a função filtrarLocais() do seu script antigo
// para evitar erros de referência, já que o input "filtroLocal" não existe mais.

// Listener para o campo "OUTRO"
document.getElementById('selectLocal').addEventListener('change', function() {
    if (this.value === 'OUTRO') {
        const descricao = document.querySelector('textarea[name="descricao"]');
        descricao.focus();
        descricao.placeholder = "Por favor, informe o endereço completo e detalhes do local aqui...";
        descricao.classList.add('border-warning');
    }
});

// PRESENÇA MODAL

// Definimos as variáveis no escopo global (fora de qualquer função)
let eventoIdAtual = null;
let modalPresencaInstance = null;

/**
 * Abre o modal e carrega a lista
 */
function abrirModalPresenca(id, titulo) {
    eventoIdAtual = id;

    const elTitulo = document.getElementById('tituloEventoPresenca');
    const elLista = document.getElementById('listaMembrosPresenca');
    const elModal = document.getElementById('modalPresenca');

    if (!elTitulo || !elLista || !elModal) return;

    elTitulo.innerText = titulo;
    elLista.innerHTML = '<div class="p-4 text-center"><div class="spinner-border text-primary" role="status"></div></div>';

    if(!modalPresencaInstance) {
        modalPresencaInstance = new bootstrap.Modal(elModal);
    }
    modalPresencaInstance.show();

    let fd = new FormData();
    fd.append('evento_id', id);

    fetch('<?= url("sociedadeLider/carregarListaPresenca") ?>', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(membros => {
        if (!membros || membros.length === 0) {
            elLista.innerHTML = '<div class="p-4 text-center text-muted small">Nenhum membro na sociedade.</div>';
            return;
        }

        elLista.innerHTML = membros.map(m => `
            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                <div class="d-flex flex-column">
                    <span class="fw-bold small text-dark">${m.membro_nome}</span>
                    <small class="text-muted" style="font-size: 0.7rem;">${m.sociedade_membro_funcao || 'Sócio'}</small>
                </div>
                <div class="btn-group btn-group-sm">
                    <button id="btn-presenca-${m.membro_id}"
                            onclick="togglePresenca(${m.membro_id}, 'Presente')"
                            class="btn ${m.sociedade_presenca_status === 'Presente' ? 'btn-success' : 'btn-outline-success'}">
                        P
                    </button>
                    <button id="btn-falta-${m.membro_id}"
                            onclick="togglePresenca(${m.membro_id}, 'Faltou')"
                            class="btn ${m.sociedade_presenca_status === 'Faltou' ? 'btn-danger' : 'btn-outline-danger'}">
                        F
                    </button>
                </div>
            </div>
        `).join('');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('selectLocal');

    if (element) {
        const choices = new Choices(element, {
            searchEnabled: true,          // Habilita a busca
            itemSelectText: 'Clique para selecionar',
            noResultsText: 'Nenhum local encontrado',
            noChoicesText: 'Sem opções disponíveis',
            placeholder: true,
            placeholderValue: 'Selecione o local...',
            shouldSort: false,            // Mantém a ordem dos optgroups que você definiu
            searchPlaceholderValue: 'Digite o nome do sócio ou endereço...',
            classNames: {
                containerInner: 'choices__inner border-light bg-light', // Mantém seu estilo
            }
        });
    }
});


</script>

<?php $this->rawview('sociedade_portal/footer'); ?>
