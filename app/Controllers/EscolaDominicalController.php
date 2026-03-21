<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EscolaDominical;
use App\Core\Database;

class EscolaDominicalController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new EscolaDominical();
    }

    // LISTAR CLASSES (INDEX)
	public function index() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Dados necessários apenas para a listagem e criação de classes
		$classes = $this->model->getClassesByIgreja($igrejaId);
		$configuracoes = $this->model->listarConfiguracoes($igrejaId);

		// Membros para o select de professor no modal
		$db = \App\Core\Database::getInstance();
		$stMembros = $db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
		$stMembros->execute([$igrejaId]);
		$membros = $stMembros->fetchAll(\PDO::FETCH_ASSOC);

		// Renderiza a view original (index.php)
		$this->view('escoladominical/index', [
			'classes' => $classes,
			'configuracoes' => $configuracoes,
			'membros' => $membros
		]);
	}

	public function cadastrarClasse()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId    = $_SESSION['usuario_igreja_id'];
			$nomeClasse  = $_POST['classe_nome'] ?? null;
			$professorId = $_POST['classe_professor_id'] ?? null;
			$idadeMin    = $_POST['classe_idade_min'] ?? 0;
			$idadeMax    = $_POST['classe_idade_max'] ?? 99;

			if ($nomeClasse && $professorId) {
				$db = \App\Core\Database::getInstance();

				// Note que adicionei as colunas de idade na Query
				$sql = "INSERT INTO classes_escola
						(classe_igreja_id, classe_nome, classe_professor_id, classe_idade_min, classe_idade_max)
						VALUES (?, ?, ?, ?, ?)";

				$stmt = $db->prepare($sql);

				if ($stmt->execute([$igrejaId, $nomeClasse, $professorId, $idadeMin, $idadeMax])) {
					header("Location: " . url('escolaDominical?sucesso=1'));
					exit;
				}
			}
		}
		header("Location: " . url('escolaDominical?erro=1'));
		exit;
	}

	public function cadastrarConfiguracao() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];
			$nome = $_POST['config_nome'];
			$min  = $_POST['config_idade_min'];
			$max  = $_POST['config_idade_max'];

			$this->model->salvarConfiguracao($igrejaId, $nome, $min, $max);
			header("Location: " . url('escolaDominical'));
			exit;
		}
	}

	// Listar as configurações (A chamada que vem do Sidebar)
	public function configuracoes()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$configuracoes = $this->model->listarConfiguracoes($igrejaId);

		$this->view('escoladominical/configuracoes', [
			'configuracoes' => $configuracoes
		]);
	}

	// Salvar nova configuração de sala
	public function salvarConfig()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];
			$nome = $_POST['config_nome'] ?? '';
			$min  = $_POST['config_idade_min'] ?? 0;
			$max  = $_POST['config_idade_max'] ?? 99;

			if (!empty($nome)) {
				$db = \App\Core\Database::getInstance();
				$sql = "INSERT INTO classes_config (config_igreja_id, config_nome, config_idade_min, config_idade_max) VALUES (?, ?, ?, ?)";
				$stmt = $db->prepare($sql);
				$stmt->execute([$igrejaId, $nome, $min, $max]);
			}
		}
		header("Location: " . url('escolaDominical/configuracoes'));
		exit;
	}

	// Excluir configuração
	public function excluirConfig($id)
	{
		$db = \App\Core\Database::getInstance();
		$stmt = $db->prepare("DELETE FROM classes_config WHERE config_id = ? AND config_igreja_id = ?");
		$stmt->execute([$id, $_SESSION['usuario_igreja_id']]);

		header("Location: " . url('escolaDominical/configuracoes'));
		exit;
	}

	// Rota AJAX: Busca os dados para preencher o modal de gerenciamento
	public function getDadosGerenciamentoAjax($classeId)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$classe = $this->model->getClasseById($classeId);
		$matriculados = $this->model->getAlunosMatriculados($classeId);
		$sugestoes = $this->model->getSugestoesAlunos($igrejaId, $classeId);

		// Retorna tudo como JSON
		echo json_encode([
			'classe' => $classe,
			'matriculados' => $matriculados,
			'sugestoes' => $sugestoes
		]);
		exit;
	}

	// Rota AJAX: Adicionar aluno (Matricular)
	public function matricularAlunoAjax()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$classeId = $_POST['classe_id'];
			$membroId = $_POST['membro_id'];

			$success = $this->model->matricularAluno($classeId, $membroId);
			echo json_encode(['success' => $success]);
			exit;
		}
	}

	// Rota AJAX: Remover aluno (Desmatricular)
	public function removerAlunoAjax()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$classeMembroId = $_POST['classe_membro_id'];

			$success = $this->model->removerAluno($classeMembroId);
			echo json_encode(['success' => $success]);
			exit;
		}
	}

	public function cadastrar()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId    = $_SESSION['usuario_igreja_id'];
			$nome        = $_POST['classe_nome'] ?? '';
			$professorId = $_POST['classe_professor_id'] ?? null;
			$idadeMin    = $_POST['classe_idade_min'] ?? 0;
			$idadeMax    = $_POST['classe_idade_max'] ?? 99;

			if (!empty($nome) && !empty($professorId)) {
				// Chamamos o Model para inserir
				$success = $this->model->inserirClasse($igrejaId, $nome, $professorId, $idadeMin, $idadeMax);

				if ($success) {
					header("Location: " . url('escolaDominical?sucesso=1'));
				} else {
					header("Location: " . url('escolaDominical?erro=1'));
				}
				exit;
			}
		}
		header("Location: " . url('escolaDominical'));
		exit;
	}

	public function chamada($classeId)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$dataSelecionada = $_GET['data'] ?? date('Y-m-d');

		// Busca detalhes da classe para o cabeçalho
		$classe = $this->model->getClasseById($classeId);

		// Verifica se a classe pertence à igreja do usuário (segurança)
		if (!$classe || $classe['classe_igreja_id'] != $igrejaId) {
			header("Location: " . url('escolaDominical'));
			exit;
		}

		// Busca alunos e se já possuem presença registrada nessa data
		$alunos = $this->model->getAlunosEPresenca($classeId, $dataSelecionada);

		$this->view('escoladominical/chamada', [
			'classe' => $classe,
			'alunos' => $alunos,
			'dataSelecionada' => $dataSelecionada
		]);
	}

	public function salvarPresenca()
	{
		// Verifica se a requisição é POST
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$classeId = $_POST['classe_id'] ?? null;
			$membroId = $_POST['membro_id'] ?? null;
			$data     = $_POST['data']      ?? null;
			$status   = $_POST['status']    ?? 0; // 1 para presente, 0 para falta

			if ($classeId && $membroId && $data) {
				// Chama o Model para fazer o "Upsert" (Insert ou Update)
				$success = $this->model->salvarPresenca($classeId, $membroId, $data, $status);

				header('Content-Type: application/json');
				echo json_encode(['success' => $success]);
				exit;
			}
		}

		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
		exit;
    }

	public function dashboard() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// 1. Métricas existentes
		$ocupacao    = $this->model->getTaxaOcupacao($igrejaId);
		$comparativo = $this->model->getComparativoPresenca($igrejaId);
		$sumidos     = $this->model->getAlunosSumidos($igrejaId);
		$classes     = $this->model->getClassesByIgreja($igrejaId);

		// 2. Novas Métricas solicitadas
		$topAssiduidade   = $this->model->getTopAssiduidade($igrejaId);
		$resumoMatriculas = $this->model->getResumoMatriculas($igrejaId);
		$faixasEtarias    = $this->model->getDistribuicaoEtaria($igrejaId);

		// CORREÇÃO: Buscando os dados para o segundo gráfico (Membros fora da EBD)
		$faixasEtariasFora = $this->model->getDistribuicaoEtariaNaoMatriculados($igrejaId);

		// Adiciona o Top 5 individual para cada classe dentro do array $classes
		foreach ($classes as $key => $classe) {
			$classes[$key]['top_alunos'] = $this->model->getTopAssiduidadePorClasse($classe['classe_id']);
		}

		// Retorno completo para a View
		$this->view('escoladominical/dashboard', [
			'ocupacao'          => $ocupacao,
			'comparativo'       => $comparativo,
			'sumidos'           => $sumidos,
			'classes'           => $classes,
			'topAssiduidade'    => $topAssiduidade,
			'resumoMatriculas'  => $resumoMatriculas,
			'faixasEtarias'     => $faixasEtarias,
			'faixasEtariasFora' => $faixasEtariasFora // Variável agora disponível na View
		]);
    }

    //SISTEMA DE PRESENÇA PRO CAMERA
	public function registrarPresencaAjax() {
		// Desativa a exibição de erros na tela para não sujar o JSON
		ini_set('display_errors', 0);
		error_reporting(E_ALL);

		if (ob_get_length()) ob_clean();
		header('Content-Type: application/json');

		try {
			$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;
			$classeId = $_POST['classe_id'] ?? null;
			$membroRegistro = $_POST['membro_id'] ?? null;

			if (!$igrejaId || !$classeId || !$membroRegistro) {
				echo json_encode(['status' => 'erro', 'mensagem' => 'Sessão expirada ou dados incompletos.']);
				exit;
			}

			$resultado = $this->model->registrarPresencaQRCode($igrejaId, $classeId, $membroRegistro);
			echo json_encode($resultado);

		} catch (\Exception $e) {
			echo json_encode(['status' => 'erro', 'mensagem' => 'Erro de sistema: ' . $e->getMessage()]);
		}
		exit;
	}


}
