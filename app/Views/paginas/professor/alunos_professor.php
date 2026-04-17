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

    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* HEADER & NAV PADRONIZADO */
    .header-ipb {
        background-color: var(--azul-ipb);
        color: white;
        padding: 12px 0;
        border-bottom: 3px solid var(--dourado-ipb);
    }
    .logo-container img { height: 45px; width: auto; }
    .header-info h1 { font-size: 0.9rem; margin: 0; font-weight: 700; text-transform: uppercase; }
    .header-info small { font-size: 0.75rem; color: #d1d1d1; }

    .nav-professor {
        background: #ffffff;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-around;
    }
    .nav-item-ebd {
        flex: 1;
        text-align: center;
        padding: 12px 5px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 600;
        border-bottom: 3px solid transparent;
    }
    .nav-item-ebd i { font-size: 1.3rem; display: block; margin-bottom: 2px; }
    .nav-item-ebd.active {
        color: var(--azul-ipb);
        border-bottom-color: var(--azul-ipb);
        background-color: #f8f9fa;
    }

    /* OTIMIZAÇÃO MOBILE & BUSCA */
    .search-box { position: sticky; top: 0; z-index: 1020; background: #f4f6f9; padding: 10px 0; }
    .search-box .form-control {
        height: 55px;
        border-radius: 15px;
        font-size: 1.1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .secao-titulo {
        font-size: 0.75rem;
        font-weight: 800;
        color: #888;
        letter-spacing: 1px;
        margin-top: 15px;
    }

    /* GRID DE ALUNOS (CARDS) */
    .grid-alunos, .grid-disponiveis {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
        gap: 12px;
    }

    .card-aluno-disponivel {
        background: #fff;
        border-radius: 15px;
        padding: 15px 10px;
        text-align: center;
        position: relative;
        border: 1px solid #eee;
        transition: transform 0.1s ease, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .card-aluno-disponivel:active {
        transform: scale(0.96);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    /* AVATAR & FOTOS */
    .avatar-card {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 10px auto;
        display: block;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .avatar-card-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #f0f2f5;
        margin: 0 auto 10px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 2rem;
        border: 3px solid #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    /* INFOS DO CARD */
    .nome-card {
        font-size: 0.88rem;
        font-weight: 700;
        color: #333;
        line-height: 1.2;
        min-height: 2.4em;
        max-height: 2.4em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 5px;
    }

    .idade-card {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 12px;
    }

    /* BOTÕES DE AÇÃO NOS CARDS */
    .btn-add-card, .btn-remove-card {
        width: 100%;
        border-radius: 10px;
        padding: 8px 0;
        font-size: 0.85rem;
        font-weight: 600;
        border-width: 2px;
        transition: 0.2s;
    }

    .btn-remove-card {
        border-color: #dc3545;
        color: #dc3545;
        background: transparent;
    }

    .btn-remove-card:hover, .btn-remove-card:active {
        background-color: #dc3545 !important;
        color: #fff !important;
    }

    /* Utilitário para o filtro de busca via JS */
    .item-aluno[style*="display: none"] {
        display: none !important;
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
    <?php $igrejaId = $_SESSION['usuario_igreja_id']; ?>
	<div class="secao-titulo mb-2 px-1 text-uppercase">Alunos Matriculados</div>

	<div class="grid-alunos" id="listaClasse">
		<?php foreach ($alunos as $a): ?>
			<div class="card-aluno-disponivel shadow-sm item-aluno" data-nome="<?= strtolower($a['membro_nome']) ?>">

				<?php if (!empty($a['foto'])): ?>
					<img src="<?= url("assets/uploads/{$igrejaId}/membros/" . $a['membro_registro_interno'] . "/" . $a['foto']) ?>"
						 class="avatar-card" alt="Foto">
				<?php else: ?>
					<div class="avatar-card-placeholder">
						<i class="bi bi-person-fill"></i>
					</div>
				<?php endif; ?>

				<div class="nome-card text-truncate-2">
					<?= $a['membro_nome'] ?>
				</div>
				<div class="idade-card">
					<span class="text-success"><i class="bi bi-check-circle-fill"></i> Matriculado</span>
				</div>

				<button type="button" class="btn btn-outline-danger btn-remove-card"
						onclick="confirmarRemocao('<?= $a['membro_nome'] ?>', '<?= url('professor/remover_aluno/'.$a['membro_id']) ?>')">
					<i class="bi bi-trash3 me-1"></i> Remover
				</button>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="secao-titulo mb-2 px-1 text-uppercase mt-4">Disponíveis (Faixa Etária)</div>

	<div class="grid-disponiveis" id="listaDisponiveis">
		<?php if(empty($disponiveis)): ?>
			<div class="text-center py-4 text-muted small bg-white border-0 shadow-sm w-100" style="grid-column: 1 / -1; border-radius: 15px;">
				Nenhum aluno da igreja nesta faixa etária sem classe.
			</div>
		<?php endif; ?>

		<?php foreach ($disponiveis as $d): ?>
			<div class="card-aluno-disponivel shadow-sm item-aluno" data-nome="<?= strtolower($d['membro_nome']) ?>">

				<?php if (!empty($d['foto'])): ?>
					<img src="<?= url("assets/uploads/{$igrejaId}/membros/" . $d['membro_registro_interno'] . "/" . $d['foto']) ?>"
						 class="avatar-card" alt="Foto">
				<?php else: ?>
					<div class="avatar-card-placeholder">
						<i class="bi bi-person-fill"></i>
					</div>
				<?php endif; ?>

				<div class="nome-card text-truncate-2">
					<?= $d['membro_nome'] ?>
				</div>
				<div class="idade-card">
					<strong><?= $d['idade'] ?></strong> anos
				</div>

				<a href="<?= url('professor/adicionar_aluno/'.$d['membro_id']) ?>"
				   class="btn btn-success btn-add-card shadow-sm">
					<i class="bi bi-person-plus-fill me-1"></i> Adicionar
				</a>
			</div>
		<?php endforeach; ?>
    </div:w
></div>

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
