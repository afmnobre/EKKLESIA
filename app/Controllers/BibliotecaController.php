<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Biblioteca;
use App\Core\Utils;

class BibliotecaController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Biblioteca();
    }

    /**
     * Lista os livros aplicando filtros de letra e categoria
     */
	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Define a lógica de filtros
		$categoria = $_GET['categoria'] ?? null;
		$letra = $categoria ? null : ($_GET['letra'] ?? 'A');

		// MONTA O ARRAY DE FILTROS (Isso resolve o problema)
		$filtros = [
			'letra'     => $letra,
			'categoria' => $categoria
		];

		$igreja = $this->model->getIgrejaDetalhes($igrejaId);

		// CHAMA O MODEL PASSANDO O ARRAY
		$livros = $this->model->getLivrosFiltrados($igrejaId, $filtros);

		$categorias = $this->model->getCategorias($igrejaId);
		$membros = $this->model->getMembros($igrejaId);

		$this->view('biblioteca/index', [
			'igreja'         => $igreja,
			'livros'         => $livros,
			'membros'        => $membros,
			'letraAtiva'     => $_GET['letra'] ?? ($categoria ? '' : 'A'),
			'categorias'     => $categorias,
			'categoriaAtiva' => $categoria
		]);
	}

    /**
     * Salva um novo livro com upload de capa
     */
	public function salvar() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nomeCapa = null;
			$diretorio = "assets/uploads/{$igrejaId}/biblioteca/";

			// Captura os filtros para manter o estado da página
			$letra = $_POST['letra_atual'] ?? 'A';
			$categoria = $_POST['categoria_atual'] ?? '';
			$queryFiltros = "&letra={$letra}" . (!empty($categoria) ? "&categoria=" . urlencode($categoria) : "");

			// 1. LÓGICA DE CAPA (UPLOAD MANUAL OU API)

			// Verificamos se há um upload de arquivo manual primeiro (prioridade)
			if (isset($_FILES['livro_capa']) && $_FILES['livro_capa']['error'] === UPLOAD_ERR_OK) {
				$ext = strtolower(pathinfo($_FILES['livro_capa']['name'], PATHINFO_EXTENSION));
				$extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

				if (in_array($ext, $extensoesPermitidas)) {
					$nomeCapa = 'capa_' . time() . '_' . uniqid() . '.jpg';
					if (!is_dir($diretorio)) mkdir($diretorio, 0755, true);

					$caminhoTemporario = $_FILES['livro_capa']['tmp_name'];
					$caminhoFinal = $diretorio . $nomeCapa;

					if (!Utils::otimizarImagem($caminhoTemporario, $caminhoFinal, 800, 75)) {
						$nomeCapa = null;
					}
				}
			}
			// Se não houve upload manual, mas existe uma URL de capa vinda da API
			elseif (!empty($_POST['capa_url_externa'])) {
				$urlCapa = $_POST['capa_url_externa'];
				$nomeCapa = 'capa_api_' . time() . '_' . uniqid() . '.jpg';

				if (!is_dir($diretorio)) mkdir($diretorio, 0755, true);

				// Baixa a imagem da URL externa para o servidor local
				$conteudoImagem = @file_get_contents($urlCapa);
				if ($conteudoImagem) {
					$caminhoFinal = $diretorio . $nomeCapa;
					file_put_contents($caminhoFinal, $conteudoImagem);

					// Otimizamos a imagem baixada para garantir que o tamanho seja padronizado
					Utils::otimizarImagem($caminhoFinal, $caminhoFinal, 800, 75);
				} else {
					$nomeCapa = null;
				}
			}

			// 2. MONTAGEM DOS DADOS PARA O MODEL
			$data = [
				'livro_igreja_id'  => $igrejaId,
				'livro_titulo'     => $_POST['livro_titulo'] ?? null,
				'livro_autor'      => $_POST['livro_autor'] ?? null,
				'livro_isbn'       => $_POST['livro_isbn'] ?? null,
				'livro_editora'    => $_POST['livro_editora'] ?? null, // Verifique se o name no HTML é exatamente este
				'livro_categoria'  => $_POST['livro_categoria'] ?? null,
				'livro_publicacao' => $_POST['livro_publicacao'] ?? null,
				'livro_quantidade' => $_POST['livro_quantidade'] ?? 1,
				'livro_capa'       => $nomeCapa
			];

			// 3. EXECUÇÃO
			if ($this->model->cadastrarLivro($data)) {
				header("Location: " . url("biblioteca?sucesso=1{$queryFiltros}"));
			} else {
				header("Location: " . url("biblioteca?erro=1{$queryFiltros}"));
			}
			exit;
		}
	}

	public function excluir($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Captura filtros da URL enviados pelo link ajustado no JS
		$letra = $_GET['letra'] ?? 'A';
		$categoria = $_GET['categoria'] ?? '';
		$queryFiltros = "&letra={$letra}" . (!empty($categoria) ? "&categoria=" . urlencode($categoria) : "");

		$livro = $this->model->getLivroPorId($id, $igrejaId);

		if ($livro) {
			if ($this->model->excluirLivro($id, $igrejaId)) {
				if (!empty($livro['livro_capa'])) {
					$caminhoArquivo = "assets/uploads/{$igrejaId}/biblioteca/" . $livro['livro_capa'];
					if (file_exists($caminhoArquivo)) unlink($caminhoArquivo);
				}
				header("Location: " . url("biblioteca?sucesso_exclusao=1{$queryFiltros}"));
			} else {
				header("Location: " . url("biblioteca?erro_exclusao=1{$queryFiltros}"));
			}
		} else {
			header("Location: " . url("biblioteca?erro=404{$queryFiltros}"));
		}
		exit;
	}

    /**
     * Atualiza dados do livro e substitui capa se necessário
     */
	public function atualizar() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$livroId = $_POST['livro_id'];

			// Captura os filtros para manter o estado da página
			$letra = $_POST['letra_atual'] ?? 'A';
			$categoria = $_POST['categoria_atual'] ?? '';
			$queryFiltros = "&letra={$letra}" . (!empty($categoria) ? "&categoria=" . urlencode($categoria) : "");

			$livroAtual = $this->model->getLivroPorId($livroId, $igrejaId);
			$nomeArquivoCapa = $livroAtual['livro_capa'];

			if (isset($_FILES['livro_capa']) && $_FILES['livro_capa']['error'] === UPLOAD_ERR_OK) {
				$diretorio = "assets/uploads/{$igrejaId}/biblioteca/";
				$novoNome = 'capa_' . time() . '_' . uniqid() . '.jpg';

				if (Utils::otimizarImagem($_FILES['livro_capa']['tmp_name'], $diretorio . $novoNome, 800, 75)) {
					if (!empty($nomeArquivoCapa)) {
						$caminhoAntigo = $diretorio . $nomeArquivoCapa;
						if (file_exists($caminhoAntigo)) unlink($caminhoAntigo);
					}
					$nomeArquivoCapa = $novoNome;
				}
			}

			$data = [
				'livro_id'         => $livroId,
				'livro_igreja_id'  => $igrejaId,
				'livro_titulo'     => $_POST['livro_titulo'],
                'livro_autor'      => $_POST['livro_autor'],
                'livro_isbn'       => $_POST['livro_isbn'],
                'livro_editora'    => $_POST['livro_editora'],
                'livro_categoria'  => $_POST['livro_categoria'],
                'livro_publicacao' => $_POST['livro_publicacao'],
				'livro_capa'       => $nomeArquivoCapa
			];

			if ($this->model->atualizarLivro($data)) {
				header("Location: " . url("biblioteca?sucesso=editado{$queryFiltros}"));
			} else {
				header("Location: " . url("biblioteca?erro=update_failed{$queryFiltros}"));
			}
			exit;
		}
	}

    /**
     * Tela de gerenciamento de categorias
     */
    public function categorias()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $igreja = $this->model->getIgrejaDetalhes($igrejaId);
        $categorias = $this->model->getCategorias($igrejaId);

        $this->view('biblioteca/categorias', [
            'igreja' => $igreja,
            'categorias' => $categorias
        ]);
    }

    /**
     * Salva uma nova categoria
     */
    public function categoriaSalvar()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'igreja_id' => $igrejaId,
                'nome' => $_POST['categoria_nome'],
                'descricao' => $_POST['categoria_descricao']
            ];

            if ($this->model->salvarCategoria($data)) {
                header("Location: " . url('biblioteca/categorias?sucesso=1'));
            } else {
                header("Location: " . url('biblioteca/categorias?erro=1'));
            }
            exit;
        }
    }

	public function categoriaAtualizar()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [
				'id'        => $_POST['categoria_id'],
				'igreja_id' => $igrejaId,
				'nome'      => $_POST['categoria_nome'],
				'descricao' => $_POST['categoria_descricao']
			];

			if ($this->model->atualizarCategoria($data)) {
				header("Location: " . url('biblioteca/categorias?sucesso=editado'));
			} else {
				header("Location: " . url('biblioteca/categorias?erro=edit_failed'));
			}
			exit;
		}
	}

	public function categoriaExcluir($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($this->model->excluirCategoria($id, $igrejaId)) {
			header("Location: " . url('biblioteca/categorias?sucesso=excluido'));
		} else {
			header("Location: " . url('biblioteca/categorias?erro=delete_failed'));
		}
		exit;
	}

	public function emprestar() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [
				'igreja_id'     => $_SESSION['usuario_igreja_id'],
				'livro_id'      => $_POST['livro_id'],
				'membro_id'     => $_POST['membro_id'], // ID vindo do select
				'data_prevista' => $_POST['data_devolucao']
			];

			if ($this->model->emprestarLivro($data)) {
				header("Location: " . url('biblioteca?sucesso=1'));
			}
			exit;
		}
	}

	public function emprestimos() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$dados['igreja'] = $this->model->getIgrejaDetalhes($igrejaId);

		$listaBusca = $this->model->getEmprestimosAtivos($igrejaId);

		$agrupados = [];
		foreach ($listaBusca as $emp) {
			$chave = $emp['emprestimo_membro_id'] . '_' . date('Y-m-d', strtotime($emp['emprestimo_data_saida']));

			if (!isset($agrupados[$chave])) {
				$agrupados[$chave] = [
					'membro_id'       => $emp['emprestimo_membro_id'],
					'membro_nome'     => $emp['membro_nome'],
					'membro_telefone' => $emp['membro_telefone'], // <-- ADICIONE ESTA LINHA
					'data_saida'      => $emp['emprestimo_data_saida'],
					'data_prevista'   => $emp['emprestimo_data_prevista'],
					'livros'          => []
				];
			}

			$agrupados[$chave]['livros'][] = [
				'emprestimo_id' => $emp['emprestimo_id'],
				'livro_id'      => $emp['emprestimo_livro_id'],
				'titulo'        => $emp['livro_titulo'],
				'categoria'     => $emp['livro_categoria'] ?? 'Geral'
			];
		}

		$dados['emprestimos_agrupados'] = $agrupados;
		$this->view('biblioteca/emprestimos', $dados);
	}

	public function processarDevolucao($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		if($this->model->devolverLivro($id, $igrejaId)) {
			header("Location: " . url('biblioteca/emprestimos?sucesso=devolvido'));
		}
		exit;
    }

	public function processarDevolucaoTotal($ids) {
		// Segurança: verifica se o utilizador está logado e tem permissão
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if (empty($ids)) {
			$_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum empréstimo selecionado.</div>";
			header("Location: " . url('biblioteca/emprestimos'));
			exit;
		}

		if ($this->model->processarDevolucaoEmLote($ids, $igrejaId)) {
			$_SESSION['msg'] = "<div class='alert alert-success'>Todos os livros foram devolvidos com sucesso!</div>";
		} else {
			$_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao processar a devolução total.</div>";
		}

		header("Location: " . url('biblioteca/emprestimos'));
		exit;
	}

	public function dashboard() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$model = new Biblioteca();

		// Busca os dados do Model
		$dadosDashboard = $model->getEstatisticasDashboard($igrejaId);

		// Passa para a view. Certifique-se de que 'editoras' existe no array!
		$this->view('biblioteca/dashboard', [
			'stats' => $dadosDashboard['stats'],
			'dadosCategorias' => $dadosDashboard['categorias'],
			'dadosAutores' => $dadosDashboard['autores'],
			'editoras' => $dadosDashboard['editoras'] ?? [], // O SEGREDO ESTÁ AQUI
			'historico' => $dadosDashboard['historico'],
			'maisPopulares' => $dadosDashboard['populares']
		]);
	}

	public function imprimirEtiquetas() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$model = new Biblioteca();

		// Captura os filtros da URL
		$filtrosSelecionados = [
			'titulo'    => $_GET['titulo'] ?? null,
			'autor'     => $_GET['autor'] ?? null,
			'editora'   => $_GET['editora'] ?? null,
			'categoria' => $_GET['categoria'] ?? null
		];

		$detalhesIgreja = $model->getIgrejaDetalhes($igrejaId);

		// Busca os livros com base nos filtros
		$livros = $model->getLivrosFiltrados($igrejaId, $filtrosSelecionados);

		// Busca as listas para preencher os <select> do topo
		$autores    = $model->getAutoresDistinct($igrejaId);
		$editoras   = $model->getEditorasDistinct($igrejaId);
		$categorias = $model->getCategorias($igrejaId);

		$this->view('biblioteca/etiquetas', [
			'livros'     => $livros,
			'igreja_id'  => $igrejaId,
			'nomeIgreja' => $detalhesIgreja['igreja_nome'] ?? 'Minha Igreja',
			'logoIgreja' => $detalhesIgreja['igreja_logo'] ?? null,
			'filtros_selecionados' => $filtrosSelecionados, // Para manter o valor no campo
			'filtros'    => [
				'autores'    => $autores,
				'editoras'   => $editoras,
				'categorias' => $categorias
			]
		]);
	}

	public function api_isbn() {
		header('Content-Type: application/json');

		$isbn = preg_replace('/\D/', '', $_GET['isbn'] ?? '');
		$providers = explode(',', $_GET['providers'] ?? 'google,openlibrary,brasilapi');

		if (strlen($isbn) < 10) {
			echo json_encode(['error' => 'ISBN inválido']);
			exit;
		}

		$res = ['titulo' => '', 'autor' => '', 'editora' => '', 'data' => '', 'capa' => ''];

		// Configurações padrão do CURL
		$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

		// 1. GOOGLE BOOKS
		if (in_array('google', $providers)) {
			$urlGoogle = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . $isbn . "&country=BR";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlGoogle);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$response = curl_exec($ch);
			$data = json_decode($response, true);

			// Fallback textual dentro do Google caso não ache pelo filtro exact isbn
			if (!isset($data['items'])) {
				curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=" . $isbn);
				$response = curl_exec($ch);
				$data = json_decode($response, true);
			}

			if (isset($data['items'][0]['volumeInfo'])) {
				$info = $data['items'][0]['volumeInfo'];
				$res['titulo']  = $info['title'] ?? '';
				$res['autor']   = isset($info['authors']) ? implode(', ', $info['authors']) : '';
				$res['editora'] = $info['publisher'] ?? '';
				$res['data']    = $info['publishedDate'] ?? '';

				if (isset($info['imageLinks'])) {
					$imgs = $info['imageLinks'];
					$capaG = $imgs['extraLarge'] ?? $imgs['large'] ?? $imgs['medium'] ?? $imgs['thumbnail'] ?? '';
					$res['capa'] = str_replace(['http:', '&edge=curl'], ['https:', ''], $capaG);
				}
			}
		}

		// 2. OPEN LIBRARY (Ajustado para Data API - Mais preciso para Editora e Capa)
		if (in_array('openlibrary', $providers)) {
			$urlOL = "https://openlibrary.org/api/books?bibkeys=ISBN:" . $isbn . "&jscmd=data&format=json";

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlOL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$respOL = curl_exec($ch);
			$dataOL = json_decode($respOL, true);

			$key = "ISBN:" . $isbn;
			if (isset($dataOL[$key])) {
				$book = $dataOL[$key];

				if (empty($res['titulo'])) $res['titulo'] = $book['title'] ?? '';
				if (empty($res['autor']))  $res['autor'] = isset($book['authors']) ? implode(', ', array_column($book['authors'], 'name')) : '';

				// Tratamento de Editora (pode vir como string ou array de objetos)
				if (empty($res['editora']) && isset($book['publishers'])) {
					$pub = $book['publishers'][0];
					$res['editora'] = is_array($pub) ? ($pub['name'] ?? '') : $pub;
				}

				if (empty($res['data'])) $res['data'] = $book['publish_date'] ?? '';

				// Validação de Imagem Real (evita capas genéricas da OL)
				if (empty($res['capa'])) {
					$capaTentativa = "";
					if (isset($book['cover']['large'])) $capaTentativa = $book['cover']['large'];
					elseif (isset($book['cover']['medium'])) $capaTentativa = $book['cover']['medium'];

					if (!empty($capaTentativa) && $this->is_imagem_valida_mesmo($capaTentativa)) {
						$res['capa'] = $capaTentativa;
					}
				}
			}
		}

		// 3. BRASIL API (Fallback final)
		if (in_array('brasilapi', $providers) && (empty($res['titulo']) || empty($res['capa']))) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://brasilapi.com.br/api/isbn/v1/{$isbn}");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$respB = curl_exec($ch);
			if ($respB) {
				$dataB = json_decode($respB, true);
				if (empty($res['titulo'])) $res['titulo'] = $dataB['title'] ?? '';
				if (empty($res['autor']))  $res['autor'] = isset($dataB['authors']) ? implode(', ', $dataB['authors']) : '';
				if (empty($res['editora'])) $res['editora'] = $dataB['publisher'] ?? '';
				if (empty($res['data']))    $res['data'] = $dataB['year'] ?? '';
				// Brasil API raramente fornece capas, mas se no futuro fornecer:
				if (empty($res['capa']) && isset($dataB['cover_url'])) {
					if ($this->is_imagem_valida_mesmo($dataB['cover_url'])) {
						$res['capa'] = $dataB['cover_url'];
					}
				}
			}
		}

		echo json_encode($res);
		exit;
	}

	/**
	 * Validação de Imagem Real vs Placeholder
	 */
	private function is_imagem_valida_mesmo($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		// Adicionando um User-Agent de navegador para evitar bloqueios durante a checagem
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/110.0.0.0 Safari/537.36');

		$raw = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		// 1. Se não for status 200 ou não for uma imagem, descarta
		if ($info['http_code'] !== 200 || strpos($info['content_type'], 'image') === false) {
			return false;
		}

		// 2. TESTE DE PESO (A imagem cinza que você mandou é muito leve)
		// Subimos para 12000 bytes (12KB).
		// Capas reais (JPEG/PNG) com detalhes de arte costumam ter de 15KB a 100KB.
		if ($info['size_download'] < 12000) {
			return false;
		}

		return true;
	}

	public function imprimirEtiquetasQr()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// 1. Coleta os filtros da URL
		$filtrosAplicados = [
			'titulo'    => $_GET['titulo'] ?? null,
			'autor'     => $_GET['autor'] ?? null,
			'editora'   => $_GET['editora'] ?? null,
			'categoria' => $_GET['categoria'] ?? null
		];

		// 2. Busca os livros filtrados
		$livros = $this->model->getLivrosFiltrados($igrejaId, $filtrosAplicados);

		// 3. Busca as listas para preencher os selects dos filtros (o "dropdown")
		$dadosFiltros = [
			'autores'    => $this->model->getAutoresDistinct($igrejaId),
			'editoras'   => $this->model->getEditorasDistinct($igrejaId),
			'categorias' => $this->model->getCategorias($igrejaId)
		];

		$igreja = $this->model->getIgrejaDetalhes($igrejaId);

		$this->view('biblioteca/etiquetas_qr', [
			'igreja'  => $igreja,
			'livros'  => $livros,
			'filtros' => $dadosFiltros // Passamos as listas para a view
		]);
	}

	public function exportarExcel()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Buscamos TODOS os livros para o relatório completo
		// Certifique-se que o método getTodosLivrosRelatorio use a query com LEFT JOIN que ajustamos
		$livros = $this->model->getTodosLivrosRelatorio($igrejaId);

		$arquivo = "relatorio_geral_biblioteca_" . date('d_m_Y') . ".xls";

		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=\"$arquivo\"");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");

		echo "<table border='1'>";
		echo "<tr>";
		echo "<th style='background-color: #333; color: #fff;'>TITULO</th>";
		echo "<th style='background-color: #333; color: #fff;'>AUTOR</th>";
		echo "<th style='background-color: #333; color: #fff;'>ISBN</th>";
		echo "<th style='background-color: #333; color: #fff;'>CATEGORIA</th>"; // Cabeçalho mantido
		echo "<th style='background-color: #333; color: #fff;'>EDITORA</th>";
		echo "<th style='background-color: #333; color: #fff;'>PUBLICAÇÃO</th>";
		echo "<th style='background-color: #333; color: #fff;'>QTD TOTAL</th>";
		echo "<th style='background-color: #333; color: #fff;'>DISPONÍVEL</th>";
		echo "</tr>";

		foreach ($livros as $l) {
			$qtdTotal = (int)$l['livro_quantidade'];
			$emprestados = (int)$l['total_emprestados'];
			$disponivel = $qtdTotal - $emprestados;

			echo "<tr>";
			echo "<td>" . mb_strtoupper($l['livro_titulo']) . "</td>";
			echo "<td>" . ($l['livro_autor'] ?: '---') . "</td>";
			echo "<td>" . ($l['livro_isbn'] ?: '---') . "</td>";

			// ALTERAÇÃO AQUI: Trocado 'livro_categoria' (ID) por 'categoria_nome' (Texto)
			echo "<td>" . ($l['categoria_nome'] ?: '---') . "</td>";

			echo "<td>" . ($l['livro_editora'] ?: '---') . "</td>";
			echo "<td>" . ($l['livro_publicacao'] ?: '---') . "</td>";
			echo "<td style='text-align:center;'>" . $qtdTotal . "</td>";
			echo "<td style='text-align:center;'>" . ($disponivel < 0 ? 0 : $disponivel) . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		exit;
	}


}
