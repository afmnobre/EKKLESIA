<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class MensagemDominical
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista todas as mensagens dominicais de uma igreja específica
     */
    public function getAllByIgreja($igrejaId)
    {
        $sql = "SELECT b.*, m.membro_nome as autor_nome
                FROM igrejas_mensagens_dominicais b
                LEFT JOIN membros m ON b.igreja_mensagem_dominical_autor_id = m.membro_id
                WHERE b.igreja_mensagem_dominical_igreja_id = ?
                ORDER BY b.igreja_mensagem_dominical_data DESC, b.igreja_mensagem_dominical_num_historico DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma mensagem específica pelo ID
     */
    public function getById($id, $igrejaId)
    {
        $sql = "SELECT * FROM igrejas_mensagens_dominicais
                WHERE igreja_mensagem_dominical_id = ?
                AND igreja_mensagem_dominical_igreja_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $igrejaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna o último número usado para sugerir o próximo
     */
    public function getUltimoNumero($igrejaId)
    {
        $sql = "SELECT MAX(igreja_mensagem_dominical_num_historico) as ultimo
                FROM igrejas_mensagens_dominicais
                WHERE igreja_mensagem_dominical_igreja_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        $res = $stmt->fetch();
        return $res['ultimo'] ?? 0;
    }

    /**
     * Salva ou atualiza uma mensagem dominical
     */
    public function salvar($dados)
    {
        if (!empty($dados['id'])) {
            // Lógica de UPDATE
            $sql = "UPDATE igrejas_mensagens_dominicais SET
                        igreja_mensagem_dominical_num_historico = ?,
                        igreja_mensagem_dominical_data = ?,
                        igreja_mensagem_dominical_autor_id = ?,
                        igreja_mensagem_dominical_titulo = ?,
                        igreja_mensagem_dominical_mensagem = ?,
                        igreja_mensagem_dominical_status = ?
                    WHERE igreja_mensagem_dominical_id = ? AND igreja_mensagem_dominical_igreja_id = ?";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $dados['numero'],
                $dados['data'],
                $dados['autor_id'],
                $dados['titulo'],
                $dados['mensagem'],
                $dados['status'],
                $dados['id'],
                $dados['igreja_id']
            ]);
        } else {
            // Lógica de INSERT
            $sql = "INSERT INTO igrejas_mensagens_dominicais (
                        igreja_mensagem_dominical_igreja_id,
                        igreja_mensagem_dominical_num_historico,
                        igreja_mensagem_dominical_data,
                        igreja_mensagem_dominical_autor_id,
                        igreja_mensagem_dominical_titulo,
                        igreja_mensagem_dominical_mensagem,
                        igreja_mensagem_dominical_status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $dados['igreja_id'],
                $dados['numero'],
                $dados['data'],
                $dados['autor_id'],
                $dados['titulo'],
                $dados['mensagem'],
                $dados['status']
            ]);
        }
    }

    /**
     * Exclui uma mensagem dominical
     */
    public function excluir($id, $igrejaId)
    {
        $sql = "DELETE FROM igrejas_mensagens_dominicais
                WHERE igreja_mensagem_dominical_id = ?
                AND igreja_mensagem_dominical_igreja_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $igrejaId]);
    }
}
