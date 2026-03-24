<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Alunos - <?= $classe['classe_nome'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --azul-ipb: #003366;
            --dourado-ipb: #b8860b;
        }

        body { background-color: #f4f6f9; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }

        /* HEADER & NAV PADRONIZADO */
        .header-ipb { background-color: var(--azul-ipb); color: white; padding: 12px 0; border-bottom: 3px solid var(--dourado-ipb); }
        .logo-container img { height: 45px; width: auto; }
        .header-info h1 { font-size: 0.9rem; margin: 0; font-weight: 700; text-transform: uppercase; }
        .header-info small { font-size: 0.75rem; color: #d1d1d1; }

        .nav-professor { background: #ffffff; border-bottom: 1px solid #e0e0e0; margin-bottom: 15px; display: flex; justify-content: space-around; }
        .nav-item-ebd { flex: 1; text-align: center; padding: 12px 5px; color: #6c757d; text-decoration: none; font-size: 0.75rem; font-weight: 600; border-bottom: 3px solid transparent; }
        .nav-item-ebd i { font-size: 1.3rem; display: block; margin-bottom: 2px; }
        .nav-item-ebd.active { color: var(--azul-ipb); border-bottom-color: var(--azul-ipb); background-color: #f8f9fa; }

        /* OTIMIZAÇÃO MOBILE */
        .search-box { position: sticky; top: 0; z-index: 1020; background: #f4f6f9; padding: 10px 0; }
        .search-box .form-control { height: 55px; border-radius: 15px; font-size: 1.1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }

        .list-group-item {
            padding: 1rem !important;
            border-radius: 15px !important;
            margin-bottom: 10px !important;
            border: 1px solid #eee !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        /* Botões de Ação Grandes (Touch Target) */
        .btn-touch {
            width: 55px !important;
            height: 55px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px !important;
            flex-shrink: 0;
        }

        .nome-aluno { font-size: 1.05rem; font-weight: 700; color: #333; }
        .info-aluno { font-size: 0.85rem; color: #666; }

        .secao-titulo { font-size: 0.75rem; font-weight: 800; color: #888; letter-spacing: 1px; margin-top: 15px; }
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
        <a href="<?= url('professor/logout') ?>" class="text-white opacity-75 fs-4" title="Sair">
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

    <div class="search-box">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0" style="border-radius: 15px 0 0 15px;">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="inputBusca" class="form-control border-start-0"
                   placeholder="Nome do aluno..." onkeyup="filtrarAlunos()" style="border-radius: 0 15px 15px 0;">
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3 mt-2" style="border-radius: 15px;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small d-block">Público Alvo</span>
                    <strong class="text-dark"><?= $classe['classe_idade_min'] ?> a <?= $classe['classe_idade_max'] ?> anos</strong>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6"><?= count($alunos) ?> matriculados</span>
                </div>
            </div>
        </div>
    </div>

    <div class="secao-titulo mb-2 px-1 text-uppercase">Alunos Matriculados</div>
    <div class="list-group border-0" id="listaClasse">
        <?php foreach ($alunos as $a): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center item-aluno bg-white border-0 shadow-sm mb-2" data-nome="<?= strtolower($a['membro_nome']) ?>">
                <div class="text-truncate me-2">
                    <div class="nome-aluno text-truncate"><?= $a['membro_nome'] ?></div>
                    <div class="info-aluno"><i class="bi bi-check-circle-fill text-success me-1"></i> No sistema</div>
                </div>
                <button type="button" class="btn btn-outline-danger btn-touch border-2"
                        onclick="confirmarRemocao('<?= $a['membro_nome'] ?>', '<?= url('professor/remover_aluno/'.$a['membro_id']) ?>')">
                    <i class="bi bi-trash3 fs-4"></i>
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="secao-titulo mb-2 px-1 text-uppercase mt-4">Disponíveis (Faixa Etária)</div>
    <div class="list-group border-0" id="listaDisponiveis">
        <?php if(empty($disponiveis)): ?>
            <div class="list-group-item text-center py-4 text-muted small bg-white border-0 shadow-sm">
                Nenhum aluno da igreja nesta faixa etária sem classe.
            </div>
        <?php endif; ?>

        <?php foreach ($disponiveis as $d): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center bg-white border-0 shadow-sm mb-2 item-aluno" data-nome="<?= strtolower($d['membro_nome']) ?>">
                <div class="text-truncate me-2">
                    <div class="nome-aluno text-truncate"><?= $d['membro_nome'] ?></div>
                    <div class="info-aluno">Idade: <strong><?= $d['idade'] ?> anos</strong></div>
                </div>
                <a href="<?= url('professor/adicionar_aluno/'.$d['membro_id']) ?>"
                   class="btn btn-success btn-touch shadow-sm border-0">
                    <i class="bi bi-person-plus-fill fs-3"></i>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function filtrarAlunos() {
    const termo = document.getElementById('inputBusca').value.toLowerCase();
    const itens = document.querySelectorAll('.item-aluno');
    itens.forEach(item => {
        const nome = item.getAttribute('data-nome');
        item.style.setProperty('display', nome.includes(termo) ? 'flex' : 'none', 'important');
    });
}

function confirmarRemocao(nome, url) {
    if(confirm('Deseja remover ' + nome + ' da sua lista de alunos?')) {
        window.location.href = url;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
