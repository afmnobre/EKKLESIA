<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Relatório - <?= $classe['classe_nome'] ?></title>
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

        /* ESTILOS DO RELATÓRIO */
        .filtro-card { border-radius: 15px; background: white; }
        .form-select-lg, .form-control-lg {
            height: 50px !important;
            font-size: 1rem !important;
            border-radius: 10px !important;
        }

        .aluno-card {
            border-radius: 15px !important;
            border: 0 !important;
            transition: transform 0.2s;
        }

        .progress { height: 12px; border-radius: 10px; background-color: #e9ecef; }
        .progress-bar { border-radius: 10px; }

        .stats-box {
            display: flex;
            justify-content: space-around;
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
        }
        .stat-item { text-align: center; flex: 1; }
        .stat-value { display: block; font-weight: 800; font-size: 1rem; color: #333; }
        .stat-label { display: block; font-size: 0.65rem; color: #888; text-transform: uppercase; }

        .secao-titulo { font-size: 0.75rem; font-weight: 800; color: #888; letter-spacing: 1px; }
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

<div class="container pb-5">

    <div class="card filtro-card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="" method="GET" class="row g-2">
                <div class="col-6">
                    <select name="mes" class="form-select form-select-lg border-light bg-light">
                        <?php
                        $meses = [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'];
                        foreach($meses as $num => $nome): ?>
                            <option value="<?= $num ?>" <?= ($mes == $num) ? 'selected' : '' ?>><?= $nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-4">
                    <input type="number" name="ano" value="<?= $ano ?>" class="form-control form-control-lg border-light bg-light">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-lg w-100 text-white shadow-sm" style="background-color: var(--azul-ipb);">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <h6 class="secao-titulo mb-0 text-uppercase">Desempenho da Classe</h6>
    </div>

    <?php if(empty($dados) || $dados[0]['total_aulas'] == 0): ?>
        <div class="alert alert-warning border-0 shadow-sm text-center py-4" style="border-radius: 15px;">
            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
            Nenhuma chamada realizada no período selecionado.
        </div>
    <?php else: ?>
        <?php foreach ($dados as $item): ?>
            <div class="card aluno-card shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark fs-6 text-truncate" style="max-width: 75%;"><?= $item['membro_nome'] ?></span>
                        <span class="badge <?= $item['frequencia'] >= 70 ? 'bg-success' : 'bg-danger' ?> rounded-pill px-3 py-2">
                            <?= $item['frequencia'] ?>%
                        </span>
                    </div>

                    <div class="progress mb-3 shadow-sm">
                        <div class="progress-bar <?= $item['frequencia'] >= 70 ? 'bg-success' : 'bg-danger' ?> progress-bar-striped progress-bar-animated"
                             role="progressbar" style="width: <?= $item['frequencia'] ?>%"></div>
                    </div>

                    <div class="stats-box">
                        <div class="stat-item">
                            <span class="stat-value text-success"><?= $item['presencas'] ?></span>
                            <span class="stat-label">Presenças</span>
                        </div>
                        <div class="stat-item border-start border-end">
                            <span class="stat-value text-danger"><?= $item['faltas'] ?></span>
                            <span class="stat-label">Faltas</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value text-secondary"><?= $item['total_aulas'] ?></span>
                            <span class="stat-label">Aulas</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
