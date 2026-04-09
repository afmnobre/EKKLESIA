<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\PortalMembro;
use App\Models\BoletimSemanal;
use App\Core\Utils;

class PortalMembroController extends Controller {

    public function cadastro($idIgreja) {
        $model = new PortalMembro();
        $igreja = $model->getIgreja($idIgreja);

        if (!$igreja) die("Igreja inválida.");

        // Usa rawview para não carregar sidebar/nav do admin
        return $this->rawview('membros/cadastro_inicial', [
            'igreja' => $igreja
        ]);
    }

    public function salvar() {
        $model = new PortalMembro();
        $idIgreja = $_POST['igreja_id'];

        $dataBatismo = !empty($_POST['data_batismo']) ? $_POST['data_batismo'] : null;
        $dataCasamento = !empty($_POST['data_casamento']) ? $_POST['data_casamento'] : null;

        $dados = [
            'igreja_id'    => $idIgreja,
            'nome'         => mb_strtoupper($_POST['nome']),
            'email'        => $_POST['email'],
            'senha'        => password_hash($_POST['senha'], PASSWORD_DEFAULT),
            'data_nasc'    => $_POST['data_nasc'],
            'sexo'         => $_POST['sexo'],
            'estado_civil' => $_POST['estado_civil'],
            'data_batismo' => $dataBatismo,
            'data_casamento' => $dataCasamento,
            'telefone'     => $_POST['telefone'],
            'rua'          => $_POST['rua'],
            'numero'       => $_POST['numero'],
            'complemento'  => $_POST['complemento'],
            'bairro'       => $_POST['bairro'],
            'cidade'       => $_POST['cidade'],
            'estado'       => $_POST['estado'] ?? 'SP',
            'cep'          => $_POST['cep'],
            'aceite_lgpd' => 1,
            'data_aceite_lgpd' => date('Y-m-d H:i:s'),
            'ip_aceite_lgpd'   => $_SERVER['REMOTE_ADDR'],
            'rg'          => $_POST['rg'] ?? null,
            'cpf'         => $_POST['cpf'] ?? null,
        ];

        $membroId = $model->registrarPendente($dados);

        if ($membroId) {
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $this->processarFoto($membroId, $idIgreja);
            }

            $idBase64 = base64_encode($membroId);
            header("Location: " . url("PortalMembro/resumo/{$idBase64}?igreja={$idIgreja}"));
            exit;
        } else {
            var_dump($dados);
            die("Erro ao registrar membro. Verifique os logs do banco de dados.");
        }
    }

    public function resumo($idCriptografado) {
        $membroId = base64_decode($idCriptografado);

        $model = new PortalMembro();
        $dadosMembro = $model->getMembroComEndereco($membroId);

        if (!$dadosMembro) {
            header("Location: " . url("auth/login"));
            exit;
        }

        $this->rawview('membros/cadastro_resumo', [
            'm'      => $dadosMembro,
            'titulo' => 'Resumo do Cadastro'
        ]);
    }

    private function processarFoto($membroId, $idIgreja) {
        $model = new PortalMembro();
        $novoNome = "selfie_" . time() . "_" . $membroId . ".jpg";
        $diretorioDestino = dirname(__DIR__, 2) . "/public/assets/uploads/{$idIgreja}/membros/PENDENTE_{$membroId}/";

        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0777, true);
        }

        $caminhoFinal = $diretorioDestino . $novoNome;

        if (Utils::otimizarImagem($_FILES['foto']['tmp_name'], $caminhoFinal)) {
            $model->saveFoto($membroId, $novoNome);
        } else {
            error_log("ERRO EKKLESIA: Falha ao otimizar imagem do membro ID: " . $membroId);
        }
    }

    public function painel() {
        if (!isset($_SESSION['membro_id'])) {
            header("Location: " . url('PortalMembro/login'));
            exit;
        }

        $idMembro = $_SESSION['membro_id'];
        $igrejaId = $_SESSION['membro_igreja_id'];

        $model = new PortalMembro();
        $modelBoletim = new BoletimSemanal();

        $perfil = $model->getMembroCompleto($idMembro);
        $dados['perfil'] = $perfil;
        $dados['dependentes'] = $model->listarDependentes($idMembro);

        $igreja = $model->getIgreja($igrejaId);
        $dados['igreja_dados'] = $igreja;
        $dados['nomeIgreja'] = $igreja['igreja_nome'] ?? 'EKKLESIA';
        $dados['tem_boletim'] = $modelBoletim->getUltimaLiturgia($igrejaId);

        // --- PROCESSAMENTO DE PRESENÇAS ---
        $presencasFormatadas = [];
        if (!empty($perfil['presencas']) && is_array($perfil['presencas'])) {
            foreach ($perfil['presencas'] as $p) {
                // Garantimos o formato '01', '02' etc para o mês
                $dataObj = new \DateTime($p['presenca_data']);
                $mes = $dataObj->format('m');

                $presencasFormatadas[$mes][] = [
                    'presenca_data'   => $p['presenca_data'],
                    'classe_nome'     => $p['classe_nome'] ?? 'Classe EBD',
                    'presenca_status' => $p['presenca_status']
                ];
            }
        }

        // Enviamos como objeto para que o json_encode mantenha as chaves dos meses
        $dados['presencas_mensais'] = (object)$presencasFormatadas;
        $dados['anoAtual'] = date('Y');
        $dados['mesAtual'] = date('m');

        $this->rawview('membros/painel_membro', $dados);
    }

    public function login($idIgreja = null) {
        $model = new PortalMembro();
        $igreja = $model->getIgreja($idIgreja);

        if (!$igreja) {
            die("Igreja inválida ou não encontrada.");
        }

        return $this->rawview('membros/painel_membro_login', ['igreja' => $igreja]);
    }

    public function auth() {
        $telefone = preg_replace('/\D/', '', $_POST['membro_telefone']);
        $senha = $_POST['membro_senha'];

        $model = new PortalMembro();
        $membro = $model->buscarPorTelefone($telefone);

        if ($membro && password_verify($senha, $membro['membro_senha'])) {
            $_SESSION['membro_id'] = $membro['membro_id'];
            $_SESSION['membro_igreja_id'] = $membro['membro_igreja_id'];
            $_SESSION['membro_nome'] = $membro['membro_nome'];

            header("Location: " . url('PortalMembro/painel'));
        } else {
            header("Location: " . url('PortalMembro/login?erro=1'));
        }
        exit;
    }

    public function novoDependente() {
        if (!isset($_SESSION['membro_id'])) {
            header("Location: " . url('PortalMembro/login'));
            exit;
        }

        $idMembro = $_SESSION['membro_id'];
        $idIgreja = $_SESSION['membro_igreja_id'] ?? null;

        $model = new PortalMembro();
        $perfil = $model->getMembroCompleto($idMembro);

        if ($idIgreja) {
            $dadosIgreja = $model->getIgreja($idIgreja);
            $dados['igreja_dados'] = $dadosIgreja;
            $dados['nomeIgreja']   = $dadosIgreja['igreja_nome'] ?? 'EKKLESIA';
        } else {
            $dados['igreja_dados'] = null;
            $dados['nomeIgreja']   = 'EKKLESIA';
        }

        $dados['perfil'] = $perfil;
        $dados['titulo'] = "Cadastrar Dependente";

        $this->rawview('membros/painel_membro_dependente', $dados);
    }

    public function salvarDependente() {
        if (!isset($_SESSION['membro_id'])) exit;

        $model = new PortalMembro();

        $localidade = explode('/', $_POST['membro_endereco_cidade']);
        $cidade = trim($localidade[0]);
        $estado = isset($localidade[1]) ? trim($localidade[1]) : '';

        $dadosParaRegistrar = [
            'igreja_id'    => $_SESSION['membro_igreja_id'],
            'nome'         => mb_strtoupper(trim($_POST['membro_nome'])),
            'data_nasc'    => $_POST['membro_data_nascimento'],
            'sexo'         => $_POST['membro_genero'],
            'estado_civil' => $_POST['membro_estado_civil'],
            'data_batismo' => $_POST['membro_data_batismo'] ?: null,
            'email'        => $_POST['membro_email'] ?? null,
            'senha'        => null,
            'telefone'     => $_POST['membro_telefone'] ?? null,
            'rua'          => $_POST['membro_endereco_rua'],
            'numero'       => $_POST['membro_endereco_numero'],
            'bairro'       => $_POST['membro_endereco_bairro'],
            'cidade'       => $cidade,
            'estado'       => $estado,
            'cep'          => $_POST['membro_endereco_cep'] ?? null
        ];

        $dependenteId = $model->registrarPendente($dadosParaRegistrar);

        if ($dependenteId) {
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
                $this->processarFoto($dependenteId, $_SESSION['membro_igreja_id']);
            }

            $dadosVinculo = [
                'parentesco_responsavel_id' => $_SESSION['membro_id'],
                'parentesco_dependente_id'  => $dependenteId,
                'parentesco_grau'            => $_POST['parentesco_grau']
            ];
            $model->vincularResponsavel($dadosVinculo);

            $_SESSION['mensagem_sucesso'] = "Dependente cadastrado! O administrador revisará os dados.";
        }

        header("Location: " . url('PortalMembro/painel#familia'));
        exit;
    }

    public function logout() {
        $idIgreja = $_SESSION['membro_igreja_id'] ?? null;
        $_SESSION = array();
        session_destroy();

        if ($idIgreja) {
            header("Location: " . url("PortalMembro/login/" . $idIgreja));
        } else {
            header("Location: " . url("PortalMembro/login"));
        }
        exit;
    }
}
