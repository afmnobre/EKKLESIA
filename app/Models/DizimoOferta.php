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
     * Tenta autenticar um oficial (Pastor, Presbítero, Diácono ou Tesoureiro)
     */
	public function autenticarOficial($usuario, $senha, $igrejaId)
	{
		// IDs dos cargos permitidos
		$cargosPermitidos = [1, 2, 4, 5, 6, 7, 11];
		$inQuery = implode(',', $cargosPermitidos);

		// Adicionamos m.membro_registro_interno e f.membro_foto_arquivo
		// Usamos LEFT JOIN com membros_fotos para não barrar o login caso o membro não tenha foto
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_senha,
					m.membro_registro_interno,
					f.membro_foto_arquivo
				FROM membros m
				INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				WHERE (m.membro_email = ? OR m.membro_cpf = ?)
				AND m.membro_igreja_id = ?
				AND v.vinculo_cargo_id IN ($inQuery)
				AND m.membro_status = 'Ativo'
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$usuario, $usuario, $igrejaId]);
		$membro = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($membro && password_verify($senha, $membro['membro_senha'])) {
			// Retornamos o array completo com nome, registro e foto para a sessão
			return $membro;
		}

		return false;
	}

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
                    financeiro_conta_data_cadastro,
                    conferido_por_1,
                    conferido_por_2
                ) VALUES (?, ?, ?, ?, ?, 'entrada', 1, NOW(), ?, ?)";

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

	public function getLancamentosPorPeriodo($igrejaId, $mes, $ano)
	{
		$stmt = $this->db->prepare("
			SELECT
				fc.*,
				COALESCE(sub.subcategoria_nome, cat.financeiro_categoria_nome) AS financeiro_categoria_nome
			FROM financeiro_contas fc
			-- Tenta buscar na tabela de categorias (comportamento antigo)
			LEFT JOIN financeiro_categorias cat
				ON fc.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
			-- Tenta buscar na tabela de subcategorias (comportamento novo)
			LEFT JOIN financeiro_subcategorias sub
				ON fc.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
			WHERE fc.financeiro_conta_igreja_id = ?
			AND fc.financeiro_conta_tipo = 'entrada'
			AND MONTH(fc.financeiro_conta_data_pagamento) = ?
			AND YEAR(fc.financeiro_conta_data_pagamento) = ?
			ORDER BY fc.financeiro_conta_data_pagamento DESC
		");
		$stmt->execute([$igrejaId, $mes, $ano]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getLancamentosDoDia($igrejaId, $d1, $d2)
	{
		// Alterado de financeiro_conta_data_cadastro para financeiro_conta_data_pagamento
		$stmt = $this->db->prepare("
			SELECT fc.*, cat.financeiro_categoria_nome as categoria_nome
			FROM financeiro_contas fc
			LEFT JOIN financeiro_categorias cat ON fc.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
			WHERE fc.financeiro_conta_igreja_id = ?
			AND fc.financeiro_conta_data_pagamento = CURDATE()
			AND fc.conferido_por_1 = ?
			AND fc.conferido_por_2 = ?
			ORDER BY fc.financeiro_conta_id DESC
		");
		$stmt->execute([$igrejaId, $d1, $d2]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCategoriasSubcategoriasReceita($igrejaId)
	{
		$sql = "SELECT
					c.financeiro_categoria_id,
					c.financeiro_categoria_nome,
					s.subcategoria_id,
					s.subcategoria_nome
				FROM financeiro_categorias c
				INNER JOIN financeiro_subcategorias s ON c.financeiro_categoria_id = s.subcategoria_categoria_id
				WHERE c.financeiro_categoria_tipo = 'entrada'
				AND c.financeiro_categoria_igreja_id = ?
				ORDER BY c.financeiro_categoria_nome ASC, s.subcategoria_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getMembrosAtivos($igrejaId)
	{
		$sql = "SELECT membro_id, membro_nome FROM membros
				WHERE membro_igreja_id = ? AND membro_status = 'Ativo'
				ORDER BY membro_nome ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getContasFinanceiras($igrejaId)
	{
		$sql = "SELECT financeiro_conta_financeira_id as id, financeiro_conta_financeira_nome as nome
				FROM financeiro_contas_financeiras
				WHERE financeiro_conta_financeira_igreja_id = ?
				AND financeiro_conta_financeira_status = 'ativo'
				ORDER BY financeiro_conta_financeira_nome ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getResumoConferencia($igrejaId, $data)
	{
		$sql = "SELECT sub.subcategoria_nome as nome, SUM(fc.financeiro_conta_valor) as total
				FROM financeiro_contas fc
				JOIN financeiro_subcategorias sub ON fc.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
				WHERE fc.financeiro_conta_igreja_id = ?
				AND fc.financeiro_conta_data_pagamento = ?
				GROUP BY sub.subcategoria_id, sub.subcategoria_nome";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $data]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getRateioConferencia($igrejaId, $data)
	{
		// Usamos LIKE para pegar qualquer registro do dia, independente da hora
		$dataBusca = $data . '%';

		$sql = "SELECT m.membro_nome, sub.subcategoria_nome, rm.receita_membro_valor as valor
				FROM financeiro_receita_membros rm
				JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				JOIN financeiro_subcategorias sub ON rm.receita_membro_subcategoria_id = sub.subcategoria_id
				WHERE rm.receita_membro_data LIKE ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$dataBusca]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

    // No seu App/Models/DizimoOferta.php, altere o final do método para:
	public function salvarLancamentoCompleto($data) {
		try {
			if (!$this->db->inTransaction()) {
				$this->db->beginTransaction();
			}

			// --- 0. FUNÇÃO INTERNA PARA LIMPEZA DE VALORES MONETÁRIOS ---
			$limparValor = function($valor) {
				if (empty($valor)) return 0.00;
				// Se o valor contiver vírgula, tratamos como formato brasileiro
				if (strpos($valor, ',') !== false) {
					$valor = str_replace('.', '', $valor); // Remove ponto de milhar
					$valor = str_replace(',', '.', $valor); // Troca vírgula por ponto decimal
				}
				return (float) $valor;
			};

			// --- 1. PREPARAÇÃO DOS DADOS ---
			$data_pagamento_completa = $data['data_pagamento'] . ' ' . date('H:i:s');
			$valorPrincipal = $limparValor($data['valor']);

			// --- 2. INSERT NA financeiro_contas ---
			$sql1 = "INSERT INTO financeiro_contas (
						financeiro_conta_igreja_id,
						financeiro_conta_financeiro_categoria_id,
						financeiro_conta_descricao,
						financeiro_conta_valor,
						financeiro_conta_tipo,
						financeiro_conta_data_vencimento,
						financeiro_conta_pago,
						financeiro_conta_data_pagamento,
						conferido_por_1,
						conferido_por_2
					) VALUES (?, ?, ?, ?, 'entrada', ?, 1, ?, ?, ?)";

			$stmt1 = $this->db->prepare($sql1);
			$stmt1->execute([
				$data['igreja_id'],
				$data['categoria_id'], // ID da Subcategoria para o Admin ler corretamente
				$data['descricao'],
				$valorPrincipal,
				$data['data_pagamento'], // vencimento
				$data['data_pagamento'], // pagamento
				$data['diacono_1'],
				$data['diacono_2']
			]);

			$contaId = $this->db->lastInsertId();

			// --- 3. ATUALIZA SALDO BANCÁRIO ---
			$sql2 = "UPDATE financeiro_contas_financeiras
					 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo + ?
					 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
			$this->db->prepare($sql2)->execute([$valorPrincipal, $data['conta_financeira_id'], $data['igreja_id']]);

			// --- 4. MOVIMENTAÇÃO NO EXTRATO (financeiro_movimentacoes) ---
			$desc = "Receita (Conferência): " . $data['descricao'];
			$sql3 = "INSERT INTO financeiro_movimentacoes (
						financeiro_movimentacao_igreja_id,
						financeiro_movimentacao_financeiro_conta_id,
						financeiro_movimentacao_financeiro_categoria_id,
						financeiro_movimentacao_financeiro_conta_financeira_id,
						financeiro_movimentacao_tipo,
						financeiro_movimentacao_valor,
						financeiro_movimentacao_data,
						financeiro_movimentacao_descricao,
						financeiro_movimentacao_origem
					) VALUES (?, ?, ?, ?, 'entrada', ?, ?, ?, 'pagamento')";

			$this->db->prepare($sql3)->execute([
				$data['igreja_id'],
				$contaId,
				$data['categoria_id'],
				$data['conta_financeira_id'],
				$valorPrincipal,
				$data_pagamento_completa,
				$desc
			]);

			// --- 5. RATEIO DE MEMBROS ---
			if (!empty($data['rateio_membros'])) {
				$sql4 = "INSERT INTO financeiro_receita_membros (
							receita_membro_conta_id,
							receita_membro_categoria_id,
							receita_membro_subcategoria_id,
							receita_membro_usuario_id,
							receita_membro_valor,
							receita_membro_data
						) VALUES (?, ?, ?, ?, ?, ?)";
				$stmt4 = $this->db->prepare($sql4);

				foreach ($data['rateio_membros'] as $index => $membroId) {
					if (empty($membroId)) continue;

					$valorMembro = $limparValor($data['rateio_valores'][$index] ?? 0);

					if ($valorMembro > 0) {
						$stmt4->execute([
							$contaId,
							$data['categoria_pai_id'], // Categoria Pai (Ex: 18)
							$data['subcategoria_id'],  // Subcategoria (Ex: 13)
							$membroId,
							$valorMembro,
							$data_pagamento_completa
						]);
					}
				}
			}

			$this->db->commit();
			return true;

		} catch (\Exception $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			error_log("ERRO SQL DIZIMO/OFERTA: " . $e->getMessage());
			return false;
		}
	}

	public function getTesoureiroIgreja($igrejaId)
	{
		$sql = "SELECT m.membro_nome
				FROM membros m
				JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				WHERE m.membro_igreja_id = ?
				AND v.vinculo_cargo_id = 11
				AND m.membro_status = 'ativo'
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

		return $resultado ? $resultado['membro_nome'] : "Tesouraria (Nome não configurado)";
	}

	public function getIgrejaDetalhes($igrejaId)
	{
		$sql = "SELECT igreja_id, igreja_nome, igreja_endereco, igreja_logo
				FROM igrejas
				WHERE igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}


}
