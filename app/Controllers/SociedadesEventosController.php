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

		// Busca eventos e sociedades (Já estava funcionando)
		$eventos = $this->model->getAllGlobal($idIgreja);
		$sociedades = $this->sociedadeModel->getAll($idIgreja);

		// 1. Busca os dados da Igreja para o endereço (Novo método no Model)
		$igreja = $this->model->getDadosIgreja($idIgreja);

		// 2. Busca os Membros para o combo (Novo método no Model)
		$membros = $this->model->getMembrosEndereco($idIgreja);

		$this->view('sociedades/eventos_geral', [
			'eventos' => $eventos,
			'sociedades' => $sociedades,
			'igreja' => $igreja,
			'membros' => $membros
		]);
	}

	public function salvar() {
		$id = $_POST['evento_id'] ?? null;
		$idIgreja = $_SESSION['usuario_igreja_id'];

        // Se a sessão estiver vazia, o banco vai rejeitar a FK
        if (!$idIgreja) {
            die("Erro: ID da Igreja não encontrada na sessão. Faça login novamente.");
        }

		// Monte o array EXATAMENTE com as chaves que o Model espera
		$dados = [
			'igreja_id' => $idIgreja,
			'soc_id'    => $_POST['sociedade_id'],
			'titulo'    => $_POST['titulo'],
			'descricao' => $_POST['descricao'],
			'local'     => $_POST['local'],
			'inicio'    => $_POST['data_inicio'],
			'fim'       => (!empty($_POST['data_fim'])) ? $_POST['data_fim'] : null,
			'valor'     => (!empty($_POST['valor'])) ? str_replace(',', '.', $_POST['valor']) : 0.00,
			'status'    => $_POST['status'] ?? 'Agendado'
		];

		if ($id) {
			// No update, o $id vai por fora ou dentro do array,
			// mas as chaves de :titulo, :local, etc, devem estar em $dados
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
