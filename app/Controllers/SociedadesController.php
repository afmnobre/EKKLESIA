<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Sociedade;

class SociedadesController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Sociedade();
    }

    public function index()
    {
        $idIgreja = $_SESSION['usuario_igreja_id'];
        $sociedades = $this->model->getAll($idIgreja);

        $this->view('sociedades/index', [
            'sociedades' => $sociedades
        ]);
    }

	public function salvar()
	{
		$id = $_POST['sociedade_id'] ?? null;
		$idIgreja = $_SESSION['usuario_igreja_id'];

		$dados = [
			'igreja_id' => $idIgreja,
			'nome'      => $_POST['nome'],
			'tipo'      => $_POST['tipo'],
			'genero'    => $_POST['genero'],
			'idade_min' => $_POST['idade_min'],
			'idade_max' => $_POST['idade_max'],
			'status'    => $_POST['status'] ?? 'Ativo'
		];

		if ($id) {
			// Linha 42: Chama o método que acabamos de criar no Model
			$this->model->update($id, $dados);
			$sucesso = "editado";
		} else {
			$this->model->insert($dados);
			$sucesso = "criado";
		}

		header("Location: " . url("sociedades?sucesso=$sucesso"));
		exit;
	}

    public function vincular()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idIgreja = $_SESSION['usuario_igreja_id'];
            $idMembro = $_POST['membro_id'];
            $idSociedade = $_POST['sociedade_id'];
            $funcao = $_POST['funcao'] ?? 'Sócio';

            if ($this->model->saveVinculo($idIgreja, $idSociedade, $idMembro, $funcao)) {
                header("Location: " . url('membros') . "?sucesso=vinculo_sociedade");
            } else {
                header("Location: " . url('membros') . "?erro=vinculo_falhou");
            }
            exit;
        }
    }

	// Altere a linha 73 para aceitar o ID vindo da rota
	public function gerenciar($id = null)
	{
		// Se não houver ID, redireciona de volta para a lista
		if (!$id) {
			header("Location: " . url('sociedades'));
			exit;
		}

		$idIgreja = $_SESSION['usuario_igreja_id'];

		// 1. Busca os dados da sociedade
		$sociedade = $this->model->getById($id, $idIgreja);

		if (!$sociedade) {
			header("Location: " . url('sociedades?erro=nao_encontrada'));
			exit;
		}

		// 2. Busca membros aptos (Filtro por Gênero e Idade)
		$membrosAptos = $this->model->getMembrosAptos(
			$idIgreja,
			$sociedade['sociedade_genero'],
			$sociedade['sociedade_idade_min'],
			$sociedade['sociedade_idade_max'],
			$id
		);

		// 3. Carrega a view gerenciar.php
		$this->view('paginas/sociedades/gerenciar', [
			'sociedade' => $sociedade,
			'membrosAptos' => $membrosAptos
		]);
	}

	public function buscarAptos($idSociedade)
	{
		// 1. Pega os dados da sociedade para saber as regras (idade/gênero)
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$sociedade = $this->model->getById($idSociedade, $idIgreja);

		if (!$sociedade) {
			echo json_encode([]);
			exit;
		}

		// 2. Busca membros que se encaixam no perfil e marca quem já é sócio
		// Este método deve ser criado no seu Model Sociedade
		$membros = $this->model->getMembrosAptos(
			$idIgreja,
			$sociedade['sociedade_genero'],
			$sociedade['sociedade_idade_min'],
			$sociedade['sociedade_idade_max'],
			$idSociedade
		);

		header('Content-Type: application/json');
		echo json_encode($membros);
		exit;
	}

	public function vincularLote() {
		$idSociedade = $_POST['sociedade_id'];
		$membrosIds = $_POST['membros_ids'] ?? [];
		$idIgreja = $_SESSION['usuario_igreja_id'];

		if ($this->model->saveLoteVinculo($idIgreja, $idSociedade, $membrosIds)) {
			header("Location: " . url("sociedades?sucesso=vinculo_lote"));
		} else {
			header("Location: " . url("sociedades?erro=1"));
		}
		exit;
	}


}
