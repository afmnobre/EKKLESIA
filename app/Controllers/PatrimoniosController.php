<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Patrimonio;
use App\Core\Utils;

class PatrimoniosController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Patrimonio();
    }

    public function index()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // 1. Busca os bens (já com join de categoria)
        $bens = $this->model->getAll($igrejaId);

        // 2. Busca locais e categorias para filtros/formulários
        $locais = $this->model->getLocais($igrejaId);
        $categorias = $this->model->getCategorias($igrejaId);

        // 3. Busca o nome da igreja
        $dadosIgreja = $this->model->getIgrejaDados($igrejaId);

        // 4. Passa tudo para a view
        $this->view('patrimonios/index', [
            'bens' => $bens,
            'locais' => $locais,
            'categorias' => $categorias, // Nova variável na view
            'nomeIgreja' => $dadosIgreja['igreja_nome'] ?? 'IGREJA'
        ]);
    }

	public function novo()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Você provavelmente já tinha essa linha:
		$locais = $this->model->getLocais($igrejaId);

		// ADICIONE ESTA LINHA PARA CARREGAR AS CATEGORIAS:
		$categorias = $this->model->getCategorias($igrejaId);

		// Passe a variável 'categorias' para a view
		$this->view('patrimonios/cadastrar', [
			'locais' => $locais,
			'categorias' => $categorias // <-- Importante passar aqui
		]);
	}	public function locais() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$locais = $this->model->getLocais($igrejaId);

		$this->view('patrimonios/locais', [
			'locais' => $locais
		]);
	}

	public function salvarLocal()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id = $_POST['patrimonio_local_id'] ?? null;
			$dados = [
				'nome' => $_POST['patrimonio_local_nome'],
				'igreja_id' => $_SESSION['usuario_igreja_id']
			];

			if ($id) {
				// Se tem ID, chama o editar
				$this->model->atualizarLocal($id, $dados);
				$msg = 'Local atualizado com sucesso!';
			} else {
				// Se não tem ID, chama o salvar
				$this->model->salvarLocal($dados);
				$msg = 'Local cadastrado com sucesso!';
			}

			header('Location: ' . url('patrimonios/locais') . '?sucesso=' . urlencode($msg));
		}
	}

	public function excluirLocal($id)
	{
		// Pegamos o ID da igreja da sessão por segurança
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Chamamos o método do Model que você já criou
		if ($this->model->excluirLocal($id, $igrejaId)) {
			// Redireciona de volta para a listagem no plural
			header('Location: ' . url('patrimonios/locais'));
			exit;
		} else {
			// Caso ocorra algum erro (ex: banco fora do ar ou restrição de chave estrangeira)
			// Você pode tratar com uma mensagem de erro aqui
			header('Location: ' . url('patrimonios/locais'));
			exit;
		}
	}

	public function salvar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];

			// Geração automática do código
			$ultimoId = $this->model->getLastId();
			$proximoId = $ultimoId + 1;
			$codigoPatrimonio = "PAT-" . date('Y') . "-" . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

			// 1. Prepara os dados do Bem
			$dadosBem = [
				'igreja_id'      => $igrejaId,
				'codigo'         => $codigoPatrimonio,
				'nome'           => $_POST['patrimonio_bem_nome'],
				'descricao'      => $_POST['patrimonio_bem_descricao'],
				'data_aquisicao' => $_POST['patrimonio_bem_data_aquisicao'] ?: null,
				'valor'          => $_POST['patrimonio_bem_valor'] ?: 0,
				'status'         => $_POST['patrimonio_bem_status'],
				'local_id'       => $_POST['patrimonio_bem_local_id'],
				'categoria_id'   => $_POST['patrimonio_bem_categoria_id'] // ADICIONADO
			];

			// 2. Salva o Bem
			$bemId = $this->model->salvarBem($dadosBem);

			if ($bemId) {
				// 3. Registra a Movimentação Inicial
				$dadosMovimentacao = [
					'bem_id'        => $bemId,
					'igreja_id'     => $igrejaId,
					'tipo'          => 'entrada',
					'local_destino' => $_POST['patrimonio_bem_local_id'],
					'data'          => date('Y-m-d H:i:s'),
					'observacao'    => "Entrada inicial. Categoria definida. Código: {$codigoPatrimonio}"
				];

				$this->model->registrarMovimentacao($dadosMovimentacao);

				header('Location: ' . url('patrimonios') . '?sucesso=cadastrado');
				exit;
			}
		}
	}

	public function uploadFoto()
	{
		$this->processarUpload('foto');
	}

	public function uploadDocumento()
	{
		$this->processarUpload('doc');
	}

	private function processarUpload($tipo)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['arquivo'])) {
			$idIgreja = $_SESSION['usuario_igreja_id'];
			$patrimonioId = $_POST['patrimonio_id'];

			$subPasta = ($tipo === 'foto') ? 'fotos' : 'documentos';
			$raizProjeto = dirname(__DIR__, 2);
			$diretorioDestino = $raizProjeto . "/public/assets/uploads/{$idIgreja}/patrimonios/{$patrimonioId}/{$subPasta}/";

			if (!is_dir($diretorioDestino)) {
				if (!mkdir($diretorioDestino, 0777, true)) {
					die("Erro: Não foi possível criar o diretório.");
				}
			}

			$arquivos = $_FILES['arquivo'];
			$total = count($arquivos['name']);

			for ($i = 0; $i < $total; $i++) {
				if ($arquivos['error'][$i] === UPLOAD_ERR_OK) {

					$extensaoOriginal = strtolower(pathinfo($arquivos['name'][$i], PATHINFO_EXTENSION));

					// Se for foto, forçamos .jpg devido ao Utils::otimizarImagem
					// Se for documento, mantemos a extensão original
					$extensaoFinal = ($tipo === 'foto') ? 'jpg' : $extensaoOriginal;

					$novoNome = $tipo . "_" . time() . "_" . rand(1000, 9999) . "." . $extensaoFinal;
					$caminhoCompleto = $diretorioDestino . $novoNome;

					if (move_uploaded_file($arquivos['tmp_name'][$i], $caminhoCompleto)) {

						if ($tipo === 'foto') {
							// Otimiza a imagem (limite de 800px para patrimônio é ideal para carregar rápido no celular)
							Utils::otimizarImagem($caminhoCompleto, $caminhoCompleto, 800, 75);
							$this->model->inserirFoto($patrimonioId, $novoNome);
						} else {
							// Apenas registra o documento no banco (PDF, DOCX, etc)
							$this->model->inserirDocumento($patrimonioId, $novoNome);
						}
					}
				}
			}

			header('Location: ' . url('patrimonios') . '?sucesso=upload_concluido');
			exit;
        }

    }

	public function detalhes($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$bem = $this->model->getById($id, $igrejaId);

		// Busca a categoria específica deste bem
		$categoria = null;
		if (!empty($bem['patrimonio_bem_categoria_id'])) {
			$categoria = $this->model->getCategoriaById($bem['patrimonio_bem_categoria_id'], $igrejaId);
		}

		$fotos = $this->model->getFotos($id);
		$documentos = $this->model->getDocumentos($id);
		$movimentacoes = $this->model->getMovimentacoes($id, $igrejaId);
		$locais = $this->model->getLocais($igrejaId);

		$this->view('patrimonios/detalhes', [
			'bem'           => $bem,
			'categoria'     => $categoria, // Passando a categoria encontrada
			'fotos'         => $fotos,
			'documentos'    => $documentos,
			'movimentacoes' => $movimentacoes,
			'locais'        => $locais
		]);
	}

	public function editar($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Busca o bem específico
        $bem = $this->model->getById($id, $igrejaId);
        $categorias = $this->model->getCategorias($igrejaId);

		if (!$bem) {
			header('Location: ' . url('patrimonios'));
			exit;
		}

		// Precisamos dos locais para o select
		$locais = $this->model->getLocais($igrejaId);

		$this->view('patrimonios/editar', [
			'bem' => $bem,
            'locais' => $locais,
            'categorias' => $categorias
		]);
	}

	public function atualizar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];

			$dados = [
				'id'             => $_POST['patrimonio_bem_id'],
				'igreja_id'      => $igrejaId,
				'nome'           => $_POST['patrimonio_bem_nome'],
				'descricao'      => $_POST['patrimonio_bem_descricao'],
				'data_aquisicao' => $_POST['patrimonio_bem_data_aquisicao'] ?: null,
				'valor'          => $_POST['patrimonio_bem_valor'] ?: 0,
				'status'         => $_POST['patrimonio_bem_status'],
				'categoria_id'   => $_POST['patrimonio_bem_categoria_id'] // ADICIONADO
			];

			if ($this->model->atualizarBem($dados)) {
				header('Location: ' . url('patrimonios/detalhes/'.$dados['id']) . '?sucesso=atualizado');
			} else {
				header('Location: ' . url('patrimonios/editar/'.$dados['id']) . '?erro=erro_ao_atualizar');
			}
			exit;
		}
	}

	public function excluir($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($this->model->excluir($id, $igrejaId)) {
			// Redireciona com mensagem de sucesso (ajuste a URL se necessário)
			header('Location: ' . url('patrimonios') . '?sucesso=excluido');
		} else {
			// Redireciona com mensagem de erro
			header('Location: ' . url('patrimonios') . '?erro=excluir');
		}
		exit;
	}

	public function movimentar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];

			// Se o tipo for manutenção ou baixa, o destino pode ser o mesmo da origem
			$localDestino = $_POST['local_destino'] ?: $_POST['local_origem_hidden'];

			$dados = [
				'bem_id'        => $_POST['patrimonio_id'],
				'igreja_id'     => $igrejaId,
				'tipo'          => $_POST['tipo'],
				'local_origem'  => $_POST['local_origem_hidden'],
				'local_destino' => $localDestino,
				'data'          => $_POST['data'] . ' ' . date('H:i:s'),
				'observacao'    => $_POST['observacao']
			];

			if ($this->model->registrarMovimentacaoCompleta($dados)) {
				header('Location: ' . url('patrimonios') . '?sucesso=movimentado');
			} else {
				header('Location: ' . url('patrimonios') . '?erro=movimentar');
			}
			exit;
		}
	}

	public function dashboard()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
        $metrics = $this->model->getMetrics($igrejaId);
        $metrics['por_categoria'] = $this->model->getMetricsPorCategoria($igrejaId);

		// Cálculo da Taxa de Manutenção (Métrica 4)
		$total = $metrics['geral']['total_itens'] ?: 1;
		$manutencao = 0;
		foreach($metrics['por_status'] as $s) {
			if($s['status'] == 'manutencao') $manutencao = $s['qtd'];
		}
		$metrics['taxa_manutencao'] = ($manutencao / $total) * 100;

		$this->view('patrimonios/dashboard', ['metrics' => $metrics]);
	}

	public function imprimirEtiquetas($localId)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$bens = $this->model->getBensPorLocal($localId, $igrejaId);

		if (empty($bens)) {
			header('Location: ' . url('patrimonios/locais') . '?erro=' . urlencode('Não há bens ativos neste local.'));
			exit();
		}

		// Buscamos o nome do local para o título da página
		$localNome = $bens[0]['patrimonio_local_nome'];

		// IMPORTANTE: Carregar APENAS o arquivo da view, sem o template do sistema
		// Se o seu framework permitir, use require_once ou um método de renderização limpa
		require_once '../app/Views/paginas/patrimonios/imprimir_etiquetas.php';
		exit(); // Encerra aqui para não carregar o footer do sistema por acidente
	}

	public function exportarExcelLocal($localId)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$bens = $this->model->getBensPorLocal($localId, $igrejaId);

		if (empty($bens)) {
			header('Location: ' . url('patrimonios/locais') . '?erro=' . urlencode('Não há bens para exportar.'));
			exit();
		}

		$localNome = $bens[0]['patrimonio_local_nome'];

		// CORREÇÃO AQUI: Chamada usando $this-> e o método adicionado abaixo
		$slug = $this->slugify($localNome);
		$filename = 'conferencia_patrimonio_' . $slug . '_' . date('Ymd_Hi') . '.xls';

		// Cabeçalhos para forçar o download do Excel
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");

		// Layout do Excel com cabeçalho profissional
		echo "<table border='1'>";
		echo "<tr><th colspan='6' style='background-color:#1c452e; color:#fff; font-size:16px;'>IPB - EKKLESIA - RELATÓRIO DE CONFERÊNCIA DE PATRIMÔNIO</th></tr>";
		echo "<tr><th colspan='6' style='background-color:#f2f2f2; font-size:14px;'>LOCAL: " . strtoupper($localNome) . " - Data: " . date('d/m/Y H:i') . "</th></tr>";
		echo "<tr><th>[ ] Conf.</th><th>ID</th><th>Nome do Bem</th><th>Descrição</th><th>Data Aquisição</th><th>Valor R$</th></tr>";

		// ... dentro do foreach do método exportarExcelLocal ...
		foreach ($bens as $b) {
			echo "<tr>";
			echo "<td>[ ]</td>";
			// Alterado de patrimonio_bem_id para patrimonio_bem_codigo
			echo "<td>" . ($b['patrimonio_bem_codigo'] ?? '#'.$b['patrimonio_bem_id']) . "</td>";
			echo "<td>" . $b['patrimonio_bem_nome'] . "</td>";
			echo "<td>" . $b['patrimonio_bem_descricao'] . "</td>";

			$dataAquisicao = (!empty($b['patrimonio_bem_data_aquisicao'])) ? date('d/m/Y', strtotime($b['patrimonio_bem_data_aquisicao'])) : '-';
			echo "<td>" . $dataAquisicao . "</td>";
			echo "<td>" . number_format($b['patrimonio_bem_valor'], 2, ',', '.') . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit();
	}

	/**
	 * Método auxiliar para limpar o nome do arquivo (Remover acentos e espaços)
	 */
	private function slugify($text)
	{
		// Substitui caracteres não letras ou números por hifen
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// Translitera para ASCII (tira acentos)
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// Remove caracteres indesejados
		$text = preg_replace('~[^-\w]+~', '', $text);
		// Trim de hífens
		$text = trim($text, '-');
		// Remove hífens duplicados
		$text = preg_replace('~-+~', '-', $text);
		// Minúsculo
		$text = strtolower($text);

		return empty($text) ? 'n-a' : $text;
	}

	// Exibe a listagem de categorias
	public function categorias()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$categorias = $this->model->getCategorias($igrejaId);

		$this->view('patrimonios/categorias', [
			'categorias' => $categorias
		]);
	}

	// Salva (Insert ou Update)
	public function salvarCategoria()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$id = $_POST['id'] ?? null;
		$nome = $_POST['patrimonio_categoria_nome'] ?? '';

		if (!empty($nome)) {
			if ($id) {
				$this->model->atualizarCategoria($id, $igrejaId, $nome);
			} else {
				$this->model->inserirCategoria($igrejaId, $nome);
			}
		}

		header('Location: ' . url('patrimonios/categorias'));
		exit;
	}

	// Exclui
	public function excluirCategoria($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$this->model->excluirCategoria($id, $igrejaId);
		header('Location: ' . url('patrimonios/categorias'));
		exit;
	}

	public function documento($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Busca os dados consolidados do bem e da última movimentação no Model
		$dados = $this->model->getDadosDocumento($id, $igrejaId);
		$dadosIgreja = $this->model->getIgrejaDados($igrejaId);

		// Se não encontrar o patrimônio, volta para a listagem
		if (!$dados) {
			header('Location: ' . url('patrimonios?erro=nao_encontrado'));
			exit;
		}

		// Lista de status que permitem a geração do documento
		$statusPermitidos = ['manutencao', 'baixado', 'extraviado', 'danificado'];

		if (!in_array($dados['patrimonio_bem_status'], $statusPermitidos)) {
			header('Location: ' . url('patrimonios?erro=status_invalido'));
			exit;
		}

		// Carrega a view de impressão (layout limpo para papel A4)
		$this->rawview('patrimonios/documento_saida', [
			'dados'   => $dados,
			'igreja'  => $dadosIgreja,
			'titulo'  => 'Termo de ' . ucfirst($dados['patrimonio_bem_status'])
		]);
	}

}
