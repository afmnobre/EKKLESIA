<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Boletim
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista todos os boletins de uma igreja específica
     */
    public function getAllByIgreja($igrejaId)
    {
        $sql = "SELECT b.*, m.membro_nome as autor_nome
                FROM igrejas_boletins b
                LEFT JOIN membros m ON b.igreja_boletim_autor_id = m.membro_id
                WHERE b.igreja_boletim_igreja_id = ?
                ORDER BY b.igreja_boletim_data DESC, b.igreja_boletim_num_historico DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um boletim específico pelo ID
     */
    public function getById($id, $igrejaId)
    {
        $stmt = $this->db->prepare("SELECT * FROM igrejas_boletins WHERE igreja_boletim_id = ? AND igreja_boletim_igreja_id = ?");
        $stmt->execute([$id, $igrejaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna o último número de boletim usado para sugerir o próximo (+1)
     */
    public function getUltimoNumero($igrejaId)
    {
        $stmt = $this->db->prepare("SELECT MAX(igreja_boletim_num_historico) as ultimo FROM igrejas_boletins WHERE igreja_boletim_igreja_id = ?");
        $stmt->execute([$igrejaId]);
        $res = $stmt->fetch();
        return $res['ultimo'] ?? 0;
    }

	public function salvar($dados)
	{
		if (!empty($dados['id'])) {
			// Lógica de UPDATE (Edição)
			$sql = "UPDATE igrejas_boletins SET
						igreja_boletim_num_historico = ?,
						igreja_boletim_data = ?,
						igreja_boletim_autor_id = ?,
						igreja_boletim_titulo = ?,
						igreja_boletim_mensagem = ?,
						igreja_boletim_status = ?
					WHERE igreja_boletim_id = ? AND igreja_boletim_igreja_id = ?";

			$stmt = $this->db->prepare($sql);
			return $stmt->execute([
				$dados['numero'],
				$dados['data'],
				$dados['autor_id'],
				$dados['titulo'],
				$dados['mensagem'],
				$dados['status'],
				$dados['id'],
				$dados['igreja_id']
			]);
		} else {
			// Lógica de INSERT (Novo)
			$sql = "INSERT INTO igrejas_boletins (
						igreja_boletim_igreja_id,
						igreja_boletim_num_historico,
						igreja_boletim_data,
						igreja_boletim_autor_id,
						igreja_boletim_titulo,
						igreja_boletim_mensagem,
						igreja_boletim_status
					) VALUES (?, ?, ?, ?, ?, ?, ?)";

			$stmt = $this->db->prepare($sql);
			return $stmt->execute([
				$dados['igreja_id'],
				$dados['numero'],
				$dados['data'],
				$dados['autor_id'],
				$dados['titulo'],
				$dados['mensagem'],
				$dados['status']
			]);
		}
	}

    public function excluir($id, $igrejaId)
    {
        $stmt = $this->db->prepare("DELETE FROM igrejas_boletins WHERE igreja_boletim_id = ? AND igreja_boletim_igreja_id = ?");
        return $stmt->execute([$id, $igrejaId]);
    }
}
