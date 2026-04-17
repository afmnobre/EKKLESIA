<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= url('patrimonios') ?>">Patrimônio</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </nav>
            <h3 class="text-dark fw-bold"><?= $bem['patrimonio_bem_nome'] ?></h3>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.abrirModalMovimentacao(<?= $bem['patrimonio_bem_id'] ?>, '<?= $bem['patrimonio_bem_local_id'] ?? '' ?>')" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-left-right me-1"></i> Movimentar
            </button>
            <a href="<?= url('patrimonios/editar/'.$bem['patrimonio_bem_id']) ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Editar Dados
            </a>
            <a href="<?= url('patrimonios') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações Gerais</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Status Atual</label>
                        <?php
                            $status_cores = [
                                'ativo' => 'bg-success',
                                'manutencao' => 'bg-warning text-dark',
                                'danificado' => 'bg-danger',
                                'baixado' => 'bg-secondary'
                            ];
                            $cor = $status_cores[$bem['patrimonio_bem_status']] ?? 'bg-light text-dark';
                        ?>
                        <span class="badge <?= $cor ?>"><?= ucfirst($bem['patrimonio_bem_status']) ?></span>
                    </div>

					<div class="mb-3">
						<label class="text-muted small d-block">Código / Patrimônio</label>
						<span class="fw-bold text-primary"><?= $bem['patrimonio_bem_codigo'] ?></span>
					</div>

					<div class="mb-3">
						<label class="text-muted small d-block">Categoria</label>
						<span class="badge bg-info-subtle text-info border border-info-subtle">
							<i class="bi bi-tag me-1"></i>
							<?= $categoria ? $categoria['patrimonio_categoria_nome'] : 'Sem Categoria' ?>
						</span>
					</div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Valor de Aquisição</label>
                        <span class="fw-bold">R$ <?= number_format($bem['patrimonio_bem_valor'], 2, ',', '.') ?></span>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block">Data de Aquisição</label>
                        <span class="fw-bold"><?= $bem['patrimonio_bem_data_aquisicao'] ? date('d/m/Y', strtotime($bem['patrimonio_bem_data_aquisicao'])) : 'Não informada' ?></span>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small d-block">Descrição</label>
                        <p class="text-dark small"><?= nl2br($bem['patrimonio_bem_descricao'] ?: 'Nenhuma descrição detalhada.') ?></p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Histórico de Movimentações</h6>
                </div>
                <div class="card-body">
                    <?php if(empty($movimentacoes)): ?>
                        <div class="text-center py-3 text-muted small">Sem histórico registrado.</div>
                    <?php else: ?>
                        <div class="timeline-small">
                            <?php foreach($movimentacoes as $mov): ?>
                                <div class="mb-3 ps-3 border-start border-2 border-primary position-relative">
                                    <div class="position-absolute bg-primary rounded-circle" style="width:10px; height:10px; left:-6px; top:5px;"></div>
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-bold text-uppercase text-primary" style="font-size: 0.7rem;">
                                            <?= $mov['patrimonio_movimentacao_tipo'] ?>
                                        </small>
                                        <small class="text-muted" style="font-size: 0.65rem;">
                                            <?= date('d/m/Y', strtotime($mov['patrimonio_movimentacao_data'])) ?>
                                        </small>
                                    </div>
                                    <div class="small fw-bold text-dark">
                                        <?php if($mov['patrimonio_movimentacao_tipo'] == 'transferencia'): ?>
                                            <?= $mov['nome_origem'] ?> <i class="bi bi-arrow-right mx-1"></i> <?= $mov['nome_destino'] ?>
                                        <?php else: ?>
                                            Local: <?= $mov['nome_origem'] ?: $mov['nome_destino'] ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($mov['patrimonio_movimentacao_observacao']): ?>
                                        <div class="text-muted x-small" style="font-size: 0.75rem; font-style: italic;">
                                            "<?= $mov['patrimonio_movimentacao_observacao'] ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">Galeria de Fotos</h6>
                    <button class="btn btn-sm btn-primary" onclick="abrirModalUpload(<?= $bem['patrimonio_bem_id'] ?>, 'foto')">
                        <i class="bi bi-camera me-1"></i> Adicionar Foto
                    </button>
                </div>
                <div class="card-body">
                    <?php if(empty($fotos)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-images text-muted display-4"></i>
                            <p class="text-muted mt-2">Nenhuma foto disponível para este item.</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach($fotos as $foto): ?>
                                <?php
                                    $pathFoto = asset("uploads/{$_SESSION['usuario_igreja_id']}/patrimonios/{$bem['patrimonio_bem_id']}/fotos/{$foto['patrimonio_imagem_arquivo']}");
                                ?>
                                <div class="col-md-4 col-6">
                                    <div class="position-relative group-gallery">
                                        <a href="<?= $pathFoto ?>" target="_blank">
                                            <img src="<?= $pathFoto ?>" class="img-fluid rounded shadow-sm border" style="height: 180px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">Documentos e Notas Fiscais</h6>
                    <button class="btn btn-sm btn-outline-dark" onclick="abrirModalUpload(<?= $bem['patrimonio_bem_id'] ?>, 'doc')">
                        <i class="bi bi-plus-circle me-1"></i> Anexar Doc
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if(empty($documentos)): ?>
                            <div class="p-4 text-center text-muted small">Nenhum documento anexado.</div>
                        <?php endif; ?>

                        <?php foreach($documentos as $doc): ?>
                            <?php
                                $pathDoc = asset("uploads/{$_SESSION['usuario_igreja_id']}/patrimonios/{$bem['patrimonio_bem_id']}/documentos/{$doc['patrimonio_documento_arquivo']}");
                                $isPdf = str_contains($doc['patrimonio_documento_arquivo'], '.pdf');
                            ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="text-truncate" style="max-width: 80%;">
                                    <i class="bi <?= $isPdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-image text-primary' ?> fs-5 me-2"></i>
                                    <span class="text-dark small fw-bold"><?= $doc['patrimonio_documento_arquivo'] ?></span>
                                </div>
                                <a href="<?= $pathDoc ?>" target="_blank" class="btn btn-sm btn-light border">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/patrimonios/_modais.php'; ?>
