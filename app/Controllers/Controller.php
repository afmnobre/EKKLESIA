<?php
namespace App\Controllers;

class Controller {
    protected function view($caminho, $dados = []) {
        extract($dados);
        require_once "../app/Views/paginas/" . $caminho . ".php";
    }
}
