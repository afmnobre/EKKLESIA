<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Dashboard
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getMetricasEBD($igrejaId) {
        // Busca todas as classes e a contagem de matriculados vs potencial (baseado na idade da classe)
        $sql = "SELECT
                    ce.classe_nome,
                    ce.classe_idade_min,
                    ce.classe_idade_max,
                    (SELECT COUNT(*) FROM classes_membros cm WHERE cm.classe_membro_classe_id = ce.classe_id) as matriculados,
                    (SELECT COUNT(*) FROM membros m
                     WHERE m.membro_igreja_id = ?
                     AND m.membro_status = 'Ativo'
                     AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) BETWEEN ce.classe_idade_min AND ce.classe_idade_max) as potencial
                FROM classes_escola ce
                WHERE ce.classe_igreja_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId, $igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function getMetricasSociedades($igrejaId) {
		// 1. Inicializa a estrutura
		$sociedades = [
			'UCP' => ['real' => 0, 'potencial' => 0],
			'UPA' => ['real' => 0, 'potencial' => 0],
			'UMP' => ['real' => 0, 'potencial' => 0],
			'SAF' => ['real' => 0, 'potencial' => 0],
			'UPH' => ['real' => 0, 'potencial' => 0],
		];

		// 2. Busca TODOS os membros ativos para calcular o POTENCIAL (Perfil)
		$sqlMembros = "SELECT membro_id, membro_genero,
							  TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) as idade
					   FROM membros
					   WHERE membro_igreja_id = ? AND membro_status = 'Ativo'";

		$stmtMembros = $this->db->prepare($sqlMembros);
		$stmtMembros->execute([$igrejaId]);
		$todosMembros = $stmtMembros->fetchAll(\PDO::FETCH_ASSOC);

		foreach ($todosMembros as $m) {
			$idade = $m['idade'];
			$gen = strtoupper($m['membro_genero'] ?? '');

			// Define onde o membro PODERIA estar (Potencial)
			if ($idade <= 11) $soc = 'UCP';
			elseif ($idade <= 18) $soc = 'UPA';
			elseif ($idade <= 35) $soc = 'UMP';
			elseif ($gen == 'FEMININO' || $gen == 'MULHER') $soc = 'SAF';
			else $soc = 'UPH';

			$sociedades[$soc]['potencial']++;
		}

		// 3. Busca o REAL (Quem realmente está matriculado em classes de EBD dessas sociedades)
		// Aqui fazemos um JOIN para contar quantos membros de cada perfil estão em classes_membros
		$sqlReal = "SELECT
					(SELECT COUNT(DISTINCT cm.classe_membro_membro_id)
					 FROM classes_membros cm
					 JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
					 WHERE m.membro_igreja_id = ? AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) <= 11) as real_ucp,

					(SELECT COUNT(DISTINCT cm.classe_membro_membro_id)
					 FROM classes_membros cm
					 JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
					 WHERE m.membro_igreja_id = ? AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) BETWEEN 12 AND 18) as real_upa,

					(SELECT COUNT(DISTINCT cm.classe_membro_membro_id)
					 FROM classes_membros cm
					 JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
					 WHERE m.membro_igreja_id = ? AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) BETWEEN 19 AND 35) as real_ump,

					(SELECT COUNT(DISTINCT cm.classe_membro_membro_id)
					 FROM classes_membros cm
					 JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
					 WHERE m.membro_igreja_id = ? AND (m.membro_genero = 'Feminino' OR m.membro_genero = 'Mulher') AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) > 35) as real_saf,

					(SELECT COUNT(DISTINCT cm.classe_membro_membro_id)
					 FROM classes_membros cm
					 JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
					 WHERE m.membro_igreja_id = ? AND (m.membro_genero = 'Masculino' OR m.membro_genero = 'Homem') AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) > 35) as real_uph";

		$stmtReal = $this->db->prepare($sqlReal);
		$stmtReal->execute([$igrejaId, $igrejaId, $igrejaId, $igrejaId, $igrejaId]);
		$reais = $stmtReal->fetch(\PDO::FETCH_ASSOC);

		$sociedades['UCP']['real'] = $reais['real_ucp'];
		$sociedades['UPA']['real'] = $reais['real_upa'];
		$sociedades['UMP']['real'] = $reais['real_ump'];
		$sociedades['SAF']['real'] = $reais['real_saf'];
		$sociedades['UPH']['real'] = $reais['real_uph'];

		return $sociedades;
	}
}
