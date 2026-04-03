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

        // 4. Mescla os dados do gráfico de rosquinha no array principal
        $dadosDashboard['estado_civil_geral'] = $this->model->getEstatisticaEstadoCivil($idIgreja, false);
        $dadosDashboard['estado_civil_maiores'] = $this->model->getEstatisticaEstadoCivil($idIgreja, true);

        // Envia tudo para a view 'membros/dashboard'
        // Na view, você acessará como $estado_civil['labels'] e $estado_civil['valores']
        $this->view('membros/dashboard', $dadosDashboard);
    }
}
