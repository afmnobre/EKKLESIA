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


}

