<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Liturgia;
use App\Models\Membro;

class LiturgiaController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Liturgia();
    }

    /**
     * Listagem de Liturgias (Histórico por data)
     */
    public function index()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // Busca todas as liturgias cadastradas
        $liturgias = $this->model->getAllByIgreja($igrejaId);

        $this->view('liturgias/index', [
            'liturgias' => $liturgias
        ]);
    }

    /**
     * Cadastro de Nova Liturgia
     */
    public function novo()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $membroModel = new Membro();
        $membros = $membroModel->getAll($igrejaId);

        // Abre o formulário vazio para nova liturgia
        $this->view('liturgias/cadastro_liturgia', [
            'liturgia' => null,
            'membros'  => $membros
        ]);
    }

    /**
     * Editar Liturgia Existente
     */
    public function editar($id = null)
    {
        // Captura o ID da URL se não vier por argumento (ex: /liturgia/editar/3)
        if (empty($id)) {
            $partesUrl = explode('/', $_SERVER['REQUEST_URI'] ?? '');
            $id = end($partesUrl);
        }

        $id = (int)$id;
        $igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

        if (!$igrejaId) {
            die("Erro: Sessão de igreja não encontrada. Verifique o login.");
        }

        // O model getById já deve retornar o cabeçalho e o array ['itens']
        $liturgia = $this->model->getById($id, $igrejaId);

        if (!$liturgia) {
            header("Location: " . url('liturgia/index') . "?erro=nao_encontrada");
            exit;
        }

        $membroModel = new Membro();
        $membros = $membroModel->getAll($igrejaId);

        // Chama a mesma view de cadastro, mas com os dados preenchidos
        $this->view('liturgias/cadastro_liturgia', [
            'liturgia' => $liturgia,
            'membros'  => $membros
        ]);
    }

    /**
     * Processar o salvamento (Cabeçalho + Itens Dinâmicos)
     * Trata tanto INSERT quanto UPDATE (via salvarCompleta no Model)
     */
	public function salvar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];

			// Recuperar nomes se for ID da lista (para não salvar 'vazio' no campo nome)
			$pregador_nome = $_POST['pregador_nome_manual'] ?? '';
			$dirigente_nome = $_POST['dirigente_nome_manual'] ?? '';

			// Se selecionou da lista, precisamos buscar o nome do membro para salvar no campo _nome
			if (!empty($_POST['pregador_id']) && $_POST['pregador_id'] !== 'outro') {
				$m = $this->model->getMembroNome($_POST['pregador_id']); // Crie esse help no model se necessário
				$pregador_nome = $m['membro_nome'] ?? '';
			}

			if (!empty($_POST['dirigente_id']) && $_POST['dirigente_id'] !== 'outro') {
				$m = $this->model->getMembroNome($_POST['dirigente_id']);
				$dirigente_nome = $m['membro_nome'] ?? '';
			}

			// 1. Dados do Cabeçalho - FOCO NO TEMA
			$dadosCulto = [
				'id'             => !empty($_POST['igreja_liturgia_id']) ? $_POST['igreja_liturgia_id'] : null,
				'igreja_id'      => $igrejaId,
				'data'           => $_POST['igreja_liturgia_data'],
				'tema'           => $_POST['igreja_liturgia_tema'], // Removido o ?? '', deixe vir direto
				'pregador_id'    => ($_POST['pregador_id'] !== 'outro' && !empty($_POST['pregador_id'])) ? $_POST['pregador_id'] : null,
				'pregador_nome'  => $pregador_nome,
				'dirigente_id'   => ($_POST['dirigente_id'] !== 'outro' && !empty($_POST['dirigente_id'])) ? $_POST['dirigente_id'] : null,
				'dirigente_nome' => $dirigente_nome
			];

			// 2. Coletar Itens Dinâmicos vindos do JS
			$itensRaw = $_POST['itens'] ?? [];
			$itensProcessados = [];

			foreach ($itensRaw as $item) {
				$tipo = !empty($item['tipo']) ? $item['tipo'] : 'texto';
				$descricao = !empty($item['descricao']) ? $item['descricao'] : '';

				if (!empty($descricao)) {
					$itensProcessados[] = [
						'tipo'         => $tipo,
						'descricao'    => $descricao,
						'referencia'   => $item['referencia'] ?? '',
						// CAPTURA O CONTEÚDO LONGO DA API AQUI:
						'conteudo_api' => $item['conteudo_api'] ?? null
					];
				}
			}

			// 3. Persistir no Banco
			if ($this->model->salvarCompleta($dadosCulto, $itensProcessados)) {
				header('Location: ' . url('liturgia/index'));
				exit;
			} else {
				die("Erro crítico ao salvar a liturgia. Verifique os logs do sistema.");
			}
		}
	}

    /**
     * Excluir Liturgia Completa
     */
	public function excluir($id)
    {
        // Segurança: Pegar ID da igreja da sessão
        $igrejaId = $_SESSION['usuario_igreja_id'];

        if ($this->model->excluir($id, $igrejaId)) {
            header("Location: " . url('liturgia?sucesso=excluido'));
        } else {
            header("Location: " . url('liturgia?erro=erro_exclusao'));
        }
        exit;
    }
    /**
     * Visualização para Impressão
     */
	public function imprimir($id)
	{
		// Verifica a sessão conforme seu padrão (Admin/Membro)
		$igrejaId = $_SESSION['usuario_igreja_id'] ?? $_SESSION['membro_igreja_id'];

		$liturgia = $this->model->getById($id, $igrejaId);
		if (!$liturgia) die("Liturgia não encontrada.");

		// Busca os itens
		$itens = $liturgia['itens'] ?? $this->model->getItensByLiturgia($id);

		// --- INSERÇÃO DA LÓGICA DE HINOS ---
		// Processamos os itens para que as letras dos hinos sejam carregadas
		$itens = $this->model->processarItensHinos($itens);
		// --- FIM DA INSERÇÃO ---

		$igrejaModel = new \App\Models\Igreja();
		$igreja = $igrejaModel->getById($igrejaId);

		$this->rawview('liturgias/imprimir_liturgia', [
			'liturgia' => $liturgia,
			'itens'    => $itens, // Agora os itens já possuem hino_titulo e hino_letra
			'igreja'   => $igreja
		]);
	}
}
