<?php
    // Lógica para definir se é edição ou cadastro
    $isEdit = isset($membro) && !empty($membro['membro_id']);
    $title = $isEdit ? 'Editar Membro' : 'Novo Cadastro de Membro';
    $action = $isEdit ? url('membros/update/' . $membro['membro_id']) : url('membros/store');
    $buttonText = $isEdit ? 'Salvar Alterações' : 'Finalizar Cadastro';
?>
<style>
    .text-uppercase-input { text-transform: uppercase; }
    .bg-casamento { background-color: #fff5f8; border-color: #ffb6c1; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-secondary">👥 Gestão de Membros</h3>
        <a href="<?= url('membros') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para a Lista
        </a>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 <?= $isEdit ? 'text-warning' : 'text-primary' ?>">
                        <i class="bi <?= $isEdit ? 'bi-pencil-square' : 'bi-plus-circle' ?> me-2"></i><?= $title ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $action ?>" method="POST" id="formAdminMembro">

                        <?php if($isEdit): ?>
                            <input type="hidden" name="id" value="<?= $membro['membro_id'] ?>">
                        <?php endif; ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nome Completo</label>
                                <input type="text" name="nome" id="nome_completo"
                                   class="form-control form-control-lg text-uppercase-input"
                                   placeholder="EX: JOÃO SILVA" required
                                   value="<?= $isEdit ? htmlspecialchars(strtoupper($membro['membro_nome'])) : '' ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Gênero</label>
                                <select name="genero" class="form-select form-control-lg">
                                    <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Selecione...</option>
                                    <option value="Masculino" <?= ($isEdit && $membro['membro_genero'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                    <option value="Feminino" <?= ($isEdit && $membro['membro_genero'] == 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Estado Civil</label>
                                <select name="estado_civil" id="estado_civil" class="form-select form-control-lg" onchange="verificarCasamentoAdmin()">
                                    <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Selecione...</option>
                                    <?php
                                        $estados = ['Solteiro(a)', 'Casado(a)', 'Viúvo(a)', 'Divorciado(a)', 'Separado(a)'];
                                        foreach($estados as $est):
                                            $selected = ($isEdit && isset($membro['membro_estado_civil']) && $membro['membro_estado_civil'] == $est) ? 'selected' : '';
                                            echo "<option value=\"$est\" $selected>$est</option>";
                                        endforeach;
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-12" id="box_casamento_admin" style="display: <?= ($isEdit && $membro['membro_estado_civil'] == 'Casado(a)') ? 'block' : 'none' ?>;">
                                <div class="card card-body bg-casamento border-dashed">
                                    <label class="form-label fw-bold text-danger small text-uppercase"><i class="bi bi-heart-fill me-1"></i> Data de Casamento</label>
                                    <input type="date" name="data_casamento" class="form-control form-control-lg"
                                           value="<?= $isEdit ? $membro['membro_data_casamento'] : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">RG</label>
                                <input type="text" name="rg" id="rg" class="form-control form-control-lg"
                                       placeholder="00.000.000-0"
                                       value="<?= $isEdit ? htmlspecialchars($membro['membro_rg']) : '' ?>">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control form-control-lg"
                                       placeholder="000.000.000-00"
                                       value="<?= $isEdit ? htmlspecialchars($membro['membro_cpf']) : '' ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Telefone / WhatsApp</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="telefone" id="telefone" class="form-control border-start-0"
                                       placeholder="(00) 00000-0000" maxlength="15"
                                       value="<?= $isEdit ? htmlspecialchars($membro['membro_telefone']) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-muted small text-uppercase">E-mail</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0"
                                           placeholder="joao@email.com"
                                           value="<?= $isEdit ? htmlspecialchars($membro['membro_email']) : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">Data de Nascimento</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" name="data_nascimento" class="form-control border-start-0"
                                           value="<?= $isEdit ? $membro['membro_data_nascimento'] : '' ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase text-primary">Data de Batismo</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-primary text-white border-end-0"><i class="bi bi-droplet"></i></span>
                                    <input type="date" name="data_batismo" class="form-control border-start-0 border-primary shadow-sm"
                                           value="<?= $isEdit ? $membro['membro_data_batismo'] : '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 border-top d-flex justify-content-end">
                            <button type="submit" class="btn <?= $isEdit ? 'btn-warning' : 'btn-primary' ?> px-5 py-2 fw-bold shadow">
                                <i class="bi <?= $isEdit ? 'bi-save' : 'bi-check-circle-fill' ?> me-2"></i> <?= $buttonText ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.2.0/vanilla-masker.min.js"></script>
<script>
    // Máscaras de preenchimento
    if (document.getElementById("telefone")) VMasker(document.getElementById("telefone")).maskPattern("(99) 99999-9999");
    if (document.getElementById("cpf")) VMasker(document.getElementById("cpf")).maskPattern("999.999.999-99");
    if (document.getElementById("rg")) VMasker(document.getElementById("rg")).maskPattern("99.999.999-S");

    // Forçar Uppercase no Nome
    const nomeInput = document.getElementById('nome_completo');
    nomeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    document.getElementById('formAdminMembro').addEventListener('submit', function() {
        nomeInput.value = nomeInput.value.toUpperCase();
    });

    // Lógica para mostrar/esconder data de casamento
    function verificarCasamentoAdmin() {
        const estadoCivil = document.getElementById('estado_civil').value;
        const boxCasamento = document.getElementById('box_casamento_admin');
        if (estadoCivil === 'Casado(a)') {
            boxCasamento.style.display = 'block';
        } else {
            boxCasamento.style.display = 'none';
            const inputCasamento = boxCasamento.querySelector('input');
            if(inputCasamento) inputCasamento.value = '';
        }
    }

    window.onload = function() {
        verificarCasamentoAdmin();
    };
</script>
