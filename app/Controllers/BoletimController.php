<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Boletim;

class BoletimController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Boletim();
    }

    /**
     * Listagem de Boletins
     */
	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$db = \App\Core\Database::getInstance();

		// 1. Buscar o ID do Pastor Titular da Igreja seguindo sua regra de vínculos
		$sqlPastor = "SELECT m.membro_id
					  FROM membros m
					  INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
					  WHERE m.membro_igreja_id = ?
					  AND v.vinculo_cargo_id = 1
					  LIMIT 1";

		$stPastor = $db->prepare($sqlPastor);
		$stPastor->execute([$igrejaId]);
		$dadosPastor = $stPastor->fetch();
		$pastorId = $dadosPastor['membro_id'] ?? ''; // Se não achar, fica vazio

		// 2. Buscar demais dados (Boletins e Membros para o Select)
		$boletins = $this->model->getAllByIgreja($igrejaId);
		$proximoNumero = $this->model->getUltimoNumero($igrejaId) + 1;

		$stMembros = $db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
		$stMembros->execute([$igrejaId]);
		$membros = $stMembros->fetchAll(\PDO::FETCH_ASSOC);

		$this->view('boletins/index', [
			'boletins'      => $boletins,
			'proximoNumero' => $proximoNumero,
			'membros'       => $membros,
			'pastorId'      => $pastorId // Passando o ID do pastor para a View
		]);
	}

    /**
     * Processar o salvamento (Cadastro)
     */
	public function salvar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dados = [
				'id'        => $_POST['igreja_boletim_id'] ?? null, // Captura o ID para edição
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'numero'    => $_POST['igreja_boletim_num_historico'],
				'data'      => $_POST['igreja_boletim_data'],
				'autor_id'  => $_POST['igreja_boletim_autor_id'],
				'titulo'    => $_POST['igreja_boletim_titulo'],
				'mensagem'  => $_POST['igreja_boletim_mensagem'],
				'status'    => $_POST['igreja_boletim_status'] ?? 'publicado'
			];

			if ($this->model->salvar($dados)) {
				// Redireciona para a listagem
				header('Location: ' . url('boletim/index'));
				exit;
			} else {
				// Opcional: Tratar erro aqui
				die("Erro ao salvar o boletim.");
			}
		}
	}

    /**
     * Excluir Boletim
     */
    public function excluir($id)
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $this->model->excluir($id, $igrejaId);
        header('Location: ' . url('boletim/index'));
        exit;
    }

	public function imprimir($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Busca o boletim específico
		$boletim = $this->model->getById($id, $igrejaId);

		if (!$boletim) {
			die("Boletim não encontrado.");
		}

		// Busca os dados da igreja para o cabeçalho (Nome, Logo, etc)
		$igrejaModel = new \App\Models\Igreja();
		$igreja = $igrejaModel->getById($igrejaId);

		// Busca o nome do autor
		$db = \App\Core\Database::getInstance();
		$st = $db->prepare("SELECT membro_nome FROM membros WHERE membro_id = ?");
		$st->execute([$boletim['igreja_boletim_autor_id']]);
		$autor = $st->fetch();

		$this->rawview('boletins/imprimir', [
			'boletim' => $boletim,
			'igreja'  => $igreja,
			'autor'   => $autor['membro_nome'] ?? 'Não informado'
		]);
	}

}
