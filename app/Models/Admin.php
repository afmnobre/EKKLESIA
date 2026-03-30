<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Lista usuários e seus perfis vinculados
	public function listarUsuarios($igrejaId) {
		$sql = "SELECT u.usuario_id, u.usuario_nome, u.usuario_email, u.usuario_status,
                       GROUP_CONCAT(p.perfil_nome SEPARATOR ', ') as perfil_nome,
                       GROUP_CONCAT(p.perfil_id) as perfis_ids
				FROM usuarios u
				LEFT JOIN usuarios_perfis up ON u.usuario_id = up.usuario_perfil_usuario_id
				LEFT JOIN perfis p ON up.usuario_perfil_perfil_id = p.perfil_id
				WHERE u.usuario_igreja_id = :igreja_id
				GROUP BY u.usuario_id"; // Agrupa para mostrar uma linha por usuário

		$stmt = $this->db->prepare($sql);
		$stmt->execute([':igreja_id' => $igrejaId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function getPerfisDisponiveis() {
        return $this->db->query("SELECT * FROM perfis ORDER BY perfil_nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

	public function criarUsuario($dados) {
		try {
			$this->db->beginTransaction();

			// 1. Inserção do Usuário
			$sql = "INSERT INTO usuarios (usuario_igreja_id, usuario_nome, usuario_email, usuario_senha, usuario_status, usuario_data_criacao)
					VALUES (:igreja, :nome, :email, :senha, 'ativo', NOW())";

			$stmt = $this->db->prepare($sql);
			$stmt->execute([
				':igreja' => $dados['igreja_id'],
				':nome'   => $dados['nome'],
				':email'  => $dados['email'],
				':senha'  => password_hash($dados['senha'], PASSWORD_DEFAULT)
			]);

			$usuarioId = $this->db->lastInsertId();

			// 2. Vínculo dos Múltiplos Perfis
			// Verificamos se 'perfis' existe e é um array
			if (isset($dados['perfis']) && is_array($dados['perfis'])) {
				$sqlPerfil = "INSERT INTO usuarios_perfis (usuario_perfil_igreja_id, usuario_perfil_usuario_id, usuario_perfil_perfil_id)
							  VALUES (:igreja, :usuario, :perfil)";
				$stmtPerfil = $this->db->prepare($sqlPerfil);

				foreach ($dados['perfis'] as $perfilId) {
					$stmtPerfil->execute([
						':igreja'  => $dados['igreja_id'],
						':usuario' => $usuarioId,
						':perfil'  => $perfilId
					]);
				}
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			// Opcional: Logar o erro $e->getMessage();
			return false;
		}
	}

	// Listar todos os perfis
	public function listarPerfis() {
		return $this->db->query("SELECT * FROM perfis ORDER BY perfil_nome ASC")->fetchAll(PDO::FETCH_ASSOC);
	}

	// Criar novo perfil
	public function criarPerfil($dados) {
		$sql = "INSERT INTO perfis (perfil_nome, perfil_descricao, perfil_status)
				VALUES (:nome, :descricao, :status)";
		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			':nome'      => $dados['nome'],
			':descricao' => $dados['descricao'],
			':status'    => 'ativo'
		]);
	}

	// Buscar um perfil específico
	public function getPerfilById($id) {
		$sql = "SELECT * FROM perfis WHERE perfil_id = :id";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([':id' => $id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Atualizar perfil
	public function atualizarPerfil($id, $dados) {
		$sql = "UPDATE perfis SET
				perfil_nome = :nome,
				perfil_descricao = :descricao,
				perfil_status = :status
				WHERE perfil_id = :id";

		$stmt = $this->db->prepare($sql);
		return $stmt->execute([
			':nome'      => $dados['nome'],
			':descricao' => $dados['descricao'],
			':status'    => $dados['status'],
			':id'        => $id
		]);
	}

	public function atualizarUsuario($id, $dados) {
		try {
			$this->db->beginTransaction();

			// 1. Atualizar dados básicos
			$campos = "usuario_nome = :nome, usuario_email = :email, usuario_status = :status";
			$params = [
				':nome'   => $dados['nome'],
				':email'  => $dados['email'],
				':status' => $dados['status'],
				':id'     => $id
			];

			// Se a senha foi informada, adicionamos ao Update
			if (!empty($dados['senha'])) {
				$campos .= ", usuario_senha = :senha";
				$params[':senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
			}

			$sql = "UPDATE usuarios SET {$campos} WHERE usuario_id = :id";
			$this->db->prepare($sql)->execute($params);

			// 2. Sincronizar Perfis
			// Primeiro, removemos todos os vínculos atuais deste usuário
			$sqlDelete = "DELETE FROM usuarios_perfis WHERE usuario_perfil_usuario_id = :id";
			$this->db->prepare($sqlDelete)->execute([':id' => $id]);

			// Agora inserimos os novos perfis selecionados
			if (isset($dados['perfis']) && is_array($dados['perfis'])) {
				$sqlPerfil = "INSERT INTO usuarios_perfis (usuario_perfil_igreja_id, usuario_perfil_usuario_id, usuario_perfil_perfil_id)
							  VALUES (:igreja, :usuario, :perfil)";
				$stmtPerfil = $this->db->prepare($sqlPerfil);

				foreach ($dados['perfis'] as $perfilId) {
					$stmtPerfil->execute([
						':igreja'  => $dados['igreja_id'],
						':usuario' => $id,
						':perfil'  => $perfilId
					]);
				}
			}

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}

	public function excluirUsuario($id, $igrejaId) {
		try {
			$this->db->beginTransaction();

			// 1. Remove os vínculos de perfil primeiro (evita erro de constraint)
			$sqlPerfis = "DELETE FROM usuarios_perfis WHERE usuario_perfil_usuario_id = :id";
			$this->db->prepare($sqlPerfis)->execute([':id' => $id]);

			// 2. Remove o usuário garantindo que pertença à igreja da sessão (segurança)
			$sqlUser = "DELETE FROM usuarios WHERE usuario_id = :id AND usuario_igreja_id = :igreja";
			$stmt = $this->db->prepare($sqlUser);
			$stmt->execute([
				':id' => $id,
				':igreja' => $igrejaId
			]);

			$this->db->commit();
			return true;
		} catch (\Exception $e) {
			$this->db->rollBack();
			return false;
		}
	}


}
