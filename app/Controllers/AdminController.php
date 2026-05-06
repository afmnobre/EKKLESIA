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


    /**
     * Listagem de Igrejas
     */
    public function igrejas() {
        AuthMiddleware::handle();

        $model = new Admin();

        $this->rawview('admin/igrejas_lista', [
            'igrejas' => $model->listarIgrejas(),
            'titulo'  => 'Gestão de Igrejas'
        ]);
    }

    /**
     * Salva uma nova igreja
     */
    public function salvar_igreja() {
        AuthMiddleware::handle();

        $dados = [
            'nome'      => $_POST['igreja_nome'],
            'cnpj'      => $_POST['igreja_cnpj'],
            'endereco'  => $_POST['igreja_endereco'],
            'pastor_id' => !empty($_POST['igreja_pastor_id']) ? $_POST['igreja_pastor_id'] : null
        ];

        $model = new Admin();

        if ($model->criarIgreja($dados)) {
            $_SESSION['sucesso'] = "Igreja cadastrada com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar igreja.";
        }

        header("Location: " . url('admin/igrejas'));
        exit;
    }

    /**
     * Atualiza dados de uma igreja existente
     */
    public function editar_igreja() {
        AuthMiddleware::handle();

        $id = $_POST['igreja_id'] ?? null;
        if (!$id) {
            header("Location: " . url('admin/igrejas?erro=id_invalido'));
            exit;
        }

        $dados = [
            'nome'      => $_POST['igreja_nome'],
            'cnpj'      => $_POST['igreja_cnpj'],
            'endereco'  => $_POST['igreja_endereco'],
            'pastor_id' => !empty($_POST['igreja_pastor_id']) ? $_POST['igreja_pastor_id'] : null
        ];

        $model = new Admin();

        if ($model->atualizarIgreja($id, $dados)) {
            $_SESSION['sucesso'] = "Dados da igreja atualizados!";
        } else {
            $_SESSION['erro'] = "Falha ao atualizar igreja.";
        }

        header("Location: " . url('admin/igrejas'));
        exit;
    }


}
