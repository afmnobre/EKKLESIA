<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class IgrejaEvento {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function listarTodos($igrejaId) {
        $sql = "SELECT * FROM igrejas_eventos WHERE evento_igreja_id = ? ORDER BY evento_data_hora_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function salvar($dados) {
		$sql = "INSERT INTO igrejas_eventos (
					evento_igreja_id,
					evento_titulo,
					evento_descricao,
					evento_data_hora_inicio,
					evento_data_hora_fim,
					evento_local,
					evento_cor,
					evento_status
				) VALUES (
					:igreja_id,
					:titulo,
					:descricao,
					:inicio,
					:fim,
					:local,
					:cor,
					:status
				)";

		$stmt = $this->db->prepare($sql);
		// O array $dados vindo do Controller já deve ter essas chaves exatas
		return $stmt->execute($dados);
	}

    public function excluir($id, $igrejaId) {
        $sql = "DELETE FROM igrejas_eventos WHERE evento_id = ? AND evento_igreja_id = ?";
        return $this->db->prepare($sql)->execute([$id, $igrejaId]);
    }

    public function buscarPorId($id, $igrejaId) {
		$sql = "SELECT * FROM igrejas_eventos WHERE evento_id = ? AND evento_igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id, $igrejaId]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function atualizar($dados) {
		$sql = "UPDATE igrejas_eventos SET
				evento_titulo = :titulo,
				evento_descricao = :descricao,
				evento_data_hora_inicio = :inicio,
				evento_data_hora_fim = :fim,
				evento_local = :local,
				evento_cor = :cor,
				evento_status = :status
				WHERE evento_id = :id AND evento_igreja_id = :igreja_id";
		return $this->db->prepare($sql)->execute($dados);
	}

	/**
	 * Busca um evento específico pelo ID
	 */
	public function getById($id) {
		$sql = "SELECT * FROM igrejas_eventos WHERE evento_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function getByPeriodo($igrejaId, $mes, $ano) {
		$sql = "SELECT * FROM igrejas_eventos
				WHERE evento_igreja_id = :igreja_id
				AND MONTH(evento_data_hora_inicio) = :mes
				AND YEAR(evento_data_hora_inicio) = :ano
				ORDER BY evento_data_hora_inicio ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			':igreja_id' => $igrejaId,
			':mes' => $mes,
			':ano' => $ano
		]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Método auxiliar para o select de anos
	public function getAnosComEventos($igrejaId) {
		$sql = "SELECT DISTINCT YEAR(evento_data_hora_inicio) as ano
				FROM igrejas_eventos
				WHERE evento_igreja_id = ?
				ORDER BY ano DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getEventoParaBanner($eventoId, $igrejaId)
	{
		$sql = "SELECT e.*, i.igreja_nome, i.igreja_logo
				FROM igrejas_eventos e
				JOIN igrejas i ON e.evento_igreja_id = i.igreja_id
				WHERE e.evento_id = ? AND e.evento_igreja_id = ?";

		$st = $this->db->prepare($sql);
		$st->execute([$eventoId, $igrejaId]);
		return $st->fetch(\PDO::FETCH_ASSOC);
	}




}
