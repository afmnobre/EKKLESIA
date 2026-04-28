<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Financeiro;
use App\Models\Igreja;
use App\Core\Utils;

class FinanceiroController extends Controller {
    private $model;
    private $igrejaModel;

    public function __construct() {
        $this->model = new Financeiro();
        $this->igrejaModel = new Igreja();
    }

	public function index() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Define datas padrão (Mês Corrente) se não vierem via GET
		$dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
		$dataFim    = $_GET['data_fim'] ?? date('Y-m-t');

		$contas = $this->model->getContasBancarias($igrejaId);

		// Busca movimentações filtradas
		$movimentacoes = $this->model->getMovimentacoesPorPeriodo($igrejaId, $dataInicio, $dataFim);

		$this->view('financeiro/index', [
			'contas'        => $contas,
			'movimentacoes' => $movimentacoes,
			'dataInicio'    => $dataInicio,
			'dataFim'       => $dataFim
		]);
	}

    public function transferir() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $res = $this->model->transferir(
                $_SESSION['usuario_igreja_id'],
                $_POST['origem'],
                $_POST['destino'],
                $_POST['valor'],
                $_POST['descricao']
            );
            header("Location: " . url('financeiro') . ($res ? "?sucesso=1" : "?erro=1"));
        }
    }

	public function categorias() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$this->view('financeiro/categorias', [
			'categorias' => $this->model->getCategorias($igrejaId)
		]);
	}

	public function salvar_categoria() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [
				'id' => $_POST['id'] ?? null,
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'nome' => $_POST['nome'],
				'tipo' => $_POST['tipo']
			];
			$this->model->salvarCategoria($data);
			header("Location: " . url('financeiro/categorias') . "?sucesso=1");
		}
	}

	public function excluir_categoria($id) {
		$this->model->excluirCategoria($id, $_SESSION['usuario_igreja_id']);
		header("Location: " . url('financeiro/categorias') . "?sucesso=excluido");
	}

	public function contas() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$this->view('financeiro/contas', [
			'contas' => $this->model->getContasFinanceiras($igrejaId)
		]);
	}

	public function salvar_conta() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [
				'id'            => $_POST['id'] ?? null,
				'igreja_id'     => $_SESSION['usuario_igreja_id'],
				'nome'          => $_POST['nome'],
				'tipo'          => $_POST['tipo'],
				'saldo_inicial' => $_POST['saldo'] ?? 0, // Apenas na criação
				'status'        => $_POST['status'] ?? 'ativo'
			];
			$this->model->salvarContaFinanceira($data);
			header("Location: " . url('financeiro/contas') . "?sucesso=1");
		}
	}

	public function lancamentos() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$anoAtual = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
		$mesAtual = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');

		$anosDisponiveis = $this->model->getAnosComMovimentacao($igrejaId);
		if (empty($anosDisponiveis)) { $anosDisponiveis = [['ano' => date('Y')]]; }

		$contas = $this->model->getContasAgendadas($igrejaId, $mesAtual, $anoAtual);

		// --- ADICIONE ESTE BLOCO AQUI ---
		foreach ($contas as &$c) {
			// Busca se existe rateio para este lançamento específico
			$c['membros'] = $this->model->getMembrosRateio($c['financeiro_conta_id']);
		}
		unset($c); // Limpa a referência
		// --------------------------------

		$categorias = $this->model->getCategoriasParaCombo($igrejaId);
		$contasBancarias = $this->model->getContasBancarias($igrejaId);
		$membros = $this->model->getMembrosAtivos($igrejaId);
		$oficiais = $this->model->getOficiaisConferentes($igrejaId);

		$this->view('financeiro/lancamentos', [
			'contas_agendadas'      => $contas, // Agora cada conta tem a chave ['membros']
			'categorias_formatadas' => $categorias,
			'contas_bancarias'      => $contasBancarias,
			'membros'               => $membros,
			'oficiais'              => $oficiais,
			'anoSelecionado'        => $anoAtual,
			'mesSelecionado'        => $mesAtual,
			'anosDisponiveis'       => $anosDisponiveis
		]);
	}

	// Aproveite e crie a rota para salvar o agendamento que o modal envia
	public function salvar_conta_agendada() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = [
				'id'           => $_POST['id'] ?? null,
				'igreja_id'    => $_SESSION['usuario_igreja_id'],
				'categoria_id' => $_POST['subcategoria_id'], // Pega do select 'subcategoria_id'
				'descricao'    => $_POST['descricao'],
				'valor'        => $_POST['valor'],
				'tipo'         => $_POST['tipo'],
				'vencimento'   => $_POST['vencimento'],
				'reembolso'    => isset($_POST['reembolso']) ? 1 : 0, // Captura o checkbox
				'pago'         => 0
			];

			if ($this->model->salvarConta($data)) {
				header("Location: " . url('financeiro/lancamentos') . "?sucesso=agendado");
			} else {
				// Se falhar, você pode dar um die para ver o erro de PDO
				die("Erro ao salvar no banco de dados.");
			}
			exit;
		}
	}

	public function atualizar() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Monta o array exatamente como o seu método atualizarLancamentoCompleto espera
		$data = [
			'id'                               => $_POST['id'],
			'igreja_id'                        => $igrejaId,
			'categoria_id'                     => $_POST['categoria_id'] ?? $_POST['subcategoria_id'],
			'descricao'                        => $_POST['descricao'],
			'valor'                            => $_POST['valor'],
			'data_pagamento'                   => $_POST['data_pagamento'],
			'reembolso'                        => $_POST['reembolso'] ?? 0,
			'financeiro_conta_financeira_id'   => $_POST['financeiro_conta_financeira_id'], // CAMPO CHAVE
			'membros'                          => $_POST['membros'] ?? [],
			'membros_valores'                  => $_POST['membros_valores'] ?? []
		];

		$sucesso = $this->model->atualizarLancamentoCompleto($data);

		if ($sucesso) {
			// Redireciona com mensagem de sucesso
			header("Location: " . url('financeiro') . "?msg=atualizado");
		} else {
			// Trate o erro conforme sua estrutura
			die("Erro ao atualizar o lançamento. Verifique os dados ou o saldo das contas.");
		}
	}

	public function gerar_recibo_reembolso($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$conta = $this->model->getContaById($id, $igrejaId);

		if (!$conta || $conta['financeiro_conta_reembolso'] != 1) {
			die("Lançamento não encontrado ou não é um reembolso.");
		}

		$data = [
			'conta'      => $conta,
			'igreja'     => $this->model->getDadosIgreja($igrejaId),
			'tesoureiro' => $this->model->getTesoureiro($igrejaId) // Busca o tesoureiro
		];

		$this->rawview('financeiro/recibo_reembolso', $data);
	}

	public function baixar_conta() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];
			$contaId = $_POST['conta_id'];
			$comprovanteNome = null;

			// Tratar Upload do Comprovante
			if (!empty($_FILES['comprovante']['name'])) {
				$ext = pathinfo($_FILES['comprovante']['name'], PATHINFO_EXTENSION);
				$comprovanteNome = 'comp_' . uniqid() . '.' . $ext;
				$destino = 'public/uploads/financeiro/' . $comprovanteNome;

				if (!is_dir('public/uploads/financeiro/')) mkdir('public/uploads/financeiro/', 0777, true);
				move_uploaded_file($_FILES['comprovante']['tmp_name'], $destino);
			}

			$dados = [
				'igreja_id'            => $igrejaId,
				'conta_id'             => $contaId,
				'conta_financeira_id'  => $_POST['conta_financeira_id'],
				'valor'                => $_POST['valor'],
				'data_pagamento'       => $_POST['data_pagamento'],
				'comprovante'          => $comprovanteNome
			];

			if ($this->model->processarBaixa($dados)) {
				header("Location: " . url('financeiro/lancamentos') . "?sucesso=pago");
			} else {
				header("Location: " . url('financeiro/lancamentos') . "?erro=falha_baixa");
			}
		}
	}

	public function excluir_subcategoria($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Capturamos o cat_id que enviamos pelo link da View (?cat_id=X)
		// para saber para onde voltar após a exclusão
		$catIdPai = $_GET['cat_id'] ?? null;

		// Chama o model para processar a exclusão
		$sucesso = $this->model->excluirSubcategoria($id, $igrejaId);

		if ($sucesso) {
			// Se temos o ID da categoria pai, voltamos para a tela filtrada
			if ($catIdPai) {
				header("Location: " . url('financeiro/subcategorias?cat_id=' . $catIdPai) . "&sucesso=removido");
			} else {
				// Caso contrário, volta para a lista geral (fallback)
				header("Location: " . url('financeiro/subcategorias') . "?sucesso=removido");
			}
		} else {
			// Se falhar (ex: subcategoria em uso), também tenta manter o filtro
			$urlRetorno = $catIdPai ? 'financeiro/subcategorias?cat_id='.$catIdPai : 'financeiro/subcategorias';
			header("Location: " . url($urlRetorno) . "&erro=em_uso");
		}
		exit;
	}

	public function subcategorias() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$catSelecionadaId = $_GET['cat_id'] ?? null; // Captura o ID da URL

		$categorias = $this->model->getCategorias($igrejaId);
		$dadosAgrupados = $this->model->getCategoriasAgrupadas($igrejaId);

		$this->view('financeiro/subcategorias', [
			'categorias' => $categorias,
			'dados' => $dadosAgrupados,
			'cat_selecionada_id' => $catSelecionadaId // Passa para a view
		]);
	}

	public function salvar_subcategoria() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$categoriaId = $_POST['categoria_id'];

			$this->model->salvarSubcategoria(
				$_SESSION['usuario_igreja_id'],
				$categoriaId,
				$_POST['nome']
			);

			// Redireciona de volta para a mesma tela de subcategorias filtrada
			header("Location: " . url('financeiro/subcategorias?cat_id=' . $categoriaId) . "&sucesso=1");
			exit;
		}
    }

	public function excluir_lancamento($id) {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// O Model deve verificar se a conta não está paga antes de excluir por segurança
		if ($this->model->excluirContaAgendada($id, $igrejaId)) {
			header("Location: " . url('financeiro/lancamentos') . "?sucesso=excluido");
		} else {
			header("Location: " . url('financeiro/lancamentos') . "?erro=pago_nao_pode_excluir");
		}
		exit;
	}

	public function salvar_rateio_membros() {
		ob_clean();
		header('Content-Type: application/json');

		$json = file_get_contents('php://input');
		$dados = json_decode($json, true);

		$contaId = $dados['conta_id'] ?? null;
		// Pega a subcategoria enviada pelo JS
		$subcategoriaId = $dados['subcategoria_id'] ?? null;

		$receita = $this->model->getReceitaPorId($contaId);

		if (!$receita) {
			echo json_encode(['erro' => 'Receita não encontrada']);
			exit;
		}

		// 1. A Categoria principal (ex: 14)
		$categoriaId = $receita['financeiro_conta_financeiro_categoria_id'];

		// 2. A Subcategoria: Se o JS não enviou, tentamos usar a categoria como plano B
		// mas o ideal é que o JS envie 14 (Dízimo) ou 13 (Oferta)
		if (empty($subcategoriaId) || $subcategoriaId == 0) {
			$subcategoriaId = $categoriaId;
		}

		$dataDoc = $receita['financeiro_conta_data_pagamento'] ?? $receita['financeiro_conta_data_vencimento'] ?? date('Y-m-d');

		$sucesso = $this->model->registrarRateioMembros(
			$contaId,
			$dados['membros'],
			$_SESSION['usuario_igreja_id'],
			$categoriaId,
			$subcategoriaId,
			$dataDoc
		);

		echo json_encode(['sucesso' => $sucesso]);
		exit;
	}

	public function dashboard() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$anoAtual = date('Y');
		$anoAnterior = $anoAtual - 1;

		$resumo = $this->model->getResumoMes($igrejaId);
		$contas = $this->model->getContasBancarias($igrejaId);

		// 1. DASHBOARD DE LINHA (FLUXO MENSAL)
		$dadosRaw = $this->model->getDadosGraficoLinha($igrejaId);
		$dadosGrafico = array_fill(1, 12, ['entradas' => 0, 'saidas' => 0]);
		foreach ($dadosRaw as $d) {
			$dadosGrafico[(int)$d['mes']] = [
				'entradas' => (float)$d['entradas'],
				'saidas' => (float)$d['saidas']
			];
		}

		// 2. RELATÓRIO ESTRUTURADO (Para as tabelas detalhadas que estavam dando erro)
		// Este método preenche as chaves ['entrada'] e ['saida'] que a linha 282 espera
		$relatorioCategorias = $this->model->getFluxoAnualPorCategorias($igrejaId, $anoAtual);

		// 3. NOVOS COMPARATIVOS (Para a tabela de evolução e gráficos de barras)
		$compReceitas = $this->model->getComparativoReceitasAnual($igrejaId, $anoAtual);
		$compDespesas = $this->model->getComparativoDespesasAnual($igrejaId, $anoAtual);

		$this->view('financeiro/dashboard', [
			'resumo'             => $resumo,
			'contas'             => $contas,
			'fluxoAnual'         => $dadosGrafico,
			'anoAtual'           => $anoAtual,
			'anoAnterior'        => $anoAnterior,
			'relatorio'          => $relatorioCategorias, // Mantém compatibilidade com as tabelas de baixo
			'compReceitas'       => $compReceitas,       // Novo nome para o comparativo de receitas
			'compDespesas'       => $compDespesas        // Novo nome para o comparativo de despesas
		]);
	}

	public function comparativo() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$ano = $_GET['ano'] ?? date('Y');

		$dadosRaw = $this->model->getComparativoAnual($igrejaId, $ano);

		// Organiza os dados: [Categoria][Subcategoria][Mês] = values
		$comparativo = [];
		foreach ($dadosRaw as $d) {
			$key = $d['categoria'] . ' > ' . $d['subcategoria'];
			$comparativo[$key][$d['mes']] = [
				'atual' => $d['valor_atual'],
				'anterior' => $d['valor_anterior']
			];
		}

		$this->view('financeiro/comparativo', [
			'ano' => $ano,
			'anoAnterior' => $ano - 1,
			'dados' => $comparativo
		]);
	}

	public function uploadAnexo() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Detecta se é uma requisição AJAX (JavaScript)
			$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || isset($_POST['is_ajax']);

			$idIgreja = $_SESSION['usuario_igreja_id'] ?? $_POST['igreja_id'];
			$contaId = $_POST['conta_id'];
			$tipo = $_POST['tipo_arquivo'] ?? 'comprovante';
			$ano = $_POST['ano_referencia'] ?? $_POST['ano'];
			$mes = str_pad($_POST['mes_referencia'] ?? $_POST['mes'], 2, "0", STR_PAD_LEFT);
			$isMobile = isset($_POST['is_mobile']);
			$membroId = $_POST['membro_id'] ?? null;

			if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === 0) {

				// Busca arquivo antigo para deletar depois
				if ($membroId) {
					$membroAtual = $this->model->getMembroRateioById($membroId);
					$arquivoAntigo = $membroAtual['receita_membro_comprovante'] ?? null;
				} else {
					$contaAtual = $this->model->getContaById($contaId, $idIgreja);
					$colunaBanco = ($tipo === 'comprovante') ? 'financeiro_conta_comprovante' : 'financeiro_conta_nota_fiscal';
					$arquivoAntigo = $contaAtual[$colunaBanco] ?? null;
				}

				$extensaoOriginal = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
				$isImagem = in_array($extensaoOriginal, ['jpg', 'jpeg', 'png', 'webp', 'jfif']);
				$extensaoFinal = $isImagem ? 'jpg' : $extensaoOriginal;

				$prefixo = $membroId ? "membro_{$membroId}" : $tipo;
				$novoNome = "{$prefixo}_" . time() . "_" . rand(1000, 9999) . "." . $extensaoFinal;

				$raizProjeto = dirname(__DIR__, 2);
				$subPastaPath = ($tipo === 'comprovante') ? 'comprovantes' : 'notasfiscais';
				$diretorioDestino = $raizProjeto . "/public/assets/uploads/{$idIgreja}/financeiro/{$subPastaPath}/{$ano}/{$mes}/";
				$caminhoCompleto = $diretorioDestino . $novoNome;

				if (!is_dir($diretorioDestino)) {
					mkdir($diretorioDestino, 0777, true);
				}

				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoCompleto)) {
					if ($isImagem) {
						Utils::otimizarImagem($caminhoCompleto, $caminhoCompleto, 1000, 80);
					}

					$caminhoRelativo = "{$idIgreja}/financeiro/{$subPastaPath}/{$ano}/{$mes}/{$novoNome}";

					if ($membroId) {
						$sucessoDb = $this->model->atualizarComprovanteMembro($membroId, $caminhoRelativo);
					} else {
						$sucessoDb = $this->model->atualizarAnexoFinanceiro($contaId, $idIgreja, $tipo, $caminhoRelativo);
					}

					if ($sucessoDb) {
						if ($arquivoAntigo) {
							$caminhoFisicoAntigo = $raizProjeto . "/public/assets/uploads/" . $arquivoAntigo;
							if (file_exists($caminhoFisicoAntigo)) { @unlink($caminhoFisicoAntigo); }
						}

						if ($isAjax) {
							header('Content-Type: application/json');
							echo json_encode([
								'success' => true,
								'message' => 'Upload realizado com sucesso!',
								'novoCaminho' => $caminhoRelativo // Retorno essencial para o JS atualizar o botão
							]);
							exit;
						}

						if ($isMobile) {
							header("Location: " . full_url("financeiro/uploadExterno/{$contaId}?i={$idIgreja}&sucesso=1"));
							exit;
						}
						header("Location: " . url("financeiro/lancamentos?mes={$mes}&ano={$ano}&sucesso=arquivo_atualizado"));
						exit;
					}
				}
			}
		}

		if ($isAjax) {
			header('Content-Type: application/json');
			echo json_encode([
				'success' => false,
				'message' => 'Falha no upload.',
				'debug' => [
					'post' => $_POST,
					'files' => $_FILES,
					'sessao_igreja' => $_SESSION['usuario_igreja_id'] ?? 'vazia'
				]
			]);
			exit;
		}
		header("Location: " . url("financeiro/lancamentos?erro=upload_falhou"));
		exit;
    }

    public function relatorio_membros() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$ano = $_GET['ano'] ?? date('Y');

		$dadosRaw = $this->model->getRelatorioRateioMembros($igrejaId, $ano);

		$relatorio = [];
		foreach ($dadosRaw as $d) {
			$tipo = $d['tipo_receita']; // Ex: "Dízimos" ou "Ofertas"
			$membro = $d['membro_nome'];
			$mes = (int)$d['mes'];

			// Estrutura: [Tipo][Nome do Membro][Mes] = Soma dos valores
			if (!isset($relatorio[$tipo][$membro][$mes])) {
				$relatorio[$tipo][$membro][$mes] = 0;
			}
			$relatorio[$tipo][$membro][$mes] += $d['receita_membro_valor'];
		}

		$this->view('financeiro/relatorio_membros', [
			'relatorio' => $relatorio,
			'ano' => $ano
		]);
	}

	public function exportar_excel() {
		$mes = $_GET['mes'] ?? date('m');
		$ano = $_GET['ano'] ?? date('Y');
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$dados = $this->model->getContasAgendadas($igrejaId, $mes, $ano);
		$igreja = $this->model->getDadosIgreja($igrejaId);

		$nomeIgreja = $igreja['nome'] ?? 'EKKLESIA';
		$arquivo = "Financeiro_" . $mes . "_" . $ano . ".csv";

		// Configura os Headers para CSV
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $arquivo . '"');

		// Abre a saída de dados (output stream)
		$output = fopen('php://output', 'w');

		// Adiciona o BOM para o Excel/Google Sheets reconhecer acentos em UTF-8
		fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

		// Cabeçalho de Identificação (Opcional no CSV, mas ajuda)
		fputcsv($output, ["RELATÓRIO FINANCEIRO - " . $nomeIgreja]);
		fputcsv($output, ["Período: " . $mes . "/" . $ano]);
		fputcsv($output, []); // Linha em branco

		// Cabeçalho da Tabela
		fputcsv($output, ['Vencimento', 'Descrição', 'Categoria', 'Subcategoria', 'Tipo', 'Valor (R$)', 'Status']);

		$totalEntradas = 0;
		$totalSaidas = 0;

		foreach ($dados as $d) {
			$status = $d['financeiro_conta_pago'] ? 'Pago' : 'Pendente';
			$tipo = ucfirst((string)($d['financeiro_conta_tipo'] ?? ''));
			$data = date('d/m/Y', strtotime($d['financeiro_conta_data_vencimento']));
			$valorNumerico = (float)$d['financeiro_conta_valor'];

			if ($d['financeiro_conta_tipo'] == 'entrada') {
				$totalEntradas += $valorNumerico;
			} else {
				$totalSaidas += $valorNumerico;
			}

			// Adiciona a linha no CSV
			fputcsv($output, [
				$data,
				(string)($d['financeiro_conta_descricao'] ?? ''),
				(string)($d['financeiro_categoria_nome'] ?? ''),
				(string)($d['subcategoria_nome'] ?? ''),
				$tipo,
				number_format($valorNumerico, 2, ',', ''), // Vírgula como separador decimal para PT-BR
				$status
			]);
		}

		// Totais
		fputcsv($output, []);
		fputcsv($output, ['', '', '', '', 'SALDO DO PERÍODO:', number_format($totalEntradas - $totalSaidas, 2, ',', ''), '']);

		fclose($output);
		exit;
	}

	public function baixar_anexos_zip() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
		$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');

		$contas = $this->model->getContasAgendadas($igrejaId, $mes, $ano);

		// Define a raiz de uploads onde sabemos que temos permissão (chmod 775/777)
		$raizUploads = dirname(__DIR__, 2) . "/public/assets/uploads/";

		$zip = new \ZipArchive();
		$nomeZip = "Anexos_" . $mes . "_" . $ano . "_" . time() . ".zip"; // Adicionado time() para evitar conflito
		$caminhoZip = $raizUploads . $nomeZip; // SALVANDO NA PASTA DE UPLOADS

		if ($zip->open($caminhoZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
			$temArquivos = false;
			$mesNome = str_pad($mes, 2, "0", STR_PAD_LEFT);

			foreach ($contas as $c) {
				$tipoPasta = ($c['financeiro_conta_tipo'] == 'entrada') ? 'receitas' : 'despesas';

				// Limpeza radical para o nome do arquivo dentro do ZIP
				$desc = preg_replace('/[^a-zA-Z0-9]/', '_', $c['financeiro_conta_descricao']);
				$id = $c['financeiro_conta_id'];

				// 1. Verificar Comprovante
				if (!empty($c['financeiro_conta_comprovante'])) {
					$caminhoFisico = $raizUploads . $c['financeiro_conta_comprovante'];
					if (file_exists($caminhoFisico)) {
						$ext = pathinfo($caminhoFisico, PATHINFO_EXTENSION);
						$zip->addFile($caminhoFisico, "{$mesNome}/{$tipoPasta}/comprovantes/{$id}_{$desc}.{$ext}");
						$temArquivos = true;
					}
				}

				// 2. Verificar Nota Fiscal
				if (!empty($c['financeiro_conta_nota_fiscal'])) {
					$caminhoFisico = $raizUploads . $c['financeiro_conta_nota_fiscal'];
					if (file_exists($caminhoFisico)) {
						$ext = pathinfo($caminhoFisico, PATHINFO_EXTENSION);
						$zip->addFile($caminhoFisico, "{$mesNome}/{$tipoPasta}/notasfiscais/NF_{$id}_{$desc}.{$ext}");
						$temArquivos = true;
					}
				}
			}

			$zip->close();

			if ($temArquivos && file_exists($caminhoZip)) {
				// Limpa qualquer saída acidental do PHP antes de enviar o arquivo
				if (ob_get_level()) ob_end_clean();

				header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename=' . $nomeZip);
				header('Content-Length: ' . filesize($caminhoZip));
				header('Pragma: no-cache');

				readfile($caminhoZip);
				unlink($caminhoZip); // Remove o ZIP da pasta de uploads após o download
				exit;
			} else {
				if (file_exists($caminhoZip)) unlink($caminhoZip);
				header("Location: " . url('financeiro/lancamentos') . "?erro=sem_anexos");
				exit;
			}
		} else {
			die("Erro: O servidor não permitiu criar o ZIP mesmo na pasta de uploads. Verifique as permissões da pasta: " . $raizUploads);
		}
	}

	public function relatorio_raw() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Captura dados do GET (enviados pelo JavaScript do Modal)
		$data      = $_GET['data'] ?? date('Y-m-d');
		$diacono1  = $_GET['d1']   ?? 'Não informado';
		$diacono2  = $_GET['d2']   ?? 'Não informado';

		// Busca dados no Model
		$movimentos = $this->model->getReceitasParaConferencia($igrejaId, $data);
		$tesoureiro = $this->model->getTesoureiroIgreja($igrejaId);

		// Carrega a view de impressão (crie este arquivo em views/financeiro/relatorio_conferencia_print.php)
		$this->rawview('financeiro/relatorio_conferencia_print', [
			'movimentos'  => $movimentos,
			'data'        => $data,
			'conferente1' => $diacono1,
			'conferente2' => $diacono2,
			'tesoureiro'  => $tesoureiro
		]);
	}

	public function exportar_excel_dashboard() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$ano = date('Y');

		// 1. Instancia o Model (ajuste conforme seu framework se necessário)
		// Se estiver dentro do Controller Financeiro, geralmente é $this->financeiroModel ou similar
		$relatorio = $this->model->getFluxoAnualPorCategorias($igrejaId, $ano);
		$contas = $this->model->getContasBancarias($igrejaId);
		$mesesNomes = ['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];

		// Nome do arquivo
		$filename = "Relatorio_Anual_" . $ano . ".xls";

		// Cabeçalhos para Download
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		// Força o UTF-8 para evitar problemas com acentos (ç, ã, é)
		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";

		// Estilos Básicos para o Excel
		echo "<style>
				.header-receita { background-color: #198754; color: #ffffff; font-weight: bold; }
				.header-despesa { background-color: #dc3545; color: #ffffff; font-weight: bold; }
				.subcategoria { color: #666666; font-style: italic; }
				.total-linha { font-weight: bold; }
			  </style>";

		// --- TABELA 1: RECEITAS ---
		echo "<table border='1'>
				<tr><th colspan='14' class='header-receita'>DETALHAMENTO DE RECEITAS ANUAL - $ano</th></tr>
				<tr>
					<th>RECEITAS</th>";
					foreach($mesesNomes as $m) echo "<th>$m</th>";
		echo "      <th>TOTAL GERAL</th>
				</tr>";

		if(isset($relatorio['entrada'])) {
			foreach($relatorio['entrada'] as $cat) {
				$totalAnualCat = array_sum($cat['meses']);
				echo "<tr>
						<td style='font-weight:bold; background-color: #f8fff9;'>{$cat['nome']}</td>";
						foreach($cat['meses'] as $valor) {
							echo "<td align='right'>" . number_format($valor, 2, ',', '.') . "</td>";
						}
				echo "  <td align='right' class='total-linha'>" . number_format($totalAnualCat, 2, ',', '.') . "</td>
					  </tr>";

				// Subcategorias
				foreach($cat['subcategorias'] as $sub) {
					echo "<tr>
							<td class='subcategoria'>&nbsp;&nbsp;&nbsp;{$sub['nome']}</td>";
							foreach($sub['meses'] as $vSub) {
								echo "<td align='right' style='color:#666; font-size: 0.9em;'>" . number_format($vSub, 2, ',', '.') . "</td>";
							}
					echo "  <td align='right' style='color:#666;'>" . number_format(array_sum($sub['meses']), 2, ',', '.') . "</td>
						  </tr>";
				}
			}
		}
		echo "</table><br>";

		// --- TABELA 2: DESPESAS ---
		echo "<table border='1'>
				<tr><th colspan='14' class='header-despesa'>DETALHAMENTO DE DESPESAS ANUAL - $ano</th></tr>
				<tr>
					<th>DESPESAS</th>";
					foreach($mesesNomes as $m) echo "<th>$m</th>";
		echo "      <th>TOTAL GERAL</th>
				</tr>";

		if(isset($relatorio['saida'])) {
			foreach($relatorio['saida'] as $cat) {
				$totalAnualCat = array_sum($cat['meses']);
				echo "<tr>
						<td style='font-weight:bold; background-color: #fff9f9;'>{$cat['nome']}</td>";
						foreach($cat['meses'] as $valor) {
							echo "<td align='right'>" . number_format($valor, 2, ',', '.') . "</td>";
						}
				echo "  <td align='right' class='total-linha'>" . number_format($totalAnualCat, 2, ',', '.') . "</td>
					  </tr>";

				// Subcategorias
				foreach($cat['subcategorias'] as $sub) {
					echo "<tr>
							<td class='subcategoria'>&nbsp;&nbsp;&nbsp;{$sub['nome']}</td>";
							foreach($sub['meses'] as $vSub) {
								echo "<td align='right' style='color:#666; font-size: 0.9em;'>" . number_format($vSub, 2, ',', '.') . "</td>";
							}
					echo "  <td align='right' style='color:#666;'>" . number_format(array_sum($sub['meses']), 2, ',', '.') . "</td>
						  </tr>";
				}
			}
		}
		echo "</table><br>";

		// --- TABELA 3: SALDO POR CONTA ---
		echo "<table border='1'>
				<tr><th colspan='3' style='background-color:#343a40; color:#ffffff;'>DISPONIBILIDADE POR CONTA</th></tr>
				<tr><th>Conta</th><th>Tipo</th><th>Saldo Atual</th></tr>";
		foreach($contas as $c) {
			echo "<tr>
					<td>{$c['financeiro_conta_financeira_nome']}</td>
					<td>{$c['financeiro_conta_financeira_tipo']}</td>
					<td align='right'>R$ " . number_format($c['financeiro_conta_financeira_saldo'], 2, ',', '.') . "</td>
				  </tr>";
		}
		echo "</table>";

		exit;
	}

	public function exportar_extrato() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Pega as datas da URL (enviadas pelo JS) ou define um padrão
		$dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
		$dataFim = $_GET['data_fim'] ?? date('Y-m-t');

		// Busca os dados usando o método que você já tem no Model
		$movimentacoes = $this->model->getMovimentacoesPorPeriodo($igrejaId, $dataInicio, $dataFim);

		$filename = "Extrato_Financeiro_" . $dataInicio . "_a_" . $dataFim . ".xls";

		// Configuração do Header para Excel
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";

		echo "<table border='1'>";
		echo "<tr><th colspan='6' style='background-color:#0d6efd; color:white;'>EXTRATO DE MOVIMENTAÇÕES (" . date('d/m/Y', strtotime($dataInicio)) . " até " . date('d/m/Y', strtotime($dataFim)) . ")</th></tr>";
		echo "<tr style='background-color:#f8f9fa;'>
				<th>Data</th>
				<th>Conta/Caixa</th>
				<th>Descrição</th>
				<th>Origem</th>
				<th>Entrada (R$)</th>
				<th>Saída (R$)</th>
			  </tr>";

		if (!empty($movimentacoes)) {
			foreach ($movimentacoes as $mov) {
				$data = date('d/m/Y H:i', strtotime($mov['financeiro_movimentacao_data']));
				$conta = $mov['financeiro_conta_financeira_nome'];
				$desc = $mov['financeiro_movimentacao_descricao'];
				$origem = strtoupper($mov['financeiro_movimentacao_origem']);

				$entrada = ($mov['financeiro_movimentacao_tipo'] == 'entrada') ? number_format($mov['financeiro_movimentacao_valor'], 2, ',', '.') : '-';
				$saida = ($mov['financeiro_movimentacao_tipo'] == 'saida') ? number_format($mov['financeiro_movimentacao_valor'], 2, ',', '.') : '-';

				echo "<tr>
						<td>$data</td>
						<td>$conta</td>
						<td>$desc</td>
						<td align='center'><small>$origem</small></td>
						<td align='right' style='color:green;'>$entrada</td>
						<td align='right' style='color:red;'>$saida</td>
					  </tr>";
			}
		} else {
			echo "<tr><td colspan='6' align='center'>Nenhuma movimentação no período.</td></tr>";
		}
		echo "</table>";
		exit;
    }

	// Método para exibir a página no celular via QRCode
    public function uploadExterno($id) {
        $idIgreja = $_SESSION['usuario_igreja_id'] ?? $_GET['i'] ?? null;

        if (!$idIgreja) {
            die("Erro: Identificação da igreja ausente.");
        }

        $conta = $this->model->getContaById($id, $idIgreja);

        // Use a instância que você criou no construtor
        $igreja = $this->igrejaModel->getById($idIgreja);

        if (!$conta || $conta['financeiro_conta_igreja_id'] != $idIgreja) {
            die("Lançamento não encontrado ou acesso negado.");
        }

        $this->rawview('financeiro/upload_mobile', [
            'conta' => $conta,
            'igreja' => $igreja,
            'tipo' => $_GET['tipo'] ?? 'comprovante',
            'idIgreja' => $idIgreja
        ]);
    }

    public function getRateio($id) {
        $model = new Financeiro();
        $rateio = $model->getRateioLancamento($id);
        echo json_encode($rateio);
        exit;
    }

    public function getContaJson($id) {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $dados = $this->model->getContaById($id, $igrejaId);
        echo json_encode($dados);
        exit;
    }

	// Upload para MEMBRO (Rateio)
	public function uploadAnexoMembro() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$idIgreja = $_SESSION['usuario_igreja_id'] ?? $_POST['igreja_id'];
			$contaId = $_POST['conta_id'];
			$membroId = $_POST['membro_id']; // ID da linha na tabela financeiro_receita_membros
			$ano = $_POST['ano_referencia'] ?? date('Y');
			$mes = str_pad($_POST['mes_referencia'] ?? date('m'), 2, "0", STR_PAD_LEFT);

			if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === 0) {

				// 1. BUSCAR ARQUIVO ANTIGO PARA DELETAR (Opcional, mas boa prática)
				$membroDados = $this->model->getMembroRateioById($membroId);
				$arquivoAntigo = $membroDados['receita_membro_comprovante'] ?? null;

				$extensaoOriginal = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
				$isImagem = in_array($extensaoOriginal, ['jpg', 'jpeg', 'png', 'webp', 'jfif']);
				$extensaoFinal = $isImagem ? 'jpg' : $extensaoOriginal;

				// Nome do arquivo identifica o membro para facilitar a gestão física
				$novoNome = "membro_{$membroId}_" . time() . "_" . rand(1000, 9999) . "." . $extensaoFinal;

				$raizProjeto = dirname(__DIR__, 2);
				$diretorioDestino = $raizProjeto . "/public/assets/uploads/{$idIgreja}/financeiro/comprovantes/{$ano}/{$mes}/";
				$caminhoCompleto = $diretorioDestino . $novoNome;

				if (!is_dir($diretorioDestino)) {
					mkdir($diretorioDestino, 0777, true);
				}

				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoCompleto)) {
					if ($isImagem) {
						Utils::otimizarImagem($caminhoCompleto, $caminhoCompleto, 1000, 80);
					}

					$caminhoRelativo = "{$idIgreja}/financeiro/comprovantes/{$ano}/{$mes}/{$novoNome}";

					if ($this->model->atualizarComprovanteMembro($membroId, $caminhoRelativo)) {
						// Deletar antigo se existir
						if ($arquivoAntigo) {
							$caminhoFisicoAntigo = $raizProjeto . "/public/assets/uploads/" . $arquivoAntigo;
							if (file_exists($caminhoFisicoAntigo)) unlink($caminhoFisicoAntigo);
						}

						// Resposta para o AJAX do modal
						header('Content-Type: application/json');
						echo json_encode(['sucesso' => true, 'caminho' => $caminhoRelativo]);
						exit;
					}
				}
			}
		}
		http_response_code(400);
		echo json_encode(['sucesso' => false, 'erro' => 'Falha no upload']);
		exit;
	}





}
