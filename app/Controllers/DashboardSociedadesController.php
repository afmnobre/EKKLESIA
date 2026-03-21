<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SociedadeDashboard; // IMPORTANTE: Importar o Model aqui!

class DashboardSociedadesController extends Controller
{
    protected $model;

    public function __construct()
    {
        // Remova o parent::__construct() se a classe Controller não tiver um construtor
        // Se o seu sistema de rotas exigir o parent, deixe-o, mas o erro indica que ele está falhando
        $this->model = new SociedadeDashboard();
    }

    public function index()
    {
        // Verifica se existe sessão (ajuste conforme seu sistema de login)
        if (!isset($_SESSION['usuario_igreja_id'])) {
            header('Location: ' . url('login'));
            exit;
        }

        $idIgreja = $_SESSION['usuario_igreja_id'];

        // 1. Métricas de População e Aproveitamento
        $sociedades = $this->model->getMetricasGerais($idIgreja);

        // 2. Alertas de Saúde dos Dados
        $alertasFaixaEtaria = $this->model->getMembrosForaDaFaixa($idIgreja);

        // 3. Calendário de Eventos (Próximos 30 dias)
        $proximosEventos = $this->model->getProximosEventos($idIgreja, 30);

        // 4. Ranking de Atividade
        $rankingAtividade = $this->model->getFrequenciaEventos($idIgreja);

        // Certifique-se de que o caminho 'dashboard/sociedades/dashboard'
        // aponte para /app/Views/paginas/sociedades/dashboard.php

        $this->view('sociedades/dashboard', [
            'titulo' => 'Dashboard de Sociedades',
            'sociedades' => $sociedades,
            'alertas' => $alertasFaixaEtaria, // Verifique se na view você usa $alertas ou $alertasFaixaEtaria
            'eventos' => $proximosEventos,
            'ranking' => $rankingAtividade
            ]);
    }
}
