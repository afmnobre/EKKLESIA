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


    public function listarPorIgreja($igrejaId) {
        $sql = "SELECT u.*, p.perfil_nome
                FROM usuarios u
                LEFT JOIN usuarios_perfis up ON u.usuario_id = up.usuario_perfil_usuario_id
                LEFT JOIN perfis p ON up.usuario_perfil_perfil_id = p.perfil_id
                WHERE u.usuario_igreja_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($data) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO usuarios (usuario_igreja_id, usuario_nome, usuario_email, usuario_senha, usuario_status, usuario_data_criacao)
                    VALUES (?, ?, ?, ?, 'Ativo', NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['igreja_id'], $data['nome'], $data['email'], password_hash($data['senha'], PASSWORD_DEFAULT)]);

            $usuarioId = $this->db->lastInsertId();

            $sqlPerfil = "INSERT INTO usuarios_perfis (usuario_perfil_igreja_id, usuario_perfil_usuario_id, usuario_perfil_perfil_id) VALUES (?, ?, ?)";
            $this->db->prepare($sqlPerfil)->execute([$data['igreja_id'], $usuarioId, $data['perfil_id']]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

}

