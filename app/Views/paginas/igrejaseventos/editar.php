<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="<?= url('igrejaEvento/index') ?>" class="btn btn-link text-decoration-none text-muted p-0">
            <i class="bi bi-arrow-left"></i> Voltar para listagem
        </a>
        <h3 class="text-secondary mt-2">📝 Editar Evento</h3>
        <p class="text-muted small">Atualize as informações ou altere o status do evento.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="<?= url('igrejaEvento/atualizar/' . $evento['evento_id']) ?>" method="POST">

                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Título do Evento</label>
                            <input type="text" name="evento_titulo" class="form-control" value="<?= htmlspecialchars($evento['evento_titulo']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Data e Hora de Início</label>
                                <input type="datetime-local" name="evento_data_hora_inicio" class="form-control"
                                       value="<?= date('Y-m-d\TH:i', strtotime($evento['evento_data_hora_inicio'])) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Previsão de Término (Opcional)</label>
                                <input type="datetime-local" name="evento_data_hora_fim" class="form-control"
                                       value="<?= !empty($evento['evento_data_hora_fim']) ? date('Y-m-d\TH:i', strtotime($evento['evento_data_hora_fim'])) : '' ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Sugestão de Local / Membro (Pesquise aqui)</label>
                            <select id="select_local_preset" class="form-select">
                                <option value="">Buscar nome do membro ou endereço da igreja...</option>

                                <?php if (!empty($igreja) && is_array($igreja)):
                                    $sedeOptionValue = $igreja['igreja_nome'];
                                    $sedeFullAddress = $igreja['igreja_nome'] . " - " . $igreja['igreja_endereco'];
                                    $isSedeSelected = ($evento['evento_local'] == $sedeFullAddress || $evento['evento_local'] == $sedeOptionValue);
                                ?>
                                    <option value="<?= htmlspecialchars($sedeOptionValue) ?>"
                                            data-endereco="<?= htmlspecialchars($igreja['igreja_endereco'] ?? '') ?>"
                                            <?= $isSedeSelected ? 'selected' : '' ?>>
                                        📍 SEDE: <?= htmlspecialchars($igreja['igreja_nome']) ?>
                                    </option>
                                <?php endif; ?>

                                <optgroup label="Residência de Membros">
                                    <?php foreach($membros as $m):
                                        $partes = [];
                                        if (!empty($m['membro_endereco_rua'])) $partes[] = $m['membro_endereco_rua'];
                                        if (!empty($m['membro_endereco_numero'])) $partes[] = $m['membro_endereco_numero'];
                                        if (!empty($m['membro_endereco_complemento'])) $partes[] = "(" . $m['membro_endereco_complemento'] . ")";
                                        if (!empty($m['membro_endereco_bairro'])) $partes[] = $m['membro_endereco_bairro'];
                                        if (!empty($m['membro_endereco_cidade'])) $partes[] = $m['membro_endereco_cidade'];
                                        if (!empty($m['membro_endereco_estado'])) $partes[] = $m['membro_endereco_estado'];

                                        $endComp = implode(', ', $partes);
                                        $membroOptionValue = "Residência de " . $m['membro_nome'];
                                        $membroFullString = $membroOptionValue . " - " . $endComp;

                                        // Verifica se o que está no banco bate com este membro
                                        $isSelected = ($evento['evento_local'] == $membroFullString || $evento['evento_local'] == $membroOptionValue);
                                    ?>
                                        <option value="<?= htmlspecialchars($membroOptionValue) ?>"
                                                data-endereco="<?= htmlspecialchars($endComp) ?>"
                                                <?= $isSelected ? 'selected' : '' ?>>
                                            🏠 <?= htmlspecialchars($m['membro_nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted text-uppercase">Local / Endereço Final</label>
                            <input type="text" name="evento_local" id="ev_local" class="form-control" value="<?= htmlspecialchars($evento['evento_local']) ?>" placeholder="Selecione acima ou digite o local">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Cor no Calendário</label>
                                <div class="d-flex align-items-center">
                                    <input type="color" name="evento_cor" class="form-control form-control-color me-3" value="<?= $evento['evento_cor'] ?>">
                                    <small class="text-muted">Cor atual aplicada na grade</small>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted text-uppercase">Status do Evento</label>
                                <select name="evento_status" class="form-select">
                                    <?php
                                    $statusArr = ['Agendado', 'Confirmado', 'Cancelado', 'Concluído'];
                                    foreach($statusArr as $status): ?>
                                        <option value="<?= $status ?>" <?= ($evento['evento_status'] == $status) ? 'selected' : '' ?>><?= $status ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase">Descrição / Observações</label>
                            <textarea name="evento_descricao" class="form-control" rows="3"><?= htmlspecialchars($evento['evento_descricao']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                            <a href="<?= url('igrejaEvento/index') ?>" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="bi bi-save"></i> Atualizar Alterações
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
    const element = document.getElementById('select_local_preset');
    const choices = new Choices(element, {
        searchPlaceholderValue: "Digite o nome para filtrar...",
        noResultsText: 'Nenhum local encontrado',
        itemSelectText: 'Pressione para selecionar',
        allowHTML: true,
    });

    element.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (!selectedOption || this.value === "") return;

        const endereco = selectedOption.getAttribute('data-endereco');
        const nomeLocal = this.value;
        const campoLocal = document.getElementById('ev_local');

        if (endereco && endereco !== "" && endereco !== "Endereço não informado") {
            campoLocal.value = `${nomeLocal} - ${endereco}`;
        } else {
            campoLocal.value = nomeLocal;
        }
    });
});
</script>
