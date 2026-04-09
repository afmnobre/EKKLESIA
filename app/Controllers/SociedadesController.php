<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Sociedade;
use App\Core\Utils;


class SociedadesController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Sociedade();
    }

    public function index()
    {
        $idIgreja = $_SESSION['usuario_igreja_id'];
        $sociedades = $this->model->getAll($idIgreja);

        $this->view('sociedades/index', [
            'sociedades' => $sociedades
        ]);
    }

	public function salvar()
	{
		$id = $_POST['sociedade_id'] ?? null;
		$idIgreja = $_SESSION['usuario_igreja_id'];

		$dados = [
			'igreja_id' => $idIgreja,
			'nome'      => $_POST['nome'],
			'tipo'      => $_POST['tipo'],
			'genero'    => $_POST['genero'],
			'idade_min' => $_POST['idade_min'],
			'idade_max' => $_POST['idade_max'],
			'status'    => $_POST['status'] ?? 'Ativo'
		];

		if ($id) {
			// Linha 42: Chama o método que acabamos de criar no Model
			$this->model->update($id, $dados);
			$sucesso = "editado";
		} else {
			$this->model->insert($dados);
			$sucesso = "criado";
		}

		header("Location: " . url("sociedades?sucesso=$sucesso"));
		exit;
	}

    public function vincular()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idIgreja = $_SESSION['usuario_igreja_id'];
            $idMembro = $_POST['membro_id'];
            $idSociedade = $_POST['sociedade_id'];
            $funcao = $_POST['funcao'] ?? 'Sócio';

            if ($this->model->saveVinculo($idIgreja, $idSociedade, $idMembro, $funcao)) {
                header("Location: " . url('membros') . "?sucesso=vinculo_sociedade");
            } else {
                header("Location: " . url('membros') . "?erro=vinculo_falhou");
            }
            exit;
        }
    }

	// Altere a linha 73 para aceitar o ID vindo da rota
	public function gerenciar($id = null)
	{
		// Se não houver ID, redireciona de volta para a lista
		if (!$id) {
			header("Location: " . url('sociedades'));
			exit;
		}

		$idIgreja = $_SESSION['usuario_igreja_id'];

		// 1. Busca os dados da sociedade
		$sociedade = $this->model->getById($id, $idIgreja);

		if (!$sociedade) {
			header("Location: " . url('sociedades?erro=nao_encontrada'));
			exit;
		}

		// 2. Busca membros aptos (Filtro por Gênero e Idade)
		$membrosAptos = $this->model->getMembrosAptos(
			$idIgreja,
			$sociedade['sociedade_genero'],
			$sociedade['sociedade_idade_min'],
			$sociedade['sociedade_idade_max'],
			$id
		);

		// 3. Carrega a view gerenciar.php
        // No seu método gerenciar, altere a última parte para:
        $this->view('sociedades/gerenciar', [
            'sociedade' => $sociedade,
            'membrosAptos' => $membrosAptos
        ]);
	}

	public function buscarAptos($idSociedade)
	{
		// 1. Pega os dados da sociedade para saber as regras (idade/gênero)
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$sociedade = $this->model->getById($idSociedade, $idIgreja);

		if (!$sociedade) {
			echo json_encode([]);
			exit;
		}

		// 2. Busca membros que se encaixam no perfil e marca quem já é sócio
		// Este método deve ser criado no seu Model Sociedade
		$membros = $this->model->getMembrosAptos(
			$idIgreja,
			$sociedade['sociedade_genero'],
			$sociedade['sociedade_idade_min'],
			$sociedade['sociedade_idade_max'],
			$idSociedade
		);

		header('Content-Type: application/json');
		echo json_encode($membros);
		exit;
	}

	public function vincularLote() {
		$idSociedade = $_POST['sociedade_id'];
		$membrosIds = $_POST['membros_ids'] ?? [];
		$idIgreja = $_SESSION['usuario_igreja_id'];

		if ($this->model->saveLoteVinculo($idIgreja, $idSociedade, $membrosIds)) {
			header("Location: " . url("sociedades?sucesso=vinculo_lote"));
		} else {
			header("Location: " . url("sociedades?erro=1"));
		}
		exit;
	}

	public function buscarLideranca($idSociedade)
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// Mapeamento de Sociedade para Cargo de Líder
		// IDs das sociedades no seu banco podem variar, ajuste conforme necessário
		$sociedade = $this->model->getById($idSociedade, $idIgreja);
		$cargoId = 0;

		if (strpos($sociedade['sociedade_nome'], 'UPH') !== false) $cargoId = 18;
		elseif (strpos($sociedade['sociedade_nome'], 'SAF') !== false) $cargoId = 17;
		elseif (strpos($sociedade['sociedade_nome'], 'UMP') !== false) $cargoId = 12;
		elseif (strpos($sociedade['sociedade_nome'], 'UPA') !== false) $cargoId = 13;
		elseif (strpos($sociedade['sociedade_nome'], 'UCP') !== false) $cargoId = 14;

		$membros = $this->model->getMembrosParaLideranca($idIgreja, $cargoId);

		header('Content-Type: application/json');
		echo json_encode(['membros' => $membros, 'cargo_id' => $cargoId]);
		exit;
	}

	public function salvarLider()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$idSociedade = $_POST['sociedade_id'];
		$idMembro = $_POST['membro_id'];

		// 1. Buscamos a sociedade para saber o nome dela e definir o cargo certo
		$sociedade = $this->model->getById($idSociedade, $idIgreja);
		$cargoId = 0;

		if (!$sociedade) {
			echo json_encode(['success' => false, 'message' => 'Sociedade não encontrada.']);
			exit;
		}

		// 2. Mesma lógica que você já usa no buscarLideranca()
		if (strpos($sociedade['sociedade_nome'], 'UPH') !== false) $cargoId = 18;
		elseif (strpos($sociedade['sociedade_nome'], 'SAF') !== false) $cargoId = 17;
		elseif (strpos($sociedade['sociedade_nome'], 'UMP') !== false) $cargoId = 12;
		elseif (strpos($sociedade['sociedade_nome'], 'UPA') !== false) $cargoId = 13;
		elseif (strpos($sociedade['sociedade_nome'], 'UCP') !== false) $cargoId = 14;

		if ($cargoId === 0) {
			echo json_encode(['success' => false, 'message' => 'Não foi possível identificar o cargo para esta sociedade.']);
			exit;
		}

		// 3. Salva no banco (Lembre-se de usar o Model atualizado que limpa os vínculos antigos)
		if ($this->model->salvarLider($idIgreja, $idSociedade, $idMembro, $cargoId)) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erro ao salvar líder no banco de dados.']);
		}
		exit;
	}

	public function salvarLogo()
	{
		$idSociedade = $_POST['sociedade_id'];
		$idIgreja = $_SESSION['usuario_igreja_id'];

		if (isset($_FILES['sociedade_logo']) && $_FILES['sociedade_logo']['error'] === 0) {

			// Forçamos a extensão .jpg pois a Utils::otimizarImagem converte para JPEG
			$novoNome = "logo_" . time() . ".jpg";

			$raizProjeto = dirname(__DIR__, 2);
			$diretorioDestino = $raizProjeto . "/public/assets/uploads/{$idIgreja}/sociedades/{$idSociedade}/logo/";
			$caminhoCompleto = $diretorioDestino . $novoNome;

			if (!is_dir($diretorioDestino)) {
				if (!mkdir($diretorioDestino, 0777, true)) {
					die("Erro: Não foi possível criar o diretório.");
				}
			}

			// 1. Movemos o arquivo original para o destino
			if (move_uploaded_file($_FILES['sociedade_logo']['tmp_name'], $caminhoCompleto)) {

				// 2. Otimizamos a imagem (Redimensiona e reduz peso)
				Utils::otimizarImagem($caminhoCompleto, $caminhoCompleto, 800, 80);

				// 3. Define o caminho relativo para o banco de dados
				$caminhoRelativoBanco = "{$idIgreja}/sociedades/{$idSociedade}/logo/{$novoNome}";

				// 4. Busca e deleta a logo antiga para manter o servidor limpo
				$sociedade = $this->model->getById($idSociedade, $idIgreja);
				if (!empty($sociedade['sociedade_logo'])) {
					$caminhoAntigo = $raizProjeto . "/public/assets/uploads/" . $sociedade['sociedade_logo'];
					if (file_exists($caminhoAntigo)) {
						unlink($caminhoAntigo);
					}
				}

				// 5. Atualiza o banco de dados
				if ($this->model->updateLogo($idSociedade, $idIgreja, $caminhoRelativoBanco)) {
					header("Location: " . url("sociedades?sucesso=logo_atualizado"));
				} else {
					die("Erro ao salvar o caminho no banco de dados.");
				}
				exit;
			} else {
				die("Erro ao mover o arquivo.");
			}
		}

		header("Location: " . url("sociedades?erro=arquivo_invalido"));
		exit;
	}

 	public function banner($id)
	{
		// Chama o método que acabamos de criar no Model
		$dados = $this->model->getDadosBanner($id);

		if (!$dados) {
			header("Location: " . url("sociedades?erro=nao_encontrado"));
			exit;
		}

		// Renderiza a view passando as variáveis separadas
		$this->view('sociedades/banner_builder', [
			'sociedade' => $dados['sociedade'],
			'igreja'    => $dados['sociedade'], // Os dados da igreja estão no mesmo array devido ao JOIN
			'redes'     => $dados['redes'],
			'membros'   => $dados['membros']
		]);
	}

	public function salvar_layout()
	{
		if (ob_get_length()) ob_clean();
		header('Content-Type: application/json');

		try {
			$id = $_POST['id'] ?? null;
            $layoutJson = $_POST['layout'] ?? null;

            if ($layoutJson) {
               // Remove escapes automáticos se o servidor estiver adicionando
                $layoutJson = stripslashes($layoutJson);
                }

			if (!$id || !$layoutJson) {
				throw new \Exception("Dados incompletos (ID ou Layout ausentes).");
			}

			// CHAMADA CORRETA PARA O NOVO MÉTODO
			$atualizou = $this->model->updateLayout($id, $layoutJson);

			if ($atualizou) {
				echo json_encode(['sucesso' => true]);
			} else {
				throw new \Exception("Erro ao salvar no banco de dados.");
			}

		} catch (\Exception $e) {
			echo json_encode(['sucesso' => false, 'mensagem' => $e->getMessage()]);
		}
		exit;
	}

    public function orcamentos()
    {
        // Mantendo o padrão de segurança que você já usa no __construct
        $this->view('sociedades/orcamentos', [
            'titulo_pagina' => 'Orçamentos das Sociedades'
        ]);
    }

}
