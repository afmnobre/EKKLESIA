<?php
namespace App\Models;
use App\Core\Database;

class Financeiro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Retorna as contas (Caixa, Banco, etc) com seus saldos
    public function getContasFinanceiras($igrejaId) {
        $stmt = $this->db->prepare("SELECT * FROM financeiro_contas_financeiras WHERE financeiro_conta_financeira_igreja_id = ?");
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Processa transferência entre contas (Ex: Caixa -> Banco)
	public function transferir($igrejaId, $origemId, $destinoId, $valor, $descricao) {
		try {
			$this->db->beginTransaction();

			// 1. ATUALIZAÇÃO DOS SALDOS REAIS
			$stmt = $this->db->prepare("UPDATE financeiro_contas_financeiras SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo - ? WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?");
			$stmt->execute([$valor, $origemId, $igrejaId]);

			$stmt = $this->db->prepare("UPDATE financeiro_contas_financeiras SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo + ? WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?");
			$stmt->execute([$valor, $destinoId, $igrejaId]);

			// 2. REGISTRO DAS MOVIMENTAÇÕES (EXTRATO)
			// Note que mudamos 'saida' por ? no VALUES
			$sqlMov = "INSERT INTO financeiro_movimentacoes
					   (financeiro_movimentacao_igreja_id, financeiro_movimentacao_financeiro_conta_financeira_id,
						financeiro_movimentacao_tipo, financeiro_movimentacao_valor, financeiro_movimentacao_data,
						financeiro_movimentacao_descricao, financeiro_movimentacao_origem)
					   VALUES (?, ?, ?, ?, NOW(), ?, 'transferencia')";

			// Execução para a SAÍDA
			$this->db->prepare($sqlMov)->execute([
				$igrejaId, $origemId, 'saida', $valor, "Transferência enviada: " . $descricao
			]);

			// Execução para a ENTRADA
			$this->db->prepare($sqlMov)->execute([
				$igrejaId, $destinoId, 'entrada', $valor, "Transferência recebida: " . $descricao
			]);

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	// Listar categorias filtradas por tipo ou todas
	public function getCategorias($igrejaId, $tipo = null) {
		$sql = "SELECT * FROM financeiro_categorias WHERE financeiro_categoria_igreja_id = ?";
		$params = [$igrejaId];

		if ($tipo) {
			$sql .= " AND financeiro_categoria_tipo = ?";
			$params[] = $tipo;
		}

		$sql .= " ORDER BY financeiro_categoria_nome ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Salvar ou Atualizar Categoria
	public function salvarCategoria($data) {
		if (!empty($data['id'])) {
			$sql = "UPDATE financeiro_categorias SET financeiro_categoria_nome = ?, financeiro_categoria_tipo = ?
					WHERE financeiro_categoria_id = ? AND financeiro_categoria_igreja_id = ?";
			return $this->db->prepare($sql)->execute([$data['nome'], $data['tipo'], $data['id'], $data['igreja_id']]);
		} else {
			$sql = "INSERT INTO financeiro_categorias (financeiro_categoria_igreja_id, financeiro_categoria_nome, financeiro_categoria_tipo) VALUES (?, ?, ?)";
			return $this->db->prepare($sql)->execute([$data['igreja_id'], $data['nome'], $data['tipo']]);
		}
	}

	// Eliminar Categoria
	public function excluirCategoria($id, $igrejaId) {
		$sql = "DELETE FROM financeiro_categorias WHERE financeiro_categoria_id = ? AND financeiro_categoria_igreja_id = ?";
		return $this->db->prepare($sql)->execute([$id, $igrejaId]);
	}

	// Salvar ou Atualizar Conta Financeira
	public function salvarContaFinanceira($data) {
		if (!empty($data['id'])) {
			$sql = "UPDATE financeiro_contas_financeiras
					SET financeiro_conta_financeira_nome = ?,
						financeiro_conta_financeira_tipo = ?,
						financeiro_conta_financeira_status = ?
					WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
			return $this->db->prepare($sql)->execute([
				$data['nome'], $data['tipo'], $data['status'], $data['id'], $data['igreja_id']
			]);
		} else {
			$sql = "INSERT INTO financeiro_contas_financeiras
					(financeiro_conta_financeira_igreja_id, financeiro_conta_financeira_nome, financeiro_conta_financeira_tipo, financeiro_conta_financeira_saldo, financeiro_conta_financeira_status)
					VALUES (?, ?, ?, ?, ?)";
			return $this->db->prepare($sql)->execute([
				$data['igreja_id'], $data['nome'], $data['tipo'], $data['saldo_inicial'], $data['status']
			]);
		}
	}

	// Excluir Conta (Apenas se não houver movimentações vinculadas)
	public function excluirContaFinanceira($id, $igrejaId) {
		// Verificação de segurança para não apagar contas com histórico
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM financeiro_movimentacoes WHERE financeiro_movimentacao_financeiro_conta_financeira_id = ?");
		$stmt->execute([$id]);
		if ($stmt->fetchColumn() > 0) return false;

		$sql = "DELETE FROM financeiro_contas_financeiras WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
		return $this->db->prepare($sql)->execute([$id, $igrejaId]);
	}

	public function salvarConta($data) {
		if (!empty($data['id'])) {
			$sql = "UPDATE financeiro_contas SET
					financeiro_conta_financeiro_categoria_id = ?,
					financeiro_conta_descricao = ?,
					financeiro_conta_valor = ?,
					financeiro_conta_data_vencimento = ?,
					financeiro_conta_pago = ?,
					financeiro_conta_reembolso = ?
					WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?";
			return $this->db->prepare($sql)->execute([
				$data['categoria_id'],
				$data['descricao'],
				$data['valor'],
				$data['vencimento'],
				$data['pago'],
				$data['reembolso'], // Novo campo
				$data['id'],
				$data['igreja_id']
			]);
		} else {
			$sql = "INSERT INTO financeiro_contas
					(financeiro_conta_igreja_id, financeiro_conta_financeiro_categoria_id, financeiro_conta_descricao,
					 financeiro_conta_valor, financeiro_conta_tipo, financeiro_conta_data_vencimento,
					 financeiro_conta_pago, financeiro_conta_reembolso)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			return $this->db->prepare($sql)->execute([
				$data['igreja_id'],
				$data['categoria_id'],
				$data['descricao'],
				$data['valor'],
				$data['tipo'],
				$data['vencimento'],
				$data['pago'],
				$data['reembolso'] // Novo campo
			]);
		}
	}

	public function getTesoureiro($igrejaId) {
		$sql = "SELECT m.membro_nome
				FROM membros m
				JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				WHERE m.membro_igreja_id = ?
				AND v.vinculo_cargo_id = 11
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

		return $resultado ? $resultado['membro_nome'] : "Tesouraria";
	}

	public function getDadosIgreja($igrejaId) {
		$sql = "SELECT * FROM igrejas WHERE igreja_id = ?"; // Ajuste o nome da tabela/coluna se for diferente
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getContaById($id, $igrejaId) {
		$sql = "SELECT
					c.*,
					cat.financeiro_categoria_nome,
					sub.subcategoria_nome,
					-- Este alias precisa ser EXATAMENTE igual ao que o JS procura
					m.financeiro_movimentacao_financeiro_conta_financeira_id as financeiro_conta_financeira_id
				FROM financeiro_contas c
				LEFT JOIN financeiro_categorias cat ON c.financeiro_conta_financeiro_categoria_id = cat.financeiro_categoria_id
				LEFT JOIN financeiro_subcategorias sub ON c.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
				LEFT JOIN financeiro_movimentacoes m ON m.financeiro_movimentacao_financeiro_conta_id = c.financeiro_conta_id
					AND m.financeiro_movimentacao_origem = 'pagamento'
				WHERE c.financeiro_conta_id = ? AND c.financeiro_conta_igreja_id = ?
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id, $igrejaId]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	// Busca os lançamentos filtrados por mês e ano
	public function getContasAgendadas($igrejaId, $mes, $ano) {
		$sql = "SELECT
					fc.*,
					fc.financeiro_conta_financeiro_categoria_id AS subcategoria_id,
					cat.financeiro_categoria_id AS categoria_pai_id,
					cat.financeiro_categoria_nome,
					sub.subcategoria_nome
				FROM financeiro_contas fc
				-- Aqui ligamos a conta à subcategoria
				LEFT JOIN financeiro_subcategorias sub ON fc.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
				-- Aqui subimos para a categoria pai para pegar o ID dela
				LEFT JOIN financeiro_categorias cat ON sub.subcategoria_categoria_id = cat.financeiro_categoria_id
				WHERE fc.financeiro_conta_igreja_id = ?
				AND MONTH(fc.financeiro_conta_data_vencimento) = ?
				AND YEAR(fc.financeiro_conta_data_vencimento) = ?
				ORDER BY fc.financeiro_conta_data_vencimento DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $mes, $ano]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getUltimasMovimentacoes($igrejaId, $limite = 10) {
		$sql = "SELECT m.*, c.financeiro_conta_financeira_nome
				FROM financeiro_movimentacoes m
				JOIN financeiro_contas_financeiras c ON m.financeiro_movimentacao_financeiro_conta_financeira_id = c.financeiro_conta_financeira_id
				WHERE m.financeiro_movimentacao_igreja_id = ?
				ORDER BY m.financeiro_movimentacao_data DESC
				LIMIT ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $limite]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

	public function processarBaixa($data) {
		try {
			$this->db->beginTransaction();

			// --- CORREÇÃO DO HORÁRIO ---
			$data_pagamento_completa = $data['data_pagamento'];
			if (strlen($data_pagamento_completa) <= 10) {
				$data_pagamento_completa .= ' ' . date('H:i:s');
			}

			// 0. BUSCAR DADOS DA CONTA (Adicionado categoria_id no SELECT)
			$stmtTipo = $this->db->prepare("SELECT financeiro_conta_tipo, financeiro_conta_descricao, financeiro_conta_financeiro_categoria_id
											FROM financeiro_contas
											WHERE financeiro_conta_id = ?");
			$stmtTipo->execute([$data['conta_id']]);
			$infoConta = $stmtTipo->fetch(\PDO::FETCH_ASSOC);

			$tipo = $infoConta['financeiro_conta_tipo'];
			$descricaoOriginal = $infoConta['financeiro_conta_descricao'];
			$categoriaId = $infoConta['financeiro_conta_financeiro_categoria_id']; // <--- CAPTURADO AQUI

			// 1. Atualiza o status da conta agendada
			$sql1 = "UPDATE financeiro_contas SET financeiro_conta_pago = 1, financeiro_conta_data_pagamento = ?
					 WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?";
			$this->db->prepare($sql1)->execute([$data_pagamento_completa, $data['conta_id'], $data['igreja_id']]);

			// 2. Registra o Pagamento Detalhado
			$sql2 = "INSERT INTO financeiro_pagamentos
					 (financeiro_pagamento_igreja_id, financeiro_pagamento_financeiro_conta_id, financeiro_pagamento_valor,
					  financeiro_pagamento_conta_financeira_id, financeiro_pagamento_documentos, financeiro_pagamento_data)
					 VALUES (?, ?, ?, ?, ?, ?)";
			$this->db->prepare($sql2)->execute([
				$data['igreja_id'], $data['conta_id'], $data['valor'],
				$data['conta_financeira_id'], $data['comprovante'], $data_pagamento_completa
			]);

			// 3. ATUALIZAÇÃO DO SALDO
			$operador = ($tipo == 'entrada') ? '+' : '-';
			$sql3 = "UPDATE financeiro_contas_financeiras
					 SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo $operador ?
					 WHERE financeiro_conta_financeira_id = ? AND financeiro_conta_financeira_igreja_id = ?";
			$this->db->prepare($sql3)->execute([$data['valor'], $data['conta_financeira_id'], $data['igreja_id']]);

			// 4. Cria o Registro no Extrato (ADICIONADO OS CAMPOS DE LINK)
			$desc = "Baixa de " . ($tipo == 'entrada' ? 'Receita' : 'Despesa') . ": " . $descricaoOriginal;
			$sql4 = "INSERT INTO financeiro_movimentacoes
					 (financeiro_movimentacao_igreja_id,
					  financeiro_movimentacao_financeiro_conta_id,      -- <--- NOVO
					  financeiro_movimentacao_financeiro_categoria_id,  -- <--- NOVO
					  financeiro_movimentacao_financeiro_conta_financeira_id,
					  financeiro_movimentacao_tipo,
					  financeiro_movimentacao_valor,
					  financeiro_movimentacao_data,
					  financeiro_movimentacao_descricao,
					  financeiro_movimentacao_origem)
					 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pagamento')";

			$this->db->prepare($sql4)->execute([
				$data['igreja_id'],
				$data['conta_id'],      // Link com a conta original
				$categoriaId,           // Link com a categoria/subcategoria
				$data['conta_financeira_id'],
				$tipo,
				$data['valor'],
				$data_pagamento_completa,
				$desc
			]);

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function atualizarLancamentoCompleto($data) {
		try {
			$this->db->beginTransaction();

			// 1. BUSCAR DADOS ATUAIS (Importante para saber o que estornar)
			$sqlBusca = "SELECT fc.*, fp.financeiro_pagamento_conta_financeira_id, fp.financeiro_pagamento_valor
						 FROM financeiro_contas fc
						 LEFT JOIN financeiro_pagamentos fp ON fp.financeiro_pagamento_financeiro_conta_id = fc.financeiro_conta_id
						 WHERE fc.financeiro_conta_id = ? AND fc.financeiro_conta_igreja_id = ?";
			$stmt = $this->db->prepare($sqlBusca);
			$stmt->execute([$data['id'], $data['igreja_id']]);
			$antigo = $stmt->fetch(\PDO::FETCH_ASSOC);

			if (!$antigo) throw new \Exception("Lançamento não encontrado.");

			$tipo = $antigo['financeiro_conta_tipo'];
			$foiPago = ($antigo['financeiro_conta_pago'] == 1);

			// 2. ATUALIZAR A TABELA PRINCIPAL (financeiro_contas)
			$sqlUpConta = "UPDATE financeiro_contas SET
							financeiro_conta_financeiro_categoria_id = ?,
							financeiro_conta_descricao = ?,
							financeiro_conta_valor = ?,
							financeiro_conta_data_pagamento = ?,
							financeiro_conta_reembolso = ?
						  WHERE financeiro_conta_id = ?";
			$this->db->prepare($sqlUpConta)->execute([
				$data['categoria_id'], $data['descricao'], $data['valor'],
				$data['data_pagamento'], $data['reembolso'] ?? 0, $data['id']
			]);

			// 3. ATUALIZAÇÃO DO RATEIO DE MEMBROS (Se for Entrada/Receita)
			if ($tipo == 'entrada') {
					// ... dentro do if ($tipo == 'entrada')
					$this->db->prepare("DELETE FROM financeiro_receita_membros WHERE receita_membro_conta_id = ?")
							 ->execute([$data['id']]);

					if (!empty($data['membros'])) {
						$sqlMembro = "INSERT INTO financeiro_receita_membros
									 (receita_membro_conta_id, receita_membro_categoria_id, receita_membro_subcategoria_id,
									  receita_membro_usuario_id, receita_membro_valor, receita_membro_data)
									 VALUES (?, ?, ?, ?, ?, ?)";
						$stmtMembro = $this->db->prepare($sqlMembro);

						foreach ($data['membros'] as $index => $membro_id) {
							// Ponto Crítico: Verifica se o membro_id não está vazio e se o valor existe
							if (empty($membro_id)) continue;

							$valorRaw = $data['membros_valores'][$index] ?? 0;
							$valorMembro = str_replace(',', '.', $valorRaw);

							if ($valorMembro <= 0) continue; // Não insere se o valor for zero

							$stmtMembro->execute([
								$data['id'],
								$data['categoria_id'],
								$data['categoria_id'], // Subcategoria
								$membro_id,
								$valorMembro,
								$data['data_pagamento']
							]);
						}
					}
			}

			// 4. SE ESTIVER PAGO, SINCRONIZA SALDO, PAGAMENTO E EXTRATO (MOVIMENTAÇÕES)
			if ($foiPago) {
				$contaFinanceiraAntiga = $antigo['financeiro_pagamento_conta_financeira_id'];

				// AJUSTE: Pegando o nome correto do campo vindo do <select>
				$contaFinanceiraNova = $data['financeiro_conta_financeira_id'];

				$valorAntigo = $antigo['financeiro_conta_valor'];
				$valorNovo = $data['valor'];

				// A) Estornar saldo na conta bancária antiga
				$opEstorno = ($tipo == 'entrada') ? '-' : '+';
				$this->db->prepare("UPDATE financeiro_contas_financeiras SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo $opEstorno ?
									WHERE financeiro_conta_financeira_id = ?")
						 ->execute([$valorAntigo, $contaFinanceiraAntiga]);

				// B) Aplicar novo saldo na conta bancária nova
				$opAplicar = ($tipo == 'entrada') ? '+' : '-';
				$this->db->prepare("UPDATE financeiro_contas_financeiras SET financeiro_conta_financeira_saldo = financeiro_conta_financeira_saldo $opAplicar ?
									WHERE financeiro_conta_financeira_id = ?")
						 ->execute([$valorNovo, $contaFinanceiraNova]);

				// C) Atualizar a tabela de Pagamentos
				$this->db->prepare("UPDATE financeiro_pagamentos SET
									 financeiro_pagamento_valor = ?,
									 financeiro_pagamento_conta_financeira_id = ?,
									 financeiro_pagamento_data = ?
									 WHERE financeiro_pagamento_financeiro_conta_id = ?")
						 ->execute([$valorNovo, $contaFinanceiraNova, $data['data_pagamento'], $data['id']]);

				// D) Atualizar o Extrato (financeiro_movimentacoes)
				$novaDesc = "Correção de " . ($tipo == 'entrada' ? 'Receita' : 'Despesa') . ": " . $data['descricao'];

				$sqlUpMov = "UPDATE financeiro_movimentacoes SET
							 financeiro_movimentacao_financeiro_conta_financeira_id = ?,
							 financeiro_movimentacao_financeiro_categoria_id = ?,
							 financeiro_movimentacao_valor = ?,
							 financeiro_movimentacao_descricao = ?,
							 financeiro_movimentacao_data = ?
							 WHERE financeiro_movimentacao_financeiro_conta_id = ?
							 AND financeiro_movimentacao_origem = 'pagamento'";

				$this->db->prepare($sqlUpMov)->execute([
					$contaFinanceiraNova,
					$data['categoria_id'],
					$valorNovo,
					$novaDesc,
					$data['data_pagamento'],
					$data['id']
				]);
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function atualizarAnexoFinanceiro($contaId, $igrejaId, $tipo, $caminho) {
		$coluna = ($tipo === 'comprovante') ? 'financeiro_conta_comprovante' : 'financeiro_conta_nota_fiscal';
		$sql = "UPDATE financeiro_contas SET {$coluna} = ? WHERE financeiro_conta_id = ? AND financeiro_conta_igreja_id = ?";
		return $this->db->prepare($sql)->execute([$caminho, $contaId, $igrejaId]);
	}

	// Busca categorias e subcategorias agrupadas
	public function getCategoriasAgrupadas($igrejaId) {
		$sql = "SELECT
					c.financeiro_categoria_id,
					c.financeiro_categoria_nome,
					c.financeiro_categoria_tipo,
					s.subcategoria_id,
					s.subcategoria_nome
				FROM financeiro_categorias c
				LEFT JOIN financeiro_subcategorias s ON c.financeiro_categoria_id = s.subcategoria_categoria_id
				WHERE c.financeiro_categoria_igreja_id = ?
				/* Ordena primeiro por TIPO (entrada antes de saida) e depois pelo NOME */
				ORDER BY c.financeiro_categoria_tipo ASC, c.financeiro_categoria_nome ASC, s.subcategoria_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$agrupado = [];
		foreach ($dados as $linha) {
			$catId = $linha['financeiro_categoria_id'];

			if (!isset($agrupado[$catId])) {
				$agrupado[$catId] = [
					'id'   => $catId,
					'nome' => $linha['financeiro_categoria_nome'],
					'tipo' => $linha['financeiro_categoria_tipo'],
					'subs' => []
				];
			}

			if ($linha['subcategoria_id']) {
				$agrupado[$catId]['subs'][] = [
					'id'   => $linha['subcategoria_id'],
					'nome' => $linha['subcategoria_nome']
				];
			}
		}
		return $agrupado;
	}

	// Salvar Subcategoria
	public function salvarSubcategoria($igrejaId, $catId, $nome) {
		$sql = "INSERT INTO financeiro_subcategorias (subcategoria_igreja_id, subcategoria_categoria_id, subcategoria_nome) VALUES (?, ?, ?)";
		return $this->db->prepare($sql)->execute([$igrejaId, $catId, $nome]);
	}

	// Verifica se a subcategoria está em uso antes de excluir
	public function excluirSubcategoria($id, $igrejaId) {
		// 1. Verificar se existem contas (financeiro_contas) vinculadas a esta subcategoria
		// Nota: Como mudamos para subcategorias, certifique-se que a coluna na tabela financeiro_contas
		// agora armazena o ID da subcategoria.
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM financeiro_contas WHERE financeiro_conta_financeiro_categoria_id = ? AND financeiro_conta_igreja_id = ?");
		$stmt->execute([$id, $igrejaId]);

		if ($stmt->fetchColumn() > 0) {
			return false; // Não pode excluir pois há registros vinculados
		}

		// 2. Se estiver livre, deleta
		$sql = "DELETE FROM financeiro_subcategorias WHERE subcategoria_id = ? AND subcategoria_igreja_id = ?";
		return $this->db->prepare($sql)->execute([$id, $igrejaId]);
	}

	/**
	 * Busca todas as contas financeiras (bancárias/caixa) da igreja
	 */
	public function getContasBancarias($igrejaId) {
		$sql = "SELECT
					financeiro_conta_financeira_id,
					financeiro_conta_financeira_nome,
					financeiro_conta_financeira_saldo,
					financeiro_conta_financeira_tipo  -- <--- ADICIONE ESTA LINHA
				FROM financeiro_contas_financeiras
				WHERE financeiro_conta_financeira_igreja_id = ?
				ORDER BY financeiro_conta_financeira_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function excluirContaAgendada($id, $igrejaId) {
		// Só exclui se financeiro_conta_pago for 0
		$sql = "DELETE FROM financeiro_contas
				WHERE financeiro_conta_id = ?
				AND financeiro_conta_igreja_id = ?
				AND financeiro_conta_pago = 0";
		return $this->db->prepare($sql)->execute([$id, $igrejaId]);
	}

	public function getMovimentacoesPorPeriodo($igrejaId, $dataInicio, $dataFim) {
		$sql = "SELECT
					m.*,
					c.financeiro_conta_financeira_nome,
					-- FORÇAMOS O NOME DO CAMPO PARA O JS RECONHECER:
					m.financeiro_movimentacao_financeiro_conta_financeira_id AS financeiro_conta_financeira_id,
					f.financeiro_conta_id,
					f.financeiro_conta_descricao,
					f.financeiro_conta_valor,
					f.financeiro_conta_financeiro_categoria_id,
					f.financeiro_conta_tipo
				FROM financeiro_movimentacoes m
				JOIN financeiro_contas_financeiras c ON m.financeiro_movimentacao_financeiro_conta_financeira_id = c.financeiro_conta_financeira_id
				LEFT JOIN financeiro_contas f ON m.financeiro_movimentacao_financeiro_conta_id = f.financeiro_conta_id
				WHERE m.financeiro_movimentacao_igreja_id = ?
				AND m.financeiro_movimentacao_data BETWEEN ? AND ?
				ORDER BY m.financeiro_movimentacao_data DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function registrarRateioMembros($contaId, $membros, $igrejaId, $catId, $subCatId, $data) {
		try {
			$this->db->beginTransaction();

			// 1. Limpa o que já existe para essa conta
			$stmtDel = $this->db->prepare("DELETE FROM financeiro_receita_membros WHERE receita_membro_conta_id = ?");
			$stmtDel->execute([$contaId]);

			// 2. Prepara o Insert
			$sql = "INSERT INTO financeiro_receita_membros (
						receita_membro_conta_id,
						receita_membro_categoria_id,
						receita_membro_subcategoria_id,
						receita_membro_usuario_id,
						receita_membro_valor,
						receita_membro_data
					) VALUES (?, ?, ?, ?, ?, ?)";

			$stmt = $this->db->prepare($sql);

			foreach ($membros as $m) {
				// VERIFIQUE: Se no seu JS o valor é 'valor' ou 'valor_formatado'
				// E se o ID do membro é 'membro_id' ou apenas 'id'
				$idMembro = $m['membro_id'] ?? $m['id'];
				$valorMembro = $m['valor'];

				if ($valorMembro > 0) {
					$stmt->execute([
						$contaId,
						$catId,
						$subCatId,
						$idMembro,
						$valorMembro,
						$data
					]);
				}
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			error_log("Erro ao salvar rateio: " . $e->getMessage());
			return false;
		}
	}

	public function getMembrosAtivos($igrejaId) {
		// Usando a tabela 'membros' e os nomes de colunas que você enviou
		$sql = "SELECT
					membro_id as id,
					membro_nome as nome
				FROM membros
				WHERE membro_igreja_id = ?
				AND membro_status = 'Ativo'
				ORDER BY membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getResumoMes($igrejaId) {
		$mesAtual = date('m');
		$anoAtual = date('Y');

		// Soma Receitas (entradas)
		$sqlRec = "SELECT SUM(financeiro_movimentacao_valor) as total FROM financeiro_movimentacoes
				   WHERE financeiro_movimentacao_igreja_id = ? AND financeiro_movimentacao_tipo = 'entrada'
				   AND MONTH(financeiro_movimentacao_data) = ? AND YEAR(financeiro_movimentacao_data) = ?";
		$stmtRec = $this->db->prepare($sqlRec);
		$stmtRec->execute([$igrejaId, $mesAtual, $anoAtual]);
		$receitas = $stmtRec->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

		// Soma Despesas (saídas)
		$sqlDesp = "SELECT SUM(financeiro_movimentacao_valor) as total FROM financeiro_movimentacoes
					WHERE financeiro_movimentacao_igreja_id = ? AND financeiro_movimentacao_tipo = 'saida'
					AND MONTH(financeiro_movimentacao_data) = ? AND YEAR(financeiro_movimentacao_data) = ?";
		$stmtDesp = $this->db->prepare($sqlDesp);
		$stmtDesp->execute([$igrejaId, $mesAtual, $anoAtual]);
		$despesas = $stmtDesp->fetch(\PDO::FETCH_ASSOC)['total'] ?? 0;

		return [
			'receitas' => $receitas,
			'despesas' => $despesas,
			'saldo_mes' => $receitas - $despesas
		];
	}

	public function getDadosGraficoLinha($igrejaId) {
		$anoAtual = date('Y');

		// Busca todos os meses do ano atual
		$sql = "SELECT
					MONTH(financeiro_movimentacao_data) as mes,
					SUM(CASE WHEN financeiro_movimentacao_tipo = 'entrada' THEN financeiro_movimentacao_valor ELSE 0 END) as entradas,
					SUM(CASE WHEN financeiro_movimentacao_tipo = 'saida' THEN financeiro_movimentacao_valor ELSE 0 END) as saidas
				FROM financeiro_movimentacoes
				WHERE financeiro_movimentacao_igreja_id = ?
				AND YEAR(financeiro_movimentacao_data) = ?
				GROUP BY MONTH(financeiro_movimentacao_data)
				ORDER BY mes ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $anoAtual]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getComparativoAnual($igrejaId, $anoReferencia) {
		$anoAnterior = $anoReferencia - 1;

		$sql = "SELECT
					cat.financeiro_categoria_nome as categoria,
					sub.financeiro_subcategoria_nome as subcategoria,
					MONTH(m.financeiro_movimentacao_data) as mes,
					SUM(CASE WHEN YEAR(m.financeiro_movimentacao_data) = ? THEN m.financeiro_movimentacao_valor ELSE 0 END) as valor_atual,
					SUM(CASE WHEN YEAR(m.financeiro_movimentacao_data) = ? THEN m.financeiro_movimentacao_valor ELSE 0 END) as valor_anterior
				FROM financeiro_movimentacoes m
				INNER JOIN financeiro_subcategorias sub ON m.financeiro_movimentacao_subcategoria_id = sub.financeiro_subcategoria_id
				INNER JOIN financeiro_categorias cat ON sub.financeiro_subcategoria_categoria_id = cat.financeiro_categoria_id
				WHERE m.financeiro_movimentacao_igreja_id = ?
				AND m.financeiro_movimentacao_tipo = 'saida'
				AND YEAR(m.financeiro_movimentacao_data) IN (?, ?)
				GROUP BY cat.financeiro_categoria_id, sub.financeiro_subcategoria_id, MONTH(m.financeiro_movimentacao_data)
				ORDER BY cat.financeiro_categoria_nome, sub.financeiro_subcategoria_nome, mes";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$anoReferencia, $anoAnterior, $igrejaId, $anoReferencia, $anoAnterior]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getRelatorioRateioMembros($igrejaId, $ano) {
		$sql = "SELECT
					m.membro_nome,
					rm.receita_membro_valor,
					MONTH(rm.receita_membro_data) as mes,
					-- Se não houver subcategoria, ele tenta a categoria, senão mostra Geral
					COALESCE(sub.subcategoria_nome, cat.financeiro_categoria_nome, 'Outros') as tipo_receita
				FROM financeiro_receita_membros rm
				INNER JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				LEFT JOIN financeiro_subcategorias sub ON rm.receita_membro_subcategoria_id = sub.subcategoria_id
				LEFT JOIN financeiro_categorias cat ON rm.receita_membro_categoria_id = cat.financeiro_categoria_id
				WHERE m.membro_igreja_id = ?
				AND YEAR(rm.receita_membro_data) = ?
				ORDER BY tipo_receita ASC, m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $ano]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Busca os dados de uma receita específica para herdar categoria e subcategoria
	 */
	public function getReceitaPorId($id) {
		// Buscamos na tabela de contas
		$sql = "SELECT * FROM financeiro_contas WHERE financeiro_conta_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getFluxoAnualPorCategorias($igrejaId, $ano) {
		// 1. Busca Categorias (Pai)
		$sqlCat = "SELECT financeiro_categoria_id as id, financeiro_categoria_nome as nome, financeiro_categoria_tipo as tipo
				   FROM financeiro_categorias WHERE financeiro_categoria_igreja_id = ?
				   ORDER BY financeiro_categoria_tipo DESC, id ASC";
		$stmtC = $this->db->prepare($sqlCat);
		$stmtC->execute([$igrejaId]);
		$categorias = $stmtC->fetchAll(\PDO::FETCH_ASSOC);

		// 2. Busca Subcategorias e seus IDs de Pai
		$sqlSub = "SELECT subcategoria_id as id, subcategoria_nome as nome, subcategoria_categoria_id as pai_id
				   FROM financeiro_subcategorias WHERE subcategoria_igreja_id = ?";
		$stmtS = $this->db->prepare($sqlSub);
		$stmtS->execute([$igrejaId]);
		$subcategorias = $stmtS->fetchAll(\PDO::FETCH_ASSOC);

		// 3. Busca os Valores Reais (Março está aqui)
		$sqlDados = "SELECT financeiro_conta_financeiro_categoria_id as cat_id,
							MONTH(financeiro_conta_data_pagamento) as mes,
							SUM(financeiro_conta_valor) as total
					 FROM financeiro_contas
					 WHERE financeiro_conta_igreja_id = ? AND YEAR(financeiro_conta_data_pagamento) = ?
					 GROUP BY cat_id, mes";
		$stmtD = $this->db->prepare($sqlDados);
		$stmtD->execute([$igrejaId, $ano]);
		$valores = $stmtD->fetchAll(\PDO::FETCH_ASSOC);

		$mapa = [];
		foreach($valores as $v) {
			$mapa[(int)$v['cat_id']][(int)$v['mes']] = (float)$v['total'];
		}

		$relatorio = ['entrada' => [], 'saida' => []];

		// 4. Montagem da Estrutura
		foreach($categorias as $c) {
			$cID = (int)$c['id'];
			$tipo = $c['tipo'];

			$mesesPai = array_fill(1, 12, 0);
			// Se houver valor direto no ID do Pai, soma
			if(isset($mapa[$cID])) {
				foreach($mapa[$cID] as $m => $val) { $mesesPai[$m] += $val; }
			}

			$subsFinal = [];
			foreach($subcategorias as $s) {
				if((int)$s['pai_id'] === $cID) {
					$sID = (int)$s['id'];
					$mesesSub = array_fill(1, 12, 0);

					// IMPORTANTE: Se o lançamento foi feito no ID da subcategoria (ex: ID 5),
					// somamos na subcategoria E no total do Pai para Março aparecer!
					if(isset($mapa[$sID])) {
						foreach($mapa[$sID] as $m => $val) {
							$mesesSub[$m] = $val;
							$mesesPai[$m] += $val; // Soma o valor da subcategoria no total da categoria pai
						}
					}

					$subsFinal[] = [
						'nome' => $s['nome'],
						'meses' => $mesesSub
					];
				}
			}

			$relatorio[$tipo][$cID] = [
				'nome' => $c['nome'],
				'meses' => $mesesPai,
				'subcategorias' => $subsFinal
			];
		}

		return $relatorio;
	}

	public function getSaldosPorConta($igrejaId) {
		// Busca o nome da conta e o saldo atual da tabela de contas financeiras
		$sql = "SELECT
					financeiro_conta_financeira_nome as nome,
					financeiro_conta_financeira_saldo as saldo
				FROM financeiro_contas_financeiras
				WHERE financeiro_conta_financeira_igreja_id = ?
				AND financeiro_conta_financeira_status = 'Ativo'
				ORDER BY financeiro_conta_financeira_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Busca os anos únicos baseados na data de vencimento
	public function getAnosComMovimentacao($igrejaId) {
		$sql = "SELECT DISTINCT YEAR(financeiro_conta_data_vencimento) as ano
				FROM financeiro_contas
				WHERE financeiro_conta_igreja_id = ?
				ORDER BY ano DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$resultado = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		// Se não houver nada, retorna o ano atual para não quebrar o combo
		return !empty($resultado) ? $resultado : [['ano' => date('Y')]];
	}

	// Busca receitas de um dia específico com detalhes de rateio/membros
	public function getReceitasParaConferencia($igrejaId, $data) {
		$sql = "SELECT
					c.financeiro_conta_id,
					c.financeiro_conta_descricao,
					c.financeiro_conta_valor,
					cat.financeiro_categoria_nome as categoria_pai,
					sub.subcategoria_nome as subcategoria,
					m.membro_nome as ofertante,
					i.igreja_nome,
					i.igreja_logo,
					-- Busca o nome da conta de destino através da tabela de pagamentos
					cf.financeiro_conta_financeira_nome as destino,
					COALESCE(rm.receita_membro_valor, c.financeiro_conta_valor) as valor_exibir
				FROM financeiro_contas c
				INNER JOIN igrejas i ON c.financeiro_conta_igreja_id = i.igreja_id
				-- Relacionamento Categorias
				INNER JOIN financeiro_subcategorias sub ON c.financeiro_conta_financeiro_categoria_id = sub.subcategoria_id
				INNER JOIN financeiro_categorias cat ON sub.subcategoria_categoria_id = cat.financeiro_categoria_id
				-- Relacionamento para pegar o destino do dinheiro (através de pagamentos)
				LEFT JOIN financeiro_pagamentos p ON c.financeiro_conta_id = p.financeiro_pagamento_financeiro_conta_id
				LEFT JOIN financeiro_contas_financeiras cf ON p.financeiro_pagamento_conta_financeira_id = cf.financeiro_conta_financeira_id
				-- Relacionamento Membros/Rateio
				LEFT JOIN financeiro_receita_membros rm ON c.financeiro_conta_id = rm.receita_membro_conta_id
				LEFT JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				WHERE c.financeiro_conta_igreja_id = ?
				  AND c.financeiro_conta_tipo = 'entrada'
				  AND DATE(c.financeiro_conta_data_pagamento) = ?
				  AND c.financeiro_conta_pago = 1
				ORDER BY cat.financeiro_categoria_nome ASC, sub.subcategoria_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $data]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Busca o nome do Tesoureiro atual da igreja (Cargo 11)
	public function getTesoureiroIgreja($igrejaId) {
		$sql = "SELECT m.membro_nome
				FROM membros m
				INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				WHERE v.vinculo_cargo_id = 11 AND m.membro_igreja_id = ?
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $res['membro_nome'] ?? 'A Designar';
	}

	public function getOficiaisConferentes($igrejaId) {
		$sql = "SELECT DISTINCT
					m.membro_id,
					m.membro_nome,
					c.cargo_nome
				FROM membros m
				INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				INNER JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				WHERE m.membro_igreja_id = ?
				  AND v.vinculo_cargo_id IN (5, 6, 7) -- 5,6: Presbíteros | 7: Diácono
				  AND m.membro_status = 'Ativo'
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getRateioLancamento($contaId) {
		$sql = "SELECT r.*, m.membro_nome
				FROM financeiro_receita_membros r
				INNER JOIN membros m ON r.receita_membro_usuario_id = m.membro_id
				WHERE r.receita_membro_conta_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$contaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getMembrosRateio($contaId) {
		$sql = "SELECT
					rm.receita_membro_id,
					rm.receita_membro_valor,
					rm.receita_membro_comprovante,
					m.membro_nome
				FROM financeiro_receita_membros rm
				INNER JOIN membros m ON rm.receita_membro_usuario_id = m.membro_id
				WHERE rm.receita_membro_conta_id = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$contaId]);

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getCategoriasParaCombo($igrejaId) {
		$sql = "SELECT
					c.financeiro_categoria_nome,
					c.financeiro_categoria_tipo,
					c.financeiro_categoria_id,
					s.subcategoria_id,
					s.subcategoria_nome
				FROM financeiro_categorias c
				LEFT JOIN financeiro_subcategorias s ON s.subcategoria_categoria_id = c.financeiro_categoria_id
				WHERE c.financeiro_categoria_igreja_id = ?
				ORDER BY c.financeiro_categoria_tipo ASC, c.financeiro_categoria_nome ASC, s.subcategoria_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$dados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($dados as &$item) {
			// Adicione os colchetes aqui
			$tipo = ($item['financeiro_categoria_tipo'] == 'entrada') ? '[ RECEITA ]' : '[ DESPESA ]';

			$label = $tipo . " " . $item['financeiro_categoria_nome'];
			if (!empty($item['subcategoria_nome'])) {
				$label .= " - " . $item['subcategoria_nome'];
			}

			$item['nome_formatado'] = $label;
		}

		return $dados;
	}

	// Para buscar o arquivo antigo antes de sobrescrever
	public function getMembroRateioById($membroId) {
		$sql = "SELECT receita_membro_comprovante FROM financeiro_receita_membros WHERE receita_membro_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	// Para salvar o novo caminho
	public function atualizarComprovanteMembro($membroId, $caminho) {
		$sql = "UPDATE financeiro_receita_membros SET receita_membro_comprovante = ? WHERE receita_membro_id = ?";
		return $this->db->prepare($sql)->execute([$caminho, $membroId]);
	}

}
