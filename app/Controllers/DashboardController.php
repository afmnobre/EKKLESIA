<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dashboard;
use App\Models\Igreja;

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
		$totalMembros = $this->model->getTotalMembros($igrejaId); // Nova linha
		$ebdDinamica = $this->model->getMetricasEBD($igrejaId);
		$sociedades = $this->model->getMetricasSociedades($igrejaId);

		$this->view('dashboard/index', [
			'igreja' => $igreja,
			'totalMembros' => $totalMembros, // Enviando para a view
			'ebd' => $ebdDinamica,
			'sociedades' => $sociedades
		]);
	}
}
