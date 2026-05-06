<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // =========================================================================
    // CRUD DE IGREJAS
    // =========================================================================

    public function listarIgrejas() {
        // Traz as igrejas e o nome do pastor vinculado (da tabela membros)
        $sql = "SELECT i.*, m.membro_nome as pastor_nome
                FROM igrejas i
                LEFT JOIN membros m ON i.igreja_pastor_id = m.membro_id
                ORDER BY i.igreja_nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIgrejaById($id) {
        $sql = "SELECT * FROM igrejas WHERE igreja_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criarIgreja($dados) {
        $sql = "INSERT INTO igrejas (igreja_nome, igreja_cnpj, igreja_endereco, igreja_pastor_id, igreja_data_criacao)
                VALUES (:nome, :cnpj, :endereco, :pastor_id, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'     => $dados['nome'],
            ':cnpj'     => $dados['cnpj'],
            ':endereco' => $dados['endereco'],
            ':pastor_id' => $dados['pastor_id'] // Pode ser nulo conforme sua constraint
        ]);
    }

    public function atualizarIgreja($id, $dados) {
        $sql = "UPDATE igrejas SET
                igreja_nome = :nome,
                igreja_cnpj = :cnpj,
                igreja_endereco = :endereco,
                igreja_pastor_id = :pastor_id
                WHERE igreja_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome'     => $dados['nome'],
            ':cnpj'     => $dados['cnpj'],
            ':endereco' => $dados['endereco'],
            ':pastor_id' => $dados['pastor_id'],
            ':id'       => $id
        ]);
    }

    // =========================================================================
    // MÉTODOS DE USUÁRIOS (Existentes ajustados)
    // =========================================================================

    public function listarUsuarios($igrejaId) {
        $sql = "SELECT u.usuario_id, u.usuario_nome, u.usuario_email, u.usuario_status,
                       GROUP_CONCAT(p.perfil_nome SEPARATOR ', ') as perfil_nome,
                       GROUP_CONCAT(p.perfil_id) as perfis_ids
                FROM usuarios u
                LEFT JOIN usuarios_perfis up ON u.usuario_id = up.usuario_perfil_usuario_id
                LEFT JOIN perfis p ON up.usuario_perfil_perfil_id = p.perfil_id
                WHERE u.usuario_igreja_id = :igreja_id
                GROUP BY u.usuario_id";

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
            return false;
        }
    }

    // =========================================================================
    // MÉTODOS DE PERFIS (Existentes)
    // =========================================================================

    public function listarPerfis() {
        return $this->db->query("SELECT * FROM perfis ORDER BY perfil_nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function getPerfilById($id) {
        $sql = "SELECT * FROM perfis WHERE perfil_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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

            $campos = "usuario_nome = :nome, usuario_email = :email, usuario_status = :status";
            $params = [
                ':nome'   => $dados['nome'],
                ':email'  => $dados['email'],
                ':status' => $dados['status'],
                ':id'     => $id
            ];

            if (!empty($dados['senha'])) {
                $campos .= ", usuario_senha = :senha";
                $params[':senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
            }

            $sql = "UPDATE usuarios SET {$campos} WHERE usuario_id = :id";
            $this->db->prepare($sql)->execute($params);

            $sqlDelete = "DELETE FROM usuarios_perfis WHERE usuario_perfil_usuario_id = :id";
            $this->db->prepare($sqlDelete)->execute([':id' => $id]);

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

            $sqlPerfis = "DELETE FROM usuarios_perfis WHERE usuario_perfil_usuario_id = :id";
            $this->db->prepare($sqlPerfis)->execute([':id' => $id]);

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
