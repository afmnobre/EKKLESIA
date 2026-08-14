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
				COALESCE(sub.subcategoria_nome, cat.financeiro_categoria_nome) AS financeiro_categoria_nome,
				fm.financeiro_movimentacao_data
			FROM financeiro_contas fc
			-- Tenta buscar na tabela de categorias (comportamento antigo)
			LEFT JOIN financeiro_categorias cat
				ON fc.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
			-- Tenta buscar na tabela de subcategorias (comportamento novo)
			LEFT JOIN financeiro_subcategorias sub
				ON fc.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
			-- Busca a data/hora exata em que o lançamento foi realizado
			LEFT JOIN (
				SELECT
					financeiro_movimentacao_financeiro_conta_id,
					MIN(financeiro_movimentacao_data) AS financeiro_movimentacao_data
				FROM financeiro_movimentacoes
				GROUP BY financeiro_movimentacao_financeiro_conta_id
			) fm ON fc.financeiro_conta_id = fm.financeiro_movimentacao_financeiro_conta_id
			WHERE fc.financeiro_conta_igreja_id = ?
			AND fc.financeiro_conta_tipo = 'entrada'
			AND MONTH(fc.financeiro_conta_data_pagamento) = ?
			AND YEAR(fc.financeiro_conta_data_pagamento) = ?
			ORDER BY fc.financeiro_conta_data_pagamento DESC, fc.financeiro_conta_id DESC
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
		// Adicionamos o filtro para pegar apenas as ENTRADAS
		$sql = "SELECT sub.subcategoria_nome as nome, SUM(fc.financeiro_conta_valor) as total
				FROM financeiro_contas fc
				JOIN financeiro_subcategorias sub ON fc.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
				WHERE fc.financeiro_conta_igreja_id = ?
				AND fc.financeiro_conta_data_pagamento = ?
				AND fc.financeiro_conta_tipo = 'entrada'
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

	// Arquivo: App/Models/DizimoOferta.php
	// Linha: Localize o método salvarLancamentoCompleto (ou inclua-o na classe DizimoOferta)

	public function salvarLancamentoCompleto($data) {
		try {
			if (!$this->db->inTransaction()) {
				$this->db->beginTransaction();
			}

			// Função auxiliar interna para converter string '1.234,56' ou número para float
			$limparValor = function($valor) {
				if (empty($valor)) return 0.00;
				if (is_numeric($valor)) return (float) $valor;
				if (strpos($valor, ',') !== false) {
					$valor = str_replace('.', '', $valor);
					$valor = str_replace(',', '.', $valor);
				}
				return (float) $valor;
			};

			$valorTotal = $limparValor($data['valor']);
			$data_pagamento_completa = $data['data_pagamento'] . ' ' . date('H:i:s');

			// --- 1. INSERIR CONTA FINANCEIRA (PAGAMENTO) ---
			$sqlConta = "INSERT INTO financeiro_contas (
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

			$stmt = $this->db->prepare($sqlConta);
			$stmt->execute([
				$data['igreja_id'],
				$data['subcategoria_id'] ?? $data['categoria_id'],
				$data['descricao'] ?? 'Lançamento em Lote',
				$valorTotal,
				$data['data_pagamento'],
				$data['data_pagamento'],
				$data['diacono_1'],
				$data['diacono_2']
			]);

			$contaId = $this->db->lastInsertId();

			// --- 2. ATUALIZAR SALDO DA CONTA BANCÁRIA / CAIXA ---
			if (!empty($data['conta_financeira_id'])) {
				$sqlSaldo = "UPDATE financeiro_contas_financeiras
							 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo + ?
							 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
				$this->db->prepare($sqlSaldo)->execute([
					$valorTotal,
					$data['conta_financeira_id'],
					$data['igreja_id']
				]);

				// --- 3. INSERIR MOVIMENTAÇÃO FINANCEIRA ---
				$sqlMov = "INSERT INTO financeiro_movimentacoes (
							financeiro_movimentacao_igreja_id,
							financeiro_movimentacao_financeiro_conta_id,
							financeiro_movimentacao_financeiro_conta_financeira_id,
							financeiro_movimentacao_tipo,
							financeiro_movimentacao_valor,
							financeiro_movimentacao_data,
							financeiro_movimentacao_descricao,
							financeiro_movimentacao_origem
						) VALUES (?, ?, ?, 'entrada', ?, ?, ?, 'pagamento')";

				$this->db->prepare($sqlMov)->execute([
					$data['igreja_id'],
					$contaId,
					$data['conta_financeira_id'],
					$valorTotal,
					$data_pagamento_completa,
					$data['descricao'] ?? 'Lançamento em Lote'
				]);
			}

			// --- 4. INSERIR O RATEIO DOS MEMBROS ---
			if (!empty($data['rateio_membros']) && is_array($data['rateio_membros'])) {
				$sqlMembro = "INSERT INTO financeiro_receita_membros (
								receita_membro_conta_id,
								receita_membro_subcategoria_id,
								receita_membro_usuario_id,
								receita_membro_valor,
								receita_membro_data
							) VALUES (?, ?, ?, ?, ?)";

				$stmtMembro = $this->db->prepare($sqlMembro);

				foreach ($data['rateio_membros'] as $index => $membroId) {
					if (empty($membroId)) continue;

					$valMembro = isset($data['rateio_valores'][$index]) ? $limparValor($data['rateio_valores'][$index]) : 0;

					if ($valMembro > 0) {
						$stmtMembro->execute([
							$contaId,
							$data['subcategoria_id'] ?? $data['categoria_id'],
							$membroId,
							$valMembro,
							$data_pagamento_completa
						]);
					}
				}
			}

			// Se tudo ocorreu bem, confirma as alterações no banco de dados
			$this->db->commit();
			return true;

		} catch (\Exception $e) {
			// Se houver qualquer falha, cancela todas as inserções da transação
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			error_log("ERRO SALVAR LANÇAMENTO EM LOTE: " . $e->getMessage());
			return false;
		}
	}

	// Arquivo: App/Models/DizimoOferta.php
	// Linha: Localize o método salvarLancamentoIndividualCompleto

	public function salvarLancamentoIndividualCompleto($data) {
		try {
			if (!$this->db->inTransaction()) {
				$this->db->beginTransaction();
			}

			$limparValor = function($valor) {
				if (empty($valor)) return 0.00;
				if (is_numeric($valor)) return (float) $valor;
				if (strpos($valor, ',') !== false) {
					$valor = str_replace('.', '', $valor);
					$valor = str_replace(',', '.', $valor);
				}
				return (float) $valor;
			};

			$data_pagamento_completa = $data['data_pagamento'] . ' ' . date('H:i:s');
			$membroId = $data['membro_id'];

			// --- 1. PROCESSAR DÍZIMO (SE INFORMADO) ---
			$dizimoValor = $limparValor($data['dizimo_valor']);
			if ($dizimoValor > 0 && !empty($data['dizimo_conta_id'])) {
				// Insere Conta Financeira (Dízimo = Subcategoria ID 14)
				$sqlConta = "INSERT INTO financeiro_contas (
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

				$stmt = $this->db->prepare($sqlConta);
				$stmt->execute([
					$data['igreja_id'],
					$data['dizimo_subcategoria_id'], // Valor fixado em 14
					"Dízimo - Individual",
					$dizimoValor,
					$data['data_pagamento'],
					$data['data_pagamento'],
					$data['diacono_1'],
					$data['diacono_2']
				]);
				$contaIdDizimo = $this->db->lastInsertId();

				// Atualiza Saldo da Conta Bancária / Caixa
				$sqlSaldo = "UPDATE financeiro_contas_financeiras
							 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo + ?
							 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
				$this->db->prepare($sqlSaldo)->execute([$dizimoValor, $data['dizimo_conta_id'], $data['igreja_id']]);

				// Insere Movimentação
				$sqlMov = "INSERT INTO financeiro_movimentacoes (
							financeiro_movimentacao_igreja_id,
							financeiro_movimentacao_financeiro_conta_id,
							financeiro_movimentacao_financeiro_conta_financeira_id,
							financeiro_movimentacao_tipo,
							financeiro_movimentacao_valor,
							financeiro_movimentacao_data,
							financeiro_movimentacao_descricao,
							financeiro_movimentacao_origem
						) VALUES (?, ?, ?, 'entrada', ?, ?, 'Dízimo Individual', 'pagamento')";
				$this->db->prepare($sqlMov)->execute([
					$data['igreja_id'],
					$contaIdDizimo,
					$data['dizimo_conta_id'],
					$dizimoValor,
					$data_pagamento_completa
				]);

				// Vínculo com o Membro (Dízimo)
				$sqlMembro = "INSERT INTO financeiro_receita_membros (
								receita_membro_conta_id,
								receita_membro_subcategoria_id,
								receita_membro_usuario_id,
								receita_membro_valor,
								receita_membro_data
							) VALUES (?, ?, ?, ?, ?)";
				$this->db->prepare($sqlMembro)->execute([
					$contaIdDizimo,
					$data['dizimo_subcategoria_id'], // Valor fixado em 14
					$membroId,
					$dizimoValor,
					$data_pagamento_completa
				]);
			}

			// --- 2. PROCESSAR OFERTA (SE INFORMADA) ---
			$ofertaValor = $limparValor($data['oferta_valor']);
			if ($ofertaValor > 0 && !empty($data['oferta_conta_id'])) {
				$sqlConta = "INSERT INTO financeiro_contas (
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

				$stmt = $this->db->prepare($sqlConta);
				$stmt->execute([
					$data['igreja_id'],
					$data['oferta_subcategoria_id'],
					"Oferta - Individual",
					$ofertaValor,
					$data['data_pagamento'],
					$data['data_pagamento'],
					$data['diacono_1'],
					$data['diacono_2']
				]);
				$contaIdOferta = $this->db->lastInsertId();

				// Atualiza Saldo da Conta Bancária / Caixa
				$sqlSaldo = "UPDATE financeiro_contas_financeiras
							 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo + ?
							 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
				$this->db->prepare($sqlSaldo)->execute([$ofertaValor, $data['oferta_conta_id'], $data['igreja_id']]);

				// Insere Movimentação
				$sqlMov = "INSERT INTO financeiro_movimentacoes (
							financeiro_movimentacao_igreja_id,
							financeiro_movimentacao_financeiro_conta_id,
							financeiro_movimentacao_financeiro_conta_financeira_id,
							financeiro_movimentacao_tipo,
							financeiro_movimentacao_valor,
							financeiro_movimentacao_data,
							financeiro_movimentacao_descricao,
							financeiro_movimentacao_origem
						) VALUES (?, ?, ?, 'entrada', ?, ?, 'Oferta Individual', 'pagamento')";
				$this->db->prepare($sqlMov)->execute([
					$data['igreja_id'],
					$contaIdOferta,
					$data['oferta_conta_id'],
					$ofertaValor,
					$data_pagamento_completa
				]);

				// Vínculo com o Membro (Oferta)
				$sqlMembro = "INSERT INTO financeiro_receita_membros (
								receita_membro_conta_id,
								receita_membro_subcategoria_id,
								receita_membro_usuario_id,
								receita_membro_valor,
								receita_membro_data
							) VALUES (?, ?, ?, ?, ?)";
				$this->db->prepare($sqlMembro)->execute([
					$contaIdOferta,
					$data['oferta_subcategoria_id'],
					$membroId,
					$ofertaValor,
					$data_pagamento_completa
				]);
			}

			$this->db->commit();
			return true;

		} catch (\Exception $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			error_log("ERRO SALVAR INDIVIDUAL: " . $e->getMessage());
			return false;
		}
	}

	// Arquivo: App/Models/DizimoOferta.php
	// Linha: Localize o método excluirLancamento

	public function excluirLancamento($contaId, $igrejaId) {
		try {
			if (!$this->db->inTransaction()) {
				$this->db->beginTransaction();
			}

			// 1. Buscar os dados do lançamento e a data/hora exata da movimentação
			$sql = "SELECT c.financeiro_conta_id,
						   m.financeiro_movimentacao_data
					FROM financeiro_contas c
					LEFT JOIN financeiro_movimentacoes m
						   ON m.financeiro_movimentacao_financeiro_conta_id = c.financeiro_conta_id
					WHERE c.financeiro_conta_id = ? AND c.financeiro_conta_igreja_id = ?";
			$stmt = $this->db->prepare($sql);
			$stmt->execute([$contaId, $igrejaId]);
			$conta = $stmt->fetch(\PDO::FETCH_ASSOC);

			if (!$conta) {
				$this->db->rollBack();
				return ['success' => false, 'message' => 'Lançamento não encontrado.'];
			}

			// Validação das 24 horas usando financeiro_movimentacao_data
			$dataHoraLancamento = !empty($conta['financeiro_movimentacao_data']) ? $conta['financeiro_movimentacao_data'] : null;

			if ($dataHoraLancamento) {
				$dataCriacao = new \DateTime($dataHoraLancamento);
				$agora = new \DateTime();
				$diferencaHoras = ($agora->getTimestamp() - $dataCriacao->getTimestamp()) / 3600;

				if ($diferencaHoras > 24) {
					$this->db->rollBack();
					return ['success' => false, 'message' => 'Este lançamento tem mais de 24h e não pode ser excluído.'];
				}
			}

			// 2. Desfazer os saldos das contas bancárias
			$sqlMov = "SELECT financeiro_movimentacao_financeiro_conta_financeira_id, financeiro_movimentacao_valor
					   FROM financeiro_movimentacoes
					   WHERE financeiro_movimentacao_financeiro_conta_id = ? AND financeiro_movimentacao_igreja_id = ?";
			$stmtMov = $this->db->prepare($sqlMov);
			$stmtMov->execute([$contaId, $igrejaId]);
			$movimentacoes = $stmtMov->fetchAll(\PDO::FETCH_ASSOC);

			foreach ($movimentacoes as $mov) {
				if (!empty($mov['financeiro_movimentacao_financeiro_conta_financeira_id'])) {
					$sqlSaldo = "UPDATE financeiro_contas_financeiras
								 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo - ?
								 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
					$this->db->prepare($sqlSaldo)->execute([
						$mov['financeiro_movimentacao_valor'],
						$mov['financeiro_movimentacao_financeiro_conta_financeira_id'],
						$igrejaId
					]);
				}
			}

			// 3. Excluir rateio de membros
			$sqlDelMembros = "DELETE FROM financeiro_receita_membros WHERE receita_membro_conta_id = ?";
			$this->db->prepare($sqlDelMembros)->execute([$contaId]);

			// 4. Excluir movimentações
			$sqlDelMov = "DELETE FROM financeiro_movimentacoes WHERE financeiro_movimentacao_financeiro_conta_id = ? AND financeiro_movimentacao_igreja_id = ?";
			$this->db->prepare($sqlDelMov)->execute([$contaId, $igrejaId]);

			// 5. Excluir registro da conta
			$sqlDelConta = "DELETE FROM financeiro_contas WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?";
			$this->db->prepare($sqlDelConta)->execute([$contaId, $igrejaId]);

			$this->db->commit();
			return ['success' => true, 'message' => 'Lançamento excluído e saldo estornado com sucesso!'];

		} catch (\Exception $e) {
			if ($this->db->inTransaction()) {
				$this->db->rollBack();
			}
			error_log("ERRO EXCLUIR LANÇAMENTO: " . $e->getMessage());
			return ['success' => false, 'message' => 'Erro ao tentar excluir o lançamento.'];
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

	public function getMembrosRateio($contaId) {
		$sql = "SELECT
					rm.receita_membro_id,
					rm.receita_membro_valor,
					rm.receita_membro_comprovante, -- ESTA LINHA É ESSENCIAL
					m.membro_nome
				FROM financeiro_receita_membros rm
				INNER JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				WHERE rm.receita_membro_conta_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$contaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Atualiza o comprovante na tabela de contas (Geral)
	 */
	public function atualizarAnexoFinanceiro($contaId, $igrejaId, $tipo, $caminho) {
		$coluna = ($tipo === 'comprovante') ? 'financeiro_conta_comprovante' : 'financeiro_conta_nota_fiscal';
		$sql = "UPDATE financeiro_contas SET $coluna = ? WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?";
		return $this->db->prepare($sql)->execute([$caminho, $contaId, $igrejaId]);
	}

	/**
	 * Atualiza o comprovante na tabela de membros (Rateio)
	 */
	public function atualizarComprovanteRateio($id, $caminho) {
		$stmt = $this->db->prepare("UPDATE financeiro_receita_membros SET receita_membro_comprovante = ? WHERE receita_membro_id = ?");
		return $stmt->execute([$caminho, $id]);
	}

	public function getContaById($id, $igrejaId) {
		$stmt = $this->db->prepare("SELECT * FROM financeiro_contas WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?");
		$stmt->execute([$id, $igrejaId]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function getRateioById($id) {
		$stmt = $this->db->prepare("SELECT * FROM financeiro_receita_membros WHERE receita_membro_id = ?");
		$stmt->execute([$id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Arquivo: App/Models/DizimoOferta.php

	/**
	 * Busca todas as movimentações e lançamentos do dia especificamente para a conferência
	 */
	public function getDetalhamentoRelatorioConferencia($igrejaId, $data)
	{
		$sql = "SELECT
					fc.financeiro_conta_id,
					fc.financeiro_conta_descricao,
					fc.financeiro_conta_valor,
					COALESCE(sub.subcategoria_nome, cat.financeiro_categoria_nome, 'Outros') AS tipo_receita,
					COALESCE(cf.financeiro_conta_financeira_nome, 'Em Espécie') AS conta_nome,
					cf.financeiro_conta_financeira_id,
					cf.financeiro_conta_financeira_tipo,
					rm.receita_membro_valor,
					COALESCE(m.membro_nome, 'Não Identificado / Avulso') AS contribuinte_nome
				FROM financeiro_contas fc
				LEFT JOIN financeiro_categorias cat ON fc.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
				LEFT JOIN financeiro_receita_membros rm ON fc.financeiro_conta_id = rm.receita_membro_conta_id
				LEFT JOIN financeiro_subcategorias sub ON rm.receita_membro_subcategoria_id = sub.subcategoria_id
				LEFT JOIN financeiro_movimentacoes fm ON fc.financeiro_conta_id = fm.financeiro_movimentacao_financeiro_conta_id
				LEFT JOIN financeiro_contas_financeiras cf ON fm.financeiro_movimentacao_financeiro_conta_financeira_id = cf.financeiro_conta_financeira_id
				LEFT JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				WHERE fc.financeiro_conta_igreja_id = ?
				  AND fc.financeiro_conta_data_pagamento = ?
				  AND fc.financeiro_conta_tipo = 'entrada'
				ORDER BY tipo_receita ASC, contribuinte_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $data]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


    /**
	 * Busca o resumo contábil de entradas por modalidade/subcategoria em uma determinada data
	 */
    public function getResumoContabilModalidades($igrejaId, $dataInicio, $dataFim)
    {
        $sql = "SELECT
                    s.subcategoria_id AS id,
                    c.financeiro_categoria_nome,
                    s.subcategoria_nome,
                    CONCAT(c.financeiro_categoria_nome, ' - ', s.subcategoria_nome) AS nome,
                    SUM(fc.financeiro_conta_valor) AS valor
                FROM financeiro_contas fc
                INNER JOIN financeiro_subcategorias s
                    ON s.subcategoria_id = fc.financeiro_conta_financeiro_categoria_id
                INNER JOIN financeiro_categorias c
                    ON c.financeiro_categoria_id = s.subcategoria_categoria_id
                WHERE fc.financeiro_conta_igreja_id = :igreja_id
                  AND fc.financeiro_conta_tipo = 'entrada'
                  AND fc.financeiro_conta_pago = 1
                  AND fc.financeiro_conta_data_pagamento BETWEEN :data_inicio AND :data_fim
                GROUP BY s.subcategoria_id, c.financeiro_categoria_id
                ORDER BY c.financeiro_categoria_nome ASC, s.subcategoria_nome ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':igreja_id'   => $igrejaId,
            ':data_inicio' => $dataInicio,
            ':data_fim'    => $dataFim
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

	public function getRelatorioContabil($dataInicio, $dataFim)
	{
		$igrejaId = $_SESSION['igreja_id'] ?? null;

		$sql = "SELECT
					c.financeiro_categoria_nome AS categoria,
					m.financeiro_movimentacao_tipo AS tipo,
					cf.financeiro_conta_financeira_nome AS conta_financeira,
					COALESCE(SUM(m.financeiro_movimentacao_valor), 0) AS total
				FROM financeiro_movimentacoes m
				INNER JOIN financeiro_categorias c
					ON c.financeiro_categoria_id = m.financeiro_movimentacao_categoria_id
				LEFT JOIN financeiro_contas_financeiras cf
					ON cf.financeiro_conta_financeira_id = m.financeiro_movimentacao_financeiro_conta_financeira_id
				WHERE m.financeiro_movimentacao_igreja_id = :igreja_id
				  AND m.financeiro_movimentacao_data BETWEEN :data_inicio AND :data_fim
				GROUP BY
					c.financeiro_categoria_nome,
					m.financeiro_movimentacao_tipo,
					cf.financeiro_conta_financeira_nome
				ORDER BY c.financeiro_categoria_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':igreja_id', $igrejaId);
		$stmt->bindValue(':data_inicio', $dataInicio);
		$stmt->bindValue(':data_fim', $dataFim);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


}
