<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="<?= url('igrejaseventos/index') ?>" class="btn btn-link text-decoration-none text-muted p-0">
            <i class="bi bi-arrow-left"></i> Voltar para listagem
        </a>
        <h3 class="text-secondary mt-2">⛪ Cadastrar Novo Evento</h3>
        <p class="text-muted small">Agende congressos, festividades e convocações oficiais da igreja.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?= url('igrejaEvento/salvar') ?>" method="POST">

                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Título do Evento</label>
                            <input type="text" name="evento_titulo" class="form-control" placeholder="Ex: Aniversário da Igreja" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Data e Hora de Início</label>
                                <input type="datetime-local" name="evento_data_hora_inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Previsão de Término (Opcional)</label>
                                <input type="datetime-local" name="evento_data_hora_fim" class="form-control">
                            </div>
                        </div>

						<div class="mb-3">
							<label class="small fw-bold text-muted text-uppercase">Sugestão de Local / Membro (Pesquise aqui)</label>
							<select id="select_local_preset" class="form-select">
								<option value="">Buscar nome do membro ou endereço da igreja...</option>

								<?php if (!empty($igreja) && is_array($igreja)): ?>
									<option value="<?= htmlspecialchars($igreja['igreja_nome'] ?? 'SEDE') ?>"
											data-endereco="<?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>">
										📍 SEDE: <?= htmlspecialchars($igreja['igreja_nome'] ?? 'Igreja') ?>
									</option>
								<?php endif; ?>

								<optgroup label="Residência de Membros">
									<?php foreach($membros as $m):
										// Montagem do endereço completo baseada na sua tabela membros_enderecos
										$partes = [];
										if (!empty($m['membro_endereco_rua'])) $partes[] = $m['membro_endereco_rua'];
										if (!empty($m['membro_endereco_numero'])) $partes[] = $m['membro_endereco_numero'];
										if (!empty($m['membro_endereco_complemento'])) $partes[] = "(" . $m['membro_endereco_complemento'] . ")";
										if (!empty($m['membro_endereco_bairro'])) $partes[] = $m['membro_endereco_bairro'];
										if (!empty($m['membro_endereco_cidade'])) $partes[] = $m['membro_endereco_cidade'];
										if (!empty($m['membro_endereco_estado'])) $partes[] = $m['membro_endereco_estado'];

										$enderecoCompleto = !empty($partes) ? implode(', ', $partes) : 'Endereço não cadastrado';
									?>
										<option value="Residência de <?= htmlspecialchars($m['membro_nome'] ?? '') ?>"
												data-endereco="<?= htmlspecialchars($enderecoCompleto) ?>">
											🏠 <?= htmlspecialchars($m['membro_nome'] ?? '') ?>
										</option>
									<?php endforeach; ?>
								</optgroup>
							</select>
						</div>

						<div class="mb-3">
							<label class="small fw-bold text-muted text-uppercase">Local / Endereço Final</label>
							<input type="text" name="evento_local" id="ev_local" class="form-control"
								   value="<?= isset($evento) ? htmlspecialchars($evento['evento_local']) : '' ?>"
								   placeholder="O endereço aparecerá aqui após selecionar acima ou digite manualmente">
						</div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Cor no Calendário</label>
                                <div class="d-flex align-items-center">
                                    <input type="color" name="evento_cor" class="form-control form-control-color me-3" value="#0B1C2D" title="Escolha a cor do evento">
                                    <small class="text-muted">Isso ajuda na identificação visual</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Status Inicial</label>
                                <select name="evento_status" class="form-select">
                                    <option value="Agendado">Agendado</option>
                                    <option value="Confirmado" selected>Confirmado</option>
                                    <option value="Cancelado">Cancelado</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase">Descrição / Observações</label>
                            <textarea name="evento_descricao" class="form-control" rows="3" placeholder="Detalhes importantes para os membros..."></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                            <a href="<?= url('igrejaEvento/index') ?>" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="bi bi-check-lg"></i> Salvar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o Choices.js no select de sugestão
    const element = document.getElementById('select_local_preset');
    const choices = new Choices(element, {
        searchPlaceholderValue: "Digite o nome para filtrar...",
        noResultsText: 'Nenhum local encontrado',
        itemSelectText: 'Pressione para selecionar',
        allowHTML: true,
    });

    // Função para atualizar o endereço final (ajustada para funcionar com Choices)
    element.addEventListener('change', function(event) {
        // No Choices.js, o evento original está em event.detail
        const select = element;
        const selectedOption = select.options[select.selectedIndex];

        if (!selectedOption || select.value === "") return;

        const endereco = selectedOption.getAttribute('data-endereco');
        const nomeLocal = select.value;
        const campoLocal = document.getElementById('ev_local');

        if (endereco && endereco !== "null") {
            campoLocal.value = `${nomeLocal} - ${endereco}`;
        } else {
            campoLocal.value = nomeLocal;
        }
    });
});
</script>
