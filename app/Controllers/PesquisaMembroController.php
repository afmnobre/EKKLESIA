<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PesquisaMembro;

class PesquisaMembroController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new PesquisaMembro();
    }

	public function index() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$filtros = $_GET;

		// Se houver filtros ou se quiser carregar tudo ao abrir
		$membros = $this->model->pesquisar($igrejaId, $filtros);

		$dados = [
			'membros'      => $membros,
			'totalMembros' => count($membros), // Contagem dos filtrados
			'sociedades'   => $this->model->getSociedades($igrejaId),
			'cargos'       => $this->model->getCargos(),
			'classes'      => $this->model->getClassesEBD($igrejaId),
			'cidades'      => $this->model->getCidadesCadastradas($igrejaId),
			'filtros'      => $filtros
		];

		$this->view('pesquisas/membros', $dados);
	}

	// Método para o AJAX buscar bairros
	public function buscarBairros() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$cidade = $_GET['cidade'] ?? '';
		$bairros = $this->model->getBairrosPorCidade($igrejaId, $cidade);
		echo json_encode($bairros);
		exit;
	}

	public function exportarExcel() {
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$filtros = $_GET; // Os parâmetros vêm do JS via URL

		$membros = $this->model->pesquisar($igrejaId, $filtros);

		$filename = "Relatorio_Membros_" . date('d-m-Y_H-i') . ".xls";

		// Configuração do Header para Excel (Padrão que funcionou no seu financeiro)
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";

		echo "<table border='1'>";
		echo "<tr><th colspan='8' style='background-color:#0d6efd; color:white; font-size:16px;'>RELATÓRIO DE MEMBROS FILTRADOS</th></tr>";
		echo "<tr style='background-color:#f8f9fa;'>
				<th>Nome</th>
				<th>ROL</th>
				<th>Gênero</th>
				<th>Cargos</th>
				<th>Sociedades</th>
				<th>Classe EBD</th>
				<th>Telefone</th>
				<th>Cidade/Bairro</th>
			  </tr>";

		if (!empty($membros)) {
			foreach ($membros as $m) {
				$cargos = $m['cargos_nomes'] ?? 'Sem Cargo';
				$sociedades = $m['sociedades_nomes'] ?? 'Sem Sociedade';
				$classes = $m['classes_nomes'] ?? 'Não Matriculado';
				$local = $m['membro_endereco_cidade'] . " / " . $m['membro_endereco_bairro'];

				echo "<tr>
						<td>{$m['membro_nome']}</td>
						<td align='center'>{$m['membro_registro_interno']}</td>
						<td>{$m['membro_genero']}</td>
						<td><small>$cargos</small></td>
						<td><small>$sociedades</small></td>
						<td><small>$classes</small></td>
						<td>{$m['membro_telefone']}</td>
						<td>$local</td>
					  </tr>";
			}
		} else {
			echo "<tr><td colspan='8' align='center'>Nenhum membro encontrado.</td></tr>";
		}
		echo "</table>";
		exit;
	}

	public function perfil($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Busca os dados do membro
		$membro = $this->model->getByIdCompleto($id, $igrejaId);

		if (!$membro) {
			header("Location: " . url('pesquisas/membros'));
			exit;
		}

		// --- ADICIONADO: Busca os dados da igreja para pegar a logo ---
		// Ajuste o nome do model se for diferente no seu sistema (ex: IgrejaModel)
		$igrejaModel = new \App\Models\Igreja();
		$igreja = $igrejaModel->getByIgreja($igrejaId);

		$data = [
			'titulo'     => 'Perfil: ' . $membro['membro_nome'],
			'membro'     => $membro,
			'igreja'     => $igreja, // Enviando os dados da igreja para a view
			'historicos' => $this->model->getHistorico($id),
            'cargos'     => $this->model->getCargosMembro($id),
            'familia'    => $this->model->getFamilia($id),
			'todos_membros' => $this->model->getAllShort($_SESSION['usuario_igreja_id'], $id), // Lista simples para o select, excluindo o próprio membro
		];

		$this->view('pesquisas/perfil', $data);
	}

	public function vincularParente() {
		if($_POST) {
			$dados = [
				'resp' => $_POST['responsavel_id'],
				'dep'  => $_POST['dependente_id'],
				'grau' => $_POST['grau']
			];
			$this->model->vincularParente($dados);
			header("Location: " . $_SERVER['HTTP_REFERER']);
		}
	}

}
