<div class="sidebar">

    <div class="text-center py-4 border-bottom">
        <a href="<?= url('dashboard') ?>">
            <img src="<?= url('assets/img/logo_ipb_completo.png') ?>"
                 alt="Logo IPB"
                 style="max-width: 85%; height: auto; filter: brightness(0) invert(1); opacity: 0.9;">
        </a>
    </div>

    <div class="menu">

        <a class="menu-link" data-bs-toggle="collapse" href="#igreja">⛪ Igreja</a>
        <div class="collapse submenu" id="igreja">
            <a href="<?= url('igreja') ?>">📄 Dados da Igreja</a>
            <a href="<?= url('igreja/editar') ?>">✏️ Atualizar Dados</a>
        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#membros">👥 Membros</a>
        <div class="collapse submenu" id="membros">
            <a href="<?= url('membros') ?>">📋 Membros Cadastrados</a>
            <a href="<?= url('membros/create') ?>">➕ Cadastro de Membros</a>
            <a href="<?= url('dashboardMembros') ?>">📊 Dashboard</a>
        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#sociedades">🏛️ Sociedades</a>
        <div class="collapse submenu" id="sociedades">
            <a href="<?= url('sociedades') ?>">📋 Sociedades Cadastradas</a>
            <hr class="text-white-50">
            <a href="<?= url('sociedades/orcamentos') ?>">💰 Orçamentos (em produção)</a>
            <a href="<?= url('sociedadesEventos') ?>">🎉 Eventos</a>
            <a href="<?= url('dashboardSociedades') ?>">📊 Dashboard</a>
        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#escola">📚 Escola Dominical</a>
        <div class="collapse submenu" id="escola">
            <a href="<?= url('escolaDominical') ?>">📋 Classes</a>
            <a href="<?= url('escolaDominical/configuracoes') ?>">➕ Cadastro de Classes</a>
            <a href="<?= url('escolaDominical/dashboard') ?>">📊 Dashboard</a>
        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#financeiro">💵 Financeiro</a>
        <div class="collapse submenu" id="financeiro">

            <a href="<?= url('financeiro/categorias') ?>">📂 Categorias de Contas</a>
            <a href="<?= url('financeiro/categorias/create') ?>">➕ Cadastrar Categoria</a>

            <hr class="text-white-50">

            <a href="<?= url('financeiro/contas/create') ?>">➕ Cadastro de Contas</a>
            <a href="<?= url('financeiro/contas') ?>">📋 Contas Cadastradas</a>

            <hr class="text-white-50">

            <a href="<?= url('financeiro/movimentacoes') ?>">🔄 Movimentações</a>
            <a href="<?= url('financeiro/pagamentos') ?>">💳 Pagamentos</a>

        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#patrimonios">🏠 Patrimônios</a>
        <div class="collapse submenu" id="patrimonios">

            <a href="<?= url('patrimonios') ?>">📋 Patrimônios Cadastrados</a>
            <a href="<?= url('patrimonios/create') ?>">➕ Cadastro</a>

            <hr class="text-white-50">

            <a href="<?= url('patrimonios/movimentacoes') ?>">🔄 Movimentação</a>

            <hr class="text-white-50">

            <a href="<?= url('patrimonios/locais') ?>">📍 Locais</a>
            <a href="<?= url('patrimonios/locais/create') ?>">➕ Cadastrar Local</a>

        </div>

        <a class="menu-link" data-bs-toggle="collapse" href="#documentos">📄 Documentos</a>
        <div class="collapse submenu" id="documentos">
            <a href="<?= url('documentos/categorias/create') ?>">📂 Categoria</a>
            <a href="<?= url('documentos') ?>">📋 Documentos</a>
            <a href="<?= url('documentos/create') ?>">➕ Cadastrar Documento</a>
        </div>

        <hr class="text-white-50">

        <div class="px-3 py-2 text-white-50">
            👤 <?= $_SESSION['usuario_nome'] ?? '' ?>
            <br>
            <a href="<?= url('auth/logout') ?>" class="text-white text-decoration-none">🚪 Sair</a>
        </div>

    </div>
</div>
