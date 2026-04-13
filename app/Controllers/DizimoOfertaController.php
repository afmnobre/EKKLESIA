<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\DizimoOferta;

class DizimoOfertaController extends Controller
{
    private $model;

    public function __construct()
    {
        // Exige o login do usuário principal do sistema antes de iniciar a conferência
        exigirLogin();
        $this->model = new DizimoOferta();
    }

    public function index()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        // Lógica de Trava: Só acessa a view principal se houver dois conferentes definidos na sessão
        if (!isset($_SESSION['conferencia_diacono_1'])) {
            return $this->rawview('dizimosofertas/login_passo1');
        }

        if (!isset($_SESSION['conferencia_diacono_2'])) {
            return $this->rawview('dizimosofertas/login_passo2');
        }

        $lancamentos = $this->model->getLancamentosDoDia(
            $igrejaId,
            $_SESSION['conferencia_diacono_1']['id'],
            $_SESSION['conferencia_diacono_2']['id']
        );

        $this->rawview('dizimosofertas/index', [
            'lancamentos' => $lancamentos,
            'diacono1'    => $_SESSION['conferencia_diacono_1']['nome'],
            'diacono2'    => $_SESSION['conferencia_diacono_2']['nome']
        ]);
    }

    public function setDiacono1()
    {
        // Aqui você faria a validação de senha do Diácono 1
        // Se válido:
        $_SESSION['conferencia_diacono_1'] = [
            'id' => $_POST['id_usuario'],
            'nome' => $_POST['nome_usuario']
        ];
        header("Location: " . url('dizimosofertas'));
    }

    public function setDiacono2()
    {
        // Validação: Diácono 2 não pode ser o mesmo que o 1
        if ($_POST['id_usuario'] == $_SESSION['conferencia_diacono_1']['id']) {
            die("O segundo conferente deve ser uma pessoa diferente.");
        }

        $_SESSION['conferencia_diacono_2'] = [
            'id' => $_POST['id_usuario'],
            'nome' => $_POST['nome_usuario']
        ];
        header("Location: " . url('dizimosofertas'));
    }

    public function salvar()
    {
        $data = [
            'igreja_id'    => $_SESSION['usuario_igreja_id'],
            'categoria_id' => $_POST['categoria_id'],
            'descricao'    => $_POST['descricao'],
            'valor'        => $_POST['valor'],
            'diacono_1'    => $_SESSION['conferencia_diacono_1']['id'],
            'diacono_2'    => $_SESSION['conferencia_diacono_2']['id']
        ];

        if ($this->model->salvar($data)) {
            header("Location: " . url('dizimosofertas?sucesso=1'));
        }
    }
}
