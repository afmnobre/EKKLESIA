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
        $dadosDashboard = $this->model->getDashboardStats($idIgreja);

        // Removi o "paginas/" do início, pois o erro mostra que o sistema já o adiciona
        $this->view('membros/dashboard', $dadosDashboard);
    }
}
