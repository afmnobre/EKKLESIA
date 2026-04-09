<?php
// Proteção e dados iniciais
$idIgreja = $idIgreja ?? $_GET['i'] ?? null;
$descricao = $conta['financeiro_conta_descricao'] ?? 'Não informada';
$valor = $conta['financeiro_conta_valor'] ?? 0;
$vencimento = $conta['financeiro_conta_data_vencimento'] ?? null;
$uploadSucesso = isset($_GET['sucesso']) && $_GET['sucesso'] == 1;

// Referências de data
$dataRef = $vencimento ?? date('Y-m-d');
$ano = date('Y', strtotime($dataRef));
$mes = date('m', strtotime($dataRef));

// --- LÓGICA DO LOGO ---
// No topo da upload_mobile.php, altere para pegar da nova variável $igreja:
$nomeIgreja = $igreja['igreja_nome'] ?? "EKKLESIA";

$logoIgreja = !empty($igreja['igreja_logo'])
    ? full_url("public/assets/uploads/{$idIgreja}/logo/{$igreja['igreja_logo']}")
    : full_url("public/assets/img/logo_ipb.png");
?>

<div class="container py-3">

    <div class="text-center mb-4">
        <img src="<?= $logoIgreja ?>" alt="Logo" style="max-height: 80px; width: auto;" class="mb-2">
        <h5 class="fw-bold text-secondary"><?= $nomeIgreja ?></h5>
        <hr class="opacity-10">
    </div>

    <?php if ($uploadSucesso): ?>
        <div class="text-center py-4">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            <h2 class="fw-bold">Envio Concluído!</h2>
            <p class="text-muted mb-5">O documento foi anexado com sucesso ao lançamento.</p>

            <div class="d-grid gap-3">
                <a href="<?= full_url("financeiro/uploadExterno/{$conta['financeiro_conta_id']}?i={$idIgreja}") ?>"
                   class="btn btn-primary btn-lg fw-bold py-3 shadow">
                    <i class="bi bi-arrow-left me-2"></i> ENVIAR OUTRO ARQUIVO
                </a>
                <button onclick="window.close();" class="btn btn-light btn-lg py-3 border">
                    <i class="bi bi-x-lg me-2"></i> FECHAR PÁGINA
                </button>
            </div>
        </div>

    <?php else: ?>
        <div class="card shadow-sm border-0 mb-3 bg-light" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem;">Lançamento Financeiro</div>
                <div class="mb-3 fw-bold text-dark fs-5"><?= $descricao ?></div>

                <div class="row g-0">
                    <div class="col-6 border-end pe-2">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Valor</div>
                        <div class="text-danger fw-bold fs-5">R$ <?= number_format((float)$valor, 2, ',', '.') ?></div>
                    </div>
                    <div class="col-6 ps-3">
                        <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Vencimento</div>
                        <div class="fw-bold fs-5"><?= $vencimento ? date('d/m/Y', strtotime($vencimento)) : '--/--/----' ?></div>
                    </div>
                </div>
            </div>
        </div>

        <form action="<?= full_url('financeiro/uploadAnexo') ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="conta_id" value="<?= $conta['financeiro_conta_id'] ?>">
            <input type="hidden" name="igreja_id" value="<?= $idIgreja ?>">
            <input type="hidden" name="ano_referencia" value="<?= $ano ?>">
            <input type="hidden" name="mes_referencia" value="<?= $mes ?>">
            <input type="hidden" name="is_mobile" value="1">

            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <label class="form-label fw-bold d-block mb-3 text-center">O que você está enviando?</label>

                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="tipo_arquivo" id="tipo1" value="comprovante" checked>
                        <label class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center" for="tipo1">
                            <i class="bi bi-receipt fs-3 mb-1"></i>
                            <span class="small fw-bold">Comprovante</span>
                        </label>

                        <input type="radio" class="btn-check" name="tipo_arquivo" id="tipo2" value="nota_fiscal">
                        <label class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center" for="tipo2">
                            <i class="bi bi-file-earmark-text fs-3 mb-1"></i>
                            <span class="small fw-bold">Nota Fiscal</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Selecione o arquivo ou tire uma foto</label>
                <input type="file" name="arquivo" class="form-control form-control-lg border-2 shadow-sm"
                       accept="image/*,application/pdf" capture="environment" required>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-lg py-3 rounded-pill">
                <i class="bi bi-cloud-arrow-up me-2"></i> ENVIAR AGORA
            </button>
        </form>
    <?php endif; ?>

    <div class="text-center mt-5">
        <small class="text-muted">Sistema EKKLESIA &bull; Gestão Inteligente</small>
    </div>
</div>
