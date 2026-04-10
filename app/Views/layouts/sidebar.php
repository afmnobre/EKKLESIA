<div class="sidebar">

    <div class="text-center py-4 border-bottom">
        <a href="<?= url('dashboard') ?>">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>"
                 alt="Logo IPB"
                 style="max-width: 85%; height: auto; filter: brightness(0) invert(1); opacity: 0.9;">
        </a>
    </div>

    <div class="menu mt-3">
        <?php
            // Pegamos a lista de perfis do array da sessão
            $perfisUsuario = $_SESSION['usuario_perfis'] ?? [];

            // Função auxiliar para verificar se o usuário tem o perfil ou é Admin
            function temPermissao($perfilRequerido, $listaPerfis) {
                return in_array('Admin', $listaPerfis) || in_array($perfilRequerido, $listaPerfis);
            }

            $isAdmin = in_array('Admin', $perfisUsuario);
        ?>

        <?php if (temPermissao('Secretario', $perfisUsuario)): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#igreja">⛪ Igreja</a>
            <div class="collapse submenu" id="igreja">
                <a href="<?= url('igreja') ?>">Dados da Igreja</a>
                <a href="<?= url('igreja/editar') ?>">Atualizar Dados</a>
                <a href="<?= url('mensagemDominical') ?>">Mensagens Dominicais</a>
                <a href="<?= url('liturgia') ?>">Ordem Litúrgica</a>
                <a href="<?= url('IgrejaEvento') ?>">Eventos da Igreja</a>
                <a href="<?= url('boletimSemanal') ?>">Boletim Semanal</a>
                <a href="<?= url('Calendario') ?>">Calendário</a>
            </div>

            <a class="menu-link" data-bs-toggle="collapse" href="#membros">👥 Membros</a>
            <div class="collapse submenu" id="membros">
                <a href="<?= url('membros') ?>">Listagem de Membros</a>
                <a href="<?= url('membros/create') ?>">Novo Cadastro</a>
                <a href="<?= url('dashboardMembros') ?>">Dashboard</a>
                <a href="<?= url('pesquisaMembro') ?>">Pesquisas</a>
            </div>

            <a class="menu-link" data-bs-toggle="collapse" href="#sociedades">🏛️ Sociedades</a>
            <div class="collapse submenu" id="sociedades">
                <a href="<?= url('sociedades') ?>">Listagem de Sociedades</a>
                <a href="<?= url('sociedades/orcamentos') ?>">Orçamentos</a>
                <a href="<?= url('sociedadesEventos') ?>">Eventos</a>
                <a href="<?= url('dashboardSociedades') ?>">Dashboard</a>
            </div>

            <a class="menu-link" data-bs-toggle="collapse" href="#documentos">📄 Documentos</a>
            <div class="collapse submenu" id="documentos">
                <a href="<?= url('documentos') ?>">Listagem de Documentos</a>
                <a href="<?= url('documentos/categorias') ?>">Categorias</a>
                <a href="<?= url('documentos/dashboard') ?>">Dashboard</a>
            </div>
        <?php endif; ?>

        <?php if (temPermissao('Professor', $perfisUsuario)): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#escola">📚 Escola Dominical</a>
            <div class="collapse submenu" id="escola">
                <a href="<?= url('escolaDominical') ?>">Listagem de Classes</a>
                <a href="<?= url('escolaDominical/configuracoes') ?>">Cadastro de Classes</a>
                <a href="<?= url('escolaDominical/dashboard') ?>">Dashboard</a>
            </div>
        <?php endif; ?>

        <?php if (temPermissao('Tesoureiro', $perfisUsuario)): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#financeiro">💵 Financeiro</a>
            <div class="collapse submenu" id="financeiro">
                <a href="<?= url('financeiro') ?>">Movimentações</a>
                <a href="<?= url('financeiro/categorias') ?>">Categorias</a>
                <hr class="m-1 opacity-25">
                <a href="<?= url('financeiro/contas') ?>">Contas Bancárias</a>
                <a href="<?= url('financeiro/lancamentos') ?>">Lançamentos</a>
                <hr class="m-1 opacity-25">
                <a href="<?= url('financeiro/dashboard') ?>">Dashboard</a>
                <a href="<?= url('financeiro/relatorio_membros') ?>">Ofertas e Dizimos</a>
            </div>
        <?php endif; ?>

        <?php if (temPermissao('Patrimônio', $perfisUsuario)): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#patrimonios">🏠 Patrimônios</a>
            <div class="collapse submenu" id="patrimonios">
                <a href="<?= url('patrimonios') ?>">Listagem de Patrimonios</a>
                <a href="<?= url('patrimonios/novo') ?>">Novo Cadastro</a>
                <a href="<?= url('patrimonios/locais') ?>">Locais</a>
                <a href="<?= url('patrimonios/dashboard') ?>">Dashboard</a>
            </div>
        <?php endif; ?>

        <a class="menu-link text-warning" href="<?= url('igreja/acessos') ?>" target="_blank">
            <i class="bi bi-qr-code-scan"></i> Canais de Acesso
        </a>

        <hr class="text-white-50">

        <?php if ($isAdmin): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#configuracoes">⚙️ Configurações</a>
            <div class="collapse submenu" id="configuracoes">
                <a target='_blank' href="<?= url('admin/usuarios') ?>">Gestão de Usuários</a>
                <a target='_blank' href="<?= url('admin/perfis') ?>">Perfis e Permissões</a>
                <a href="<?= url('Backup') ?>">Backup Database</a>
            </div>
            <hr class="text-white-50">
        <?php endif; ?>

        <div class="px-3 py-2 text-white-50 small">
            <div class="mb-1 fw-bold text-white"><i class="bi bi-person-circle"></i> <?= $_SESSION['usuario_nome'] ?? '' ?></div>
            <div class="mb-2 text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                <i class="bi bi-shield-lock-fill"></i>
                <?= !empty($perfisUsuario) ? implode(' | ', $perfisUsuario) : 'Visitante' ?>
            </div>
            <a href="<?= url('auth/logout') ?>" class="btn btn-outline-danger btn-sm w-100 mt-2 text-white py-0" style="font-size: 0.8rem;">
                <i class="bi bi-door-open"></i> Sair
            </a>
        </div>
    </div>
</div>
