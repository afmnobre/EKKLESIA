<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Igreja;

class IgrejaController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Igreja();
    }

    // LISTAR
    public function index()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $igreja = $this->model->getByIgreja($igrejaId);

        $this->view('igreja/index', [
            'igreja' => $igreja
        ]);
    }


    // EDITAR FORM
    public function editar()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $igreja = $this->model->getByIgreja($igrejaId);

        $this->view('igreja/editar', [
            'igreja' => $igreja
        ]);
    }

    // SALVAR
    public function atualizar() // Removi o $id = 1 por segurança
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // Validação básica
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . url('igreja'));
            exit;
        }

        $dados = [
            'nome'     => $_POST['nome'] ?? '',
            'cnpj'     => $_POST['cnpj'] ?? '',
            'endereco' => $_POST['endereco'] ?? ''
        ];

        if ($this->model->update($igrejaId, $dados)) {
            // Você pode adicionar uma mensagem de sucesso na sessão aqui
            header("Location: " . url('igreja?sucesso=1'));
        } else {
            header("Location: " . url('igreja/editar?erro=1'));
        }
        exit;
    }
}

