<style>
    /* Estilos específicos para o documento oficial e impressão A4 */
    @media print {
        body * {
            visibility: hidden;
        }
        /* Oculta o título/cabeçalho padrão do modal se houver fora do corpo */
        .modal-header, .d-print-none {
            display: none !important;
        }
        .modal, .modal-dialog, .modal-content, .modal-content * {
            visibility: visible;
        }
        .modal {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 0;
            width: 100% !important;
        }
        .modal-dialog {
            max-width: 100% !important;
            margin: 0 !important;
        }
        .modal-content {
            border: none !important;
            box-shadow: none !important;
            padding: 5mm !important;
        }
        @page {
            size: A4;
            margin: 8mm;
        }
    }

    .documento-oficial {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #333;
    }
</style>

<div class="modal-body p-3 bg-white documento-oficial">
    <!-- Cabeçalho Oficial do Documento -->
    <div class="text-center border-bottom pb-2 mb-3">
        <img src="<?= url('assets/img/logo_ipb.png') ?>" alt="Logo IPB" style="max-height: 45px; width: auto;" class="mb-1">
        <h5 class="text-uppercase fw-bold text-dark mb-0" style="font-size: 1.1rem;"><?= htmlspecialchars($membro['igreja_nome']) ?></h5>
        <p class="text-muted small text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 1.5px;">Igreja Presbiteriana do Brasil — Ficha Cadastral Oficial de Membro</p>
    </div>

    <!-- Dados Principais e Foto -->
    <div class="card border border-dark mb-2 shadow-none rounded-0">
        <div class="card-body p-2">
            <div class="row align-items-center">
                <div class="col-md-3 text-center border-end">
                    <?php if(!empty($membro['foto_url'])): ?>
                        <img src="<?= $membro['foto_url'] ?>"
                             class="img-fluid rounded border p-1 bg-white"
                             style="width: 110px; height: 140px; object-fit: cover;"
                             alt="Foto do Membro">
                    <?php else: ?>
                        <div class="bg-light d-flex flex-column align-items-center justify-content-center rounded border mx-auto"
                             style="width: 110px; height: 140px;">
                            <i class="bi bi-person-bounding-box text-secondary" style="font-size: 2.5rem;"></i>
                            <span class="text-muted mt-1" style="font-size: 0.6rem; font-weight: bold;">SEM FOTO</span>
                        </div>
                    <?php endif; ?>

                    <button class="btn btn-sm btn-outline-secondary mt-2 w-100 d-print-none btn-acao-dinamica"
                            data-id="<?= $membro['membro_id'] ?>"
                            data-acao="foto">
                        <i class="bi bi-camera me-1"></i> Alterar Foto
                    </button>
                </div>

                <div class="col-md-9 ps-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1 text-dark fw-bold text-uppercase" style="font-size: 1rem;"><?= htmlspecialchars($membro['membro_nome']) ?></h5>
                            <span class="badge bg-light text-dark border border-dark rounded-0 px-2 py-1 mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="bi bi-hash me-1"></i>MATRÍCULA:
                                <?php
                                    $reg = $membro['membro_registro_interno'];
                                    echo (strlen($reg) > 8) ? substr($reg, 0, -10) . " / " . substr($reg, -10, 4) . " / " . substr($reg, -6, 2) . " / " . substr($reg, -4) : $reg;
                                ?>
                            </span>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            <span class="badge border border-dark text-dark rounded-0 px-2 py-0" style="font-size: 0.7rem;">
                                <?= strtoupper($membro['membro_status']) ?>
                            </span>
                            <?php if(isset($membro['membro_dizimista']) && $membro['membro_dizimista'] == 1): ?>
                                <span class="badge bg-light text-dark border border-dark rounded-0 px-2 py-0" style="font-size: 0.6rem;">
                                    DIZIMISTA
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row small mt-1 pt-2 border-top" style="font-size: 0.75rem;">
                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">E-MAIL</span>
                            <span class="text-dark"><?= htmlspecialchars($membro['membro_email'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">TELEFONE</span>
                            <span class="text-dark"><?= htmlspecialchars($membro['membro_telefone'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">CPF</span>
                            <span class="text-dark"><?= htmlspecialchars($membro['membro_cpf'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">RG</span>
                            <span class="text-dark"><?= htmlspecialchars($membro['membro_rg'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">DATA DE NASCIMENTO</span>
                            <span class="text-dark"><?= $membro['membro_data_nascimento'] ? date('d/m/Y', strtotime($membro['membro_data_nascimento'])) : '---' ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">NATURALIDADE</span>
                            <span class="text-dark text-uppercase"><?= htmlspecialchars($membro['membro_naturalidade'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">DATA DE BATISMO</span>
                            <span class="text-dark"><?= !empty($membro['membro_data_batismo']) ? date('d/m/Y', strtotime($membro['membro_data_batismo'])) : 'Não Informado' ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">PROFISSÃO DE FÉ</span>
                            <span class="text-dark fw-bold"><?= !empty($membro['membro_data_profissao_fe']) ? date('d/m/Y', strtotime($membro['membro_data_profissao_fe'])) : 'Não Informada' ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">GÊNERO</span>
                            <span class="text-dark"><?= $membro['membro_genero'] ?: '---' ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">ESTADO CIVIL</span>
                            <span class="text-dark"><?= $membro['membro_estado_civil'] ?: '---' ?></span>
                        </div>

                        <?php if (!empty($membro['membro_data_casamento'])): ?>
                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">DATA DE CASAMENTO</span>
                            <span class="text-dark"><?= date('d/m/Y', strtotime($membro['membro_data_casamento'])) ?></span>
                        </div>
                        <?php endif; ?>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">ESCOLARIDADE</span>
                            <span class="text-dark text-uppercase"><?= htmlspecialchars($membro['membro_escolaridade'] ?? '---') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">PROFISSÃO</span>
                            <span class="text-dark text-uppercase"><?= htmlspecialchars($membro['membro_profissao'] ?? 'Não Informada') ?></span>
                        </div>

                        <div class="col-6 mb-1">
                            <span class="fw-bold text-muted d-block" style="font-size: 0.65rem;">CARGO / FUNÇÃO</span>
                            <span class="text-dark fw-bold text-uppercase">
                                <?= htmlspecialchars($membro['membro_cargo'] ?? 'Membro Comum') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bloco de Endereço -->
    <div class="card border border-dark mb-2 shadow-none rounded-0">
        <div class="card-header bg-light fw-bold border-bottom border-dark py-1 text-dark small text-uppercase" style="font-size: 0.75rem;">
            <i class="bi bi-geo-alt-fill me-1"></i> Endereço Residencial
        </div>
        <div class="card-body py-1 small" style="font-size: 0.75rem;">
            <?php if(!empty($membro['membro_endereco_rua'])): ?>
                <p class="mb-0 text-dark">
                    <strong><?= htmlspecialchars($membro['membro_endereco_rua']) ?>, <?= htmlspecialchars($membro['membro_endereco_numero'] ?: 'S/N') ?></strong>
                    <?php if(!empty($membro['membro_endereco_complemento'])): ?>
                        <span>(Comp: <?= htmlspecialchars($membro['membro_endereco_complemento']) ?>)</span>
                    <?php endif; ?>
                    <span>| Bairro: <?= htmlspecialchars($membro['membro_endereco_bairro'] ?: 'Não informado') ?></span>
                    <span>| Cidade: <?= htmlspecialchars($membro['membro_endereco_cidade']) ?> - <?= $membro['membro_endereco_estado'] ?> — CEP: <?= $membro['membro_endereco_cep'] ?></span>
                </p>
            <?php else: ?>
                <p class="text-muted mb-0 fst-italic text-center">Nenhum endereço cadastrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bloco de Histórico -->
    <div class="card border border-dark shadow-none rounded-0 mb-3">
        <div class="card-header bg-light fw-bold border-bottom border-dark py-1 text-dark small text-uppercase" style="font-size: 0.75rem;">
            <i class="bi bi-journal-text me-1"></i> Histórico de Registros
        </div>
        <div class="card-body py-1 small" style="font-size: 0.75rem;">
            <?php if (!empty($membro['historicos'])): ?>
                <ul class="list-unstyled mb-0">
                    <?php foreach ($membro['historicos'] as $h): ?>
                        <li class="mb-1 pb-1 border-bottom last-border-0">
                            <span class="fw-bold text-dark">
                                <i class="bi bi-calendar3 me-1"></i> <?= date('d/m/Y H:i', strtotime($h['membro_historico_data'])) ?>:
                            </span>
                            <span class="text-dark">
                                <?= $h['membro_historico_texto'] ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted mb-0 fst-italic text-center">Nenhum histórico registrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Linha de Assinatura para Impressão Oficial -->
    <div class="row mt-4 pt-3 text-center d-none d-print-flex">
        <div class="col-6 mx-auto">
            <div class="border-top border-dark pt-1">
                <span class="small fw-bold text-dark" style="font-size: 0.75rem;">Assinatura da Secretaria / Liderança</span>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-white border-top p-3 d-print-none">
    <button type="button" class="btn btn-outline-secondary px-4 fw-bold" data-bs-dismiss="modal">Fechar Ficha</button>
    <button type="button" class="btn btn-dark px-4 fw-bold" onclick="window.print();">
        <i class="bi bi-printer me-2"></i>Imprimir Ficha (A4)
    </button>
</div>
