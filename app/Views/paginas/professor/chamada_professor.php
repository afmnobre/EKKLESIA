<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Chamada - <?= $classe['classe_nome'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --azul-ipb: #003366;
            --dourado-ipb: #b8860b;
        }

        body { background-color: #f4f6f9; font-family: 'Segoe UI', system-ui, sans-serif; }

        /* HEADER & NAV PADRONIZADO */
        .header-ipb { background-color: var(--azul-ipb); color: white; padding: 12px 0; border-bottom: 3px solid var(--dourado-ipb); }
        .logo-container img { height: 45px; width: auto; }
        .header-info h1 { font-size: 0.9rem; margin: 0; font-weight: 700; text-transform: uppercase; }
        .header-info small { font-size: 0.75rem; color: #d1d1d1; }

        .nav-professor { background: #ffffff; border-bottom: 1px solid #e0e0e0; margin-bottom: 15px; display: flex; justify-content: space-around; }
        .nav-item-ebd { flex: 1; text-align: center; padding: 12px 5px; color: #6c757d; text-decoration: none; font-size: 0.75rem; font-weight: 600; border-bottom: 3px solid transparent; }
        .nav-item-ebd i { font-size: 1.3rem; display: block; margin-bottom: 2px; }
        .nav-item-ebd.active { color: var(--azul-ipb); border-bottom-color: var(--azul-ipb); background-color: #f8f9fa; }

        /* ELEMENTOS DE CHAMADA MOBILE */
        .sticky-menu { position: sticky; top: 0; z-index: 1020; background: #f4f6f9; padding-bottom: 5px; }

        .list-group-item {
            padding: 0.8rem 1rem !important;
            border-radius: 15px !important;
            margin-bottom: 8px !important;
            border: 0 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* BOTÕES P e F GIGANTES */
        .btn-presenca {
            width: 58px !important;
            height: 58px !important;
            font-size: 1.4rem !important;
            font-weight: 800 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px !important;
            margin-left: 8px;
            border-width: 2px !important;
            transition: 0.2s;
        }

        .nome-aluno { font-size: 1.05rem; font-weight: 700; color: #333; margin-bottom: 2px; }
        .status-badge { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Estilo do Input de Data Mobile */
        .input-data-mobile {
            height: 45px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-weight: 600;
            color: var(--azul-ipb);
            text-align: center;
        }
    </style>
</head>
<body>

<header class="header-ipb shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="logo-container me-3">
                <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB">
            </div>
            <div class="header-info">
                <h1>Escola Dominical</h1>
                <small><i class="bi bi-door-open me-1"></i><?= $classe['classe_nome'] ?></small>
            </div>
        </div>
        <a href="<?= url('professor/logout') ?>" class="text-white opacity-75 fs-4">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</header>

<nav class="nav-professor shadow-sm">
    <a href="<?= url('professor/chamada') ?>" class="nav-item-ebd <?= (strpos($_SERVER['REQUEST_URI'], 'chamada') !== false) ? 'active' : '' ?>">
        <i class="bi bi-check2-square"></i> Chamada
    </a>
    <a href="<?= url('professor/alunos') ?>" class="nav-item-ebd <?= (strpos($_SERVER['REQUEST_URI'], 'alunos') !== false) ? 'active' : '' ?>">
        <i class="bi bi-people"></i> Alunos
    </a>
    <a href="<?= url('professor/relatorio') ?>" class="nav-item-ebd <?= (strpos($_SERVER['REQUEST_URI'], 'relatorio') !== false) ? 'active' : '' ?>">
        <i class="bi bi-clipboard-data"></i> Relatório
    </a>
</nav>


<button type="button"
        class="btn btn-success shadow-lg d-flex align-items-center justify-content-center"
        data-bs-toggle="modal"
        data-bs-target="#modalScanner"
        style="position: fixed; bottom: 20px; right: 20px; width: 65px; height: 65px; border-radius: 50%; z-index: 1050; border: 3px solid #fff;">
    <i class="bi bi-qr-code-scan fs-2"></i>
</button>

<div class="container pb-5">

    <div class="sticky-menu">
        <div class="row g-2 align-items-center">
            <div class="col-7">
                <form action="" method="GET" id="formData">
                    <input type="date" name="data" value="<?= $dataSelecionada ?>"
                           class="form-control input-data-mobile shadow-sm"
                           onchange="document.getElementById('formData').submit()">
                </form>
            </div>
            <div class="col-5">
                <div class="bg-white text-center py-2 rounded-10 shadow-sm border" style="height: 45px; border-radius: 10px;">
                    <small class="text-muted d-block" style="font-size: 0.6rem; line-height: 1;">PRESENTES</small>
                    <span id="contador-presentes" class="fw-bold text-primary">
                        <?php
                            $presentes = count(array_filter($alunos, fn($a) => ($a['presenca'] == '1')));
                            echo $presentes . '/' . count($alunos);
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="list-group mt-3 border-0">
        <?php foreach ($alunos as $aluno): ?>
            <?php $presencaStatus = $aluno['presenca'] ?? null; ?>
            <div class="list-group-item d-flex justify-content-between align-items-center bg-white mb-2">
                <div class="text-truncate" style="flex: 1;">
                    <div class="nome-aluno text-truncate"><?= $aluno['membro_nome'] ?></div>
                    <div class="status-label">
                        <?php
                            if ($presencaStatus == '1') echo '<span class="status-badge text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Presente</span>';
                            elseif ($presencaStatus == '0' && $presencaStatus !== null) echo '<span class="status-badge text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Faltou</span>';
                            else echo '<span class="status-badge text-warning"><i class="bi bi-clock"></i> Pendente</span>';
                        ?>
                    </div>
                </div>

                <div class="d-flex">
                    <button type="button"
                        class="btn btn-presenca <?= ($presencaStatus == '1') ? 'btn-success shadow' : 'btn-outline-secondary opacity-50' ?>"
                        onclick="registrarPresenca(<?= $aluno['membro_id'] ?>, 1, this)">P</button>

                    <button type="button"
                        class="btn btn-presenca <?= ($presencaStatus == '0' && $presencaStatus !== null) ? 'btn-danger shadow' : 'btn-outline-secondary opacity-50' ?>"
                        onclick="registrarPresenca(<?= $aluno['membro_id'] ?>, 0, this)">F</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<div class="modal fade" id="modalScanner" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-camera-fill me-2"></i>Scanner de Presença</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="btnFecharScanTop"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <div id="scannerFeedback" class="text-center p-3 d-none fw-bold" style="position: absolute; top: 10px; left: 0; width: 100%; z-index: 1000;"></div>
                <div id="reader" style="width: 100%; background: #000; min-height: 350px;"></div>
            </div>
            <div class="modal-footer bg-light justify-content-center">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bi bi-camera-video-off me-2"></i>Parar Câmera
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function atualizarContador() {
    const total = document.querySelectorAll('.list-group-item').length;
    const presentes = Array.from(document.querySelectorAll('.status-badge'))
                           .filter(s => s.innerText.includes('Presente')).length;
    document.getElementById('contador-presentes').innerText = presentes + '/' + total;
}

function registrarPresenca(alunoId, status, btn) {
    const data = "<?= $dataSelecionada ?>";
    const classeId = "<?= $classe['classe_id'] ?>";
    const grupo = btn.parentElement;
    const botoes = grupo.querySelectorAll('button');

    // Feedback visual imediato
    botoes.forEach(b => b.classList.add('opacity-25'));

    const formData = new URLSearchParams();
    formData.append('aluno_id', alunoId);
    formData.append('status', status);
    formData.append('data', data);
    formData.append('classe_id', classeId);

    fetch("<?= url('professor/salvarPresenca') ?>", {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(result => {
        botoes.forEach(b => {
            b.classList.remove('opacity-25', 'opacity-50', 'btn-success', 'btn-danger', 'shadow');
            b.classList.add('btn-outline-secondary', 'opacity-50');
        });

        const statusLabel = btn.closest('.list-group-item').querySelector('.status-label');

        if(status === 1) {
            btn.classList.remove('btn-outline-secondary', 'opacity-50');
            btn.classList.add('btn-success', 'shadow');
            statusLabel.innerHTML = '<span class="status-badge text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Presente</span>';
        } else {
            btn.classList.remove('btn-outline-secondary', 'opacity-50');
            btn.classList.add('btn-danger', 'shadow');
            statusLabel.innerHTML = '<span class="status-badge text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Faltou</span>';
        }
        atualizarContador();
    })
    .catch(error => {
        alert("Erro de conexão.");
        botoes.forEach(b => b.classList.remove('opacity-25'));
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackDiv = document.getElementById('scannerFeedback');
    const html5QrCode = new Html5Qrcode("reader");
    let isProcessing = false;

    function mostraFeedback(mensagem, tipo) {
        feedbackDiv.textContent = mensagem;
        feedbackDiv.classList.remove('d-none', 'bg-success', 'bg-danger', 'bg-warning', 'text-white', 'text-dark');
        if (tipo === 'sucesso') feedbackDiv.classList.add('bg-success', 'text-white');
        else if (tipo === 'aviso') feedbackDiv.classList.add('bg-warning', 'text-dark');
        else feedbackDiv.classList.add('bg-danger', 'text-white');
    }

    const onScanSuccess = (decodedText) => {
        if (isProcessing) return;
        isProcessing = true;

        mostraFeedback("Processando...", 'aviso');

        const formData = new FormData();
        formData.append('classe_id', '<?= $classe["classe_id"] ?>');
        formData.append('membro_id', decodedText.trim()); // O model espera o registro interno aqui

        fetch("<?= url('professor/registrarPresencaAjax') ?>", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            mostraFeedback(data.mensagem, data.status);

            // Se foi sucesso, recarregamos a página após 2 segundos para atualizar a lista
            if(data.status === 'sucesso') {
                setTimeout(() => location.reload(), 1500);
            } else {
                setTimeout(() => { isProcessing = false; }, 3000);
            }
        })
        .catch(error => {
            mostraFeedback("Erro na conexão com o servidor", 'erro');
            isProcessing = false;
        });
    };

    const modalEl = document.getElementById('modalScanner');
    modalEl.addEventListener('shown.bs.modal', () => {
        isProcessing = false;
        feedbackDiv.classList.add('d-none');
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess
        ).catch(err => mostraFeedback("Erro ao acessar câmera", 'erro'));
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        if (html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error(err));
        }
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
</body>
</html>


