<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\DizimoOferta;

class DizimoOfertaController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new DizimoOferta();
    }

	public function index()
	{
		// Mantemos a trava de segurança para garantir que uma dupla conferente esteja logada
		if (!isset($_SESSION['conf_diacono_1']) || !isset($_SESSION['conf_diacono_2'])) {
			header("Location: " . url('dizimoOferta/login'));
			exit;
		}

		$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

		if (!$igrejaId) {
			header("Location: " . url('dizimoOferta/login'));
			exit;
		}

		// --- BUSCA OS DADOS DA IGREJA PARA O CABEÇALHO ---
		$igreja = $this->model->getIgrejaDetalhes($igrejaId);

		$mesSelecionado = $_GET['mes'] ?? date('n');
		$anoSelecionado = $_GET['ano'] ?? date('Y');

		// 1. Busca todos os lançamentos de RECEITA da igreja no período
		$lancamentos = $this->model->getLancamentosPorPeriodo(
			$igrejaId,
			$mesSelecionado,
			$anoSelecionado
		);

		// 2. Lista de anos para o filtro lateral (últimos 5 anos)
		$anosDisponiveis = [];
		for($i = date('Y'); $i >= date('Y')-5; $i--) {
			$anosDisponiveis[] = ['ano' => $i];
		}

		// 3. Carrega Categorias e Subcategorias para o Select do Modal
		$categorias = $this->model->getCategoriasSubcategoriasReceita($igrejaId);

		// 4. Carrega membros ativos para o rateio opcional
		$membros = $this->model->getMembrosAtivos($igrejaId);

		// 5. Carrega contas financeiras (Banco do Brasil, Caixinha, etc)
        $contas_bancarias = $this->model->getContasFinanceiras($igrejaId);

        // 6. Buscar os rateiros dos lançamentos(se tiver)
        $lancamentos = $this->model->getLancamentosPorPeriodo($igrejaId, $mesSelecionado, $anoSelecionado);
		foreach ($lancamentos as &$l) {
			// Busca se existem membros rateados para este lançamento
			$l['membros'] = $this->model->getMembrosRateio($l['financeiro_conta_id']);
		}
		unset($l);


		return $this->rawview('dizimosofertas/index', [
			'igreja'           => $igreja,
			'lancamentos'      => $lancamentos,
			'categorias'       => $categorias,
			'membros'          => $membros,
			'contas_bancarias' => $contas_bancarias,
			'mesSelecionado'   => $mesSelecionado,
			'anoSelecionado'   => $anoSelecionado,
			'anosDisponiveis'  => $anosDisponiveis,
			// Os dados dos diáconos (incluindo fotos) já estão na sessão após o login
			'diacono1'         => $_SESSION['conf_diacono_1']['nome'],
			'diacono2'         => $_SESSION['conf_diacono_2']['nome']
		]);
	}

    public function login()
	{
		// Limpa qualquer sessão prévia de diaconos ao acessar a tela de login
		unset($_SESSION['conf_diacono_1']);
		unset($_SESSION['conf_diacono_2']);

		// Pega o ID da sessão ou via GET (caso venha de uma seleção de canais)
		$igrejaId = $_GET['igreja'] ?? $_SESSION['usuario_igreja_id'] ?? 1;

		// Busca os dados (Nome, Logo, Endereço) para a Identidade Visual do Login
		$igreja = $this->model->getIgrejaDetalhes($igrejaId);

		// Se a igreja não existir, redireciona ou define um padrão para não quebrar
		if (!$igreja) {
			die("Igreja não encontrada.");
		}

		return $this->rawview('dizimosofertas/login', [
			'igreja' => $igreja // Enviando o array completo para a View
		]);
	}

	public function autenticarPrimeiro()
	{
		header('Content-Type: application/json');

		$igrejaId = $_POST['igreja_id'] ?? null;
		$user = $_POST['user1'] ?? '';
		$pass = $_POST['pass1'] ?? '';

		if (!$igrejaId || empty($user) || empty($pass)) {
			echo json_encode(['success' => false, 'message' => 'Informe usuário e senha.']);
			exit;
		}

		$oficial1 = $this->model->autenticarOficial($user, $pass, $igrejaId);

		if ($oficial1) {
			$_SESSION['usuario_igreja_id'] = $igrejaId;
			$_SESSION['conf_diacono_1'] = [
				'id'       => $oficial1['membro_id'],
				'nome'     => $oficial1['membro_nome'],
				'registro' => $oficial1['membro_registro_interno'],
				'foto'     => $oficial1['membro_foto_arquivo']
			];

			echo json_encode([
				'success' => true,
				'nome'    => $oficial1['membro_nome']
			]);
			exit;
		}

		echo json_encode(['success' => false, 'message' => 'Credenciais inválidas ou cargo sem permissão.']);
		exit;
	}

	public function autenticarSegundo()
	{
		header('Content-Type: application/json');

		if (!isset($_SESSION['conf_diacono_1'])) {
			echo json_encode(['success' => false, 'message' => 'Sessão do 1º Diácono expirada. Reinicie a autenticação.']);
			exit;
		}

		$igrejaId = $_POST['igreja_id'] ?? $_SESSION['usuario_igreja_id'];
		$user = $_POST['user2'] ?? '';
		$pass = $_POST['pass2'] ?? '';

		if (!$igrejaId || empty($user) || empty($pass)) {
			echo json_encode(['success' => false, 'message' => 'Informe usuário e senha.']);
			exit;
		}

		$oficial2 = $this->model->autenticarOficial($user, $pass, $igrejaId);

		if ($oficial2) {
			// Impede que o 2º oficial seja a mesma pessoa do 1º
			if ($_SESSION['conf_diacono_1']['id'] === $oficial2['membro_id']) {
				echo json_encode(['success' => false, 'message' => 'O 2º Diácono deve ser uma pessoa diferente do 1º.']);
				exit;
			}

			$_SESSION['conf_diacono_2'] = [
				'id'       => $oficial2['membro_id'],
				'nome'     => $oficial2['membro_nome'],
				'registro' => $oficial2['membro_registro_interno'],
				'foto'     => $oficial2['membro_foto_arquivo']
			];

			echo json_encode([
				'success'  => true,
				'redirect' => url('dizimoOferta')
			]);
			exit;
		}

		echo json_encode(['success' => false, 'message' => 'Credenciais inválidas ou cargo sem permissão.']);
		exit;
	}

	// Arquivo: App/Controllers/DizimoOfertaController.php

	public function salvar()
	{
		header('Content-Type: application/json');

		if (!isset($_POST['categoria_sub_id']) || empty($_POST['categoria_sub_id'])) {
			echo json_encode(['success' => false, 'message' => 'Selecione a categoria/subcategoria.']);
			exit;
		}

		$ids = explode('-', $_POST['categoria_sub_id']);

		$data = [
			'igreja_id'           => $_SESSION['usuario_igreja_id'],
			'categoria_id'        => $ids[1],
			'subcategoria_id'     => $ids[1],
			'categoria_pai_id'    => $ids[0],
			'conta_financeira_id' => $_POST['conta_financeira_id'] ?? null,
			'descricao'           => $_POST['descricao'] ?? '',
			'valor'               => $_POST['valor'] ?? 0,
			'data_pagamento'      => $_POST['data_pagamento'] ?? date('Y-m-d'),
			'diacono_1'           => $_SESSION['conf_diacono_1']['id'],
			'diacono_2'           => $_SESSION['conf_diacono_2']['id'],
			'rateio_membros'      => $_POST['rateio_membro'] ?? [],
			'rateio_valores'      => $_POST['rateio_valor'] ?? []
		];

		$sucesso = $this->model->salvarLancamentoCompleto($data);

		if ($sucesso) {
			echo json_encode(['success' => true, 'message' => 'Lançamento em lote salvo com sucesso!']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Falha ao salvar lançamento em lote.']);
		}
		exit;
	}

	// Arquivo: App/Controllers/DizimoOfertaController.php
	// Linha: Localize o método salvarIndividual

	public function salvarIndividual(){
		header('Content-Type: application/json');

		$membroId = $_POST['membro_id'] ?? null;
		if (!$membroId) {
			echo json_encode(['success' => false, 'message' => 'Selecione um membro.']);
			exit;
		}

		$ofertaSubCat = null;
		if (!empty($_POST['oferta_categoria_sub_id'])) {
			$partes = explode('-', $_POST['oferta_categoria_sub_id']);
			$ofertaSubCat = $partes[1] ?? null;
		}

		$data = [
			'igreja_id'              => $_SESSION['usuario_igreja_id'],
			'membro_id'              => $membroId,
			'data_pagamento'         => $_POST['data_pagamento'] ?? date('Y-m-d'),
			'dizimo_valor'           => $_POST['dizimo_valor'] ?? 0,
			'dizimo_conta_id'        => $_POST['dizimo_conta_id'] ?? null,
			'dizimo_categoria_id'    => 18, // ID da categoria "Culto - Dizimo e Ofertas"
			'dizimo_subcategoria_id' => 14, // ID da subcategoria "Dizimo"
			'oferta_valor'           => $_POST['oferta_valor'] ?? 0,
			'oferta_conta_id'        => $_POST['oferta_conta_id'] ?? null,
			'oferta_subcategoria_id' => $ofertaSubCat,
			'diacono_1'              => $_SESSION['conf_diacono_1']['id'],
			'diacono_2'              => $_SESSION['conf_diacono_2']['id']
		];

		$sucesso = $this->model->salvarLancamentoIndividualCompleto($data);

		if ($sucesso) {
			echo json_encode(['success' => true, 'message' => 'Lançamento individual realizado com sucesso!']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erro ao salvar o lançamento individual.']);
		}
		exit;
	}

	// Arquivo: App/Controllers/DizimoOfertaController.php
	// Linha: Adicione o método abaixo dentro do Controller DizimoOfertaController

	public function excluirLancamento() {
		header('Content-Type: application/json');

		$contaId = $_POST['id'] ?? null;
		$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

		if (!$contaId || !$igrejaId) {
			echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']);
			exit;
		}

		$resultado = $this->model->excluirLancamento($contaId, $igrejaId);
		echo json_encode($resultado);
		exit;
	}

    public function sair()
    {
        unset($_SESSION['conf_diacono_1'], $_SESSION['conf_diacono_2']);
        header("Location: " . url('dizimoOferta/login'));
    }

	public function imprimir() {
		$data = $_GET['data'] ?? date('Y-m-d');
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// 1. Dados da Igreja para o cabeçalho (Nome, Logo, Endereço)
		$igreja = $this->model->getIgrejaDetalhes($igrejaId);

		// 2. Resumo total por subcategoria (Dízimos, Ofertas, etc)
		$resumo = $this->model->getResumoConferencia($igrejaId, $data);

		// 3. Detalhes de quem deu (Rateio)
		$rateio = $this->model->getRateioConferencia($igrejaId, $data);

		// 4. Busca o nome do tesoureiro da igreja
		$tesoureiro = $this->model->getTesoureiroIgreja($igrejaId);

		// 5. Oficiais que realizaram a conferência (dados da sessão)
		$oficiais = [
			'd1' => $_SESSION['conf_diacono_1']['nome'] ?? 'Não informado',
			'd2' => $_SESSION['conf_diacono_2']['nome'] ?? 'Não informado'
		];

		// 6. Cálculo para o Relatório (Diferença entre total lançado e identificado)
		$somaIdentificada = 0;
		foreach($rateio as $item) {
			$somaIdentificada += (float)$item['valor'];
		}

		$totalGeral = 0;
		foreach($resumo as $r) {
			$totalGeral += (float)$r['total'];
		}

		// Valor que entrou mas não foi atrelado a nenhum membro (ex: salva/envelope sem nome)
		$valorAvulso = $totalGeral - $somaIdentificada;

		// 7. Renderização da View de Impressão (passando a variável $igreja)
		$this->rawview('dizimosofertas/conferencia_impressao', [
			'data'             => $data,
			'igreja'           => $igreja, // Novos dados aqui
			'resumo'           => $resumo,
			'rateio'           => $rateio,
			'oficiais'         => $oficiais,
			'tesoureiro'       => $tesoureiro,
			'totalGeral'       => $totalGeral,
			'valorAvulso'      => $valorAvulso,
			'somaIdentificada' => $somaIdentificada
		]);
	}

	public function uploadAnexo() {
		// Segurança: Verifica se a conferência está ativa
		if (!isset($_SESSION['conf_diacono_1']) || !isset($_SESSION['conf_diacono_2'])) {
			header("Location: " . url('dizimoOferta/login'));
			exit;
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$idIgreja = $_SESSION['usuario_igreja_id'];
			$contaId = $_POST['conta_id'];
			$receitaMembroId = $_POST['receita_membro_id'] ?? null; // Captura ID do rateio
			$tipo = $_POST['tipo_arquivo'];
			$ano = $_POST['ano_referencia'];
			$mes = str_pad($_POST['mes_referencia'], 2, "0", STR_PAD_LEFT);

			if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === 0) {

				// --- 1. BUSCA O ARQUIVO ANTIGO PARA DELETAR ---
				if (!empty($receitaMembroId)) {
					$item = $this->model->getRateioById($receitaMembroId);
					$arquivoAntigo = $item['receita_membro_comprovante'] ?? null;
				} else {
					$conta = $this->model->getContaById($contaId, $idIgreja);
					$coluna = ($tipo === 'comprovante') ? 'financeiro_conta_comprovante' : 'financeiro_conta_nota_fiscal';
					$arquivoAntigo = $conta[$coluna] ?? null;
				}

				$ext = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
				$isImagem = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);

				// Nomeclatura: Se for rateio, identificamos no nome do arquivo
				$prefixo = !empty($receitaMembroId) ? "membro_{$receitaMembroId}_" : "{$tipo}_";
				$novoNome = $prefixo . time() . "_" . rand(1000, 9999) . "." . ($isImagem ? 'jpg' : $ext);

				$raiz = dirname(__DIR__, 2);
				$subPasta = ($tipo === 'comprovante') ? 'comprovantes' : 'notasfiscais';
				$diretorio = $raiz . "/public/assets/uploads/{$idIgreja}/financeiro/{$subPasta}/{$ano}/{$mes}/";

				if (!is_dir($diretorio)) mkdir($diretorio, 0777, true);

				if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio . $novoNome)) {
					if ($isImagem) {
						\App\Core\Utils::otimizarImagem($diretorio . $novoNome, $diretorio . $novoNome, 1000, 80);
					}

					$caminhoRelativo = "{$idIgreja}/financeiro/{$subPasta}/{$ano}/{$mes}/{$novoNome}";

					// --- 2. ATUALIZAÇÃO DO BANCO ---
					if (!empty($receitaMembroId)) {
						$sucesso = $this->model->atualizarComprovanteRateio($receitaMembroId, $caminhoRelativo);
					} else {
						$sucesso = $this->model->atualizarAnexoFinanceiro($contaId, $idIgreja, $tipo, $caminhoRelativo);
					}

					if ($sucesso) {
						// Remove arquivo antigo se existir
						if ($arquivoAntigo) {
							$fisicoAntigo = $raiz . "/public/assets/uploads/" . $arquivoAntigo;
							if (file_exists($fisicoAntigo)) unlink($fisicoAntigo);
						}
						header("Location: " . url("dizimoOferta/index?mes={$mes}&ano={$ano}&sucesso=1"));
					} else {
						die("Erro ao salvar no banco.");
					}
					exit;
				}
			}
		}
		header("Location: " . url("dizimoOferta/index?erro=1"));
		exit;
	}

}
