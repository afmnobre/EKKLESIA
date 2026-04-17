<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-boxes me-2 text-primary"></i>Gestão de Patrimônio</h3>
        <div class="d-flex gap-2">
            <a href="<?= url('patrimonios/categorias') ?>" class="btn btn-outline-primary">
                <i class="bi bi-tags me-1"></i> Categorias
            </a>
            <a href="<?= url('patrimonios/locais') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-geo-alt me-1"></i> Locais
            </a>
            <a href="<?= url('patrimonios/novo') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Cadastrar Bem
            </a>
        </div>
    </div>

    <?php
    // Agrupando os bens por categoria para a exibição
    $bensAgrupados = [];
    foreach ($bens as $b) {
        $catNome = $b['patrimonio_categoria_nome'] ?? 'Sem Categoria';
        $bensAgrupados[$catNome][] = $b;
    }
    ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0"> <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item / Categoria</th>
                            <th>Data Aquisição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bensAgrupados)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Nenhum bem cadastrado.</td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($bensAgrupados as $categoria => $itens): ?>
                            <tr class="bg-light">
                                <td colspan="5" class="ps-4 py-2">
                                    <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">
                                        <i class="bi bi-tag-fill me-1"></i> <?= $categoria ?>
                                    </span>
                                    <small class="text-muted ms-2">(<?= count($itens) ?> itens)</small>
                                </td>
                            </tr>

                            <?php foreach ($itens as $b): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <?php
                                            $caminhoPasta = "uploads/{$_SESSION['usuario_igreja_id']}/patrimonios/{$b['patrimonio_bem_id']}/fotos/";
                                        ?>
                                        <div class="me-3">
                                            <?php if (!empty($b['foto'])): ?>
                                                <img src="<?= asset($caminhoPasta . $b['foto']) ?>"
                                                     class="rounded border shadow-sm"
                                                     width="45"
                                                     height="45"
                                                     style="object-fit: cover;"
                                                     alt="<?= $b['patrimonio_bem_nome'] ?>">
                                            <?php else: ?>
                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted"
                                                     style="width: 45px; height: 45px;"
                                                     title="Sem foto">
                                                    <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $b['patrimonio_bem_nome'] ?></div>
                                            <small class="text-muted d-block" style="font-size: 0.8rem;">
                                                <i class="bi bi-geo-alt"></i> <?= $b['patrimonio_local_nome'] ?? 'Não definido' ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $b['patrimonio_bem_data_aquisicao'] ? date('d/m/Y', strtotime($b['patrimonio_bem_data_aquisicao'])) : '-' ?></td>
                                <td class="fw-bold text-dark">R$ <?= number_format($b['patrimonio_bem_valor'], 2, ',', '.') ?></td>
                                <td>
                                    <?php
                                        $status_cores = [
                                            'ativo' => 'bg-success',
                                            'manutencao' => 'bg-warning text-dark',
                                            'danificado' => 'bg-danger',
                                            'baixado' => 'bg-secondary',
                                            'extraviado' => 'bg-dark'
                                        ];
                                        $cor = $status_cores[$b['patrimonio_bem_status']] ?? 'bg-light text-dark';
                                    ?>
                                    <span class="badge <?= $cor ?>" style="font-size: 0.7rem;"><?= strtoupper($b['patrimonio_bem_status']) ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group shadow-sm">
                                        <button class="btn btn-sm btn-white border" title="Etiqueta"
                                                onclick="gerarEtiqueta('<?= $b['patrimonio_bem_codigo'] ?>', '<?= $b['patrimonio_bem_nome'] ?>')">
                                            <i class="bi bi-qr-code text-dark"></i>
                                        </button>
                                        <button class="btn btn-sm btn-white border" title="Fotos" onclick="abrirModalUpload(<?= $b['patrimonio_bem_id'] ?>, 'foto')">
                                            <i class="bi bi-camera text-info"></i>
                                        </button>
                                        <button class="btn btn-sm btn-white border" title="Movimentar"
                                                onclick="window.abrirModalMovimentacao('<?= $b['patrimonio_bem_id'] ?>', '<?= $b['patrimonio_bem_local_id'] ?? '' ?>')">
                                            <i class="bi bi-arrow-left-right text-primary"></i>
                                        </button>
                                        <a href="<?= url('patrimonios/editar/'.$b['patrimonio_bem_id']) ?>" class="btn btn-sm btn-white border" title="Editar">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>
                                        <a href="<?= url('patrimonios/detalhes/'.$b['patrimonio_bem_id']) ?>" class="btn btn-sm btn-white border" title="Visualizar">
                                            <i class="bi bi-eye text-dark"></i>
                                        </a>
                                        <button class="btn btn-sm btn-white border text-danger"
                                                onclick="window.confirmarExclusao('<?= $b['patrimonio_bem_id'] ?>', '<?= $b['patrimonio_bem_nome'] ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/patrimonios/_modais.php'; ?>
