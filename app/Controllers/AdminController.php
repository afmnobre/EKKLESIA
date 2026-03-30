<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Admin;
use App\Middlewares\AuthMiddleware;

class AdminController extends Controller {

    public function usuarios() {
        AuthMiddleware::handle();

        $model = new Admin();
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $this->rawview('admin/usuarios_lista', [
            'usuarios' => $model->listarUsuarios($igrejaId),
            'perfis'   => $model->getPerfisDisponiveis(),
            'titulo'   => 'Gestão de Acessos'
        ]);
    }

	public function salvar_usuario() {
			// Coletamos os dados do POST
			$dados = [
				'nome'      => $_POST['nome'],
				'email'     => $_POST['email'],
				'senha'     => $_POST['senha'],
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'perfis'    => $_POST['perfis'] ?? [] // Pega o array dos checkboxes
			];

			$adminModel = new Admin();

			if ($adminModel->criarUsuario($dados)) {
				$_SESSION['sucesso'] = "Usuário criado com sucesso!";
			} else {
				$_SESSION['erro'] = "Erro ao criar usuário.";
			}

			header("Location: " . url('admin/usuarios'));
			exit;
		}

	public function perfis() {
		AuthMiddleware::handle();

		$model = new Admin();
		// Usando rawview para renderizar a página de perfis
		$this->rawview('admin/perfis_lista', [
			'perfis' => $model->listarPerfis(),
			'titulo' => 'Configuração de Perfis'
		]);
	}

	public function salvar_perfil() {
		AuthMiddleware::handle();

		$model = new Admin();
		$dados = [
			'nome'      => $_POST['nome'],
			'descricao' => $_POST['descricao']
		];

		if ($model->criarPerfil($dados)) {
			header("Location: " . url('admin/perfis?sucesso=1'));
		} else {
			header("Location: " . url('admin/perfis?erro=1'));
		}
		exit;
	}

	public function editar_perfil() {
		AuthMiddleware::handle();

		$id = $_POST['perfil_id'] ?? null;
		if (!$id) {
			header("Location: " . url('admin/perfis?erro=id_invalido'));
			exit;
		}

		$model = new Admin();
		$dados = [
			'nome'      => $_POST['nome'],
			'descricao' => $_POST['descricao'],
			'status'    => $_POST['status']
		];

		if ($model->atualizarPerfil($id, $dados)) {
			header("Location: " . url('admin/perfis?sucesso=perfil_atualizado'));
		} else {
			header("Location: " . url('admin/perfis?erro=falha_ao_atualizar'));
		}
		exit;
	}

	public function editar_usuario() {

		$id = $_POST['usuario_id'] ?? null;
		if (!$id) {
			header("Location: " . url('admin/usuarios?erro=id_invalido'));
			exit;
		}

		$dados = [
			'nome'      => $_POST['nome'],
			'email'     => $_POST['email'],
			'status'    => $_POST['status'],
			'senha'     => $_POST['senha'] ?? '', // Senha é opcional no update
			'igreja_id' => $_SESSION['usuario_igreja_id'],
			'perfis'    => $_POST['perfis'] ?? []
		];

		$model = new Admin();
		if ($model->atualizarUsuario($id, $dados)) {
			$_SESSION['sucesso'] = "Usuário atualizado com sucesso!";
		} else {
			$_SESSION['erro'] = "Falha ao atualizar usuário.";
		}

		header("Location: " . url('admin/usuarios'));
		exit;
	}

	public function excluir_usuario($id) {
		AuthMiddleware::handle();

		if (!$id) {
			header("Location: " . url('admin/usuarios?erro=id_invalido'));
			exit;
		}

		$model = new Admin();
		$igrejaId = $_SESSION['usuario_igreja_id'];

		if ($model->excluirUsuario($id, $igrejaId)) {
			$_SESSION['sucesso'] = "Usuário removido com sucesso!";
		} else {
			$_SESSION['erro'] = "Não foi possível excluir o usuário.";
		}

		header("Location: " . url('admin/usuarios'));
		exit;
	}

}
