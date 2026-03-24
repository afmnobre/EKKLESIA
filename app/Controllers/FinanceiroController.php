<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Financeiro;

class FinanceiroController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new Financeiro();
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

		// 1. Pega as contas para a tabela (usando a query com JOIN que você postou)
		$contas = $this->model->getContasAgendadas($igrejaId);

		// 2. Pega as categorias agrupadas para o COMBO do Modal
		$categoriasAgrupadas = $this->model->getCategoriasAgrupadas($igrejaId);

		// 3. Pega as contas bancárias para o Modal de Baixa
        $contasBancarias = $this->model->getContasBancarias($igrejaId);

        // 4. Carrega os membros para rateiro das receitas.
		$membros = $this->model->getMembrosAtivos($igrejaId); // Crie este método se não existir

		$this->view('financeiro/lancamentos', [
			'contas_agendadas'     => $contas,
			'categorias_agrupadas' => $categoriasAgrupadas, // <-- O combo usa esta aqui
            'contas_bancarias'     => $contasBancarias,
			'membros'              => $membros // <--- Envie para a view aqui
		]);
	}

	// Aproveite e crie a rota para salvar o agendamento que o modal envia
	public function salvar_conta_agendada() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// DEBUG: Se quiser ter certeza, descomente a linha abaixo e tente salvar.
			// die("Recebi o ID: " . $_POST['subcategoria_id']);

			$data = [
				'id'           => $_POST['id'] ?? null,
				'igreja_id'    => $_SESSION['usuario_igreja_id'],
				'categoria_id' => $_POST['subcategoria_id'], // O nome deve ser IDÊNTICO ao 'name' do select no HTML
				'descricao'    => $_POST['descricao'],
				'valor'        => $_POST['valor'],
				'tipo'         => $_POST['tipo'],
				'vencimento'   => $_POST['vencimento'],
				'pago'         => 0
			];

			$this->model->salvarConta($data);
			header("Location: " . url('financeiro/lancamentos') . "?sucesso=agendado");
			exit;
		}
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
		$anoAtual = date('Y'); // Definimos o ano para o relatório anual

		$resumo = $this->model->getResumoMes($igrejaId);
		$contas = $this->model->getContasBancarias($igrejaId);
		$dadosRaw = $this->model->getDadosGraficoLinha($igrejaId);

		// 1. CHAMADA DO NOVO RELATÓRIO DE CATEGORIAS
		// Certifique-se de que o método 'getFluxoAnualPorCategorias' está no seu Model
		$relatorioCategorias = $this->model->getFluxoAnualPorCategorias($igrejaId, $anoAtual);

// ADICIONE ISSO PARA TESTAR:
//var_dump($relatorioCategorias); die();



		// Criamos um esqueleto com os 12 meses zerados para o gráfico de linha
		$dadosGrafico = array_fill(1, 12, ['entradas' => 0, 'saidas' => 0]);

		// Preenchemos com o que veio do banco para o gráfico
		foreach ($dadosRaw as $d) {
			$dadosGrafico[(int)$d['mes']] = [
				'entradas' => (float)$d['entradas'],
				'saidas' => (float)$d['saidas']
			];
		}

		// 2. ADICIONE 'relatorio' AO ARRAY DA VIEW
		$this->view('financeiro/dashboard', [
			'resumo'    => $resumo,
			'contas'    => $contas,
			'fluxoAnual' => $dadosGrafico,
			'relatorio' => $relatorioCategorias // Esta é a variável que a View estava sentindo falta
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


}
