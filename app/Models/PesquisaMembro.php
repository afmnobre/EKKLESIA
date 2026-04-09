<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PesquisaMembro
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function pesquisar($igrejaId, $filtros)
    {
        // 1. Base da Query com Joins para Endereço, Fotos, Cargos e Sociedades
	    $sql = "SELECT m.*,
					   e.membro_endereco_rua, e.membro_endereco_cidade,
					   e.membro_endereco_bairro, e.membro_endereco_estado, e.membro_endereco_cep,
					   f.membro_foto_arquivo,
					   GROUP_CONCAT(DISTINCT c.cargo_nome SEPARATOR ', ') as cargos_nomes,
					   GROUP_CONCAT(DISTINCT s.sociedade_nome SEPARATOR ', ') as sociedades_nomes,
					   GROUP_CONCAT(DISTINCT cl.classe_nome SEPARATOR ', ') as classes_nomes
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				-- Vínculo de Cargos
				LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				LEFT JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				-- Vínculo de Sociedades
				LEFT JOIN sociedades_membros sm ON m.membro_id = sm.sociedade_membro_membro_id
				LEFT JOIN sociedades s ON sm.sociedade_membro_sociedade_id = s.sociedade_id
				-- Vínculo de Classes EBD
				LEFT JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
				LEFT JOIN classes_escola cl ON cm.classe_membro_classe_id = cl.classe_id
				WHERE m.membro_igreja_id = :igreja_id";

        $params = [':igreja_id' => $igrejaId];

        // --- FILTROS DE TEXTO ---
        if (!empty($filtros['nome'])) {
            $sql .= " AND m.membro_nome LIKE :nome";
            $params[':nome'] = '%' . $filtros['nome'] . '%';
        }
        if (!empty($filtros['email'])) {
            $sql .= " AND m.membro_email LIKE :email";
            $params[':email'] = '%' . $filtros['email'] . '%';
        }
        if (!empty($filtros['cidade'])) {
            $sql .= " AND e.membro_endereco_cidade LIKE :cidade";
            $params[':cidade'] = '%' . $filtros['cidade'] . '%';
        }
        if (!empty($filtros['genero'])) {
            $sql .= " AND m.membro_genero = :genero";
            $params[':genero'] = $filtros['genero'];
        }

        // --- FILTROS DE RELACIONAMENTO (N:N) ---

        //Cidades x Bairros
        if (!empty($filtros['bairro'])) {
            $sql .= " AND e.membro_endereco_bairro = :bairro";
            $params[':bairro'] = $filtros['bairro'];
        }

        // --- FILTRO DE CLASSE EBD ---
		if (!empty($filtros['classe_id'])) {
			if ($filtros['classe_id'] === 'sem_classe') {
				// Busca membros que NÃO estão matriculados em NENHUMA classe
				$sql .= " AND m.membro_id NOT IN (SELECT classe_membro_membro_id FROM classes_membros)";
			} else {
				$sql .= " AND m.membro_id IN (SELECT classe_membro_membro_id FROM classes_membros WHERE classe_membro_classe_id = :classe_id)";
				$params[':classe_id'] = $filtros['classe_id'];
			}
        }

        // Sociedade
		if (!empty($filtros['sociedade_id'])) {
			if ($filtros['sociedade_id'] === 'sem_sociedade') {
				// Busca membros que NÃO possuem vínculo em NENHUMA sociedade
				$sql .= " AND m.membro_id NOT IN (SELECT sociedade_membro_membro_id FROM sociedades_membros)";
			} else {
				$sql .= " AND m.membro_id IN (SELECT sociedade_membro_membro_id FROM sociedades_membros WHERE sociedade_membro_sociedade_id = :soc_id)";
				$params[':soc_id'] = $filtros['sociedade_id'];
			}
		}

		// Cargo
		if (!empty($filtros['cargo_id'])) {
			if ($filtros['cargo_id'] === 'sem_cargo') {
				// Busca membros que NÃO possuem nenhum cargo vinculado
				$sql .= " AND m.membro_id NOT IN (SELECT vinculo_membro_id FROM membros_cargos_vinculo)";
			} else {
				// Busca o cargo específico
				$sql .= " AND m.membro_id IN (SELECT vinculo_membro_id FROM membros_cargos_vinculo WHERE vinculo_cargo_id = :cargo_id)";
				$params[':cargo_id'] = $filtros['cargo_id'];
			}
		}

        // --- INTERVALOS DE DATAS ---
        if (!empty($filtros['nasc_ini'])) {
            $sql .= " AND m.membro_data_nascimento >= :nasc_ini";
            $params[':nasc_ini'] = $filtros['nasc_ini'];
        }
        if (!empty($filtros['nasc_fim'])) {
            $sql .= " AND m.membro_data_nascimento <= :nasc_fim";
            $params[':nasc_fim'] = $filtros['nasc_fim'];
        }
        if (!empty($filtros['bat_ini'])) {
            $sql .= " AND m.membro_data_batismo >= :bat_ini";
            $params[':bat_ini'] = $filtros['bat_ini'];
        }
        if (!empty($filtros['bat_fim'])) {
            $sql .= " AND m.membro_data_batismo <= :bat_fim";
            $params[':bat_fim'] = $filtros['bat_fim'];
        }
        if (!empty($filtros['cad_ini'])) {
            $sql .= " AND m.membro_data_criacao >= :cad_ini";
            $params[':cad_ini'] = $filtros['cad_ini'] . ' 00:00:00';
        }
        if (!empty($filtros['cad_fim'])) {
            $sql .= " AND m.membro_data_criacao <= :cad_fim";
            $params[':cad_fim'] = $filtros['cad_fim'] . ' 23:59:59';
        }

        $sql .= " GROUP BY m.membro_id ORDER BY m.membro_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSociedades($igrejaId) {
        $sql = "SELECT sociedade_id, sociedade_nome FROM sociedades WHERE sociedade_igreja_id = ? ORDER BY sociedade_nome ASC";
        $st = $this->db->prepare($sql);
        $st->execute([$igrejaId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCargos() {
        $sql = "SELECT cargo_id, cargo_nome FROM cargos ORDER BY cargo_nome ASC";
        $st = $this->db->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

	// Busca todas as cidades distintas que possuem membros cadastrados nesta igreja
	public function getCidadesCadastradas($igrejaId) {
		$sql = "SELECT DISTINCT membro_endereco_cidade
				FROM membros_enderecos
				WHERE membro_endereco_igreja_id = ?
				AND membro_endereco_cidade IS NOT NULL
				ORDER BY membro_endereco_cidade ASC";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	// Busca bairros baseados na cidade selecionada
	public function getBairrosPorCidade($igrejaId, $cidade) {
		$sql = "SELECT DISTINCT membro_endereco_bairro
				FROM membros_enderecos
				WHERE membro_endereco_igreja_id = ?
				AND membro_endereco_cidade = ?
				AND membro_endereco_bairro IS NOT NULL
				ORDER BY membro_endereco_bairro ASC";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId, $cidade]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getClassesEBD($igrejaId) {
		$sql = "SELECT classe_id, classe_nome FROM classes_escola WHERE classe_igreja_id = ? ORDER BY classe_nome ASC";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByIdCompleto($id, $igrejaId) {
		// Note que fazemos JOIN com endereços e fotos para vir tudo de uma vez
		$sql = "SELECT m.*, e.*, f.membro_foto_arquivo
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				WHERE m.membro_id = ? AND m.membro_igreja_id = ?";
		$st = $this->db->prepare($sql);
		$st->execute([$id, $igrejaId]);
		return $st->fetch(PDO::FETCH_ASSOC);
	}

	public function getHistorico($id) {
		// O nome da sua tabela é 'membros_historico' (singular) e o campo é 'membro_historico_texto'
		$sql = "SELECT * FROM membros_historico
				WHERE membro_historico_membro_id = ?
				ORDER BY membro_historico_data DESC";
		$st = $this->db->prepare($sql);
		$st->execute([$id]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCargosMembro($id) {
		// Usando a sua tabela 'membros_cargos_vinculo'
		$sql = "SELECT c.cargo_nome
				FROM membros_cargos_vinculo v
				INNER JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				WHERE v.vinculo_membro_id = ?";
		$st = $this->db->prepare($sql);
		$st->execute([$id]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	// Busca os familiares (quem é dependente do membro atual e de quem ele é dependente)
	public function getFamilia($membro_id) {
		$sql = "SELECT r.*, m.membro_nome, m.membro_id as id_parente
				FROM membros_responsaveis r
				JOIN membros m ON (r.parentesco_dependente_id = m.membro_id AND r.parentesco_responsavel_id = :id1)
				   OR (r.parentesco_responsavel_id = m.membro_id AND r.parentesco_dependente_id = :id2)";
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id1', $membro_id);
		$stmt->bindValue(':id2', $membro_id);
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Salva o vínculo
	public function vincularParente($dados) {
		$sql = "INSERT INTO membros_responsaveis (parentesco_responsavel_id, parentesco_dependente_id, parentesco_grau)
				VALUES (:resp, :dep, :grau)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute($dados);
	}

	/**
	 * Retorna uma lista simplificada de membros da mesma igreja para vinculação familiar
	 */
	public function getAllShort($igrejaId, $membroIdExcluir)
	{
		$sql = "SELECT membro_id, membro_nome
				FROM membros
				WHERE membro_igreja_id = :igreja_id
				AND membro_id != :membro_id
				ORDER BY membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':igreja_id', $igrejaId, \PDO::PARAM_INT);
		$stmt->bindValue(':membro_id', $membroIdExcluir, \PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


}
