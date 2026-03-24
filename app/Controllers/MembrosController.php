<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Membro;

class MembrosController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Membro();
    }

    // Corresponde a: url('membros')
	public function index()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// CONSULTA 1: Todos os membros da igreja
		$membros = $this->model->getAll($idIgreja);

		if (empty($membros)) {
			return $this->view('membros/index', ['membros' => [], 'todosCargos' => $this->model->getTodosCargos()]);
		}

		// Coletamos todos os IDs de membros em um array simples [1, 2, 3...]
		$idsMembros = array_column($membros, 'membro_id');

		// CONSULTA 2: Todos os cargos (você já faz isso)
		$todosCargos = $this->model->getTodosCargos();
		$mapaCargos = array_column($todosCargos, 'cargo_nome', 'cargo_id');

		// CONSULTA 3: Todos os vínculos de cargos e históricos DE UMA VEZ
		// Para isso, precisamos de dois novos métodos no Model que usem "WHERE IN (...)"
		$todosVinculos = $this->model->getCargosParaVariosMembros($idsMembros);
		$todosHistoricos = $this->model->getHistoricosParaVariosMembros($idsMembros);

		// Agora organizamos os dados no loop (Sem tocar no Banco de Dados!)
		foreach ($membros as &$m) {
			$id = $m['membro_id'];

			// Filtra cargos desse membro no array geral
			$meusCargosIds = $todosVinculos[$id] ?? [];
			$m['cargos_selecionados'] = $meusCargosIds;

			$nomes = [];
			foreach ($meusCargosIds as $cid) {
				if (isset($mapaCargos[$cid])) $nomes[] = $mapaCargos[$cid];
			}
			$m['membro_cargo'] = !empty($nomes) ? implode(', ', $nomes) : 'Membro Comum';

			// Filtra históricos desse membro no array geral
			$m['historicos'] = $todosHistoricos[$id] ?? [];
		}

		$this->view('membros/index', [
			'membros' => $membros,
			'todosCargos' => $todosCargos
		]);
	}

	public function updateCargos()
	{
		$membroId = $_POST['membro_id'] ?? null;
		$cargosIds = $_POST['cargos'] ?? [];

		if ($membroId) {
			// Removi o bloco de diagnóstico e deixei o fluxo normal
			if ($this->model->saveCargosVinculo($membroId, $cargosIds)) {
				header("Location: " . url('membros') . "?sucesso=cargos_atualizados");
			} else {
				die("Erro persistente no Model. Verifique permissões de escrita.");
			}
		}
		exit;
	}

    // Corresponde a: url('membros/create')
    public function create()
    {
        $this->view('membros/cadastrar');
    }

    // Processamento do formulário
	public function store()
	{
		$membroModel = new \App\Models\Membro();

		$idIgreja = $_SESSION['usuario_igreja_id'] ?? null;
		if (!$idIgreja) {
			header("Location: " . url('login'));
			exit;
		}

		$proximoId = $membroModel->getNextId();
		$ano       = date('Y');
		$mes       = date('m');
		$idSufixo  = str_pad($proximoId, 4, '0', STR_PAD_LEFT);
		$registroInterno = "{$idIgreja}{$ano}{$mes}{$idSufixo}";

		$data = [
			'igreja_id'        => $idIgreja,
			'registro_interno' => $registroInterno,
			'nome'             => $_POST['nome'],
			'nascimento'       => $_POST['data_nascimento'] ?: null,
			'genero'           => $_POST['genero'] ?? null,
			'email'            => $_POST['email'] ?? null,
			'telefone'         => $_POST['telefone'] ?? null,
			'batismo'          => $_POST['data_batismo'] ?: null, // Novo campo capturado do HTML
			'status'           => 'Ativo'
		];

		if ($membroModel->insert($data)) {
			header("Location: " . url('membros') . "?sucesso=1");
			exit;
		} else {
			die("Erro ao inserir no banco de dados. Verifique o Model.");
		}
	}

	public function updateEndereco()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'] ?? null;

		if (!$idIgreja || $_SERVER['REQUEST_METHOD'] !== 'POST') {
			header("Location: " . url('membros'));
			exit;
		}

		$membroModel = new \App\Models\Membro();

		$data = [
			'membro_id' => $_POST['membro_id'],
			'igreja_id' => $idIgreja,
			'rua'       => $_POST['rua'],
			'cidade'    => $_POST['cidade'],
			'estado'    => $_POST['estado'],
			'cep'       => $_POST['cep']
        ];

		if ($membroModel->saveEndereco($data)) {
			header("Location: " . url('membros') . "?sucesso=endereco_atualizado");
		} else {
			header("Location: " . url('membros') . "?erro=1");
		}
		exit;
	}

	public function uploadFoto()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'] ?? null;
		$membroId = $_POST['membro_id'];
		$registro = $_POST['membro_registro_interno'];

		if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {

			$extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
			$novoNome = "perfil_" . time() . "." . $extensao;

			// 1. Define o caminho absoluto subindo níveis a partir deste arquivo (Controller)
			// Se o controller está em /app/Controllers/, subimos 2 níveis para a raiz do projeto
			$raizProjeto = dirname(__DIR__, 2);

			// Caminho final: /var/www/html/EKKLESIA/public/assets/uploads/ID/REGISTRO/
			$diretorioDestino = $raizProjeto . "/public/assets/uploads/{$idIgreja}/{$registro}/";

			// 2. Tenta criar a pasta com permissão total (0777)
			if (!is_dir($diretorioDestino)) {
				if (!mkdir($diretorioDestino, 0777, true)) {
					// Se falhar aqui, é permissão do Linux na pasta 'uploads'
					die("Erro: Não foi possível criar o diretório: " . $diretorioDestino);
				}
			}

			// 3. Move o arquivo
			if (move_uploaded_file($_FILES['foto']['tmp_name'], $diretorioDestino . $novoNome)) {
				$membroModel = new \App\Models\Membro();

				// Salva no banco e captura o nome da antiga para limpar o servidor
				$fotoAntiga = $membroModel->saveFoto($membroId, $novoNome);

				if (is_string($fotoAntiga) && !empty($fotoAntiga)) {
					$caminhoAntigo = $diretorioDestino . $fotoAntiga;
					if (file_exists($caminhoAntigo)) {
						unlink($caminhoAntigo);
					}
				}

				header("Location: " . url('membros') . "?sucesso=foto_atualizada");
				exit;
			} else {
				// Se cair aqui, o PHP não tem permissão de escrita no destino final
				die("Erro ao mover o arquivo para: " . $diretorioDestino);
			}
		}
		exit;
	}

    public function updateStatus()
	{
		$membroId = $_POST['membro_id'] ?? null;
		$novoStatus = $_POST['status'] ?? null;
		$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

		if ($membroId && $novoStatus && $igrejaId) {
			$membroModel = new \App\Models\Membro();

			if ($membroModel->updateStatus($membroId, $igrejaId, $novoStatus)) {
				header("Location: " . url('membros') . "?sucesso=status_atualizado");
			} else {
				header("Location: " . url('membros') . "?erro=falha_status");
			}
		} else {
			header("Location: " . url('membros') . "?erro=dados_invalidos");
		}
		exit;
	}

	public function addHistorico()
	{
		$membroId = $_POST['membro_id'] ?? null;
		$texto = $_POST['historico'] ?? null;

		if ($membroId && !empty($texto)) {
			$membroModel = new \App\Models\Membro();

			$data = [
				'membro_id' => $membroId,
				'texto'     => $texto, // O CKEditor envia HTML (ex: <p><strong>...</strong></p>)
				'data'      => date('Y-m-d H:i:s')
			];

			if ($membroModel->insertHistorico($data)) {
				header("Location: " . url('membros') . "?sucesso=historico_salvo");
			} else {
				header("Location: " . url('membros') . "?erro=falha_historico");
			}
		} else {
			header("Location: " . url('membros') . "?erro=texto_vazio");
		}
		exit;
    }

	public function edit($id)
	{
		// Pegamos o ID da igreja da sessão, igual você fez no index()
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// Chamamos o Model passando os dois parâmetros: o ID do membro e o ID da igreja
		$membro = $this->model->getById($id, $idIgreja);

		if (!$membro) {
			// Se não encontrar o membro (ou ele não pertencer a essa igreja), volta
			header('Location: ' . url('membros'));
			exit;
		}

		// Carrega a view de cadastro passando os dados do membro
		// A view usará a variável $membro para preencher os campos automaticamente
		$this->view('membros/cadastrar', [
			'membro' => $membro
		]);
	}

	public function update($id)
	{
		// 1. Verifica se os dados vieram via POST
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Location: ' . url('membros'));
			exit;
		}

		$idIgreja = $_SESSION['usuario_igreja_id'];

		// 2. Coleta os dados do formulário
		$dados = [
			'nome'            => $_POST['nome'],
			'genero'          => $_POST['genero'] ?? null,
			'email'           => $_POST['email'],
			'telefone'        => $_POST['telefone'],
			'data_nascimento' => $_POST['data_nascimento'],
			'data_batismo'    => $_POST['data_batismo']
		];

		// 3. Tenta atualizar no banco
		if ($this->model->update($id, $idIgreja, $dados)) {
			// Redireciona com flag de sucesso (você pode tratar isso na view depois)
			header('Location: ' . url('membros?sucesso=editado'));
		} else {
			// Se der erro, volta para a edição com mensagem de erro
			header('Location: ' . url('membros/edit/' . $id . '?erro=update_failed'));
		}
		exit;
	}

	public function get_dados_carteirinha($id) {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (ob_get_length()) ob_clean();
		header('Content-Type: application/json');

		try {
			$db = \App\Core\Database::getInstance();
			$membroModel = new \App\Models\Membro();

			$igrejaId = $_SESSION['igreja_id'] ?? null;

			if (!$igrejaId) {
				$stmt = $db->prepare("SELECT membro_igreja_id FROM membros WHERE membro_id = ?");
				$stmt->execute([$id]);
				$res = $stmt->fetch(\PDO::FETCH_ASSOC);
				$igrejaId = $res['membro_igreja_id'] ?? 0;
			}

			// 1. Buscar Dados do Membro
			$membro = $membroModel->getById($id, $igrejaId);

			if (!$membro) {
				echo json_encode(['error' => 'Membro não encontrado.']);
				exit;
			}

			// 2. Lógica das Datas
			$dataNasc = (!empty($membro['membro_data_nascimento']) && $membro['membro_data_nascimento'] != '0000-00-00')
						? date('d/m/Y', strtotime($membro['membro_data_nascimento']))
						: '--/--/----';

			$dataBat = (!empty($membro['membro_data_batismo']) && $membro['membro_data_batismo'] != '0000-00-00')
						? date('d/m/Y', strtotime($membro['membro_data_batismo']))
						: '--/--/----';

			// 3. Buscar Dados da Igreja + Nome do Pastor (JOIN com a tabela membros)
			$stmtIgreja = $db->prepare("
				SELECT i.*, p.membro_nome as pastor_nome
				FROM igrejas i
				LEFT JOIN membros p ON i.igreja_pastor_id = p.membro_id
				WHERE i.igreja_id = ?
			");
			$stmtIgreja->execute([$igrejaId]);
			$igrejaData = $stmtIgreja->fetch(\PDO::FETCH_ASSOC);

			// 4. Buscar Redes Sociais Ativas (Tabela Auxiliar)
			$stmtRedes = $db->prepare("
				SELECT rede_nome, rede_usuario
				FROM igrejas_redes_sociais
				WHERE rede_igreja_id = ? AND rede_status = 'ativo'
			");
			$stmtRedes->execute([$igrejaId]);
			$redes = $stmtRedes->fetchAll(\PDO::FETCH_ASSOC);

			// Montar string de contatos (Ex: @igreja | (11) 9999-9999)
			$contatosArray = [];
			foreach ($redes as $r) {
				$contatosArray[] = $r['rede_usuario'];
			}
			$contatosStr = implode(' | ', $contatosArray);

			// 5. Buscar Foto e Cargos do Membro
			$stmtFoto = $db->prepare("SELECT membro_foto_arquivo FROM membros_fotos WHERE membro_foto_membro_id = ?");
			$stmtFoto->execute([$id]);
			$foto = $stmtFoto->fetch(\PDO::FETCH_ASSOC);
			$cargosStr = $membroModel->getCargosNomesByMembro($id);

			// 6. Montar JSON Final
			$json = [
				'membro' => [
					'id'              => $membro['membro_id'],
					'igreja_id'       => $igrejaId,
					'registro'        => $membro['membro_registro_interno'] ?? '000',
					'nome'            => mb_strtoupper($membro['membro_nome'] ?? ''),
					'data_nascimento' => $dataNasc,
					'data_batismo'    => $dataBat,
					'foto'            => $foto ? $foto['membro_foto_arquivo'] : null,
					'cargo'           => mb_strtoupper($cargosStr ?: 'MEMBRO')
				],
				'igreja' => [
					'nome'     => mb_strtoupper($igrejaData['igreja_nome'] ?? ''),
					'endereco' => $igrejaData['igreja_endereco'] ?? '',
					'pastor'   => mb_strtoupper($igrejaData['pastor_nome'] ?? 'NÃO INFORMADO'),
					'contatos' => $contatosStr
				]
			];

			echo json_encode($json);

		} catch (\Exception $e) {
			echo json_encode(['error' => 'Erro interno: ' . $e->getMessage()]);
		}
		exit;
	}

	public function get_info($id) {
		// Busca no banco: SELECT endereco, numero, bairro FROM membros WHERE id = $id
		$membro = $this->membroModel->find($id);

		$dados = [
			'endereco' => $membro['logradouro'] . ", " . $membro['numero'] . " - " . $membro['bairro']
		];

		echo json_encode($dados);
		exit;
	}

	// No MembrosController.php
	public function dadosCertificado($id)
	{
		// Adicione isso no início para testar se a rota chega aqui
		header('Content-Type: application/json');

		$idIgreja = $_SESSION['usuario_igreja_id'];
		$dados = $this->model->getDadosCertificado($id, $idIgreja);

		if (!$dados) {
			echo json_encode(['success' => false, 'message' => 'Membro não encontrado']);
			exit;
		}

		$dados['data_batismo_formatada'] = date('d/m/Y', strtotime($dados['membro_data_batismo']));
		$dados['data_hoje'] = date('d/m/Y'); // Simplificado para teste

		echo json_encode(['success' => true, 'dados' => $dados]);
		exit; // Garante que nenhum HTML extra do sistema seja enviado
	}

	private function getMesPt($n) {
		$meses = [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'];
		return $meses[$n];
	}

}
