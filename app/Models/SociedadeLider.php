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

}
