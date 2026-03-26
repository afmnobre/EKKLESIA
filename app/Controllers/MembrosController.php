<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Membro;

class MembrosController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Membro();
    }

    /**
     * Lista principal de membros
     */
	public function index()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$portalModel = new \App\Models\PortalMembro();

		// BUSCA O NOME DA IGREJA PARA EVITAR O WARNING
		$dadosIgreja = $portalModel->getIgreja($idIgreja);
		$nomeIgreja = $dadosIgreja['igreja_nome'] ?? 'EKKLESIA';

		$pendentes = $portalModel->getPendentes($idIgreja);
		$totalPendentes = count($pendentes);

		$membros = $this->model->getAll($idIgreja);
		$todosCargos = $this->model->getTodosCargos();

		// Se não houver membros ativos, enviamos os dados básicos
		if (empty($membros)) {
			return $this->view('membros/index', [
				'membros' => [],
				'todosCargos' => $todosCargos,
				'totalPendentes' => $totalPendentes,
				'nomeIgreja' => $nomeIgreja
			]);
		}

		$idsMembros = array_column($membros, 'membro_id');
		$mapaCargos = array_column($todosCargos, 'cargo_nome', 'cargo_id');
		$todosVinculos = $this->model->getCargosParaVariosMembros($idsMembros);
		$todosHistoricos = $this->model->getHistoricosParaVariosMembros($idsMembros);

		foreach ($membros as &$m) {
			$id = $m['membro_id'];
			$meusCargosIds = $todosVinculos[$id] ?? [];
			$m['cargos_selecionados'] = $meusCargosIds;

			$nomes = [];
			foreach ($meusCargosIds as $cid) {
				if (isset($mapaCargos[$cid])) $nomes[] = $mapaCargos[$cid];
			}
			$m['membro_cargo'] = !empty($nomes) ? implode(', ', $nomes) : 'Membro Comum';
			$m['historicos'] = $todosHistoricos[$id] ?? [];
		}

		$this->view('membros/index', [
			'membros' => $membros,
			'todosCargos' => $todosCargos,
			'totalPendentes' => $totalPendentes,
			'nomeIgreja' => $nomeIgreja // Passando o nome corrigido
		]);
	}

    /**
     * Filtro AJAX para busca e paginação alfabética
     */
    public function filtrar()
    {
        $letra = $_GET['letra'] ?? 'A';
        $busca = $_GET['busca'] ?? '';
        $idIgreja = $_SESSION['usuario_igreja_id'];

        $membros = $this->model->buscarFiltrado($idIgreja, $letra, $busca);

        if (empty($membros)) {
            echo '<tr><td colspan="6" class="text-center py-5 text-muted">Nenhum membro encontrado.</td></tr>';
            return;
        }

        foreach ($membros as $m): ?>
            <tr>
                <td><span class="badge bg-light text-dark border"><?= $m['membro_registro_interno'] ?></span></td>
                <td class="ps-4">
                    <span class="fw-bold d-block text-dark"><?= htmlspecialchars($m['membro_nome']) ?></span>
                    <small class="text-muted">Nasc: <?= !empty($m['membro_data_nascimento']) ? date('d/m/Y', strtotime($m['membro_data_nascimento'])) : '--' ?></small>
                </td>
				<td>
					<?php if (!empty($m['cargos_nomes'])): ?>
						<span class="badge bg-light text-primary border shadow-sm"
							  title="<?= htmlspecialchars($m['cargos_nomes']) ?>"
							  style="cursor: help;">
							<i class="bi bi-tag-fill me-1"></i>
							<?= (strlen($m['cargos_nomes']) > 25) ? substr(htmlspecialchars($m['cargos_nomes']), 0, 25) . '...' : htmlspecialchars($m['cargos_nomes']) ?>
						</span>
					<?php else: ?>
						<span class="text-muted small italic">Membro Comum</span>
					<?php endif; ?>
				</td>
                <td><small class="text-muted"><?= $m['membro_telefone'] ?></small></td>
                <td>
                    <span class="badge rounded-pill <?= $m['membro_status'] == 'Ativo' ? 'bg-success' : 'bg-danger' ?>">
                        <?= strtoupper($m['membro_status']) ?>
                    </span>
                </td>
                <td class="text-center">
                    <div class="btn-group shadow-sm border">
                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="certificado" title="Certificado">🎓</button>


<button class="btn btn-white btn-sm btn-acao-dinamica"
        data-id="<?= $m['membro_id'] ?>"
        data-acao="carteirinha"
        title="Visualizar Carteirinha">🆔</button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="foto" title="Foto do Membro">
                                <?= !empty($m['membro_foto_arquivo']) ? '📸' : '📷' ?></button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="cargos" title="Cargos e Funções">🏷️</button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="endereco" title="Endereço">📍</button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="status" title="Alterar Status">🔄</button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="historico" title="Registrar Histórico">📜</button>

                        <button class="btn btn-white btn-sm btn-acao-dinamica"
                                data-id="<?= $m['membro_id'] ?>"
                                data-acao="ficha" title="Ficha Completa">📄</button>
                    </div>
                    <a href="<?= url('membros/edit/' . $m['membro_id']) ?>" class="btn btn-link btn-sm text-primary ms-2 fw-bold text-decoration-none">✏️</a>
                </td>
            </tr>
        <?php endforeach;
    }

    /**
     * O CARREGADOR MODULAR (O que você pediu)
     * Busca os arquivos em app/Views/paginas/membros/modais/modal-{acao}/
     */
	public function getModalContent($acao, $id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$membro = $this->model->getByIdCompleto($id, $igrejaId);

		if (!$membro) {
			echo "<div class='p-3 text-danger'>Membro não localizado.</div>";
			return;
		}

		// Inicializamos as variáveis para evitar o erro "Undefined variable" em outros modais
		$todosCargos = [];
		$cargosSelecionados = [];

		// Lógica específica baseada na ação
		switch ($acao) {
			case 'cargos':
				// Busca a lista de 31 cargos que você tem no banco
				$todosCargos = $this->model->getTodosCargos();
				// Busca o que o membro já tem (IDs)
				$cargosSelecionados = $this->model->getCargosIdsByMembro($id);
				break;

			case 'historico':
				// Se precisar de algo específico para o histórico no futuro, adicione aqui
                break;
			case 'ficha':
				// 1. Busca os dados da Igreja do usuário logado
				$idIgreja = $_SESSION['usuario_igreja_id'];

				// Supondo que você tenha um método no model para pegar os dados da igreja
				// Se não tiver, pode fazer uma query simples: SELECT igreja_nome FROM igrejas WHERE igreja_id = ?
				$dadosIgreja = $this->model->getIgrejaDados($idIgreja);
				$membro['nome_igreja'] = $dadosIgreja['igreja_nome'] ?? 'Igreja Não Identificada';

				// 2. Busca cargos e histórico
				$membro['membro_cargo'] = $this->model->getCargosNomesByMembro($id);
				$membro['historicos'] = $this->model->getHistorico($id);

				// 3. Lógica da Foto (URL Pública)
				$membro['foto_url'] = null;
				if (!empty($membro['membro_foto_arquivo'])) {
					$registro = $membro['membro_registro_interno'];
					$caminhoWeb = "assets/uploads/{$idIgreja}/membros/{$registro}/{$membro['membro_foto_arquivo']}";
					$membro['foto_url'] = url($caminhoWeb);
				}
				break;
			case 'carteirinha':
				$idIgreja = $_SESSION['usuario_igreja_id'];

				// Captura a instância do banco de dados corretamente
				$db = \App\Core\Database::getInstance();

				// 1. Busca os dados básicos da igreja
				$dadosIgreja = $this->model->getIgrejaDados($idIgreja);
				$pastorId = $dadosIgreja['igreja_pastor_id'] ?? null;
				$nomePastor = 'NÃO INFORMADO';

				// 2. Busca o NOME do Pastor na tabela membros
				if ($pastorId) {
					$stmtPastor = $db->prepare("SELECT membro_nome FROM membros WHERE membro_id = ?");
					$stmtPastor->execute([$pastorId]);
					$resPastor = $stmtPastor->fetch(\PDO::FETCH_ASSOC);
					if ($resPastor) {
						$nomePastor = mb_strtoupper($resPastor['membro_nome']);
					}
				}

				// 3. Busca as Redes Sociais para os contatos
				$stmtRedes = $db->prepare("
					SELECT rede_nome, rede_usuario
					FROM igrejas_redes_sociais
					WHERE rede_igreja_id = ? AND rede_status = 'ativo'
				");
				$stmtRedes->execute([$idIgreja]);
				$redes = $stmtRedes->fetchAll(\PDO::FETCH_ASSOC);

				$contatosArray = [];
				foreach ($redes as $r) {
					$contatosArray[] = "{$r['rede_nome']}: {$r['rede_usuario']}";
				}
				$contatosStr = !empty($contatosArray) ? implode(' | ', $contatosArray) : ($dadosIgreja['igreja_telefone'] ?? '');

				// 4. Prepara os arrays para a View (layout.php)
				$m = [
					'membro_id'               => $membro['membro_id'],
					'membro_registro_interno' => $membro['membro_registro_interno'] ?? '000',
					'membro_nome'             => mb_strtoupper($membro['membro_nome'] ?? ''),
					'membro_data_nascimento'  => $membro['membro_data_nascimento'] ?? '',
					'membro_data_batismo'     => $membro['membro_data_batismo'] ?? '',
					'membro_foto_arquivo'     => $membro['membro_foto_arquivo'] ?? null,
					'membro_cargo'            => mb_strtoupper($this->model->getCargosNomesByMembro($id) ?: 'MEMBRO')
				];

				$igreja = [
					'nome'     => mb_strtoupper($dadosIgreja['igreja_nome'] ?? 'IGREJA PRESBITERIANA'),
					'pastor'   => $nomePastor, // Agora com o nome real vindo de membros
					'endereco' => $dadosIgreja['igreja_endereco'] ?? '',
					'contatos' => $contatosStr
				];

				$caminhoView = dirname(__DIR__) . '/Views/paginas/membros/modais/modal-carteirinha/layout.php';
				if (file_exists($caminhoView)) {
					include $caminhoView;
				}
				exit;
        }

		// Define os caminhos dos arquivos modulares
		$diretorioModal = dirname(__DIR__, 1) . "/Views/paginas/membros/modais/modal-{$acao}";
		$layout = "{$diretorioModal}/layout.php";
		$css    = "{$diretorioModal}/estilo.css";
		$js     = "{$diretorioModal}/script.js";

		if (file_exists($layout)) {
			// Injeta CSS se houver
			if (file_exists($css)) {
				echo "<style>" . file_get_contents($css) . "</style>";
			}

			// Torna as variáveis disponíveis para o layout.php
			// O include extrai o conteúdo aqui, mantendo o acesso a $membro, $todosCargos, etc.
			include $layout;

			// Injeta JS se houver
			if (file_exists($js)) {
				echo "<script>" . file_get_contents($js) . "</script>";
			}
		} else {
			echo "<div class='p-4 text-center text-muted'>Componente modular '{$acao}' não encontrado em: <br><small>{$diretorioModal}</small></div>";
		}
	}

    // --- MÉTODOS DE PERSISTÊNCIA E EDIÇÃO ---

    public function create() { $this->view('membros/cadastrar'); }

    public function store()
    {
        $idIgreja = $_SESSION['usuario_igreja_id'] ?? die("Sessão expirada");

        $proximoId = $this->model->getNextId();
        $registroInterno = $idIgreja . date('Ym') . str_pad($proximoId, 4, '0', STR_PAD_LEFT);

        $data = [
            'igreja_id'        => $idIgreja,
            'registro_interno' => $registroInterno,
            'nome'             => $_POST['nome'],
            'nascimento'       => $_POST['data_nascimento'] ?: null,
            'genero'           => $_POST['genero'] ?? null,
            'email'            => $_POST['email'] ?? null,
            'telefone'         => $_POST['telefone'] ?? null,
            'batismo'          => $_POST['data_batismo'] ?: null,
            'status'           => 'Ativo'
        ];

        if ($this->model->insert($data)) {
            header("Location: " . url('membros') . "?sucesso=1");
        } else {
            die("Erro ao inserir membro.");
        }
        exit;
    }

    public function edit($id)
    {
        $idIgreja = $_SESSION['usuario_igreja_id'];
        $membro = $this->model->getById($id, $idIgreja);
        if (!$membro) { header('Location: ' . url('membros')); exit; }
        $this->view('membros/cadastrar', ['membro' => $membro]);
    }

    public function update($id)
    {
        $idIgreja = $_SESSION['usuario_igreja_id'];
        $dados = [
            'nome'            => $_POST['nome'],
            'genero'          => $_POST['genero'] ?? null,
            'email'           => $_POST['email'],
            'telefone'        => $_POST['telefone'],
            'data_nascimento' => $_POST['data_nascimento'],
            'data_batismo'    => $_POST['data_batismo']
        ];

        if ($this->model->update($id, $idIgreja, $dados)) {
            header('Location: ' . url('membros?sucesso=editado'));
        } else {
            header('Location: ' . url('membros/edit/' . $id . '?erro=1'));
        }
        exit;
    }

    public function updateCargos()
    {
        $membroId = $_POST['membro_id'] ?? null;
        $cargosIds = $_POST['cargos'] ?? [];
        if ($membroId && $this->model->saveCargosVinculo($membroId, $cargosIds)) {
            header("Location: " . url('membros') . "?sucesso=cargos_atualizados");
        }
        exit;
    }

	public function updateEndereco() //NOVO AJAX
	{
		// Captura os dados via POST
		$data = [
			'membro_id'   => $_POST['membro_id'],
			'igreja_id'   => $_SESSION['usuario_igreja_id'],
			'cep'         => $_POST['membro_cep'],
			'rua'         => $_POST['membro_rua'],
			'numero'      => $_POST['membro_numero'],
			'complemento' => $_POST['membro_complemento'],
			'bairro'      => $_POST['membro_bairro'],
			'cidade'      => $_POST['membro_cidade'],
			'estado'      => $_POST['membro_uf'] // Nome que está no ID do input do modal
		];

		if ($this->model->saveEndereco($data)) {
			// Retorna para a lista com mensagem de sucesso
			header("Location: " . url('membros') . "?sucesso=endereco_atualizado");
		} else {
			die("Erro ao processar o endereço.");
		}
		exit;
	}

	public function uploadFoto()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$membroId = $_POST['membro_id'];
		$registro = $_POST['membro_registro_interno'];

		if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
			$extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
			$novoNome = "perfil_" . time() . "." . $extensao;

			// NOVO CAMINHO: Adicionada a subpasta /membros/
			$diretorioDestino = dirname(__DIR__, 2) . "/public/assets/uploads/{$idIgreja}/membros/{$registro}/";

			// Cria o diretório recursivamente (o 0777 com 'true' garante a criação de toda a árvore)
			if (!is_dir($diretorioDestino)) {
				mkdir($diretorioDestino, 0777, true);
			}

			if (move_uploaded_file($_FILES['foto']['tmp_name'], $diretorioDestino . $novoNome)) {
				// Salva o nome do arquivo no banco e retorna o nome da foto antiga
				$fotoAntiga = $this->model->saveFoto($membroId, $novoNome);

				// Opcional: Deleta a foto antiga do servidor para não acumular lixo
				if ($fotoAntiga && file_exists($diretorioDestino . $fotoAntiga)) {
					unlink($diretorioDestino . $fotoAntiga);
				}

				header("Location: " . url('membros') . "?sucesso=foto_atualizada");
			}
		}
		exit;
	}

    public function addHistorico()
    {
        $membroId = $_POST['membro_id'];
        $data = [
            'membro_id' => $membroId,
            'texto'     => $_POST['historico'],
            'data'      => date('Y-m-d H:i:s')
        ];
        if ($this->model->insertHistorico($data)) {
            header("Location: " . url('membros') . "?sucesso=historico_salvo");
        }
        exit;
    }

    public function updateStatus()
    {
        $membroId = $_POST['membro_id'];
        $novoStatus = $_POST['status'];
        $igrejaId = $_SESSION['usuario_igreja_id'];

        if ($this->model->updateStatus($membroId, $igrejaId, $novoStatus)) {
            header("Location: " . url('membros') . "?sucesso=status_atualizado");
        }
        exit;
    }

	public function pendentes() {
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$model = new \App\Models\PortalMembro();

		$pendentes = $model->getPendentes($idIgreja);
		// Geramos a sugestão baseada na regra igreja_id + data + prox_id
		$sugestaoRegistro = $model->gerarSugestaoRegistro($idIgreja);

		$this->view('membros/pendentes', [
			'pendentes' => $pendentes,
			'sugestao'  => $sugestaoRegistro,
			'titulo'    => 'Aprovação de Novos Membros'
		]);
	}

	public function aprovar() {
		$idIgreja = $_SESSION['usuario_igreja_id'];
		$membroId = $_POST['membro_id'];
		$statusPost = $_POST['status'];
		$novoRegistro = $_POST['membro_registro_interno'];

		$model = new \App\Models\PortalMembro();

		if ($statusPost === 'Ativo') {
			// DINÂMICO: Sobe 2 níveis a partir de app/Controllers para chegar na raiz do projeto
			// Independente se está no Windows/Local ou Linux/Produção
			$rootPath = dirname(__DIR__, 2);

			// Caminho absoluto para a pasta de uploads
			$basePath = $rootPath . "/public/assets/uploads/{$idIgreja}/membros/";

			$diretorioAntigo = $basePath . "PENDENTE_" . $membroId;
			$diretorioNovo = $basePath . $novoRegistro;

			if (is_dir($diretorioAntigo)) {
				if (!is_dir($diretorioNovo)) {
					if (!rename($diretorioAntigo, $diretorioNovo)) {
						error_log("ERRO EKKLESIA: Falha ao renomear de $diretorioAntigo para $diretorioNovo");
						// Opcional: Você pode avisar o usuário que a pasta não foi movida
					}
				}
			}
		}

		if($model->alterarStatus($membroId, $statusPost, $novoRegistro)) {
			$msg = ($statusPost === 'Ativo') ? 'aprovado' : 'rejeitado';
			header("Location: " . url('membros/pendentes?sucesso=' . $msg));
		} else {
			header("Location: " . url('membros/pendentes?erro=falha_banco'));
		}
		exit;
	}

}
