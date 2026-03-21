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
        // No seu método gerenciar, altere a última parte para:
        $this->view('sociedades/gerenciar', [
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

	public function buscarLideranca($idSociedade)
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// Mapeamento de Sociedade para Cargo de Líder
		// IDs das sociedades no seu banco podem variar, ajuste conforme necessário
		$sociedade = $this->model->getById($idSociedade, $idIgreja);
		$cargoId = 0;

		if (strpos($sociedade['sociedade_nome'], 'UPH') !== false) $cargoId = 18;
		elseif (strpos($sociedade['sociedade_nome'], 'SAF') !== false) $cargoId = 17;
		elseif (strpos($sociedade['sociedade_nome'], 'UMP') !== false) $cargoId = 12;
		elseif (strpos($sociedade['sociedade_nome'], 'UPA') !== false) $cargoId = 13;
		elseif (strpos($sociedade['sociedade_nome'], 'UCP') !== false) $cargoId = 14;

		$membros = $this->model->getMembrosParaLideranca($idIgreja, $cargoId);

		header('Content-Type: application/json');
		echo json_encode(['membros' => $membros, 'cargo_id' => $cargoId]);
		exit;
	}

	public function salvarLider()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$idSociedade = $_POST['sociedade_id'];
		$idMembro = $_POST['membro_id'];

		// 1. Buscamos a sociedade para saber o nome dela e definir o cargo certo
		$sociedade = $this->model->getById($idSociedade, $idIgreja);
		$cargoId = 0;

		if (!$sociedade) {
			echo json_encode(['success' => false, 'message' => 'Sociedade não encontrada.']);
			exit;
		}

		// 2. Mesma lógica que você já usa no buscarLideranca()
		if (strpos($sociedade['sociedade_nome'], 'UPH') !== false) $cargoId = 18;
		elseif (strpos($sociedade['sociedade_nome'], 'SAF') !== false) $cargoId = 17;
		elseif (strpos($sociedade['sociedade_nome'], 'UMP') !== false) $cargoId = 12;
		elseif (strpos($sociedade['sociedade_nome'], 'UPA') !== false) $cargoId = 13;
		elseif (strpos($sociedade['sociedade_nome'], 'UCP') !== false) $cargoId = 14;

		if ($cargoId === 0) {
			echo json_encode(['success' => false, 'message' => 'Não foi possível identificar o cargo para esta sociedade.']);
			exit;
		}

		// 3. Salva no banco (Lembre-se de usar o Model atualizado que limpa os vínculos antigos)
		if ($this->model->salvarLider($idIgreja, $idSociedade, $idMembro, $cargoId)) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erro ao salvar líder no banco de dados.']);
		}
		exit;
	}

}
