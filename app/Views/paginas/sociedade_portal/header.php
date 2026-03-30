<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?> - EKKLESIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/EKKLESIA/assets/img/favicon.png?v=1.1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/EKKLESIA/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/EKKLESIA/assets/css/app.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root { --cor-primaria: #003366; --cor-secundaria: #b8860b; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }

        /* Navbar Principal */
        .navbar-sociedade { background: var(--cor-primaria); padding: 10px 0; border-bottom: 4px solid var(--cor-secundaria); }
        .logo-igreja { height: 40px; filter: brightness(0) invert(1); }
        .logo-sociedade { height: 50px; width: 50px; object-fit: cover; border-radius: 50%; border: 2px solid white; background: white; }

        /* Foto do Perfil Líder */
        .foto-lider { width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 2px solid rgba(255,255,255,0.2); }

        /* Menu de Navegação */
        .nav-funcionalidades { background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .nav-link-soc { color: #555; font-weight: 600; padding: 15px 20px; border-bottom: 3px solid transparent; transition: 0.3s; display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .nav-link-soc:hover { background: #f8f9fa; color: var(--cor-primaria); }
        .nav-link-soc.active { border-bottom-color: var(--cor-secundaria); color: var(--cor-primaria); background: #f0f4f8; }

        .card-operacional { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .secao-titulo { font-size: 0.85rem; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 1px; }

        /* Foto na Lista */
        .avatar-membro { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>

<nav class="navbar-sociedade shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <img src="<?= url('assets/img/logo_ipb.png') ?>" class="logo-igreja" alt="Logo IPB">
            <div class="vr text-white opacity-25" style="height: 35px;"></div>
            <?php
                $caminhoLogo = !empty($sociedade['sociedade_logo'])
                    ? url("assets/uploads/" . $sociedade['sociedade_logo'])
                    : url("assets/img/default_sociedade.png");
            ?>
            <img src="<?= $caminhoLogo ?>" class="logo-sociedade" alt="Logo Sociedade">
            <div class="text-white d-none d-sm-block">
                <span class="d-block fw-bold fs-6 lh-1"><?= $sociedade['sociedade_nome'] ?></span>
                <small class="opacity-75" style="font-size: 0.7rem;">PAINEL DO LÍDER</small>
            </div>
        </div>

		<div class="d-flex align-items-center gap-3">
			<div class="text-white text-end d-none d-md-block">
				<small class="d-block opacity-75" style="font-size: 0.7rem;">Bem-vindo,</small>
				<span class="fw-bold" style="font-size: 0.9rem;"><?= $sociedade['membro_nome'] ?></span>
			</div>

			<?php
				// Lógica para a foto do LÍDER (usando o campo 'lider_foto' que criamos no SQL acima)
				$fotoLiderPath = !empty($sociedade['lider_foto'])
					? url("assets/uploads/{$sociedade['membro_igreja_id']}/membros/{$sociedade['membro_registro_interno']}/{$sociedade['lider_foto']}")
					: null;
			?>

			<div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; overflow: hidden; border: 2px solid rgba(255,255,255,0.2);">
				<?php if($fotoLiderPath): ?>
					<img src="<?= $fotoLiderPath ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Perfil">
				<?php else: ?>
					<i class="bi bi-person-fill text-primary fs-5"></i>
				<?php endif; ?>
			</div>

			<a href="<?= url('sociedadeLider/logout') ?>" class="text-white fs-4 ms-2" title="Sair"><i class="bi bi-box-arrow-right"></i></a>
		</div>
    </div>
</nav>

<div class="nav-funcionalidades">
    <div class="container d-flex overflow-x-auto text-nowrap">
        <a href="<?= url('sociedadeLider/dashboard') ?>" class="nav-link-soc">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= url('sociedadeLider/index') ?>" class="nav-link-soc">
            <i class="bi bi-people"></i> Membros
        </a>
        <a href="<?= url('sociedadeLider/novoEvento') ?>" class="nav-link-soc">
            <i class="bi bi-calendar-event"></i> Eventos
        </a>
        <a href="<?= url('sociedadeLider/presenca') ?>" class="nav-link-soc">
            <i class="bi bi-card-checklist"></i> Chamada/Presença
        </a>
    </div>
</div>
