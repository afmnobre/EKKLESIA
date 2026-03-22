<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Patrimonio
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Listar todos os bens da igreja
	public function getAll($igrejaId)
	{
		$sql = "SELECT b.*, l.patrimonio_local_nome,
				(SELECT patrimonio_imagem_arquivo
				 FROM patrimonio_imagens
				 WHERE patrimonio_imagem_bem_id = b.patrimonio_bem_id
				 LIMIT 1) as foto
				FROM patrimonio_bens b
				LEFT JOIN patrimonio_locais l ON b.patrimonio_bem_local_id = l.patrimonio_local_id
				WHERE b.patrimonio_bem_igreja_id = ?
				ORDER BY b.patrimonio_bem_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    // Buscar locais cadastrados para a igreja
    public function getLocais($igrejaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM patrimonio_locais WHERE patrimonio_local_igreja_id = ?");
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar um bem específico por ID
    public function getById($id, $igrejaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM patrimonio_bens WHERE patrimonio_bem_id = ? AND patrimonio_bem_igreja_id = ?");
        $stmt->execute([$id, $igrejaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Salvar novo local
	public function salvarLocal($dados) {
		$sql = "INSERT INTO patrimonio_locais (patrimonio_local_igreja_id, patrimonio_local_nome) VALUES (?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$dados['igreja_id'], $dados['nome']]);
	}

	// Excluir local
	public function excluirLocal($id, $igrejaId) {
		$stmt = $this->db->prepare("DELETE FROM patrimonio_locais WHERE patrimonio_local_id = ? AND patrimonio_local_igreja_id = ?");
		return $stmt->execute([$id, $igrejaId]);
	}

	// Salva o bem e retorna o ID inserido
	public function salvarBem($dados)
	{
		$sql = "INSERT INTO patrimonio_bens (
					patrimonio_bem_igreja_id,
					patrimonio_bem_codigo,
					patrimonio_bem_nome,
					patrimonio_bem_descricao,
					patrimonio_bem_data_aquisicao,
					patrimonio_bem_valor,
					patrimonio_bem_status,
					patrimonio_bem_local_id -- ADICIONADO
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; // Adicionado um '?'

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			$dados['igreja_id'],
			$dados['codigo'],
			$dados['nome'],
			$dados['descricao'],
			$dados['data_aquisicao'],
			$dados['valor'],
			$dados['status'],
			$dados['local_id'] // ADICIONADO
		]);

		return $this->db->lastInsertId();
	}

	// Registra a movimentação de entrada/saída/transferência
	public function registrarMovimentacao($dados)
	{
		$sql = "INSERT INTO patrimonio_movimentacoes (
					patrimonio_movimentacao_patrimonio_bem_id,
					patrimonio_movimentacao_igreja_id,
					patrimonio_movimentacao_tipo,
					patrimonio_movimentacao_local_destino,
					patrimonio_movimentacao_data,
					patrimonio_movimentacao_observacao
				) VALUES (?, ?, ?, ?, ?, ?)";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['bem_id'],
			$dados['igreja_id'],
			$dados['tipo'],
			$dados['local_destino'],
			$dados['data'],
			$dados['observacao']
		]);
	}

	public function inserirFoto($bemId, $nomeArquivo)
	{
		$sql = "INSERT INTO patrimonio_imagens (patrimonio_imagem_bem_id, patrimonio_imagem_arquivo) VALUES (?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$bemId, $nomeArquivo]);
	}

	public function inserirDocumento($bemId, $nomeArquivo)
	{
		$sql = "INSERT INTO patrimonio_documentos (patrimonio_documento_bem_id, patrimonio_documento_arquivo) VALUES (?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$bemId, $nomeArquivo]);
	}

    // Buscar todas as fotos de um bem
    public function getFotos($bemId) {
        $stmt = $this->db->prepare("SELECT * FROM patrimonio_imagens WHERE patrimonio_imagem_bem_id = ?");
        $stmt->execute([$bemId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Buscar todos os documentos de um bem
    public function getDocumentos($bemId) {
        $stmt = $this->db->prepare("SELECT * FROM patrimonio_documentos WHERE patrimonio_documento_bem_id = ?");
        $stmt->execute([$bemId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

	public function atualizarBem($dados)
	{
		$sql = "UPDATE patrimonio_bens SET
					patrimonio_bem_nome = ?,
					patrimonio_bem_descricao = ?,
					patrimonio_bem_data_aquisicao = ?,
					patrimonio_bem_valor = ?,
					patrimonio_bem_status = ?
				WHERE patrimonio_bem_id = ? AND patrimonio_bem_igreja_id = ?";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['nome'],
			$dados['descricao'],
			$dados['data_aquisicao'],
			$dados['valor'],
			$dados['status'],
			$dados['id'],
			$dados['igreja_id']
		]);
	}

	public function getLastId()
	{
		// Busca o maior ID da tabela para garantir a sequência correta
		$sql = "SELECT MAX(patrimonio_bem_id) as total FROM patrimonio_bens";
		$stmt = $this->db->query($sql);
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		return (int)($res['total'] ?? 0);
	}

    public function getIgrejaDados($igrejaId)
    {
        $sql = "SELECT igreja_nome FROM igrejas WHERE igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

	public function excluir($id, $igrejaId)
	{
		try {
			// 1. Antes de deletar, precisamos saber quais arquivos existem para apagar o disco
			// No seu sistema, as fotos e documentos ficam em subpastas dentro da pasta do patrimônio
			$raizProjeto = dirname(__DIR__, 2);
			$diretorioPatrimonio = $raizProjeto . "/public/assets/uploads/{$igrejaId}/patrimonios/{$id}/";

			$this->db->beginTransaction();

			// 2. Limpa os registros do Banco de Dados
			$this->db->prepare("DELETE FROM patrimonio_imagens WHERE patrimonio_imagem_bem_id = ?")->execute([$id]);
			$this->db->prepare("DELETE FROM patrimonio_documentos WHERE patrimonio_documento_bem_id = ?")->execute([$id]);
			$this->db->prepare("DELETE FROM patrimonio_movimentacoes WHERE patrimonio_movimentacao_patrimonio_bem_id = ?")->execute([$id]);

			// 3. Deleta o Bem
			$stmt = $this->db->prepare("DELETE FROM patrimonio_bens WHERE patrimonio_bem_id = ? AND patrimonio_bem_igreja_id = ?");
			$stmt->execute([$id, $igrejaId]);

			$this->db->commit();

			// 4. Exclusão Física dos Arquivos e Pastas
			// Como você cria uma pasta específica para cada ID de patrimônio,
			// o mais limpo é apagar a pasta do ID do patrimônio inteira.
			if (is_dir($diretorioPatrimonio)) {
				$this->deletarDiretorioRecursivo($diretorioPatrimonio);
			}

			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	/**
	 * Auxiliar para apagar a pasta e tudo que tem dentro (fotos, documentos e subpastas)
	 */
	private function deletarDiretorioRecursivo($dir) {
		if (!file_exists($dir)) return true;
		if (!is_dir($dir)) return unlink($dir);

		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			if (!$this->deletarDiretorioRecursivo($dir . DIRECTORY_SEPARATOR . $item)) return false;
		}

		return rmdir($dir);
	}

	public function registrarMovimentacaoCompleta($dados)
	{
		try {
			$this->db->beginTransaction();

			// 1. Insere o Histórico de Movimentação
			$sqlMov = "INSERT INTO patrimonio_movimentacoes (
						patrimonio_movimentacao_patrimonio_bem_id,
						patrimonio_movimentacao_igreja_id,
						patrimonio_movimentacao_tipo,
						patrimonio_movimentacao_local_origem,
						patrimonio_movimentacao_local_destino,
						patrimonio_movimentacao_data,
						patrimonio_movimentacao_observacao
					) VALUES (?, ?, ?, ?, ?, ?, ?)";

			$stmtMov = $this->db->prepare($sqlMov);
			$stmtMov->execute([
				$dados['bem_id'],
				$dados['igreja_id'],
				$dados['tipo'],
				$dados['local_origem'],
				$dados['local_destino'],
				$dados['data'],
				$dados['observacao']
			]);

			// 2. Atualiza o Local Atual do Bem na tabela principal
			// Se for "baixa", você também pode aproveitar para mudar o status do bem
			$statusExtra = ($dados['tipo'] === 'baixa') ? ", patrimonio_bem_status = 'baixado'" : "";
			$statusExtra = ($dados['tipo'] === 'manutencao') ? ", patrimonio_bem_status = 'manutencao'" : $statusExtra;
			$statusExtra = ($dados['tipo'] === 'transferencia' || $dados['tipo'] === 'entrada') ? ", patrimonio_bem_status = 'ativo'" : $statusExtra;

			$sqlBem = "UPDATE patrimonio_bens
					   SET patrimonio_bem_local_id = ? {$statusExtra}
					   WHERE patrimonio_bem_id = ? AND patrimonio_bem_igreja_id = ?";

			$stmtBem = $this->db->prepare($sqlBem);
			$stmtBem->execute([
				$dados['local_destino'],
				$dados['bem_id'],
				$dados['igreja_id']
			]);

			$this->db->commit();
			return true;

		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function getMovimentacoes($bemId, $igrejaId)
	{
		$sql = "SELECT m.*,
					   lo.patrimonio_local_nome as nome_origem,
					   ld.patrimonio_local_nome as nome_destino
				FROM patrimonio_movimentacoes m
				LEFT JOIN patrimonio_locais lo ON m.patrimonio_movimentacao_local_origem = lo.patrimonio_local_id
				LEFT JOIN patrimonio_locais ld ON m.patrimonio_movimentacao_local_destino = ld.patrimonio_local_id
				WHERE m.patrimonio_movimentacao_patrimonio_bem_id = ?
				AND m.patrimonio_movimentacao_igreja_id = ?
				ORDER BY m.patrimonio_movimentacao_data DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$bemId, $igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function atualizarLocal($id, $dados)
	{
		$sql = "UPDATE patrimonio_locais
				SET patrimonio_local_nome = ?
				WHERE patrimonio_local_id = ? AND patrimonio_local_igreja_id = ?";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['nome'],
			$id,
			$dados['igreja_id']
		]);
	}

	public function getMetrics($igrejaId)
	{
		$metrics = [];

		// 1. Valor Total e Qtd (Apenas Ativos e em Manutenção - Ignora Baixados)
		$sqlGeral = "SELECT COUNT(*) as total_itens, SUM(patrimonio_bem_valor) as valor_total
					 FROM patrimonio_bens
					 WHERE patrimonio_bem_igreja_id = ? AND patrimonio_bem_status != 'baixado'";
		$stmt = $this->db->prepare($sqlGeral);
		$stmt->execute([$igrejaId]);
		$metrics['geral'] = $stmt->fetch(PDO::FETCH_ASSOC);

		// 2. Distribuição por Status
		$sqlStatus = "SELECT patrimonio_bem_status as status, COUNT(*) as qtd
					  FROM patrimonio_bens WHERE patrimonio_bem_igreja_id = ?
					  GROUP BY patrimonio_bem_status";
		$stmt = $this->db->prepare($sqlStatus);
		$stmt->execute([$igrejaId]);
		$metrics['por_status'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 3. Top 5 Locais com mais Bens (Quantidade e Valor)
		$sqlLocais = "SELECT l.patrimonio_local_nome, COUNT(b.patrimonio_bem_id) as qtd, SUM(b.patrimonio_bem_valor) as valor
					  FROM patrimonio_locais l
					  LEFT JOIN patrimonio_bens b ON l.patrimonio_local_id = b.patrimonio_bem_local_id
					  WHERE l.patrimonio_local_igreja_id = ?
					  GROUP BY l.patrimonio_local_id
					  ORDER BY qtd DESC LIMIT 5";
		$stmt = $this->db->prepare($sqlLocais);
		$stmt->execute([$igrejaId]);
		$metrics['por_local'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// 4. Entradas Recentes (Últimos 30 dias)
		$sqlRecentes = "SELECT COUNT(*) as qtd FROM patrimonio_bens
						WHERE patrimonio_bem_igreja_id = ?
						AND patrimonio_bem_data_aquisicao >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
		$stmt = $this->db->prepare($sqlRecentes);
		$stmt->execute([$igrejaId]);
		$metrics['entradas_30_dias'] = $stmt->fetch(PDO::FETCH_ASSOC)['qtd'] ?? 0;

		// 5. Volume de Movimentações (Últimos 30 dias)
		$sqlMov = "SELECT COUNT(*) as qtd FROM patrimonio_movimentacoes
				   WHERE patrimonio_movimentacao_igreja_id = ?
				   AND patrimonio_movimentacao_data >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
		$stmt = $this->db->prepare($sqlMov);
		$stmt->execute([$igrejaId]);
		$metrics['movimentacoes_30_dias'] = $stmt->fetch(PDO::FETCH_ASSOC)['qtd'] ?? 0;

		return $metrics;
	}

	public function getBensPorLocal($localId, $igrejaId)
	{
		$sql = "SELECT b.patrimonio_bem_id,
					   b.patrimonio_bem_codigo, -- Adicionado o campo aqui
					   b.patrimonio_bem_nome,
					   b.patrimonio_bem_descricao,
					   b.patrimonio_bem_data_aquisicao,
					   b.patrimonio_bem_valor,
					   l.patrimonio_local_nome
				FROM patrimonio_bens b
				JOIN patrimonio_locais l ON b.patrimonio_bem_local_id = l.patrimonio_local_id
				WHERE b.patrimonio_bem_local_id = ?
				  AND b.patrimonio_bem_igreja_id = ?
				  AND b.patrimonio_bem_status != 'baixado'
				ORDER BY b.patrimonio_bem_codigo ASC, b.patrimonio_bem_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$localId, $igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


}
