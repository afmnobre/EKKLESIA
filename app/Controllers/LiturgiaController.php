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

    // BIBLIA API TESTE
	public function buscarTexto() {
		// Mantemos o silêncio de erros para garantir o JSON limpo
		ini_set('display_errors', 0);
		error_reporting(E_ALL);

		try {
			$refInput = $_GET['ref'] ?? '';
			if (!$refInput) throw new Exception('Sem referência fornecida.');

			// 1. Regex para decompor a referência
			$regex = '/^([\d\s]*[a-zÀ-ÿ]+)\s+(\d+)(?:[:\s]+(\d+)(?:-(\d+))?)?$/iu';
			if (!preg_match($regex, trim($refInput), $matches)) {
				throw new Exception('Formato inválido. Use Ex: João 3:16');
			}

			$livroDigitado = mb_strtolower(trim($matches[1]), 'UTF-8');
			$capitulo      = (int)$matches[2];
			$vInicio       = isset($matches[3]) ? (int)$matches[3] : null;
			$vFim          = isset($matches[4]) ? (int)$matches[4] : $vInicio;

			// 2. Mapeamento de siglas (Mantendo o seu dicionário ARA)
			$mapeamento = [
				'gn' => 'Gênesis', 'genesis' => 'Gênesis', 'gênesis' => 'Gênesis',
				'ex' => 'Êxodo', 'exodo' => 'Êxodo', 'êxodo' => 'Êxodo',
				'lv' => 'Levítico', 'levitico' => 'Levítico', 'levítico' => 'Levítico',
				'nm' => 'Números', 'numeros' => 'Números', 'números' => 'Números',
				'dt' => 'Deuteronômio', 'deuteronomio' => 'Deuteronômio', 'deuteronômio' => 'Deuteronômio',
				'js' => 'Josué', 'josue' => 'Josué', 'josué' => 'Josué',
				'jz' => 'Juízes', 'juizes' => 'Juízes', 'juízes' => 'Juízes',
				'rt' => 'Rute', 'rute' => 'Rute',
				'1sm' => '1 Samuel', '1 samuel' => '1 Samuel',
				'2sm' => '2 Samuel', '2 samuel' => '2 Samuel',
				'1rs' => '1 Reis', '1 reis' => '1 Reis',
				'2rs' => '2 Reis', '2 reis' => '2 Reis',
				'1cr' => '1 Crônicas', '1 cronicas' => '1 Crônicas', '1 crônicas' => '1 Crônicas',
				'2cr' => '2 Crônicas', '2 cronicas' => '2 Crônicas', '2 crônicas' => '2 Crônicas',
				'ez' => 'Esdras', 'esdras' => 'Esdras',
				'ne' => 'Neemias', 'neemias' => 'Neemias',
				'et' => 'Ester', 'ester' => 'Ester',
				'job' => 'Jó', 'jó' => 'Jó',
				'sl' => 'Salmos', 'salmos' => 'Salmos',
				'pv' => 'Provérbios', 'proverbios' => 'Provérbios', 'provérbios' => 'Provérbios',
				'ec' => 'Eclesiastes', 'eclesiastes' => 'Eclesiastes',
				'ct' => 'Cantares', 'cantares' => 'Cantares', 'cântico dos cânticos' => 'Cantares',
				'is' => 'Isaías', 'isaias' => 'Isaías', 'isaías' => 'Isaías',
				'jr' => 'Jeremias', 'jeremias' => 'Jeremias',
				'lm' => 'Lamentações', 'lamentacoes' => 'Lamentações', 'lamentações' => 'Lamentações',
				'ezk' => 'Ezequiel', 'ezequiel' => 'Ezequiel',
				'dn' => 'Daniel', 'daniel' => 'Daniel',
				'os' => 'Oseias', 'oseias' => 'Oseias', 'oséias' => 'Oseias',
				'jl' => 'Joel', 'joel' => 'Joel',
				'am' => 'Amós', 'amos' => 'Amós', 'amós' => 'Amós',
				'ob' => 'Obadias', 'obadias' => 'Obadias',
				'jn' => 'Jonas', 'jonas' => 'Jonas',
				'mi' => 'Miqueias', 'miqueias' => 'Miqueias', 'miqueías' => 'Miqueias',
				'na' => 'Naum', 'naum' => 'Naum',
				'hc' => 'Habacuque', 'habacuque' => 'Habacuque',
				'sf' => 'Sofonias', 'sofonias' => 'Sofonias',
				'ag' => 'Ageu', 'ageu' => 'Ageu',
				'zc' => 'Zacarias', 'zacarias' => 'Zacarias',
				'ml' => 'Malaquias', 'malaquias' => 'Malaquias',
				'mt' => 'Mateus', 'mateus' => 'Mateus',
                'mc' => 'Marcos', 'marcos' => 'Marcos',
                'jo' => 'João', 'joão' => 'João', 'joao' => 'João',
				'lc' => 'Lucas', 'lucas' => 'Lucas',
				'at' => 'Atos', 'atos' => 'Atos',
				'rm' => 'Romanos', 'romanos' => 'Romanos',
				'1co' => '1 Coríntios', '1 corintios' => '1 Coríntios', '1 coríntios' => '1 Coríntios',
				'2co' => '2 Coríntios', '2 corintios' => '2 Coríntios', '2 coríntios' => '2 Coríntios',
				'gl' => 'Gálatas', 'galatas' => 'Gálatas', 'gálatas' => 'Gálatas',
				'ef' => 'Efésios', 'efesios' => 'Efésios', 'efésios' => 'Efésios',
				'fp' => 'Filipenses', 'filipenses' => 'Filipenses',
				'cl' => 'Colossenses', 'colossenses' => 'Colossenses',
				'1ts' => '1 Tessalonicenses', '1 tessalonicenses' => '1 Tessalonicenses',
				'2ts' => '2 Tessalonicenses', '2 tessalonicenses' => '2 Tessalonicenses',
				'1ti' => '1 Timóteo', '1 timoteo' => '1 Timóteo', '1 timóteo' => '1 Timóteo',
				'2ti' => '2 Timóteo', '2 timoteo' => '2 Timóteo', '2 timóteo' => '2 Timóteo',
				'tt' => 'Tito', 'tito' => 'Tito',
				'fm' => 'Filemon', 'filemon' => 'Filemon',
				'hb' => 'Hebreus', 'hebreus' => 'Hebreus',
				'tg' => 'Tiago', 'tiago' => 'Tiago',
				'1pe' => '1 Pedro', '1 pedro' => '1 Pedro',
				'2pe' => '2 Pedro', '2 pedro' => '2 Pedro',
				'1jn' => '1 João', '1 joao' => '1 João', '1 joão' => '1 João',
				'2jn' => '2 João', '2 joao' => '2 João', '2 joão' => '2 João',
				'3jn' => '3 João', '3 joao' => '3 João', '3 joão' => '3 João',
				'jd' => 'Judas', 'judas' => 'Judas',
				'ap' => 'Apocalipse', 'apocalipse' => 'Apocalipse'
			];

			$livroReal = $mapeamento[$livroDigitado] ?? null;
			if (!$livroReal) throw new Exception('Livro não reconhecido.');

			// 3. Chamada via MODEL (Seguindo o padrão do seu método salvar)
			// Você deve criar o método 'getVersiculos' no seu LiturgiaModel
			$versiculos = $this->model->getVersiculos($livroReal, $capitulo, $vInicio, $vFim);

			header('Content-Type: application/json');
			echo json_encode(['success' => true, 'versiculos' => $versiculos]);

		} catch (Exception $e) {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'error' => $e->getMessage()]);
		}
		exit;
	}

}
