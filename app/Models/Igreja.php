<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Igreja
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM igrejas ORDER BY igreja_nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM igrejas WHERE igreja_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByIgreja($igrejaId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM igrejas
            WHERE igreja_id = ?
        ");

        $stmt->execute([$igrejaId]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE igrejas SET
                    igreja_nome = :nome,
                    igreja_cnpj = :cnpj,
                    igreja_endereco = :endereco
                WHERE igreja_id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nome' => $data['nome'],
            ':cnpj' => $data['cnpj'],
            ':endereco' => $data['endereco'],
            ':id' => $id
        ]);
    }

	// Adicione estes métodos à classe Igreja em App/Models/Igreja.php

	public function getRedesSociais($igrejaId)
	{
		$stmt = $this->db->prepare("SELECT * FROM igrejas_redes_sociais WHERE rede_igreja_id = ?");
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addRedeSocial($data)
	{
		$sql = "INSERT INTO igrejas_redes_sociais (rede_igreja_id, rede_nome, rede_usuario, rede_status)
				VALUES (:igreja_id, :nome, :usuario, :status)";
		return $this->db->prepare($sql)->execute($data);
	}

	public function deleteRedeSocial($id, $igrejaId)
	{
		$stmt = $this->db->prepare("DELETE FROM igrejas_redes_sociais WHERE rede_id = ? AND rede_igreja_id = ?");
		return $stmt->execute([$id, $igrejaId]);
	}

	// Método para buscar membros que podem ser pastores (opcional para o select)
	public function getPossiveisPastores($igrejaId)
	{
		$stmt = $this->db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function updatePastor($igrejaId, $membroId)
	{
		try {
			$this->db->beginTransaction();

			// 1. Atualizar a tabela igrejas
			$stmt = $this->db->prepare("UPDATE igrejas SET igreja_pastor_id = ? WHERE igreja_id = ?");
			$stmt->execute([$membroId, $igrejaId]);

			// 2. Remover o cargo de Pastor (ID 1) de todos os membros DESTA igreja antes de atribuir ao novo
			// Isso garante que apenas um membro tenha o cargo de pastor ativo no vínculo por igreja
			$stmtRemover = $this->db->prepare("
				DELETE v FROM membros_cargos_vinculo v
				INNER JOIN membros m ON v.vinculo_membro_id = m.membro_id
				WHERE v.vinculo_cargo_id = 1 AND m.membro_igreja_id = ?
			");
			$stmtRemover->execute([$igrejaId]);

			// 3. Inserir o novo vínculo na tabela membros_cargos_vinculo
			if ($membroId) {
				$stmtInsert = $this->db->prepare("
					INSERT INTO membros_cargos_vinculo (vinculo_membro_id, vinculo_cargo_id)
					VALUES (?, 1)
				");
				$stmtInsert->execute([$membroId]);
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
    }

	public function updateLogo($igrejaId, $nomeArquivo)
	{
		$sql = "UPDATE igrejas SET igreja_logo = ? WHERE igreja_id = ?";
		return $this->db->prepare($sql)->execute([$nomeArquivo, $igrejaId]);
	}

	public function getProgramacoes($igrejaId)
	{
		// A ordenação por FIELD garante que o Domingo apareça primeiro na lista
		$sql = "SELECT * FROM igrejas_programacao
				WHERE programacao_igreja_id = ?
				ORDER BY FIELD(programacao_dia_semana, 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'),
				programacao_hora ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function addProgramacao($dados) {
		$sql = "INSERT INTO igrejas_programacao (programacao_igreja_id, programacao_titulo, programacao_dia_semana, programacao_hora, programacao_recorrencia_mensal, programacao_is_ceia, programacao_is_externo)
				VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			$dados['igreja_id'], $dados['titulo'], $dados['dia_semana'],
			$dados['hora'], $dados['recorrencia'], $dados['is_ceia'], $dados['is_externo']
		]);
	}

	public function updateLocal($id, $local) {
		$sql = "UPDATE igrejas_programacao SET programacao_local_nome = ? WHERE programacao_id = ?";
		return $this->db->prepare($sql)->execute([$local, $id]);
	}

	public function upsertEscala($dados) {
		$sql = "INSERT INTO igrejas_programacao_locais
					(programacao_id, membro_id, data_evento, local_nome_endereco)
				VALUES (:prog, :membro, :data, :end)
				ON DUPLICATE KEY UPDATE
					membro_id = VALUES(membro_id),
					local_nome_endereco = VALUES(local_nome_endereco)";

		$st = $this->db->prepare($sql);

		$st->bindValue(':prog', $dados['programacao_id'], PDO::PARAM_INT);

		// Se membro_id for nulo, passamos o tipo NULL explicitamente para o banco
		if (is_null($dados['membro_id'])) {
			$st->bindValue(':membro', null, PDO::PARAM_NULL);
		} else {
			$st->bindValue(':membro', $dados['membro_id'], PDO::PARAM_INT);
		}

		$st->bindValue(':data', $dados['data_evento']);
		$st->bindValue(':end', $dados['local_nome_endereco']);

		return $st->execute();
	}

	public function getEscalas($progId) {
		// Trocamos local_id por id, que é o nome real na sua tabela
		$sql = "SELECT id, programacao_id, data_evento, local_nome_endereco
				FROM igrejas_programacao_locais
				WHERE programacao_id = ?
				ORDER BY data_evento ASC";
		$st = $this->db->prepare($sql);
		$st->execute([$progId]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function deleteEscala($id) {
		// Aqui também usamos 'id'
		$sql = "DELETE FROM igrejas_programacao_locais WHERE id = ?";
		$st = $this->db->prepare($sql);
		return $st->execute([$id]);
	}

	public function getMembrosEnderecos($igrejaId) {
		// Busca nome e concatena endereço da tabela auxiliar
		$sql = "SELECT m.membro_nome, m.membro_id,
				CONCAT(e.membro_endereco_rua, ', ', e.membro_endereco_numero, ' - ', e.membro_endereco_bairro) as endereco
				FROM membros m
				INNER JOIN membros_enderecos e ON m.membro_id = e.membro_endereco_membro_id
				WHERE m.membro_igreja_id = ? AND m.membro_status = 'Ativo'";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function deleteProgramacao($id, $igrejaId)
	{
		$stmt = $this->db->prepare("DELETE FROM igrejas_programacao WHERE programacao_id = ? AND programacao_igreja_id = ?");
		return $stmt->execute([$id, $igrejaId]);
	}

	/**
	 * Busca a liderança oficial da igreja com ordenação hierárquica
	 * Pastor(1), Auxiliar(2), Presbítero(5), Diácono(7), Seminarista(3)
	 */
	public function getLideranca($igrejaId)
	{
	    $sql = "SELECT
					c.cargo_id AS vinculo_cargo_id, -- Adicionado para identificar a sigla
					c.cargo_nome,
					m.membro_id,
					m.membro_nome,
					m.membro_registro_interno,
					mf.membro_foto_arquivo
				FROM membros m
				INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
				INNER JOIN cargos c ON v.vinculo_cargo_id = c.cargo_id
				LEFT JOIN membros_fotos mf ON m.membro_id = mf.membro_foto_membro_id
				WHERE m.membro_igreja_id = ?
				AND c.cargo_id IN (1, 2, 5, 7, 3)
				ORDER BY FIELD(c.cargo_id, 1, 2, 5, 7, 3), m.membro_nome ASC";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}


}

