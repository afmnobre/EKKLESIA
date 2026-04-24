<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class EscolaDominical
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista as classes de uma igreja específica com o nome do professor
     */
    public function getClassesByIgreja($igrejaId)
    {
        $sql = "SELECT c.*, m.membro_nome as professor_nome
                FROM classes_escola c
                LEFT JOIN membros m ON c.classe_professor_id = m.membro_id
                WHERE c.classe_igreja_id = ?
                ORDER BY c.classe_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna detalhes de uma classe
     */
    public function getClasseById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM classes_escola WHERE classe_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarConfiguracoes($igrejaId) {
        $stmt = $this->db->prepare("SELECT * FROM classes_config WHERE config_igreja_id = ? ORDER BY config_idade_min ASC");
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvarConfiguracao($igrejaId, $nome, $min, $max) {
        $sql = "INSERT INTO classes_config (config_igreja_id, config_nome, config_idade_min, config_idade_max) VALUES (?, ?, ?, ?)";
        return $this->db->prepare($sql)->execute([$igrejaId, $nome, $min, $max]);
    }

	/**
	 * Lista os membros que já estão matriculados em uma classe específica
	 */
	public function getAlunosMatriculados($classeId)
	{
		$sql = "SELECT m.membro_id, m.membro_nome, m.membro_registro_interno, cm.classe_membro_id
				FROM classes_membros cm
				JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
				WHERE cm.classe_membro_classe_id = ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$classeId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca sugestões de membros não matriculados baseados na faixa etária da classe
	 */
	public function getSugestoesAlunos($igrejaId, $classeId)
	{
		// 1. Busca as idades da classe
		$classe = $this->getClasseById($classeId);
		if (!$classe) return [];

		$idadeMin = $classe['classe_idade_min'];
		$idadeMax = $classe['classe_idade_max'];

		// 2. SQL complexo: Calcula idade, filtra por faixa etária da classe,
		// garante que é da mesma igreja e que NÃO está em NENHUMA classe.
		$sql = "SELECT m.membro_id, m.membro_nome, m.membro_data_nascimento,
					   FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) as idade
				FROM membros m
				WHERE m.membro_igreja_id = ?
				  AND m.membro_status = 'Ativo'
				  AND FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) BETWEEN ? AND ?
				  AND m.membro_id NOT IN (SELECT classe_membro_membro_id FROM classes_membros)
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId, $idadeMin, $idadeMax]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Matricula um aluno na classe
	 */
	public function matricularAluno($classeId, $membroId)
	{
		// Verifica se já não está matriculado (segurança)
		$stCheck = $this->db->prepare("SELECT classe_membro_id FROM classes_membros WHERE classe_membro_classe_id = ? AND classe_membro_membro_id = ?");
		$stCheck->execute([$classeId, $membroId]);
		if ($stCheck->fetch()) return false;

		$sql = "INSERT INTO classes_membros (classe_membro_classe_id, classe_membro_membro_id) VALUES (?, ?)";
		return $this->db->prepare($sql)->execute([$classeId, $membroId]);
	}

	/**
	 * Remove um aluno da classe
	 */
	public function removerAluno($classeMembroId)
	{
		$sql = "DELETE FROM classes_membros WHERE classe_membro_id = ?";
		return $this->db->prepare($sql)->execute([$classeMembroId]);
	}

	public function inserirClasse($igrejaId, $nome, $professorId, $idadeMin, $idadeMax)
	{
		$sql = "INSERT INTO classes_escola
				(classe_igreja_id, classe_nome, classe_professor_id, classe_idade_min, classe_idade_max)
				VALUES (?, ?, ?, ?, ?)";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$igrejaId,
			$nome,
			$professorId,
			$idadeMin,
			$idadeMax
		]);
	}

	public function getAlunosEPresenca($classeId, $data)
	{
		$sql = "SELECT m.membro_id, m.membro_nome, m.membro_registro_interno,
					   p.presenca_status
				FROM classes_membros cm
				JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
				LEFT JOIN classes_presencas p ON p.presenca_membro_id = m.membro_id
					 AND p.presenca_classe_id = ?
					 AND p.presenca_data = ?
				WHERE cm.classe_membro_classe_id = ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$classeId, $data, $classeId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function salvarPresenca($classeId, $membroId, $data, $status)
	{
		// 1. Verificar se já existe registro de presença para este aluno neste dia nesta classe
		$sqlCheck = "SELECT presenca_id FROM classes_presencas
					 WHERE presenca_classe_id = ? AND presenca_membro_id = ? AND presenca_data = ?";
		$stCheck = $this->db->prepare($sqlCheck);
		$stCheck->execute([$classeId, $membroId, $data]);
		$registro = $stCheck->fetch();

		if ($registro) {
			// 2. Se já existe, atualiza o status
			$sql = "UPDATE classes_presencas SET presenca_status = ? WHERE presenca_id = ?";
			return $this->db->prepare($sql)->execute([$status, $registro['presenca_id']]);
		} else {
			// 3. Se não existe, cria um novo
			$sql = "INSERT INTO classes_presencas (presenca_classe_id, presenca_membro_id, presenca_data, presenca_status)
					VALUES (?, ?, ?, ?)";
			return $this->db->prepare($sql)->execute([$classeId, $membroId, $data, $status]);
		}
	}

	/**
	 * Busca o Top 5 Alunos com mais presenças (Assiduidade)
	 */
	public function getTopAssiduidade($igrejaId, $classeId = null) {
		$where = "WHERE m.membro_igreja_id = ?";
		$params = [$igrejaId];

		if ($classeId) {
			$where .= " AND p.presenca_classe_id = ?";
			$params[] = $classeId;
		}

		$sql = "SELECT m.membro_nome, COUNT(p.presenca_id) as total_presencas
				FROM classes_presencas p
				JOIN membros m ON p.presenca_membro_id = m.membro_id
				$where AND p.presenca_status = 1
				GROUP BY m.membro_id
				ORDER BY total_presencas DESC
				LIMIT 5";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Busca alunos sumidos há mais de 30 dias (Alerta de Evasão)
	 */
	public function getAlunosSumidos($igrejaId) {
		// AJUSTE: Troque 'membro_celular' pelo nome correto da sua coluna (ex: membro_telefone)
		$sql = "SELECT m.membro_nome, m.membro_telefone, MAX(p.presenca_data) as ultima_presenca,
					   DATEDIFF(CURRENT_DATE, MAX(p.presenca_data)) as dias_ausente,
					   c.classe_nome
				FROM classes_presencas p
				JOIN membros m ON p.presenca_membro_id = m.membro_id
				JOIN classes_escola c ON p.presenca_classe_id = c.classe_id
				WHERE m.membro_igreja_id = ?
				GROUP BY m.membro_id
				HAVING dias_ausente >= 30
				ORDER BY dias_ausente DESC
				LIMIT 5";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Calcula a taxa de ocupação (Matriculados vs Total da Igreja)
	 */
	public function getTaxaOcupacao($igrejaId)
	{
		// 1. Total de membros ativos na igreja
		$stMembros = $this->db->prepare("SELECT COUNT(membro_id) as total FROM membros WHERE membro_igreja_id = ? AND membro_status = 'Ativo'");
		$stMembros->execute([$igrejaId]);
		$totalIgreja = $stMembros->fetch()['total'] ?: 1; // <--- Variável definida aqui com $

		// 2. Total de membros matriculados
		$stEbd = $this->db->prepare("
			SELECT COUNT(DISTINCT classe_membro_membro_id) as total
			FROM classes_membros cm
			JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
			WHERE m.membro_igreja_id = ?
		");
		$stEbd->execute([$igrejaId]);
		$totalEbd = $stEbd->fetch()['total'];

		// CORREÇÃO AQUI: Adicione o $ na frente de totalIgreja
		$percentual = ($totalEbd / $totalIgreja) * 100;

		return [
			'total_igreja' => $totalIgreja,
			'total_ebd' => $totalEbd,
			'percentual' => round($percentual, 1)
		];
	}

	/**
	 * Compara presença do último domingo com o anterior
	 */
	public function getComparativoPresenca($igrejaId)
	{
		// Datas dos dois últimos domingos
		$hoje = date('Y-m-d');
		$ultimoDomingo = date('Y-m-d', strtotime('last sunday', strtotime($hoje . ' +1 day')));
		$domingoAnterior = date('Y-m-d', strtotime('-7 days', strtotime($ultimoDomingo)));

		// Query para contar presentes por data
		$sql = "SELECT COUNT(presenca_id) as total FROM classes_presencas p
				JOIN membros m ON p.presenca_membro_id = m.membro_id
				WHERE m.membro_igreja_id = ? AND p.presenca_data = ? AND p.presenca_status = 1";

		$st1 = $this->db->prepare($sql);
		$st1->execute([$igrejaId, $ultimoDomingo]);
		$presencaUltima = $st1->fetch()['total'];

		$st2 = $this->db->prepare($sql);
		$st2->execute([$igrejaId, $domingoAnterior]);
		$presencaAnterior = $st2->fetch()['total'];

		// Cálculo de tendência
		$tendencia = 'estavel';
		if ($presencaUltima > $presencaAnterior) $tendencia = 'subida';
		if ($presencaUltima < $presencaAnterior) $tendencia = 'descida';

		return [
			'atual' => $presencaUltima,
			'anterior' => $presencaAnterior,
			'tendencia' => $tendencia,
			'data_ref' => date('d/m', strtotime($ultimoDomingo))
		];
	}

	/**
	 * Distribuição de alunos por faixa etária (para gráfico)
	 */
	public function getDistribuicaoEtaria($igrejaId) {
		$sql = "SELECT
					CASE
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) < 12 THEN 'Crianças'
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) BETWEEN 12 AND 17 THEN 'Adolescentes'
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) BETWEEN 18 AND 29 THEN 'Jovens'
						ELSE 'Adultos'
					END as faixa,
					COUNT(*) as total
				FROM classes_membros cm
				JOIN membros m ON cm.classe_membro_membro_id = m.membro_id
				WHERE m.membro_igreja_id = ?
				GROUP BY faixa";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Total de matriculados vs membros ativos sem classe
	 */
	public function getResumoMatriculas($igrejaId) {
		// Matriculados
		$st1 = $this->db->prepare("SELECT COUNT(DISTINCT classe_membro_membro_id) as total FROM classes_membros cm
								   JOIN membros m ON cm.classe_membro_membro_id = m.membro_id WHERE m.membro_igreja_id = ?");
		$st1->execute([$igrejaId]);
		$matriculados = $st1->fetch()['total'];

		// Total Ativos
		$st2 = $this->db->prepare("SELECT COUNT(membro_id) as total FROM membros WHERE membro_igreja_id = ? AND membro_status = 'Ativo'");
		$st2->execute([$igrejaId]);
		$totalIgreja = $st2->fetch()['total'];

		return [
			'matriculados' => $matriculados,
			'disponiveis' => max(0, $totalIgreja - $matriculados),
			'total_igreja' => $totalIgreja
		];
    }

	/**
	 * Busca o Top 5 Alunos com mais presenças em uma CLASSE ESPECÍFICA
	 */
	public function getTopAssiduidadePorClasse($classeId) {
		$sql = "SELECT m.membro_nome, COUNT(p.presenca_id) as total_presencas
				FROM classes_presencas p
				JOIN membros m ON p.presenca_membro_id = m.membro_id
				WHERE p.presenca_classe_id = ? AND p.presenca_status = 1
				GROUP BY m.membro_id
				ORDER BY total_presencas DESC
				LIMIT 5";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$classeId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getDistribuicaoEtariaNaoMatriculados($igrejaId) {
		$sql = "SELECT
					CASE
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) < 12 THEN 'Crianças'
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) BETWEEN 12 AND 17 THEN 'Adolescentes'
						WHEN FLOOR(DATEDIFF(CURRENT_DATE, m.membro_data_nascimento) / 365.25) BETWEEN 18 AND 29 THEN 'Jovens'
						ELSE 'Adultos'
					END as faixa,
					COUNT(*) as total
				FROM membros m
				LEFT JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
				WHERE m.membro_igreja_id = ?
				  AND m.membro_status = 'Ativo'
				  AND cm.classe_membro_id IS NULL
				GROUP BY faixa";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Registra presença via leitura de QR Code (AJAX)
	 * Verifica se o aluno pertence à classe e registra para a data de hoje.
	 */
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

	/**
	 * Salva ou atualiza a presença do professor
	 */
	public function salvarPresencaProfessor($dados)
	{
		// Verifica se já existe registro para este professor nesta classe e data
		$sqlBusca = "SELECT presenca_id FROM classes_presencas
					 WHERE presenca_classe_id = ? AND presenca_data = ? AND presenca_tipo = 'professor'";
		$stmtBusca = $this->db->prepare($sqlBusca);
		$stmtBusca->execute([$dados['classe_id'], $dados['data']]);
		$existente = $stmtBusca->fetch();

		if ($existente) {
			$sql = "UPDATE classes_presencas SET
					presenca_membro_id = ?,
					presenca_status = ?,
					presenca_substituto_id = ?
					WHERE presenca_id = ?";
			return $this->db->prepare($sql)->execute([
				$dados['professor_id'],
				$dados['status'],
				$dados['substituto_id'] ?? null,
				$existente['presenca_id']
			]);
		} else {
			$sql = "INSERT INTO classes_presencas
					(presenca_classe_id, presenca_membro_id, presenca_data, presenca_status, presenca_tipo, presenca_substituto_id)
					VALUES (?, ?, ?, ?, 'professor', ?)";
			return $this->db->prepare($sql)->execute([
				$dados['classe_id'],
				$dados['professor_id'],
				$dados['data'],
				$dados['status'],
				$dados['substituto_id'] ?? null
			]);
		}
	}

	public function getAssiduidadeProfessoresAnual($igrejaId) {
		$sql = "SELECT
					ce.classe_id,
					MONTH(cp.presenca_data) AS mes,
					SUM(CASE WHEN cp.presenca_substituto_id IS NULL AND cp.presenca_status = 1 THEN 1 ELSE 0 END) as presencas,
					SUM(CASE WHEN cp.presenca_substituto_id IS NOT NULL THEN 1 ELSE 0 END) as faltas
				FROM classes_escola ce
				INNER JOIN classes_presencas cp ON ce.classe_id = cp.presenca_classe_id
				WHERE ce.classe_igreja_id = :igrejaId
				  AND cp.presenca_tipo = 'professor'
				  AND YEAR(cp.presenca_data) = YEAR(CURDATE())
				GROUP BY ce.classe_id, mes";

		return $this->db->prepare($sql, ['igrejaId' => $igrejaId]); // Ajuste conforme seu método de banco
	}

	public function getAssiduidadeProfessoresMensal($igrejaId) {
		$sql = "SELECT
					ce.classe_id,
					MONTH(cp.presenca_data) AS mes,
					SUM(CASE WHEN cp.presenca_substituto_id IS NULL AND cp.presenca_status = 1 THEN 1 ELSE 0 END) as presencas,
					SUM(CASE WHEN cp.presenca_substituto_id IS NOT NULL THEN 1 ELSE 0 END) as faltas
				FROM classes_escola ce
				INNER JOIN classes_presencas cp ON ce.classe_id = cp.presenca_classe_id
				WHERE ce.classe_igreja_id = :igrejaId
				  AND cp.presenca_tipo = 'professor'
				  AND YEAR(cp.presenca_data) = YEAR(CURDATE())
				GROUP BY ce.classe_id, mes";

		// Se o seu model estende uma classe que tem um método query personalizado, use-o.
		// Caso contrário, o código abaixo é o padrão para PDO nativo:
		$stmt = $this->db->prepare($sql);
		$stmt->bindValue(':igrejaId', $igrejaId, PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


}
