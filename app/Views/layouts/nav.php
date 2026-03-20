<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <a class="navbar-brand" href="<?= url('dashboard') ?>">
            EKKLESIA
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <!-- 🔥 MENU ALINHADO À DIREITA -->
            <ul class="navbar-nav ms-auto">

                <!-- IGREJA -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Igreja
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url('igreja') ?>">Dados da Igreja</a></li>
                        <li><a class="dropdown-item" href="<?= url('igreja/editar') ?>">Atualizar Dados</a></li>
                    </ul>
                </li>

                <!-- MEMBROS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Membros
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url('membros') ?>">Membros Cadastrados</a></li>
                        <li><a class="dropdown-item" href="<?= url('membros/create') ?>">Cadastro de Membros</a></li>
                        <li><a class="dropdown-item" href="<?= url('membros/dashboard') ?>">Dashboard</a></li>
                    </ul>
                </li>

                <!-- SOCIEDADES -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Sociedades
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url('sociedades') ?>">Sociedades Cadastradas</a></li>
                        <li><a class="dropdown-item" href="<?= url('sociedades/create') ?>">Cadastro de Sociedades</a></li>
                        <li><a class="dropdown-item" href="<?= url('sociedades/orcamentos') ?>">Orçamentos</a></li>
                        <li><a class="dropdown-item" href="<?= url('sociedades/eventos') ?>">Eventos</a></li>
                        <li><a class="dropdown-item" href="<?= url('sociedades/dashboard') ?>">Dashboard</a></li>
                    </ul>
                </li>

                <!-- ESCOLA DOMINICAL -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Escola Dominical
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url('classes') ?>">Classes Cadastradas</a></li>
                        <li><a class="dropdown-item" href="<?= url('classes/create') ?>">Cadastro de Classes</a></li>
                        <li><a class="dropdown-item" href="<?= url('classes/presencas') ?>">Presenças</a></li>
                        <li><a class="dropdown-item" href="<?= url('classes/dashboard') ?>">Dashboard</a></li>
                    </ul>
                </li>

                <!-- FINANCEIRO -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Financeiro
                    </a>
                    <ul class="dropdown-menu">

                        <li><a class="dropdown-item" href="<?= url('financeiro/categorias') ?>">Categorias de Contas</a></li>
                        <li><a class="dropdown-item" href="<?= url('financeiro/categorias/create') ?>">Cadastrar Categoria</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="<?= url('financeiro/contas/create') ?>">Cadastro de Contas</a></li>
                        <li><a class="dropdown-item" href="<?= url('financeiro/contas') ?>">Contas Cadastradas</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="<?= url('financeiro/movimentacoes') ?>">Movimentações</a></li>
                        <li><a class="dropdown-item" href="<?= url('financeiro/pagamentos') ?>">Pagamentos</a></li>

                    </ul>
                </li>

                <!-- PATRIMÔNIOS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Patrimônios
                    </a>
                    <ul class="dropdown-menu">

                        <li><a class="dropdown-item" href="<?= url('patrimonios') ?>">Patrimônios Cadastrados</a></li>
                        <li><a class="dropdown-item" href="<?= url('patrimonios/create') ?>">Cadastro</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="<?= url('patrimonios/movimentacoes') ?>">Movimentação</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="<?= url('patrimonios/locais') ?>">Locais</a></li>
                        <li><a class="dropdown-item" href="<?= url('patrimonios/locais/create') ?>">Cadastrar Local</a></li>

                    </ul>
                </li>

                <!-- DOCUMENTOS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Documentos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url('documentos/categorias/create') ?>">Categoria</a></li>
                        <li><a class="dropdown-item" href="<?= url('documentos') ?>">Documentos</a></li>
                        <li><a class="dropdown-item" href="<?= url('documentos/create') ?>">Cadastrar Documento</a></li>
                    </ul>
                </li>

                <!-- USUÁRIO -->
                <li class="nav-item d-flex align-items-center ms-3">
                    <span class="text-light me-2">
                        <?= $_SESSION['usuario_nome'] ?? '' ?>
                    </span>

                    <a href="<?= url('auth/logout') ?>" class="btn btn-outline-light btn-sm">
                        Sair
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

