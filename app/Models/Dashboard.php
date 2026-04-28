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
		// 1. Busca as configurações de cada sociedade no banco (Incluindo o campo logo)
		$sqlSoc = "SELECT sociedade_id, sociedade_nome, sociedade_genero,
						  sociedade_idade_min, sociedade_idade_max, sociedade_logo
				   FROM sociedades
				   WHERE sociedade_igreja_id = ? AND sociedade_status = 'Ativo'";
		$stmtSoc = $this->db->prepare($sqlSoc);
		$stmtSoc->execute([$igrejaId]);
		$configSociedades = $stmtSoc->fetchAll(\PDO::FETCH_ASSOC);

		// Inicializa a estrutura baseada no que existe no banco
		$sociedades = [];
		foreach ($configSociedades as $s) {
			// Extrai a sigla (ex: "UCP") do nome "UCP - União de..."
			$sigla = explode(' ', $s['sociedade_nome'])[0];
			$sociedades[$sigla] = [
				'id'        => $s['sociedade_id'],
				'genero'    => strtolower($s['sociedade_genero'] ?? 'ambos'),
				'idade_min' => (int)$s['sociedade_idade_min'],
				'idade_max' => (int)$s['sociedade_idade_max'],
				'logo'      => $s['sociedade_logo'], // <-- Campo adicionado aqui
				'real'      => 0,
				'potencial' => 0
			];
		}

		// 2. Busca TODOS os membros ativos para cálculo de potencial
		$sqlMembros = "SELECT membro_genero,
							  TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) as idade
					   FROM membros
					   WHERE membro_igreja_id = ? AND membro_status = 'Ativo'";
		$stmtMembros = $this->db->prepare($sqlMembros);
		$stmtMembros->execute([$igrejaId]);
		$membros = $stmtMembros->fetchAll(\PDO::FETCH_ASSOC);

		// 3. Calcula o POTENCIAL dinamicamente cruzando idade/gênero
		foreach ($membros as $m) {
			$idadeMembro = (int)$m['idade'];
			$genMembro   = strtolower($m['membro_genero'] ?? '');

			foreach ($sociedades as $sigla => &$dados) {
				$bateGenero = ($dados['genero'] == 'ambos' || $dados['genero'] == $genMembro);
				$bateIdade  = ($idadeMembro >= $dados['idade_min'] && $idadeMembro <= $dados['idade_max']);

				if ($bateGenero && $bateIdade) {
					$dados['potencial']++;
				}
			}
		}

		// 4. Busca o REAL (Matriculados na tabela sociedades_membros)
		foreach ($sociedades as $sigla => &$dados) {
			$sqlReal = "SELECT COUNT(*) FROM sociedades_membros
						WHERE sociedade_membro_sociedade_id = ?
						AND sociedade_membro_igreja_id = ?";
			$stmtReal = $this->db->prepare($sqlReal);
			$stmtReal->execute([$dados['id'], $igrejaId]);
			$dados['real'] = (int)$stmtReal->fetchColumn();
		}

		return $sociedades;
	}

	public function getTotalMembros($igrejaId) {
		$sql = "SELECT COUNT(*) FROM membros WHERE membro_igreja_id = ? AND membro_status = 'Ativo'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchColumn();
    }

	public function getMembrosAusentes($igrejaId) {
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_telefone,
					m.membro_email,
					TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) as idade
				FROM membros m
				WHERE m.membro_igreja_id = ?
				AND m.membro_status = 'Ativo'
				AND m.membro_id NOT IN (
					-- Presenças na EBD nos últimos 3 meses
					SELECT presenca_membro_id
					FROM classes_presencas
					WHERE presenca_data >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
					AND presenca_status = 1
				)
				AND m.membro_id NOT IN (
					-- Presenças em Sociedades nos últimos 3 meses
					SELECT sociedade_presenca_membro_id
					FROM sociedades_eventos_presencas
					WHERE sociedade_presenca_registrado_em >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
					AND sociedade_presenca_status = 'Presente'
				)
				ORDER BY m.membro_nome ASC
				LIMIT 10";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


}
