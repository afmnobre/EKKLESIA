<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">⛪ Informações da Instituição</h3>
        <a href="<?= url('igreja/editar') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-pencil"></i> Editar Dados
        </a>
    </div>

    <?php if (!empty($igreja)): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-primary">📄 Ficha Cadastral</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="text-muted small fw-bold text-uppercase">Nome da Igreja</label>
                            <p class="fs-5 mb-0 text-dark"><?= htmlspecialchars($igreja['igreja_nome']) ?></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">CNPJ</label>
                            <p class="mb-0 text-dark"><?= !empty($igreja['igreja_cnpj']) ? htmlspecialchars($igreja['igreja_cnpj']) : '<span class="text-muted italic">Não informado</span>' ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small fw-bold text-uppercase">Data de Registro no Sistema</label>
                            <p class="mb-0 text-dark"><?= date('d/m/Y H:i', strtotime($igreja['igreja_data_criacao'])) ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="text-muted small fw-bold text-uppercase">Endereço Sede</label>
                            <p class="mb-0 text-dark"><?= !empty($igreja['igreja_endereco']) ? htmlspecialchars($igreja['igreja_endereco']) : '<span class="text-muted italic">Não informado</span>' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white mb-3">
                <div class="card-body text-center py-5">
                    <img src="<?= url('assets/img/logo_ipb.png') ?>"
                         alt="Logo IPB"
                         class="mb-3"
                         style="max-width: 180px; height: auto; filter: brightness(0) invert(1);">

                    <h4 class="mt-2 mb-0"><?= htmlspecialchars($igreja['igreja_nome']) ?></h4>
                    <p class="small opacity-75">Sede Principal</p>
                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm small">
                <i class="bi bi-info-circle-fill me-2"></i>
                Estes dados aparecem no cabeçalho de documentos e relatórios financeiros.
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-warning border-0 shadow-sm">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        Dados da igreja não encontrados no banco de dados.
    </div>
    <?php endif; ?>
</div>
