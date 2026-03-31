<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\SociedadeLider;


class SociedadeLiderController extends Controller {


	/**
	 * Tela de Operação Principal (Dashboard com Métricas)
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

		// Buscamos as novas métricas para alimentar os gráficos do Chart.js
        $metricas = $model->getMetricasDashboard($idSociedade);

		// Cálculo de aproveitamento para o novo card
		$stats = $model->getEstatisticasAproveitamento($idSociedade);

		$this->rawview('sociedade_portal/index', [ // Alterado de index para dashboard
			'sociedade' => $sociedade,
            'membros'   => $model->getMeusMembros($idSociedade),
            'aproveitamento'    => $stats['porcentagem'],
			'sugestoes' => $model->getSugestoesNovosMembros($idSociedade, $idIgreja),
			'eventos'   => $model->getMeusEventos($idSociedade),
			'metricas'  => $metricas, // Enviando os dados de Gênero e Presença
			'titulo'    => 'Dashboard - ' . $sociedade['sociedade_nome']
		]);
	}

	public function dashboard() {
		if (!isset($_SESSION['membro_id']) || !isset($_SESSION['sociedade_ativa_id'])) {
			header("Location: " . url('sociedadeLider/login'));
			exit;
		}

		$model = new SociedadeLider();
		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// Pegamos os dados da sociedade para o cabeçalho
		$sociedade = $model->getSociedadeVinculada($_SESSION['membro_id']);

		// Buscamos as novas métricas para alimentar os gráficos do Chart.js
        $metricas = $model->getMetricasDashboard($idSociedade);

		// Cálculo de aproveitamento para o novo card
		$stats = $model->getEstatisticasAproveitamento($idSociedade);

		$this->rawview('sociedade_portal/dashboard', [ // Alterado de index para dashboard
			'sociedade' => $sociedade,
            'membros'   => $model->getMeusMembros($idSociedade),
            'aproveitamento'    => $stats['porcentagem'],
            'membros_ativos'    => $stats['ativos'],      // FALTAVA ESTA
            'membros_possiveis' => $stats['possiveis'],   // FALTAVA ESTA
			'sugestoes' => $model->getSugestoesNovosMembros($idSociedade, $idIgreja),
			'eventos'   => $model->getMeusEventos($idSociedade),
			'metricas'  => $metricas, // Enviando os dados de Gênero e Presença
			'titulo'    => 'Dashboard - ' . $sociedade['sociedade_nome']
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
		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$idMembroLogado = $_SESSION['membro_id'];

		// 2. Buscamos os dados da sociedade para o Header
		$sociedade = $model->getSociedadeVinculada($idMembroLogado);

		// Pegamos o ID da igreja que vem do vínculo da sociedade
		$idIgreja = $sociedade['membro_igreja_id'];

		// 3. BUSCAMOS OS DADOS FALTANTES PARA O FORMULÁRIO E PARA A LISTA LATERAL
		$igrejaEndereço = $model->getEnderecoIgreja($idIgreja);
		$membrosEndereços = $model->getMembrosComEndereco($idSociedade);
		$eventosCadastrados = $model->getMeusEventos($idSociedade);

		// 4. Enviamos TUDO para a view
		$this->rawview('sociedade_portal/novo_evento', [
			'titulo'    => 'Novo Evento',
			'ativo'     => 'eventos',
			'sociedade' => $sociedade,
			'igreja'    => $igrejaEndereço,      // <--- Resolve o erro da variável $igreja
			'membros'   => $membrosEndereços,   // <--- Resolve o erro da variável $membros
			'eventos'   => $eventosCadastrados  // <--- Carrega a lista da direita
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
				header("Location: " . url('sociedadeLider/NovoEvento?sucesso=1'));
			} else {
				header("Location: " . url('sociedadeLider/novoEvento?erro=1'));
			}
			exit;
		}
	}

	public function deletarEvento($id) {
		// 1. Validação de segurança
		if (!isset($_SESSION['sociedade_ativa_id'])) {
			header("Location: " . url('sociedadeLider/login'));
			exit;
		}

		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$model = new \App\Models\SociedadeLider();

		// 2. Executa a exclusão
		if ($model->excluirEvento($id, $idSociedade)) {
			// Você pode adicionar uma mensagem de sucesso na sessão aqui
			header("Location: " . url('sociedadeLider/novoEvento?sucesso=1'));
		} else {
			header("Location: " . url('sociedadeLider/novoEvento?erro=1'));
		}
		exit;
	}

	/**
	 * Exibe a página de edição de um evento
	 */
	public function editarEvento($id) {
		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$model = new \App\Models\SociedadeLider();

		$evento = $model->getEventoPorId($id, $idSociedade);

		if (!$evento) {
			header("Location: " . url('sociedadeLider/novoEvento?erro=404'));
			exit;
		}

		// Buscamos os dados da sociedade
		$dadosSociedade = $model->getSociedade($idSociedade);

		// Se o array vindo do banco não tiver o nome do membro,
		// injetamos o nome que está na sessão para o header não quebrar
		if (!isset($dadosSociedade['membro_nome'])) {
			$dadosSociedade['membro_nome'] = $_SESSION['usuario_nome'] ?? 'Líder';
		}

		$dados = [
			'sociedade' => $dadosSociedade,
			'membros'   => $model->getMembrosComEndereco($idSociedade),
			'igreja'    => $_SESSION['igreja_nome_sede'] ?? 'Sede Principal',
			'evento'    => $evento,
			'eventos'   => $model->getMeusEventos($idSociedade)
		];

		$this->rawview('sociedade_portal/novo_evento', $dados);
	}

	/**
	 * Processa a atualização via POST
	 */
	public function processarEditarEvento($id) {
		$idSociedade = $_SESSION['sociedade_ativa_id'];
		$model = new \App\Models\SociedadeLider();

		// Captura os dados do formulário
		$dados = $_POST;

		if ($model->atualizarEvento($id, $idSociedade, $dados)) {
			header("Location: " . url('sociedadeLider/novoEvento?sucesso=alterado'));
		} else {
			header("Location: " . url('sociedadeLider/editarEvento/'.$id.'?erro=falha'));
		}
		exit;
	}

	public function carregarListaPresenca() {
		$idEvento = $_POST['evento_id'];
		$idSociedade = $_SESSION['sociedade_ativa_id'];

		$model = new \App\Models\SociedadeLider();
		$lista = $model->getListaPresencaEvento($idEvento, $idSociedade);

		echo json_encode($lista);
		exit;
	}

	public function registrarPresenca() {
		// Recebemos os IDs via POST enviados pelo JavaScript acima
		$igrejaId    = $_POST['igreja_id'] ?? 0;
		$sociedadeId = $_POST['sociedade_id'] ?? 0;
		$eventoId    = $_POST['evento_id'] ?? 0;
		$membroId    = $_POST['membro_id'] ?? 0;
		$status      = $_POST['status'] ?? 'Presente';

		// Validação mínima para evitar erros de Foreign Key
		if ($igrejaId > 0 && $eventoId > 0 && $membroId > 0) {
			$model = new \App\Models\SociedadeLider();

			$dados = [
				'igreja_id'    => (int)$igrejaId,
				'sociedade_id' => (int)$sociedadeId,
				'evento_id'    => (int)$eventoId,
				'membro_id'    => (int)$membroId,
				'status'       => $status
			];

			try {
				// O Model deve usar estes índices para o INSERT/UPDATE
				$model->salvarPresenca($dados);
			} catch (\Exception $e) {
				error_log("Erro ao salvar presença: " . $e->getMessage());
			}
		}

		// Como é um envio via Iframe, apenas encerramos a execução
		exit;
    }

	public function banner()
	{
		$idSociedade = $_SESSION['sociedade_ativa_id'] ?? null;

		if (!$idSociedade) {
			header("Location: " . url("sociedadeLider?erro=acesso_negado"));
			exit;
		}

		$model = new \App\Models\SociedadeLider();
		$dados = $model->getDadosBannerPortal($idSociedade);

		if (!$dados) {
			die("Sociedade não encontrada.");
		}

		// RESOLUÇÃO DO ERRO DO HEADER E DA FOTO:
		// Mapeamos os aliases da query para as chaves que o header.php espera
		$sociedadeData = $dados['sociedade'];

		// Dados de Identidade
		$sociedadeData['membro_nome']             = $sociedadeData['lider_nome'];
		$sociedadeData['membro_igreja_id']        = $sociedadeData['sociedade_igreja_id'];

		// Dados para o caminho da imagem (URL do assets)
		$sociedadeData['membro_registro_interno'] = $sociedadeData['lider_registro'];
		$sociedadeData['lider_foto']              = $sociedadeData['lider_foto'];

		// Renderização da View
		$this->rawview('sociedade_portal/banner_builder', [
			'sociedade' => $sociedadeData, // Contém agora todas as chaves para o header e para o builder
			'redes'     => $dados['redes'],
			'membros'   => $dados['membros']
		]);
	}

	public function salvarBanner() {
		// 1. Verifica se a requisição é POST
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

			// 2. Instancia o Model (conforme seu padrão nos outros métodos)
			$model = new \App\Models\SociedadeLider();

			// 3. Captura os dados da sessão e do post (conforme seu padrão)
			$idSociedade = $_SESSION['sociedade_ativa_id'] ?? null;
			$jsonLayout = $_POST['layout'] ?? null;

			if (!$idSociedade || !$jsonLayout) {
				echo json_encode(['status' => 'error', 'message' => 'Dados incompletos ou sessão expirada.']);
				exit;
			}

			// 4. Executa a gravação usando o Model instanciado localmente
			if ($model->salvarLayoutBanner($idSociedade, $jsonLayout)) {
				echo json_encode(['status' => 'success', 'message' => 'Layout do Banner salvo com sucesso!']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Erro ao gravar no banco de dados.']);
			}
			exit;
		}
	}

}
