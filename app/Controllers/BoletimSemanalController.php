<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BoletimSemanal;

class BoletimSemanalController extends Controller
{
    private $model;

    public function __construct()
    {
        // Se não houver sessão de usuário (Admin) E não houver sessão de membro, exige login.
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['membro_id'])) {
            exigirLogin();
        }

        $this->model = new BoletimSemanal();
    }

    /**
     * Exibe o Boletim Semanal consolidado
     */
    public function index()
    {
        // Busca o ID da igreja de quem estiver logado (Admin ou Membro)
        $igrejaId = $_SESSION['usuario_igreja_id'] ?? $_SESSION['membro_igreja_id'];

        if (!$igrejaId) {
            die("Acesso negado: Igreja não identificada.");
        }

        // 1. LITURGIA
        $liturgia = $this->model->getUltimaLiturgia($igrejaId);

		// --- LÓGICA DE HINOS NO CONTROLLER ---
		if ($liturgia && !empty($liturgia['itens'])) {
			foreach ($liturgia['itens'] as &$item) {
				$tipoLower = strtolower($item['tipo'] ?? '');

				if ($tipoLower == 'hino') {
					$numeroHino = preg_replace('/[^0-9]/', '', $item['desc'] ?? '');
					if (!empty($numeroHino)) {
						// CHAMADA PARA O NOVO MÉTODO LIMPO
						$hinoData = $this->model->getHinoLimpo($numeroHino);

						if ($hinoData) {
							$item['hino_titulo'] = $hinoData['titulo'];
							$item['hino_letra']  = $hinoData['letra'];
						}
					}
				}
			}
			unset($item);
		}

        // PROCESSAMENTO DE FOTOS DO DIRIGENTE E PREGADOR
        $dirigente_foto = null;
        $pregador_foto  = null;

        if ($liturgia) {
            if (!empty($liturgia['membro_foto_dirigente']) && !empty($liturgia['registro_dirigente'])) {
                $dirigente_foto = "assets/uploads/{$igrejaId}/membros/{$liturgia['registro_dirigente']}/{$liturgia['membro_foto_dirigente']}";
            }
            if (!empty($liturgia['membro_foto_pregador']) && !empty($liturgia['registro_pregador'])) {
                $pregador_foto = "assets/uploads/{$igrejaId}/membros/{$liturgia['registro_pregador']}/{$liturgia['membro_foto_pregador']}";
            }
        }

        // 2. EVENTOS DAS SOCIEDADES
        $eventosBrutos = $this->model->buscarProximosEventos($igrejaId);
        $eventos = [];

        foreach ($eventosBrutos as $ev) {
            $logo = !empty($ev['sociedade_logo'])
                    ? "assets/uploads/" . $ev['sociedade_logo']
                    : "assets/img/logo-placeholder.png";

            $eventos[] = [
                'titulo'    => $ev['sociedade_evento_titulo'],
                'sociedade' => $ev['sociedade_nome'],
                'data'      => date('d/m', strtotime($ev['sociedade_evento_data_hora_inicio'])),
                'hora'      => date('H:i', strtotime($ev['sociedade_evento_data_hora_inicio'])),
                'local'     => $ev['sociedade_evento_local'],
                'logo'      => $logo
            ];
        }

		// 3. CELEBRAÇÕES (Nascimento, Batismo e Casamento)
		$aniversariantes = $this->model->buscarAniversariantesMes($igrejaId);
		$mesAtual = (int)date('m');
		$anoAtual = (int)date('Y');

		$nascidos  = [];
		$batizados = [];
		$casados   = [];

		foreach ($aniversariantes as $m) {
			// Como a coluna membro_foto_arquivo não existe, definimos como null
			$caminhoFoto = null;

			$nomeMembro = $m['membro_nome'] ?? 'Membro';

			// Nascimento
			if (!empty($m['membro_data_nascimento']) && $m['membro_data_nascimento'] != '0000-00-00') {
				$ts = strtotime($m['membro_data_nascimento']);
				if ((int)date('m', $ts) === $mesAtual) {
					$nascidos[] = [
						'nome' => $nomeMembro,
						'foto' => $caminhoFoto,
						'dia'  => (int)date('d', $ts)
					];
				}
			}

			// Batismo
			if (!empty($m['membro_data_batismo']) && $m['membro_data_batismo'] != '0000-00-00') {
				$ts = strtotime($m['membro_data_batismo']);
				if ((int)date('m', $ts) === $mesAtual) {
					$batizados[] = [
						'nome' => $nomeMembro,
						'foto' => $caminhoFoto,
						'dia'  => (int)date('d', $ts),
						'anos' => $anoAtual - (int)date('Y', $ts)
					];
				}
			}

			// Casamento
			if (($m['membro_estado_civil'] == 'Casado(a)' || $m['membro_estado_civil'] == 'Casado') && !empty($m['membro_data_casamento']) && $m['membro_data_casamento'] != '0000-00-00') {
				$ts = strtotime($m['membro_data_casamento']);
				if ((int)date('m', $ts) === $mesAtual) {
					$casados[] = [
						'nome' => $nomeMembro,
						'foto' => $caminhoFoto,
						'dia'  => (int)date('d', $ts),
						'anos' => $anoAtual - (int)date('Y', $ts)
					];
				}
			}
		}

        // Ordenação por dia
        usort($nascidos, fn($a, $b) => $a['dia'] <=> $b['dia']);
        usort($batizados, fn($a, $b) => $a['dia'] <=> $b['dia']);
        usort($casados, fn($a, $b) => $a['dia'] <=> $b['dia']);

        $meses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        // 4. PROGRAMAÇÃO RECORRENTE
        $programacao = $this->model->getProgramacao($igrejaId);

        // 5. Monta o array de dados final
        $dados = [
            'nomeMes'        => $meses[$mesAtual],
            'eventos'        => $eventos,
            'liturgia'       => $liturgia,
            'programacao'    => $programacao,
            'lideranca'      => $this->model->getLideranca($igrejaId),
            'mensagem'       => $this->model->getUltimaMensagem($igrejaId),
            'nascidos'       => $nascidos,
            'batizados'      => $batizados,
            'casados'        => $casados,
            'dirigente_nome' => $liturgia ? ($liturgia['nome_membro_dirigente'] ?: $liturgia['igreja_liturgia_dirigente_nome']) : 'Não informado',
            'dirigente_foto' => $dirigente_foto,
            'pregador_nome'  => $liturgia ? ($liturgia['nome_membro_pregador'] ?: $liturgia['igreja_liturgia_pregador_nome']) : 'Não informado',
            'pregador_foto'  => $pregador_foto
        ];

        $this->rawview('boletinssemanais/index', $dados);
    }
}
