<?php
/**
 * SCRIPT DE POPULAÇÃO FINANCEIRA - EKKLESIA
 * Período: 2024 - 2026 (Pagos)
 */

header('Content-Type: text/html; charset=utf-8');
// Conexão com seu banco de dados
$host = 'sql200.4sql.net';
$db   = 'sq_40883683_EKKLESIA';
$user = 'sq_40883683';
$pass = 'SALVADOR2013@';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (\PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Configurações Base
$igrejaId = 1;
$anos = [2024, 2025, 2026];
$contasFinanceiras = [1, 2, 3]; // BB, Caixinha, Bradesco

echo "Iniciando geração de dados...<br>";

foreach ($anos as $ano) {
    for ($mes = 1; $mes <= 12; $mes++) {
        
        // --- 1. GERAR ENTRADAS (Dízimos e Ofertas) ---
        // Simulação: Cultos aos Domingos e Quartas
        $dataLoop = new DateTime("$ano-$mes-01");
        $ultimoDia = $dataLoop->format('t');
        
        for ($dia = 1; $dia <= $ultimoDia; $dia++) {
            $dataAtual = new DateTime("$ano-$mes-$dia");
            $diaSemana = $dataAtual->format('N'); // 7 = Domingo, 3 = Quarta

            if ($diaSemana == 7 || $diaSemana == 3) {
                // Oferta de Culto (Cat 18, Sub 13)
                gerarLancamento($pdo, $igrejaId, 18, 13, 'entrada', 'Oferta de Culto', rand(400, 1200), $dataAtual);
                
                // Dízimos acumulados do dia (Cat 18, Sub 14)
                // Média para 180 membros classe média baixa
                gerarLancamento($pdo, $igrejaId, 18, 14, 'entrada', 'Dízimos do Rol de Membros', rand(2500, 5500), $dataAtual);
            }
        }

        // --- 2. GERAR DESPESAS FIXAS (Cat 15) ---
        $dataVenc = "$ano-$mes-10";
        gerarLancamento($pdo, $igrejaId, 15, 3, 'saida', 'Conta de Água/Esgoto', rand(180, 350), new DateTime($dataVenc));
        gerarLancamento($pdo, $igrejaId, 15, 4, 'saida', 'Conta de Luz (Energia)', rand(450, 950), new DateTime($dataVenc));
        gerarLancamento($pdo, $igrejaId, 15, 5, 'saida', 'Internet Fibra', 120.00, new DateTime($dataVenc));

        // --- 3. GERAR DESPESAS VARIÁVEIS (Aleatórias) ---
        // Manutenção (Cat 16)
        gerarLancamento($pdo, $igrejaId, 16, 10, 'saida', 'Material de Limpeza mensal', rand(150, 400), new DateTime("$ano-$mes-15"));
        
        // Social (Cat 17)
        if (rand(0, 1)) {
            gerarLancamento($pdo, $igrejaId, 17, 8, 'saida', 'Compra de Cestas Básicas', rand(300, 800), new DateTime("$ano-$mes-20"));
        }
    }
    echo "Ano $ano concluído...<br>";
}

/**
 * Função para inserir em todas as tabelas relacionadas (Conta, Pagamento e Movimentação)
 */
function gerarLancamento($pdo, $igrejaId, $catId, $subId, $tipo, $desc, $valor, $dataObj) {
    $dataStr = $dataObj->format('Y-m-d');
    $dataHoraStr = $dataObj->format('Y-m-d H:i:s');
    $contaFinanceiraId = ($tipo == 'entrada') ? 1 : rand(1, 2); // Entradas no banco, Saídas banco ou caixa

    // 1. Inserir em financeiro_contas
    $sqlConta = "INSERT INTO financeiro_contas 
        (financeiro_conta_igreja_id, financeiro_conta_financeiro_categoria_id, financeiro_conta_descricao, 
         financeiro_conta_valor, financeiro_conta_tipo, financeiro_conta_data_vencimento, 
         financeiro_conta_pago, financeiro_conta_data_pagamento) 
        VALUES (?, ?, ?, ?, ?, ?, 1, ?)";
    
    $stmt = $pdo->prepare($sqlConta);
    $stmt->execute([$igrejaId, $catId, $desc, $valor, $tipo, $dataStr, $dataStr]);
    $contaId = $pdo->lastInsertId();

    // 2. Inserir em financeiro_pagamentos (Pois está como PAGO)
    $metodo = ($tipo == 'entrada') ? 'pix' : 'transferencia';
    $sqlPag = "INSERT INTO financeiro_pagamentos 
        (financeiro_pagamento_igreja_id, financeiro_pagamento_financeiro_conta_id, 
         financeiro_pagamento_valor, financeiro_pagamento_conta_financeira_id, 
         financeiro_pagamento_metodo, financeiro_pagamento_data) 
        VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sqlPag);
    $stmt->execute([$igrejaId, $contaId, $valor, $contaFinanceiraId, $metodo, $dataHoraStr]);

    // 3. Inserir em financeiro_movimentacoes (O extrato real)
    $sqlMov = "INSERT INTO financeiro_movimentacoes 
        (financeiro_movimentacao_igreja_id, financeiro_movimentacao_financeiro_conta_id, 
         financeiro_movimentacao_financeiro_categoria_id, financeiro_movimentacao_financeiro_conta_financeira_id, 
         financeiro_movimentacao_tipo, financeiro_movimentacao_valor, financeiro_movimentacao_data, 
         financeiro_movimentacao_descricao, financeiro_movimentacao_origem) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pagamento')";
    
    $stmt = $pdo->prepare($sqlMov);
    $stmt->execute([$igrejaId, $contaId, $catId, $contaFinanceiraId, $tipo, $valor, $dataHoraStr, $desc]);
}

echo "--- PROCESSO FINALIZADO COM SUCESSO ---";
