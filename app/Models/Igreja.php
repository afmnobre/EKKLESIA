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
}

