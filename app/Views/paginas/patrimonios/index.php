<div class="container-fluid pt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark fw-bold"><i class="bi bi-boxes me-2 text-primary"></i>Gestão de Patrimônio</h3>
        <div class="d-flex gap-2">
            <a href="<?= url('patrimonios/locais') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-geo-alt me-1"></i> Locais
            </a>
            <a href="<?= url('patrimonios/novo') ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Cadastrar Bem
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Data Aquisição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($bens as $b): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
									<?php
										// Montamos o caminho relativo para a pasta de fotos do patrimônio específico
										// Lembre-se: a função asset() já adiciona o prefixo da URL até a pasta /assets/
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
											<div class="bg-light rounded border d-flex align-items-center justify-content-center"
												 style="width: 45px; height: 45px;"
												 title="Sem foto">
												<i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
											</div>
										<?php endif; ?>
									</div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= $b['patrimonio_bem_nome'] ?></div>
                                        <small class="text-muted"><?= mb_strimwidth($b['patrimonio_bem_descricao'], 0, 50, "...") ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= $b['patrimonio_bem_data_aquisicao'] ? date('d/m/Y', strtotime($b['patrimonio_bem_data_aquisicao'])) : '-' ?></td>
                            <td>R$ <?= number_format($b['patrimonio_bem_valor'], 2, ',', '.') ?></td>
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
                                <span class="badge <?= $cor ?>"><?= ucfirst($b['patrimonio_bem_status']) ?></span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-dark" title="Imprimir Etiqueta"
                                            onclick="gerarEtiqueta('<?= $b['patrimonio_bem_codigo'] ?>', '<?= $b['patrimonio_bem_nome'] ?>')">
                                        <i class="bi bi-qr-code"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="Inserir Fotos" onclick="abrirModalUpload(<?= $b['patrimonio_bem_id'] ?>, 'foto')">
                                        <i class="bi bi-camera"></i>
                                    </button>
									<button class="btn btn-sm btn-outline-primary"
											title="Movimentar"
											onclick="window.abrirModalMovimentacao('<?= $b['patrimonio_bem_id'] ?>', '<?= $b['patrimonio_bem_local_id'] ?? '' ?>')">
										<i class="bi bi-arrow-left-right"></i>
									</button>
                                    <button class="btn btn-sm btn-outline-secondary" title="Inserir Documentos" onclick="abrirModalUpload(<?= $b['patrimonio_bem_id'] ?>, 'doc')">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </button>
                                    <a href="<?= url('patrimonios/editar/'.$b['patrimonio_bem_id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= url('patrimonios/detalhes/'.$b['patrimonio_bem_id']) ?>" class="btn btn-sm btn-outline-dark" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="window.confirmarExclusao('<?= $b['patrimonio_bem_id'] ?>', '<?= $b['patrimonio_bem_nome'] ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/patrimonios/_modais.php'; ?>
