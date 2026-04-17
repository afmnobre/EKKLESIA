<div class="container-fluid py-4">
    <div class="d-none d-print-block mb-4">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
            <?php
                // O caminho conforme seu método uploadLogo: assets/uploads/{id}/logo/{nome_no_banco}
                $dirLogo = "assets/uploads/{$_SESSION['usuario_igreja_id']}/logo/";
                $nomeLogo = !empty($igreja['igreja_logo']) ? $igreja['igreja_logo'] : '';
                $logoPath = $dirLogo . $nomeLogo;

                $logoUrl = (!empty($nomeLogo) && file_exists($logoPath))
                    ? url($logoPath)
                    : url('assets/img/logo_ipb.png'); // Logo padrão caso não exista
            ?>
            <div class="d-flex align-items-center">
                <img src="<?= $logoUrl ?>" style="height: 80px; max-width: 200px; object-fit: contain;" class="me-3">
                <div>
                    <h4 class="mb-0 fw-bold"><?= mb_strtoupper($igreja['igreja_nome'] ?? 'Ficha de Membro') ?></h4>
                    <p class="mb-0 small text-muted"><?= $igreja['igreja_cidade'] ?? '' ?> - <?= $igreja['igreja_estado'] ?? '' ?></p>
                </div>
            </div>
            <div class="text-end">
                <h5 class="mb-0 text-secondary">EKKLESIA</h5>
                <p class="text-muted small mb-0">Emissão: <?= date('d/m/Y H:i') ?></p>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 d-print-none">
        <div class="d-flex align-items-center">
            <a href="<?= url('pesquisaMembro') ?>" class="btn btn-sm btn-light border shadow-sm me-3">
                <i class="bi bi-arrow-left me-1"></i> Voltar à Pesquisa
            </a>
            <h3 class="text-secondary mb-0 fw-bold">Perfil do Membro</h3>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Imprimir Ficha
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <?php
                            $fotoPath = "assets/uploads/{$_SESSION['usuario_igreja_id']}/membros/{$membro['membro_registro_interno']}/{$membro['membro_foto_arquivo']}";
                            $fotoUrl = (!empty($membro['membro_foto_arquivo']) && file_exists($fotoPath))
                                ? url($fotoPath)
                                : url('assets/img/default-user.png');
                        ?>
                        <img src="<?= $fotoUrl ?>" class="rounded-4 shadow-sm border" style="width: 160px; height: 160px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-<?= $membro['membro_status'] == 'ativo' ? 'success' : 'danger' ?> border border-white p-2">
                            <?= strtoupper($membro['membro_status']) ?>
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1"><?= mb_strtoupper($membro['membro_nome']) ?></h5>
                    <p class="text-muted small mb-0">Registro: #<?= $membro['membro_registro_interno'] ?></p>

                    <div class="bg-light rounded-3 p-2 mt-3">
                        <small class="text-muted d-block">Cadastro realizado em:</small>
                        <strong class="small text-dark">
                            <?= !empty($membro['membro_data_criacao']) ? date('d/m/Y H:i', strtotime($membro['membro_data_criacao'])) : '---' ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3 d-print-none">
                    <nav>
                        <div class="nav nav-tabs card-header-tabs border-bottom-0" id="nav-tab" role="tablist">
                            <button class="nav-link active fw-bold" id="nav-info-tab" data-bs-toggle="tab" data-bs-target="#nav-info" type="button">Dados Pessoais</button>
                            <button class="nav-link fw-bold" id="nav-igreja-tab" data-bs-toggle="tab" data-bs-target="#nav-igreja" type="button">Eclesiástico</button>
                            <button class="nav-link fw-bold" id="nav-hist-tab" data-bs-toggle="tab" data-bs-target="#nav-hist" type="button">Histórico</button>
                            <button class="nav-link fw-bold" id="nav-familia-tab" data-bs-toggle="tab" data-bs-target="#nav-familia" type="button">Família</button>
                        </div>
                    </nav>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="nav-info" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-muted small text-uppercase fw-bold">E-mail</label>
                                    <p class="mb-0 border-bottom pb-1"><?= $membro['membro_email'] ?: '---' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">Telefone</label>
                                    <p class="mb-0 border-bottom pb-1"><?= $membro['membro_telefone'] ?: '---' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">Nascimento</label>
                                    <p class="mb-0 border-bottom pb-1"><?= !empty($membro['membro_data_nascimento']) ? date('d/m/Y', strtotime($membro['membro_data_nascimento'])) : '---' ?></p>
                                </div>

                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">RG</label>
                                    <p class="mb-0 border-bottom pb-1"><?= $membro['membro_rg'] ?: '---' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">CPF</label>
                                    <p class="mb-0 border-bottom pb-1"><?= $membro['membro_cpf'] ?: '---' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">Estado Civil</label>
                                    <p class="mb-0 border-bottom pb-1"><?= $membro['membro_estado_civil'] ?: '---' ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-muted small text-uppercase fw-bold">Casamento</label>
                                    <p class="mb-0 border-bottom pb-1">
                                        <?= (!empty($membro['membro_data_casamento']) && $membro['membro_data_casamento'] != '0000-00-00') ? date('d/m/Y', strtotime($membro['membro_data_casamento'])) : '---' ?>
                                    </p>
                                </div>

                                <div class="col-12 mt-4">
                                    <h6 class="text-primary fw-bold mb-3 d-print-block"><i class="bi bi-geo-alt me-1"></i> Localização</h6>
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <small class="text-muted d-block">Endereço</small>
                                                <strong>
                                                    <?= $membro['membro_endereco_rua'] ?: 'Rua não informada' ?>,
                                                    <?= $membro['membro_endereco_numero'] ?: 'S/N' ?>
                                                </strong>
                                                <div class="small">
                                                    <?= $membro['membro_endereco_bairro'] ?> -
                                                    <?= $membro['membro_endereco_cidade'] ?> /
                                                    <?= $membro['membro_endereco_estado'] ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">CEP</small>
                                                <strong><?= $membro['membro_endereco_cep'] ?: '---' ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-igreja" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="text-muted small text-uppercase fw-bold">Data de Batismo</label>
                                    <p class="mb-0 border-bottom pb-1">
                                        <?= (!empty($membro['membro_data_batismo']) && $membro['membro_data_batismo'] != '0000-00-00')
                                            ? date('d/m/Y', strtotime($membro['membro_data_batismo']))
                                            : 'Não informado' ?>
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label class="text-muted small text-uppercase fw-bold">Status do Membro</label>
                                    <p class="mb-0 border-bottom pb-1">
                                        <?= strtoupper($membro['membro_status']) ?>
                                    </p>
                                </div>

                                <div class="col-12 mt-4">
                                    <label class="text-muted small text-uppercase fw-bold d-block mb-2">Cargos e Funções</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php if(!empty($cargos)): ?>
                                            <?php foreach($cargos as $c): ?>
                                                <span class="badge bg-white text-primary border border-primary px-3 py-2"><?= $c['cargo_nome'] ?></span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted italic">Nenhum cargo ativo.</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-hist" role="tabpanel">
                            <h6 class="text-primary fw-bold mb-3 d-none d-print-block">Histórico de Observações</h6>
                            <div class="timeline p-2">
                                <?php if(!empty($historicos)): foreach($historicos as $h): ?>
                                    <div class="border-start border-primary border-3 ps-3 mb-4 position-relative">
                                        <i class="bi bi-circle-fill text-primary position-absolute d-print-none" style="left: -9px; top: 0; font-size: 12px;"></i>
                                        <small class="text-primary fw-bold d-block">
                                            <?= (!empty($h['membro_historico_data'])) ? date('d/m/Y', strtotime($h['membro_historico_data'])) : '---' ?>
                                        </small>
                                        <p class="mb-0 text-dark"><?= nl2br($h['membro_historico_texto']) ?></p>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="text-center py-5 text-muted">Nenhum registro encontrado.</div>
                                <?php endif; ?>
                            </div>
                        </div>

						<div class="tab-pane fade" id="nav-familia" role="tabpanel">
							<div class="d-flex justify-content-between align-items-center mb-4">
								<h6 class="text-primary fw-bold mb-0">Vínculos Familiares</h6>
								<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalParente">
									<i class="bi bi-person-plus me-1"></i> Vincular Parente
								</button>
							</div>

							<div class="row g-3">
								<?php if(!empty($familia)): foreach($familia as $f): ?>
									<div class="col-md-6 mb-3">
										<div class="p-3 border rounded-3 bg-white shadow-sm d-flex align-items-center justify-content-between">
											<div class="d-flex align-items-center text-truncate">
												<div class="me-3">
													<div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
														<i class="bi bi-person text-secondary fs-4"></i>
													</div>
												</div>
												<div class="text-truncate">
													<small class="text-primary d-block fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
														<?= $f['parentesco_nome'] ?> </small>
													<strong class="text-dark d-block text-truncate"><?= $f['membro_nome'] ?></strong>
												</div>
											</div>
											<a href="<?= url('PesquisaMembro/perfil/'.$f['id_parente']) ?>" class="btn btn-sm btn-outline-secondary border-0">
												<i class="bi bi-arrow-right-short fs-4"></i>
											</a>
										</div>
									</div>
								<?php endforeach; else: ?>
									<div class="col-12 text-center py-4 text-muted">
										<i class="bi bi-people d-block fs-2 opacity-25"></i>
										Nenhum familiar vinculado.
									</div>
								<?php endif; ?>
							</div>
						</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalParente" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= url('pesquisaMembro/vincularParente') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Vínculo Familiar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="responsavel_id" value="<?= $membro['membro_id'] ?>">

                <div class="mb-3">
                    <label class="form-label small fw-bold">Parente</label>
                    <select name="dependente_id" class="form-select select2" required style="width: 100%">
                        <option value="">Selecione um membro...</option>
                        <?php foreach($todos_membros as $tm): ?>
                            <option value="<?= $tm['membro_id'] ?>"><?= $tm['membro_nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Grau de Parentesco:(Eu sou... do Parente Selecionado)</label>
                    <select name="grau" class="form-select" required>
                        <option value="Pai/Mãe">Pai/Mãe</option>
                        <option value="Filho(a)">Filho(a)</option>
                        <option value="Cônjuge">Cônjuge</option>
                        <option value="Irmão/Irmã">Irmão/Irmã</option>
                        <option value="Avô/Avó">Avô/Avó</option>
                        <option value="Neto(a)">Neto(a)</option>
                        <option value="Tio(a)">Tio(a)</option>
                        <option value="Sobrinho(a)">Sobrinho(a)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Salvar Vínculo</button>
            </div>
        </form>
    </div>
</div>


<style>
/* Estilos visuais para a tela */
.nav-tabs .nav-link { border: none; color: #6c757d; margin-bottom: -1px; padding: 12px 20px; }
.nav-tabs .nav-link.active { color: #0d6efd; border-bottom: 3px solid #0d6efd; background: transparent; }
.timeline { position: relative; }
.text-justify { text-align: justify; }

@media print {
    /* 1. Reset de Layout e Papel */
    @page {
        size: A4;
        margin: 1.5cm;
    }

    body {
        background: white !important;
        font-size: 11pt !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .container-fluid, .container {
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* 2. Esconde elementos desnecessários */
    #sidebar, .sidebar, #sidebar-wrapper, .navbar, header, footer, .btn, .nav-tabs,
    .d-print-none, .modal, .badge.rounded-pill {
        display: none !important;
    }

    /* 3. Força exibição de blocos de impressão */
    .d-print-block { display: block !important; }
    .card { border: none !important; shadow: none !important; width: 100% !important; }
    .card-body { padding: 0 !important; }

    /* 4. Ajuste do Cabeçalho (Foto e Nome) */
    .row {
        display: flex !important;
        flex-direction: row !important;
        width: 100% !important;
        margin: 0 !important;
    }

    /* Faz a coluna da foto e a coluna dos dados ocuparem a largura total juntas */
    .col-lg-3, .col-md-4 {
        width: 20% !important;
        float: left !important;
    }
    .col-lg-9, .col-md-8 {
        width: 80% !important;
        float: left !important;
    }

    img.rounded-4 {
        width: 120px !important;
        height: 120px !important;
        margin-bottom: 10px !important;
    }

    /* 5. Justificação e Textos */
    p, div, .tab-pane {
        text-align: justify !important;
        color: #000 !important;
    }

    .border-bottom { border-bottom: 1px solid #ccc !important; }

    /* 6. Grid Interno de Dados (2 ou 4 colunas na folha) */
    .tab-content { display: block !important; width: 100% !important; }
    .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; }

    /* Garante que os campos de dados fiquem lado a lado */
    .tab-pane .row { display: flex !important; flex-wrap: wrap !important; margin-top: 10px !important; }

    .tab-pane .col-md-6 { width: 50% !important; flex: 0 0 50% !important; }
    .tab-pane .col-md-3 { width: 25% !important; flex: 0 0 25% !important; }
    .tab-pane .col-12 { width: 100% !important; flex: 0 0 100% !important; }

    /* 7. Estilização dos Títulos de Seção */
    h6.text-primary, .h6 {
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        padding: 5px 10px !important;
        margin-top: 15px !important;
        border-left: 4px solid #0d6efd !important;
        width: 100% !important;
        display: block !important;
    }

    /* 8. Ajuste para a Timeline e Histórico */
    .timeline .border-start {
        border-left: 2px solid #0d6efd !important;
    }

    /* Evita quebrar um campo no meio da folha */
    .col-md-6, .col-md-3, .col-12, .mb-4 {
        page-break-inside: avoid !important;
    }
}
</style>
