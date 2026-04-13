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

	public function autenticar()
	{
		$igrejaId = $_POST['igreja_id'];

		$oficial1 = $this->model->autenticarOficial($_POST['user1'], $_POST['pass1'], $igrejaId);
		$oficial2 = $this->model->autenticarOficial($_POST['user2'], $_POST['pass2'], $igrejaId);

		if ($oficial1 && $oficial2) {
			if ($oficial1['membro_id'] === $oficial2['membro_id']) {
				header("Location: " . url('dizimoOferta/login?erro=mesmo_usuario'));
				exit;
			}

			$_SESSION['usuario_igreja_id'] = $igrejaId;

			// SALVANDO DADOS COMPLETOS PARA OS AVATARES
			$_SESSION['conf_diacono_1'] = [
				'id'       => $oficial1['membro_id'],
				'nome'     => $oficial1['membro_nome'],
				'registro' => $oficial1['membro_registro_interno'],
				'foto'     => $oficial1['membro_foto_arquivo']
			];

			$_SESSION['conf_diacono_2'] = [
				'id'       => $oficial2['membro_id'],
				'nome'     => $oficial2['membro_nome'],
				'registro' => $oficial2['membro_registro_interno'],
				'foto'     => $oficial2['membro_foto_arquivo']
			];

			header("Location: " . url('dizimoOferta'));
			exit;
		}

		header("Location: " . url('dizimoOferta/login?erro=1'));
	}

	public function salvar()
	{
		// Verifica se os dados básicos chegaram
		if (!isset($_POST['categoria_sub_id']) || empty($_POST['categoria_sub_id'])) {
			header("Location: " . url('dizimoOferta?erro=dados_incompletos'));
			exit;
		}

		// Separa os IDs: $ids[0] é a Categoria Pai, $ids[1] é a Subcategoria
		$ids = explode('-', $_POST['categoria_sub_id']);

		$data = [
			'igreja_id'           => $_SESSION['usuario_igreja_id'],
			/**
			 * IMPORTANTE: Para o Admin exibir "Oferta" ou "Dízimo" corretamente,
			 * passamos o ID da Subcategoria ($ids[1]) para o campo que o Model
			 * usa como categoria principal no banco.
			 */
			'categoria_id'        => $ids[1],
			'subcategoria_id'     => $ids[1],
			'categoria_pai_id'    => $ids[0], // Caso queira manter referência do pai
			'conta_financeira_id' => $_POST['conta_financeira_id'],
			'descricao'           => $_POST['descricao'],
			'valor'               => $_POST['valor'],
			'data_pagamento'      => $_POST['data_pagamento'],
			'diacono_1'           => $_SESSION['conf_diacono_1']['id'],
			'diacono_2'           => $_SESSION['conf_diacono_2']['id'],
			'rateio_membros'      => $_POST['rateio_membro'] ?? [],
			'rateio_valores'      => $_POST['rateio_valor'] ?? []
		];

		// Chama o método completo do Model
		$sucesso = $this->model->salvarLancamentoCompleto($data);

		if ($sucesso) {
			header("Location: " . url('dizimoOferta?sucesso=1'));
		} else {
			header("Location: " . url('dizimoOferta?erro=falha_ao_salvar'));
		}
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

}
