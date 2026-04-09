<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class SociedadeEvento
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllBySociedade($socId, $igrejaId) {
        $sql = "SELECT * FROM sociedades_eventos
                WHERE sociedade_evento_sociedade_id = ?
                AND sociedade_evento_igreja_id = ?
                ORDER BY sociedade_evento_data_hora_inicio DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$socId, $igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        $sql = "INSERT INTO sociedades_eventos (
                    sociedade_evento_igreja_id, sociedade_evento_sociedade_id,
                    sociedade_evento_titulo, sociedade_evento_descricao,
                    sociedade_evento_local, sociedade_evento_data_hora_inicio,
                    sociedade_evento_data_hora_fim, sociedade_evento_valor, sociedade_evento_status
                ) VALUES (:igreja_id, :soc_id, :titulo, :descricao, :local, :inicio, :fim, :valor, :status)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

	public function update($id, $data) {
		$sql = "UPDATE sociedades_eventos SET
					sociedade_evento_sociedade_id = :soc_id,
					sociedade_evento_titulo = :titulo,
					sociedade_evento_descricao = :descricao,
					sociedade_evento_local = :local,
					sociedade_evento_data_hora_inicio = :inicio,
					sociedade_evento_data_hora_fim = :fim,
					sociedade_evento_valor = :valor,
					sociedade_evento_status = :status
				WHERE sociedade_evento_id = :id
				AND sociedade_evento_igreja_id = :igreja_id";

		// Garantimos que o ID e a IGREJA estejam no array com os nomes corretos da query
		$data['id'] = $id;
		// O igreja_id já deve vir no array $data vindo do Controller, mas garantimos aqui se necessário.

		$stmt = $this->db->prepare($sql);

		// Se o execute falhar, ele retornará falso e não o Fatal Error se estiver bem mapeado
		return $stmt->execute($data);
	}

	// No seu Model (SociedadesEventos)
	public function getAllGlobal($idIgreja, $mes, $ano) {
		$sql = "SELECT e.*, s.sociedade_nome
				FROM sociedades_eventos e
				JOIN sociedades s ON e.sociedade_evento_sociedade_id = s.sociedade_id
				WHERE s.sociedade_igreja_id = :igrejaId
				AND MONTH(e.sociedade_evento_data_hora_inicio) = :mes
				AND YEAR(e.sociedade_evento_data_hora_inicio) = :ano
				ORDER BY e.sociedade_evento_data_hora_inicio ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([
			'igrejaId' => $idIgreja,
			'mes'      => $mes,
			'ano'      => $ano
		]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getDadosIgreja($igrejaId) {
		$sql = "SELECT igreja_nome, igreja_endereco FROM igrejas WHERE igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);

		// Fallback caso o endereço da igreja esteja vazio no banco
		if (!$res['igreja_endereco']) {
			$res['igreja_endereco'] = "Endereço da Sede não cadastrado";
		}
		return $res;
	}

	public function getMembrosEndereco($igrejaId) {
		$sql = "SELECT
					m.membro_nome,
					CONCAT(
						COALESCE(e.membro_endereco_rua, 'Sem rua'), ', ',
						COALESCE(e.membro_endereco_cidade, 'Sem cidade'), ' - ',
						COALESCE(e.membro_endereco_estado, '')
					) as membro_endereco
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				WHERE m.membro_igreja_id = ?
				AND m.membro_status = 'Ativo'
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

    public function delete($id, $igrejaId) {
        // Verificamos o ID da igreja por segurança, para um usuário não deletar evento de outra igreja via URL
        $sql = "DELETE FROM sociedades_eventos WHERE sociedade_evento_id = ? AND sociedade_evento_igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $igrejaId]);
    }
}
