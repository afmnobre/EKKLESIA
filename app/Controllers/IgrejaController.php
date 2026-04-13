<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Igreja;

class IgrejaController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Igreja();
    }

    // LISTAR
	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Dados principais vindos do Model
		$igreja = $this->model->getByIgreja($igrejaId);
		$redes = $this->model->getRedesSociais($igrejaId);
		$programacoes = $this->model->getProgramacoes($igrejaId);

		// NOVA CHAMADA: Liderança vindo do Model
		$lideranca = $this->model->getLideranca($igrejaId);

		// Mantemos a busca de membros e pastor aqui por enquanto (ou pode mover também se preferir)
		$db = \App\Core\Database::getInstance();
		$stMembros = $db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
		$stMembros->execute([$igrejaId]);
		$membros = $stMembros->fetchAll(\PDO::FETCH_ASSOC);

		$pastorNome = "Não definido";
		if (!empty($igreja['igreja_pastor_id'])) {
			$stP = $db->prepare("SELECT membro_nome FROM membros WHERE membro_id = ?");
			$stP->execute([$igreja['igreja_pastor_id']]);
			$p = $stP->fetch();
			$pastorNome = $p['membro_nome'] ?? "Não definido";
		}

		$this->view('igreja/index', [
			'igreja'       => $igreja,
			'redes'        => $redes,
			'membros'      => $membros,
			'pastorNome'   => $pastorNome,
			'programacoes' => $programacoes,
			'lideranca'    => $lideranca // Objeto limpo enviado para a View
		]);
	}

    // EDITAR FORM
    public function editar()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $igreja = $this->model->getByIgreja($igrejaId);

        $this->view('igreja/editar', [
            'igreja' => $igreja
        ]);
    }

    // SALVAR
    public function atualizar() // Removi o $id = 1 por segurança
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // Validação básica
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . url('igreja'));
            exit;
        }

        $dados = [
            'nome'     => $_POST['nome'] ?? '',
            'cnpj'     => $_POST['cnpj'] ?? '',
            'endereco' => $_POST['endereco'] ?? ''
        ];

        if ($this->model->update($igrejaId, $dados)) {
            // Você pode adicionar uma mensagem de sucesso na sessão aqui
            header("Location: " . url('igreja?sucesso=1'));
        } else {
            header("Location: " . url('igreja/editar?erro=1'));
        }
        exit;
    }

	public function salvarRedeSocial()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = [
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'nome'      => $_POST['rede_nome'],
				'usuario'   => $_POST['rede_usuario'],
				'status'    => $_POST['rede_status'] ?? 'ativo'
			];

			$this->model->addRedeSocial($dados);
			header("Location: " . url('igreja?sucesso_rede=1'));
			exit;
		}
	}

	public function excluirRedeSocial($id)
	{
		$this->model->deleteRedeSocial($id, $_SESSION['usuario_igreja_id']);
		header("Location: " . url('igreja?excluido=1'));
		exit;
	}

	public function salvarPastor()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];
			$membroId = $_POST['pastor_id'] ?: null;

			if ($this->model->updatePastor($igrejaId, $membroId)) {
				header("Location: " . url('igreja?sucesso_pastor=1'));
			} else {
				header("Location: " . url('igreja?erro_pastor=1'));
			}
			exit;
		}
	}

	public function uploadLogo()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['igreja_logo'])) {
			$file = $_FILES['igreja_logo'];
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$allowed = ['jpg', 'jpeg', 'png', 'webp'];

			if (!in_array(strtolower($ext), $allowed)) {
				header("Location: " . url('igreja?erro_extensao=1'));
				exit;
			}

			// Caminho do diretório: assets/uploads/{id}/logo/
			$dir = "assets/uploads/{$igrejaId}/logo/";
			if (!is_dir($dir)) {
				mkdir($dir, 0755, true);
			}

			// 1. Buscar logo antigo para excluir
			$igrejaAtual = $this->model->getByIgreja($igrejaId);
			if (!empty($igrejaAtual['igreja_logo'])) {
				$oldFile = $dir . $igrejaAtual['igreja_logo'];
				if (file_exists($oldFile)) {
					unlink($oldFile); // Deleta o arquivo antigo
				}
			}

			// 2. Novo nome de arquivo (evita cache do navegador com timestamp)
			$novoNome = "logo_" . time() . "." . $ext;
			$destino = $dir . $novoNome;

			if (move_uploaded_file($file['tmp_name'], $destino)) {
				$this->model->updateLogo($igrejaId, $novoNome);
				header("Location: " . url('igreja?sucesso_logo=1'));
			} else {
				header("Location: " . url('igreja?erro_upload=1'));
			}
			exit;
		}
	}

    // PROGRAMAÇÔES DA IGREJA

	public function salvarProgramacao() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = [
				'igreja_id'   => $_SESSION['usuario_igreja_id'],
				'titulo'      => $_POST['prog_titulo'],
				'dia_semana'  => $_POST['prog_dia'],
				'hora'        => $_POST['prog_hora'],
				'recorrencia' => $_POST['prog_recorrencia'] ?? 0,
				'is_ceia'     => isset($_POST['prog_is_ceia']) ? 1 : 0,
				'is_externo'  => isset($_POST['prog_is_externo']) ? 1 : 0 // NOVO CAMPO
			];
			$this->model->addProgramacao($dados);
			header("Location: " . url('igreja?sucesso_prog=1'));
		}
	}

	public function updateLocalProgramacao() {
		$id = $_POST['programacao_id'];
		$local = $_POST['membro_info'];
		$this->model->updateLocal($id, $local);
		header("Location: " . url('igreja?sucesso_local=1'));
	}

	// Para o AJAX do Choices.js
	public function buscarMembrosJson() {
		$membros = $this->model->getMembrosEnderecos($_SESSION['usuario_igreja_id']);
		echo json_encode($membros);
	}

    public function excluirProgramacao($id)
    {
        $this->model->deleteProgramacao($id, $_SESSION['usuario_igreja_id']);
        header("Location: " . url('igreja?excluido_prog=1'));
        exit;
    }

	public function salvarEscalaLocal() {
		$progId   = $_POST['programacao_id'] ?? null;
		$membroId = $_POST['membro_id'] ?? null;
		$data     = $_POST['data_evento'] ?? null;
		$endereco = $_POST['local_nome_endereco'] ?? null;

		// FORÇAR NULL: Se o ID for '0', vazio ou não numérico, tratamos como nulo (Sede)
		// Caso contrário, garantimos que seja um Inteiro
		if ($membroId === '0' || empty($membroId)) {
			$membroId = null;
		} else {
			$membroId = (int)$membroId;
		}

		if (!$progId || !$data || !$endereco) {
			echo json_encode(['success' => false, 'message' => 'Campos obrigatórios ausentes']);
			return;
		}

		$dados = [
			'programacao_id'      => $progId,
			'membro_id'           => $membroId,
			'data_evento'         => $data,
			'local_nome_endereco' => $endereco
		];

		$model = new \App\Models\Igreja();
		if ($model->upsertEscala($dados)) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false]);
		}
	}

	public function listarEscalas($progId) {
		$escalas = $this->model->getEscalas($progId);
		// Formata a data para PT-BR antes de enviar para o JS
		foreach($escalas as &$e) {
			$e['data_formatada'] = date('d/m/Y', strtotime($e['data_evento']));
		}
		echo json_encode($escalas);
	}

	public function excluirEscala($id = null) {
		if (!$id) {
			echo json_encode(['status' => 'error', 'message' => 'ID não fornecido']);
			return;
		}

		$resultado = $this->model->deleteEscala($id);

		if ($resultado) {
			echo json_encode(['status' => 'ok']);
		} else {
			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode(['status' => 'error']);
		}
	}

	/**
	 * Exibe a página de acessos externos (QR Codes) em modo RawView
	 * Rota sugerida: /Igreja/acessos
	 */
	public function acessos()
	{
		// Recuperamos o ID da igreja da sessão
		$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

		if (!$igrejaId) {
			die("Erro: Sessão de igreja não identificada.");
		}

		// Buscamos os dados básicos da igreja para exibir o nome na página, se desejar
		$igreja = $this->model->getByIgreja($igrejaId);

		// Definimos os links de acesso baseados na sua estrutura
		$canais = [
			[
				'titulo' => 'Portal do Membro',
				'desc'   => 'Consulta de dados, dízimos e relatórios',
				'url'    => full_url('PortalMembro/login/' . $igrejaId),
				'icon'   => 'bi-person-badge-fill',
				'bg'     => 'bg-primary'
			],
			[
				'titulo' => 'Cadastro Online',
				'desc'   => 'Pré-cadastro de novos membros',
				'url'    => full_url('PortalMembro/cadastro/' . $igrejaId),
				'icon'   => 'bi-person-plus-fill',
				'bg'     => 'bg-success'
			],
			[
				'titulo' => 'Líder de Sociedade',
				'desc'   => 'Painel de gestão de departamentos',
				'url'    => full_url('sociedadeLider/login'),
				'icon'   => 'bi-shield-lock-fill',
				'bg'     => 'bg-dark'
			],
			[
				'titulo' => 'Escola Dominical',
				'desc'   => 'Portal do Professor e Chamadas',
				'url'    => full_url('professor/login'),
				'icon'   => 'bi-journal-check',
				'bg'     => 'bg-info'
			]
		];

		// Chamamos a view passando o parâmetro true para o RawView (limpa o layout)
		$this->rawview('igreja/acessos_externos', [
			'igreja' => $igreja,
			'canais' => $canais
		], true);
	}


}

