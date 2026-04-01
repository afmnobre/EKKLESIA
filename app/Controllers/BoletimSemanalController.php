<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BoletimSemanal;

class BoletimSemanalController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new BoletimSemanal();
    }

    /**
     * Exibe o Boletim Semanal consolidado
     */
	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// 1. LITURGIA (Busca os dados brutos com os Joins de Fotos e Igreja)
		$liturgia = $this->model->getUltimaLiturgia($igrejaId);

		// PROCESSAMENTO DE FOTOS DO DIRIGENTE E PREGADOR
		$dirigente_foto = null;
		$pregador_foto  = null;

		if ($liturgia) {
			// Foto Dirigente
			if (!empty($liturgia['membro_foto_dirigente']) && !empty($liturgia['registro_dirigente'])) {
				$dirigente_foto = "assets/uploads/{$igrejaId}/membros/{$liturgia['registro_dirigente']}/{$liturgia['membro_foto_dirigente']}";
			}

			// Foto Pregador
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

		// 3. ANIVERSARIANTES
		$aniversariantes = $this->model->buscarAniversariantesMes($igrejaId);
		$mesAtual = (int)date('m');
		$nascidos = [];
		$batizados = [];

		foreach ($aniversariantes as $m) {
			$registro = $m['membro_registro_interno'];
			$arquivo  = $m['membro_foto_arquivo'];

			$caminhoFoto = (!empty($arquivo) && !empty($registro))
				? "assets/uploads/{$igrejaId}/membros/{$registro}/{$arquivo}"
				: null;

			$nomeMembro = $m['membro_nome'] ?? 'Membro';

			if (!empty($m['membro_data_nascimento']) && $m['membro_data_nascimento'] != '0000-00-00') {
				$timestamp = strtotime($m['membro_data_nascimento']);
				if ((int)date('m', $timestamp) === $mesAtual) {
					$nascidos[] = [
						'nome' => $nomeMembro,
						'foto' => $caminhoFoto,
						'dia'  => (int)date('d', $timestamp)
					];
				}
			}

			if (!empty($m['membro_data_batismo']) && $m['membro_data_batismo'] != '0000-00-00') {
				$timestampBat = strtotime($m['membro_data_batismo']);
				if ((int)date('m', $timestampBat) === $mesAtual) {
					$batizados[] = [
						'nome' => $nomeMembro,
						'foto' => $caminhoFoto,
						'dia'  => (int)date('d', $timestampBat),
						'anos' => date('Y') - date('Y', $timestampBat)
					];
				}
			}
		}

		usort($nascidos, fn($a, $b) => $a['dia'] <=> $b['dia']);
		usort($batizados, fn($a, $b) => $a['dia'] <=> $b['dia']);

		$meses = [
			1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
			5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
			9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
		];

		// 4. PROGRAMAÇÃO RECORRENTE (Agenda Semanal)
		// Chama o método que busca na tabela igrejas_programacao
		$programacao = $this->model->getProgramacao($igrejaId);

		// 5. Monta o array de dados final enviando tudo para a View
		$dados = [
			'nomeMes'        => $meses[$mesAtual],
			'eventos'        => $eventos,
			'liturgia'       => $liturgia,
			'programacao'    => $programacao, // ADICIONADO AQUI
			'lideranca'      => $this->model->getLideranca($igrejaId),
			'mensagem'       => $this->model->getUltimaMensagem($igrejaId),
			'nascidos'       => $nascidos,
			'batizados'      => $batizados,
			'dirigente_nome' => $liturgia ? ($liturgia['nome_membro_dirigente'] ?: $liturgia['igreja_liturgia_dirigente_nome']) : 'Não informado',
			'dirigente_foto' => $dirigente_foto,
			'pregador_nome'  => $liturgia ? ($liturgia['nome_membro_pregador'] ?: $liturgia['igreja_liturgia_pregador_nome']) : 'Não informado',
			'pregador_foto'  => $pregador_foto
		];

		$this->rawview('boletinssemanais/index', $dados);
	}
}
