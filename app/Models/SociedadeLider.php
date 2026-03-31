<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class SociedadeLider {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

	/**
	 * Realiza a autenticação comparando o celular COM MÁSCARA
	 */
	public function login($celularComMascara) {
		// Não limpamos mais os caracteres, pois o banco armazena com a máscara
		$sql = "SELECT
					s.sociedade_id,
					s.sociedade_nome,
					s.sociedade_senha,
					m.membro_id,
					m.membro_nome,
					m.membro_igreja_id
				FROM sociedades s
				INNER JOIN membros m ON s.sociedade_lider = m.membro_id
				WHERE m.membro_telefone = ?
				AND s.sociedade_status = 'Ativo'
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$celularComMascara]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    /**
     * Busca a sociedade onde o membro logado é o líder oficial
     */
	public function getSociedadeVinculada($membroId) {
		$sql = "SELECT
					s.*,
					m.membro_nome,
					m.membro_igreja_id,
					m.membro_registro_interno,
					mf.membro_foto_arquivo as lider_foto
				FROM sociedades s
				INNER JOIN membros m ON s.sociedade_lider = m.membro_id
				LEFT JOIN membros_fotos mf ON m.membro_id = mf.membro_foto_membro_id
				WHERE s.sociedade_lider = ?
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca todas as sociedades ativas para exibir os logos na tela de login
	 * O filtro por igreja_id é opcional, dependendo de como você quer exibir (Geral ou por Igreja)
	 */
	public function getTodasSociedadesAtivas($idIgreja = null) {
		$sql = "SELECT
					sociedade_id,
					sociedade_nome,
					sociedade_logo
				FROM sociedades
				WHERE sociedade_status = 'Ativo'";

		// Se você quiser filtrar apenas as sociedades da igreja do usuário logado/configurado
		if ($idIgreja) {
			$sql .= " AND sociedade_igreja_id = :igreja_id";
		}

		$sql .= " ORDER BY sociedade_nome ASC";

		$stmt = $this->db->prepare($sql);

		if ($idIgreja) {
			$stmt->bindParam(':igreja_id', $idIgreja, PDO::PARAM_INT);
		}

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca o nome da igreja pelo ID
	 */
	public function getNomeIgreja($idIgreja) {
		$sql = "SELECT igreja_nome FROM igrejas WHERE igreja_id = ? LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idIgreja]);
		$igreja = $stmt->fetch(PDO::FETCH_ASSOC);
		return $igreja['igreja_nome'] ?? 'Igreja Presbiteriana'; // Fallback se não encontrar
    }

    /**
     * Lista os membros que já estão vinculados à sociedade
     */
	public function getMeusMembros($idSociedade) {
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_igreja_id,
					m.membro_registro_interno,
					mf.membro_foto_arquivo,
					sm.sociedade_membro_funcao
				FROM sociedades_membros sm
				JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
				LEFT JOIN membros_fotos mf ON m.membro_id = mf.membro_foto_membro_id
				WHERE sm.sociedade_membro_sociedade_id = ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idSociedade]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
     * Sugere membros da igreja que batem com o perfil da sociedade
     * (idade e gênero) mas que ainda NÃO estão nela.
     */
    public function getSugestoesNovosMembros($idSociedade, $idIgreja) {
        $sqlRegras = "SELECT sociedade_genero, sociedade_idade_min, sociedade_idade_max
                      FROM sociedades WHERE sociedade_id = ?";
        $stmtRegras = $this->db->prepare($sqlRegras);
        $stmtRegras->execute([$idSociedade]);
        $regra = $stmtRegras->fetch(PDO::FETCH_ASSOC);

        if (!$regra) return [];

        $filtroGenero = ($regra['sociedade_genero'] !== 'Ambos') ? "AND m.membro_genero = ?" : "";

        $sql = "SELECT m.membro_id, m.membro_nome,
                FLOOR(DATEDIFF(CURDATE(), m.membro_data_nascimento) / 365.25) as idade
                FROM membros m
                WHERE m.membro_igreja_id = ?
                AND m.membro_status = 'Ativo'
                $filtroGenero
                AND m.membro_id NOT IN (
                    SELECT sociedade_membro_membro_id
                    FROM sociedades_membros
                    WHERE sociedade_membro_sociedade_id = ?
                )
                HAVING idade BETWEEN ? AND ?
                ORDER BY m.membro_nome ASC";

        $params = [$idIgreja];
        if ($regra['sociedade_genero'] !== 'Ambos') $params[] = $regra['sociedade_genero'];
        $params[] = $idSociedade;
        $params[] = $regra['sociedade_idade_min'];
        $params[] = $regra['sociedade_idade_max'];

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca os eventos agendados apenas para esta sociedade
     */
    public function getMeusEventos($idSociedade) {
        $sql = "SELECT * FROM sociedades_eventos
                WHERE sociedade_evento_sociedade_id = ?
                ORDER BY sociedade_evento_data_hora_inicio ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idSociedade]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function vincularMembro() {
		// Segurança: verifica se o líder está logado
		if (!isset($_SESSION['membro_id']) || !isset($_SESSION['sociedade_ativa_id'])) {
			header('Content-Type: application/json');
			echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
			exit;
		}

		$membroId = $_POST['membro_id'] ?? null;
		$idSociedade = $_SESSION['sociedade_ativa_id'];

		if ($membroId && $idSociedade) {
			$db = \App\Core\Database::getInstance();

			// Evita duplicidade (verifica se já não é membro)
			$check = $db->prepare("SELECT 1 FROM sociedades_membros WHERE sociedade_membro_sociedade_id = ? AND sociedade_membro_membro_id = ?");
			$check->execute([$idSociedade, $membroId]);

			if ($check->fetch()) {
				echo json_encode(['sucesso' => false, 'erro' => 'Membro já vinculado.']);
				exit;
			}

			// Insere o vínculo
			$sql = "INSERT INTO sociedades_membros (sociedade_membro_sociedade_id, sociedade_membro_membro_id, sociedade_membro_funcao) VALUES (?, ?, 'Sócio')";
			$stmt = $db->prepare($sql);
			$res = $stmt->execute([$idSociedade, $membroId]);

			header('Content-Type: application/json');
			echo json_encode(['sucesso' => $res]);
		} else {
			echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos.']);
		}
		exit;
    }

	/**
	 * Remove o vínculo de um membro com a sociedade do líder logado
	 */
	public function desvincularMembro($membroId, $idSociedade) {
		$sql = "DELETE FROM sociedades_membros
				WHERE sociedade_membro_sociedade_id = ?
				AND sociedade_membro_membro_id = ?";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$idSociedade, $membroId]);
	}

	/**
	 * Busca o histórico de observações de um membro específico
	 */
	public function getHistoricoMembro($membroId) {
		$sql = "SELECT * FROM membros_historico
				WHERE membro_historico_membro_id = :id
				ORDER BY membro_historico_data DESC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([':id' => $membroId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Salva uma nova observação no histórico
	 */
	public function salvarObservacao($membroId, $texto) {
		$sql = "INSERT INTO membros_historico (membro_historico_membro_id, membro_historico_data, membro_historico_texto)
				VALUES (:id, NOW(), :texto)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			':id' => $membroId,
			':texto' => $texto
		]);
	}

	public function salvarEvento($dados) {
		$sql = "INSERT INTO sociedades_eventos (
					sociedade_evento_igreja_id,
					sociedade_evento_sociedade_id,
					sociedade_evento_titulo,
					sociedade_evento_descricao,
					sociedade_evento_local,
					sociedade_evento_data_hora_inicio,
					sociedade_evento_data_hora_fim,
					sociedade_evento_valor,
					sociedade_evento_status
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Agendado')";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['igreja_id'],
			$dados['sociedade_id'],
			$dados['titulo'],
			$dados['descricao'],
			$dados['local'],
			$dados['data_inicio'],
			$dados['data_fim'],
			$dados['valor']
		]);
	}

	/**
	 * Busca o endereço completo da igreja
	 */
	public function getEnderecoIgreja($idIgreja) {
		$sql = "SELECT igreja_endereco FROM igrejas WHERE igreja_id = ? LIMIT 1";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idIgreja]);
		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
		return $resultado['igreja_endereco'] ?? null;
	}

	/**
	 * Busca os membros da sociedade com seus respectivos endereços
	 */
	public function getMembrosComEndereco($idSociedade) {
		$sql = "SELECT
					m.membro_nome,
					me.membro_endereco_rua,
					me.membro_endereco_numero,
					me.membro_endereco_complemento,
					me.membro_endereco_bairro,
					me.membro_endereco_cidade,
					me.membro_endereco_cep
				FROM sociedades_membros sm
				INNER JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
				INNER JOIN membros_enderecos me ON m.membro_id = me.membro_endereco_membro_id
				WHERE sm.sociedade_membro_sociedade_id = ?
				AND me.membro_endereco_rua IS NOT NULL";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idSociedade]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Exclui um evento garantindo que ele pertença à sociedade do líder logado
	 */
	public function excluirEvento($idEvento, $idSociedade) {
		$sql = "DELETE FROM sociedades_eventos
				WHERE sociedade_evento_id = ?
				AND sociedade_evento_sociedade_id = ?";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$idEvento, $idSociedade]);
	}

	/**
	 * Atualiza os dados de um evento existente
	 */
	public function atualizarEvento($idEvento, $idSociedade, $dados) {
		$sql = "UPDATE sociedades_eventos SET
					sociedade_evento_titulo = :titulo,
					sociedade_evento_descricao = :descricao,
					sociedade_evento_local = :local,
					sociedade_evento_data_hora_inicio = :inicio,
					sociedade_evento_data_hora_fim = :fim,
					sociedade_evento_valor = :valor,
					sociedade_evento_status = :status
				WHERE sociedade_evento_id = :id
				AND sociedade_evento_sociedade_id = :socid";

		$stmt = $this->db->prepare($sql);

		return $stmt->execute([
			':titulo'    => $dados['titulo'],
			':descricao' => $dados['descricao'],
			':local'     => $dados['local'],
			':inicio'    => $dados['data_inicio'],
			':fim'       => !empty($dados['data_fim']) ? $dados['data_fim'] : null,
			':valor'     => str_replace(',', '.', $dados['valor']), // Garante ponto decimal
			':status'    => $dados['status'],
			':id'        => $idEvento,
			':socid'     => $idSociedade
		]);
	}

	/**
	 * Busca os dados básicos da sociedade para o portal
	 */
	public function getSociedade($idSociedade) {
		$sql = "SELECT * FROM sociedades WHERE sociedade_id = ?";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idSociedade]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca um evento específico validando a propriedade da sociedade
	 */
	public function getEventoPorId($id, $idSociedade) {
		$sql = "SELECT
					e.*,
					m.membro_nome as lider_nome,
					f.membro_foto_arquivo as lider_foto
				FROM sociedades_eventos e
				INNER JOIN sociedades s ON s.sociedade_id = e.sociedade_evento_sociedade_id
				LEFT JOIN membros m ON m.membro_id = s.sociedade_lider
				LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
				WHERE e.sociedade_evento_id = ?
				AND e.sociedade_evento_sociedade_id = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id, $idSociedade]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Busca a lista de membros da sociedade e marca quem já tem presença no evento
	 */
	public function getListaPresencaEvento($idEvento, $idSociedade) {
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					sm.sociedade_membro_funcao,
					p.sociedade_presenca_id,
					p.sociedade_presenca_status
				FROM sociedades_membros sm
				JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
				LEFT JOIN sociedades_eventos_presencas p ON p.sociedade_presenca_membro_id = m.membro_id
					AND p.sociedade_presenca_evento_id = ?
				WHERE sm.sociedade_membro_sociedade_id = ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idEvento, $idSociedade]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Salva ou atualiza a presença de um membro
	 */
	// No Model SociedadeLider.php método salvarPresenca:
	public function salvarPresenca($dados) {
		$sql = "INSERT INTO sociedades_eventos_presencas
				(sociedade_presenca_igreja_id, sociedade_presenca_sociedade_id, sociedade_presenca_evento_id, sociedade_presenca_membro_id, sociedade_presenca_status)
				VALUES (?, ?, ?, ?, ?)
				ON DUPLICATE KEY UPDATE sociedade_presenca_status = VALUES(sociedade_presenca_status)";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['igreja_id'],
			$dados['sociedade_id'],
			$dados['evento_id'],
			$dados['membro_id'],
			$dados['status']
		]);
	}

	public function getDadosBannerPortal($idSociedade)
	{
		// 1. Busca Sociedade + Igreja + Líder + Pastor (Incluindo Fotos e Registro Interno)
		$sqlSociedade = "SELECT s.*,
                                i.igreja_id, i.igreja_nome, i.igreja_endereco, i.igreja_pastor_id,
                                i.igreja_logo,
								m_lider.membro_nome as lider_nome,
								m_lider.membro_registro_interno as lider_registro,
								f_lider.membro_foto_arquivo as lider_foto,
								m_pastor.membro_nome as pastor_nome,
								m_pastor.membro_registro_interno as pastor_registro, -- ADICIONADO AQUI
								f_pastor.membro_foto_arquivo as pastor_foto
						 FROM sociedades s
						 LEFT JOIN igrejas i ON i.igreja_id = s.sociedade_igreja_id
						 LEFT JOIN membros m_lider ON m_lider.membro_id = s.sociedade_lider
						 LEFT JOIN membros_fotos f_lider ON f_lider.membro_foto_membro_id = m_lider.membro_id
						 LEFT JOIN membros m_pastor ON m_pastor.membro_id = i.igreja_pastor_id
						 LEFT JOIN membros_fotos f_pastor ON f_pastor.membro_foto_membro_id = m_pastor.membro_id
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

		// 3. Busca APENAS Membros vinculados à Sociedade específica
		$sqlMembros = "SELECT
							m.membro_id,
							m.membro_nome,
							m.membro_igreja_id,
							m.membro_registro_interno,
							f.membro_foto_arquivo,
							e.membro_endereco_rua,
							e.membro_endereco_numero,
							e.membro_endereco_bairro,
							e.membro_endereco_cidade,
							sm.sociedade_membro_funcao
					   FROM membros m
					   INNER JOIN sociedades_membros sm ON sm.sociedade_membro_membro_id = m.membro_id
					   LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
					   LEFT JOIN membros_enderecos e ON e.membro_endereco_membro_id = m.membro_id
					   WHERE sm.sociedade_membro_sociedade_id = ?
					   AND m.membro_status = 'Ativo'
					   ORDER BY m.membro_nome ASC";

		$stmtM = $this->db->prepare($sqlMembros);
		$stmtM->execute([$idSociedade]);
		$membros = $stmtM->fetchAll(\PDO::FETCH_ASSOC);

		return [
			'sociedade' => $sociedade,
			'redes'     => $redes,
			'membros'   => $membros
		];
	}

	public function salvarLayoutBanner($idSociedade, $jsonLayout) {
		// Usando o padrão de prepared statements que você já utiliza
		$sql = "UPDATE sociedades SET sociedade_layout_config = ? WHERE sociedade_id = ?";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$jsonLayout, $idSociedade]);
	}

	public function getMetricasDashboard($idSociedade) {
		// 1. Total de membros por gênero
		$sqlGen = "SELECT m.membro_genero, COUNT(*) as total
				   FROM sociedades_membros sm
				   JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
				   WHERE sm.sociedade_membro_sociedade_id = ?
				   GROUP BY m.membro_genero";
		$stmtGen = $this->db->prepare($sqlGen);
		$stmtGen->execute([$idSociedade]);
		$generos = $stmtGen->fetchAll(PDO::FETCH_ASSOC);

		// 2. Presença nos últimos 5 eventos
		$sqlPres = "SELECT e.sociedade_evento_titulo as titulo,
					(SELECT COUNT(*) FROM sociedades_eventos_presencas p
					 WHERE p.sociedade_presenca_evento_id = e.sociedade_evento_id
					 AND p.sociedade_presenca_status = 'Presente') as total_presente
					FROM sociedades_eventos e
					WHERE e.sociedade_evento_sociedade_id = ?
					ORDER BY e.sociedade_evento_data_hora_inicio DESC LIMIT 5";
		$stmtPres = $this->db->prepare($sqlPres);
		$stmtPres->execute([$idSociedade]);
		$presencas = array_reverse($stmtPres->fetchAll(PDO::FETCH_ASSOC));

		// 3. Distribuição por Estado Civil
		$sqlCivil = "SELECT m.membro_estado_civil, COUNT(*) as total
					 FROM sociedades_membros sm
					 JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
					 WHERE sm.sociedade_membro_sociedade_id = ?
					 GROUP BY m.membro_estado_civil";
		$stmtCivil = $this->db->prepare($sqlCivil);
		$stmtCivil->execute([$idSociedade]);
		$civil = $stmtCivil->fetchAll(PDO::FETCH_ASSOC);

		// 4. Distribuição por Faixa Etária
		$sqlIdade = "SELECT
						CASE
							WHEN idade < 18 THEN 'Menor de 18'
							WHEN idade BETWEEN 18 AND 25 THEN '18-25'
							WHEN idade BETWEEN 26 AND 35 THEN '26-35'
							WHEN idade BETWEEN 36 AND 50 THEN '36-50'
							ELSE '50+'
						END as faixa,
						COUNT(*) as total
					 FROM (
						SELECT FLOOR(DATEDIFF(CURDATE(), m.membro_data_nascimento) / 365.25) as idade
						FROM sociedades_membros sm
						JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
						WHERE sm.sociedade_membro_sociedade_id = ?
					 ) as sub
					 GROUP BY faixa ORDER BY faixa ASC";
		$stmtIdade = $this->db->prepare($sqlIdade);
		$stmtIdade->execute([$idSociedade]);
		$idades = $stmtIdade->fetchAll(PDO::FETCH_ASSOC);

		// O retorno PRECISA conter todas as chaves usadas na View
		return [
			'generos'   => $generos,
			'presencas' => $presencas,
			'civil'     => $civil,
			'idades'    => $idades
		];
	}

	public function getEstatisticasAproveitamento($idSociedade) {
		// Busca as regras da sociedade
		$sqlSoc = "SELECT sociedade_igreja_id, sociedade_tipo, sociedade_idade_min, sociedade_idade_max
				   FROM sociedades WHERE sociedade_id = ?";
		$stmtSoc = $this->db->prepare($sqlSoc);
		$stmtSoc->execute([$idSociedade]);
		$regras = $stmtSoc->fetch(\PDO::FETCH_ASSOC);

		if (!$regras) return ['ativos' => 0, 'possiveis' => 0, 'porcentagem' => 0];

		$igrejaId = $regras['sociedade_igreja_id'];
		$idadeMin = (int)$regras['sociedade_idade_min'];
		$idadeMax = (int)$regras['sociedade_idade_max'];
		$tipo     = $regras['sociedade_tipo'];

		// Filtro de gênero baseado no tipo da sociedade
		$filtroGenero = "";
		if ($tipo === 'Mulheres') {
			$filtroGenero = " AND m.membro_genero = 'Feminino'";
		} elseif ($tipo === 'Homens') {
			$filtroGenero = " AND m.membro_genero = 'Masculino'";
		}

		// QUERY 1: Membros da igreja que PODEM entrar mas NÃO estão na sociedade
		$sqlPossiveis = "
			SELECT COUNT(*) as total
			FROM membros m
			WHERE m.membro_igreja_id = :igreja_id
			AND m.membro_status = 'Ativo'
			$filtroGenero
			AND (TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE())) BETWEEN :min AND :max
			AND m.membro_id NOT IN (
				SELECT sociedade_membro_membro_id
				FROM sociedades_membros
				WHERE sociedade_membro_sociedade_id = :soc_id
			)
		";

		$stmtP = $this->db->prepare($sqlPossiveis);
		$stmtP->bindValue(':igreja_id', $igrejaId);
		$stmtP->bindValue(':min', $idadeMin, \PDO::PARAM_INT);
		$stmtP->bindValue(':max', $idadeMax, \PDO::PARAM_INT);
		$stmtP->bindValue(':soc_id', $idSociedade);
		$stmtP->execute();
		$foraDaSociedade = $stmtP->fetch(\PDO::FETCH_ASSOC)['total'];

		// QUERY 2: Membros que JÁ ESTÃO na sociedade
		$sqlAtivos = "SELECT COUNT(*) as total FROM sociedades_membros WHERE sociedade_membro_sociedade_id = ?";
		$stmtA = $this->db->prepare($sqlAtivos);
		$stmtA->execute([$idSociedade]);
		$ativosNaSociedade = $stmtA->fetch(\PDO::FETCH_ASSOC)['total'];

		// Universo Total = Quem está dentro + Quem poderia estar
		$universoTotal = $ativosNaSociedade + $foraDaSociedade;
		$porcentagem = ($universoTotal > 0) ? round(($ativosNaSociedade / $universoTotal) * 100) : 0;

		return [
			'ativos' => $ativosNaSociedade,
			'possiveis' => $universoTotal,
			'porcentagem' => $porcentagem
		];
	}


}
