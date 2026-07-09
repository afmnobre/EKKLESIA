<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item['rateio_titulo']) ?></title>
    <!-- CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- JS do Bootstrap (Essencial para os Modais funcionarem) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <style>
        body { background-color: #eef1f5; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .whatsapp-style-list .list-group-item { border-left: none; border-right: none; border-radius: 0; padding: 12px 16px; }
        .linha-preenchida { background-color: #f8f9fa; color: #6c757d; text-decoration: line-through; }
        .linha-disponivel { cursor: pointer; transition: background 0.2s; }
        .linha-disponivel:hover { background-color: #e8f4fd; }
    </style>
</head>
<body>
<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow border-0 rounded-3">
        <div class="card-body p-4">

            <h4 class="fw-bold text-dark text-center mb-2"><?= htmlspecialchars($item['rateio_titulo']) ?></h4>

<div class="text-dark bg-light p-3 rounded border mb-3" style="white-space: pre-wrap; font-size: 0.95rem;"><?= htmlspecialchars($item['rateio_descricao']) ?>

<strong>Entregar até dia: <?= date('d/m/Y', strtotime($item['rateio_data_limite'])) ?></strong>
            </div>

            <?php
            // Cálculos matemáticos dos indicadores baseados no array de linhas
            $totalCotas = count($linhas);
            $escolhidas = 0;
            foreach ($linhas as $l) {
                if ($l['linha_status'] === 'Preenchido') {
                    $escolhidas++;
                }
            }
            $faltam = $totalCotas - $escolhidas;
            $porcentagem = $totalCotas > 0 ? round(($escolhidas / $totalCotas) * 100) : 0;
            ?>

            <div class="card bg-white border shadow-sm mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-dark mb-3 small text-uppercase"><i class="bi bi-bar-chart-line me-1 text-primary"></i> Progresso da Lista</h6>

                <div class="d-grid mb-3">
                    <a href="<?= full_url("sociedadeLider/rateioConferencia/" . $item['rateio_token']) ?>" class="btn btn-dark shadow-sm fw-bold rounded-pill">
                        <i class="bi bi-printer-fill me-2"></i> Relatório de Conferência / Impressão
                    </a>
                </div>

                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded border">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Total de Itens</small>
                                <span class="fw-bold text-dark fs-5"><?= $totalCotas ?></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded border">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Escolhidos</small>
                                <span class="fw-bold text-success fs-5"><?= $escolhidas ?></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded border">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Faltam</small>
                                <span class="fw-bold text-danger fs-5"><?= $faltam ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="small fw-bold text-muted">Preenchimento Geral</span>
                        <span class="small fw-bold text-primary"><?= $porcentagem ?>%</span>
                    </div>
                    <div class="progress" style="height: 12px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                             role="progressbar"
                             style="width: <?= $porcentagem ?>%"
                             aria-valuenow="<?= $porcentagem ?>"
                             aria-valuemin="0"
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>

            <p class="small text-muted fw-bold mb-2 text-uppercase"><i class="bi bi-hand-index-thumb me-1"></i> Toque em uma linha livre para colocar seu nome:</p>
            <div class="list-group whatsapp-style-list shadow-sm border rounded">
                <?php foreach($linhas as $l): ?>
                    <?php if($l['linha_status'] === 'Preenchido'): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center linha-preenchida">
                            <span><i class="bi bi-check2-circle text-success me-2"></i><?= htmlspecialchars($l['linha_descricao']) ?> — <strong><?= htmlspecialchars($l['membro_nome'] ?? $l['linha_nome_externo']) ?></strong></span>
                            <a href="<?= url("sociedadeLider/rateioLiberarCota/{$l['linha_id']}/{$item['rateio_token']}") ?>" class="text-muted text-decoration-none small ms-2" onclick="return confirm('Remover este nome desta cota?')"><i class="bi bi-x-circle"></i></a>
                        </div>
                    <?php else: ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center linha-disponivel"
                             onclick="abrirModalAssinatura(<?= $l['linha_id'] ?>, '<?= htmlspecialchars($l['linha_descricao']) ?>')">
                            <span><i class="bi bi-circle text-muted opacity-50 me-2"></i><?= htmlspecialchars($l['linha_descricao']) ?></span>
                            <span class="badge bg-outline-primary text-primary border border-primary rounded-pill btn-sm py-1 px-2 small">Livre</span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalAssinar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= url('sociedadeLider/rateioAssinarCota') ?>" method="POST" class="modal-content border-0 shadow">
            <input type="hidden" name="token" value="<?= $item['rateio_token'] ?>">
            <input type="hidden" name="linha_id" id="modal_linha_id">

            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold" id="modal_titulo_item">Assinar Cota</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
			<div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Seu nome está na lista da igreja?</label>
                    <select name="membro_id" class="form-select text-dark" id="select_membro_modal" data-igreja="<?= $item['rateio_igreja_id'] ?>">
                        <option value="">-- Não, vou digitar meu nome (Visitante/Outros) --</option>
                    </select>
                </div>
                <div class="mb-3" id="campo_nome_manual">
                    <label class="form-label small fw-bold text-muted">Digite seu nome para a lista:</label>
                    <input type="text" name="nome_externo" id="input_nome_manual" class="form-control text-dark" placeholder="Ex: Seu Nome / Família" required>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">Salvar na Lista</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= asset('js/choices.min.js') ?>"></script>
<script>
let modalAssinarInstance = null;
let choicesInstance = null;

document.addEventListener("DOMContentLoaded", function() {
    // 1. Inicializa o Modal do Bootstrap
    const modalElement = document.getElementById('modalAssinar');
    if (modalElement) {
        modalAssinarInstance = new bootstrap.Modal(modalElement);
    }

    // 2. Inicializa o Choices.js com busca assíncrona (Remota)
    const selectMembro = document.getElementById('select_membro_modal');
    const igrejaId = selectMembro.getAttribute('data-igreja');

    choicesInstance = new Choices(selectMembro, {
        searchEnabled: true,
        noResultsText: 'Nenhum membro encontrado',
        noChoicesText: 'Digite 3 ou mais letras para buscar...',
        itemSelectText: 'Toque para selecionar',
        searchChoices: false, // Desativa a busca local pura para usar o ajax remoto
        placeholder: true,
        placeholderValue: '-- Digite seu nome para buscar --'
    });

    // Evento de digitação no campo de pesquisa do Choices.js
    selectMembro.addEventListener('search', function(event) {
        const busca = event.detail.value;

        if (busca.length >= 3) {
            // Faz a requisição na API criada no controller
            fetch(`<?= full_url('sociedadeLider/apiBuscarMembros') ?>?igreja_id=${igrejaId}&q=${encodeURIComponent(busca)}`)
                .then(response => response.json())
                .then(data => {
                    // Limpa as opções antigas mantendo a opção padrão "Não, vou digitar..."
                    choicesInstance.clearChoices();

                    const opcoes = [
                        { value: '', label: '-- Não, vou digitar meu nome (Visitante/Outros) --', selected: true }
                    ];

                    data.forEach(item => {
                        opcoes.push({ value: item.value, label: item.label, selected: false });
                    });

                    choicesInstance.setChoices(opcoes, 'value', 'label', true);
                });
        }
    });

    // Monitora a mudança de seleção para exibir ou ocultar o input manual
    selectMembro.addEventListener('change', function() {
        const manual = document.getElementById('campo_nome_manual');
        const input = document.getElementById('input_nome_manual');

        if(this.value !== "") {
            manual.style.display = "none";
            input.removeAttribute('required');
            input.value = "";
        } else {
            manual.style.display = "block";
            input.setAttribute('required', 'required');
        }
    });
});

function abrirModalAssinatura(id, descricao) {
    document.getElementById('modal_linha_id').value = id;
    document.getElementById('modal_titulo_item').innerText = "Assinar: " + descricao;

    // Reseta o Choices.js para o estado inicial padrão
    if (choicesInstance) {
        choicesInstance.clearChoices();
        choicesInstance.setChoices([
            { value: '', label: '-- Não, vou digitar meu nome (Visitante/Outros) --', selected: true }
        ], 'value', 'label', true);
    }

    document.getElementById('input_nome_manual').value = "";
    document.getElementById('campo_nome_manual').style.display = "block";
    document.getElementById('input_nome_manual').setAttribute('required', 'required');

    if (modalAssinarInstance) {
        modalAssinarInstance.show();
    }
}
</script>
</body>
</html>
