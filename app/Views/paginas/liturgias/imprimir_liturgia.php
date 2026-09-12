<!-- Nome do arquivo: app/Views/liturgia/ver.php -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liturgia - <?= date('d/m/Y', strtotime($liturgia['igreja_liturgia_data'])) ?></title>

    <style>
        /* VARIÁVEIS PADRÃO (TEMA CLARO E FONTE NORMAL) */
        :root {
            --bg-body: #f0f2f5;
            --bg-card: #ffffff;
            --bg-subcard: #f8f9fa;
            --text-main: #1a1a1a;
            --text-muted: #666666;
            --border-color: #eeeeee;
            --border-header: #333333;
            --accent-blue: #0d6efd;
            --accent-green: #198754;
            --font-base: 1rem;
        }

        /* RESET BÁSICO */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: var(--font-base);
            line-height: 1.5;
            padding-bottom: 80px;
        }

        /* --- LÓGICA OFFLINE VIA ANCORAGEM CSS (:TARGET) --- */

        /* CONTROLE DE TAMANHO DE FONTE */
        #font-sm:target ~ .page-wrapper { --font-base: 0.85rem; }
        #font-md:target ~ .page-wrapper { --font-base: 1rem; }
        #font-lg:target ~ .page-wrapper { --font-base: 1.25rem; }
        #font-xl:target ~ .page-wrapper { --font-base: 1.5rem; }

        /* CONTROLE DE TEMA (MODO ESCURO) */
        #dark:target ~ .page-wrapper {
            --bg-body: #121212;
            --bg-card: #1e1e1e;
            --bg-subcard: #2a2a2a;
            --text-main: #e0e0e0;
            --text-muted: #a0a0a0;
            --border-color: #333333;
            --border-header: #555555;
            --accent-blue: #4d94ff;
            --accent-green: #2eca7f;
        }

        /* ELEMENTOS DUMMY DE ANCORAGEM OCULTOS */
        .anchor-state {
            display: none;
        }

        /* LAYOUT E CONTAINERS */
        .page-wrapper {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            padding: 10px;
            font-size: var(--font-base);
        }

        .print-container {
            max-width: 850px;
            margin: 10px auto;
            background: var(--bg-card);
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* CABEÇALHO */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5em;
            border-bottom: 2px solid var(--border-header);
            padding-bottom: 1em;
        }
        .logo-box img { max-height: 65px; object-fit: contain; }
        .header-title-box { text-align: center; flex-grow: 1; padding: 0 10px; }
        .igreja-nome { font-size: 1.2em; font-weight: bold; text-transform: uppercase; }
        .igreja-endereco { font-size: 0.8em; color: var(--text-muted); display: block; margin-top: 4px; }

        .header-info { text-align: center; margin-bottom: 2em; }
        .header-info h2 { font-weight: 800; text-transform: uppercase; letter-spacing: 1px; font-size: 1.5em; margin-bottom: 0.4em; }
        .date-badge {
            background: var(--bg-subcard);
            border: 1px solid var(--border-color);
            padding: 0.4em 1em;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85em;
            display: inline-block;
        }

        /* EQUIPE (DIRIGENTE E PREGADOR) */
        .staff-box {
            background: var(--bg-subcard);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1em;
            margin-bottom: 1.5em;
            display: flex;
            justify-content: space-around;
        }
        .staff-item { text-align: center; }
        .staff-label { display: block; font-size: 0.75em; text-transform: uppercase; color: var(--text-muted); font-weight: bold; }
        .staff-name { font-size: 1.1em; font-weight: 600; color: var(--text-main); }
        .staff-photo, .staff-placeholder {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--bg-card);
            margin: 0 auto 8px auto;
        }
        .staff-placeholder {
            background-color: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        /* LISTA DA LITURGIA */
        .liturgia-list { border-top: 2px solid var(--border-header); margin-top: 0.5em; }
        .item-row {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            padding: 1em 0;
            align-items: flex-start;
        }
        .item-main { flex-grow: 1; }
        .item-title { font-size: 1.1em; color: var(--text-main); font-weight: 600; }
        .item-ref { font-style: italic; color: var(--accent-blue); font-weight: 700; font-size: 0.95em; margin-left: 6px; }

        .biblia-content {
            margin-top: 0.8em;
            padding: 1em;
            background: var(--bg-subcard);
            border-left: 4px solid var(--accent-blue);
            font-size: 1em;
            line-height: 1.6;
            color: var(--text-main);
            border-radius: 0 8px 8px 0;
            white-space: pre-wrap;
        }

        /* BARRA DE CONTROLES FLUTUANTE COM LINKS DE ANCORAGEM */
        .controls-toolbar {
            position: fixed;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(30, 30, 30, 0.92);
            padding: 6px 12px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 6px;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }

        .btn-ctrl {
            background: #333333;
            color: #ffffff;
            border: 1px solid #555555;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-ctrl svg { width: 16px; height: 16px; fill: currentColor; }

        @media print {
            .controls-toolbar { display: none !important; }
            .page-wrapper { background: white !important; color: black !important; padding: 0; font-size: 12pt !important; }
            .print-container { margin: 0; padding: 0; box-shadow: none; max-width: 100%; background: white !important; }
            .staff-box { border: 1px solid #ccc; background: white !important; }
            .biblia-content { background: white !important; border-color: #333 !important; color: black !important; }
        }
    </style>
</head>
<body>

    <!-- PONTOS DE ANCORAGEM OFFLINE -->
    <div id="font-sm" class="anchor-state"></div>
    <div id="font-md" class="anchor-state"></div>
    <div id="font-lg" class="anchor-state"></div>
    <div id="font-xl" class="anchor-state"></div>
    <div id="dark" class="anchor-state"></div>
    <div id="light" class="anchor-state"></div>

    <!-- BARRA DE FERRAMENTAS FIXA -->
    <div class="controls-toolbar">
        <a href="#font-sm" class="btn-ctrl">A-</a>
        <a href="#font-md" class="btn-ctrl">A</a>
        <a href="#font-lg" class="btn-ctrl">A+</a>
        <a href="#font-xl" class="btn-ctrl">A++</a>
        <a href="#dark" class="btn-ctrl" title="Modo Escuro">
            <svg viewBox="0 0 16 16"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/></svg>
        </a>
        <a href="#light" class="btn-ctrl" title="Modo Claro">
            <svg viewBox="0 0 16 16"><path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8z"/></svg>
        </a>
    </div>

    <!-- CONTEÚDO PRINCIPAL -->
    <div class="page-wrapper">
        <div class="print-container">
            <div class="header-top">
                <div class="logo-box">
                    <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" alt="IPB">
                </div>

                <div class="header-title-box">
                    <div class="igreja-nome"><?= htmlspecialchars($liturgia['igreja_nome']) ?></div>
                    <span class="igreja-endereco">
                        <?= htmlspecialchars($liturgia['igreja_endereco'] ?? '') ?>
                    </span>
                </div>

                <div class="logo-box" style="text-align: right;">
                    <?php
                        $caminhoLogo = "assets/uploads/{$liturgia['igreja_id']}/logo/{$liturgia['igreja_logo']}";
                        if(!empty($liturgia['igreja_logo']) && file_exists($caminhoLogo)):
                    ?>
                        <img src="<?= url($caminhoLogo) ?>" alt="Logo Igreja">
                    <?php else: ?>
                        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="IPB">
                    <?php endif; ?>
                </div>
            </div>

            <div class="header-info">
                <h2><?= htmlspecialchars($liturgia['igreja_liturgia_tema'] ?: 'Ordem de Culto') ?></h2>
                <span class="date-badge">
                    <?= date('d/m/Y', strtotime($liturgia['igreja_liturgia_data'])) ?> às <?= date('H:i', strtotime($liturgia['igreja_liturgia_data'])) ?>h
                </span>
            </div>

            <div class="staff-box">
                <div class="staff-item">
                    <?php
                        $caminhoDir = "assets/uploads/{$liturgia['igreja_id']}/membros/{$liturgia['registro_dirigente']}/{$liturgia['foto_dirigente']}";
                        if(!empty($liturgia['foto_dirigente']) && file_exists($caminhoDir)): ?>
                            <img src="<?= url($caminhoDir) ?>" class="staff-photo" alt="Dirigente">
                        <?php else: ?>
                            <div class="staff-placeholder">
                                <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                            </div>
                        <?php endif; ?>

                    <small class="staff-label">Dirigente</small>
                    <span class="staff-name">
                        <?= htmlspecialchars($liturgia['nome_membro_dirigente'] ?? $liturgia['igreja_liturgia_dirigente_nome'] ?? 'A definir'); ?>
                    </span>
                </div>

                <div class="staff-item">
                    <?php
                        $caminhoPreg = "assets/uploads/{$liturgia['igreja_id']}/membros/{$liturgia['registro_pregador']}/{$liturgia['foto_pregador']}";
                        if(!empty($liturgia['foto_pregador']) && file_exists($caminhoPreg)): ?>
                            <img src="<?= url($caminhoPreg) ?>" class="staff-photo" alt="Pregador">
                        <?php else: ?>
                            <div class="staff-placeholder">
                                <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16"><path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg>
                            </div>
                        <?php endif; ?>

                    <small class="staff-label">Pregador</small>
                    <span class="staff-name">
                        <?= htmlspecialchars($liturgia['nome_membro_pregador'] ?? $liturgia['igreja_liturgia_pregador_nome'] ?? 'A definir'); ?>
                    </span>
                </div>
            </div>

            <div class="liturgia-list">
                <?php
                $listaItens = $itens ?? $liturgia['itens'] ?? [];
                foreach ($listaItens as $item):
                    $tipoLower = strtolower($item['tipo'] ?? '');
                ?>
                    <div class="item-row">
                        <div class="item-main">
                            <span class="item-title"><?= htmlspecialchars($item['desc']) ?></span>

                            <?php if(!empty($item['ref'])): ?>
                                <span class="item-ref">(<?= htmlspecialchars($item['ref']) ?>)</span>
                            <?php endif; ?>

                            <?php if(!empty($item['conteudo'])): ?>
                                <div class="biblia-content"><?= nl2br(htmlspecialchars($item['conteudo'])) ?></div>
                            <?php endif; ?>

                            <?php if($tipoLower == 'hino' && !empty($item['hino_letra'])): ?>
                                <div class="biblia-content" style="border-left-color: var(--accent-green); font-family: 'Verdana', sans-serif;">
                                    <div style="font-weight: bold; text-transform: uppercase; font-size: 0.85em; color: var(--accent-green); border-bottom: 1px solid var(--border-color); padding-bottom: 5px; margin-bottom: 8px;"><?= htmlspecialchars($item['hino_titulo']) ?></div>
                                    <div style="white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($item['hino_letra']) ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>
