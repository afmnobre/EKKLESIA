<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MensagemDominical;

class MensagemDominicalController extends Controller
{
    private $model;

    public function __construct()
    {
        exigirLogin();
        $this->model = new MensagemDominical();
    }

    /**
     * Listagem de Mensagens Dominicais
     */
    public function index()
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $db = \App\Core\Database::getInstance();

        // 1. Buscar o ID do Pastor Titular da Igreja (Cargo ID = 1)
        $sqlPastor = "SELECT m.membro_id
                      FROM membros m
                      INNER JOIN membros_cargos_vinculo v ON m.membro_id = v.vinculo_membro_id
                      WHERE m.membro_igreja_id = ?
                      AND v.vinculo_cargo_id = 1
                      LIMIT 1";

        $stPastor = $db->prepare($sqlPastor);
        $stPastor->execute([$igrejaId]);
        $dadosPastor = $stPastor->fetch();
        $pastorId = $dadosPastor['membro_id'] ?? '';

        // 2. Buscar Mensagens e Membros para o Select
        $mensagens = $this->model->getAllByIgreja($igrejaId);
        $proximoNumero = $this->model->getUltimoNumero($igrejaId) + 1;

        $stMembros = $db->prepare("SELECT membro_id, membro_nome FROM membros WHERE membro_igreja_id = ? ORDER BY membro_nome");
        $stMembros->execute([$igrejaId]);
        $membros = $stMembros->fetchAll(\PDO::FETCH_ASSOC);

        // Alterado o diretório da view para 'mensagensdominicais'
        $this->view('mensagensdominicais/index', [
            'mensagens'     => $mensagens,
            'proximoNumero' => $proximoNumero,
            'membros'       => $membros,
            'pastorId'      => $pastorId
        ]);
    }

    /**
     * Processar o salvamento (Cadastro/Edição)
     */
    public function salvar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dados = [
                'id'        => $_POST['igreja_mensagem_dominical_id'] ?? null,
                'igreja_id' => $_SESSION['usuario_igreja_id'],
                'numero'    => $_POST['igreja_mensagem_dominical_num_historico'],
                'data'      => $_POST['igreja_mensagem_dominical_data'],
                'autor_id'  => $_POST['igreja_mensagem_dominical_autor_id'],
                'titulo'    => $_POST['igreja_mensagem_dominical_titulo'],
                'mensagem'  => $_POST['igreja_mensagem_dominical_mensagem'],
                'status'    => $_POST['igreja_mensagem_dominical_status'] ?? 'publicado'
            ];

            if ($this->model->salvar($dados)) {
                // Redireciona para a nova rota
                header('Location: ' . url('mensagemDominical/index'));
                exit;
            } else {
                die("Erro ao salvar a mensagem dominical.");
            }
        }
    }

    /**
     * Excluir Mensagem
     */
    public function excluir($id)
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];
        $this->model->excluir($id, $igrejaId);
        header('Location: ' . url('mensagemDominical/index'));
        exit;
    }

    /**
     * Visualização para Impressão
     */
    public function imprimir($id)
    {
        $igrejaId = $_SESSION['usuario_igreja_id'];

        $mensagem = $this->model->getById($id, $igrejaId);

        if (!$mensagem) {
            die("Mensagem não encontrada.");
        }

        $igrejaModel = new \App\Models\Igreja();
        $igreja = $igrejaModel->getById($igrejaId);

        $db = \App\Core\Database::getInstance();
        $st = $db->prepare("SELECT membro_nome FROM membros WHERE membro_id = ?");
        $st->execute([$mensagem['igreja_mensagem_dominical_autor_id']]);
        $autor = $st->fetch();

        // Alterado o diretório da view para 'mensagensdominicais'
        $this->rawview('mensagensdominicais/imprimir', [
            'mensagem' => $mensagem,
            'igreja'   => $igreja,
            'autor'    => $autor['membro_nome'] ?? 'Não informado'
        ]);
    }
}
