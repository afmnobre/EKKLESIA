<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Documento;

class DocumentosController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Documento();
    }

	public function index()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$documentosAgrupados = $this->model->listarAgrupado($igrejaId);
		$categorias = $this->model->getCategorias($igrejaId);

		// Contagem total de documentos para o card
		$totalDocs = 0;
		foreach($documentosAgrupados as $cat) {
			$totalDocs += count($cat);
		}

		$this->view('documentos/index', [
			'documentosAgrupados' => $documentosAgrupados,
			'categorias'          => $categorias,
			'totalDocs'           => $totalDocs // Enviando a contagem pronta
		]);
	}

	public function salvar_categoria()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$id = $_POST['categoria_id'] ?? null;
			$data = [
				'id' => $id,
				'igreja_id' => $_SESSION['usuario_igreja_id'],
				'nome' => $_POST['nome']
			];

			$this->model->salvarCategoria($data);

			$msg = $id ? "categoria_atualizada" : "categoria_salva";
			header("Location: " . url('documentos/categorias') . "?sucesso=" . $msg);
		}
	}

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $igrejaId = $_SESSION['usuario_igreja_id'];
            $categoriaId = $_POST['documento_categoria_id'];

            $dataDoc = [
                'igreja_id' => $igrejaId,
                'categoria_id' => $categoriaId,
                'nome' => $_POST['documento_nome'],
                'descricao' => $_POST['documento_descricao'],
                'data_referencia' => $_POST['documento_data_referencia'],
                'status' => 'ativo'
            ];

            $documentoId = $this->model->salvarDocumento($dataDoc);

            // PROCESSAR UPLOADS (Múltiplos arquivos)
            if (!empty($_FILES['arquivos']['name'][0])) {
                $this->processarUploads($documentoId, $igrejaId, $categoriaId);
            }

            header("Location: " . url('documentos') . "?sucesso=documento_salvo");
        }
    }

    private function processarUploads($documentoId, $igrejaId, $categoriaId)
    {
        $raizProjeto = dirname(__DIR__, 2);
        $diretorioDestino = "/public/assets/uploads/{$igrejaId}/documentos/{$categoriaId}/";
        $caminhoAbsoluto = $raizProjeto . $diretorioDestino;

        if (!is_dir($caminhoAbsoluto)) {
            mkdir($caminhoAbsoluto, 0777, true);
        }

        foreach ($_FILES['arquivos']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['arquivos']['error'][$key] === 0) {
                $nomeOriginal = $_FILES['arquivos']['name'][$key];
                $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
                $tipo = $_FILES['arquivos']['type'][$key];

                // Nome único para evitar sobrescrita: doc_IDDOC_TIMESTAMP_RAND.ext
                $novoNome = "doc_{$documentoId}_" . time() . "_" . rand(10, 99) . "." . $extensao;

                if (move_uploaded_file($tmpName, $caminhoAbsoluto . $novoNome)) {
                    $this->model->salvarArquivo($documentoId, $nomeOriginal, $novoNome, $tipo);
                }
            }
        }
    }

	// No DocumentosController.php
	public function categorias()
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		$categorias = $this->model->getCategorias($igrejaId);

		$this->view('documentos/categorias', [
			'categorias' => $categorias
		]);
	}

	public function listar_arquivos($documentoId)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// Buscar arquivos do documento (garantindo que o documento pertença à igreja)
		$db = \App\Core\Database::getInstance();
		$stmt = $db->prepare("
			SELECT da.*, d.documento_documento_categoria_id as cat_id
			FROM documentos_arquivos da
			JOIN documentos d ON da.documento_arquivo_documento_id = d.documento_id
			WHERE da.documento_arquivo_documento_id = ? AND d.documento_igreja_id = ?
		");
		$stmt->execute([$documentoId, $igrejaId]);
		$arquivos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		if (empty($arquivos)) {
			echo '<div class="alert alert-light text-center">Nenhum arquivo anexado.</div>';
			exit;
		}

		echo '<div class="list-group list-group-flush">';
		foreach ($arquivos as $arq) {
			$caminho = url("assets/uploads/{$igrejaId}/documentos/{$arq['cat_id']}/{$arq['documento_arquivo_caminho']}");
			$ext = strtolower(pathinfo($arq['documento_arquivo_nome'], PATHINFO_EXTENSION));

			// Ícone baseado na extensão
			$icon = 'bi-file-earmark';
			if(in_array($ext, ['jpg', 'jpeg', 'png'])) $icon = 'bi-file-earmark-image';
			if($ext == 'pdf') $icon = 'bi-file-earmark-pdf text-danger';
			if(in_array($ext, ['doc', 'docx'])) $icon = 'bi-file-earmark-word text-primary';

			echo "
			<div class='list-group-item d-flex justify-content-between align-items-center py-3'>
				<div class='d-flex align-items-center text-truncate'>
					<i class='bi {$icon} fs-4 me-3'></i>
					<div class='text-truncate'>
						<h6 class='mb-0 text-truncate' style='max-width: 250px;'>{$arq['documento_arquivo_nome']}</h6>
						<small class='text-muted text-uppercase'>{$ext} • " . date('d/m/Y H:i', strtotime($arq['documento_arquivo_data'])) . "</small>
					</div>
				</div>
				<div class='btn-group'>
					<a href='{$caminho}' target='_blank' class='btn btn-sm btn-outline-primary'>
						<i class='bi bi-eye'></i>
					</a>
					<a href='{$caminho}' download='{$arq['documento_arquivo_nome']}' class='btn btn-sm btn-outline-secondary'>
						<i class='bi bi-download'></i>
					</a>
				</div>
			</div>";
		}
		echo '</div>';
		exit;
	}

	public function excluir_arquivo($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];
		if ($this->model->excluirArquivo($id, $igrejaId)) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}

	public function excluir($id)
	{
		$igrejaId = $_SESSION['usuario_igreja_id'];

		// O Model deve cuidar de deletar os arquivos físicos e os registros no banco
		if ($this->model->excluirDocumentoCompleto($id, $igrejaId)) {
			header("Location: " . url('documentos') . "?sucesso=documento_excluido");
		} else {
			header("Location: " . url('documentos') . "?erro=falha_exclusao");
		}
		exit;
	}

}
