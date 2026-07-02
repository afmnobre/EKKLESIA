<?php
// Arquivo: app/Controllers/MuralPublicoController.php

namespace App\Controllers;

use App\Core\Controller; // Ajuste conforme o namespace do seu framework base

class MuralPublicoController extends Controller {

    public function index($igrejaId) {
        $db = \App\Core\Database::getInstance();

		$sql = "SELECT
					m.membro_id,
					m.membro_nome,
					m.membro_email,
					m.membro_registro_interno,
					mf.membro_foto_arquivo,
					GROUP_CONCAT(DISTINCT c.cargo_nome SEPARATOR ', ') as cargos_nomes,
					GROUP_CONCAT(DISTINCT s.sociedade_sigla SEPARATOR ', ') as sociedades_siglas
				FROM membros m
				LEFT JOIN membros_fotos mf ON m.membro_id = mf.membro_foto_membro_id
				LEFT JOIN membros_cargos_vinculo mcv ON m.membro_id = mcv.vinculo_membro_id
				LEFT JOIN cargos c ON mcv.vinculo_cargo_id = c.cargo_id
				LEFT JOIN sociedades_membros sm ON m.membro_id = sm.sociedade_membro_membro_id
				LEFT JOIN sociedades s ON sm.sociedade_membro_sociedade_id = s.sociedade_id
				WHERE m.membro_igreja_id = ?
				  AND m.membro_status = 'Ativo'
				  AND TIMESTAMPDIFF(YEAR, m.membro_data_nascimento, CURDATE()) >= 18
				GROUP BY m.membro_id, mf.membro_foto_arquivo
				ORDER BY m.membro_nome ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([$igrejaId]);
        $membros = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($membros as &$m) {
            $sqlMsg = "SELECT * FROM membro_mural_mensagens WHERE msg_membro_id = ? ORDER BY mensagem_id DESC";
            $stmtMsg = $db->prepare($sqlMsg);
            $stmtMsg->execute([$m['membro_id']]);
            $m['mensagens'] = $stmtMsg->fetchAll(\PDO::FETCH_ASSOC);
        }

        $this->rawview('igreja/mural_membros', [
            'membros'   => $membros,
            'igreja_id' => $igrejaId
        ]);
    }

    public function enviarMensagem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = \App\Core\Database::getInstance();
            $membroId = $_POST['membro_id'];
            $igrejaId = $_POST['igreja_id'];

            $sql = "INSERT INTO membro_mural_mensagens (msg_membro_id, msg_autor, msg_tipo, msg_texto) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $membroId,
                trim($_POST['autor']),
                $_POST['msg_tipo'],
                trim($_POST['texto'])
            ]);

            header("Location: " . full_url("muralPublico/index/{$igrejaId}?sucesso=1"));
            exit;
        }
    }

    // Arquivo: app/Controllers/MuralPublicoController.php
	// Substitua ou adicione o método deletarMensagem() sem a trava de sessão

	public function deletarMensagem() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$db = \App\Core\Database::getInstance();
			$mensagemId = $_POST['mensagem_id'];
			$igrejaId = $_POST['igreja_id'];

			$sql = "DELETE FROM membro_mural_mensagens WHERE mensagem_id = ?";
			$stmt = $db->prepare($sql);
			$stmt->execute([$mensagemId]);

			header("Location: " . full_url("muralPublico/index/{$igrejaId}?sucesso=deletado"));
			exit;
		}
	}


}
