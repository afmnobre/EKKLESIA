<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Calendario;

class CalendarioController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Calendario();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Calendário de Atividades'
        ];
        $this->view('calendarios/index', $data);
    }

    public function feed()
    {
        // Pega o ID da igreja da sessão do usuário logado
        $igrejaId = $_SESSION['usuario_igreja_id'] ?? null;

        if (!$igrejaId) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        $eventos = $this->model->getEventos($igrejaId);

        header('Content-Type: application/json');
        echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
