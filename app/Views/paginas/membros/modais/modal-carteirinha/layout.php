<?php
// --- DADOS DO MEMBRO ---
$nomeMembro     = mb_strtoupper($m['membro_nome'] ?? 'NOME NÃO INFORMADO');
$registro       = $m['membro_registro_interno'] ?? '000';
$cargo          = mb_strtoupper($m['membro_cargo'] ?? 'MEMBRO');

// Tratamento de Datas
$dataBatismo    = (!empty($m['membro_data_batismo']) && $m['membro_data_batismo'] != '0000-00-00')
                  ? date('d/m/Y', strtotime($m['membro_data_batismo']))
                  : '--/--/----';

$dataNascimento = (!empty($m['membro_data_nascimento']) && $m['membro_data_nascimento'] != '0000-00-00')
                  ? date('d/m/Y', strtotime($m['membro_data_nascimento']))
                  : '--/--/----';

// Lógica da Foto
$fotoMembro = null;
if (!empty($m['membro_foto_arquivo'])) {
    $idIgrejaSessao = $_SESSION['usuario_igreja_id'];
    $fotoMembro = url("assets/uploads/{$idIgrejaSessao}/membros/{$registro}/{$m['membro_foto_arquivo']}");
}

// --- DADOS DA IGREJA ---
$nomeIgreja     = $igreja['nome']     ?? 'IGREJA NÃO INFORMADA';
$pastorIgreja   = $igreja['pastor']   ?? 'NÃO INFORMADO';
$enderecoIgreja = $igreja['endereco'] ?? 'ENDEREÇO NÃO CADASTRADO';
$contatoIgreja  = $igreja['contatos'] ?? '';
?>

<style>
    #conteudoModalDinamico { padding: 0 !important; }

    .carteirinha-container {
        width: 17.5cm; /* Ajustado para proporção melhor de carteirinha aberta */
        height: 6.5cm;
        position: relative;
        background-image: url('<?= url('assets/img/Documento.png') ?>');
        background-size: 100% 100%;
        margin: 20px auto;
        color: #000;
        font-family: 'Segoe UI', Arial, sans-serif;
        overflow: hidden;
        background-color: white;
    }

    /* Lados da carteirinha */
    .lado {
        width: 50%;
        height: 100%;
        position: absolute;
        top: 0;
        padding: 0.6cm; /* Padding para não encavalar na borda verde */
    }
    .frente { left: 0; }
    .verso { right: 0; }

    /* Cabeçalho */
    .header-card { display: flex; align-items: center; margin-bottom: 10px; }
    .logo-ipb { width: 1.1cm; margin-right: 8px; }
    .cabecalho-txt h1 { font-size: 9pt; margin: 0; font-weight: 800; color: #004a2f; line-height: 1; }
    .cabecalho-txt p { font-size: 7.5pt; margin: 0; font-weight: bold; color: #333; }

    .num-reg {
        position: absolute;
        top: 0.6cm;
        right: 0.6cm;
        font-size: 7pt;
        font-weight: 900;
        color: #004a2f;
    }

    /* Corpo da Frente */
    .corpo-frente { display: flex; gap: 10px; margin-top: 5px; }

    .foto-box {
        width: 2.3cm;
        height: 3cm;
        border: 1px solid #004a2f;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .foto-box img { width: 100%; height: 100%; object-fit: cover; }

    .dados-membro { flex: 1; }
    .info-group { margin-bottom: 3px; }
    .info-label { font-size: 5.5pt; text-transform: uppercase; color: #666; font-weight: bold; display: block; }
    .info-valor { font-size: 8.5pt; font-weight: 700; color: #111; display: block; border-bottom: 0.5px solid #ddd; }

    .footer-frente {
        position: absolute;
        bottom: 0.5cm;
        left: 0;
        width: 100%;
        text-align: center;
        font-size: 7pt;
        font-weight: bold;
        color: #004a2f;
        text-transform: uppercase;
    }

    /* Estilo do Verso */
    .texto-certificacao { font-size: 7.5pt; text-align: justify; line-height: 1.3; color: #222; margin-top: 5px; }
    .versiculo { font-size: 7pt; font-style: italic; color: #444; margin-top: 8px; border-left: 2px solid #004a2f; padding-left: 5px; }

    .verso-bottom { display: flex; align-items: flex-end; position: absolute; bottom: 0.6cm; left: 0.6cm; right: 0.6cm; }
    .contatos-verso { flex: 1; font-size: 6pt; color: #555; line-height: 1.2; }
    .qrcode-box { width: 1.4cm; height: 1.4cm; margin-left: 10px; }

    .assinatura-area {
        margin-top: 15px;
        border-top: 0.8px solid #333;
        text-align: center;
        font-size: 6.5pt;
        padding-top: 2px;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
    }

    @media print {
        body { background: white; }
        .no-print { display: none !important; }
        .carteirinha-container { margin: 0; box-shadow: none; }
    }
</style>

<div id="printArea" class="carteirinha-container">
    <div class="lado frente">
        <div class="header-card">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
            <div class="cabecalho-txt">
                <h1>IGREJA PRESBITERIANA</h1>
                <p><?= $nomeIgreja ?></p>
            </div>
        </div>
        <div class="num-reg">REG. <?= $registro ?></div>

        <div class="corpo-frente">
            <div class="foto-box">
                <?php if ($fotoMembro): ?>
                    <img src="<?= $fotoMembro ?>" alt="Foto">
                <?php else: ?>
                    <i class="bi bi-person text-muted" style="font-size: 2rem;"></i>
                <?php endif; ?>
            </div>

            <div class="dados-membro">
                <div class="info-group">
                    <span class="info-label">Nome Completo:</span>
                    <span class="info-valor" style="font-size: 9pt;"><?= $nomeMembro ?></span>
                </div>

                <div class="row g-1">
                    <div class="col-6">
                        <span class="info-label">Nascimento:</span>
                        <span class="info-valor"><?= $dataNascimento ?></span>
                    </div>
                    <div class="col-6">
                        <span class="info-label">Batismo:</span>
                        <span class="info-valor"><?= $dataBatismo ?></span>
                    </div>
                </div>

                <div class="info-group mt-1">
                    <span class="info-label">Cargo / Sociedade:</span>
                    <span class="info-valor"><?= $cargo ?></span>
                </div>
            </div>
        </div>
        <div class="footer-frente"><?= $nomeIgreja ?></div>
    </div>

    <div class="lado verso">
        <div class="header-card">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-ipb">
            <div class="cabecalho-txt">
                <h1>IGREJA PRESBITERIANA</h1>
                <p>DO BRASIL</p>
            </div>
        </div>

        <div class="texto-certificacao">
            Certificamos que o portador acima identificado é membro comungante desta igreja, estando no pleno gozo de seus privilégios e deveres espirituais.
        </div>

        <div class="versiculo">
            "Tudo faço por causa do evangelho, para ser também participante dele." <br>
            <strong>1 Coríntios 9:23</strong>
        </div>

        <div class="assinatura-area">Assinatura do Portador</div>

        <div class="verso-bottom">
            <div class="contatos-verso">
                <i class="bi bi-geo-alt-fill"></i> <?= $enderecoIgreja ?><br>
                <i class="bi bi-whatsapp"></i> <?= $contatoIgreja ?>
            </div>
            <div class="qrcode-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= $registro ?>" style="width:100%; height:100%;">
            </div>
        </div>
    </div>
</div>

<div class="text-center my-4 no-print">
    <hr>
    <div class="btn-group">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Fechar</button>
<button type="button"
        id="btn-download-card"
        class="btn btn-success"
        data-filename="Carteirinha_<?= str_replace([' ', "'"], '_', $nomeMembro) ?>.png">
    <i class="bi bi-download"></i> Baixar Carteirinha (PNG)
</button>
    </div>
</div>


