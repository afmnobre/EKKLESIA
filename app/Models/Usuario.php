<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

	// Busca apenas os dados básicos e a Igreja do usuário
	public function buscarPorEmail($email) {
		$sql = "SELECT * FROM usuarios WHERE usuario_email = :email LIMIT 1";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([':email' => $email]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	// Busca todos os nomes de perfis vinculados a esse ID de usuário
	public function buscarPerfis($usuarioId) {
		$sql = "SELECT p.perfil_nome
				FROM usuarios_perfis up
				JOIN perfis p ON up.usuario_perfil_perfil_id = p.perfil_id
				WHERE up.usuario_perfil_usuario_id = :id AND p.perfil_status = 'ativo'";

		$stmt = $this->db->prepare($sql);
		$stmt->execute([':id' => $usuarioId]);

		// FETCH_COLUMN retorna um array simples indexado: ['Admin', 'Tesoureiro']
		return $stmt->fetchAll(\PDO::FETCH_COLUMN);
	}

}

