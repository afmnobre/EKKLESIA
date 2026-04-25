<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<?php
// Formatação da data por extenso
if (class_exists('IntlDateFormatter')) {
    $formatter = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
    $dataHojeExtenso = $formatter->format(new DateTime());
} else {
    $meses = [1 => 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
    $dataHojeExtenso = date('d') . " de " . $meses[(int)date('m')] . " de " . date('Y');
}

$pNome = $membro['pastor_nome'] ?? $membro['igreja_pastor_nome'] ?? '';
$pastorExibicao = !empty($pNome) ? "Rev. " . $pNome : "Reverendo: ____________________";
?>

<style>
    .certificado-container {
        width: 1000px; /* Largura ideal para certificado */
        height: 700px;
        position: relative;
        margin: 0 auto;
        overflow: hidden;
        background-color: #fff;
    }

    .img-background {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .certificado-content {
        position: relative;
        z-index: 2;
        padding: 60px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Ajuste para evitar que o html2canvas ignore fontes ou estilos */
    .certificado-container * {
        font-family: 'Times New Roman', Times, serif;
    }
</style>

<div id="capturaCertificado" class="certificado-container">
    <img src="<?= url('assets/img/CertificadoBatismo.png') ?>" class="img-background">

    <div class="certificado-content">
        <div>
            <img src="<?= url('assets/img/logo_ipb.png') ?>" style="height: 60px; margin-bottom: 10px;">
            <div style="font-size: 24px; font-weight: bold; color: #000; text-transform: uppercase;">
                <?= ($membro['igreja_nome'] ?? 'IGREJA NÃO DEFINIDA') ?>
            </div>
            <div style="font-size: 13px; color: #333;">
                <?= $membro['igreja_endereco'] ?? '' ?>
            </div>
        </div>

        <div style="text-align: right; padding-right: 40px; font-size: 14px;">
            Rol nº: <strong><?= $membro['membro_registro_interno'] ?></strong>
        </div>

        <div style="font-size: 48px; font-weight: bold; color: #000; letter-spacing: 3px; margin: 20px 0;">
            CERTIFICADO DE BATISMO
        </div>

        <div style="font-size: 20px; line-height: 1.8; padding: 0 80px; color: #000;">
            Certificamos que <br>
            <span style="font-size: 32px; font-weight: bold; border-bottom: 2px solid #000; padding: 0 20px; display: inline-block; margin: 10px 0;">
                <?= mb_strtoupper($membro['membro_nome']) ?>
            </span><br>
            foi batizado(a) em nome do Pai, do Filho e do Espírito Santo no dia <br>
            <strong><?= !empty($membro['membro_data_batismo']) ? date('d/m/Y', strtotime($membro['membro_data_batismo'])) : '--/--/----' ?></strong>, tornando-se membro desta igreja.
        </div>

        <div>
            <p style="font-size: 18px; margin-bottom: 50px; color: #000;">
                <?= $dataHojeExtenso ?>
            </p>

            <div style="display: flex; justify-content: space-around; align-items: flex-end; padding: 0 40px;">
                <div style="width: 40%; border-top: 1px solid #000; padding-top: 8px;">
                    <span style="font-size: 14px; font-weight: bold; display: block;"><?= $pastorExibicao ?></span>
                    <small style="font-size: 11px; text-transform: uppercase;">Ministro</small>
                </div>
                <div style="width: 40%; border-top: 1px solid #000; padding-top: 8px;">
                    <span style="font-size: 14px; font-weight: bold; display: block;">Secretário(a) do Conselho</span>
                    <small style="font-size: 11px; text-transform: uppercase;">Conselho Local</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p-4 bg-white border-top text-end">
    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Fechar</button>

    <button type="button"
            id="btnBaixarCertificado"
            class="btn btn-success px-5 fw-bold shadow"
            data-filename="Certificado_Batismo_<?= str_replace([' ', "'"], '_', $membro['membro_nome']) ?>.png">
        <i class="bi bi-download me-2"></i>BAIXAR CERTIFICADO (PNG)
    </button>
</div>


