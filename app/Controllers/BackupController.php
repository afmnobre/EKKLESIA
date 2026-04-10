<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Backup;

class BackupController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new Backup();
    }

    public function index()
    {
        $data = [
            'titulo'  => 'Gerenciamento de Backups',
            'backups' => $this->model->listarTodos()
        ];
        $this->view('backups/index', $data);
    }

    public function gerar()
    {
        $nomeArquivo = "backup_ekklesia_" . date('Y-m-d_H-i-s') . ".sql";

        // Chamamos o método que criamos no Model que usa PDO
        if ($this->model->gerarBackupPDO($nomeArquivo)) {
            header("Location: " . url('backup/index?status=sucesso'));
        } else {
            header("Location: " . url('backup/index?status=erro'));
        }
        exit;
    }

    public function excluir($nome)
    {
        if ($this->model->deletarArquivo($nome)) {
            header("Location: " . url('backup/index?status=excluido'));
        } else {
            header("Location: " . url('backup/index?status=erro_excluir'));
        }
        exit;
    }
}
