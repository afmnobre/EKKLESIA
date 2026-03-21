<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class SociedadeDashboard
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retorna métricas de população, aproveitamento e liderança
     */
    public function getMetricasGerais($igrejaId)
    {
        $sql = "SELECT
                    s.sociedade_id,
                    s.sociedade_nome,
                    s.sociedade_lider,
                    s.sociedade_idade_min,
                    s.sociedade_idade_max,
                    s.sociedade_genero,
                    m_lider.membro_nome as nome_lider,
                    v_lider.vinculo_data_atribuicao,
                    -- Total de membros efetivamente vinculados à sociedade
                    (SELECT COUNT(*)
                     FROM sociedades_membros sm
                     WHERE sm.sociedade_membro_sociedade_id = s.sociedade_id) as total_socios,
                    -- Total de membros na igreja que atendem aos requisitos (idade/gênero)
                    (SELECT COUNT(*)
                     FROM membros m
                     WHERE m.membro_igreja_id = ?
                     AND m.membro_status = 'Ativo'
                     AND (m.membro_genero = s.sociedade_genero OR s.sociedade_genero = 'Ambos')
                     AND (TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) BETWEEN s.sociedade_idade_min AND s.sociedade_idade_max)
                    ) as membros_aptos
                FROM sociedades s
                LEFT JOIN membros m_lider ON s.sociedade_lider = m_lider.membro_id
                -- Join para buscar a data em que o líder assumiu o cargo
                LEFT JOIN membros_cargos_vinculo v_lider ON s.sociedade_lider = v_lider.vinculo_membro_id
                WHERE s.sociedade_igreja_id = ?
                ORDER BY s.sociedade_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId, $igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista membros que estão em uma sociedade mas não possuem a idade permitida
     */
    public function getMembrosForaDaFaixa($igrejaId)
    {
        $sql = "SELECT
                    m.membro_nome,
                    s.sociedade_nome,
                    TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) as idade_atual,
                    s.sociedade_idade_min,
                    s.sociedade_idade_max
                FROM sociedades_membros sm
                JOIN membros m ON sm.sociedade_membro_membro_id = m.membro_id
                JOIN sociedades s ON sm.sociedade_membro_sociedade_id = s.sociedade_id
                WHERE s.sociedade_igreja_id = ?
                AND (
                    TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) < s.sociedade_idade_min
                    OR
                    TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) > s.sociedade_idade_max
                )
                ORDER BY m.membro_nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca eventos próximos (Agendados ou Confirmados)
     */
    public function getProximosEventos($igrejaId, $dias = 30)
    {
        $sql = "SELECT e.*, s.sociedade_nome
                FROM sociedades_eventos e
                JOIN sociedades s ON e.sociedade_evento_sociedade_id = s.sociedade_id
                WHERE e.sociedade_evento_igreja_id = ?
                AND e.sociedade_evento_status IN ('Agendado', 'Confirmado')
                AND e.sociedade_evento_data_hora_inicio >= NOW()
                AND e.sociedade_evento_data_hora_inicio <= DATE_ADD(NOW(), INTERVAL ? DAY)
                ORDER BY e.sociedade_evento_data_hora_inicio ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId, $dias]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ranking de sociedades mais ativas (baseado em quantidade de eventos)
     */
    public function getFrequenciaEventos($igrejaId)
    {
        $sql = "SELECT s.sociedade_nome, COUNT(e.sociedade_evento_id) as total_eventos
                FROM sociedades s
                LEFT JOIN sociedades_eventos e ON s.sociedade_id = e.sociedade_evento_sociedade_id
                WHERE s.sociedade_igreja_id = ?
                AND e.sociedade_evento_status = 'Concluído'
                GROUP BY s.sociedade_id
                ORDER BY total_eventos DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$igrejaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
