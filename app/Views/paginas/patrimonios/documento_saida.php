<!DOCTYPE html>
<html>
<head>
    <title>Termo de Movimentação de Patrimônio</title>
    <style>
        body { font-family: sans-serif; padding: 40px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 30px; }
        .titulo { text-transform: uppercase; font-weight: bold; font-size: 20px; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .info-table td { border: 1px solid #ddd; padding: 10px; }
        .footer { margin-top: 100px; text-align: center; }
        .assinatura { display: inline-block; width: 300px; border-top: 1px solid #000; margin-top: 50px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Imprimir Documento</button>
        <hr>
    </div>

    <div class="header">
        <div class="titulo"><?= $igreja['igreja_nome'] ?></div>
        <div>TERMO DE <?= strtoupper($dados['patrimonio_bem_status']) ?> DE PATRIMÔNIO</div>
    </div>

    <p>Documentamos através deste termo a alteração de status/localização do bem patrimonial abaixo descrito:</p>

    <table class="info-table">
        <tr>
            <td><strong>CÓDIGO:</strong> <?= $dados['patrimonio_bem_codigo'] ?></td>
            <td><strong>ITEM:</strong> <?= $dados['patrimonio_bem_nome'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>CATEGORIA:</strong> <?= $dados['patrimonio_categoria_nome'] ?></td>
        </tr>
        <tr>
            <td><strong>STATUS ATUAL:</strong> <?= strtoupper($dados['patrimonio_bem_status']) ?></td>
            <td><strong>DATA:</strong> <?= date('d/m/Y H:i', strtotime($dados['patrimonio_movimentacao_data'] ?? 'now')) ?></td>
        </tr>
        <?php if($dados['patrimonio_bem_status'] == 'manutencao'): ?>
        <tr>
            <td colspan="2"><strong>DESTINO/RESPONSÁVEL:</strong> <?= $dados['patrimonio_movimentacao_observacao'] ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <div style="margin-top: 30px;">
        <strong>OBSERVAÇÕES / MOTIVO:</strong><br>
        <?= nl2br($dados['patrimonio_movimentacao_observacao'] ?: 'Nenhuma observação informada.') ?>
    </div>

    <div class="footer">
        <p><?= date('d/m/Y') ?></p>
        <div class="assinatura">Responsável pelo Patrimônio</div>
        <div style="width: 50px; display: inline-block;"></div>
        <div class="assinatura">Responsável pelo Recebimento/Retirada</div>
    </div>
</body>
</html>
