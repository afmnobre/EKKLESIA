<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Igreja;

class IgrejaController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Igreja();
    }

    // LISTAR
	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$igreja = $this->model->getByIgreja($igrejaId);
		$redes = $this->model->getRedesSociais($igrejaId);

		// Buscar lista de membros para o modal de seleção de pastor
		$db = \App\Core\Database::getInstance();
		$stMembros = $db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
		$stMembros->execute([$igrejaId]);
		$membros = $stMembros->fetchAll(\PDO::FETCH_ASSOC);

		// Buscar nome do pastor atual
		$pastorNome = "Não definido";
		if (!empty($igreja['igreja_pastor_id'])) {
			$stP = $db->prepare("SELECT membro_nome FROM membros WHERE membro_id = ?");
			$stP->execute([$igreja['igreja_pastor_id']]);
			$p = $stP->fetch();
			$pastorNome = $p['membro_nome'] ?? "Não definido";
		}

		$this->view('igreja/index', [
			'igreja' => $igreja,
			'redes'  => $redes,
			'membros' => $membros,
			'pastorNome' => $pastorNome
		]);
	}


    // EDITAR FORM
    public function editar()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $igreja = $this->model->getByIgreja($igrejaId);

        $this->view('igreja/editar', [
            'igreja' => $igreja
        ]);
    }

    // SALVAR
    public function atualizar() // Removi o $id = 1 por segurança
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // Validação básica
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . url('igreja'));
            exit;
        }

        $dados = [
            'nome'     => $_POST['nome'] ?? '',
            'cnpj'     => $_POST['cnpj'] ?? '',
            'endereco' => $_POST['endereco'] ?? ''
        ];

        if ($this->model->update($igrejaId, $dados)) {
            // Você pode adicionar uma mensagem de sucesso na sessão aqui
            header("Location: " . url('igreja?sucesso=1'));
        } else {
            header("Location: " . url('igreja/editar?erro=1'));
        }
        exit;
    }

	public function salvarRedeSocial()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = [
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'nome'      => $_POST['rede_nome'],
				'usuario'   => $_POST['rede_usuario'],
				'status'    => $_POST['rede_status'] ?? 'ativo'
			];

			$this->model->addRedeSocial($dados);
			header("Location: " . url('igreja?sucesso_rede=1'));
			exit;
		}
	}

	public function excluirRedeSocial($id)
	{
		$this->model->deleteRedeSocial($id, $_SESSION['usuario_igreja_id']);
		header("Location: " . url('igreja?excluido=1'));
		exit;
	}

	public function salvarPastor()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$igrejaId = $_SESSION['usuario_igreja_id'];
			$membroId = $_POST['pastor_id'] ?: null;

			if ($this->model->updatePastor($igrejaId, $membroId)) {
				header("Location: " . url('igreja?sucesso_pastor=1'));
			} else {
				header("Location: " . url('igreja?erro_pastor=1'));
			}
			exit;
		}
	}

}

