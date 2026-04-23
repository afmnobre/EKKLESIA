<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Sociedade;
use App\Models\SociedadeEvento;

class SociedadesEventosController extends Controller
{
    private $model;
    private $sociedadeModel;

    public function __construct() {
        $this->model = new SociedadeEvento();
        $this->sociedadeModel = new Sociedade();
    }

	public function index() {
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// 1. Captura o Mês e Ano selecionados ou define o atual como padrão
		$mesSelecionado = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('n');
		$anoSelecionado = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

		// 2. Filtra os eventos por mês e ano (ajuste o método no seu Model para aceitar esses parâmetros)
		// Se o seu getAllGlobal não suportar filtros, você precisará criar o getByMes no Model
		$eventos = $this->model->getAllGlobal($idIgreja, $mesSelecionado, $anoSelecionado);

		$sociedades = $this->sociedadeModel->getAll($idIgreja);

		// 3. Busca os dados da Igreja para o endereço
		$igreja = $this->model->getDadosIgreja($idIgreja);

		// 4. Busca os Membros para o combo
		$membros = $this->model->getMembrosEndereco($idIgreja);

		// 5. Gera a lista de anos para o select (ex: ano passado, atual e os próximos 2)
		$anosDisponiveis = [];
		$anoBase = (int)date('Y');
		for ($i = $anoBase - 1; $i <= $anoBase + 2; $i++) {
			$anosDisponiveis[] = ['ano' => $i];
		}

		$this->view('sociedades/eventos_geral', [
			'eventos'         => $eventos,
			'sociedades'      => $sociedades,
			'igreja'          => $igreja,
			'membros'         => $membros,
			'mesSelecionado'  => $mesSelecionado,
			'anoSelecionado'  => $anoSelecionado,
			'anosDisponiveis' => $anosDisponiveis
		]);
	}

	public function salvar() {
		$id = $_POST['evento_id'] ?? null;
		$idIgreja = $_SESSION['usuario_igreja_id'];

		if (!$idIgreja) {
			die("Erro: ID da Igreja não encontrada na sessão. Faça login novamente.");
		}

		// Monte o array com as chaves que o Model espera
		$dados = [
			'igreja_id' => $idIgreja,
			'soc_id'    => $_POST['sociedade_id'],
			'titulo'    => $_POST['titulo'],
			'descricao' => $_POST['descricao'],
			'local'     => $_POST['local'],
			'inicio'    => $_POST['data_inicio'],
			'fim'       => (!empty($_POST['data_fim'])) ? $_POST['data_fim'] : null,
			'valor'     => (!empty($_POST['valor'])) ? str_replace(',', '.', $_POST['valor']) : 0.00,
			// Captura o status do POST
			'status'    => $_POST['status'] ?? 'Agendado'
		];

		if ($id) {
			$this->model->update($id, $dados);
		} else {
			$this->model->insert($dados);
		}

		header("Location: " . url("SociedadesEventos?sucesso=1"));
		exit;
	}

    public function excluir($id) {
        $idIgreja = $_SESSION['usuario_igreja_id'];

        if ($this->model->delete($id, $idIgreja)) {
            // AJUSTE: SociedadesEventos (S e E maiúsculos)
            header("Location: " . url("SociedadesEventos?sucesso=evento_excluido"));
        } else {
            header("Location: " . url("SociedadesEventos?erro=erro_ao_excluir"));
        }
        exit;
    }

}
