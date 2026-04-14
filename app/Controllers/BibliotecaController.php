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
				'livro_titulo'     => $_POST['livro_titulo'],
				'livro_autor'      => $_POST['livro_autor'],
				'livro_isbn'       => $_POST['livro_isbn'],
				'livro_categoria'  => $_POST['livro_categoria'],
				'livro_publicacao' => $_POST['livro_publicacao'] ?? null, // Novo campo
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
				'livro_id'        => $livroId,
				'livro_igreja_id' => $igrejaId,
				'livro_titulo'    => $_POST['livro_titulo'],
				'livro_autor'     => $_POST['livro_autor'],
				'livro_categoria' => $_POST['livro_categoria'],
				'livro_capa'      => $nomeArquivoCapa
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

		// Buscar dados da igreja (ajuste conforme seu método de busca de igreja)
		$dados['igreja'] = $this->model->getIgrejaDetalhes($igrejaId);

		// Buscar empréstimos ativos
		$dados['emprestimos'] = $this->model->getEmprestimosAtivos($igrejaId);

		$this->view('biblioteca/emprestimos', $dados);
	}

	public function processarDevolucao($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		if($this->model->devolverLivro($id, $igrejaId)) {
			header("Location: " . url('biblioteca/emprestimos?sucesso=devolvido'));
		}
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

		if (strlen($isbn) < 10) {
			echo json_encode(['error' => 'ISBN inválido']);
			exit;
		}

		$res = ['titulo' => '', 'autor' => '', 'data' => '', 'capa' => ''];

		// 1. BUSCA NO GOOGLE BOOKS (Dados + Capa)
		$urlG = "https://www.googleapis.com/books/v1/volumes?q=isbn:" . $isbn;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlG);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		$respG = curl_exec($ch);
		curl_close($ch);
		$dataG = json_decode($respG, true);

		if (isset($dataG['items'][0]['volumeInfo'])) {
			$info = $dataG['items'][0]['volumeInfo'];
			$res['titulo'] = $info['title'] ?? '';
			$res['autor'] = isset($info['authors']) ? implode(', ', $info['authors']) : '';
			$res['data'] = $info['publishedDate'] ?? '';
			if (isset($info['imageLinks']['thumbnail'])) {
				$res['capa'] = str_replace(['http:', '&edge=curl'], ['https:', ''], $info['imageLinks']['thumbnail']);
			}
		}

		// 2. SE NÃO ACHOU CAPA, TENTA OPEN LIBRARY (Específico para imagens)
		if (empty($res['capa'])) {
			$urlOL = "https://covers.openlibrary.org/b/isbn/{$isbn}-M.jpg?default=false";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlOL);
			curl_setopt($ch, CURLOPT_NOBODY, true); // Só verifica se a imagem existe
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpCode == 200) {
				$res['capa'] = $urlOL;
			}
		}

		// 3. SE AINDA NÃO TEM TÍTULO, TENTA BRASIL API (Dados Técnicos)
		if (empty($res['titulo'])) {
			$ctx = stream_context_create(["http" => ["timeout" => 5]]);
			$brasilApi = @file_get_contents("https://brasilapi.com.br/api/isbn/v1/{$isbn}", false, $ctx);
			if ($brasilApi) {
				$dataB = json_decode($brasilApi, true);
				$res['titulo'] = $dataB['title'] ?? '';
				$res['autor'] = isset($dataB['authors']) ? implode(', ', $dataB['authors']) : '';
				$res['data'] = $dataB['year'] ?? '';
			}
		}

		echo json_encode($res);
		exit;
	}

}
