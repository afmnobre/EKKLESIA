<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Membro
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Método para descobrir o próximo ID antes de inserir
    public function getNextId()
    {
        $stmt = $this->db->query("SHOW TABLE STATUS LIKE 'membros'");
        $status = $stmt->fetch(PDO::FETCH_ASSOC);
        return $status['Auto_increment'];
    }

	public function getAll($igrejaId)
	{
		$sql = "SELECT m.*,
					   e.membro_endereco_rua,
					   e.membro_endereco_numero,
					   e.membro_endereco_complemento,
					   e.membro_endereco_bairro,
					   e.membro_endereco_cidade,
					   e.membro_endereco_estado,
					   e.membro_endereco_cep,
					   f.membro_foto_arquivo,
					   GROUP_CONCAT(c.cargo_nome SEPARATOR ', ') as cargos_nomes
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				LEFT JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				WHERE m.membro_igreja_id = ?
				GROUP BY m.membro_id
				ORDER BY m.membro_nome ASC"; // Dica: mudei para ordem alfabética para facilitar sua busca no Choices

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll();
	}

	public function getById($id, $igrejaId)
	{
		$sql = "SELECT m.*,
					   e.membro_endereco_rua,
					   e.membro_endereco_cidade,
					   e.membro_endereco_estado,
                       e.membro_endereco_cep
       			FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				WHERE m.membro_id = ? AND m.membro_igreja_id = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id, $igrejaId]);
		return $stmt->fetch();
	}

	public function insert($data)
	{
		$sql = "INSERT INTO membros (
					membro_igreja_id,
					membro_registro_interno,
					membro_nome,
					membro_rg,
					membro_cpf,
					membro_data_nascimento,
					membro_genero,
					membro_estado_civil,
					membro_email,
					membro_telefone,
					membro_data_batismo,
					membro_data_casamento,
					membro_status, -- Campo da tabela
					membro_data_criacao
				) VALUES (
					:igreja_id,
					:registro_interno,
					:nome,
					:rg,
					:cpf,
					:nascimento,
					:genero,
					:estado_civil,
					:email,
					:telefone,
					:batismo,
					:data_casamento,
                    :status,
                    NOW()
				)";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute($data);
	}

	public function update($id, $igrejaId, $data)
	{
		// Adicionado membro_rg e membro_cpf no SET
		$sql = "UPDATE membros SET
					membro_nome = :nome,
					membro_rg = :rg,
					membro_cpf = :cpf,
					membro_genero = :genero,
					membro_estado_civil = :estado_civil,
					membro_email = :email,
					membro_telefone = :telefone,
					membro_data_nascimento = :nascimento,
					membro_data_batismo = :batismo,
					membro_data_casamento = :data_casamento
				WHERE membro_id = :id AND membro_igreja_id = :igreja_id";

		try {
			$stmt = $this->db->prepare($sql);
			return $stmt->execute([
				'nome'           => $data['nome'],
				'rg'             => $data['rg'] ?? null,
				'cpf'            => $data['cpf'] ?? null,
				'genero'         => $data['genero'] ?? null,
				'estado_civil'   => $data['estado_civil'] ?? null,
				'email'          => $data['email'],
				'telefone'       => $data['telefone'],
				'nascimento'     => $data['data_nascimento'], // Chave vinda do Controller
				'batismo'        => $data['data_batismo'],    // Chave vinda do Controller
				'data_casamento' => $data['data_casamento'],
				'id'             => (int)$id,
				'igreja_id'      => (int)$igrejaId
			]);
		} catch (\PDOException $e) {
			error_log("Erro ao atualizar membro: " . $e->getMessage());
			return false;
		}
	}

	public function saveFoto($membroId, $nomeArquivo)
	{
		// 1. Verificar se já existe uma foto para este membro para retornar o nome antigo (para deletar o arquivo físico)
		$stmt = $this->db->prepare("SELECT membro_foto_arquivo FROM membros_fotos WHERE membro_foto_membro_id = ?");
		$stmt->execute([$membroId]);
		$fotoAntiga = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($fotoAntiga) {
			// Atualiza o registro existente
			$sql = "UPDATE membros_fotos SET membro_foto_arquivo = ? WHERE membro_foto_membro_id = ?";
			$this->db->prepare($sql)->execute([$nomeArquivo, $membroId]);
			return $fotoAntiga['membro_foto_arquivo']; // Retorna o nome do arquivo antigo para o Controller apagar
		} else {
			// Insere um novo registro
			$sql = "INSERT INTO membros_fotos (membro_foto_membro_id, membro_foto_arquivo) VALUES (?, ?)";
			$this->db->prepare($sql)->execute([$membroId, $nomeArquivo]);
			return true;
		}
	}

	public function updateStatus($id, $igrejaId, $status)
	{
		$sql = "UPDATE membros
				SET membro_status = :status
				WHERE membro_id = :id
				AND membro_igreja_id = :igreja_id";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			'status'    => $status,
			'id'        => $id,
			'igreja_id' => $igrejaId
		]);
	}

	public function insertHistorico($data)
	{
		$sql = "INSERT INTO membros_historico (
					membro_historico_membro_id,
					membro_historico_data,
					membro_historico_texto
				) VALUES (:membro_id, :data, :texto)";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			'membro_id' => $data['membro_id'],
			'data'      => $data['data'],
			'texto'     => $data['texto']
		]);
	}

	public function getHistorico($membroId)
	{
		$sql = "SELECT * FROM membros_historico
				WHERE membro_historico_membro_id = :membro_id
				ORDER BY membro_historico_data DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(['membro_id' => $membroId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Busca todos os cargos disponíveis no sistema para listar no modal
	public function getTodosCargos()
	{
		return $this->db->query("SELECT * FROM cargos ORDER BY cargo_nome ASC")->fetchAll(\PDO::FETCH_ASSOC);
	}

	// Busca apenas os IDs dos cargos que um membro específico já possui
	public function getCargosIdsByMembro($membroId)
	{
		$sql = "SELECT vinculo_cargo_id FROM membros_cargos_vinculo WHERE vinculo_membro_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId]);
		return $stmt->fetchAll(\PDO::FETCH_COLUMN); // Retorna um array simples de IDs [1, 5, 10]
	}

	// Salva os vínculos: Deleta os antigos e insere os novos selecionados
	public function saveCargosVinculo($membroId, $cargosIds)
	{
		$membroId = (int)$membroId;
		$cargosIds = is_array($cargosIds) ? $cargosIds : [];

		try {
			// DESATIVA a checagem de chaves estrangeiras temporariamente
			$this->db->exec("SET FOREIGN_KEY_CHECKS = 0");

			// 1. Limpa os vínculos
			$stmtDel = $this->db->prepare("DELETE FROM membros_cargos_vinculo WHERE vinculo_membro_id = ?");
			$stmtDel->execute([$membroId]);

			// 2. Insere os novos
			if (!empty($cargosIds)) {
				$stmtIns = $this->db->prepare("INSERT INTO membros_cargos_vinculo (vinculo_membro_id, vinculo_cargo_id) VALUES (?, ?)");
				foreach ($cargosIds as $cargoId) {
					$stmtIns->execute([$membroId, (int)$cargoId]);
				}
			}

			// REATIVA a checagem
			$this->db->exec("SET FOREIGN_KEY_CHECKS = 1");

			return true;
		} catch (\Exception $e) {
			$this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
			die("Exceção no Remoto: " . $e->getMessage());
		}
	}

	public function getDashboardStats($igrejaId)
	{
		$stats = [];

		// 1. Total de Membros Ativos
		$sqlAtivos = "SELECT COUNT(*) as total FROM membros WHERE membro_status = 'Ativo' AND membro_igreja_id = ?";
		$stmt = $this->db->prepare($sqlAtivos);
		$stmt->execute([$igrejaId]);
		$stats['ativos'] = $stmt->fetch()['total'];

		// 2. Novos Membros (Mês Atual) - Usando membro_data_criacao
		$sqlNovos = "SELECT COUNT(*) as total FROM membros
					 WHERE membro_igreja_id = ?
					 AND MONTH(membro_data_criacao) = MONTH(CURRENT_DATE)
					 AND YEAR(membro_data_criacao) = YEAR(CURRENT_DATE)";
		$stmt = $this->db->prepare($sqlNovos);
		$stmt->execute([$igrejaId]);
		$stats['novos_mes'] = $stmt->fetch()['total'];

		// 3. Distribuição por Gênero
		$sqlGenero = "SELECT IFNULL(membro_genero, 'Não Informado') as genero, COUNT(*) as total
					  FROM membros
					  WHERE membro_igreja_id = ?
					  GROUP BY membro_genero";
		$stmt = $this->db->prepare($sqlGenero);
		$stmt->execute([$igrejaId]);
		$stats['generos'] = $stmt->fetchAll();

		// 4. Faixa Etária (Demografia)
		$sqlEtaria = "SELECT
			IFNULL(SUM(CASE WHEN TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) <= 12 THEN 1 ELSE 0 END), 0) AS criancas,
			IFNULL(SUM(CASE WHEN TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) BETWEEN 13 AND 18 THEN 1 ELSE 0 END), 0) AS jovens,
			IFNULL(SUM(CASE WHEN TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) BETWEEN 19 AND 59 THEN 1 ELSE 0 END), 0) AS adultos,
			IFNULL(SUM(CASE WHEN TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) >= 60 THEN 1 ELSE 0 END), 0) AS idosos
			FROM membros WHERE membro_igreja_id = ?";
		$stmt = $this->db->prepare($sqlEtaria);
		$stmt->execute([$igrejaId]);
		$stats['faixa_etaria'] = $stmt->fetch();

		// 5. Aniversariantes do Mês (Nascimento) - ADICIONADO TIMESTAMPDIFF PARA IDADE
		$sqlAniv = "SELECT membro_nome,
						   DAY(membro_data_nascimento) as dia,
						   TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) as idade
					FROM membros
					WHERE membro_igreja_id = ?
					AND MONTH(membro_data_nascimento) = MONTH(CURRENT_DATE)
					ORDER BY dia ASC";
		$stmt = $this->db->prepare($sqlAniv);
		$stmt->execute([$igrejaId]);
		$stats['aniversariantes'] = $stmt->fetchAll();

		// 6. Aniversário de Batismo (Mês Atual)
		$sqlBatismo = "SELECT membro_nome, DAY(membro_data_batismo) as dia,
					   TIMESTAMPDIFF(YEAR, membro_data_batismo, CURDATE()) as anos
					   FROM membros WHERE membro_igreja_id = ?
					   AND MONTH(membro_data_batismo) = MONTH(CURRENT_DATE)
					   AND membro_data_batismo IS NOT NULL ORDER BY dia ASC";
		$stmt = $this->db->prepare($sqlBatismo);
		$stmt->execute([$igrejaId]);
		$stats['aniv_batismo'] = $stmt->fetchAll();

		// 7. NOVO: Aniversário de Casamento (Mês Atual)
		$sqlCasamento = "SELECT membro_nome,
								DAY(membro_data_casamento) as dia,
								TIMESTAMPDIFF(YEAR, membro_data_casamento, CURDATE()) as anos
						 FROM membros
						 WHERE membro_igreja_id = ?
						 AND MONTH(membro_data_casamento) = MONTH(CURRENT_DATE)
						 AND membro_estado_civil = 'Casado(a)'
						 AND membro_data_casamento IS NOT NULL
						 ORDER BY dia ASC";
		$stmt = $this->db->prepare($sqlCasamento);
		$stmt->execute([$igrejaId]);
		$stats['aniv_casamento'] = $stmt->fetchAll();

		// 8. Membros sem Cargo Atribuído (ajuste o índice se necessário)
		$sqlSemCargo = "SELECT COUNT(*) as total FROM membros m
						LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
						WHERE m.membro_igreja_id = ? AND v.vinculo_id IS NULL";
		$stmt = $this->db->prepare($sqlSemCargo);
		$stmt->execute([$igrejaId]);
		$stats['sem_cargo'] = $stmt->fetch()['total'];

		return $stats;
	}

	public function getEstatisticaEstadoCivil($igreja_id, $apenasMaiores = false) {
		$whereIdade = $apenasMaiores ? " AND (TIMESTAMPDIFF(YEAR, membro_data_nascimento, CURDATE()) >= 18)" : "";

		$sql = "SELECT membro_estado_civil as estado, COUNT(*) as total
				FROM membros
				WHERE membro_igreja_id = :igreja_id
				AND membro_estado_civil IS NOT NULL
				AND membro_estado_civil != ''
				$whereIdade
				GROUP BY membro_estado_civil
				ORDER BY total DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute(['igreja_id' => $igreja_id]);
		$dadosBD = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$labels = [];
		$valores = [];
		foreach ($dadosBD as $item) {
			$labels[] = $item['estado'];
			$valores[] = (int)$item['total'];
		}

		return ['labels' => $labels, 'valores' => $valores];
	}

	public function getEstatisticasBairro($idIgreja)
	{
		$sql = "SELECT TRIM(e.membro_endereco_bairro) as bairro, COUNT(*) as total
				FROM membros_enderecos e
				INNER JOIN membros m ON e.membro_endereco_membro_id = m.membro_id
				WHERE m.membro_igreja_id = :idIgreja
				AND e.membro_endereco_bairro IS NOT NULL
				AND e.membro_endereco_bairro != ''
				GROUP BY TRIM(e.membro_endereco_bairro)
				ORDER BY total DESC
				LIMIT 10";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':idIgreja', $idIgreja, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCargosNomesByMembro($membroId)
	{
		$sql = "SELECT c.cargo_nome
				FROM membros_cargos_vinculo v
				JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				WHERE v.vinculo_membro_id = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId]);
		$nomes = $stmt->fetchAll(\PDO::FETCH_COLUMN);

		// Retorna os nomes separados por vírgula ou "Membro" se não tiver cargo
		return !empty($nomes) ? implode(', ', $nomes) : 'Membro Comum';
	}

	public function getCargosParaVariosMembros($ids) {
		if (empty($ids)) return [];

		$placeholder = implode(',', array_fill(0, count($ids), '?'));
		$sql = "SELECT vinculo_membro_id, vinculo_cargo_id
				FROM membros_cargos_vinculo
				WHERE vinculo_membro_id IN ($placeholder)";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($ids);

		$res = [];
		// Usamos FETCH_ASSOC para garantir que o array venha limpo
		foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
			$res[$row['vinculo_membro_id']][] = $row['vinculo_cargo_id'];
		}
		return $res;
	}

    public function getIgrejaDados($idIgreja) {
        $sql = "SELECT * FROM igrejas WHERE igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idIgreja]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

	public function getHistoricosParaVariosMembros($ids) {
		if (empty($ids)) return [];

		$placeholder = implode(',', array_fill(0, count($ids), '?'));

		// NOME DA TABELA CORRIGIDO: membros_historico
		$sql = "SELECT * FROM membros_historico
				WHERE membro_historico_membro_id IN ($placeholder)
				ORDER BY membro_historico_data DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($ids);

		$res = [];
		foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
			$res[$row['membro_historico_membro_id']][] = $row;
		}
		return $res;
	}

	public function getDadosCertificado($membroId, $igrejaId)
	{
		$sql = "SELECT m.*, i.igreja_nome, i.igreja_cnpj, i.igreja_endereco, p.membro_nome as pastor_nome
				FROM membros m
				JOIN igrejas i ON m.membro_igreja_id = i.igreja_id
				LEFT JOIN membros p ON i.igreja_pastor_id = p.membro_id
				WHERE m.membro_id = ? AND m.membro_igreja_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId, $igrejaId]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca os dados de um membro específico pelo ID
	 */
	public function getMembroById($id)
	{
		$sql = "SELECT * FROM membros WHERE membro_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function buscarFiltrado($igrejaId, $letra = null, $busca = null)
	{
		$sql = "SELECT m.*,
					   e.membro_endereco_rua, e.membro_endereco_cidade,
					   e.membro_endereco_estado, e.membro_endereco_cep,
					   f.membro_foto_arquivo,
					   GROUP_CONCAT(c.cargo_nome SEPARATOR ', ') as cargos_nomes
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				LEFT JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				WHERE m.membro_igreja_id = :igrejaId";

		$params = [':igrejaId' => $igrejaId];

		// Se houver busca por texto (prioridade)
		if (!empty($busca)) {
			$sql .= " AND m.membro_nome LIKE :busca";
			$params[':busca'] = '%' . $busca . '%';
		}
		// Se não houver busca, mas houver letra (Abas)
		elseif (!empty($letra)) {
			$sql .= " AND m.membro_nome LIKE :letra";
			$params[':letra'] = $letra . '%';
		}

		$sql .= " GROUP BY m.membro_id ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getByIdCompleto($id, $igrejaId)
	{
		$sql = "SELECT
					m.*,
					f.membro_foto_arquivo,
					i.igreja_nome,
					i.igreja_endereco,
					i.igreja_cnpj,
					p.membro_nome as pastor_nome,
					-- ADICIONADO: CAMPOS DO ENDEREÇO
					e.membro_endereco_id,
					e.membro_endereco_cep,
					e.membro_endereco_rua,
					e.membro_endereco_numero,
					e.membro_endereco_complemento,
					e.membro_endereco_bairro,
					e.membro_endereco_cidade,
					e.membro_endereco_estado
				FROM membros m
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				LEFT JOIN igrejas i ON m.membro_igreja_id = i.igreja_id
				LEFT JOIN membros p ON i.igreja_pastor_id = p.membro_id
				-- ADICIONADO: JOIN COM A TABELA DE ENDEREÇOS
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				WHERE m.membro_id = :id AND m.membro_igreja_id = :igreja_id";

		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':igreja_id', $igrejaId);
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function saveEndereco($data)
	{
		// 1. Verifica se este membro já tem um endereço cadastrado
		$check = "SELECT membro_endereco_id FROM membros_enderecos WHERE membro_endereco_membro_id = :membro_id";
		$stmtCheck = $this->db->prepare($check);
		$stmtCheck->bindValue(':membro_id', $data['membro_id']);
		$stmtCheck->execute();
		$idExistente = $stmtCheck->fetchColumn();

		if ($idExistente) {
			// 2. Se existe, faz UPDATE
			$sql = "UPDATE membros_enderecos SET
						membro_endereco_rua = :rua,
						membro_endereco_numero = :numero,
						membro_endereco_complemento = :complemento,
						membro_endereco_bairro = :bairro,
						membro_endereco_cidade = :cidade,
						membro_endereco_estado = :estado,
						membro_endereco_cep = :cep
					WHERE membro_endereco_id = :id";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(':id', $idExistente);
		} else {
			// 3. Se não existe, faz INSERT
			$sql = "INSERT INTO membros_enderecos (
						membro_endereco_membro_id,
						membro_endereco_igreja_id,
						membro_endereco_rua,
						membro_endereco_numero,
						membro_endereco_complemento,
						membro_endereco_bairro,
						membro_endereco_cidade,
						membro_endereco_estado,
						membro_endereco_cep
					) VALUES (
						:membro_id, :igreja_id, :rua, :numero, :complemento, :bairro, :cidade, :estado, :cep
					)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(':membro_id', $data['membro_id']);
			$stmt->bindValue(':igreja_id', $data['igreja_id']);
		}

		// Bind dos valores comuns a ambos
		$stmt->bindValue(':rua', $data['rua']);
		$stmt->bindValue(':numero', $data['numero']);
		$stmt->bindValue(':complemento', $data['complemento']);
		$stmt->bindValue(':bairro', $data['bairro']);
		$stmt->bindValue(':cidade', $data['cidade']);
		$stmt->bindValue(':estado', $data['estado']);
		$stmt->bindValue(':cep', $data['cep']);

		return $stmt->execute();
	}

    public function updateSenha($membroId, $igrejaId, $novaSenhaHash) {
        $sql = "UPDATE membros SET membro_senha = ? WHERE membro_id = ? AND membro_igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$novaSenhaHash, $membroId, $igrejaId]);
    }

	// No Membro.php
	public function buscarCompletoParaBanner($igrejaId, $termo)
	{
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_registro_interno,
					f.membro_foto_arquivo,
					e.membro_endereco_rua,
					e.membro_endereco_numero,
					e.membro_endereco_complemento,
					e.membro_endereco_bairro,
					e.membro_endereco_cidade,
					e.membro_endereco_estado,
					(SELECT GROUP_CONCAT(c.cargo_nome SEPARATOR ', ')
					 FROM membros_cargos_vinculo v
					 JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
					 WHERE v.vinculo_membro_id = m.membro_id) as cargos
				FROM membros m
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				WHERE m.membro_igreja_id = :igreja AND m.membro_nome LIKE :termo
				LIMIT 20";

		$st = $this->db->prepare($sql);
		$st->execute([
			':igreja' => $igrejaId,
			':termo' => "%$termo%"
		]);

		$resultados = $st->fetchAll(\PDO::FETCH_ASSOC);

		// Formata o endereço completo para cada membro retornado
		foreach ($resultados as &$m) {
			$end = [];
			if (!empty($m['membro_endereco_rua'])) $end[] = $m['membro_endereco_rua'];
			if (!empty($m['membro_endereco_numero'])) $end[] = "nº " . $m['membro_endereco_numero'];
			if (!empty($m['membro_endereco_complemento'])) $end[] = "(" . $m['membro_endereco_complemento'] . ")";
			if (!empty($m['membro_endereco_bairro'])) $end[] = $m['membro_endereco_bairro'];
			if (!empty($m['membro_endereco_cidade'])) $end[] = $m['membro_endereco_cidade'];
			if (!empty($m['membro_endereco_estado'])) $end[] = $m['membro_endereco_estado'];

			$m['endereco_completo_formatado'] = implode(', ', $end);
		}

		return $resultados;
	}


}
