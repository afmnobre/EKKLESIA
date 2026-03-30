<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Professor; // Importando o novo Model Professor

class ProfessorController extends Controller {

    public function chamada() {
        if (!isset($_SESSION['professor_classe_id'])) {
            header("Location: " . url('professor/login'));
            exit;
        }

        $model = new Professor();
        $classeId = $_SESSION['professor_classe_id'];
        $dataSelecionada = $_GET['data'] ?? date('Y-m-d');

        $classe = $model->getClasseById($classeId);
        $alunos = $model->getAlunosEPresenca($classeId, $dataSelecionada);

        $this->rawview('professor/chamada_professor', [
            'classe' => $classe,
            'alunos' => $alunos,
            'dataSelecionada' => $dataSelecionada,
            'is_professor' => true
        ]);
    }

    public function salvarPresenca() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $membroId = $_POST['aluno_id'] ?? null;
            $status   = $_POST['status'] ?? null;
            $data     = $_POST['data'] ?? null;
            $classeId = $_POST['classe_id'] ?? null;

            if (!$membroId || $status === null || !$data || !$classeId) {
                echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
                exit;
            }

            $db = \App\Core\Database::getInstance();

            try {
                $stmt = $db->prepare("SELECT presenca_id FROM classes_presencas
                                     WHERE presenca_membro_id = ?
                                     AND presenca_data = ?
                                     AND presenca_classe_id = ?");
                $stmt->execute([$membroId, $data, $classeId]);
                $existente = $stmt->fetch();

                if ($existente) {
                    $sql = "UPDATE classes_presencas SET presenca_status = ? WHERE presenca_id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$status, $existente['presenca_id']]);
                } else {
                    $sql = "INSERT INTO classes_presencas (presenca_membro_id, presenca_classe_id, presenca_data, presenca_status)
                            VALUES (?, ?, ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$membroId, $classeId, $data, $status]);
                }

                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
            exit;
        }
    }

	public function autenticar() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Limpa os dados de entrada
			$celular = preg_replace('/[^0-9]/', '', $_POST['celular'] ?? ''); // Remove parênteses e traços
			$senha   = $_POST['senha'] ?? '';

			if (empty($celular) || empty($senha)) {
				header("Location: " . url('professor/login?erro=vazio'));
				exit;
			}

			$db = \App\Core\Database::getInstance();

			/**
			 * SQL EXPLICADO:
			 * 1. Buscamos na tabela 'classes_escola' (c)
			 * 2. Fazemos JOIN com 'membros' (m) para validar se o celular pertence ao professor daquela classe
			 * 3. Validamos o telefone do membro e a senha da classe
			 */
			$sql = "SELECT c.classe_id, c.classe_nome, c.classe_igreja_id, m.membro_nome
					FROM classes_escola c
					JOIN membros m ON c.classe_professor_id = m.membro_id
					WHERE (m.membro_telefone = ? OR REPLACE(REPLACE(REPLACE(m.membro_telefone, ' ', ''), '-', ''), '(', '') = ?)
					AND c.classe_senha = ?
					LIMIT 1";

			$stmt = $db->prepare($sql);
			// Passamos o celular original e o limpo para aumentar a chance de acerto, mais a senha
			$stmt->execute([$_POST['celular'], $celular, $senha]);
			$classe = $stmt->fetch(\PDO::FETCH_ASSOC);

			if ($classe) {
				// Login com sucesso: Cria a sessão do Professor
				$_SESSION['professor_classe_id'] = $classe['classe_id'];
				$_SESSION['professor_nome']      = $classe['membro_nome'];
				$_SESSION['usuario_igreja_id']   = $classe['classe_igreja_id'];

				// Redireciona para a chamada
				header("Location: " . url('professor/chamada'));
				exit;
			} else {
				// Falha na autenticação
				header("Location: " . url('professor/login?erro=1'));
				exit;
			}
		}

		// Se tentarem acessar a rota via GET, manda pro login
		header("Location: " . url('professor/login'));
		exit;
	}

	public function alunos() {
		$this->verificarSessao();
		$model = new \App\Models\Professor();
		$classeId = $_SESSION['professor_classe_id'];
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$classe = $model->getClasseById($classeId);
		$alunos = $model->getAlunosEPresenca($classeId, date('Y-m-d'));
		$disponiveis = $model->getMembrosDisponiveis($igrejaId, $classe['classe_idade_min'], $classe['classe_idade_max']);

		$this->rawview('professor/alunos_professor', [
			'classe' => $classe,
			'alunos' => $alunos,
			'disponiveis' => $disponiveis
		]);
	}

	public function adicionar_aluno($id) {
		$this->verificarSessao();
		(new \App\Models\Professor())->adicionarMembroAClasse($id, $_SESSION['professor_classe_id']);
		header("Location: " . url('professor/alunos'));
	}

	public function remover_aluno($id) {
		$this->verificarSessao();
		(new \App\Models\Professor())->removerMembroDaClasse($id, $_SESSION['professor_classe_id']);
		header("Location: " . url('professor/alunos'));
	}

	private function verificarSessao() {
		if (!isset($_SESSION['professor_classe_id'])) {
			header("Location: " . url('professor/login'));
			exit;
		}
	}

	public function relatorio() {
		$this->verificarSessao();
		$model = new \App\Models\Professor();

		$mes = $_GET['mes'] ?? date('m');
		$ano = $_GET['ano'] ?? date('Y');
		$classeId = $_SESSION['professor_classe_id'];

		$dados = $model->getRelatorioPresencas($classeId, $mes, $ano);
		$classe = $model->getClasseById($classeId);

		$this->rawview('professor/relatorio_professor', [
			'dados' => $dados,
			'classe' => $classe,
			'mes' => $mes,
			'ano' => $ano
		]);
	}

	// 1. Método para EXIBIR a tela de login
	public function login() {
		// Se o professor já estiver logado, manda direto para a chamada
		if (isset($_SESSION['professor_classe_id'])) {
			header("Location: " . url('professor/chamada'));
			exit;
		}

		// Carrega a view da pasta professor (ajuste o caminho se necessário)
		$this->rawview('professor/login', [
			'is_professor' => true
		]);
	}

	// 2. Método para SAIR (Logout) - Ajustado
	public function logout() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		// Limpa os dados do professor
		unset($_SESSION['professor_classe_id']);
		unset($_SESSION['professor_nome']);
		unset($_SESSION['usuario_igreja_id']);

		// Redireciona para a rota de login que acabamos de criar
		header("Location: " . url('professor/login'));
		exit;
	}

	// Dentro de ProfessorController.php

	public function registrarPresencaAjax() {
		header('Content-Type: application/json');

		try {
			// Pega a igreja da sessão do professor (verifique se o nome da chave é este mesmo)
			$igrejaId = $_SESSION['usuario_igreja_id'] ?? null;
			$classeId = $_POST['classe_id'] ?? null;
			$membroRegistro = $_POST['membro_id'] ?? null;

			if (!$igrejaId || !$classeId || !$membroRegistro) {
				echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
				exit;
			}

			// Instancia o model da Escola Dominical
			$modelEbd = new \App\Models\EscolaDominical();

			// REUTILIZA o método que já existe no seu Model!
			$resultado = $modelEbd->registrarPresencaQRCode($igrejaId, $classeId, $membroRegistro);

			echo json_encode($resultado);

		} catch (\Exception $e) {
			echo json_encode(['status' => 'erro', 'mensagem' => 'Erro: ' . $e->getMessage()]);
		}
		exit;
	}


}
