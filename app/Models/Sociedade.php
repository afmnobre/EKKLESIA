<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Sociedade
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

	public function getAll($igrejaId)
	{
		// Fazemos um JOIN com a tabela membros usando a coluna sociedade_lider
		$sql = "SELECT s.*, m.membro_nome as nome_lider
				FROM sociedades s
				LEFT JOIN membros m ON s.sociedade_lider = m.membro_id
				WHERE s.sociedade_igreja_id = ?
				ORDER BY s.sociedade_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function insert($data)
    {
        $sql = "INSERT INTO sociedades (
                    sociedade_igreja_id,
                    sociedade_nome,
                    sociedade_tipo,
                    sociedade_genero,
                    sociedade_idade_min,
                    sociedade_idade_max,
                    sociedade_status
                ) VALUES (
                    :igreja_id,
                    :nome,
                    :tipo,
                    :genero,
                    :idade_min,
                    :idade_max,
                    :status
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    // ESTA É A FUNÇÃO QUE ESTÁ FALTANDO NO SEU ERRO
    public function update($id, $data)
    {
        $sql = "UPDATE sociedades SET
                    sociedade_nome = :nome,
                    sociedade_tipo = :tipo,
                    sociedade_genero = :genero,
                    sociedade_idade_min = :idade_min,
                    sociedade_idade_max = :idade_max,
                    sociedade_status = :status
                WHERE sociedade_id = :id AND sociedade_igreja_id = :igreja_id";

        $stmt = $this->db->prepare($sql);

        // Adicionamos o ID e o Igreja_ID aos dados para o WHERE
        $data['id'] = $id;

        return $stmt->execute($data);
    }

	/**
	 * Busca os dados de uma única sociedade
	 */
	public function getById($id, $igrejaId)
	{
		$sql = "SELECT * FROM sociedades WHERE sociedade_id = ? AND sociedade_igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id, $igrejaId]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * Busca membros aptos filtrando por gênero e idade (calculada no SQL)
	 */
	public function getMembrosAptos($igrejaId, $genero, $idadeMin, $idadeMax, $idSociedade)
	{
		// Se for "Ambos", não filtramos por gênero no SQL
		$filtroGenero = ($genero === 'Ambos') ? "" : "AND membro_genero = '$genero'";

		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_genero,
					TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) as idade,
					(SELECT COUNT(*) FROM sociedades_membros sm
					 WHERE sm.sociedade_membro_membro_id = m.membro_id
					 AND sm.sociedade_membro_sociedade_id = ?) as ja_pertence
				FROM membros m
				WHERE m.membro_igreja_id = ?
				AND m.membro_status = 'Ativo'
				$filtroGenero
				HAVING idade >= ? AND idade <= ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idSociedade, $igrejaId, $idadeMin, $idadeMax]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function saveLoteVinculo($igrejaId, $socId, $membrosIds) {
		try {
			$this->db->beginTransaction();
			// 1. Remove quem estava na sociedade para atualizar
			$sqlDel = "DELETE FROM sociedades_membros WHERE sociedade_membro_sociedade_id = ? AND sociedade_membro_igreja_id = ?";
			$this->db->prepare($sqlDel)->execute([$socId, $igrejaId]);

			// 2. Insere os novos selecionados
			$sqlIns = "INSERT INTO sociedades_membros (sociedade_membro_igreja_id, sociedade_membro_sociedade_id, sociedade_membro_membro_id, sociedade_membro_funcao) VALUES (?, ?, ?, 'Sócio')";
			$stmtIns = $this->db->prepare($sqlIns);
			foreach ($membrosIds as $mid) {
				$stmtIns->execute([$igrejaId, $socId, $mid]);
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	/**
	 * Busca todos os membros da igreja e marca quem já possui o cargo de líder desta sociedade
	 */
	public function getMembrosParaLideranca($igrejaId, $cargoId)
	{
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_status,
					(SELECT COUNT(*) FROM membros_cargos_vinculo v
					 WHERE v.vinculo_membro_id = m.membro_id
					 AND v.vinculo_cargo_id = ?) as tem_vinculo
				FROM membros m
				WHERE m.membro_igreja_id = ?
				AND m.membro_status = 'Ativo'
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$cargoId, $igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Salva o líder: remove o antigo e insere o novo (Garante 1 líder por cargo)
	 */
	public function salvarLider($igrejaId, $sociedadeId, $membroId, $cargoId)
	{
		try {
			$this->db->beginTransaction();

			// 1. Remove qualquer líder anterior deste CARGO nesta igreja
			$sqlDel = "DELETE v FROM membros_cargos_vinculo v
					  INNER JOIN membros m ON v.vinculo_membro_id = m.membro_id
					  WHERE v.vinculo_cargo_id = ? AND m.membro_igreja_id = ?";
			$this->db->prepare($sqlDel)->execute([$cargoId, $igrejaId]);

			// 2. Insere o novo vínculo
			$sqlIns = "INSERT INTO membros_cargos_vinculo (vinculo_membro_id, vinculo_cargo_id) VALUES (?, ?)";
			$this->db->prepare($sqlIns)->execute([$membroId, $cargoId]);

			// 3. ATUALIZA A TABELA SOCIEDADES (Para o LEFT JOIN do getAll funcionar)
			$sqlUp = "UPDATE sociedades SET sociedade_lider = ? WHERE sociedade_id = ? AND sociedade_igreja_id = ?";
			$this->db->prepare($sqlUp)->execute([$membroId, $sociedadeId, $igrejaId]);

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

    public function updateLogo($idSociedade, $idIgreja, $caminhoLogo)
    {
        $sql = "UPDATE sociedades SET sociedade_logo = ?
            WHERE sociedade_id = ? AND sociedade_igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$caminhoLogo, $idSociedade, $idIgreja]);
    }

	public function getDadosBanner($idSociedade)
	{
		// 1. Busca Sociedade + Igreja + Nome do Pastor
		$sqlSociedade = "SELECT s.*, i.igreja_id, i.igreja_nome, i.igreja_endereco, m.membro_nome as pastor_nome
						 FROM sociedades s
						 LEFT JOIN igrejas i ON i.igreja_id = s.sociedade_igreja_id
						 LEFT JOIN membros m ON m.membro_id = i.igreja_pastor_id
						 WHERE s.sociedade_id = ?";

		$stmtS = $this->db->prepare($sqlSociedade);
		$stmtS->execute([$idSociedade]);
		$sociedade = $stmtS->fetch(\PDO::FETCH_ASSOC);

		if (!$sociedade) return null;

		$idIgreja = $sociedade['igreja_id'];

		// 2. Busca Redes Sociais da Igreja
		$sqlRedes = "SELECT * FROM igrejas_redes_sociais WHERE rede_igreja_id = ? AND rede_status = 'ativo'";
		$stmtR = $this->db->prepare($sqlRedes);
		$stmtR->execute([$idIgreja]);
		$redes = $stmtR->fetchAll(\PDO::FETCH_ASSOC);

		// 3. Busca Membros da Sociedade + Foto + Endereço + Dados de Pasta
		$sqlMembros = "SELECT m.membro_id, m.membro_nome, m.membro_registro_interno, m.membro_igreja_id,
					   f.membro_foto_arquivo, e.membro_endereco_rua
					   FROM sociedades_membros sm
					   INNER JOIN membros m ON m.membro_id = sm.sociedade_membro_membro_id
					   LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
					   LEFT JOIN membros_enderecos e ON e.membro_endereco_membro_id = m.membro_id
					   WHERE sm.sociedade_membro_sociedade_id = ?";

		$stmtM = $this->db->prepare($sqlMembros);
		$stmtM->execute([$idSociedade]);
		$membros = $stmtM->fetchAll(\PDO::FETCH_ASSOC);

		return [
			'sociedade' => $sociedade,
			'redes'     => $redes,
			'membros'   => $membros
		];
	}

    public function updateLayout($idSociedade, $layoutJson)
    {
        // Usamos ? para simplificar o bind e evitar erro de parâmetro não definido
        $sql = "UPDATE sociedades SET sociedade_layout_config = ? WHERE sociedade_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$layoutJson, $idSociedade]);
    }

}
