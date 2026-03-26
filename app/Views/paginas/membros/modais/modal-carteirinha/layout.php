<?php
/**
 * As variáveis $m e $igreja vêm do Controller através do include.
 * Aqui nós extraímos os dados para as variáveis simples usadas no HTML.
 */

// --- DADOS DO MEMBRO ---
$nomeMembro     = $m['membro_nome'] ?? 'NOME NÃO INFORMADO';
$registro       = $m['membro_registro_interno'] ?? '000';
$cargo          = $m['membro_cargo'] ?? 'MEMBRO';

// Tratamento de Datas
$dataBatismo    = (!empty($m['membro_data_batismo']) && $m['membro_data_batismo'] != '0000-00-00')
                  ? date('d/m/Y', strtotime($m['membro_data_batismo']))
                  : '--/--/----';

$dataNascimento = (!empty($m['membro_data_nascimento']) && $m['membro_data_nascimento'] != '0000-00-00')
                  ? date('d/m/Y', strtotime($m['membro_data_nascimento']))
                  : '--/--/----';

// Lógica da Foto (Caminho que você usa no sistema)
$fotoMembro = null;
if (!empty($m['membro_foto_arquivo'])) {
    $idIgrejaSessao = $_SESSION['usuario_igreja_id'];
    $fotoMembro = url("assets/uploads/{$idIgrejaSessao}/membros/{$registro}/{$m['membro_foto_arquivo']}");
}

// --- DADOS DA IGREJA (Resolvendo os Warnings) ---
$nomeIgreja     = $igreja['nome']     ?? 'IGREJA NÃO INFORMADA';
$pastorIgreja   = $igreja['pastor']   ?? 'NÃO INFORMADO';
$enderecoIgreja = $igreja['endereco'] ?? 'ENDEREÇO NÃO CADASTRADO';
$contatoIgreja  = $igreja['contatos'] ?? '';
?>

<style>
    #conteudoModalDinamico { padding: 0 !important; }

    .carteirinha-container {
        width: 21cm;
        height: 7.4cm;
        position: relative;
        background-image: url('<?= url('assets/img/Documento.png') ?>');
        background-size: 100% 100%;
        margin: 10px auto;
        color: #000;
        font-family: Arial, sans-serif;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        background-color: white; /* Garante fundo branco se a imagem falhar */
    }

    /* Classe auxiliar para o gerador de imagem */
    .carteirinha-container.is-capturing {
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    .lado { width: 10.2cm; height: 6.8cm; position: absolute; top: 0.3cm; }
    .frente { left: 0.2cm; }
    .verso { right: 0.2cm; }

    /* Linha de dobra central (Guia para o usuário) */
    .linha-dobra {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;
        width: 1px;
        border-left: 1px dashed rgba(0,0,0,0.2);
        transform: translateX(-50%);
        z-index: 10;
    }

    .logo-ipb { position: absolute; top: 0.4cm; left: 0.4cm; width: 1.3cm; }
    .cabecalho-txt { position: absolute; top: 0.45cm; left: 1.8cm; line-height: 1.1; text-align: left; }
    .cabecalho-txt h1 { font-size: 11pt; margin: 0; font-weight: bold; color: #004a2f; }
    .cabecalho-txt p { font-size: 8.5pt; margin: 0; font-weight: bold; }
    .num-reg { position: absolute; top: 0.4cm; right: 0.4cm; font-size: 8.5pt; border: 1px solid #000; padding: 1px 4px; font-weight: bold; background: #fff; }
    .foto-box { position: absolute; top: 1.8cm; left: 0.4cm; width: 2.8cm; height: 3.5cm; border: 1px solid #000; overflow: hidden; background: #fff; }
    .foto-box img { width: 100%; height: 100%; object-fit: cover; }
    .dados-membro { position: absolute; top: 1.8cm; left: 3.4cm; width: 6.4cm; text-align: left; }
    .info-label { font-size: 6.5pt; text-transform: uppercase; color: #444; display: block; margin-top: 2px; }
    .info-valor { font-size: 9.5pt; font-weight: bold; display: block; border-bottom: 0.5px solid #eee; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .flex-row { display: flex; justify-content: space-between; gap: 5px; }
    .footer-frente { position: absolute; bottom: 0.2cm; left: 3.4cm; right: 0.4cm; text-align: center; font-size: 8.5pt; font-weight: bold; font-style: italic; color: #004a2f; }
    .texto-certificacao { position: absolute; top: 1.8cm; left: 0.5cm; right: 0.5cm; font-size: 8.5pt; text-align: justify; line-height: 1.2; }
    .versiculo { position: absolute; top: 3.2cm; left: 0.5cm; right: 2.5cm; font-size: 8.5pt; font-style: italic; text-align: left; }
    .contatos-verso { position: absolute; bottom: 0.4cm; left: 0.5cm; width: 6.5cm; font-size: 7pt; line-height: 1.2; text-align: left; }
    .qrcode-box { position: absolute; top: 3.8cm; right: 0.5cm; width: 1.6cm; height: 1.6cm; }
    .assinatura-area { position: absolute; bottom: 0.4cm; right: 0.5cm; width: 4.5cm; border-top: 1px solid #000; text-align: center; font-size: 7.5pt; padding-top: 2px; }

    @media print {
        @page { size: landscape; margin: 0; }
        body * { visibility: hidden; }
        #printArea, #printArea * { visibility: visible; }
        #printArea { position: fixed; left: 0; top: 0; margin: 0; border: none; box-shadow: none; }
        .linha-dobra { border-left: 1px dashed #ccc !important; }
        .no-print { display: none !important; }
    }
</style>

<div id="printArea" class="carteirinha-container">
    <div class="lado frente">
        <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
        <div class="cabecalho-txt">
            <h1>IGREJA PRESBITERIANA</h1>
            <p><?= $nomeIgreja ?></p>
        </div>
        <div class="num-reg">Nº <?= $registro ?></div>

        <div class="foto-box">
            <?php if ($fotoMembro): ?>
                <img src="<?= $fotoMembro ?>" alt="Foto">
            <?php else: ?>
                <div style="padding-top:1.3cm; text-align:center; font-size:7pt; color: #ccc;">SEM FOTO</div>
            <?php endif; ?>
        </div>

        <div class="dados-membro">
            <span class="info-label">Nome do Membro:</span>
            <span class="info-valor" style="font-size: 10.5pt;"><?= $nomeMembro ?></span>

            <div class="flex-row">
                <div style="flex:1"><span class="info-label">Batismo:</span><span class="info-valor"><?= $dataBatismo ?></span></div>
                <div style="flex:1"><span class="info-label">Nascimento:</span><span class="info-valor"><?= $dataNascimento ?></span></div>
            </div>

            <span class="info-label">Pastor:</span>
            <span class="info-valor"><?= $pastorIgreja ?></span>

            <span class="info-label">Sociedade / Cargo:</span>
            <span class="info-valor"><?= $cargo ?></span>
        </div>
        <div class="footer-frente">Igreja Presbiteriana do Jardim Girassol</div>
    </div>

    <div class="lado verso">
        <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
        <div class="cabecalho-txt">
            <h1>IGREJA PRESBITERIANA</h1>
            <p><?= $nomeIgreja ?></p>
        </div>

        <div class="texto-certificacao">
            Este documento certifica que o portador é membro comungante da Igreja Presbiteriana do Jardim Girassol, jurisdicionada à Igreja Presbiteriana do Brasil.
        </div>

        <div class="versiculo">
            "Tudo faço por causa do evangelho, para ser também participante dele."<br><strong>1 Coríntios 9:23</strong>
        </div>

        <div class="qrcode-box">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $registro ?>" style="width:100%;">
        </div>

        <div class="contatos-verso">
            📍 <?= $enderecoIgreja ?><br>
            📱 <?= $contatoIgreja ?>
        </div>

        <div class="assinatura-area">Assinatura do portador</div>
    </div>
</div>

<div class="text-center my-4 no-print">
    <hr>
    <div class="btn-group">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Fechar
        </button>

<button type="button" id="btn-gerar-img-carteirinha" class="btn btn-info text-white" onclick="executarDownloadCarteirinha(this)">
    <i class="fa fa-download"></i> Baixar Imagem (PNG)
</button>

    </div>
    <p class="small text-muted mt-2">
        <i class="fa fa-info-circle"></i> A imagem gerada terá o tamanho proporcional de um RG aberto.
    </p>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


<script>
// Usamos var para garantir o escopo global no carregamento AJAX
var executarDownloadCarteirinha = function(btn) {
    const entrada = document.getElementById('printArea');
    if (!entrada) return alert("Área de impressão não encontrada.");

    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Gerando...';
    btn.disabled = true;

    // Adiciona classe para remover sombras e margens na captura
    entrada.classList.add('is-capturing');

    html2canvas(entrada, {
        scale: 4,
        useCORS: true,
        backgroundColor: "#ffffff"
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'Carteirinha_<?= str_replace([" ", "'"], "_", $nomeMembro) ?>.png';
        link.href = canvas.toDataURL("image/png", 1.0);
        link.click();

        entrada.classList.remove('is-capturing');
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    }).catch(err => {
        console.error(err);
        entrada.classList.remove('is-capturing');
        btn.innerHTML = originalHTML;
        btn.disabled = false;
    });
};
</script>
