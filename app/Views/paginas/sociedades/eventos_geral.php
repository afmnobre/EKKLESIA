<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Ação realizada com sucesso!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Ocorreu um erro ao processar a solicitação.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark"><i class="bi bi-calendar-week me-2 text-primary"></i>Calendário de Sociedades</h3>
        <button class="btn btn-primary shadow-sm" onclick="window.novoEvento()">
            <i class="bi bi-plus-lg me-2"></i>Agendar Novo Evento
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Data/Hora</th>
                        <th>Sociedade</th>
                        <th>Evento</th>
                        <th>Local</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($eventos)): foreach ($eventos as $ev): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></div>
                            <small class="text-muted"><?= date('H:i', strtotime($ev['sociedade_evento_data_hora_inicio'])) ?></small>
                        </td>
                        <td><span class="badge bg-secondary"><?= $ev['sociedade_nome'] ?></span></td>
                        <td><strong><?= $ev['sociedade_evento_titulo'] ?></strong></td>
                        <td><small><?= $ev['sociedade_evento_local'] ?></small></td>
                        <td><span class="badge rounded-pill bg-info text-white"><?= $ev['sociedade_evento_status'] ?></span></td>
						<td class="text-end pe-4">
							<button class="btn btn-sm btn-light border" onclick='window.editarEvento(<?= json_encode($ev) ?>)'>
								<i class="bi bi-pencil-square text-primary"></i>
							</button>

							<button class="btn btn-sm btn-light border ms-1" onclick="window.excluirEvento(<?= $ev['sociedade_evento_id'] ?>)">
								<i class="bi bi-trash text-danger"></i>
							</button>
						</td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center py-4">Nenhum evento agendado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formEvento" action="<?= url('SociedadesEventos/salvar') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="evento_id" id="ev_id">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEventoTitulo">Agendar Evento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small text-primary">Sociedade Responsável</label>
                        <select name="sociedade_id" id="ev_soc_id" class="form-select border-primary" required>
                            <option value="">Escolha uma sociedade...</option>
                            <?php foreach($sociedades as $s): ?>
                                <option value="<?= $s['sociedade_id'] ?>"><?= $s['sociedade_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Título do Evento</label>
                        <input type="text" name="titulo" id="ev_titulo" class="form-control" placeholder="Ex: Chá das Mulheres..." required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold small text-info">Sugestão de Local / Membro (Pesquise aqui)</label>
						<select id="select_local_preset" class="form-select" onchange="window.atualizarEnderecoEvento(this)" placeholder="Buscar nome...">
							<option value="">Buscar nome do membro ou endereço da igreja...</option>

							<option value="<?= $igreja['igreja_nome'] ?>" data-endereco="<?= $igreja['igreja_endereco'] ?>">
								📍 SEDE: <?= $igreja['igreja_nome'] ?>
							</option>

							<optgroup label="Residência de Membros">
								<?php foreach($membros as $m): ?>
									<option value="Residência de <?= $m['membro_nome'] ?>" data-endereco="<?= $m['membro_endereco'] ?>">
										🏠 <?= $m['membro_nome'] ?>
									</option>
								<?php endforeach; ?>
							</optgroup>
						</select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label fw-bold small">Data/Hora Início</label>
                        <input type="datetime-local" name="data_inicio" id="ev_inicio" class="form-control" required>
                    </div>
                    <div class="col-md-7 mb-3">
                        <label class="form-label fw-bold small">Local Confirmado / Endereço Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" name="local" id="ev_local" class="form-control border-info" placeholder="Selecione acima ou digite aqui..." required>
                        </div>
                    </div>
                </div>

				<div class="row">
					<div class="col-md-6 mb-3">
						<label class="form-label fw-bold small">Data/Hora Fim (Opcional)</label>
						<input type="datetime-local" name="data_fim" id="ev_fim" class="form-control">
					</div>
					<div class="col-md-6 mb-3">
						<label class="form-label fw-bold small">Valor/Investimento (R$)</label>
						<input type="number" name="valor" id="ev_valor" class="form-control" step="0.01" placeholder="0,00">
					</div>
				</div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Descrição/Observações</label>
                    <textarea name="descricao" id="ev_desc" class="form-control" rows="3" placeholder="Detalhes do evento..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary px-4">Salvar Evento</button>
            </div>
        </form>
    </div>
</div>

<script>
// Variável global para controlar a instância do Choices
let choicesLocal;

document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('select_local_preset');
    if (element) {
        choicesLocal = new Choices(element, {
            searchEnabled: true,
            itemSelectText: 'Selecionar',
            noResultsText: 'Membro não encontrado',
            placeholder: true,
            placeholderValue: 'Digite para buscar um membro ou local...',
            searchPlaceholderValue: 'Pesquisar pelo nome...',
            shouldSort: false // Mantém a Sede no topo conforme o PHP entregou
        });

        // Evento do Choices para atualizar o campo de endereço real
        element.addEventListener('change', function() {
            window.atualizarEnderecoEvento(element);
        });
    }
});

// Abre o modal para um novo registro
window.novoEvento = function() {
    const form = document.getElementById('formEvento');
    if(form) form.reset();

    // Resetar o Choices.js visualmente
    if(choicesLocal) {
        choicesLocal.setChoiceByValue('');
    }

    document.getElementById('ev_id').value = '';
    document.getElementById('modalEventoTitulo').innerText = 'Agendar Evento';
    document.getElementById('ev_local').value = '';

    const modal = new bootstrap.Modal(document.getElementById('modalEvento'));
    modal.show();
};

// Abre o modal preenchido para edição
window.editarEvento = function(dados) {
    document.getElementById('ev_id').value = dados.sociedade_evento_id;
    document.getElementById('ev_soc_id').value = dados.sociedade_evento_sociedade_id;
    document.getElementById('ev_titulo').value = dados.sociedade_evento_titulo;
    document.getElementById('ev_local').value = dados.sociedade_evento_local;
    document.getElementById('ev_desc').value = dados.sociedade_evento_descricao;

    // NOVO: Carregar Fim e Valor
    document.getElementById('ev_fim').value = dados.sociedade_evento_data_hora_fim ? dados.sociedade_evento_data_hora_fim.replace(" ", "T").substring(0, 16) : '';
    document.getElementById('ev_valor').value = dados.sociedade_evento_valor || '';

    if(dados.sociedade_evento_data_hora_inicio) {
        document.getElementById('ev_inicio').value = dados.sociedade_evento_data_hora_inicio.replace(" ", "T").substring(0, 16);
    }

    document.getElementById('modalEventoTitulo').innerText = 'Editar Evento';
    new bootstrap.Modal(document.getElementById('modalEvento')).show();
};

// Disparado ao selecionar um local no combo de sugestões
window.atualizarEnderecoEvento = function(select) {
    const selectedOption = select.options[select.selectedIndex];

    // Se não houver opção selecionada (ex: reset), não faz nada
    if (!selectedOption || select.value === "") return;

    const endereco = selectedOption.getAttribute('data-endereco');
    const nomeLocal = select.value;
    const campoLocal = document.getElementById('ev_local');

    if (endereco && endereco !== "null") {
        // Preenche o input de texto com "Nome - Endereço"
        campoLocal.value = `${nomeLocal} - ${endereco}`;
    } else {
        // Se não tiver endereço no data-attribute, usa só o nome (ex: "Sede")
        campoLocal.value = nomeLocal;
    }
};

window.excluirEvento = function(id) {
    if (confirm('Tem certeza?')) {
        // Ajuste para SociedadesEventos (com E maiúsculo)
        window.location.href = `<?= url('SociedadesEventos/excluir/') ?>${id}`;
    }
};
</script>
