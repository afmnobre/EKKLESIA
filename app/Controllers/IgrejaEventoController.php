<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\IgrejaEvento;
use App\Core\Utils;

class IgrejaEventoController extends Controller
{
    private $model;
    private $igrejaId;

    public function __construct()
    {
        // Verifica login e define o ID da igreja da sessão
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . url("login"));
            exit;
        }
        $this->model = new IgrejaEvento();
        $this->igrejaId = $_SESSION['usuario_igreja_id'];
    }

	public function index()
	{
		// Captura mês e ano da URL ou define o atual como padrão
		$mesSelecionado = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
		$anoSelecionado = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

		// Busca os eventos filtrados
		$eventos = $this->model->getByPeriodo($this->igrejaId, $mesSelecionado, $anoSelecionado);

		// Opcional: Buscar anos que possuem eventos para popular o select de anos
		$anosDisponiveis = $this->model->getAnosComEventos($this->igrejaId);

		$this->view('igrejaseventos/index', [
			'titulo' => 'Agenda de Eventos',
			'eventos' => $eventos,
			'mesSelecionado' => $mesSelecionado,
			'anoSelecionado' => $anoSelecionado,
			'anosDisponiveis' => $anosDisponiveis
		]);
	}

	public function novo()
	{
		$membroModel = new \App\Models\Membro();
		$igrejaModel = new \App\Models\Igreja(); // Certifique-se de que este model existe

		$membros = $membroModel->getAll($this->igrejaId);
		// Busque os dados da igreja pelo ID da igreja da sessão, não o ID do usuário
		$igreja = $igrejaModel->getById($this->igrejaId);

		$this->view('igrejaseventos/novo', [
			'titulo'  => 'Cadastrar Novo Evento',
			'membros' => $membros,
			'igreja'  => $igreja
		]);
	}

    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'igreja_id' => $this->igrejaId,
                'titulo'    => $_POST['evento_titulo'],
                'descricao' => $_POST['evento_descricao'],
                'inicio'    => $_POST['evento_data_hora_inicio'],
                'fim'       => !empty($_POST['evento_data_hora_fim']) ? $_POST['evento_data_hora_fim'] : null,
                'local'     => $_POST['evento_local'],
                'cor'       => $_POST['evento_cor'],
                'status'    => $_POST['evento_status'] ?? 'Agendado'
            ];

            if ($this->model->salvar($dados)) {
                header("Location: " . url("igrejaEvento?sucesso=cadastrado"));
            } else {
                header("Location: " . url("igrejaEvento/novo?erro=falha_ao_salvar"));
            }
            exit;
        }
    }

	public function editar($id)
	{
		$membroModel = new \App\Models\Membro();
		$igrejaModel = new \App\Models\Igreja();

		// Busca o evento para preencher o form
		$evento = $this->model->getById($id);

		if (!$evento) {
			header("Location: " . url("igrejaseventos?erro=nao_encontrado"));
			exit;
		}

		// Busca as listas para o combo Choices
		$membrosDaIgreja = $membroModel->getAll($this->igrejaId);
		$dadoIgreja = $igrejaModel->getById($this->igrejaId);

		// O NOME DAS CHAVES AQUI DEVE SER O MESMO NOME DAS VARIÁVEIS NA VIEW
		$this->view('igrejaseventos/editar', [
			'titulo'  => 'Editar Evento',
			'evento'  => $evento,
			'membros' => $membrosDaIgreja, // Esta chave vira $membros na view
			'igreja'  => $dadoIgreja    // Esta chave vira $igreja na view
		]);
	}

    public function atualizar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'id'        => $id,
                'igreja_id' => $this->igrejaId,
                'titulo'    => $_POST['evento_titulo'],
                'descricao' => $_POST['evento_descricao'],
                'inicio'    => $_POST['evento_data_hora_inicio'],
                'fim'       => !empty($_POST['evento_data_hora_fim']) ? $_POST['evento_data_hora_fim'] : null,
                'local'     => $_POST['evento_local'],
                'cor'       => $_POST['evento_cor'],
                'status'    => $_POST['evento_status']
            ];

            if ($this->model->atualizar($dados)) {
                header("Location: " . url("igrejaEvento?sucesso=atualizado"));
            } else {
                header("Location: " . url("igrejaEvento/editar/{$id}?erro=falha_ao_atualizar"));
            }
            exit;
        }
    }

    public function excluir($id)
    {
        if ($this->model->excluir($id, $this->igrejaId)) {
            header("Location: " . url("igrejaseventos?sucesso=excluido"));
        } else {
            header("Location: " . url("igrejaseventos?erro=falha_ao_excluir"));
        }
        exit;
    }

	public function banner($id)
	{
		// 1. Busca os dados do evento
		$evento = $this->model->getEventoParaBanner($id, $this->igrejaId);

		if (!$evento) {
			$_SESSION['erro'] = "Evento não encontrado.";
			header("Location: " . url("igrejaseventos"));
			exit;
		}

		// 2. Buscar sociedades da igreja para o Builder
		$sociedadeModel = new \App\Models\Sociedade();
		$sociedades = $sociedadeModel->getAll($this->igrejaId);

		// 3. Buscar dados da Igreja e do Pastor (usando o MembroModel)
		$igrejaModel = new \App\Models\Igreja();
		$membroModel = new \App\Models\Membro();

		$dadosIgreja = $igrejaModel->getById($this->igrejaId);

		// Busca os dados completos do pastor se ele estiver definido na igreja
		$dadosPastor = null;
		if (!empty($dadosIgreja['igreja_pastor_id'])) {
			$dadosPastor = $membroModel->getByIdCompleto($dadosIgreja['igreja_pastor_id'], $this->igrejaId);
		}

		// 4. Passar tudo para a view
		$this->view('igrejaseventos/banner_builder', [
			'titulo'     => 'Criar Banner do Evento',
			'evento'     => $evento,
			'sociedades' => $sociedades,
			'igreja'     => $dadosIgreja,
			'pastor'     => $dadosPastor // Nova variável disponível na view
		]);
	}

	public function buscarMembrosApi()
	{
		$termo = $_GET['q'] ?? '';
		$idIgreja = $this->igrejaId;

		$membroModel = new \App\Models\Membro(); // Certifique-se de carregar o model
		$membros = $membroModel->buscarCompletoParaBanner($idIgreja, $termo);

		header('Content-Type: application/json');
		echo json_encode($membros);
		exit;
	}

}
