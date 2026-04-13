<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class DizimoOferta
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Busca lançamentos do dia que foram conferidos pela dupla logada
     */
    public function getLancamentosDoDia($igrejaId, $diacono1, $diacono2)
    {
        $stmt = $this->db->prepare("
            SELECT fc.*, cat.financeiro_categoria_nome as categoria_nome
            FROM financeiro_contas fc
            LEFT JOIN financeiro_categorias cat ON fc.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
            WHERE fc.financeiro_conta_igreja_id = ?
            AND DATE(fc.financeiro_conta_data_cadastro) = CURDATE()
            AND fc.conferido_por_1 = ?
            AND fc.conferido_por_2 = ?
            ORDER BY fc.financeiro_conta_id DESC
        ");
        $stmt->execute([$igrejaId, $diacono1, $diacono2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Salva o lançamento vinculado à dupla de conferência
     */
    public function salvar($data)
    {
        $sql = "INSERT INTO financeiro_contas (
                    financeiro_conta_igreja_id,
                    financeiro_conta_financeiro_categoria_id,
                    financeiro_conta_descricao,
                    financeiro_conta_valor,
                    financeiro_conta_data_pagamento,
                    financeiro_conta_tipo,
                    financeiro_conta_pago,
                    conferido_por_1,
                    conferido_por_2
                ) VALUES (?, ?, ?, ?, ?, 'entrada', 1, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['igreja_id'],
            $data['categoria_id'],
            $data['descricao'],
            $data['valor'],
            date('Y-m-d'),
            $data['diacono_1'],
            $data['diacono_2']
        ]);
    }
}
