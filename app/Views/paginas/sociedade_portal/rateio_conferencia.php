<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conferência - <?= htmlspecialchars($item['rateio_titulo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: system-ui, -apple-system, sans-serif; }
        .card-conferencia { border: 1px solid #dee2e6; background: #fff; }
        .box-check { width: 24px; height: 24px; border: 2px solid #6c757d; border-radius: 4px; display: inline-block; cursor: pointer; }
        .box-check.checked { background-color: #198754; border-color: #198754; position: relative; }
        .box-check.checked::after { content: '\2714'; color: white; font-size: 14px; position: absolute; top: -2px; left: 4px; }

        /* Regras exclusivas para Impressão física ou em PDF */
        @media print {
            body { background-color: #fff !important; color: #000 !important; }
            .no-print { display: none !important; }
            .card-conferencia { border: none !important; box-shadow: none !important; margin-bottom: 2rem !important; page-break-inside: avoid; }
            .box-check { border: 2px solid #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .box-check.checked { background-color: transparent !important; border: 2px solid #000 !important; }
            .box-check.checked::after { color: #000 !important; }
            .list-group-item { border: 1px solid #dee2e6 !important; }
        }
    </style>
</head>
<body>

<div class="container my-4" style="max-width: 800px;">

    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white border rounded shadow-sm no-print">
        <a href="<?= full_url("sociedadeLider/rateioParticipar/" . $item['rateio_token']) ?>" class="btn btn-sm btn-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i> Voltar à Lista
        </a>
        <button onclick="window.print();" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow">
            <i class="bi bi-printer-fill me-1"></i> Imprimir Relatório
        </button>
    </div>

    <div class="text-center mb-4 border-bottom pb-3">
        <h4 class="fw-bold m-0 text-uppercase"><?= htmlspecialchars($item['rateio_titulo']) ?></h4>
        <p class="text-muted small m-0 mt-1">Planilha de Conferência de Ingredientes e Alimentos</p>
        <span class="badge bg-light text-dark border mt-2">Data Limite: <?= date('d/m/Y', strtotime($item['rateio_data_limite'])) ?></span>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-people-fill text-primary me-2 no-print"></i>Itens Escolhidos por Participante</h5>

        <?php if (!empty($participantes)): ?>
            <div class="row g-3">
                <?php foreach ($participantes as $nome => $ingredientes): ?>
                    <div class="col-12">
                        <div class="card card-conferencia shadow-sm rounded-3">
                            <div class="card-header bg-light fw-bold text-dark border-bottom d-flex justify-content-between align-items-center py-2 px-3">
                                <span><i class="bi bi-person text-secondary me-2 no-print"></i><?= htmlspecialchars($nome) ?></span>
                                <span class="badge bg-secondary text-white small fw-normal"><?= count($ingredientes) ?> cota(s)</span>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($ingredientes as $ingrediente): ?>
                                    <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-3">
                                        <span class="text-dark"><?= htmlspecialchars($ingrediente) ?></span>
                                        <div class="d-flex align-items-center">
                                            <span class="text-muted small me-2 no-print">Entregue:</span>
                                            <div class="box-check" onclick="this.classList.toggle('checked')" title="Marcar como entregue"></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">Nenhum membro ou participante reservou itens nesta lista até o momento.</div>
        <?php endif; ?>
    </div>

    <div class="card card-conferencia shadow-sm rounded-3 page-break-before">
        <div class="card-header bg-danger text-white fw-bold py-2 px-3">
            <i class="bi bi-exclamation-circle me-2 no-print"></i>Itens Livres / Não Reservados (Sem Membro)
        </div>
        <ul class="list-group list-group-flush">
            <?php if (!empty($itensLivres)): ?>
                <?php foreach ($itensLivres as $livre): ?>
                    <li class="list-group-item d-flex align-items-center justify-content-between py-2 px-3 bg-linear">
                        <span class="text-secondary"><em><?= htmlspecialchars($livre) ?></em></span>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-outline-danger text-danger border border-danger rounded-pill px-2 py-1 small me-3 no-print">Livre</span>
                            <div class="box-check" onclick="this.classList.toggle('checked')"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item text-center text-muted py-3">Glória a Deus! Todos os itens desta lista foram preenchidos!</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="mt-5 pt-5 text-center d-none d-print-block">
        <div class="mx-auto border-top border-dark" style="width: 280px;"></div>
        <p class="small text-muted mt-1">Visto do Conferente / Diácono Responsável</p>
    </div>

</div>

</body>
</html>
