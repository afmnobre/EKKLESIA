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
		if (!isset($_SESSION['usuario_igreja_id'])) {
			header('Location: ' . url('login'));
			exit;
		}

		$idIgreja = $_SESSION['usuario_igreja_id'];
		$sociedades = $this->model->getMetricasGerais($idIgreja);

		// --- Nova Lógica de Eventos por Mês ---
		$mesesNomes = [1=>'Jan', 2=>'Fev', 3=>'Mar', 4=>'Abr', 5=>'Mai', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Set', 10=>'Out', 11=>'Nov', 12=>'Dez'];
		$dadosEventosBD = $this->model->getEventosPorMes($idIgreja);

		$eventosMensais = [];
		foreach ($sociedades as $s) {
			$eventosMensais[$s['sociedade_id']] = [
				'nome' => $s['sociedade_nome'],
				'meses' => array_fill(1, 12, 0)
			];
		}

		foreach ($dadosEventosBD as $row) {
			if (isset($eventosMensais[$row['sociedade_id']]) && $row['mes']) {
				$eventosMensais[$row['sociedade_id']]['meses'][(int)$row['mes']] = (int)$row['total_eventos'];
			}
		}
		// ---------------------------------------

		$alertasFaixaEtaria = $this->model->getMembrosForaDaFaixa($idIgreja);
		$proximosEventos = $this->model->getProximosEventos($idIgreja, 30);
		$rankingAtividade = $this->model->getFrequenciaEventos($idIgreja);

		$this->view('sociedades/dashboard', [
			'titulo' => 'Dashboard de Sociedades',
			'sociedades' => $sociedades,
			'alertas' => $alertasFaixaEtaria,
			'eventos' => $proximosEventos,
			'ranking' => $rankingAtividade,
			'eventosMensais' => $eventosMensais, // Enviando para a view
			'mesesNomes' => $mesesNomes         // Enviando para a view
		]);
	}
}
