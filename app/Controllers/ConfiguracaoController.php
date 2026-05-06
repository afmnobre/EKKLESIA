<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Igreja;
use App\Models\Usuario;

class ConfiguracaoController extends Controller {
    private $igrejaModel;
    private $usuarioModel;

    public function __construct() {
        exigirLogin();
        $this->igrejaModel = new Igreja();
        $this->usuarioModel = new Usuario();
    }

    public function igrejas() {
        $dados = $this->igrejaModel->listarTodas();
        $this->view('configuracoes/igrejas', ['igrejas' => $dados]);
    }

    public function usuarios() {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $dados = $this->usuarioModel->listarPorIgreja($igrejaId);
        $this->view('configuracoes/usuarios', ['usuarios' => $dados]);
    }
}
