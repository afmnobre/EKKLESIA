<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\SociedadeLider;

class SociedadeLiderController extends Controller {

    /**
     * Tela de Operação Principal (Membros e Sugestões)
     */
	public function index() {
		if (!isset($_SESSION['membro_id']) || !isset($_SESSION['sociedade_ativa_id'])) {
			header("Location: " . url('sociedadeLider/login'));
			exit;
		}

		$model = new SociedadeLider();
		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// Pegamos os dados da sociedade para o cabeçalho
		$sociedade = $model->getSociedadeVinculada($_SESSION['membro_id']);

		$this->rawview('sociedade_portal/index', [
			'sociedade' => $sociedade,
			'membros' => $model->getMeusMembros($idSociedade),
			'sugestoes' => $model->getSugestoesNovosMembros($idSociedade, $idIgreja),
			'eventos' => $model->getMeusEventos($idSociedade),
			'titulo' => 'Gerenciar ' . $sociedade['sociedade_nome']
		]);
	}

    /**
     * Tela de Login
     */
	public function login() {
		$model = new SociedadeLider();

		// Defina aqui como você obtém o ID da igreja (fixo, subdomínio, etc.)
		$idIgreja = 1; // Exemplo: ID 1

		$listaSociedades = $model->getTodasSociedadesAtivas($idIgreja);
		$nomeIgreja = $model->getNomeIgreja($idIgreja);

		$this->rawview('sociedade_portal/login', [
			'titulo' => 'Login - Portal de Sociedades',
			'sociedades' => $listaSociedades,
			'nomeIgreja' => $nomeIgreja // Passando o nome para a View
		]);
	}

    /**
     * Autenticação
     */
	public function autenticar() {
		$celular = $_POST['celular'] ?? ''; // Ex: (11) 98765-4321
		$senha = $_POST['senha'] ?? '';

		$model = new SociedadeLider();
		$auth = $model->login($celular);

		if ($auth && password_verify($senha, $auth['sociedade_senha'])) {
			$_SESSION['membro_id'] = $auth['membro_id'];
			$_SESSION['membro_nome'] = $auth['membro_nome'];
			$_SESSION['sociedade_ativa_id'] = $auth['sociedade_id'];
			$_SESSION['usuario_igreja_id'] = $auth['membro_igreja_id'];

			header("Location: " . url('sociedadeLider/index'));
		} else {
			header("Location: " . url('sociedadeLider/login?erro=1'));
		}
		exit;
	}

	/**
	 * Adiciona um membro sugerido à sociedade via AJAX
	 */
	public function vincularMembro() {
		if (!isset($_SESSION['sociedade_ativa_id'])) {
			echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada']);
			exit;
		}

		$membroId = $_POST['membro_id'] ?? null;
		$idSociedade = $_SESSION['sociedade_ativa_id'];

		if ($membroId) {
			$db = \App\Core\Database::getInstance();

			// Insere na tabela de relacionamento
			$sql = "INSERT INTO sociedades_membros
					(sociedade_membro_sociedade_id, sociedade_membro_membro_id, sociedade_membro_funcao)
					VALUES (?, ?, 'Sócio')";

			$stmt = $db->prepare($sql);
			$res = $stmt->execute([$idSociedade, $membroId]);

			echo json_encode(['sucesso' => $res]);
		} else {
			echo json_encode(['sucesso' => false, 'erro' => 'ID do membro não enviado']);
		}
		exit;
	}

	/**
	 * Remove um membro da sociedade
	 */
	public function desvincularMembro() {
		// 1. Verificação de Segurança (Sessão)
		if (!isset($_SESSION['membro_id']) || !isset($_SESSION['sociedade_ativa_id'])) {
			header('Content-Type: application/json');
			echo json_encode(['sucesso' => false, 'erro' => 'Sessão expirada.']);
			exit;
		}

		$membroId = $_POST['membro_id'] ?? null;
		$idSociedade = $_SESSION['sociedade_ativa_id'];

		if ($membroId && $idSociedade) {
			$model = new \App\Models\SociedadeLider();

			// 2. Chama o método do Model
			$res = $model->desvincularMembro($membroId, $idSociedade);

			header('Content-Type: application/json');
			echo json_encode(['sucesso' => $res]);
		} else {
			header('Content-Type: application/json');
			echo json_encode(['sucesso' => false, 'erro' => 'Dados incompletos para desvincular.']);
		}
		exit;
	}

	public function buscar_historico() {
		$id = $_POST['membro_id'] ?? null;

		if (!$id) {
			echo json_encode([]);
			return; // Interrompe a execução aqui
		}

		$model = new SociedadeLider();
		echo json_encode($model->getHistoricoMembro($id));
	}

	public function salvar_observacao() {
		$id = $_POST['membro_id'] ?? null;
		$texto = $_POST['texto'] ?? '';

		if ($id && !empty($texto)) {
			$model = new SociedadeLider();
			if ($model->salvarObservacao($id, $texto)) {
				echo json_encode(['sucesso' => true]);
				exit;
			}
		}
		echo json_encode(['sucesso' => false]);
	}

	/**
	 * Finaliza a sessão do líder e redireciona para o login
	 */
	public function logout() {
		// Inicia a sessão caso ainda não tenha sido iniciada
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		// Remove apenas as chaves específicas do portal do líder
		// (Isso evita deslogar o usuário de outras áreas do sistema, se houver)
		unset($_SESSION['membro_id']);
		unset($_SESSION['membro_nome']);
		unset($_SESSION['sociedade_ativa_id']);
		unset($_SESSION['usuario_igreja_id']);

		// Se preferir limpar TUDO da sessão, use: session_destroy();

		// Redireciona para a tela de login
		header("Location: " . url('sociedadeLider/login'));
		exit;
	}

	public function novoEvento() {
		// 1. Validação de segurança básica
		if (!isset($_SESSION['sociedade_ativa_id'])) {
			header("Location: " . url('sociedadeLider/login'));
			exit;
		}

		$model = new SociedadeLider();

		// 2. Buscamos os dados da sociedade para o Header não dar erro
		$sociedade = $model->getSociedadeVinculada($_SESSION['membro_id']);

		// 3. Enviamos TUDO para a view
		$this->rawview('sociedade_portal/novo_evento', [
			'titulo'    => 'Novo Evento',
			'ativo'     => 'eventos', // <--- Isso resolve a marcação do menu
			'sociedade' => $sociedade  // <--- Isso resolve os Warnings do Header
		]);
	}

	public function processarNovoEvento() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$model = new SociedadeLider();

			$dados = [
				'igreja_id'    => $_SESSION['usuario_igreja_id'],
				'sociedade_id' => $_SESSION['sociedade_ativa_id'],
				'titulo'       => $_POST['titulo'],
				'descricao'    => $_POST['descricao'],
				'local'        => $_POST['local'],
				'data_inicio'  => $_POST['data_inicio'],
				'data_fim'     => !empty($_POST['data_fim']) ? $_POST['data_fim'] : null,
				'valor'        => !empty($_POST['valor']) ? str_replace(',', '.', $_POST['valor']) : 0.00
			];

			if ($model->salvarEvento($dados)) {
				header("Location: " . url('sociedadeLider/index?sucesso=1'));
			} else {
				header("Location: " . url('sociedadeLider/novoEvento?erro=1'));
			}
			exit;
		}
	}

}
