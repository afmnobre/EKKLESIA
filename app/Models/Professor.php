<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Professor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Busca os alunos da classe e o status de presença (P, F ou NULL)
     */
    public function getAlunosEPresenca($classeId, $data) {
        $sql = "SELECT
                    m.membro_id,
                    m.membro_nome,
                    p.presenca_status AS presenca
                FROM membros m
                JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
                LEFT JOIN classes_presencas p ON (
                    m.membro_id = p.presenca_membro_id
                    AND p.presenca_data = ?
                    AND p.presenca_classe_id = ?
                )
                WHERE cm.classe_membro_classe_id = ?
                ORDER BY m.membro_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data, $classeId, $classeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClasseById($classeId) {
        $sql = "SELECT * FROM classes_escola WHERE classe_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	/**
	 * Busca todos os membros da igreja que estão na faixa etária da classe
	 * e que ainda NÃO estão em nenhuma classe (ou na classe atual)
	 */
	public function getMembrosDisponiveis($igrejaId, $idadeMin, $idadeMax) {
		$sql = "SELECT m.membro_id, m.membro_nome, m.membro_data_nascimento,
				FLOOR(DATEDIFF(CURDATE(), m.membro_data_nascimento) / 365.25) as idade
				FROM membros m
				LEFT JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
				WHERE m.membro_igreja_id = ?
				AND cm.classe_membro_id IS NULL
				HAVING idade BETWEEN ? AND ?
				ORDER BY m.membro_nome ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $idadeMin, $idadeMax]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function adicionarMembroAClasse($membroId, $classeId) {
		$sql = "INSERT INTO classes_membros (classe_membro_membro_id, classe_membro_classe_id) VALUES (?, ?)";
		return $this->db->prepare($sql)->execute([$membroId, $classeId]);
	}

	public function removerMembroDaClasse($membroId, $classeId) {
		$sql = "DELETE FROM classes_membros WHERE classe_membro_membro_id = ? AND classe_membro_classe_id = ?";
		return $this->db->prepare($sql)->execute([$membroId, $classeId]);
	}

	public function getRelatorioPresencas($classeId, $mes, $ano) {
		$sql = "SELECT
					m.membro_nome,
					COUNT(p.presenca_id) as total_aulas,
					SUM(CASE WHEN p.presenca_status = 1 THEN 1 ELSE 0 END) as presencas,
					SUM(CASE WHEN p.presenca_status = 0 THEN 1 ELSE 0 END) as faltas,
					ROUND((SUM(CASE WHEN p.presenca_status = 1 THEN 1 ELSE 0 END) / COUNT(p.presenca_id)) * 100) as frequencia
				FROM membros m
				JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
				LEFT JOIN classes_presencas p ON m.membro_id = p.presenca_membro_id
					AND MONTH(p.presenca_data) = ?
					AND YEAR(p.presenca_data) = ?
					AND p.presenca_classe_id = ?
				WHERE cm.classe_membro_classe_id = ?
				GROUP BY m.membro_id
				ORDER BY frequencia DESC, m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$mes, $ano, $classeId, $classeId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

 	public function registrarPresencaQRCode($igrejaId, $classeId, $codigoLido) {
		$hoje = date('Y-m-d');

		// 1. Busca o membro pelo registro interno e valida se está matriculado nesta classe
		$sqlCheckMatricula = "SELECT m.membro_id, m.membro_nome
							  FROM membros m
							  JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
							  WHERE cm.classe_membro_classe_id = ?
								AND m.membro_registro_interno = ?
								AND m.membro_igreja_id = ?
								AND m.membro_status = 'Ativo'";

		$stmtCheck = $this->db->prepare($sqlCheckMatricula);
		$stmtCheck->execute([$classeId, $codigoLido, $igrejaId]);
		$aluno = $stmtCheck->fetch();

		if (!$aluno) {
			return [
				'status' => 'erro',
				'mensagem' => 'Registro ' . $codigoLido . ' não encontrado ou aluno não matriculado nesta classe.'
			];
		}

		$membroId = $aluno['membro_id'];
		$primeiroNome = explode(' ', trim($aluno['membro_nome']))[0];

		// 2. Verificação de Duplicidade (presenca_data)
		$sqlCheckPresenca = "SELECT COUNT(*) as ja_marcou
							 FROM classes_presencas
							 WHERE presenca_classe_id = ?
							   AND presenca_membro_id = ?
							   AND presenca_data = ?";

		$stmtPresenca = $this->db->prepare($sqlCheckPresenca);
		$stmtPresenca->execute([$classeId, $membroId, $hoje]);
		$resPresenca = $stmtPresenca->fetch();

		if ($resPresenca && $resPresenca['ja_marcou'] > 0) {
			return [
				'status' => 'aviso',
				'mensagem' => "{$primeiroNome} já registrou presença hoje."
			];
		}

		// 3. Inserção final respeitando as colunas da sua tabela classes_presencas
		$sqlInsert = "INSERT INTO classes_presencas
					  (presenca_classe_id, presenca_membro_id, presenca_data, presenca_status)
					  VALUES (?, ?, ?, 1)";

		$stmtInsert = $this->db->prepare($sqlInsert);

		if ($stmtInsert->execute([$classeId, $membroId, $hoje])) {
			return [
				'status' => 'sucesso',
				'mensagem' => "Presença de {$primeiroNome} confirmada!"
			];
		}

		return [
			'status' => 'erro',
			'mensagem' => 'Erro interno ao salvar presença no banco.'
		];
	}


}
