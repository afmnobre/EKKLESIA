<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php
// Fallback manual caso o IntlDateFormatter não exista
if (class_exists('IntlDateFormatter')) {
    $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $dataHojeExtenso = $formatter->format(new DateTime());
} else {
    // Lógica manual para não quebrar o sistema
    $meses = [
        1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
        5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
        9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
    ];
    $dia = date('d');
    $mes = $meses[(int)date('m')];
    $ano = date('Y');
    $dataHojeExtenso = "$dia de $mes de $ano";
}

$pNome = $membro['pastor_nome'] ?? $membro['igreja_pastor_nome'] ?? '';
$pastorExibicao = !empty($pNome) ? "Reverendo: " . $pNome : "Reverendo: ____________________";
?>

<div id="capturaCertificado" class="certificado-container">
    <img src="<?= url('assets/img/CertificadoBatismo.png') ?>" class="img-background">

    <div class="certificado-content">
        <div style="margin-top: 10px;">
             <img src="<?= url('assets/img/logo_ipb.png') ?>" style="height: 55px; margin-bottom: 5px;">
             <div style="font-size: 20px; font-weight: bold; color: #000; line-height: 1.1;">
                <?= mb_strtoupper($membro['igreja_nome'] ?? 'IGREJA NÃO DEFINIDA') ?>
             </div>
             <div style="font-size: 11px; color: #444;">
                <?= $membro['igreja_endereco'] ?? '' ?>
             </div>
        </div>

        <div style="text-align: right; padding-right: 20px; font-size: 13px;">
            Rol nº: <strong><?= $membro['membro_registro_interno'] ?></strong>
        </div>

        <div style="font-size: 42px; font-family: 'Times New Roman', serif; font-weight: bold; margin: 10px 0; color: #000; letter-spacing: 2px;">
            CERTIFICADO DE BATISMO
        </div>

        <div style="font-size: 18px; line-height: 1.5; padding: 0 60px; color: #000;">
            Certificamos que <br>
            <span style="font-size: 28px; font-weight: bold; border-bottom: 2px solid #000; padding: 0 10px; display: inline-block; margin: 8px 0;">
                <?= $membro['membro_nome'] ?>
            </span><br>
            foi batizado(a) em nome do Pai, do Filho e do Espírito Santo no dia <br>
            <strong><?= !empty($membro['membro_data_batismo']) ? date('d/m/Y', strtotime($membro['membro_data_batismo'])) : '--/--/----' ?></strong>, tornando-se membro desta igreja.
        </div>

        <div style="margin-bottom: 15px;">
            <p style="font-size: 16px; margin-bottom: 35px; color: #000;">
                <?= $dataHojeExtenso ?>
            </p>

            <div style="display: flex; justify-content: space-around; align-items: flex-end;">
                <div style="width: 35%; border-top: 1px solid #000; padding-top: 5px;">
                    <span style="font-size: 13px; font-weight: bold; display: block;"><?= $pastorExibicao ?></span>
                    <small style="font-size: 10px; text-transform: uppercase;">Ministro</small>
                </div>
                <div style="width: 35%; border-top: 1px solid #000; padding-top: 5px;">
                    <span style="font-size: 13px; font-weight: bold; display: block;">Secretário(a) do Conselho</span>
                    <small style="font-size: 10px; text-transform: uppercase;">Conselho Local</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p-4 bg-white border-top text-end">
    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Fechar</button>

    <button type="button" id="btnBaixarCertificado" class="btn btn-success px-5 fw-bold shadow">
        <i class="bi bi-download me-2"></i>BAIXAR CERTIFICADO (PNG)
    </button>
    </div>


