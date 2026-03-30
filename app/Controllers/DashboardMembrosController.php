<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Membro;

class DashboardMembrosController extends Controller
{
    private $model;

    public function __construct()
    {
        // Garante que apenas usuários logados acessem
        exigirLogin();
        $this->model = new Membro();
    }

	public function index()
	{
		$idIgreja = $_SESSION['usuario_igreja_id'];

		// 1. Pega as estatísticas gerais (ativos, novos, aniversariantes, etc.)
		$dadosDashboard = $this->model->getDashboardStats($idIgreja);

		// 2. Busca especificamente os dados para o gráfico de bairros
		$bairrosData = $this->model->getEstatisticasBairro($idIgreja);

		// 3. Mescla os bairros no array principal (garantindo que não seja null)
		$dadosDashboard['bairros'] = $bairrosData ?: [];

		// Envia tudo para a view
		$this->view('membros/dashboard', $dadosDashboard);
	}
}
