<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PortalMembro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

	public function registrarPendente($dados) {
		try {
			// 1. Inserir na tabela membros (Adicionado membro_sexo)
			$sqlMembro = "INSERT INTO membros (
				membro_igreja_id,
				membro_nome,
				membro_data_nascimento,
				membro_genero,
				membro_data_batismo,
				membro_email,
				membro_senha,
				membro_telefone,
				membro_status,
				membro_data_criacao
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pendente', NOW())";

			$stmt = $this->db->prepare($sqlMembro);
			$stmt->execute([
				$dados['igreja_id'],
				$dados['nome'],
				$dados['data_nasc'],
				$dados['sexo'], // O novo campo que adicionamos
				$dados['data_batismo'],
				$dados['email'],
				$dados['senha'],
				$dados['telefone']
			]);

			$membroId = $this->db->lastInsertId();

			// 2. Inserir na tabela membros_enderecos
			if ($membroId) {
				$sqlEnd = "INSERT INTO membros_enderecos (
					membro_endereco_membro_id,
					membro_endereco_rua,
					membro_endereco_numero,
					membro_endereco_bairro,
					membro_endereco_cidade,
					membro_endereco_estado,
					membro_endereco_cep
				) VALUES (?, ?, ?, ?, ?, ?, ?)";

				$stmtEnd = $this->db->prepare($sqlEnd);
				$stmtEnd->execute([
					$membroId,
					$dados['rua'],
					$dados['numero'],
					$dados['bairro'],
					$dados['cidade'],
					$dados['estado'],
					$dados['cep']
				]);

				return $membroId;
			}

			return false;
		} catch (\PDOException $e) {
			// Se der erro, este log ajudará a identificar qual coluna falta
			error_log("ERRO BANCO EKKLESIA: " . $e->getMessage());
			return false;
		}
	}

	public function saveFoto($membroId, $nomeArquivo) {
		// Verifique se a tabela é membros_fotos e se os nomes das colunas estão certos
		$sql = "INSERT INTO membros_fotos (membro_foto_membro_id, membro_foto_arquivo) VALUES (?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([$membroId, $nomeArquivo]);
	}

    public function getIgreja($id) {
        $stmt = $this->db->prepare("SELECT * FROM igrejas WHERE igreja_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	public function getPendentes($igrejaId) {
		// IMPORTANTE: Precisamos buscar o campo membro_foto_arquivo da tabela de fotos
		$sql = "SELECT m.*, f.membro_foto_arquivo
				FROM membros m
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				WHERE m.membro_igreja_id = ? AND m.membro_status = 'Pendente'
				ORDER BY m.membro_id DESC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function alterarStatus($membroId, $status, $registroInterno = null) {
		$sql = "UPDATE membros SET membro_status = ?, membro_registro_interno = ? WHERE membro_id = ?";
		return $this->db->prepare($sql)->execute([$status, $registroInterno, $membroId]);
    }

	public function gerarSugestaoRegistro($igrejaId) {
		// Busca o ID do último membro inserido na tabela (independente de status)
		// para garantir que o final do número seja incremental e único.
		$sql = "SELECT MAX(membro_id) as ultimo FROM membros";
		$stmt = $this->db->query($sql);
		$resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

		$proximoId = ($resultado['ultimo'] ?? 0) + 1;
		$ano = date('Y');
		$mes = date('m');

		// Formato: IgrejaID + Ano + Mês + PróximoID
		return $igrejaId . $ano . $mes . $proximoId;
	}

	public function getMembroComEndereco($id) {
		// Adicionamos a lógica de IFNULL e CONCAT diretamente no SQL
		$sql = "SELECT m.*, e.*, i.igreja_nome, f.membro_foto_arquivo,
				IF(f.membro_foto_arquivo IS NOT NULL,
				   CONCAT('public/assets/uploads/', m.membro_igreja_id, '/membros/PENDENTE_', m.membro_id, '/', f.membro_foto_arquivo),
				   NULL) as caminho_foto_pendente
				FROM membros m
				JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				JOIN igrejas i ON m.membro_igreja_id = i.igreja_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				WHERE m.membro_id = ?";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$id]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function listarDependentes($idResponsavel) {
		$sql = "SELECT m.*, r.parentesco_grau, f.membro_foto_arquivo
				FROM membros_responsaveis r
				JOIN membros m ON r.parentesco_dependente_id = m.membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				WHERE r.parentesco_responsavel_id = ?
				ORDER BY m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$idResponsavel]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getMembroCompleto($membroId) {
		// Query principal para dados biográficos, endereço, foto, cargo e grupos
		$sql = "SELECT
					m.*,
					e.*,
					f.membro_foto_arquivo,
					c.cargo_nome,
					s.sociedade_nome,
					s.sociedade_logo,
					ce.classe_nome,
					ce.classe_id
				FROM membros m
				LEFT JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				LEFT JOIN membros_fotos f ON m.membro_id = f.membro_foto_membro_id
				LEFT JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				LEFT JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				LEFT JOIN sociedades_membros sm ON m.membro_id = sm.sociedade_membro_membro_id
				LEFT JOIN sociedades s ON sm.sociedade_membro_sociedade_id = s.sociedade_id
				LEFT JOIN classes_membros cm ON m.membro_id = cm.classe_membro_membro_id
				LEFT JOIN classes_escola ce ON cm.classe_membro_classe_id = ce.classe_id
				WHERE m.membro_id = ?
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId]);
		$membro = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ($membro) {
			// Se ele estiver em uma classe, buscamos o histórico de presenças separadamente
			if (!empty($membro['classe_id'])) {
				$membro['presencas'] = $this->getPresencasAnuais($membroId, $membro['classe_id']);
			} else {
				$membro['presencas'] = [];
			}
		}

		return $membro;
	}

	/**
	 * Busca as últimas 10 presenças do membro na EBD
	 */
	public function getPresencasAnuais($membroId, $classeId) {
		$anoAtual = date('Y');

		$sql = "SELECT
					p.presenca_data,
					p.presenca_status,
					c.classe_nome
				FROM classes_presencas p
				JOIN classes_escola c ON p.presenca_classe_id = c.classe_id
				WHERE p.presenca_membro_id = ?
				AND p.presenca_classe_id = ?
				AND YEAR(p.presenca_data) = ?
				ORDER BY p.presenca_data ASC";

		// Usando o padrão que funciona no seu getMembroCompleto
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$membroId, $classeId, $anoAtual]);

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function buscarPorTelefone($telefone) {
		// Usamos REPLACE no SQL para tirar ( ) - e espaços do banco durante a busca
		// Assim, comparamos apenas números com números
		$sql = "SELECT * FROM membros
				WHERE REPLACE(REPLACE(REPLACE(REPLACE(membro_telefone, '(', ''), ')', ''), '-', ''), ' ', '') = ?
				AND membro_status != 'Rejeitado'
				LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$telefone]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function inserirMembro($dados) {
		// Usa o seu método padrão de insert
		$sql = "INSERT INTO membros (" . implode(',', array_keys($dados)) . ")
				VALUES (" . implode(',', array_fill(0, count($dados), '?')) . ")";
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array_values($dados));
		return $this->db->lastInsertId();
	}

	public function vincularResponsavel($dados) {
		$sql = "INSERT INTO membros_responsaveis
				(parentesco_responsavel_id, parentesco_dependente_id, parentesco_grau)
				VALUES (?, ?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['parentesco_responsavel_id'],
			$dados['parentesco_dependente_id'],
			$dados['parentesco_grau']
		]);
	}

}
