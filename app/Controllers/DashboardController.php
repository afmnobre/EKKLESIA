<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dashboard;
use App\Models\Igreja;
use App\Core\Utils;

class DashboardController extends Controller
{
    private $model;
    private $modelIgreja;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Dashboard();
        $this->modelIgreja = new Igreja();
    }

	public function index() {
		$igrejaId = $_SESSION['usuario_igreja_id'];

		$igreja = $this->modelIgreja->getByIgreja($igrejaId);
		$totalMembros = $this->model->getTotalMembros($igrejaId);
		$ebdDinamica = $this->model->getMetricasEBD($igrejaId);
		$sociedades = $this->model->getMetricasSociedades($igrejaId);

		// Nova métrica para o cuidado pastoral (Top 10 ausentes > 3 meses)
		$membrosAusentes = $this->model->getMembrosAusentes($igrejaId);

		$this->view('dashboard/index', [
			'igreja' => $igreja,
			'totalMembros' => $totalMembros,
			'ebd' => $ebdDinamica,
			'sociedades' => $sociedades,
			'membrosAusentes' => $membrosAusentes // Enviando a lista para a view
		]);
    }

public function alterarFotoIgreja()
{
    $idIgreja = $_SESSION['usuario_igreja_id'] ?? null;

    if ($idIgreja && isset($_FILES['foto_igreja']) && $_FILES['foto_igreja']['error'] === UPLOAD_ERR_OK) {
        $novoNome = "igreja_" . time() . ".jpg";

        // Diretório onde o arquivo será salvo
        $diretorioDestino = dirname(__DIR__, 2) . "/public/assets/uploads/{$idIgreja}/foto_igreja/";
        $caminhoCompleto = $diretorioDestino . $novoNome;

        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        // 1. Move a imagem da pasta temporária do PHP para o destino final
        if (move_uploaded_file($_FILES['foto_igreja']['tmp_name'], $caminhoCompleto)) {

            // 2. Otimiza e redimensiona a imagem (máximo 1200px de largura)
            Utils::otimizarImagem($caminhoCompleto, $caminhoCompleto, 1200, 80);

            // 3. Atualiza no banco de dados e traz o nome da foto antiga
            $fotoAntiga = $this->modelIgreja->atualizarFotoIgreja($idIgreja, $novoNome);

            // 4. Se havia uma foto anterior diferente, apaga do servidor
            if (!empty($fotoAntiga) && file_exists($diretorioDestino . $fotoAntiga)) {
                @unlink($diretorioDestino . $fotoAntiga);
            }

            header("Location: " . url('dashboard') . "?sucesso=foto_atualizada");
            exit();
        }
    }

    header("Location: " . url('dashboard') . "?erro=upload_falhou");
    exit();
}

}
