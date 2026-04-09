<style>
    /* Efeito de hover para o botão verde */
    .btn-lg:hover {
        background-color: #004426 !important;
        color: white !important;
        transform: translateY(-2px);
        transition: all 0.2s;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5 text-center">

            <div class="mb-4 mt-3">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
                <h3 class="fw-bold mt-2">Cadastro Enviado!</h3>
                <p class="text-muted small">Olá <strong><?= explode(' ', $m['membro_nome'])[0] ?></strong>, recebemos seus dados.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4 text-start mb-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h6 class="mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">Confirmação de Dados</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <?php if (!empty($m['caminho_foto_pendente'])): ?>
                                <img src="<?= url($m['caminho_foto_pendente']) ?>"
                                     class="rounded-3 shadow-sm border"
                                     style="width: 70px; height: 70px; object-fit: cover;"
                                     onerror="this.src='<?= url('assets/img/user-default.png') ?>'; this.onerror=null;">
                            <?php else: ?>
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 70px; height: 70px;">
                                    <i class="bi bi-person text-muted fs-2"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="fw-bold mb-0 text-primary text-uppercase"><?= $m['membro_nome'] ?></h6>
                            <span class="badge bg-warning text-dark px-2 mt-1" style="font-size: 0.65rem;">AGUARDANDO APROVAÇÃO</span>
                        </div>
                    </div>

                    <div class="row g-3 small">
                        <div class="col-6">
                            <label class="text-muted d-block mb-0">Sexo:</label>
                            <span class="fw-bold"><?= $m['membro_genero'] ?? 'Não informado' ?></span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted d-block mb-0">Estado Civil:</label>
                            <span class="fw-bold"><?= $m['membro_estado_civil'] ?? 'Não informado' ?></span>
                        </div>

                        <div class="col-6 mt-2">
                            <label class="text-muted d-block mb-0">RG:</label>
                            <span class="fw-bold"><?= $m['membro_rg'] ?? '---' ?></span>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="text-muted d-block mb-0">CPF:</label>
                            <span class="fw-bold"><?= $m['membro_cpf'] ?? '---' ?></span>
                        </div>

                        <div class="col-12 mt-2">
                            <label class="text-muted d-block mb-0">E-mail:</label>
                            <span class="fw-bold text-truncate d-block"><?= $m['membro_email'] ?></span>
                        </div>

                        <div class="col-12 mt-3 pt-3 border-top">
                            <label class="text-muted d-block mb-0"><i class="bi bi-geo-alt-fill me-1"></i>Endereço Residencial:</label>
                            <span class="fw-bold d-block">
                                <?= $m['membro_endereco_rua'] ?>, <?= $m['membro_endereco_numero'] ?>
                            </span>
                            <div class="text-muted">
                                <?= $m['membro_endereco_bairro'] ?> — <?= $m['membro_endereco_cidade'] ?>/<?= $m['membro_endereco_estado'] ?>
                                <br><small>CEP: <?= $m['membro_endereco_cep'] ?></small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded border-start border-primary border-4">
                        <p class="small mb-0">
                            <strong>Próximo passo:</strong> Sua ficha será analisada pela secretaria. Assim que aprovada, você poderá acessar o portal com seu e-mail e senha cadastrados.
                        </p>
                    </div>
                </div>
            </div>

            <?php
                $idIgrejaRetorno = $_GET['igreja'] ?? null;
            ?>

            <div class="mt-5 text-center">
                <p class="text-muted small mb-3">Seu cadastro foi enviado para aprovação.</p>

                <?php if ($idIgrejaRetorno): ?>
                    <a href="<?= url('PortalMembro/login/' . $idIgrejaRetorno) ?>"
                       class="btn btn-lg px-5 fw-bold shadow-sm rounded-pill"
                       style="background-color: #005a32; color: white; border: none;">
                        <i class="bi bi-box-arrow-in-right me-2"></i> IR PARA TELA DE LOGIN
                    </a>
                <?php else: ?>
                    <a href="<?= url('PortalMembro/login') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        VOLTAR AO INÍCIO
                    </a>
                <?php endif; ?>
            </div>

            <div class="mt-5 pt-3 border-top">
                <img src="<?= url('assets/img/logo_ipb_completo.png') ?>" style="max-width: 130px; filter: grayscale(1); opacity: 0.6;">
            </div>
        </div>
    </div>
</div>
