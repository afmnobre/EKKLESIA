<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Middlewares\AuthMiddleware;

class DashboardController extends Controller
{
    public function __construct()
    {
        exigirLogin();
    }

    public function index()
    {

        $dados = [
            'titulo' => 'Dashboard',
            'usuario' => $_SESSION['usuario_nome']
        ];

        $this->view('dashboard/index', $dados);
        AuthMiddleware::handle();
    }
}

