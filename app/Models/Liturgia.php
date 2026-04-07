<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Liturgia
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retorna todas as liturgias com a contagem de itens
     */
    public function getAllByIgreja($igrejaId)
    {
        // Adicionado a subquery (SELECT COUNT...) para contar as partes
        $sql = "SELECT l.*,
                m_pregador.membro_nome as nome_membro_pregador,
                m_dirigente.membro_nome as nome_membro_dirigente,
                (SELECT COUNT(*) FROM igrejas_liturgias_itens WHERE liturgia_item_liturgia_id = l.igreja_liturgia_id) as total_itens
                FROM igrejas_liturgias l
                LEFT JOIN membros m_pregador ON l.igreja_liturgia_pregador_id = m_pregador.membro_id
                LEFT JOIN membros m_dirigente ON l.igreja_liturgia_dirigente_id = m_dirigente.membro_id
                WHERE l.igreja_liturgia_igreja_id = ?
                ORDER BY l.igreja_liturgia_data DESC";

        $st = $this->db->prepare($sql);
        $st->execute([$igrejaId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma liturgia específica e seus itens para edição
     */
	public function getById($id, $igrejaId)
	{
		// 1. Busca o cabeçalho da liturgia + Dados da Igreja + Nomes dos Membros
		$sql = "SELECT l.*,
                   i.igreja_nome, i.igreja_endereco, i.igreja_logo, i.igreja_id,
                   m_pregador.membro_nome as nome_membro_pregador,
                   m_pregador.membro_registro_interno as registro_pregador, -- NOVO
                   m_dirigente.membro_nome as nome_membro_dirigente,
                   m_dirigente.membro_registro_interno as registro_dirigente, -- NOVO
                   f_pregador.membro_foto_arquivo as foto_pregador,
                   f_dirigente.membro_foto_arquivo as foto_dirigente
            FROM igrejas_liturgias l
            INNER JOIN igrejas i ON l.igreja_liturgia_igreja_id = i.igreja_id
            LEFT JOIN membros m_pregador ON l.igreja_liturgia_pregador_id = m_pregador.membro_id
            LEFT JOIN membros m_dirigente ON l.igreja_liturgia_dirigente_id = m_dirigente.membro_id
            LEFT JOIN membros_fotos f_pregador ON l.igreja_liturgia_pregador_id = f_pregador.membro_foto_membro_id
            LEFT JOIN membros_fotos f_dirigente ON l.igreja_liturgia_dirigente_id = f_dirigente.membro_foto_membro_id
            WHERE l.igreja_liturgia_id = ? AND l.igreja_liturgia_igreja_id = ?";

		$st = $this->db->prepare($sql);
		$st->execute([$id, $igrejaId]);
		$liturgia = $st->fetch(PDO::FETCH_ASSOC);

		if ($liturgia) {
			// 2. Busca os itens (conforme já corrigimos antes)
			$sqlItems = "SELECT
							liturgia_item_tipo as tipo,
							liturgia_item_descricao as `desc`,
							liturgia_item_referencia as ref,
							liturgia_item_conteudo_api as conteudo
						 FROM igrejas_liturgias_itens
						 WHERE liturgia_item_liturgia_id = ?
						 ORDER BY liturgia_item_ordem ASC";

			$stItems = $this->db->prepare($sqlItems);
			$stItems->execute([$id]);
			$liturgia['itens'] = $stItems->fetchAll(PDO::FETCH_ASSOC);
		}

		return $liturgia;
	}

    /**
     * O seu método salvarCompleta já está preparado para salvar e atualizar!
     * Ele verifica if(!empty($dados['id'])), limpa os itens antigos e insere os novos.
     */
	public function salvarCompleta($dados, $itens)
	{
		try {
			$this->db->beginTransaction();

			if (!empty($dados['id'])) {
				// UPDATE - Adicionado igreja_liturgia_tema
				$sql = "UPDATE igrejas_liturgias SET
						igreja_liturgia_data = ?,
						igreja_liturgia_tema = ?,
						igreja_liturgia_pregador_id = ?,
						igreja_liturgia_pregador_nome = ?,
						igreja_liturgia_dirigente_id = ?,
						igreja_liturgia_dirigente_nome = ?
						WHERE igreja_liturgia_id = ? AND igreja_liturgia_igreja_id = ?";

				$st = $this->db->prepare($sql);
				$st->execute([
					$dados['data'],
					$dados['tema'],
					$dados['pregador_id'],
					$dados['pregador_nome'],
					$dados['dirigente_id'],
					$dados['dirigente_nome'],
					$dados['id'],
					$dados['igreja_id']
				]);
				$liturgiaId = $dados['id'];

				$this->db->prepare("DELETE FROM igrejas_liturgias_itens WHERE liturgia_item_liturgia_id = ?")
						 ->execute([$liturgiaId]);
			} else {
				// INSERT - Adicionado igreja_liturgia_tema
				$sql = "INSERT INTO igrejas_liturgias
						(igreja_liturgia_igreja_id, igreja_liturgia_data, igreja_liturgia_tema, igreja_liturgia_pregador_id,
						 igreja_liturgia_pregador_nome, igreja_liturgia_dirigente_id, igreja_liturgia_dirigente_nome)
						VALUES (?, ?, ?, ?, ?, ?, ?)";

				$st = $this->db->prepare($sql);
				$st->execute([
					$dados['igreja_id'],
					$dados['data'],
					$dados['tema'],
					$dados['pregador_id'],
					$dados['pregador_nome'],
					$dados['dirigente_id'],
					$dados['dirigente_nome']
				]);
				$liturgiaId = $this->db->lastInsertId();
			}

			// Inserção de itens com suporte ao conteúdo da API (LONGTEXT)
			if (!empty($itens)) {
				// Adicionado a coluna liturgia_item_conteudo_api e o sexto placeholder (?)
				$sqlItem = "INSERT INTO igrejas_liturgias_itens
							(liturgia_item_liturgia_id, liturgia_item_ordem, liturgia_item_descricao,
							 liturgia_item_tipo, liturgia_item_referencia, liturgia_item_conteudo_api)
							VALUES (?, ?, ?, ?, ?, ?)";
				$stItem = $this->db->prepare($sqlItem);

				foreach ($itens as $index => $item) {
					$stItem->execute([
						$liturgiaId,
						$index + 1,
						$item['descricao'],
						$item['tipo'],
						$item['referencia'] ?? null,
						$item['conteudo_api'] ?? null // <--- GRAVA O TEXTO LONGO DA BÍBLIA
					]);
				}
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function excluir($liturgiaId, $igrejaId)
	{
			try {
				$this->db->beginTransaction();

				// 1. Remove os itens da liturgia primeiro
				$sqlItens = "DELETE FROM igrejas_liturgias_itens WHERE liturgia_item_liturgia_id = ?";
				$stmt1 = $this->db->prepare($sqlItens);
				$stmt1->execute([$liturgiaId]);

				// 2. Remove a liturgia pai, validando a igreja
				$sqlLiturgia = "DELETE FROM igrejas_liturgias WHERE igreja_liturgia_id = ? AND igreja_liturgia_igreja_id = ?";
				$stmt2 = $this->db->prepare($sqlLiturgia);
				$stmt2->execute([$liturgiaId, $igrejaId]);

				// Verifica se algo foi realmente excluído na tabela pai
				if ($stmt2->rowCount() === 0) {
					throw new \Exception("Liturgia não encontrada ou não pertence a esta igreja.");
				}

				$this->db->commit();
				return true;
			} catch (\Exception $e) {
				$this->db->rollBack();
				return false;
			}
	}

	// No seu Model
	public function getHinoPorNumero($numero) {
		$sql = "SELECT titulo, letra FROM hinos_novo_cantico WHERE numero = ?";
		$st = $this->db->prepare($sql);
		$st->execute([$numero]);
		return $st->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Método auxiliar para processar a lista de itens e injetar as letras dos hinos
	 */
	public function processarItensHinos($itens) {
		if (empty($itens)) return [];

		foreach ($itens as &$item) {
			if (strtolower($item['tipo'] ?? '') == 'hino') {
				$numeroHino = preg_replace('/[^0-9]/', '', $item['desc'] ?? '');

				if (!empty($numeroHino)) {
					$hinoData = $this->getHinoPorNumero((int)$numeroHino);
					if ($hinoData) {
						$item['hino_titulo'] = trim(preg_replace('/\s+/', ' ', $hinoData['titulo']));

						$letra = $hinoData['letra'];

						// 1. Remove as tags do Quelea {it}, {/it}, etc.
						$letra = preg_replace('/\{.*?\}/', '', $letra);

						// 2. Remove espaços em branco e TABULAÇÕES no início e fim de cada linha
						// O modificador /m (multiline) aplica a regra a cada linha individualmente
						$letra = preg_replace('/^[ \t\r]+|[ \t\r]+$/m', '', $letra);

						// 3. Normaliza as quebras de linha: transforma qualquer sequência de
						// 3 ou mais quebras em apenas 2 (uma linha em branco entre estrofes)
						$letra = preg_replace("/(\r\n|\n|\r){3,}/", "\n\n", $letra);

						// 4. Se o espaçamento entre linhas comuns ainda estiver duplo,
						// reduzimos de 2 quebras para 1 dentro da estrofe.
						$letra = preg_replace("/(\r\n|\n|\r){2,}/", "\n\n", $letra);

						$item['hino_letra'] = trim($letra);
					}
				}
			}
		}
		return $itens;
	}



}
