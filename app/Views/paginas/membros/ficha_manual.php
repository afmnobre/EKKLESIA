<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Cadastro Manual - EKKLESIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        /* Reset para Impressão Fiel */
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; }
        body { background-color: #f4f4f4; font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a; margin: 0; padding: 0; }

        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 5px auto;
            background: white;
            padding: 8mm 15mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        /* Cabeçalho */
        .header-box { border-bottom: 2px solid #000; padding-bottom: 8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; }
        .header-logo img { max-height: 45px; }
        .header-info { text-align: center; flex-grow: 1; }
        .header-info h4 { margin: 0; font-size: 1.1rem; text-transform: uppercase; font-weight: 800; }
        .header-info p { margin: 0; font-size: 0.7rem; color: #444; }

        .form-title { text-align: center; margin-bottom: 12px; }
        .form-title h2 { border: 2px solid #000; display: inline-block; padding: 3px 20px; text-transform: uppercase; font-size: 1.1rem; margin: 0; }

        /* Seções e Blocos */
        .section-title {
            background: #000; color: white; padding: 4px 10px;
            font-weight: 700; text-transform: uppercase; font-size: 0.7rem; margin-top: 8px;
        }

        /* Sistema de Grid */
        .field-row { display: flex; width: 100%; border-left: 1.5px solid #000; border-right: 1.5px solid #000; border-bottom: 1.5px solid #000; }
        .field-row:first-of-type { border-top: 1.5px solid #000; }

        .field-box { padding: 3px 8px; flex-grow: 1; border-right: 1.5px solid #000; min-height: 36px; position: relative; }
        .field-box:last-child { border-right: none; }

        .field-label { display: block; font-size: 0.55rem; font-weight: 800; text-transform: uppercase; color: #555; margin-bottom: 1px; }
        .field-content { font-family: 'Courier New', monospace; font-size: 0.9rem; }

        /* Gênero e Checks */
        .check-group { display: flex; gap: 12px; align-items: center; margin-top: 1px; }
        .square { width: 12px; height: 12px; border: 1.5px solid #000; display: inline-block; margin-right: 3px; vertical-align: middle; }

        /* Tabela de Dependentes */
        .dep-table { width: 100%; border-collapse: collapse; border: 1.5px solid #000; }
        .dep-table th { background: #eee; border: 1.5px solid #000; padding: 4px; font-size: 0.6rem; text-transform: uppercase; text-align: left; }
        .dep-table td { border: 1.5px solid #000; height: 26px; padding: 0 8px; }

        /* Assinaturas com espaçamento de 2 linhas */
        .footer-sig { margin-top: 40px; padding-bottom: 10px; display: flex; justify-content: space-between; }
        .sig-block { width: 45%; text-align: center; }
        .sig-line { border-top: 1.5px solid #000; margin-bottom: 5px; }
        .sig-text { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; }

        .btn-float { position: fixed; bottom: 20px; right: 20px; display: flex; gap: 10px; z-index: 1000; }
        .btn-liturgia { padding: 10px 20px; border-radius: 50px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }

        @media print {
            body { background: white; }
            .print-container { margin: 0; box-shadow: none; padding: 5mm 8mm; width: 100%; min-height: auto; }
            .no-print { display: none !important; }
            .section-title { -webkit-print-color-adjust: exact; background-color: #000 !important; color: #fff !important; }
        }
    </style>
</head>
<body>

<div class="btn-float no-print">
    <button onclick="window.history.back();" class="btn btn-dark btn-liturgia"><i class="bi bi-arrow-left"></i> Voltar</button>
    <button onclick="window.print();" class="btn btn-primary btn-liturgia"><i class="bi bi-printer"></i> Imprimir Ficha</button>
</div>

<div class="print-container">
    <div class="header-box">
        <div class="header-logo"><img src="<?= url('assets/img/logo_ipb_completo.png') ?>"></div>
        <div class="header-info">
            <h4><?= htmlspecialchars($igreja['igreja_nome'] ?? 'IGREJA LOCAL') ?></h4>
            <p><?= htmlspecialchars($igreja['igreja_endereco'] ?? 'Endereço Completo da Igreja') ?></p>
        </div>
        <div class="header-logo">
            <?php
                $idIgreja = $_SESSION['usuario_igreja_id'];
                $logoIgreja = $igreja['igreja_logo'] ?? '';
                $caminhoLogo = "assets/uploads/{$idIgreja}/logo/{$logoIgreja}";
                if(!empty($logoIgreja) && file_exists($caminhoLogo)): ?>
                <img src="<?= url($caminhoLogo) ?>">
            <?php endif; ?>
        </div>
    </div>

    <div class="form-title">
        <h2>Ficha de Qualificação de Membro</h2>
    </div>

    <div class="section-title">01. Identificação Pessoal</div>
    <div class="field-row" style="border-top: 1.5px solid #000;">
        <div class="field-box" style="flex: 3;"><span class="field-label">Nome Completo</span></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">Registro (ROL)</span></div>
    </div>
    <div class="field-row">
        <div class="field-box" style="flex: 1.5;"><span class="field-label">CPF</span><div class="field-content">____.____.____-___</div></div>
        <div class="field-box" style="flex: 1.5;"><span class="field-label">RG / Identidade</span><div class="field-content">___.____.____-__</div></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">Data de Nascimento</span><div class="field-content">____/____/________</div></div>
    </div>
    <div class="field-row">
        <div class="field-box" style="flex: 1;">
            <span class="field-label">Gênero</span>
            <div class="check-group">
                <span><div class="square"></div> M</span>
                <span><div class="square"></div> F</span>
            </div>
        </div>
        <div class="field-box" style="flex: 1.5;"><span class="field-label">Estado Civil</span></div>
        <div class="field-box" style="flex: 1.5;"><span class="field-label">Celular / WhatsApp</span><div class="field-content">(___) _________-________</div></div>
    </div>
    <div class="field-row">
        <div class="field-box"><span class="field-label">E-mail para Contato</span></div>
    </div>

    <div class="section-title">02. Vida Eclesiástica</div>
    <div class="field-row" style="border-top: 1.5px solid #000;">
        <div class="field-box" style="flex: 1;"><span class="field-label">Data de Batismo</span><div class="field-content">____/____/________</div></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">Profissão de Fé</span><div class="field-content">____/____/________</div></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">Data de Casamento</span><div class="field-content">____/____/________</div></div>
    </div>
    <div class="field-row">
        <div class="field-box"><span class="field-label">Cargo ou Função Atual na Igreja</span></div>
    </div>

    <div class="section-title">03. Localização Residencial</div>
    <div class="field-row" style="border-top: 1.5px solid #000;">
        <div class="field-box" style="flex: 4;"><span class="field-label">Rua / Logradouro</span></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">Nº</span></div>
    </div>
    <div class="field-row">
        <div class="field-box" style="flex: 2;"><span class="field-label">Bairro</span></div>
        <div class="field-box" style="flex: 2;"><span class="field-label">Complemento / Apto / Bloco</span></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">CEP</span><div class="field-content">_______-___</div></div>
    </div>
    <div class="field-row">
        <div class="field-box" style="flex: 4;"><span class="field-label">Cidade</span></div>
        <div class="field-box" style="flex: 1;"><span class="field-label">UF</span></div>
    </div>

    <div class="section-title" style="margin-bottom: 0;">04. Família e Dependentes (Filhos / Cônjuge)</div>
    <table class="dep-table">
        <thead>
            <tr>
                <th width="50%">Nome Completo</th>
                <th width="20%">Parentesco</th>
                <th width="30%">Data de Nascimento</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i=0; $i<6; $i++): ?>
            <tr><td></td><td></td><td></td></tr>
            <?php endfor; ?>
        </tbody>
    </table>

<br><br>

    <div class="footer-sig">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-text">Assinatura do Membro ou Responsável</div>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-text">Local e Data</div>
        </div>
    </div>
</div>

</body>
</html>
