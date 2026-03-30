<?php
    // Lógica para definir se é edição ou cadastro
    $isEdit = isset($membro) && !empty($membro['membro_id']);
    $title = $isEdit ? 'Editar Membro' : 'Novo Cadastro de Membro';
    $action = $isEdit ? url('membros/update/' . $membro['membro_id']) : url('membros/store');
    $buttonText = $isEdit ? 'Salvar Alterações' : 'Finalizar Cadastro';
?>
<style>
    /* Força visualmente o texto em maiúsculo */
    .text-uppercase-input {
        text-transform: uppercase;
    }
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
                    <form action="<?= $action ?>" method="POST">

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
								<select name="estado_civil" class="form-select form-control-lg">
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
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase">E-mail</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0"
                                           placeholder="joao@email.com"
                                           value="<?= $isEdit ? htmlspecialchars($membro['membro_email']) : '' ?>">
                                </div>
                            </div>

							<div class="col-md-6">
								<label class="form-label fw-bold text-muted small text-uppercase">Telefone</label>
								<div class="input-group input-group-lg">
									<span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp"></i></span>
									<input type="text" name="telefone" id="telefone" class="form-control border-start-0"
										   placeholder="(00) 00000-0000" maxlength="15"
										   value="<?= $isEdit ? htmlspecialchars($membro['membro_telefone']) : '' ?>">
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

            <div class="alert alert-light border-0 shadow-sm mt-3 py-2">
                <small class="text-muted italic">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php if($isEdit): ?>
                        As alterações de endereço e foto devem ser feitas através dos botões 📍 e 📸 na lista principal.
                    <?php else: ?>
                        Após salvar, você poderá definir o endereço e a foto do membro diretamente na lista principal.
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('telefone').addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);

        // Formatação dinâmica: (xx) xxxxx-xxxx
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Garante que se o valor vier do banco, ele seja formatado ao carregar (se necessário)
    window.onload = function() {
        const input = document.getElementById('telefone');
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    };
</script>
