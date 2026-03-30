<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\PortalMembro;
use App\Core\Utils;

class PortalMembroController extends Controller {

    public function cadastro($idIgreja) {
        $model = new PortalMembro();
        $igreja = $model->getIgreja($idIgreja);

        if (!$igreja) die("Igreja inválida.");

        // Usa rawview para não carregar sidebar/nav do admin
        return $this->rawview('membros/cadastro_inicial', [
            'igreja' => $igreja
        ]);
    }

	public function salvar() {
		$model = new \App\Models\PortalMembro();
		$idIgreja = $_POST['igreja_id'];

		$dataBatismo = !empty($_POST['data_batismo']) ? $_POST['data_batismo'] : null;

		$dados = [
			'igreja_id'    => $idIgreja,
			'nome'         => mb_strtoupper($_POST['nome']),
			'email'        => $_POST['email'],
			'senha'        => password_hash($_POST['senha'], PASSWORD_DEFAULT),
			'data_nasc'    => $_POST['data_nasc'],
			'sexo'         => $_POST['sexo'],
			'estado_civil' => $_POST['estado_civil'], // Novo campo adicionado
			'data_batismo' => $dataBatismo,
			'telefone'     => $_POST['telefone'],
			'rua'          => $_POST['rua'],
			'numero'       => $_POST['numero'],
			'bairro'       => $_POST['bairro'],
			'cidade'       => $_POST['cidade'],
			'estado'       => $_POST['estado'] ?? 'SP',
			'cep'          => $_POST['cep']
		];

		$membroId = $model->registrarPendente($dados);

		if ($membroId) {
			if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
				$this->processarFoto($membroId, $idIgreja);
			}

			// Redirecionamento passando o ID da igreja na URL para gerar o link de volta
			$idBase64 = base64_encode($membroId);
			header("Location: " . url("PortalMembro/resumo/{$idBase64}?igreja={$idIgreja}"));
			exit;
		} else {
			die("Erro ao registrar membro. Verifique os logs do banco de dados.");
		}
	}

	public function resumo($idCriptografado) {
		// Decodifica o ID (ex: 'MjMz' vira 233)
		$membroId = base64_decode($idCriptografado);

		$model = new \App\Models\PortalMembro();
		$dadosMembro = $model->getMembroComEndereco($membroId);

		// Se o membro não for encontrado, redireciona para evitar erro de variável nula na view
		if (!$dadosMembro) {
			header("Location: " . url("auth/login"));
			exit;
		}

		// Usando o rawview para abrir sem sidebar/menu
		// Note que passamos apenas 'membros/cadastro_resumo'
		// pois o Core já completa com o caminho das Views/paginas/
		$this->rawview('membros/cadastro_resumo', [
			'm'      => $dadosMembro,
			'titulo' => 'Resumo do Cadastro'
		]);
	}

	private function processarFoto($membroId, $idIgreja) {
		// 1. Instancia o model
		$model = new \App\Models\PortalMembro();

		// 2. Padronizamos o nome para .jpg (visto que o Utils converte tudo para JPG)
		$novoNome = "selfie_" . time() . "_" . $membroId . ".jpg";

		// 3. Define o diretório de destino
		$diretorioDestino = dirname(__DIR__, 2) . "/public/assets/uploads/{$idIgreja}/membros/PENDENTE_{$membroId}/";

		// 4. Cria a pasta se não existir
		if (!is_dir($diretorioDestino)) {
			mkdir($diretorioDestino, 0777, true);
		}

		$caminhoFinal = $diretorioDestino . $novoNome;

		/**
		 * USANDO O UTILS:
		 * Em vez de move_uploaded_file, passamos o caminho temporário
		 * direto para a nossa função de otimização.
		 */
		if (Utils::otimizarImagem($_FILES['foto']['tmp_name'], $caminhoFinal)) {
			// 5. Salva o nome do arquivo processado no banco de dados
			$model->saveFoto($membroId, $novoNome);
		} else {
			// Caso a otimização falhe (ex: arquivo corrompido),
			// podemos registrar um log ou erro se necessário.
			error_log("ERRO EKKLESIA: Falha ao otimizar imagem do membro ID: " . $membroId);
		}
	}

	public function painel() {
		if (!isset($_SESSION['membro_id'])) {
			header("Location: " . url('PortalMembro/login'));
			exit;
		}

		$idMembro = $_SESSION['membro_id'];
		$model = new \App\Models\PortalMembro();

		// O perfil já vai vir com ['presencas'] lá de dentro do Model
		$perfil = $model->getMembroCompleto($idMembro);

		$dados['perfil'] = $perfil;
		$dados['dependentes'] = $model->listarDependentes($idMembro);

		// Busca o nome da igreja para a view
		$igreja = $model->getIgreja($_SESSION['membro_igreja_id']);
		$dados['nomeIgreja'] = $igreja['igreja_nome'] ?? 'EKKLESIA';

		// Organiza as presenças por mês para a tabela da View
		$presencasFormatadas = [];
		if (!empty($perfil['presencas'])) {
			foreach ($perfil['presencas'] as $p) {
				$mes = date('m', strtotime($p['presenca_data']));
				$presencasFormatadas[$mes][] = $p;
			}
		}
		$dados['presencas_mensais'] = $presencasFormatadas;
		$dados['anoAtual'] = date('Y');

		$this->rawview('membros/painel_membro', $dados);
	}

	public function login($idIgreja = null) {
		// 1. Instancia o model
		$model = new \App\Models\PortalMembro();

		// 2. Busca os dados da igreja usando o '1' que veio da URL
		$igreja = $model->getIgreja($idIgreja);

		// 3. Se não achar a igreja, não deixa prosseguir
		if (!$igreja) {
			die("Igreja inválida ou não encontrada.");
		}

		// 4. ENVIA a variável $igreja para a view portal_membro_login
        return $this->rawview('membros/painel_membro_login', ['igreja' => $igreja]);
	}

	public function auth() {
		$telefone = preg_replace('/\D/', '', $_POST['membro_telefone']);
		$senha = $_POST['membro_senha'];

		$model = new \App\Models\PortalMembro();
		$membro = $model->buscarPorTelefone($telefone);

		// DEBUG: Se quiser testar se a senha está batendo, você pode dar um var_dump aqui
		if ($membro && password_verify($senha, $membro['membro_senha'])) {
			$_SESSION['membro_id'] = $membro['membro_id'];
			$_SESSION['membro_igreja_id'] = $membro['membro_igreja_id'];
			$_SESSION['membro_nome'] = $membro['membro_nome'];

			// Redireciona para o método PAINEL dentro deste controller
			header("Location: " . url('PortalMembro/painel')); // Ajustado
		} else {
			// Redireciona de volta para o LOGIN deste controller em caso de erro
			header("Location: " . url('PortalMembro/login?erro=1')); // Ajustado
		}
		exit;
	}

	public function novoDependente() {
		// 1. Segurança: Verifica se está logado
		if (!isset($_SESSION['membro_id'])) {
			header("Location: " . url('PortalMembro/login'));
			exit;
		}

		$idMembro = $_SESSION['membro_id'];
		$idIgreja = $_SESSION['membro_igreja_id'] ?? null;

		$model = new \App\Models\PortalMembro();

		// 2. Busca os dados do perfil do titular (Pai/Mãe)
		$perfil = $model->getMembroCompleto($idMembro);

		// 3. Busca os dados da Igreja usando o método que você já tem
		if ($idIgreja) {
			$dadosIgreja = $model->getIgreja($idIgreja);
			// Injetamos o nome da igreja no array de perfil para a view ler corretamente
			$perfil['igreja_nome'] = $dadosIgreja['igreja_nome'] ?? 'EKKLESIA';
		} else {
			$perfil['igreja_nome'] = 'EKKLESIA';
		}

		$dados['perfil'] = $perfil;
		$dados['titulo'] = "Cadastrar Dependente";

		// 4. Carrega a view de cadastro de dependente
		$this->rawview('membros/painel_membro_dependente', $dados);
	}

	public function salvarDependente() {
		if (!isset($_SESSION['membro_id'])) exit;

		$model = new \App\Models\PortalMembro();

		// Separamos a Cidade e o Estado que vêm no formato "Cidade/UF"
		$localidade = explode('/', $_POST['membro_endereco_cidade']);
		$cidade = trim($localidade[0]);
		$estado = isset($localidade[1]) ? trim($localidade[1]) : '';

		// Montamos o array seguindo EXATAMENTE as chaves que o seu registrarPendente espera
		$dadosParaRegistrar = [
			'igreja_id'    => $_SESSION['membro_igreja_id'],
			'nome'         => strtoupper(trim($_POST['membro_nome'])),
			'data_nasc'    => $_POST['membro_data_nascimento'],
			'sexo'         => $_POST['membro_genero'],
			'estado_civil' => $_POST['membro_estado_civil'], // Adicionado aqui
			'data_batismo' => $_POST['membro_data_batismo'] ?: null,
			'email'        => $_POST['membro_email'] ?? null,
			'senha'        => null, // Dependente não faz login direto (usa o do responsável)
			'telefone'     => $_POST['membro_telefone'] ?? null,
			'rua'          => $_POST['membro_endereco_rua'],
			'numero'       => $_POST['membro_endereco_numero'],
			'bairro'       => $_POST['membro_endereco_bairro'],
			'cidade'       => $cidade,
			'estado'       => $estado,
			'cep'          => $_POST['membro_endereco_cep'] ?? null
		];

		// 1. Chama o seu método robusto de registro
		$dependenteId = $model->registrarPendente($dadosParaRegistrar);

		if ($dependenteId) {
			// 2. Processa a foto (Sua lógica de diretórios PENDENTE_{id})
			if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
				$this->processarFoto($dependenteId, $_SESSION['membro_igreja_id']);
			}

			// 3. Vínculo na tabela membros_responsaveis (Crucial para a aba Família)
			$dadosVinculo = [
				'parentesco_responsavel_id' => $_SESSION['membro_id'],
				'parentesco_dependente_id'  => $dependenteId,
				'parentesco_grau'            => $_POST['parentesco_grau']
			];
			$model->vincularResponsavel($dadosVinculo);

			$_SESSION['mensagem_sucesso'] = "Dependente cadastrado! O administrador revisará os dados.";
		}

		header("Location: " . url('PortalMembro/painel#familia'));
		exit;
	}

	public function logout() {
		// 1. Pegamos o ID da igreja da sessão antes de destruir tudo
		$idIgreja = $_SESSION['membro_igreja_id'] ?? null;

		// 2. Destrói todas as variáveis de sessão
		$_SESSION = array();
		session_destroy();

		// 3. Redireciona para o login da igreja específica ou para a home se não houver ID
		if ($idIgreja) {
			header("Location: " . url("PortalMembro/login/" . $idIgreja));
		} else {
			header("Location: " . url("PortalMembro/login"));
		}
		exit;
	}
}
