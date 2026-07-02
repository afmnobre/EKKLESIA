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
                <a href="<?= url('patrimonios/categorias') ?>">Categorias</a>
                <a href="<?= url('patrimonios/locais') ?>">Locais</a>
                <a href="<?= url('patrimonios/dashboard') ?>">Dashboard</a>
            </div>
        <?php endif; ?>

		<?php if (temPermissao('Bibliotecário', $perfisUsuario)): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#biblioteca">📖 Biblioteca</a>
            <div class="collapse submenu" id="biblioteca">
                <a href="<?= url('biblioteca') ?>">Acervo de Livros</a>
                <a href="<?= url('biblioteca/emprestimos') ?>">Empréstimos</a>
                <a href="<?= url('biblioteca/categorias') ?>">Categorias / Temas</a>
                <a href="<?= url('biblioteca/dashboard') ?>">Dashboard</a>
                <a href="<?= url('biblioteca/imprimirEtiquetas') ?>">Cartão de Livros</a>
                <a href="<?= url('biblioteca/imprimirEtiquetasQr') ?>">Etiquetas de Livros</a>
            </div>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link text-white" href="#" data-bs-toggle="modal" data-bs-target="#modalLinkMural">
                <i class="bi bi-heart-pulse-fill text-danger me-2"></i>
                <span>Mural de Comunhão</span>
            </a>
        </li>


        <a class="menu-link text-warning" href="<?= url('igreja/acessos') ?>" target="_blank">
            <i class="bi bi-qr-code-scan"></i> Canais de Acesso
        </a>

        <hr class="text-white-50">

        <?php if ($isAdmin): ?>
            <a class="menu-link" data-bs-toggle="collapse" href="#configuracoes">⚙️ Configurações</a>
            <div class="collapse submenu" id="configuracoes">
                <a target='_blank' href="<?= url('admin/igrejas') ?>">Igrejas</a>
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



<div class="modal fade" id="modalLinkMural" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow text-dark">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-share-fill me-2 text-danger"></i>Mural de Membros Externo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small text-muted">Este link é público e **não exige login**. Copie e compartilhe no grupo da igreja para que os irmãos deixem mensagens e pedidos de oração nos avatares uns dos outros.</p>

                <?php $urlMural = full_url("muralPublico/index/" . $_SESSION['usuario_igreja_id']); ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Link do Mural para o WhatsApp</label>
                    <div class="input-group">
                        <input type="text" id="input_link_mural" class="form-control bg-light text-dark small" value="<?= $urlMural ?>" readonly>
                        <button class="btn btn-outline-primary" type="button" onclick="copiarLinkMural()">
                            <i class="bi bi-clipboard"></i> Copiar
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <a href="<?= $urlMural ?>" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3"><i class="bi bi-box-arrow-up-right me-1"></i> Visualizar Mural</a>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
function copiarLinkMural() {
    var copyText = document.getElementById("input_link_mural");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    alert("Link copiado com sucesso! Agora é só colar no WhatsApp.");
}
</script>
