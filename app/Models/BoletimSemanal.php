<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BoletimSemanal
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

			// No Controller, dentro do index():
	public function getUltimaLiturgia($igrejaId)
	{
		$sql = "SELECT
					l.*,
					i.igreja_nome, -- Adicionado para resolver o erro do cabeçalho
                    i.igreja_logo,
                    i.igreja_cnpj,
					-- Dados do Dirigente
					m_dir.membro_nome as nome_membro_dirigente,
					m_dir.membro_registro_interno as registro_dirigente,
					f_dir.membro_foto_arquivo as membro_foto_dirigente,
					-- Dados do Pregador
					m_pre.membro_nome as nome_membro_pregador,
					m_pre.membro_registro_interno as registro_pregador,
					f_pre.membro_foto_arquivo as membro_foto_pregador

				FROM igrejas_liturgias l
				INNER JOIN igrejas i ON l.igreja_liturgia_igreja_id = i.igreja_id -- Join para o nome da igreja
				LEFT JOIN membros m_dir ON l.igreja_liturgia_dirigente_id = m_dir.membro_id
				LEFT JOIN membros_fotos f_dir ON m_dir.membro_id = f_dir.membro_foto_membro_id
				LEFT JOIN membros m_pre ON l.igreja_liturgia_pregador_id = m_pre.membro_id
				LEFT JOIN membros_fotos f_pre ON m_pre.membro_id = f_pre.membro_foto_membro_id

				WHERE l.igreja_liturgia_igreja_id = :igrejaId
				ORDER BY l.igreja_liturgia_data DESC, l.igreja_liturgia_id DESC
				LIMIT 1";

		$st = $this->db->prepare($sql);
		$st->execute([':igrejaId' => $igrejaId]);
		$liturgia = $st->fetch(\PDO::FETCH_ASSOC);

		if ($liturgia) {
			$sqlItems = "SELECT
							liturgia_item_id, -- Adicionado para o ID do collapse ser único
							liturgia_item_tipo as tipo,
							liturgia_item_descricao as `desc`,
							liturgia_item_referencia as ref,
							liturgia_item_conteudo_api as conteudo -- Adicionamos o campo aqui!
						 FROM igrejas_liturgias_itens
						 WHERE liturgia_item_liturgia_id = ?
						 ORDER BY liturgia_item_ordem ASC";
			$stItems = $this->db->prepare($sqlItems);
			$stItems->execute([$liturgia['igreja_liturgia_id']]);
			$liturgia['itens'] = $stItems->fetchAll(\PDO::FETCH_ASSOC);
		}

		return $liturgia;
	}

    public function getLideranca($igrejaId)
    {
        $sql = "SELECT
                    c.cargo_id AS vinculo_cargo_id,
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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	public function getUltimaMensagem($igrejaId)
	{
		$sql = "SELECT
					m.*,
					autor.membro_nome as nome_autor
				FROM igrejas_mensagens_dominicais m
				INNER JOIN membros autor ON m.igreja_mensagem_dominical_autor_id = autor.membro_id
				WHERE m.igreja_mensagem_dominical_igreja_id = ?
				AND m.igreja_mensagem_dominical_status = 'publicado'
				ORDER BY m.igreja_mensagem_dominical_data DESC, m.igreja_mensagem_dominical_id DESC
				LIMIT 1";

		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetch(PDO::FETCH_ASSOC);
        }

	/**
	 * Busca aniversariantes de nascimento do mês atual
	 */
	public function buscarAniversariantesNascimento($igrejaId)
	{
		$sql = "SELECT membro_nome, DAY(membro_data_nascimento) as dia
				FROM membros
				WHERE membro_igreja_id = ?
				AND MONTH(membro_data_nascimento) = MONTH(CURRENT_DATE)
				ORDER BY dia ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Busca aniversariantes de batismo do mês atual
	 */
	public function buscarAniversariantesBatismo($igrejaId)
	{
		$sql = "SELECT membro_nome, DAY(membro_data_batismo) as dia,
					   TIMESTAMPDIFF(YEAR, membro_data_batismo, CURDATE()) as anos
				FROM membros
				WHERE membro_igreja_id = ?
				AND MONTH(membro_data_batismo) = MONTH(CURRENT_DATE)
				AND membro_data_batismo IS NOT NULL
				ORDER BY dia ASC";
		$stmt = $this->db->prepare($sql);
		$stmt->execute([$igrejaId]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function buscarAniversariantesMes($igrejaId)
	{
		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_registro_interno,
					m.membro_data_nascimento,
					m.membro_data_batismo,
					f.membro_foto_arquivo
				FROM membros m
				LEFT JOIN membros_fotos f ON f.membro_foto_membro_id = m.membro_id
				WHERE m.membro_igreja_id = ?
				AND (
					MONTH(m.membro_data_nascimento) = MONTH(CURRENT_DATE)
					OR MONTH(membro_data_batismo) = MONTH(CURRENT_DATE)
				)";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * Busca os próximos 5 eventos das sociedades que ainda não aconteceram
	 */
	public function buscarProximosEventos($igrejaId)
	{
		$sql = "SELECT
					e.sociedade_evento_titulo,
					e.sociedade_evento_local,
					e.sociedade_evento_data_hora_inicio,
					s.sociedade_nome,
					s.sociedade_logo
				FROM sociedades_eventos e
				INNER JOIN sociedades s ON s.sociedade_id = e.sociedade_evento_sociedade_id
				WHERE e.sociedade_evento_igreja_id = ?
				AND e.sociedade_evento_data_hora_inicio >= CURDATE()
				AND e.sociedade_evento_status IN ('Agendado', 'Confirmado')
				ORDER BY e.sociedade_evento_data_hora_inicio ASC
				LIMIT 5";

		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function getProgramacao($igrejaId) {
		$sql = "SELECT * FROM igrejas_programacao
				WHERE programacao_igreja_id = ?
				AND programacao_status = 'Ativo'
				ORDER BY FIELD(programacao_dia_semana, 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), programacao_hora ASC";
		$st = $this->db->prepare($sql);
		$st->execute([$igrejaId]);
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getHinoPorNumero($numero) {
		$sql = "SELECT titulo, letra FROM hinos_novo_cantico WHERE numero = ?";
		$st = $this->db->prepare($sql);
		$st->execute([$numero]);
		return $st->fetch(PDO::FETCH_ASSOC);
	}

}
